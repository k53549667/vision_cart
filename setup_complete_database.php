<?php
/**
 * Complete Database Setup and Update Script
 * Ensures all required tables exist with latest schema
 * Run this file to update your database to the latest version
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'visionkart_db');

echo "<!DOCTYPE html>
<html>
<head>
    <title>VisionKart - Database Setup</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #00bac7; margin-top: 0; }
        .success { color: #4caf50; }
        .error { color: #f44336; }
        .info { color: #2196f3; }
        .warning { color: #ff9800; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 8px; overflow-x: auto; }
        .table-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin: 20px 0; }
        .table-item { background: #e8f5e9; padding: 10px 15px; border-radius: 8px; border-left: 4px solid #4caf50; }
        .links { margin-top: 20px; padding-top: 20px; border-top: 2px solid #e0e0e0; }
        .links a { display: inline-block; margin-right: 15px; padding: 10px 20px; background: #00bac7; color: white; text-decoration: none; border-radius: 8px; }
        .links a:hover { background: #008c9a; }
    </style>
</head>
<body>
<div class='container'>
<h1>🗄️ VisionKart Database Setup</h1>
<pre>";

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("<span class='error'>❌ Connection failed: " . $conn->connect_error . "</span>");
}

echo "<span class='success'>✅ Connected to MySQL server</span>\n";

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql)) {
    echo "<span class='success'>✅ Database '" . DB_NAME . "' ready</span>\n";
} else {
    echo "<span class='error'>❌ Error creating database: " . $conn->error . "</span>\n";
}

$conn->select_db(DB_NAME);
$conn->set_charset("utf8mb4");

echo "\n<span class='info'>📋 Setting up tables...</span>\n\n";

// Define all tables
$tables = [
    'admin_users' => "CREATE TABLE IF NOT EXISTS admin_users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) DEFAULT NULL,
        role ENUM('admin','manager') DEFAULT 'admin',
        last_login TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY username (username),
        UNIQUE KEY email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'categories' => "CREATE TABLE IF NOT EXISTS categories (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        icon VARCHAR(50) DEFAULT NULL,
        products_count INT(11) DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY name (name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'products' => "CREATE TABLE IF NOT EXISTS products (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL,
        subcategory VARCHAR(100) DEFAULT NULL,
        frametype VARCHAR(50) DEFAULT NULL,
        hsn VARCHAR(20) DEFAULT NULL,
        brand VARCHAR(100) DEFAULT NULL,
        price DECIMAL(10,2) NOT NULL,
        original_price DECIMAL(10,2) DEFAULT NULL,
        gst DECIMAL(5,2) DEFAULT 12.00,
        stock INT(11) DEFAULT 0,
        color VARCHAR(50) DEFAULT NULL,
        status ENUM('active','inactive') DEFAULT 'active',
        image VARCHAR(500) DEFAULT NULL,
        video VARCHAR(500) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'users' => "CREATE TABLE IF NOT EXISTS users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20) DEFAULT NULL,
        role ENUM('customer','admin') DEFAULT 'customer',
        email_verified TINYINT(1) DEFAULT 0,
        verification_token VARCHAR(100) DEFAULT NULL,
        reset_token VARCHAR(100) DEFAULT NULL,
        reset_token_expires DATETIME DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL DEFAULT NULL,
        status ENUM('active','inactive','suspended') DEFAULT 'active',
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'user_sessions' => "CREATE TABLE IF NOT EXISTS user_sessions (
        id INT(11) NOT NULL AUTO_INCREMENT,
        session_id VARCHAR(255) NOT NULL,
        user_id INT(11) DEFAULT NULL,
        ip_address VARCHAR(45) DEFAULT NULL,
        user_agent TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NULL DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY session_id (session_id),
        KEY user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'user_addresses' => "CREATE TABLE IF NOT EXISTS user_addresses (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        address_type ENUM('home','work','other') DEFAULT 'home',
        full_name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address_line1 VARCHAR(255) NOT NULL,
        address_line2 VARCHAR(255) DEFAULT NULL,
        city VARCHAR(100) NOT NULL,
        state VARCHAR(100) NOT NULL,
        postal_code VARCHAR(20) NOT NULL,
        country VARCHAR(100) DEFAULT 'India',
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        CONSTRAINT user_addresses_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'orders' => "CREATE TABLE IF NOT EXISTS orders (
        id VARCHAR(20) NOT NULL,
        user_id INT(11) DEFAULT NULL,
        customer_id INT(11) DEFAULT NULL,
        customer_name VARCHAR(255) DEFAULT NULL,
        products TEXT DEFAULT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        order_date DATE DEFAULT NULL,
        status ENUM('pending','processing','completed','cancelled') DEFAULT 'pending',
        payment_method VARCHAR(50) DEFAULT NULL,
        shipping_address TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY customer_id (customer_id),
        KEY user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'order_items' => "CREATE TABLE IF NOT EXISTS order_items (
        id INT(11) NOT NULL AUTO_INCREMENT,
        order_id VARCHAR(20) DEFAULT NULL,
        product_id INT(11) DEFAULT NULL,
        product_name VARCHAR(255) DEFAULT NULL,
        quantity INT(11) DEFAULT 1,
        price DECIMAL(10,2) DEFAULT NULL,
        gst DECIMAL(5,2) DEFAULT NULL,
        total DECIMAL(10,2) DEFAULT NULL,
        PRIMARY KEY (id),
        KEY order_id (order_id),
        KEY product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'cart' => "CREATE TABLE IF NOT EXISTS cart (
        id INT(11) NOT NULL AUTO_INCREMENT,
        session_id VARCHAR(255) NOT NULL,
        product_id INT(11) NOT NULL,
        quantity INT(11) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_product (session_id, product_id),
        KEY product_id (product_id),
        CONSTRAINT cart_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'wishlist' => "CREATE TABLE IF NOT EXISTS wishlist (
        id INT(11) NOT NULL AUTO_INCREMENT,
        session_id VARCHAR(255) NOT NULL,
        product_id INT(11) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_product (session_id, product_id),
        KEY product_id (product_id),
        CONSTRAINT wishlist_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    'purchases' => "CREATE TABLE IF NOT EXISTS purchases (
        id INT(11) NOT NULL AUTO_INCREMENT,
        supplier VARCHAR(255) DEFAULT NULL,
        product_name VARCHAR(255) DEFAULT NULL,
        product_id INT(11) DEFAULT NULL,
        quantity INT(11) DEFAULT NULL,
        cost_price DECIMAL(10,2) DEFAULT NULL,
        selling_price DECIMAL(10,2) DEFAULT NULL,
        purchase_date DATE DEFAULT NULL,
        status ENUM('received','pending','cancelled') DEFAULT 'pending',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
];

// Create tables
foreach ($tables as $tableName => $sql) {
    // Check if table exists
    $tableExists = $conn->query("SHOW TABLES LIKE '$tableName'")->num_rows > 0;
    
    if ($tableExists) {
        echo "<span class='success'>✅ Table '$tableName' already exists</span>\n";
    } else {
        if ($conn->query($sql)) {
            echo "<span class='success'>✅ Created table '$tableName'</span>\n";
        } else {
            echo "<span class='error'>❌ Error creating '$tableName': " . $conn->error . "</span>\n";
        }
    }
}

// Insert default admin user if not exists
echo "\n<span class='info'>👤 Checking default admin user...</span>\n";
$admin_check = $conn->query("SELECT id FROM admin_users WHERE username = 'admin'");
if ($admin_check->num_rows == 0) {
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO admin_users (username, password, email, role) VALUES ('admin', '$admin_password', 'admin@visionkart.com', 'admin')";
    if ($conn->query($insert_admin)) {
        echo "<span class='success'>✅ Default admin created (username: admin, password: admin123)</span>\n";
    }
} else {
    echo "<span class='success'>✅ Admin user exists</span>\n";
}

// Insert default categories if not exist
echo "\n<span class='info'>📁 Checking categories...</span>\n";
$categories = [
    ['Round', 'fa-circle'],
    ['Cat-Eye', 'fa-cat'],
    ['Clubmaster', 'fa-glasses'],
    ['Transparent', 'fa-eye'],
    ['Aviator', 'fa-plane'],
    ['Wayfarer', 'fa-glasses'],
    ['Oval', 'fa-circle'],
    ['Square', 'fa-square'],
    ['Rectangle', 'fa-square']
];

foreach ($categories as $cat) {
    $check = $conn->query("SELECT id FROM categories WHERE name = '{$cat[0]}'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO categories (name, icon) VALUES ('{$cat[0]}', '{$cat[1]}')");
        echo "<span class='success'>✅ Added category: {$cat[0]}</span>\n";
    }
}

// Show summary
echo "\n</pre>
<h2>📊 Database Summary</h2>
<div class='table-list'>";

$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tableName = $row[0];
    $count = $conn->query("SELECT COUNT(*) as c FROM $tableName")->fetch_assoc()['c'];
    echo "<div class='table-item'><strong>$tableName</strong><br>$count records</div>";
}

echo "</div>

<div class='links'>
    <a href='index.php'>🏠 Home</a>
    <a href='admin.php'>⚙️ Admin Panel</a>
    <a href='dashboard.php'>📊 Dashboard</a>
    <a href='test-wishlist.html'>❤️ Test Wishlist</a>
    <a href='test-cart-api.html'>🛒 Test Cart</a>
</div>

</div>
</body>
</html>";

$conn->close();
?>
