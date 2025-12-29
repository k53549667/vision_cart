<?php
/**
 * Wishlist API - Handle all wishlist operations
 * Endpoints:
 * - GET ?action=list - Get wishlist items
 * - POST ?action=add - Add item to wishlist
 * - DELETE ?action=remove&product_id={id} - Remove item from wishlist
 * - DELETE ?action=clear - Clear entire wishlist
 * - GET ?action=count - Get wishlist item count
 * - GET ?action=check&product_id={id} - Check if product is in wishlist
 */

// Suppress HTML error output for JSON API
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';
require_once 'session_manager.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Get session ID
$sessionId = getSessionId();

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            getWishlistItems($sessionId);
        } elseif ($action === 'count') {
            getWishlistCount($sessionId);
        } elseif ($action === 'check' && isset($_GET['product_id'])) {
            checkWishlistItem($sessionId, $_GET['product_id']);
        } else {
            getWishlistItems($sessionId);
        }
        break;

    case 'POST':
        if ($action === 'add') {
            addToWishlist($sessionId);
        } elseif ($action === 'toggle') {
            toggleWishlist($sessionId);
        }
        break;

    case 'DELETE':
        if ($action === 'remove' && isset($_GET['product_id'])) {
            removeFromWishlist($sessionId, $_GET['product_id']);
        } elseif ($action === 'clear') {
            clearWishlist($sessionId);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
        break;
}

/**
 * Get all wishlist items with product details
 */
function getWishlistItems($sessionId) {
    $sql = "SELECT w.id as wishlist_id, w.created_at as added_at,
                   p.id, p.name, p.category, p.subcategory, p.brand, p.price, 
                   p.original_price, p.gst, p.stock, p.image, p.description, p.status
            FROM wishlist w
            INNER JOIN products p ON w.product_id = p.id
            WHERE w.session_id = ?
            ORDER BY w.created_at DESC";
    
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    
    while ($row = $result->fetch_assoc()) {
        // Calculate discount if original price exists
        $discount = 0;
        if ($row['original_price'] && $row['original_price'] > $row['price']) {
            $discount = round((($row['original_price'] - $row['price']) / $row['original_price']) * 100);
        }
        
        $items[] = [
            'wishlist_id' => $row['wishlist_id'],
            'id' => $row['id'],
            'name' => $row['name'],
            'category' => $row['category'],
            'subcategory' => $row['subcategory'],
            'brand' => $row['brand'],
            'price' => (float)$row['price'],
            'original_price' => (float)$row['original_price'],
            'discount' => $discount,
            'gst' => (float)$row['gst'],
            'stock' => (int)$row['stock'],
            'image' => $row['image'],
            'description' => $row['description'],
            'status' => $row['status'],
            'in_stock' => $row['stock'] > 0,
            'added_at' => $row['added_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'items' => $items,
        'count' => count($items)
    ]);
}

/**
 * Get wishlist item count
 */
function getWishlistCount($sessionId) {
    $sql = "SELECT COUNT(*) as count FROM wishlist WHERE session_id = ?";
    
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'count' => (int)$row['count']
    ]);
}

/**
 * Check if a product is in wishlist
 */
function checkWishlistItem($sessionId, $productId) {
    $sql = "SELECT id FROM wishlist WHERE session_id = ? AND product_id = ?";
    
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $sessionId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode([
        'success' => true,
        'in_wishlist' => $result->num_rows > 0
    ]);
}

/**
 * Add item to wishlist
 */
function addToWishlist($sessionId) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['product_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Product ID is required']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    
    // Check if product exists
    $conn = getDBConnection();
    $checkProduct = $conn->prepare("SELECT id, name FROM products WHERE id = ?");
    $checkProduct->bind_param('i', $productId);
    $checkProduct->execute();
    $productResult = $checkProduct->get_result();
    
    if ($productResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        return;
    }
    
    $product = $productResult->fetch_assoc();
    
    // Check if already in wishlist
    $checkWishlist = $conn->prepare("SELECT id FROM wishlist WHERE session_id = ? AND product_id = ?");
    $checkWishlist->bind_param('si', $sessionId, $productId);
    $checkWishlist->execute();
    $wishlistResult = $checkWishlist->get_result();
    
    if ($wishlistResult->num_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Product already in wishlist',
            'already_exists' => true,
            'product_name' => $product['name']
        ]);
        return;
    }
    
    // Add to wishlist
    $sql = "INSERT INTO wishlist (session_id, product_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $sessionId, $productId);
    
    if ($stmt->execute()) {
        // Get updated count
        $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE session_id = ?");
        $countStmt->bind_param('s', $sessionId);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $count = $countResult->fetch_assoc()['count'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Added to wishlist',
            'wishlist_id' => $conn->insert_id,
            'product_name' => $product['name'],
            'count' => (int)$count
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to add to wishlist']);
    }
}

/**
 * Toggle item in wishlist (add if not exists, remove if exists)
 */
function toggleWishlist($sessionId) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['product_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Product ID is required']);
        return;
    }
    
    $productId = (int)$data['product_id'];
    $conn = getDBConnection();
    
    // Check if product exists
    $checkProduct = $conn->prepare("SELECT id, name FROM products WHERE id = ?");
    $checkProduct->bind_param('i', $productId);
    $checkProduct->execute();
    $productResult = $checkProduct->get_result();
    
    if ($productResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        return;
    }
    
    $product = $productResult->fetch_assoc();
    
    // Check if in wishlist
    $checkWishlist = $conn->prepare("SELECT id FROM wishlist WHERE session_id = ? AND product_id = ?");
    $checkWishlist->bind_param('si', $sessionId, $productId);
    $checkWishlist->execute();
    $wishlistResult = $checkWishlist->get_result();
    
    if ($wishlistResult->num_rows > 0) {
        // Remove from wishlist
        $deleteStmt = $conn->prepare("DELETE FROM wishlist WHERE session_id = ? AND product_id = ?");
        $deleteStmt->bind_param('si', $sessionId, $productId);
        $deleteStmt->execute();
        
        $action = 'removed';
        $in_wishlist = false;
    } else {
        // Add to wishlist
        $insertStmt = $conn->prepare("INSERT INTO wishlist (session_id, product_id) VALUES (?, ?)");
        $insertStmt->bind_param('si', $sessionId, $productId);
        $insertStmt->execute();
        
        $action = 'added';
        $in_wishlist = true;
    }
    
    // Get updated count
    $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE session_id = ?");
    $countStmt->bind_param('s', $sessionId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $count = $countResult->fetch_assoc()['count'];
    
    echo json_encode([
        'success' => true,
        'action' => $action,
        'in_wishlist' => $in_wishlist,
        'message' => $action === 'added' ? 'Added to wishlist' : 'Removed from wishlist',
        'product_name' => $product['name'],
        'count' => (int)$count
    ]);
}

/**
 * Remove item from wishlist
 */
function removeFromWishlist($sessionId, $productId) {
    $conn = getDBConnection();
    
    // Get product name for response
    $productStmt = $conn->prepare("SELECT p.name FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.session_id = ? AND w.product_id = ?");
    $productStmt->bind_param('si', $sessionId, $productId);
    $productStmt->execute();
    $productResult = $productStmt->get_result();
    $productName = $productResult->num_rows > 0 ? $productResult->fetch_assoc()['name'] : '';
    
    $sql = "DELETE FROM wishlist WHERE session_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $sessionId, $productId);
    
    if ($stmt->execute()) {
        // Get updated count
        $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE session_id = ?");
        $countStmt->bind_param('s', $sessionId);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $count = $countResult->fetch_assoc()['count'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Removed from wishlist',
            'product_name' => $productName,
            'count' => (int)$count
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to remove from wishlist']);
    }
}

/**
 * Clear entire wishlist
 */
function clearWishlist($sessionId) {
    $conn = getDBConnection();
    
    $sql = "DELETE FROM wishlist WHERE session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Wishlist cleared',
            'count' => 0
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to clear wishlist']);
    }
}
?>
