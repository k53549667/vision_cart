<?php
// Test creating an order via API

$orderData = [
    'customer_name' => 'Test Customer',
    'customer_id' => 'test@example.com',
    'total_amount' => 2999,
    'order_date' => date('Y-m-d'),
    'status' => 'pending',
    'payment_method' => 'COD',
    'shipping_address' => 'Test Address, City, State - 123456',
    'products' => 'Test Product 1, Test Product 2',
    'items' => [
        [
            'product_id' => 1,
            'product_name' => 'Test Product 1',
            'quantity' => 1,
            'price' => 1499,
            'gst' => 270,
            'total' => 1769
        ],
        [
            'product_id' => 2,
            'product_name' => 'Test Product 2',
            'quantity' => 1,
            'price' => 1000,
            'gst' => 180,
            'total' => 1180
        ]
    ]
];

// Send POST request
$ch = curl_init('http://localhost/vini/api_orders.php?action=create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response:\n";
echo $response;
echo "\n\n";

$result = json_decode($response, true);
if ($result && isset($result['success']) && $result['success']) {
    echo "✓ Order created successfully!\n";
    echo "Order ID: " . $result['order_id'] . "\n";
} else {
    echo "✗ Failed to create order\n";
    if (isset($result['error'])) {
        echo "Error: " . $result['error'] . "\n";
    }
}
?>
