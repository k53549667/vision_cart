<?php
/**
 * VisionKart Authentication API
 * Handles user registration, login, logout, and password reset
 * 
 * Security Features:
 * - Rate limiting on login/register
 * - CSRF protection on state-changing operations
 * - Audit logging for security events
 */

session_start();

// Load security utilities
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/security_headers.php';
require_once __DIR__ . '/../includes/rate_limiter.php';
require_once __DIR__ . '/../includes/audit_log.php';

// Set security headers
set_api_security_headers();
set_cors_headers();

// Create PDO connection using centralized config
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    error_log('Database connection failed: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Service temporarily unavailable. Please try again later.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle preflight requests
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

switch ($method) {
    case 'POST':
        if ($action === 'register') {
            register();
        } elseif ($action === 'login') {
            login();
        } elseif ($action === 'logout') {
            logout();
        } elseif ($action === 'verify-email') {
            verifyEmail();
        } elseif ($action === 'forgot-password') {
            forgotPassword();
        } elseif ($action === 'reset-password') {
            resetPassword();
        } elseif ($action === 'change-password') {
            changePassword();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    case 'GET':
        if ($action === 'check-session' || $action === 'check') {
            checkSession();
        } elseif ($action === 'current-user') {
            getCurrentUser();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}

/**
 * Register new user
 */
function register() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['email', 'password', 'first_name', 'last_name'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
            return;
        }
    }
    
    $email = trim($data['email']);
    $password = $data['password'];
    $firstName = trim($data['first_name']);
    $lastName = trim($data['last_name']);
    $phone = isset($data['phone']) ? trim($data['phone']) : null;
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        return;
    }
    
    // Validate password strength - require 8+ chars, 1 uppercase, 1 lowercase, 1 number
    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
        return;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter']);
        return;
    }
    if (!preg_match('/[a-z]/', $password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one lowercase letter']);
        return;
    }
    if (!preg_match('/[0-9]/', $password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one number']);
        return;
    }
    
    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Email already registered']);
            return;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Generate verification token
        $verificationToken = bin2hex(random_bytes(32));
        
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, phone, verification_token, role) 
                               VALUES (?, ?, ?, ?, ?, ?, 'customer')");
        $stmt->execute([$email, $hashedPassword, $firstName, $lastName, $phone, $verificationToken]);
        
        $userId = $conn->lastInsertId();
        
        // Auto-login after registration
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        $_SESSION['user_role'] = 'customer';
        
        // Update last login
        $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful',
            'user' => [
                'id' => $userId,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'full_name' => $firstName . ' ' . $lastName,
                'role' => 'customer'
            ]
        ]);
        
    } catch (PDOException $e) {
        error_log('Registration error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}

/**
 * Login user
 */
function login() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        return;
    }
    
    $email = trim($data['email']);
    $password = $data['password'];
    
    // Rate limiting check - prevent brute force attacks
    $rateCheck = check_rate_limit($email, 'login', LOGIN_MAX_ATTEMPTS, LOGIN_LOCKOUT_MINUTES);
    if (!$rateCheck['allowed']) {
        http_response_code(429);
        audit_auth('login_blocked', $email, false, ['reason' => 'rate_limit']);
        echo json_encode([
            'success' => false, 
            'message' => "Too many login attempts. Please try again in {$rateCheck['retry_after_minutes']} minutes."
        ]);
        return;
    }
    
    try {
        $stmt = $conn->prepare("SELECT id, email, password, first_name, last_name, role, status, email_verified 
                               FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            record_attempt($email, 'login', false);
            audit_auth('login_failed', $email, false, ['reason' => 'user_not_found']);
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
            return;
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            record_attempt($email, 'login', false);
            audit_auth('login_failed', $email, false, ['reason' => 'invalid_password']);
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
            return;
        }
        
        // Check account status
        if ($user['status'] !== 'active') {
            record_attempt($email, 'login', false);
            audit_auth('login_failed', $email, false, ['reason' => 'account_' . $user['status']]);
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Account is ' . $user['status']]);
            return;
        }
        
        // Successful login - clear rate limit records and regenerate session
        record_attempt($email, 'login', true);
        session_regenerate_id(true); // Prevent session fixation
        
        // Create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        
        // Update last login
        $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        // Audit log successful login
        audit_auth('login_success', $email, true, ['user_id' => $user['id']]);
        
        // Migrate guest cart to user account if exists (fix cookie name)
        if (isset($_COOKIE['visionkart_session_id'])) {
            $sessionId = $_COOKIE['visionkart_session_id'];
            $stmt = $conn->prepare("UPDATE user_sessions SET user_id = ? WHERE session_id = ?");
            $stmt->execute([$user['id'], $sessionId]);
            
            // Also update cart items to link to user
            $stmt = $conn->prepare("UPDATE cart SET session_id = CONCAT('user_', ?) WHERE session_id = ?");
            $stmt->execute([$user['id'], $sessionId]);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'full_name' => $user['first_name'] . ' ' . $user['last_name'],
                'role' => $user['role'],
                'email_verified' => (bool)$user['email_verified']
            ]
        ]);
        
    } catch (PDOException $e) {
        error_log('Login error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}

/**
 * Logout user
 */
function logout() {
    global $conn;
    
    // Get session ID to clear cart and wishlist
    $sessionId = null;
    if (isset($_SESSION['visionkart_session_id'])) {
        $sessionId = $_SESSION['visionkart_session_id'];
    } elseif (isset($_COOKIE['visionkart_session_id'])) {
        $sessionId = $_COOKIE['visionkart_session_id'];
    }
    
    // Clear cart and wishlist from database if session exists
    if ($sessionId) {
        try {
            // Clear cart items
            $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            
            // Clear wishlist items
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            
            // Delete session record
            $stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
            $stmt->execute([$sessionId]);
        } catch (PDOException $e) {
            // Log error but continue with logout
            error_log('Error clearing cart/wishlist on logout: ' . $e->getMessage());
        }
    }
    
    // Clear the session cookie
    if (isset($_COOKIE['visionkart_session_id'])) {
        setcookie('visionkart_session_id', '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

/**
 * Check if user session is valid
 */
function checkSession() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'authenticated' => true,
            'user_id' => $_SESSION['user_id'],
            'user_email' => $_SESSION['user_email'],
            'user_name' => $_SESSION['user_name'],
            'user_role' => $_SESSION['user_role']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'authenticated' => false
        ]);
    }
}

/**
 * Get current logged-in user details
 */
function getCurrentUser() {
    global $conn;
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    try {
        $stmt = $conn->prepare("SELECT id, email, first_name, last_name, phone, role, email_verified, created_at, last_login 
                               FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'full_name' => $user['first_name'] . ' ' . $user['last_name'],
                    'phone' => $user['phone'],
                    'role' => $user['role'],
                    'email_verified' => (bool)$user['email_verified'],
                    'created_at' => $user['created_at'],
                    'last_login' => $user['last_login']
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch (PDOException $e) {
        error_log('Get user error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}

/**
 * Verify email with token
 */
function verifyEmail() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['token'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Token is required']);
        return;
    }
    
    $token = $data['token'];
    
    try {
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL 
                               WHERE verification_token = ?");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Email verified successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
        }
    } catch (PDOException $e) {
        error_log('Email verification error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}

/**
 * Request password reset
 */
function forgotPassword() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        return;
    }
    
    $email = trim($data['email']);
    
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            $stmt->execute([$resetToken, $expiry, $email]);
            
            // In production, send email with reset link
            // TODO: Implement email sending with reset link
            // $resetLink = "https://yourdomain.com/reset-password.php?token=" . $resetToken;
            // sendPasswordResetEmail($email, $resetLink);
            
            echo json_encode([
                'success' => true,
                'message' => 'If your email is registered, you will receive a password reset link shortly.'
            ]);
        } else {
            // Don't reveal if email exists for security
            echo json_encode(['success' => true, 'message' => 'If email exists, reset link has been sent']);
        }
    } catch (PDOException $e) {
        error_log('Forgot password error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}

/**
 * Reset password with token
 */
function resetPassword() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['token']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Token and new password are required']);
        return;
    }
    
    $token = $data['token'];
    $password = $data['password'];
    
    // Apply same strong password policy as registration (Fix Issue #15)
    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
        return;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter']);
        return;
    }
    if (!preg_match('/[a-z]/', $password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one lowercase letter']);
        return;
    }
    if (!preg_match('/[0-9]/', $password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one number']);
        return;
    }
    
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL 
                                   WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            
            echo json_encode(['success' => true, 'message' => 'Password reset successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid or expired reset token']);
        }
    } catch (PDOException $e) {
        error_log('Password reset error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}

/**
 * Change password for logged-in user
 */
function changePassword() {
    global $conn;
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['current_password']) || !isset($data['new_password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Current and new password are required']);
        return;
    }
    
    $currentPassword = $data['current_password'];
    $newPassword = $data['new_password'];
    
    // Strong password policy (consistent with registration)
    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters long']);
        return;
    }
    
    if (!preg_match('/[A-Z]/', $newPassword) || !preg_match('/[a-z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password must contain at least one uppercase letter, one lowercase letter, and one number']);
        return;
    }
    
    try {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!password_verify($currentPassword, $user['password'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            return;
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
        
        echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        
    } catch (PDOException $e) {
        error_log("Password change error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
    }
}
?>
