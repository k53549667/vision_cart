<?php
// Suppress HTML error output for JSON API
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            getCategories();
        } elseif ($action === 'get' && isset($_GET['id'])) {
            getCategory($_GET['id']);
        } else {
            getCategories();
        }
        break;

    case 'POST':
        if ($action === 'create') {
            createCategory();
        } elseif ($action === 'update' && isset($_GET['id'])) {
            updateCategory($_GET['id']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteCategory($_GET['id']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getCategories() {
    // Get categories with dynamic product count from products table
    // Categories table stores frame shapes (Round, Cat-Eye, etc.) which matches products.subcategory
    $sql = "SELECT c.*, 
            (SELECT COUNT(*) FROM products p WHERE LOWER(p.subcategory) = LOWER(c.name)) as products_count,
            (SELECT COUNT(*) FROM order_items oi 
             INNER JOIN products p ON oi.product_id = p.id 
             WHERE LOWER(p.subcategory) = LOWER(c.name)) as orders_count
            FROM categories c 
            ORDER BY c.name";
    $categories = getRows($sql);
    echo json_encode($categories);
}

function getCategory($id) {
    $category = getRow("SELECT * FROM categories WHERE id = ?", [$id]);
    if ($category) {
        echo json_encode($category);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Category not found']);
    }
}

function createCategory() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['name'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Category name is required']);
        return;
    }

    // Check if category already exists
    $existing = getRow("SELECT id FROM categories WHERE name = ?", [$data['name']]);
    if ($existing) {
        http_response_code(409);
        echo json_encode(['error' => 'Category already exists']);
        return;
    }

    $sql = "INSERT INTO categories (name, icon, products_count)
            VALUES (?, ?, ?)";

    $params = [
        $data['name'],
        $data['icon'] ?? 'fa-tag',
        $data['products_count'] ?? 0
    ];

    $result = executeQuery($sql, $params);

    if ($result) {
        echo json_encode(['success' => true, 'id' => getDBConnection()->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create category']);
    }
}

function updateCategory($id) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No data provided']);
        return;
    }

    // Check if name is being updated and if it conflicts
    if (isset($data['name'])) {
        $existing = getRow("SELECT id FROM categories WHERE name = ? AND id != ?", [$data['name'], $id]);
        if ($existing) {
            http_response_code(409);
            echo json_encode(['error' => 'Category name already exists']);
            return;
        }
    }

    $fields = [];
    $params = [];

    $allowed_fields = ['name', 'icon', 'products_count'];

    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = ?";
            $params[] = $data[$field];
        }
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        return;
    }

    $params[] = $id;
    $sql = "UPDATE categories SET " . implode(', ', $fields) . " WHERE id = ?";

    $result = executeQuery($sql, $params);

    if ($result !== false) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update category']);
    }
}

function deleteCategory($id) {
    // Check if category has products
    $products_count = getRow("SELECT COUNT(*) as count FROM products WHERE category = (SELECT name FROM categories WHERE id = ?)", [$id]);
    if ($products_count['count'] > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot delete category with existing products']);
        return;
    }

    $result = executeQuery("DELETE FROM categories WHERE id = ?", [$id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete category']);
    }
}
?>