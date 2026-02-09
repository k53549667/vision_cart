<?php
/**
 * VisionKart Database Configuration
 * Uses environment variables for security
 */

// Load environment variables
require_once __DIR__ . '/env_loader.php';

// Database configuration from environment
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'snoptical'));

// Application settings
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', filter_var(env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN));
define('APP_URL', env('APP_URL', 'http://localhost/snoptical'));
define('APP_SECRET', env('APP_SECRET_KEY', 'change_this_in_production'));

// Payment settings (server-side only - never expose to client!)
define('RAZORPAY_KEY_ID', env('RAZORPAY_KEY_ID', ''));
define('RAZORPAY_KEY_SECRET', env('RAZORPAY_KEY_SECRET', ''));
define('STRIPE_SECRET_KEY', env('STRIPE_SECRET_KEY', ''));

// Rate limiting settings
define('LOGIN_MAX_ATTEMPTS', (int)env('LOGIN_MAX_ATTEMPTS', 5));
define('LOGIN_LOCKOUT_MINUTES', (int)env('LOGIN_LOCKOUT_MINUTES', 15));

// Create connection
function getDBConnection() {
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Set charset to utf8
        $conn->set_charset("utf8");
    }

    return $conn;
}

// Helper function to execute queries
function executeQuery($sql, $params = []) {
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    $result = $stmt->execute();

    if ($result) {
        if (strpos(strtoupper($sql), 'SELECT') === 0) {
            return $stmt->get_result();
        } else {
            return $stmt->affected_rows;
        }
    }

    return false;
}

// Helper function to get single row
function getRow($sql, $params = []) {
    $result = executeQuery($sql, $params);
    return $result ? $result->fetch_assoc() : null;
}

// Helper function to get all rows
function getRows($sql, $params = []) {
    $result = executeQuery($sql, $params);
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}
?>