<?php
require_once 'config.php';

$conn = getDBConnection();

// Sample products for each category
$products = [
    // Round
    ['Vincent Chase Round Classic', 'Eyeglasses', 'Round', 'full-rim', 'Vincent Chase', 1999, 2499, 'Gold', 'Timeless round metal frames with adjustable nose pads'],
    ['John Jacobs Round Wire', 'Eyeglasses', 'Round', 'full-rim', 'John Jacobs', 2499, 3299, 'Silver', 'Classic wire round frames inspired by vintage styles'],
    ['VisionKart Air Round Lite', 'Eyeglasses', 'Round', 'rimless', 'VisionKart Air', 1599, 1999, 'Gold', 'Ultra-lightweight round frames for all-day comfort'],
    ['Retro Round Black', 'Eyeglasses', 'Round', 'full-rim', 'Vincent Chase', 1799, 2199, 'Black', 'Bold black round frames with a retro vibe'],
    
    // Cat-Eye
    ['Glamour Cat-Eye', 'Eyeglasses', 'Cat-Eye', 'full-rim', 'John Jacobs', 2199, 2799, 'Black', 'Elegant cat-eye frames for a sophisticated look'],
    ['Vintage Cat-Eye Pink', 'Eyeglasses', 'Cat-Eye', 'full-rim', 'Vincent Chase', 1899, 2399, 'Brown', 'Feminine cat-eye with tortoise pattern'],
    ['Bold Cat-Eye', 'Eyeglasses', 'Cat-Eye', 'full-rim', 'VisionKart Air', 1699, 2099, 'Blue', 'Statement cat-eye frames in vibrant blue'],
    ['Classic Cat-Eye Transparent', 'Eyeglasses', 'Cat-Eye', 'full-rim', 'John Jacobs', 2099, 2599, 'Transparent', 'Modern transparent cat-eye frames'],
    
    // Clubmaster
    ['Classic Clubmaster', 'Eyeglasses', 'Clubmaster', 'half-rim', 'Vincent Chase', 2299, 2899, 'Black', 'Iconic clubmaster style with gold accents'],
    ['Clubmaster Tortoise', 'Eyeglasses', 'Clubmaster', 'half-rim', 'John Jacobs', 2499, 3099, 'Brown', 'Premium tortoise clubmaster frames'],
    ['Modern Clubmaster', 'Sunglasses', 'Clubmaster', 'half-rim', 'VisionKart Air', 1999, 2499, 'Black', 'Contemporary clubmaster sunglasses'],
    ['Clubmaster Gold', 'Eyeglasses', 'Clubmaster', 'half-rim', 'Vincent Chase', 2699, 3299, 'Gold', 'Luxury gold-accented clubmaster'],
    
    // Transparent
    ['Crystal Clear Round', 'Eyeglasses', 'Transparent', 'full-rim', 'VisionKart Air', 999, 1499, 'Transparent', 'Minimalist transparent round frames'],
    ['Clear Square Modern', 'Eyeglasses', 'Transparent', 'full-rim', 'John Jacobs', 1499, 1999, 'Transparent', 'Contemporary clear square frames'],
    ['Transparent Oval', 'Eyeglasses', 'Transparent', 'full-rim', 'Vincent Chase', 1299, 1799, 'Transparent', 'Elegant transparent oval frames'],
    ['Clear Wayfarer', 'Eyeglasses', 'Transparent', 'full-rim', 'VisionKart Air', 1199, 1599, 'Transparent', 'Classic wayfarer in crystal clear'],
    
    // Aviator
    ['Classic Aviator Gold', 'Sunglasses', 'Aviator', 'full-rim', 'Vincent Chase', 2499, 3199, 'Gold', 'Iconic gold aviator sunglasses'],
    ['Aviator Pilot', 'Sunglasses', 'Aviator', 'full-rim', 'John Jacobs', 2699, 3399, 'Silver', 'Original pilot-style aviators'],
    ['Modern Aviator Black', 'Eyeglasses', 'Aviator', 'full-rim', 'VisionKart Air', 1899, 2399, 'Black', 'Contemporary black aviator frames'],
    
    // Square
    ['Bold Square Black', 'Eyeglasses', 'Square', 'full-rim', 'Vincent Chase', 1899, 2399, 'Black', 'Bold square frames for a strong look'],
    ['Square Tortoise', 'Eyeglasses', 'Square', 'full-rim', 'John Jacobs', 2199, 2799, 'Brown', 'Classic tortoise square frames'],
    ['Minimal Square', 'Eyeglasses', 'Square', 'rimless', 'VisionKart Air', 1599, 1999, 'Silver', 'Minimalist rimless square frames'],
    
    // Rectangle
    ['Professional Rectangle', 'Eyeglasses', 'Rectangle', 'full-rim', 'John Jacobs', 2099, 2699, 'Black', 'Professional rectangle frames for business'],
    ['Slim Rectangle', 'Eyeglasses', 'Rectangle', 'half-rim', 'Vincent Chase', 1799, 2299, 'Silver', 'Slim half-rim rectangle frames'],
    ['Wide Rectangle', 'Eyeglasses', 'Rectangle', 'full-rim', 'VisionKart Air', 1699, 2099, 'Brown', 'Wide rectangle frames for larger faces'],
    
    // Wayfarer
    ['Classic Wayfarer Black', 'Sunglasses', 'Wayfarer', 'full-rim', 'Vincent Chase', 2199, 2799, 'Black', 'Iconic black wayfarer sunglasses'],
    ['Wayfarer Tortoise', 'Eyeglasses', 'Wayfarer', 'full-rim', 'John Jacobs', 2399, 2999, 'Brown', 'Classic tortoise wayfarer frames'],
    ['Modern Wayfarer', 'Eyeglasses', 'Wayfarer', 'full-rim', 'VisionKart Air', 1899, 2399, 'Blue', 'Contemporary wayfarer in navy blue'],
];

// Image URLs for products
$images = [
    'Round' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop',
    'Cat-Eye' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop',
    'Clubmaster' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop',
    'Transparent' => 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop',
    'Aviator' => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=400&h=400&fit=crop',
    'Square' => 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop',
    'Rectangle' => 'https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop',
    'Wayfarer' => 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop',
];

$inserted = 0;
$skipped = 0;

foreach ($products as $p) {
    // Check if product with same name exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
    $stmt->bind_param("s", $p[0]);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Skipping (exists): {$p[0]}\n";
        $skipped++;
        continue;
    }
    
    $image = isset($images[$p[2]]) ? $images[$p[2]] : $images['Round'];
    
    $sql = "INSERT INTO products (name, category, subcategory, frametype, brand, price, original_price, color, status, image, description, stock) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, 50)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssddsss", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $image, $p[8]);
    
    if ($stmt->execute()) {
        echo "✅ Added: {$p[0]} ({$p[2]})\n";
        $inserted++;
    } else {
        echo "❌ Failed: {$p[0]} - " . $conn->error . "\n";
    }
}

echo "\n=============================\n";
echo "Inserted: $inserted\n";
echo "Skipped: $skipped\n";
echo "=============================\n";

// Show updated summary
echo "\n=== Updated Product Summary ===\n";
$result = $conn->query('SELECT subcategory, COUNT(*) as cnt FROM products WHERE status = "active" GROUP BY subcategory ORDER BY subcategory');
while($row = $result->fetch_assoc()) {
    echo "- " . ($row['subcategory'] ?: 'Other') . ": " . $row['cnt'] . " products\n";
}
?>
