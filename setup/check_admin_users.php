<?php
// Check admin_users table structure
require_once '../config.php';

header('Content-Type: text/plain');

try {
    $conn = getDBConnection();
    
    // Check if table exists
    $result = $conn->query("SHOW TABLES LIKE 'admin_users'");
    if ($result->num_rows === 0) {
        echo "admin_users table does NOT exist!\n";
        exit;
    }
    
    echo "admin_users table EXISTS\n\n";
    
    // Describe table
    echo "Table Structure:\n";
    echo str_repeat("-", 60) . "\n";
    $result = $conn->query("DESCRIBE admin_users");
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " | " . $row['Type'] . " | " . ($row['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . " | " . ($row['Default'] ?? 'N/A') . "\n";
    }
    
    echo "\n" . str_repeat("-", 60) . "\n";
    echo "Existing Admin Users:\n";
    $result = $conn->query("SELECT id, username, email, role FROM admin_users");
    if ($result->num_rows === 0) {
        echo "No admin users found!\n";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}, Role: {$row['role']}\n";
        }
    }
    
    // Check for missing columns
    echo "\n" . str_repeat("-", 60) . "\n";
    echo "Checking for required columns...\n";
    $required = ['id', 'username', 'password', 'email', 'role', 'last_login', 'created_at', 'failed_attempts', 'locked_until'];
    $columns = [];
    $result = $conn->query("DESCRIBE admin_users");
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $missing = array_diff($required, $columns);
    if (empty($missing)) {
        echo "All required columns exist!\n";
    } else {
        echo "MISSING COLUMNS: " . implode(', ', $missing) . "\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
