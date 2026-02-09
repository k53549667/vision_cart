<?php
/**
 * Admin Authentication API
 * Handles admin login/logout/session management
 */

// Suppress HTML error output for JSON API
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

session_start();

require_once '../../config.php';
require_once '../../includes/security_headers.php';
require_once '../../includes/rate_limiter.php';
require_once '../../includes/audit_log.php';

// Set security headers
set_api_security_headers();
set_cors_headers();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'check':
        checkSession();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function handleLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'Username and password are required']);
        return;
    }
    
    try {
        $conn = getDBConnection();
    
    // Check if admin_users table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'admin_users'");
    if ($tableCheck->num_rows === 0) {
        // Create table only - no default user with hardcoded password
        $conn->query("
            CREATE TABLE admin_users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100),
                role ENUM('admin', 'manager') DEFAULT 'admin',
                last_login TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                failed_attempts INT DEFAULT 0,
                locked_until TIMESTAMP NULL
            )
        ");
        
        // Return error - admin must be created via secure setup
        echo json_encode(['success' => false, 'error' => 'Admin system not configured. Please run the secure setup script.']);
        return;
    }
    
    // Get admin user
    $stmt = $conn->prepare("SELECT id, username, password, email, role, failed_attempts, locked_until FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
        return;
    }
    
    $admin = $result->fetch_assoc();
    
    // Check if account is locked (Fix Issue #16 - Rate limiting)
    if ($admin['locked_until'] && strtotime($admin['locked_until']) > time()) {
        $remainingTime = ceil((strtotime($admin['locked_until']) - time()) / 60);
        echo json_encode(['success' => false, 'error' => "Account temporarily locked. Try again in $remainingTime minutes."]);
        return;
    }
    
    // Verify password
    if (!password_verify($password, $admin['password'])) {
        // Increment failed attempts
        $failedAttempts = $admin['failed_attempts'] + 1;
        $lockUntil = null;
        
        // Lock account after 5 failed attempts for 15 minutes
        if ($failedAttempts >= 5) {
            $lockUntil = date('Y-m-d H:i:s', time() + 900); // 15 minutes
            $failedAttempts = 0; // Reset counter after locking
        }
        
        $updateStmt = $conn->prepare("UPDATE admin_users SET failed_attempts = ?, locked_until = ? WHERE id = ?");
        $updateStmt->bind_param("isi", $failedAttempts, $lockUntil, $admin['id']);
        $updateStmt->execute();
        
        // Audit log failed admin login
        audit_log('admin_login_failed', 'admin_auth', $admin['id'], [
            'username' => $username,
            'reason' => 'invalid_password',
            'failed_attempts' => $failedAttempts,
            'locked' => $lockUntil !== null
        ]);
        
        echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
        return;
    }
    
    // Reset failed attempts on successful login
    $updateStmt = $conn->prepare("UPDATE admin_users SET last_login = NOW(), failed_attempts = 0, locked_until = NULL WHERE id = ?");
    $updateStmt->bind_param("i", $admin['id']);
    $updateStmt->execute();
    
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    // Set session
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_login_time'] = time();
    
    // Audit log successful admin login
    audit_log('admin_login_success', 'admin_auth', $admin['id'], [
        'username' => $admin['username'],
        'role' => $admin['role']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'admin' => [
            'id' => $admin['id'],
            'username' => $admin['username'],
            'email' => $admin['email'],
            'role' => $admin['role']
        ]
    ]);
    
    } catch (Exception $e) {
        error_log('Admin login error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'A server error occurred. Please try again later.']);
    }
}

function handleLogout() {
    // Clear admin session
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_role']);
    unset($_SESSION['admin_login_time']);
    
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function checkSession() {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        echo json_encode([
            'success' => true,
            'logged_in' => true,
            'admin' => [
                'id' => $_SESSION['admin_id'],
                'username' => $_SESSION['admin_username'],
                'email' => $_SESSION['admin_email'],
                'role' => $_SESSION['admin_role']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'logged_in' => false
        ]);
    }
}
?>
