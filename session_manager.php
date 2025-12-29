<?php
/**
 * Session Management Utility
 * Handles user sessions for cart and wishlist persistence
 */

require_once 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Get or create session ID for cart/wishlist
 * Returns a unique session identifier
 */
function getSessionId() {
    // Check if session_id already exists in session
    if (isset($_SESSION['visionkart_session_id'])) {
        return $_SESSION['visionkart_session_id'];
    }
    
    // Check if session_id is passed in request (for API calls)
    if (isset($_COOKIE['visionkart_session_id'])) {
        $sessionId = $_COOKIE['visionkart_session_id'];
        $_SESSION['visionkart_session_id'] = $sessionId;
        return $sessionId;
    }
    
    // Generate new session ID
    $sessionId = generateUniqueSessionId();
    $_SESSION['visionkart_session_id'] = $sessionId;
    
    // Set cookie for 30 days
    setcookie('visionkart_session_id', $sessionId, time() + (30 * 24 * 60 * 60), '/');
    
    // Store in database
    createSession($sessionId);
    
    return $sessionId;
}

/**
 * Generate unique session ID
 */
function generateUniqueSessionId() {
    return 'vk_' . bin2hex(random_bytes(16)) . '_' . time();
}

/**
 * Create session record in database
 */
function createSession($sessionId, $userId = null) {
    $conn = getDBConnection();
    
    $sql = "INSERT INTO user_sessions (session_id, user_id, expires_at) 
            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))
            ON DUPLICATE KEY UPDATE updated_at = NOW()";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $sessionId, $userId);
    $stmt->execute();
    $stmt->close();
}

/**
 * Link session to user (when user logs in)
 */
function linkSessionToUser($sessionId, $userId) {
    $conn = getDBConnection();
    
    $sql = "UPDATE user_sessions SET user_id = ? WHERE session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $userId, $sessionId);
    $stmt->execute();
    $stmt->close();
    
    // Update cart and wishlist to link to user
    $sql = "UPDATE cart SET session_id = ? WHERE session_id = ?";
    $stmt = $conn->prepare($sql);
    $newSessionId = 'user_' . $userId;
    $stmt->bind_param('ss', $newSessionId, $sessionId);
    $stmt->execute();
    $stmt->close();
}

/**
 * Clean up expired sessions
 */
function cleanExpiredSessions() {
    $conn = getDBConnection();
    
    // Delete expired sessions
    $sql = "DELETE FROM user_sessions WHERE expires_at < NOW()";
    $conn->query($sql);
    
    // Delete cart items from expired sessions
    $sql = "DELETE FROM cart WHERE session_id NOT IN (SELECT session_id FROM user_sessions)";
    $conn->query($sql);
    
    // Delete wishlist items from expired sessions
    $sql = "DELETE FROM wishlist WHERE session_id NOT IN (SELECT session_id FROM user_sessions)";
    $conn->query($sql);
}

/**
 * Validate session
 */
function validateSession($sessionId) {
    $conn = getDBConnection();
    
    $sql = "SELECT id FROM user_sessions WHERE session_id = ? AND expires_at > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $valid = $result->num_rows > 0;
    $stmt->close();
    
    return $valid;
}

/**
 * Extend session expiry
 */
function extendSession($sessionId) {
    $conn = getDBConnection();
    
    $sql = "UPDATE user_sessions SET expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY), updated_at = NOW() WHERE session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $stmt->close();
}

// Clean up expired sessions periodically (1% chance)
if (rand(1, 100) === 1) {
    cleanExpiredSessions();
}
?>
