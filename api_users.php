<?php
/**
 * VisionKart User Management API
 * Handles user profile, addresses, and preferences
 */

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'visionkart_db');

// Create PDO connection
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Check if user is authenticated for most operations
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit();
    }
}

switch ($method) {
    case 'GET':
        if ($action === 'check-session') {
            // This doesn't require auth - just check if session exists
            echo json_encode([
                'success' => true,
                'authenticated' => isset($_SESSION['user_id']),
                'user_id' => $_SESSION['user_id'] ?? null
            ]);
            exit();
        }
        requireAuth();
        if ($action === 'profile') {
            getProfile();
        } elseif ($action === 'addresses') {
            getAddresses();
        } elseif ($action === 'address' && isset($_GET['id'])) {
            getAddress($_GET['id']);
        } elseif ($action === 'orders') {
            getUserOrders();
        } elseif ($action === 'stats') {
            getUserStats();
        } else {
            getProfile();
        }
        break;

    case 'POST':
        requireAuth();
        if ($action === 'update-profile') {
            updateProfile();
        } elseif ($action === 'add-address') {
            addAddress();
        } elseif ($action === 'update-address' && isset($_GET['id'])) {
            updateAddress($_GET['id']);
        } elseif ($action === 'set-default-address' && isset($_GET['id'])) {
            setDefaultAddress($_GET['id']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    case 'DELETE':
        requireAuth();
        if ($action === 'address' && isset($_GET['id'])) {
            deleteAddress($_GET['id']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}

/**
 * Get user profile
 */
function getProfile() {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, email, first_name, last_name, phone, role, email_verified, 
                               created_at, last_login, status 
                               FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'full_name' => $user['first_name'] . ' ' . $user['last_name'],
                    'phone' => $user['phone'],
                    'role' => $user['role'],
                    'email_verified' => (bool)$user['email_verified'],
                    'status' => $user['status'],
                    'created_at' => $user['created_at'],
                    'last_login' => $user['last_login']
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Update user profile
 */
function updateProfile() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $allowedFields = ['first_name', 'last_name', 'phone'];
    $updates = [];
    $values = [];
    
    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $values[] = trim($data[$field]);
        }
    }
    
    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        return;
    }
    
    $values[] = $_SESSION['user_id'];
    
    try {
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
        
        // Update session if name changed
        if (isset($data['first_name']) || isset($data['last_name'])) {
            $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        }
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Get all user addresses
 */
function getAddresses() {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'addresses' => $addresses
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Get single address
 */
function getAddress($addressId) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM user_addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $_SESSION['user_id']]);
        $address = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($address) {
            echo json_encode([
                'success' => true,
                'address' => $address
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Address not found']);
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Add new address
 */
function addAddress() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $required = ['full_name', 'phone', 'address_line1', 'city', 'state', 'postal_code'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
            return;
        }
    }
    
    try {
        // If this is first address or marked as default, set it as default
        $isDefault = isset($data['is_default']) ? (int)$data['is_default'] : 0;
        
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user_addresses WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $addressCount = $stmt->fetchColumn();
        
        if ($addressCount == 0) {
            $isDefault = 1;
        }
        
        // If setting as default, unset other defaults
        if ($isDefault) {
            $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        }
        
        $stmt = $conn->prepare("INSERT INTO user_addresses 
                               (user_id, address_type, full_name, phone, address_line1, address_line2, 
                                city, state, postal_code, country, is_default) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $data['address_type'] ?? 'home',
            $data['full_name'],
            $data['phone'],
            $data['address_line1'],
            $data['address_line2'] ?? null,
            $data['city'],
            $data['state'],
            $data['postal_code'],
            $data['country'] ?? 'India',
            $isDefault
        ]);
        
        $addressId = $conn->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Address added successfully',
            'address_id' => $addressId
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Update address
 */
function updateAddress($addressId) {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        // Verify ownership
        $stmt = $conn->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $_SESSION['user_id']]);
        
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Address not found']);
            return;
        }
        
        $allowedFields = ['address_type', 'full_name', 'phone', 'address_line1', 'address_line2', 
                         'city', 'state', 'postal_code', 'country'];
        $updates = [];
        $values = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No fields to update']);
            return;
        }
        
        $values[] = $addressId;
        $values[] = $_SESSION['user_id'];
        
        $sql = "UPDATE user_addresses SET " . implode(', ', $updates) . " WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
        
        echo json_encode(['success' => true, 'message' => 'Address updated successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Set default address
 */
function setDefaultAddress($addressId) {
    global $conn;
    
    try {
        // Verify ownership
        $stmt = $conn->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $_SESSION['user_id']]);
        
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Address not found']);
            return;
        }
        
        // Unset all defaults
        $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        // Set new default
        $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $_SESSION['user_id']]);
        
        echo json_encode(['success' => true, 'message' => 'Default address updated']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Delete address
 */
function deleteAddress($addressId) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$addressId, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Address deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Address not found']);
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Get user orders
 */
function getUserOrders() {
    global $conn;
    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    try {
        $stmt = $conn->prepare("SELECT o.*, COUNT(oi.id) as item_count 
                               FROM orders o 
                               LEFT JOIN order_items oi ON o.id = oi.order_id 
                               WHERE o.user_id = ? 
                               GROUP BY o.id 
                               ORDER BY o.created_at DESC 
                               LIMIT ? OFFSET ?");
        $stmt->execute([$_SESSION['user_id'], $limit, $offset]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $totalCount = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'orders' => $orders,
            'total' => (int)$totalCount,
            'limit' => $limit,
            'offset' => $offset
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Get user statistics
 */
function getUserStats() {
    global $conn;
    
    try {
        // Total orders
        $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $totalOrders = $stmt->fetchColumn();
        
        // Total spent
        $stmt = $conn->prepare("SELECT SUM(total_amount) FROM orders WHERE user_id = ? AND status != 'cancelled'");
        $stmt->execute([$_SESSION['user_id']]);
        $totalSpent = $stmt->fetchColumn() ?? 0;
        
        // Pending orders
        $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status IN ('pending', 'processing')");
        $stmt->execute([$_SESSION['user_id']]);
        $pendingOrders = $stmt->fetchColumn();
        
        // Wishlist count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE session_id IN 
                               (SELECT session_id FROM user_sessions WHERE user_id = ?)");
        $stmt->execute([$_SESSION['user_id']]);
        $wishlistCount = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'stats' => [
                'total_orders' => (int)$totalOrders,
                'total_spent' => (float)$totalSpent,
                'pending_orders' => (int)$pendingOrders,
                'wishlist_count' => (int)$wishlistCount
            ]
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
