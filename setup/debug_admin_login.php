<?php
/**
 * Debug script for admin login issues
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Admin Login Diagnostics</h2>";

// Step 1: Test config file
echo "<h3>1. Testing config.php</h3>";
try {
    require_once '../config.php';
    echo "<p style='color:green'>✓ config.php loaded successfully</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Error loading config.php: " . $e->getMessage() . "</p>";
    exit;
}

// Step 2: Test database connection
echo "<h3>2. Testing Database Connection</h3>";
try {
    $conn = getDBConnection();
    echo "<p style='color:green'>✓ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

// Step 3: Check if database exists
echo "<h3>3. Checking Database: " . DB_NAME . "</h3>";
$result = $conn->query("SELECT DATABASE()");
$row = $result->fetch_array();
if ($row[0] === DB_NAME) {
    echo "<p style='color:green'>✓ Connected to database: " . DB_NAME . "</p>";
} else {
    echo "<p style='color:red'>✗ Not connected to correct database. Current: " . ($row[0] ?: 'None') . "</p>";
}

// Step 4: Check admin_users table
echo "<h3>4. Checking admin_users Table</h3>";
$tableCheck = $conn->query("SHOW TABLES LIKE 'admin_users'");
if ($tableCheck->num_rows > 0) {
    echo "<p style='color:green'>✓ admin_users table exists</p>";
    
    // Check table structure
    echo "<h4>Table Structure:</h4>";
    $columns = $conn->query("DESCRIBE admin_users");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($col = $columns->fetch_assoc()) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
    }
    echo "</table>";
    
    // Check for admin users
    echo "<h4>Admin Users:</h4>";
    $users = $conn->query("SELECT id, username, email, role, last_login, failed_attempts, locked_until FROM admin_users");
    if ($users->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Last Login</th><th>Failed Attempts</th><th>Locked Until</th></tr>";
        while ($user = $users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['last_login']}</td>";
            echo "<td>{$user['failed_attempts']}</td>";
            echo "<td>{$user['locked_until']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange'>⚠ No admin users found! You need to create an admin user.</p>";
        echo "<p>Run the <a href='secure_admin_setup.php'>Secure Admin Setup</a> to create an admin account.</p>";
    }
} else {
    echo "<p style='color:red'>✗ admin_users table does NOT exist</p>";
    echo "<p>The admin_users table needs to be created. Run the <a href='secure_admin_setup.php'>Secure Admin Setup</a>.</p>";
}

// Step 5: Test password verification
echo "<h3>5. Password Hash Testing</h3>";
$testPassword = "test123";
$hash = password_hash($testPassword, PASSWORD_DEFAULT);
echo "<p>Test password: $testPassword</p>";
echo "<p>Generated hash: $hash</p>";
echo "<p>Verification: " . (password_verify($testPassword, $hash) ? "<span style='color:green'>✓ Working</span>" : "<span style='color:red'>✗ Failed</span>") . "</p>";

echo "<hr><p><strong>Diagnosis Complete</strong></p>";
?>
