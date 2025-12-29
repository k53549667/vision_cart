<?php
require_once 'config.php';

$conn = getDBConnection();

echo "=== Products Summary ===\n\n";

$result = $conn->query('SELECT COUNT(*) as total FROM products');
$row = $result->fetch_assoc();
echo "Total products: " . $row['total'] . "\n\n";

echo "=== By Category ===\n";
$result = $conn->query('SELECT category, COUNT(*) as cnt FROM products GROUP BY category');
while($row = $result->fetch_assoc()) {
    echo "- " . ($row['category'] ?: 'NULL') . ": " . $row['cnt'] . "\n";
}

echo "\n=== By Subcategory ===\n";
$result = $conn->query('SELECT subcategory, COUNT(*) as cnt FROM products GROUP BY subcategory');
while($row = $result->fetch_assoc()) {
    echo "- " . ($row['subcategory'] ?: 'NULL') . ": " . $row['cnt'] . "\n";
}

echo "\n=== By Frame Type ===\n";
$result = $conn->query('SELECT frametype, COUNT(*) as cnt FROM products GROUP BY frametype');
while($row = $result->fetch_assoc()) {
    echo "- " . ($row['frametype'] ?: 'NULL') . ": " . $row['cnt'] . "\n";
}

echo "\n=== Sample Products ===\n";
$result = $conn->query('SELECT id, name, category, subcategory, frametype, price FROM products LIMIT 10');
while($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']} | {$row['name']} | Cat: {$row['category']} | Sub: {$row['subcategory']} | Frame: {$row['frametype']} | ₹{$row['price']}\n";
}
?>
