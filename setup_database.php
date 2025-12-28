<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'visionkart_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select the database
$conn->select_db(DB_NAME);

// Create tables
$tables = [];

// Products table
$tables[] = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    subcategory VARCHAR(100),
    hsn VARCHAR(20),
    brand VARCHAR(100),
    price DECIMAL(10,2) NOT NULL,
    gst DECIMAL(5,2) DEFAULT 12.00,
    stock INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    image VARCHAR(500),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// Categories table
$tables[] = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50),
    products_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Customers table
$tables[] = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    orders_count INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// Orders table
$tables[] = "CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(20) PRIMARY KEY,
    customer_id INT,
    customer_name VARCHAR(255),
    products TEXT,
    total_amount DECIMAL(10,2) NOT NULL,
    order_date DATE,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
)";

// Order items table (for detailed order contents)
$tables[] = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20),
    product_id INT,
    product_name VARCHAR(255),
    quantity INT DEFAULT 1,
    price DECIMAL(10,2),
    gst DECIMAL(5,2),
    total DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

// Purchases table (for inventory management)
$tables[] = "CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier VARCHAR(255),
    product_name VARCHAR(255),
    quantity INT,
    cost_price DECIMAL(10,2),
    selling_price DECIMAL(10,2),
    purchase_date DATE,
    status ENUM('received', 'pending', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Admin users table
$tables[] = "CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    role ENUM('admin', 'manager') DEFAULT 'admin',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute table creation
foreach ($tables as $table_sql) {
    if ($conn->query($table_sql) === TRUE) {
        echo "Table created successfully.\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

// Insert default admin user if not exists
$admin_check = $conn->query("SELECT id FROM admin_users WHERE username = 'admin'");
if ($admin_check->num_rows == 0) {
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO admin_users (username, password, email, role) VALUES ('admin', '$admin_password', 'admin@visionkart.com', 'admin')";
    if ($conn->query($insert_admin) === TRUE) {
        echo "Default admin user created. Username: admin, Password: admin123\n";
    } else {
        echo "Error creating admin user: " . $conn->error . "\n";
    }
}

// Insert sample categories
$categories_data = [
    ['Round', 'fa-circle'],
    ['Cat-Eye', 'fa-cat'],
    ['Clubmaster', 'fa-glasses'],
    ['Transparent', 'fa-eye']
];

foreach ($categories_data as $cat) {
    $check_cat = $conn->query("SELECT id FROM categories WHERE name = '$cat[0]'");
    if ($check_cat->num_rows == 0) {
        $insert_cat = "INSERT INTO categories (name, icon) VALUES ('$cat[0]', '$cat[1]')";
        $conn->query($insert_cat);
    }
}

echo "Database setup completed!\n";
?>