<?php
require_once 'config.php';

// Test getting a product
$products = getRows("SELECT * FROM products LIMIT 1", []);

if (!empty($products)) {
    $productId = $products[0]['id'];
    echo "Testing product ID: " . $productId . "\n\n";
    
    // Get single product
    $product = getRow("SELECT * FROM products WHERE id = ?", [$productId]);
    
    echo "Product data:\n";
    print_r($product);
    
    echo "\n\nJSON output:\n";
    echo json_encode($product, JSON_PRETTY_PRINT);
} else {
    echo "No products found in database\n";
}
