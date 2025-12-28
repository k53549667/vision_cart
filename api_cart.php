<?php
/**
 * Cart API - Handle all cart operations
 * Endpoints:
 * - GET ?action=list - Get cart items
 * - POST ?action=add - Add item to cart
 * - POST ?action=update - Update item quantity
 * - DELETE ?action=remove&id={id} - Remove item from cart
 * - DELETE ?action=clear - Clear entire cart
 * - GET ?action=count - Get cart item count
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

require_once 'config.php';
require_once 'session_manager.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle preflight requests
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get session ID
$sessionId = getSessionId();

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            getCartItems($sessionId);
        } elseif ($action === 'count') {
            getCartCount($sessionId);
        } else {
            getCartItems($sessionId);
        }
        break;

    case 'POST':
        if ($action === 'add') {
            addToCart($sessionId);
        } elseif ($action === 'update') {
            updateCartItem($sessionId);
        }
        break;

    case 'DELETE':
        if ($action === 'remove' && isset($_GET['id'])) {
            removeFromCart($sessionId, $_GET['id']);
        } elseif ($action === 'clear') {
            clearCart($sessionId);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

/**
 * Get all cart items with product details
 */
function getCartItems($sessionId) {
    $sql = "SELECT c.id as cart_id, c.quantity, c.created_at as added_at,
                   p.id, p.name, p.category, p.subcategory, p.brand, p.price, 
                   p.gst, p.stock, p.image, p.description
            FROM cart c
            INNER JOIN products p ON c.product_id = p.id
            WHERE c.session_id = ?
            ORDER BY c.created_at DESC";
    
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    $total = 0;
    $totalQuantity = 0;
    
    while ($row = $result->fetch_assoc()) {
        $itemTotal = $row['price'] * $row['quantity'];
        $gstAmount = ($itemTotal * $row['gst']) / 100;
        $itemTotalWithGst = $itemTotal + $gstAmount;
        
        $items[] = [
            'cart_id' => $row['cart_id'],
            'id' => $row['id'],
            'name' => $row['name'],
            'category' => $row['category'],
            'subcategory' => $row['subcategory'],
            'brand' => $row['brand'],
            'price' => floatval($row['price']),
            'gst' => floatval($row['gst']),
            'stock' => intval($row['stock']),
            'image' => $row['image'],
            'description' => $row['description'],
            'quantity' => intval($row['quantity']),
            'item_total' => round($itemTotal, 2),
            'gst_amount' => round($gstAmount, 2),
            'item_total_with_gst' => round($itemTotalWithGst, 2),
            'added_at' => $row['added_at']
        ];
        
        $total += $itemTotalWithGst;
        $totalQuantity += $row['quantity'];
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'session_id' => $sessionId,
        'items' => $items,
        'count' => count($items),
        'total_quantity' => $totalQuantity,
        'subtotal' => round($total / 1.12, 2), // Assuming 12% GST
        'gst' => round($total - ($total / 1.12), 2),
        'total' => round($total, 2)
    ]);
}

/**
 * Get cart item count
 */
function getCartCount($sessionId) {
    $sql = "SELECT SUM(quantity) as total FROM cart WHERE session_id = ?";
    
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'count' => intval($row['total'] ?? 0)
    ]);
}

/**
 * Add item to cart
 */
function addToCart($sessionId) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['product_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Product ID required']);
        return;
    }
    
    $productId = intval($data['product_id']);
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;
    
    // Check if product exists and has stock
    $productSql = "SELECT id, name, stock FROM products WHERE id = ? AND status = 'active'";
    $conn = getDBConnection();
    $stmt = $conn->prepare($productSql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $productResult = $stmt->get_result();
    
    if ($productResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        $stmt->close();
        return;
    }
    
    $product = $productResult->fetch_assoc();
    $stmt->close();
    
    // Check if product already in cart
    $checkSql = "SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param('si', $sessionId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update quantity
        $existingItem = $result->fetch_assoc();
        $newQuantity = $existingItem['quantity'] + $quantity;
        
        // Check stock
        if ($newQuantity > $product['stock']) {
            echo json_encode([
                'success' => false, 
                'error' => 'Not enough stock available',
                'available_stock' => $product['stock']
            ]);
            $stmt->close();
            return;
        }
        
        $updateSql = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('ii', $newQuantity, $existingItem['id']);
        $updateStmt->execute();
        $updateStmt->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Cart updated',
            'product_name' => $product['name'],
            'quantity' => $newQuantity
        ]);
    } else {
        // Check stock
        if ($quantity > $product['stock']) {
            echo json_encode([
                'success' => false, 
                'error' => 'Not enough stock available',
                'available_stock' => $product['stock']
            ]);
            $stmt->close();
            return;
        }
        
        // Insert new item
        $insertSql = "INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param('sii', $sessionId, $productId, $quantity);
        $insertStmt->execute();
        $insertStmt->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart',
            'product_name' => $product['name'],
            'quantity' => $quantity
        ]);
    }
    
    $stmt->close();
    
    // Extend session
    extendSession($sessionId);
}

/**
 * Update cart item quantity
 */
function updateCartItem($sessionId) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['cart_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Cart ID and quantity required']);
        return;
    }
    
    $cartId = intval($data['cart_id']);
    $quantity = intval($data['quantity']);
    
    if ($quantity < 1) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Quantity must be at least 1']);
        return;
    }
    
    // Check stock
    $checkSql = "SELECT c.id, c.product_id, p.stock, p.name 
                 FROM cart c 
                 INNER JOIN products p ON c.product_id = p.id 
                 WHERE c.id = ? AND c.session_id = ?";
    $conn = getDBConnection();
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param('is', $cartId, $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Cart item not found']);
        $stmt->close();
        return;
    }
    
    $item = $result->fetch_assoc();
    $stmt->close();
    
    if ($quantity > $item['stock']) {
        echo json_encode([
            'success' => false, 
            'error' => 'Not enough stock available',
            'available_stock' => $item['stock']
        ]);
        return;
    }
    
    // Update quantity
    $updateSql = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ? AND session_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param('iis', $quantity, $cartId, $sessionId);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'message' => 'Cart updated',
        'product_name' => $item['name'],
        'quantity' => $quantity
    ]);
}

/**
 * Remove item from cart
 */
function removeFromCart($sessionId, $cartId) {
    $cartId = intval($cartId);
    
    $sql = "DELETE FROM cart WHERE id = ? AND session_id = ?";
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $cartId, $sessionId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Cart item not found'
        ]);
    }
    
    $stmt->close();
}

/**
 * Clear entire cart
 */
function clearCart($sessionId) {
    $sql = "DELETE FROM cart WHERE session_id = ?";
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    
    $affectedRows = $stmt->affected_rows;
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'message' => 'Cart cleared',
        'items_removed' => $affectedRows
    ]);
}
?>
