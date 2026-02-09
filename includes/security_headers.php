<?php
/**
 * Security Headers Utility
 * Sets security headers for PHP responses
 * Include this at the top of PHP files that output content
 */

/**
 * Set all security headers
 * Call this before any output
 */
function set_security_headers() {
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Enable XSS filter
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Remove PHP version
    header_remove('X-Powered-By');
    
    // Permissions policy
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

/**
 * Set security headers for API responses
 */
function set_api_security_headers() {
    set_security_headers();
    header('Content-Type: application/json; charset=utf-8');
    
    // Cache control for sensitive data
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}

/**
 * Set CORS headers for API
 * 
 * @param array $allowedOrigins List of allowed origins
 * @param bool $allowCredentials Whether to allow credentials
 */
function set_cors_headers($allowedOrigins = null, $allowCredentials = true) {
    // Load from environment if available
    if ($allowedOrigins === null) {
        require_once __DIR__ . '/../env_loader.php';
        $appUrl = env('APP_URL', 'http://localhost');
        $allowedOrigins = [$appUrl, 'http://localhost', 'http://127.0.0.1'];
        
        // Add production URL if in production
        if (EnvLoader::isProduction()) {
            // Add your production domain here
            $allowedOrigins[] = 'https://yourdomain.com';
        }
    }
    
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    
    // Check if origin is allowed
    $isAllowed = false;
    foreach ($allowedOrigins as $allowed) {
        if ($origin === $allowed || strpos($origin, 'localhost') !== false) {
            $isAllowed = true;
            break;
        }
    }
    
    if ($isAllowed && !empty($origin)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('Access-Control-Allow-Origin: ' . $allowedOrigins[0]);
    }
    
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token, Authorization');
    
    if ($allowCredentials) {
        header('Access-Control-Allow-Credentials: true');
    }
    
    // Handle preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Max-Age: 86400'); // 24 hours
        http_response_code(200);
        exit;
    }
}

/**
 * Sanitize output to prevent XSS
 * 
 * @param string $string Input string
 * @return string Sanitized string
 */
function escape_html($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitize output for JSON (already safe, but ensures encoding)
 * 
 * @param mixed $data Data to encode
 * @return string JSON string
 */
function safe_json_encode($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 * Validate and sanitize input
 * 
 * @param string $input Raw input
 * @param string $type Type of validation (email, int, string, etc.)
 * @return mixed Sanitized value or false on failure
 */
function sanitize_input($input, $type = 'string') {
    $input = trim($input);
    
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL) ? 
                   filter_var($input, FILTER_SANITIZE_EMAIL) : false;
        
        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT) !== false ? 
                   (int)$input : false;
        
        case 'float':
            return filter_var($input, FILTER_VALIDATE_FLOAT) !== false ? 
                   (float)$input : false;
        
        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL) ? 
                   filter_var($input, FILTER_SANITIZE_URL) : false;
        
        case 'alpha':
            return preg_match('/^[a-zA-Z]+$/', $input) ? $input : false;
        
        case 'alphanumeric':
            return preg_match('/^[a-zA-Z0-9]+$/', $input) ? $input : false;
        
        case 'phone':
            $phone = preg_replace('/[^0-9+]/', '', $input);
            return strlen($phone) >= 10 ? $phone : false;
        
        case 'string':
        default:
            // Remove null bytes and excessive whitespace
            $input = str_replace(chr(0), '', $input);
            $input = preg_replace('/\s+/', ' ', $input);
            return $input;
    }
}

/**
 * Log security event
 * 
 * @param string $event Event type
 * @param array $data Additional data
 */
function log_security_event($event, $data = []) {
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'data' => $data
    ];
    
    // Log to PHP error log (you can change this to a file or database)
    error_log('[SECURITY] ' . json_encode($logData));
}
