<?php
/**
 * Audit Logging Utility
 * Records important actions for security and compliance
 */

require_once __DIR__ . '/../config.php';

/**
 * Log an audit event
 * 
 * @param string $action Action performed (login, logout, order_create, etc.)
 * @param string|null $entityType Type of entity (user, order, product, etc.)
 * @param string|null $entityId ID of the entity
 * @param array $details Additional details
 * @param int|null $userId User ID (null for anonymous)
 */
function audit_log($action, $entityType = null, $entityId = null, $details = [], $userId = null) {
    $conn = getDBConnection();
    
    // Ensure table exists
    ensure_audit_table($conn);
    
    // Get user ID from session if not provided
    if ($userId === null && isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    }
    
    // Get admin ID if admin is logged in
    $adminId = null;
    if (isset($_SESSION['admin_id'])) {
        $adminId = $_SESSION['admin_id'];
    }
    
    $ipAddress = get_client_ip_audit();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $detailsJson = !empty($details) ? json_encode($details) : null;
    
    $stmt = $conn->prepare("
        INSERT INTO audit_logs 
        (user_id, admin_id, action, entity_type, entity_id, ip_address, user_agent, details, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->bind_param('iissssss', 
        $userId, 
        $adminId, 
        $action, 
        $entityType, 
        $entityId, 
        $ipAddress, 
        $userAgent, 
        $detailsJson
    );
    
    $stmt->execute();
    $stmt->close();
}

/**
 * Log user authentication events
 */
function audit_auth($action, $email, $success = true, $details = []) {
    $details['email'] = $email;
    $details['success'] = $success;
    audit_log($action, 'auth', null, $details);
}

/**
 * Log order events
 */
function audit_order($action, $orderId, $details = []) {
    audit_log($action, 'order', $orderId, $details);
}

/**
 * Log payment events
 */
function audit_payment($action, $orderId, $details = []) {
    // Mask sensitive payment data
    if (isset($details['card_number'])) {
        $details['card_number'] = '****' . substr($details['card_number'], -4);
    }
    audit_log($action, 'payment', $orderId, $details);
}

/**
 * Log admin actions
 */
function audit_admin($action, $entityType, $entityId, $details = []) {
    audit_log('admin_' . $action, $entityType, $entityId, $details);
}

/**
 * Log data access (for compliance)
 */
function audit_data_access($entityType, $entityId, $details = []) {
    audit_log('data_access', $entityType, $entityId, $details);
}

/**
 * Get client IP for audit
 */
function get_client_ip_audit() {
    $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    
    return '0.0.0.0';
}

/**
 * Ensure audit_logs table exists
 */
function ensure_audit_table($conn) {
    static $checked = false;
    
    if ($checked) {
        return;
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        admin_id INT NULL,
        action VARCHAR(100) NOT NULL,
        entity_type VARCHAR(50) NULL,
        entity_id VARCHAR(50) NULL,
        ip_address VARCHAR(45),
        user_agent TEXT,
        details JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_admin_id (admin_id),
        INDEX idx_action (action),
        INDEX idx_entity (entity_type, entity_id),
        INDEX idx_created_at (created_at),
        INDEX idx_ip_address (ip_address)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->query($sql);
    $checked = true;
}

/**
 * Get audit logs for an entity
 * 
 * @param string $entityType Entity type
 * @param string $entityId Entity ID
 * @param int $limit Maximum records to return
 * @return array Audit records
 */
function get_audit_logs($entityType, $entityId, $limit = 100) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("
        SELECT * FROM audit_logs 
        WHERE entity_type = ? AND entity_id = ? 
        ORDER BY created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param('ssi', $entityType, $entityId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['details']) {
            $row['details'] = json_decode($row['details'], true);
        }
        $logs[] = $row;
    }
    $stmt->close();
    
    return $logs;
}

/**
 * Get recent audit logs for a user
 */
function get_user_audit_logs($userId, $limit = 50) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("
        SELECT * FROM audit_logs 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param('ii', $userId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['details']) {
            $row['details'] = json_decode($row['details'], true);
        }
        $logs[] = $row;
    }
    $stmt->close();
    
    return $logs;
}

/**
 * Clean up old audit logs (for data retention policy)
 * 
 * @param int $olderThanDays Delete logs older than this many days
 * @return int Number of deleted records
 */
function cleanup_audit_logs($olderThanDays = 365) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("
        DELETE FROM audit_logs 
        WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    $stmt->bind_param('i', $olderThanDays);
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();
    
    return $deleted;
}
