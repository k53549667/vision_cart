<?php
/**
 * Payment Verification API
 * Server-side payment verification for Razorpay
 * 
 * CRITICAL: Never expose payment secrets to frontend!
 */

session_start();

require_once '../config.php';
require_once '../includes/security_headers.php';
require_once '../includes/audit_log.php';

// Set security headers
set_api_security_headers();
set_cors_headers();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'POST':
        if ($action === 'verify') {
            verifyPayment();
        } elseif ($action === 'create-order') {
            createRazorpayOrder();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
        break;
    
    case 'GET':
        if ($action === 'config') {
            getPaymentConfig();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}

/**
 * Get public payment configuration (safe to expose)
 */
function getPaymentConfig() {
    // Only return the public key, never the secret!
    echo json_encode([
        'success' => true,
        'razorpay' => [
            'key_id' => RAZORPAY_KEY_ID,
            'name' => 'VisionKart',
            'description' => 'Premium Eyewear Purchase',
            'theme_color' => '#00bac7'
        ],
        'methods' => [
            'card' => true,
            'upi' => true,
            'netbanking' => true,
            'wallet' => true
        ]
    ]);
}

/**
 * Create Razorpay order (for payment initiation)
 * This creates an order on Razorpay's server
 */
function createRazorpayOrder() {
    // Require authentication for payment
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Authentication required']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['amount']) || !isset($data['order_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Amount and order_id required']);
        return;
    }
    
    $amount = (int)($data['amount'] * 100); // Convert to paise
    $orderId = $data['order_id'];
    $receipt = 'rcpt_' . $orderId;
    
    // Razorpay API call to create order
    $apiKey = RAZORPAY_KEY_ID;
    $apiSecret = RAZORPAY_KEY_SECRET;
    
    if (empty($apiKey) || empty($apiSecret)) {
        // Payment not configured - use COD only
        echo json_encode([
            'success' => false,
            'error' => 'Online payment not configured. Please use Cash on Delivery.',
            'cod_only' => true
        ]);
        return;
    }
    
    $orderData = [
        'amount' => $amount,
        'currency' => 'INR',
        'receipt' => $receipt,
        'notes' => [
            'order_id' => $orderId,
            'user_id' => $_SESSION['user_id']
        ]
    ];
    
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':' . $apiSecret);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($httpCode === 200 && isset($result['id'])) {
        audit_log('payment_order_created', 'payment', $orderId, [
            'razorpay_order_id' => $result['id'],
            'amount' => $amount / 100
        ]);
        
        echo json_encode([
            'success' => true,
            'razorpay_order_id' => $result['id'],
            'amount' => $amount,
            'currency' => 'INR'
        ]);
    } else {
        error_log('Razorpay order creation failed: ' . $response);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create payment order. Please try again.'
        ]);
    }
}

/**
 * Verify Razorpay payment signature
 * CRITICAL: Always verify payment server-side before marking order as paid
 */
function verifyPayment() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $requiredFields = ['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'order_id'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => "Missing required field: $field"]);
            return;
        }
    }
    
    $razorpayOrderId = $data['razorpay_order_id'];
    $razorpayPaymentId = $data['razorpay_payment_id'];
    $razorpaySignature = $data['razorpay_signature'];
    $orderId = $data['order_id'];
    
    // Generate expected signature
    $apiSecret = RAZORPAY_KEY_SECRET;
    
    if (empty($apiSecret)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Payment verification not configured']);
        return;
    }
    
    $expectedSignature = hash_hmac(
        'sha256',
        $razorpayOrderId . '|' . $razorpayPaymentId,
        $apiSecret
    );
    
    // Verify signature using constant-time comparison
    if (!hash_equals($expectedSignature, $razorpaySignature)) {
        audit_log('payment_verification_failed', 'payment', $orderId, [
            'razorpay_payment_id' => $razorpayPaymentId,
            'reason' => 'signature_mismatch'
        ]);
        
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Payment verification failed']);
        return;
    }
    
    // Payment verified - update order status
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = 'processing', 
            payment_method = 'razorpay',
            updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param('s', $orderId);
    $stmt->execute();
    $stmt->close();
    
    // Store payment details
    $stmt = $conn->prepare("
        INSERT INTO payment_transactions 
        (order_id, payment_id, payment_method, amount, status, gateway_response, created_at)
        VALUES (?, ?, 'razorpay', ?, 'completed', ?, NOW())
        ON DUPLICATE KEY UPDATE 
            status = 'completed',
            gateway_response = VALUES(gateway_response)
    ");
    
    $amount = $data['amount'] ?? 0;
    $gatewayResponse = json_encode([
        'razorpay_order_id' => $razorpayOrderId,
        'razorpay_payment_id' => $razorpayPaymentId,
        'verified' => true
    ]);
    
    $stmt->bind_param('ssds', $orderId, $razorpayPaymentId, $amount, $gatewayResponse);
    $stmt->execute();
    $stmt->close();
    
    // Audit log
    audit_log('payment_verified', 'payment', $orderId, [
        'razorpay_payment_id' => $razorpayPaymentId,
        'amount' => $amount
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Payment verified successfully',
        'order_id' => $orderId,
        'payment_id' => $razorpayPaymentId
    ]);
}

/**
 * Razorpay Webhook handler
 * Configure this URL in Razorpay dashboard for automatic payment updates
 */
function handleWebhook() {
    $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET', '');
    
    if (empty($webhookSecret)) {
        http_response_code(500);
        echo json_encode(['error' => 'Webhook not configured']);
        return;
    }
    
    $payload = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';
    
    // Verify webhook signature
    $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
    
    if (!hash_equals($expectedSignature, $signature)) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid signature']);
        return;
    }
    
    $event = json_decode($payload, true);
    $eventType = $event['event'] ?? '';
    
    switch ($eventType) {
        case 'payment.captured':
            // Payment successful
            $payment = $event['payload']['payment']['entity'];
            $orderId = $payment['notes']['order_id'] ?? null;
            
            if ($orderId) {
                // Update order status
                $sql = "UPDATE orders SET status = 'processing' WHERE id = ?";
                executeQuery($sql, [$orderId]);
                
                audit_log('webhook_payment_captured', 'payment', $orderId, $payment);
            }
            break;
            
        case 'payment.failed':
            // Payment failed
            $payment = $event['payload']['payment']['entity'];
            $orderId = $payment['notes']['order_id'] ?? null;
            
            if ($orderId) {
                audit_log('webhook_payment_failed', 'payment', $orderId, $payment);
            }
            break;
            
        case 'refund.created':
            // Refund initiated
            $refund = $event['payload']['refund']['entity'];
            audit_log('webhook_refund_created', 'refund', $refund['id'], $refund);
            break;
    }
    
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
}
