<?php
/**
 * API for Purchase Categories and Subcategories
 * Stores custom categories and subcategories added during purchase entry
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

// Create table if not exists
createTableIfNotExists();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : ''; // 'category' or 'subcategory'

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            listItems($type);
        } elseif ($action === 'all') {
            listAllItems();
        }
        break;

    case 'POST':
        if ($action === 'create') {
            createItem();
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteItem($_GET['id']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function createTableIfNotExists() {
    $sql = "CREATE TABLE IF NOT EXISTS purchase_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        type ENUM('category', 'subcategory') NOT NULL,
        parent_category VARCHAR(100) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_name_type (name, type)
    )";
    
    executeQuery($sql, []);
}

function listItems($type) {
    if (!$type || !in_array($type, ['category', 'subcategory'])) {
        // Return both if type not specified
        listAllItems();
        return;
    }
    
    $sql = "SELECT * FROM purchase_categories WHERE type = ? ORDER BY name";
    $items = getRows($sql, [$type]);
    echo json_encode($items);
}

function listAllItems() {
    $categories = getRows("SELECT name FROM purchase_categories WHERE type = 'category' ORDER BY name", []);
    $subcategories = getRows("SELECT name, parent_category FROM purchase_categories WHERE type = 'subcategory' ORDER BY name", []);
    
    echo json_encode([
        'categories' => array_column($categories, 'name'),
        'subcategories' => array_column($subcategories, 'name')
    ]);
}

function createItem() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['name']) || !isset($data['type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and type are required']);
        return;
    }
    
    $name = trim($data['name']);
    $type = $data['type'];
    $parentCategory = isset($data['parent_category']) ? $data['parent_category'] : null;
    
    if (!in_array($type, ['category', 'subcategory'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid type. Must be "category" or "subcategory"']);
        return;
    }
    
    // Check if already exists
    $existing = getRow("SELECT id FROM purchase_categories WHERE name = ? AND type = ?", [$name, $type]);
    if ($existing) {
        // Already exists, just return success
        echo json_encode(['success' => true, 'id' => $existing['id'], 'message' => 'Already exists']);
        return;
    }
    
    $sql = "INSERT INTO purchase_categories (name, type, parent_category) VALUES (?, ?, ?)";
    $result = executeQuery($sql, [$name, $type, $parentCategory]);
    
    if ($result) {
        echo json_encode(['success' => true, 'id' => getDBConnection()->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create item']);
    }
}

function deleteItem($id) {
    $result = executeQuery("DELETE FROM purchase_categories WHERE id = ?", [$id]);
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete item']);
    }
}
?>
