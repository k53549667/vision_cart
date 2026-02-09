<?php
/**
 * Security Tables Migration
 * Creates tables required for security features
 * 
 * Run once during deployment setup
 */

require_once '../config.php';

echo "<h1>Security Tables Migration</h1>\n";

try {
    $conn = getDBConnection();
    
    // Rate Limiting Table
    echo "<h2>Creating rate_limit_attempts table...</h2>\n";
    $conn->query("
        CREATE TABLE IF NOT EXISTS rate_limit_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            identifier VARCHAR(255) NOT NULL,
            action_type VARCHAR(50) NOT NULL,
            attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45),
            INDEX idx_identifier_action (identifier, action_type),
            INDEX idx_attempt_time (attempt_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green;'>✓ rate_limit_attempts table created</p>\n";
    
    // Audit Log Table
    echo "<h2>Creating security_audit_log table...</h2>\n";
    $conn->query("
        CREATE TABLE IF NOT EXISTS security_audit_log (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            event_type VARCHAR(50) NOT NULL,
            event_category VARCHAR(50) NOT NULL,
            entity_id VARCHAR(100),
            user_id INT,
            admin_id INT,
            ip_address VARCHAR(45),
            user_agent VARCHAR(500),
            event_data JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_event_type (event_type),
            INDEX idx_category (event_category),
            INDEX idx_user_id (user_id),
            INDEX idx_admin_id (admin_id),
            INDEX idx_created_at (created_at),
            INDEX idx_ip_address (ip_address)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green;'>✓ security_audit_log table created</p>\n";
    
    // Payment Transactions Table
    echo "<h2>Creating payment_transactions table...</h2>\n";
    $conn->query("
        CREATE TABLE IF NOT EXISTS payment_transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id VARCHAR(50) NOT NULL,
            payment_id VARCHAR(100) NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
            gateway_response TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_order_id (order_id),
            INDEX idx_payment_id (payment_id),
            INDEX idx_status (status),
            UNIQUE KEY unique_payment (order_id, payment_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green;'>✓ payment_transactions table created</p>\n";
    
    // CSRF Tokens Table (optional - for DB-backed CSRF)
    echo "<h2>Creating csrf_tokens table...</h2>\n";
    $conn->query("
        CREATE TABLE IF NOT EXISTS csrf_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(128) NOT NULL,
            token VARCHAR(64) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NOT NULL,
            INDEX idx_session (session_id),
            INDEX idx_token (token),
            INDEX idx_expires (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green;'>✓ csrf_tokens table created</p>\n";
    
    // Clean up old rate limit entries (older than 1 hour)
    echo "<h2>Setting up cleanup procedures...</h2>\n";
    $conn->query("
        CREATE EVENT IF NOT EXISTS cleanup_rate_limits
        ON SCHEDULE EVERY 1 HOUR
        DO
        DELETE FROM rate_limit_attempts WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ");
    
    $conn->query("
        CREATE EVENT IF NOT EXISTS cleanup_audit_logs
        ON SCHEDULE EVERY 1 DAY
        DO
        DELETE FROM security_audit_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)
    ");
    
    $conn->query("
        CREATE EVENT IF NOT EXISTS cleanup_csrf_tokens
        ON SCHEDULE EVERY 1 HOUR
        DO
        DELETE FROM csrf_tokens WHERE expires_at < NOW()
    ");
    echo "<p style='color:green;'>✓ Cleanup events created (requires event_scheduler=ON)</p>\n";
    
    echo "<hr>\n";
    echo "<h2 style='color:green;'>✓ All security tables created successfully!</h2>\n";
    echo "<p>Please enable MySQL event scheduler for automatic cleanup:</p>\n";
    echo "<code>SET GLOBAL event_scheduler = ON;</code>\n";
    
    // Show table status
    echo "<h2>Table Status:</h2>\n";
    echo "<pre>";
    $tables = ['rate_limit_attempts', 'security_audit_log', 'payment_transactions', 'csrf_tokens'];
    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as count FROM $table");
        $row = $result->fetch_assoc();
        echo "$table: {$row['count']} rows\n";
    }
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    error_log('Migration error: ' . $e->getMessage());
}
