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
            getPurchases();
        } elseif ($action === 'get' && isset($_GET['id'])) {
            getPurchase($_GET['id']);
        } elseif ($action === 'stats') {
            getPurchaseStats();
        } else {
            getPurchases();
        }
        break;

    case 'POST':
        if ($action === 'create') {
            createPurchase();
        } elseif ($action === 'update' && isset($_GET['id'])) {
            updatePurchase($_GET['id']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deletePurchase($_GET['id']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getPurchases() {
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    $sql = "SELECT * FROM purchases WHERE 1=1";
    $params = [];

    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $purchases = getRows($sql, $params);
    echo json_encode($purchases);
}

function getPurchase($id) {
    $purchase = getRow("SELECT * FROM purchases WHERE id = ?", [$id]);
    if ($purchase) {
        echo json_encode($purchase);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Purchase not found']);
    }
}

function createPurchase() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['supplier'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    // Check if this is new multi-product format or old single product format
    if (isset($data['items'])) {
        // New multi-product format
        $sql = "INSERT INTO purchases (
            supplier, supplier_phone, city,
            items, total_items,
            subtotal, gst_amount, total_amount,
            payment_method, invoice_number,
            purchase_date, status, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['supplier'],
            $data['supplier_phone'] ?? null,
            $data['city'] ?? null,
            $data['items'],  // JSON string of items
            $data['total_items'] ?? 0,
            $data['subtotal'] ?? 0,
            $data['total_gst'] ?? 0,  // Maps to gst_amount column
            $data['total_amount'] ?? 0,
            $data['payment_method'] ?? 'Cash',
            $data['invoice_number'] ?? null,
            $data['purchase_date'] ?? date('Y-m-d'),
            $data['status'] ?? 'pending',
            $data['notes'] ?? null
        ];
    } else {
        // Old single product format for backward compatibility
        if (!isset($data['product_name']) || !isset($data['quantity'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $sql = "INSERT INTO purchases (
            supplier, supplier_phone, supplier_email, city,
            product_name, category, quantity,
            cost_price, selling_price,
            gst_percentage, gst_amount, total_amount,
            payment_method, invoice_number,
            purchase_date, status, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['supplier'],
            $data['supplier_phone'] ?? null,
            $data['supplier_email'] ?? null,
            $data['city'] ?? null,
            $data['product_name'],
            $data['category'] ?? null,
            $data['quantity'],
            $data['cost_price'] ?? 0,
            $data['selling_price'] ?? 0,
            $data['gst_percentage'] ?? 0,
            $data['gst_amount'] ?? 0,
            $data['total_amount'] ?? 0,
            $data['payment_method'] ?? 'Cash',
            $data['invoice_number'] ?? null,
            $data['purchase_date'] ?? date('Y-m-d'),
            $data['status'] ?? 'pending',
            $data['notes'] ?? null
        ];
    }

    $result = executeQuery($sql, $params);

    if ($result) {
        echo json_encode(['success' => true, 'id' => getDBConnection()->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create purchase']);
    }
}

function updatePurchase($id) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No data provided']);
        return;
    }

    $fields = [];
    $params = [];

    $allowed_fields = ['supplier', 'product_name', 'quantity', 'cost_price', 'selling_price', 'purchase_date', 'status'];

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
    $sql = "UPDATE purchases SET " . implode(', ', $fields) . " WHERE id = ?";

    $result = executeQuery($sql, $params);

    if ($result !== false) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update purchase']);
    }
}

function deletePurchase($id) {
    $result = executeQuery("DELETE FROM purchases WHERE id = ?", [$id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete purchase']);
    }
}

function getPurchaseStats() {
    $stats = [];

    // Total purchases
    $total_purchases = getRow("SELECT COUNT(*) as total FROM purchases");
    $stats['total_purchases'] = $total_purchases['total'];

    // Total cost
    $total_cost = getRow("SELECT SUM(cost_price * quantity) as total FROM purchases WHERE status = 'received'");
    $stats['total_cost'] = $total_cost['total'] ?? 0;

    // Pending purchases
    $pending = getRow("SELECT COUNT(*) as count FROM purchases WHERE status = 'pending'");
    $stats['pending_purchases'] = $pending['count'];

    // Recent purchases (last 30 days)
    $recent = getRow("SELECT COUNT(*) as count FROM purchases WHERE purchase_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
    $stats['recent_purchases'] = $recent['count'];

    echo json_encode($stats);
}
?>