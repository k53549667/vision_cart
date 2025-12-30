<?php
// API endpoint to handle image uploads
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configuration
$uploadDir = 'uploads/products/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
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
            $imageType = $matches[1];
            $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($base64Data);
            
            if ($imageData === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid base64 data']);
                exit;
            }
            
            // Generate unique filename
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $imageType;
            $filepath = $uploadDir . $filename;
            
            // Save the image
            if (file_put_contents($filepath, $imageData)) {
                echo json_encode([
                    'success' => true,
                    'filename' => $filename,
                    'path' => $filepath,
                    'url' => $filepath
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
            echo json_encode([
                'success' => true,
                'filename' => $filename,
                'path' => $filepath,
                'url' => $filepath
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
