<?php
/**
 * CSRF Protection Utility
 * Provides functions for CSRF token generation and validation
 */

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate a CSRF token
 * Creates a new token if one doesn't exist or is expired
 * 
 * @param string $formName Optional form-specific name for multiple forms
 * @return string The CSRF token
 */
function csrf_token($formName = 'default') {
    $tokenKey = 'csrf_token_' . $formName;
    $timeKey = 'csrf_time_' . $formName;
    
    // Token lifetime: 1 hour
    $tokenLifetime = 3600;
    
    // Generate new token if doesn't exist or expired
    if (!isset($_SESSION[$tokenKey]) || 
        !isset($_SESSION[$timeKey]) || 
        (time() - $_SESSION[$timeKey]) > $tokenLifetime) {
        
        $_SESSION[$tokenKey] = bin2hex(random_bytes(32));
        $_SESSION[$timeKey] = time();
    }
    
    return $_SESSION[$tokenKey];
}

/**
 * Get CSRF token for AJAX requests (JSON format)
 * 
 * @return array Token data for JSON response
 */
function csrf_token_json() {
    return [
        'csrf_token' => csrf_token(),
        'expires_in' => 3600
    ];
}

/**
 * Generate HTML hidden input field with CSRF token
 * 
 * @param string $formName Optional form-specific name
 * @return string HTML input element
 */
function csrf_field($formName = 'default') {
    $token = csrf_token($formName);
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Generate meta tag for AJAX CSRF (place in HTML head)
 * 
 * @return string HTML meta tag
 */
function csrf_meta() {
    $token = csrf_token();
    return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
}

/**
 * Validate CSRF token from request
 * 
 * @param string|null $token Token to validate (null = get from request)
 * @param string $formName Optional form-specific name
 * @return bool True if token is valid
 */
function csrf_validate($token = null, $formName = 'default') {
    $tokenKey = 'csrf_token_' . $formName;
    $timeKey = 'csrf_time_' . $formName;
    
    // Get token from various sources if not provided
    if ($token === null) {
        // Check POST data
        if (isset($_POST['csrf_token'])) {
            $token = $_POST['csrf_token'];
        }
        // Check JSON body
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (isset($data['csrf_token'])) {
                $token = $data['csrf_token'];
            }
        }
        // Check header (for AJAX)
        if ($token === null && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }
    }
    
    // No token provided
    if (empty($token)) {
        return false;
    }
    
    // No token in session
    if (!isset($_SESSION[$tokenKey])) {
        return false;
    }
    
    // Token lifetime check (1 hour)
    if (!isset($_SESSION[$timeKey]) || (time() - $_SESSION[$timeKey]) > 3600) {
        unset($_SESSION[$tokenKey], $_SESSION[$timeKey]);
        return false;
    }
    
    // Constant-time comparison to prevent timing attacks
    return hash_equals($_SESSION[$tokenKey], $token);
}

/**
 * Validate CSRF and return error response if invalid
 * Use this at the start of API endpoints that modify data
 * 
 * @param string $formName Optional form-specific name
 * @return bool Returns true if valid, exits with error if invalid
 */
function csrf_verify_or_fail($formName = 'default') {
    if (!csrf_validate(null, $formName)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Invalid or expired security token. Please refresh the page and try again.'
        ]);
        exit;
    }
    return true;
}

/**
 * Refresh CSRF token (call after successful form submission)
 * 
 * @param string $formName Optional form-specific name
 * @return string New token
 */
function csrf_refresh($formName = 'default') {
    $tokenKey = 'csrf_token_' . $formName;
    $timeKey = 'csrf_time_' . $formName;
    
    // Force generate new token
    $_SESSION[$tokenKey] = bin2hex(random_bytes(32));
    $_SESSION[$timeKey] = time();
    
    return $_SESSION[$tokenKey];
}

/**
 * API endpoint to get fresh CSRF token
 * Call this when page loads via AJAX
 */
function handle_csrf_token_request() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && 
        isset($_GET['action']) && 
        $_GET['action'] === 'get-csrf-token') {
        
        header('Content-Type: application/json');
        echo json_encode(csrf_token_json());
        exit;
    }
}
