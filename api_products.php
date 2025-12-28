<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            getProducts();
        } elseif ($action === 'get' && isset($_GET['id'])) {
            getProduct($_GET['id']);
        } elseif ($action === 'categories') {
            getCategories();
        } else {
            getProducts();
        }
        break;

    case 'POST':
        if ($action === 'create') {
            createProduct();
        } elseif ($action === 'update' && isset($_GET['id'])) {
            updateProduct($_GET['id']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteProduct($_GET['id']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getProducts() {
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : 'active';

    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];

    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY created_at DESC";

    $products = getRows($sql, $params);
    echo json_encode($products);
}

function getProduct($id) {
    $product = getRow("SELECT * FROM products WHERE id = ?", [$id]);
    if ($product) {
        echo json_encode($product);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
}

function createProduct() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['name']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $sql = "INSERT INTO products (name, category, subcategory, frametype, hsn, brand, price, original_price, gst, stock, color, status, image, video, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $data['name'],
        $data['category'] ?? '',
        $data['subcategory'] ?? '',
        $data['frametype'] ?? '',
        $data['hsn'] ?? '',
        $data['brand'] ?? '',
        $data['price'],
        $data['originalPrice'] ?? 0.00,
        $data['gst'] ?? 12.00,
        $data['stock'] ?? 0,
        $data['color'] ?? '',
        $data['status'] ?? 'active',
        $data['image'] ?? '',
        $data['video'] ?? '',
        $data['description'] ?? ''
    ];

    $result = executeQuery($sql, $params);

    if ($result) {
        // Update category product count
        if (isset($data['subcategory']) && !empty($data['subcategory'])) {
            executeQuery("UPDATE categories SET products_count = products_count + 1 WHERE name = ?", [$data['subcategory']]);
        }

        echo json_encode(['success' => true, 'id' => getDBConnection()->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create product']);
    }
}

function updateProduct($id) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No data provided']);
        return;
    }

    // Get current product data for category count updates
    $currentProduct = getRow("SELECT subcategory FROM products WHERE id = ?", [$id]);
    $oldSubcategory = $currentProduct ? $currentProduct['subcategory'] : '';
    $newSubcategory = isset($data['subcategory']) ? $data['subcategory'] : $oldSubcategory;

    $fields = [];
    $params = [];

    $allowed_fields = ['name', 'category', 'subcategory', 'frametype', 'hsn', 'brand', 'price', 'original_price', 'gst', 'stock', 'color', 'status', 'image', 'video', 'description'];

    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = ?";
            $params[] = $data[$field];
        }
    }
    
    // Handle originalPrice -> original_price mapping
    if (isset($data['originalPrice']) && !isset($data['original_price'])) {
        $fields[] = "original_price = ?";
        $params[] = $data['originalPrice'];
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        return;
    }

    $params[] = $id;
    $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";

    $result = executeQuery($sql, $params);

    if ($result !== false) {
        // Update category product counts if subcategory changed
        if ($oldSubcategory !== $newSubcategory) {
            if (!empty($oldSubcategory)) {
                executeQuery("UPDATE categories SET products_count = GREATEST(products_count - 1, 0) WHERE name = ?", [$oldSubcategory]);
            }
            if (!empty($newSubcategory)) {
                executeQuery("UPDATE categories SET products_count = products_count + 1 WHERE name = ?", [$newSubcategory]);
            }
        }

        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update product']);
    }
}

function deleteProduct($id) {
    // Get product subcategory before deletion
    $product = getRow("SELECT subcategory FROM products WHERE id = ?", [$id]);

    $result = executeQuery("DELETE FROM products WHERE id = ?", [$id]);

    if ($result) {
        // Update category product count
        if ($product && !empty($product['subcategory'])) {
            executeQuery("UPDATE categories SET products_count = GREATEST(products_count - 1, 0) WHERE name = ?", [$product['subcategory']]);
        }

        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete product']);
    }
}

function getCategories() {
    $categories = getRows("SELECT * FROM categories ORDER BY name");
    echo json_encode($categories);
}
?>