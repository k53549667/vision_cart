<?php
/**
 * Secure Image Upload API
 * Requires admin authentication for uploads
 */

session_start();
header('Content-Type: application/json');

// Secure CORS
$allowed_origins = ['http://localhost', 'http://127.0.0.1'];
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $allowed_origins) || strpos($origin, 'localhost') !== false) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    header('Access-Control-Allow-Origin: http://localhost');
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// CRITICAL: Require admin authentication for uploads
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required. Please login as admin.']);
    exit;
}

// Configuration - path relative to api folder, going up to project root then into assets/images
$uploadDir = '../assets/images/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

// Create upload directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if it's a base64 image upload (from camera capture)
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data && isset($data['image'])) {
        // Handle base64 image from camera capture
        $base64Image = $data['image'];
        
        // Extract the base64 data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = strtolower($matches[1]);
            
            // Validate image type
            if (!in_array($imageType, $allowedExtensions)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid image type. Allowed: ' . implode(', ', $allowedExtensions)]);
                exit;
            }
            
            $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($base64Data);
            
            if ($imageData === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid base64 data']);
                exit;
            }
            
            // Validate file size
            if (strlen($imageData) > $maxFileSize) {
                http_response_code(400);
                echo json_encode(['error' => 'File too large. Maximum size is 5MB']);
                exit;
            }
            
            // Verify the data is actually a valid image
            $imageInfo = @getimagesizefromstring($imageData);
            if ($imageInfo === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid image data. File is not a valid image.']);
                exit;
            }
            
            // Generate unique filename with sanitization
            $filename = 'product_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $imageType;
            $filepath = $uploadDir . $filename;
            
            // Save the image
            if (file_put_contents($filepath, $imageData)) {
                // Return web-accessible URL path
                $webUrl = 'assets/images/' . $filename;
                echo json_encode([
                    'success' => true,
                    'filename' => $filename,
                    'path' => $filepath,
                    'url' => $webUrl
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to save image']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid image format']);
        }
        exit;
    }
    
    // Handle file upload (from file input)
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
                UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
            ];
            http_response_code(400);
            echo json_encode(['error' => $errorMessages[$file['error']] ?? 'Unknown upload error']);
            exit;
        }
        
        // Check file size
        if ($file['size'] > $maxFileSize) {
            http_response_code(400);
            echo json_encode(['error' => 'File is too large. Maximum size is 5MB']);
            exit;
        }
        
        // Check file type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        if (!in_array($mimeType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP']);
            exit;
        }
        
        // Get file extension
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!$extension) {
            $extension = explode('/', $mimeType)[1];
        }
        
        // Generate unique filename
        $filename = 'product_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Return web-accessible URL path
            $webUrl = 'assets/images/' . $filename;
            echo json_encode([
                'success' => true,
                'filename' => $filename,
                'path' => $filepath,
                'url' => $webUrl
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save file']);
        }
        exit;
    }
    
    http_response_code(400);
    echo json_encode(['error' => 'No image data provided']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>
