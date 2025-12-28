<?php
require_once 'config.php';

// Get all products
$products = getRows("SELECT id, name, category, subcategory FROM products ORDER BY id", []);

echo "All Products in Database:\n";
echo "========================\n\n";

foreach ($products as $product) {
    echo "ID: {$product['id']} | Name: {$product['name']} | Category: {$product['category']} | Subcategory: {$product['subcategory']}\n";
}

echo "\n\nTotal products: " . count($products);
