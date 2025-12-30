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
            getCustomers();
        } elseif ($action === 'get' && isset($_GET['id'])) {
            getCustomer($_GET['id']);
        } elseif ($action === 'stats') {
            getCustomerStats();
        } else {
            getCustomers();
        }
        break;

    case 'POST':
        if ($action === 'create') {
            createCustomer();
        } elseif ($action === 'update' && isset($_GET['id'])) {
            updateCustomer($_GET['id']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteCustomer($_GET['id']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getCustomers() {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Fetch from users table instead of customers table
    $sql = "SELECT u.id, 
                   CONCAT(u.first_name, ' ', u.last_name) as name,
                   u.email,
                   u.phone,
                   u.role,
                   u.status,
                   u.created_at,
                   u.last_login,
                   COUNT(DISTINCT o.id) as orders_count,
                   COALESCE(SUM(o.total_amount), 0) as total_spent,
                   DATE(u.created_at) as joined_date
            FROM users u
            LEFT JOIN orders o ON o.customer_name = CONCAT(u.first_name, ' ', u.last_name) OR o.customer_name = u.email
            WHERE 1=1";
    $params = [];

    if ($search) {
        $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
    }

    $sql .= " GROUP BY u.id ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $customers = getRows($sql, $params);
    echo json_encode($customers);
}

function getCustomer($id) {
    // Fetch from users table
    $sql = "SELECT u.id, 
                   CONCAT(u.first_name, ' ', u.last_name) as name,
                   u.email,
                   u.phone,
                   u.role,
                   u.status,
                   u.created_at,
                   u.last_login,
                   COUNT(DISTINCT o.id) as orders_count,
                   COALESCE(SUM(o.total_amount), 0) as total_spent,
                   DATE(u.created_at) as joined_date
            FROM users u
            LEFT JOIN orders o ON o.customer_name = CONCAT(u.first_name, ' ', u.last_name) OR o.customer_name = u.email
            WHERE u.id = ?
            GROUP BY u.id";
    
    $customer = getRow($sql, [$id]);
    if ($customer) {
        // Get customer's orders
        $orders = getRows("SELECT id, total_amount, order_date, status FROM orders WHERE customer_name = ? OR customer_name LIKE ? ORDER BY order_date DESC", 
            [$customer['email'], '%' . $customer['name'] . '%']);
        $customer['orders'] = $orders;
        echo json_encode($customer);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Customer not found']);
    }
}

function createCustomer() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields (email required)']);
        return;
    }

    // Check if email already exists in users table
    $existing = getRow("SELECT id FROM users WHERE email = ?", [$data['email']]);
    if ($existing) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    // Parse name into first_name and last_name
    $first_name = '';
    $last_name = '';
    if (isset($data['name'])) {
        $name_parts = explode(' ', trim($data['name']), 2);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
    } else {
        $first_name = $data['first_name'] ?? '';
        $last_name = $data['last_name'] ?? '';
    }

    // Insert into users table with customer role
    $sql = "INSERT INTO users (email, password, first_name, last_name, phone, role, status)
            VALUES (?, ?, ?, ?, ?, 'customer', 'active')";

    // Generate a random password if not provided (user would need to reset)
    $password = password_hash($data['password'] ?? bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

    $params = [
        $data['email'],
        $password,
        $first_name,
        $last_name,
        $data['phone'] ?? ''
    ];

    $result = executeQuery($sql, $params);

    if ($result) {
        echo json_encode(['success' => true, 'id' => getDBConnection()->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create customer']);
    }
}

function updateCustomer($id) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No data provided']);
        return;
    }

    // Check if email is being updated and if it conflicts
    if (isset($data['email'])) {
        $existing = getRow("SELECT id FROM users WHERE email = ? AND id != ?", [$data['email'], $id]);
        if ($existing) {
            http_response_code(409);
            echo json_encode(['error' => 'Email already exists']);
            return;
        }
    }

    $fields = [];
    $params = [];

    // Handle name field - split into first_name and last_name
    if (isset($data['name'])) {
        $name_parts = explode(' ', trim($data['name']), 2);
        $fields[] = "first_name = ?";
        $params[] = $name_parts[0];
        $fields[] = "last_name = ?";
        $params[] = isset($name_parts[1]) ? $name_parts[1] : '';
    }

    // Direct field mappings
    $allowed_fields = ['email', 'phone', 'first_name', 'last_name', 'status', 'role'];

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
    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";

    $result = executeQuery($sql, $params);

    if ($result !== false) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update customer']);
    }
}

function deleteCustomer($id) {
    // Get user info first
    $user = getRow("SELECT CONCAT(first_name, ' ', last_name) as name, email FROM users WHERE id = ?", [$id]);
    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }
    
    // Check if user has orders
    $orders_count = getRow("SELECT COUNT(*) as count FROM orders WHERE customer_name = ? OR customer_name = ?", [$user['name'], $user['email']]);
    if ($orders_count['count'] > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot delete user with existing orders']);
        return;
    }

    $result = executeQuery("DELETE FROM users WHERE id = ?", [$id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete user']);
    }
}

function getCustomerStats() {
    $stats = [];

    // Total customers (users with customer role)
    $total_customers = getRow("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
    $stats['total_customers'] = $total_customers['total'];

    // New customers this month
    $new_this_month = getRow("SELECT COUNT(*) as count FROM users WHERE role = 'customer' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    $stats['new_this_month'] = $new_this_month['count'];

    // Top customers by spending
    $top_customers = getRows("SELECT CONCAT(u.first_name, ' ', u.last_name) as name, COALESCE(SUM(o.total_amount), 0) as total_spent FROM users u LEFT JOIN orders o ON o.customer_name = CONCAT(u.first_name, ' ', u.last_name) OR o.customer_name = u.email WHERE u.role = 'customer' GROUP BY u.id ORDER BY total_spent DESC LIMIT 5");
    $stats['top_customers'] = $top_customers;

    // Average order value per customer
    $avg_order = getRow("SELECT AVG(o.total_amount) as avg FROM orders o INNER JOIN users u ON o.customer_name = CONCAT(u.first_name, ' ', u.last_name) OR o.customer_name = u.email WHERE u.role = 'customer'");
    $stats['avg_order_value'] = $avg_order['avg'] ?? 0;

    echo json_encode($stats);
}
?>