<?php
/**
 * Rate Limiter Utility
 * Provides rate limiting for authentication and API endpoints
 */

require_once __DIR__ . '/../config.php';

/**
 * Check if an action is rate limited
 * 
 * @param string $identifier Unique identifier (email, IP, etc.)
 * @param string $action Action type (login, register, api, etc.)
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $windowMinutes Time window in minutes
 * @return array ['allowed' => bool, 'remaining' => int, 'retry_after' => int|null]
 */
function check_rate_limit($identifier, $action = 'login', $maxAttempts = 5, $windowMinutes = 15) {
    $conn = getDBConnection();
    
    // Ensure table exists
    ensure_rate_limit_table($conn);
    
    $windowStart = date('Y-m-d H:i:s', strtotime("-{$windowMinutes} minutes"));
    
    // Count recent attempts
    $stmt = $conn->prepare("
        SELECT COUNT(*) as attempts 
        FROM rate_limits 
        WHERE identifier = ? AND action = ? AND attempted_at > ? AND success = 0
    ");
    $stmt->bind_param('sss', $identifier, $action, $windowStart);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $attempts = (int)$row['attempts'];
    $stmt->close();
    
    // Check if blocked
    if ($attempts >= $maxAttempts) {
        // Get oldest attempt in window to calculate retry time
        $stmt = $conn->prepare("
            SELECT MIN(attempted_at) as first_attempt 
            FROM rate_limits 
            WHERE identifier = ? AND action = ? AND attempted_at > ? AND success = 0
        ");
        $stmt->bind_param('sss', $identifier, $action, $windowStart);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        $firstAttempt = strtotime($row['first_attempt']);
        $unblockTime = $firstAttempt + ($windowMinutes * 60);
        $retryAfter = max(0, $unblockTime - time());
        
        return [
            'allowed' => false,
            'remaining' => 0,
            'retry_after' => $retryAfter,
            'retry_after_minutes' => ceil($retryAfter / 60)
        ];
    }
    
    return [
        'allowed' => true,
        'remaining' => $maxAttempts - $attempts,
        'retry_after' => null
    ];
}

/**
 * Record an attempt (failed or successful)
 * 
 * @param string $identifier Unique identifier
 * @param string $action Action type
 * @param bool $success Whether the attempt was successful
 * @param array $metadata Additional data to store
 */
function record_attempt($identifier, $action = 'login', $success = false, $metadata = []) {
    $conn = getDBConnection();
    
    // Ensure table exists
    ensure_rate_limit_table($conn);
    
    $ipAddress = get_client_ip();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $metaJson = !empty($metadata) ? json_encode($metadata) : null;
    
    $stmt = $conn->prepare("
        INSERT INTO rate_limits (identifier, action, ip_address, user_agent, success, metadata, attempted_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    $successInt = $success ? 1 : 0;
    $stmt->bind_param('ssssis', $identifier, $action, $ipAddress, $userAgent, $successInt, $metaJson);
    $stmt->execute();
    $stmt->close();
    
    // If successful, optionally clear failed attempts for this identifier
    if ($success) {
        $stmt = $conn->prepare("
            DELETE FROM rate_limits 
            WHERE identifier = ? AND action = ? AND success = 0
        ");
        $stmt->bind_param('ss', $identifier, $action);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Check rate limit and return error response if blocked
 * 
 * @param string $identifier Unique identifier
 * @param string $action Action type
 * @param int $maxAttempts Maximum attempts
 * @param int $windowMinutes Time window
 * @return bool True if allowed, exits with error if blocked
 */
function rate_limit_or_fail($identifier, $action = 'login', $maxAttempts = 5, $windowMinutes = 15) {
    $check = check_rate_limit($identifier, $action, $maxAttempts, $windowMinutes);
    
    if (!$check['allowed']) {
        http_response_code(429);
        header('Content-Type: application/json');
        header('Retry-After: ' . $check['retry_after']);
        echo json_encode([
            'success' => false,
            'error' => "Too many attempts. Please try again in {$check['retry_after_minutes']} minutes.",
            'retry_after' => $check['retry_after'],
            'retry_after_minutes' => $check['retry_after_minutes']
        ]);
        exit;
    }
    
    return true;
}

/**
 * Get client IP address
 */
function get_client_ip() {
    $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];
            // Handle comma-separated list (X-Forwarded-For)
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            // Validate IP
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    
    return '0.0.0.0';
}

/**
 * Ensure rate_limits table exists
 */
function ensure_rate_limit_table($conn) {
    static $checked = false;
    
    if ($checked) {
        return;
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS rate_limits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        identifier VARCHAR(255) NOT NULL,
        action VARCHAR(50) NOT NULL DEFAULT 'login',
        ip_address VARCHAR(45),
        user_agent TEXT,
        success TINYINT(1) DEFAULT 0,
        metadata JSON,
        attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_identifier_action (identifier, action),
        INDEX idx_attempted_at (attempted_at),
        INDEX idx_ip_action (ip_address, action)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->query($sql);
    $checked = true;
}

/**
 * Clean up old rate limit records (run periodically)
 * 
 * @param int $olderThanDays Delete records older than this many days
 */
function cleanup_rate_limits($olderThanDays = 7) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM rate_limits WHERE attempted_at < DATE_SUB(NOW(), INTERVAL ? DAY)");
    $stmt->bind_param('i', $olderThanDays);
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();
    
    return $deleted;
}

/**
 * API rate limiting (per IP)
 * 
 * @param int $maxRequests Maximum requests per minute
 * @return bool
 */
function api_rate_limit($maxRequests = 60) {
    $ip = get_client_ip();
    return rate_limit_or_fail($ip, 'api', $maxRequests, 1);
}
