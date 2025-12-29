<?php
/**
 * VisionKart Project - PHP Files Summary
 * This file provides an overview of all PHP files and their status
 */

require_once 'config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>VisionKart - PHP Files Summary</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #00bac7; text-align: center; }
        .section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section h2 { color: #333; margin-top: 0; border-bottom: 2px solid #00bac7; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #f5f5f5; font-weight: 600; }
        .badge { padding: 4px 12px; border-radius: 15px; font-size: 12px; font-weight: 600; }
        .badge-api { background: #e3f2fd; color: #1565c0; }
        .badge-page { background: #e8f5e9; color: #2e7d32; }
        .badge-setup { background: #fff3e0; color: #ef6c00; }
        .badge-test { background: #f3e5f5; color: #7b1fa2; }
        .badge-config { background: #fce4ec; color: #c2185b; }
        .success { color: #4caf50; }
        .error { color: #f44336; }
        .links { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; justify-content: center; }
        .links a { padding: 10px 20px; background: #00bac7; color: white; text-decoration: none; border-radius: 8px; }
        .links a:hover { background: #008c9a; }
    </style>
</head>
<body>
<div class='container'>
<h1><i class='fas fa-file-code'></i> VisionKart PHP Files Summary</h1>";

// Define all PHP files with their descriptions
$files = [
    // API Files
    ['api_auth.php', 'API', 'Authentication API - Register, Login, Logout, Password Reset'],
    ['api_cart.php', 'API', 'Cart API - Add, Update, Remove, List cart items'],
    ['api_categories.php', 'API', 'Categories API - CRUD operations for categories'],
    ['api_customers.php', 'API', 'Customers API - Manage customer data'],
    ['api_orders.php', 'API', 'Orders API - Create, List, Update orders'],
    ['api_products.php', 'API', 'Products API - CRUD operations for products'],
    ['api_purchases.php', 'API', 'Purchases API - Inventory/purchase management'],
    ['api_users.php', 'API', 'Users API - Profile, Addresses, User stats'],
    ['api_wishlist.php', 'API', 'Wishlist API - Add, Remove, Toggle, List wishlist items'],
    
    // Main Pages
    ['index.php', 'Page', 'Homepage - Main storefront with products'],
    ['login.php', 'Page', 'Login Page - User authentication'],
    ['register.php', 'Page', 'Registration Page - New user signup'],
    ['dashboard.php', 'Page', 'User Dashboard - Profile, Orders, Wishlist, Addresses'],
    ['admin.php', 'Page', 'Admin Panel - Product, Order, Category management'],
    ['checkout.php', 'Page', 'Checkout Page - Order placement'],
    ['product-detail.php', 'Page', 'Product Detail Page - Single product view'],
    ['payment-methods.php', 'Page', 'Payment Methods - Available payment options'],
    
    // Configuration
    ['config.php', 'Config', 'Database configuration and helper functions'],
    ['session_manager.php', 'Config', 'Session management for cart/wishlist'],
    
    // Setup Files
    ['setup_database.php', 'Setup', 'Initial database setup'],
    ['setup_complete_database.php', 'Setup', 'Complete database setup with all tables'],
    ['setup_cart_tables.php', 'Setup', 'Cart table setup'],
    ['setup_users_table.php', 'Setup', 'Users table setup'],
    ['setup_wishlist_table.php', 'Setup', 'Wishlist table setup'],
    ['seed_database.php', 'Setup', 'Seed database with sample data'],
    ['update_products_table.php', 'Setup', 'Update products table schema'],
    ['update_purchases_table.php', 'Setup', 'Update purchases table schema'],
    
    // Test Files
    ['test_database.php', 'Test', 'Test database connection'],
    ['test_create_order.php', 'Test', 'Test order creation'],
    ['test_customers_api.php', 'Test', 'Test customers API'],
    ['test_orders_table.php', 'Test', 'Test orders table'],
    ['test_product_get.php', 'Test', 'Test product retrieval'],
    
    // Utility Files
    ['check_cart.php', 'Test', 'Check cart status'],
    ['check_categories.php', 'Test', 'Check categories'],
    ['check_products.php', 'Test', 'Check products'],
    ['check_products_table.php', 'Test', 'Check products table structure'],
    ['clear_cart.php', 'Test', 'Clear cart for testing'],
];

echo "<div class='section'>
<h2><i class='fas fa-list'></i> PHP Files Overview</h2>
<table>
<thead>
<tr><th>File</th><th>Type</th><th>Description</th><th>Status</th></tr>
</thead>
<tbody>";

foreach ($files as $file) {
    $exists = file_exists($file[0]);
    $badgeClass = 'badge-' . strtolower($file[1]);
    $status = $exists ? "<span class='success'><i class='fas fa-check-circle'></i> Exists</span>" : "<span class='error'><i class='fas fa-times-circle'></i> Missing</span>";
    
    echo "<tr>
        <td><code>{$file[0]}</code></td>
        <td><span class='badge {$badgeClass}'>{$file[1]}</span></td>
        <td>{$file[2]}</td>
        <td>{$status}</td>
    </tr>";
}

echo "</tbody></table></div>";

// Database Tables Summary
echo "<div class='section'>
<h2><i class='fas fa-database'></i> Database Tables</h2>";

$conn = getDBConnection();
$tables = $conn->query("SHOW TABLES");

echo "<table>
<thead><tr><th>Table Name</th><th>Records</th><th>Status</th></tr></thead>
<tbody>";

while ($row = $tables->fetch_array()) {
    $tableName = $row[0];
    $count = $conn->query("SELECT COUNT(*) as c FROM $tableName")->fetch_assoc()['c'];
    echo "<tr>
        <td><code>{$tableName}</code></td>
        <td>{$count}</td>
        <td><span class='success'><i class='fas fa-check-circle'></i> Active</span></td>
    </tr>";
}

echo "</tbody></table></div>";

// Quick Links
echo "<div class='links'>
    <a href='index.php'><i class='fas fa-home'></i> Home</a>
    <a href='admin.php'><i class='fas fa-cog'></i> Admin</a>
    <a href='dashboard.php'><i class='fas fa-tachometer-alt'></i> Dashboard</a>
    <a href='api-status.html'><i class='fas fa-server'></i> API Status</a>
    <a href='setup_complete_database.php'><i class='fas fa-database'></i> Setup DB</a>
    <a href='test-wishlist.html'><i class='fas fa-heart'></i> Test Wishlist</a>
</div>";

echo "</div></body></html>";
?>
