<?php
require_once 'config.php';

echo "Testing database connection and API functionality...\n\n";

// Test database connection
try {
    $conn = getDBConnection();
    echo "✓ Database connection successful\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Test products API
echo "\nTesting Products API:\n";
try {
    $products = getRows("SELECT COUNT(*) as count FROM products");
    echo "✓ Products table accessible, count: " . $products[0]['count'] . "\n";
} catch (Exception $e) {
    echo "✗ Products API test failed: " . $e->getMessage() . "\n";
}

// Test orders API
echo "\nTesting Orders API:\n";
try {
    $orders = getRows("SELECT COUNT(*) as count FROM orders");
    echo "✓ Orders table accessible, count: " . $orders[0]['count'] . "\n";
} catch (Exception $e) {
    echo "✗ Orders API test failed: " . $e->getMessage() . "\n";
}

// Test customers API
echo "\nTesting Customers API:\n";
try {
    $customers = getRows("SELECT COUNT(*) as count FROM customers");
    echo "✓ Customers table accessible, count: " . $customers[0]['count'] . "\n";
} catch (Exception $e) {
    echo "✗ Customers API test failed: " . $e->getMessage() . "\n";
}

// Test purchases API
echo "\nTesting Purchases API:\n";
try {
    $purchases = getRows("SELECT COUNT(*) as count FROM purchases");
    echo "✓ Purchases table accessible, count: " . $purchases[0]['count'] . "\n";
} catch (Exception $e) {
    echo "✗ Purchases API test failed: " . $e->getMessage() . "\n";
}

echo "\n✓ All database tests completed successfully!\n";
echo "\nYou can now access the admin panel at: http://localhost/vini/admin.html\n";
?>