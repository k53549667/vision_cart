<?php
require_once 'config.php';

// Get shape from argv or default to round
$shape = isset($argv[1]) ? $argv[1] : 'round';
echo "Testing category filter for: $shape\n\n";

$sql = "SELECT id, name, subcategory, frametype FROM products 
        WHERE status = 'active' 
        AND (LOWER(subcategory) LIKE ? OR LOWER(frametype) LIKE ? OR LOWER(name) LIKE ?)";

$searchTerm = '%' . strtolower($shape) . '%';
$products = getRows($sql, [$searchTerm, $searchTerm, $searchTerm]);

echo "Found " . count($products) . " products:\n";
foreach ($products as $p) {
    echo "- [{$p['id']}] {$p['name']} (Sub: {$p['subcategory']}, Frame: {$p['frametype']})\n";
}
?>
