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
            getOrders();
        } elseif ($action === 'get' && isset($_GET['id'])) {
            getOrder($_GET['id']);
        } elseif ($action === 'stats') {
            getOrderStats();
        } else {
            getOrders();
        }
        break;

    case 'POST':
        if ($action === 'create') {
            createOrder();
        } elseif ($action === 'update' && isset($_GET['id'])) {
            updateOrder($_GET['id']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteOrder($_GET['id']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getOrders() {
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    $sql = "SELECT * FROM orders WHERE 1=1";
    $params = [];

    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $orders = getRows($sql, $params);
    echo json_encode($orders);
}

function getOrder($id) {
    $order = getRow("SELECT * FROM orders WHERE id = ?", [$id]);
    if ($order) {
        // Get order items
        $order['items'] = getRows("SELECT * FROM order_items WHERE order_id = ?", [$id]);
        echo json_encode($order);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
    }
}

function createOrder() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['customer_name']) || !isset($data['total_amount'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    // Generate order ID
    $order_id = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

    // Handle customer_id - if it's an email or non-numeric, set to NULL
    $customer_id = null;
    if (isset($data['customer_id']) && is_numeric($data['customer_id'])) {
        $customer_id = (int)$data['customer_id'];
    }

    $sql = "INSERT INTO orders (id, customer_id, customer_name, products, total_amount, order_date, status, payment_method, shipping_address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $order_id,
        $customer_id,
        $data['customer_name'],
        $data['products'] ?? '',
        $data['total_amount'],
        $data['order_date'] ?? date('Y-m-d'),
        $data['status'] ?? 'pending',
        $data['payment_method'] ?? '',
        $data['shipping_address'] ?? ''
    ];

    $result = executeQuery($sql, $params);

    if ($result) {
        // Insert order items if provided
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, gst, total)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                $item_params = [
                    $order_id,
                    $item['product_id'] ?? null,
                    $item['product_name'] ?? '',
                    $item['quantity'] ?? 1,
                    $item['price'] ?? 0,
                    $item['gst'] ?? 0,
                    $item['total'] ?? 0
                ];
                executeQuery($item_sql, $item_params);
            }
        }

        echo json_encode(['success' => true, 'order_id' => $order_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create order']);
    }
}

function updateOrder($id) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No data provided']);
        return;
    }

    $fields = [];
    $params = [];

    $allowed_fields = ['customer_name', 'products', 'total_amount', 'status', 'payment_method', 'shipping_address'];

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
    $sql = "UPDATE orders SET " . implode(', ', $fields) . " WHERE id = ?";

    $result = executeQuery($sql, $params);

    if ($result !== false) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update order']);
    }
}

function deleteOrder($id) {
    // Delete order items first
    executeQuery("DELETE FROM order_items WHERE order_id = ?", [$id]);

    // Delete order
    $result = executeQuery("DELETE FROM orders WHERE id = ?", [$id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete order']);
    }
}

function getOrderStats() {
    $stats = [];

    // Total orders
    $total_orders = getRow("SELECT COUNT(*) as total FROM orders");
    $stats['total_orders'] = $total_orders['total'];

    // Orders by status
    $status_stats = getRows("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
    $stats['status_breakdown'] = $status_stats;

    // Total revenue
    $revenue = getRow("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
    $stats['total_revenue'] = $revenue['total'] ?? 0;

    // Recent orders (last 30 days)
    $recent = getRow("SELECT COUNT(*) as count FROM orders WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
    $stats['recent_orders'] = $recent['count'];

    echo json_encode($stats);
}
?>