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

    $sql = "SELECT * FROM customers WHERE 1=1";
    $params = [];

    if ($search) {
        $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
    }

    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $customers = getRows($sql, $params);
    echo json_encode($customers);
}

function getCustomer($id) {
    $customer = getRow("SELECT * FROM customers WHERE id = ?", [$id]);
    if ($customer) {
        // Get customer's orders
        $orders = getRows("SELECT id, total_amount, order_date, status FROM orders WHERE customer_id = ? ORDER BY order_date DESC", [$id]);
        $customer['orders'] = $orders;
        echo json_encode($customer);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Customer not found']);
    }
}

function createCustomer() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['name']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    // Check if email already exists
    $existing = getRow("SELECT id FROM customers WHERE email = ?", [$data['email']]);
    if ($existing) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    $sql = "INSERT INTO customers (name, email, phone, orders_count, total_spent, joined_date)
            VALUES (?, ?, ?, ?, ?, ?)";

    $params = [
        $data['name'],
        $data['email'],
        $data['phone'] ?? '',
        $data['orders_count'] ?? 0,
        $data['total_spent'] ?? 0.00,
        $data['joined_date'] ?? date('Y-m-d')
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
        $existing = getRow("SELECT id FROM customers WHERE email = ? AND id != ?", [$data['email'], $id]);
        if ($existing) {
            http_response_code(409);
            echo json_encode(['error' => 'Email already exists']);
            return;
        }
    }

    $fields = [];
    $params = [];

    $allowed_fields = ['name', 'email', 'phone', 'orders_count', 'total_spent'];

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
    $sql = "UPDATE customers SET " . implode(', ', $fields) . " WHERE id = ?";

    $result = executeQuery($sql, $params);

    if ($result !== false) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update customer']);
    }
}

function deleteCustomer($id) {
    // Check if customer has orders
    $orders_count = getRow("SELECT COUNT(*) as count FROM orders WHERE customer_id = ?", [$id]);
    if ($orders_count['count'] > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot delete customer with existing orders']);
        return;
    }

    $result = executeQuery("DELETE FROM customers WHERE id = ?", [$id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete customer']);
    }
}

function getCustomerStats() {
    $stats = [];

    // Total customers
    $total_customers = getRow("SELECT COUNT(*) as total FROM customers");
    $stats['total_customers'] = $total_customers['total'];

    // New customers this month
    $new_this_month = getRow("SELECT COUNT(*) as count FROM customers WHERE MONTH(joined_date) = MONTH(CURDATE()) AND YEAR(joined_date) = YEAR(CURDATE())");
    $stats['new_this_month'] = $new_this_month['count'];

    // Top customers by spending
    $top_customers = getRows("SELECT name, total_spent FROM customers ORDER BY total_spent DESC LIMIT 5");
    $stats['top_customers'] = $top_customers;

    // Average order value per customer
    $avg_order = getRow("SELECT AVG(total_spent/orders_count) as avg FROM customers WHERE orders_count > 0");
    $stats['avg_order_value'] = $avg_order['avg'] ?? 0;

    echo json_encode($stats);
}
?>