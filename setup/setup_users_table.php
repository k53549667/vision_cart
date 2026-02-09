<?php
/**
 * Setup Users Table for VisionKart Authentication System
 * Run this file once to create the users table
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'visionkart_db');

echo "Starting Users Table Setup...\n\n";

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        role ENUM('customer', 'admin') DEFAULT 'customer',
        email_verified TINYINT(1) DEFAULT 0,
        verification_token VARCHAR(100),
        reset_token VARCHAR(100),
        reset_token_expires DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        INDEX idx_email (email),
        INDEX idx_verification_token (verification_token),
        INDEX idx_reset_token (reset_token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $conn->exec($sql);
    echo "✅ Users table created successfully!\n\n";

    // Create user_addresses table
    $sql = "CREATE TABLE IF NOT EXISTS user_addresses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        address_type ENUM('home', 'work', 'other') DEFAULT 'home',
        full_name VARCHAR(200) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address_line1 VARCHAR(255) NOT NULL,
        address_line2 VARCHAR(255),
        city VARCHAR(100) NOT NULL,
        state VARCHAR(100) NOT NULL,
        postal_code VARCHAR(20) NOT NULL,
        country VARCHAR(100) DEFAULT 'India',
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $conn->exec($sql);
    echo "✅ User addresses table created successfully!\n\n";

    // Update orders table to link with users
    $sql = "ALTER TABLE orders 
            ADD COLUMN user_id INT NULL AFTER id,
            ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL";
    
    try {
        $conn->exec($sql);
        echo "✅ Orders table updated with user_id column!\n\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "ℹ️  Orders table already has user_id column\n\n";
        } else {
            echo "⚠️  Warning updating orders table: " . $e->getMessage() . "\n\n";
        }
    }

    // Update user_sessions table to link with users
    $sql = "ALTER TABLE user_sessions 
            MODIFY user_id INT NULL,
            ADD CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE";
    
    try {
        $conn->exec($sql);
        echo "✅ User sessions table updated with foreign key!\n\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  User sessions table already has foreign key constraint\n\n";
        } else {
            echo "⚠️  Warning updating user_sessions table: " . $e->getMessage() . "\n\n";
        }
    }

    // Create default admin user (password: admin123)
    $adminEmail = 'admin@visionkart.com';
    $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, role, email_verified, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?) 
                           ON DUPLICATE KEY UPDATE email=email");
    $stmt->execute([$adminEmail, $adminPassword, 'Admin', 'User', 'admin', 1, 'active']);
    
    echo "✅ Default admin user created!\n";
    echo "   Email: admin@visionkart.com\n";
    echo "   Password: admin123\n\n";

    echo "🎉 Users table setup completed successfully!\n";
    echo "You can now use the authentication system.\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
