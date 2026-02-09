<?php
/**
 * Fix Admin Users Table
 * Adds missing columns and optionally adds new admin users
 */

require_once '../config.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Admin Users Table</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        form { margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 5px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #00bac7; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        button:hover { background: #009aa5; }
        a { color: #00bac7; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Fix Admin Users Table</h1>
    
    <?php
    try {
        $conn = getDBConnection();
        
        // Check if table exists
        $result = $conn->query("SHOW TABLES LIKE 'admin_users'");
        if ($result->num_rows === 0) {
            echo '<div class="error">❌ admin_users table does NOT exist!</div>';
            exit;
        }
        
        echo '<div class="success">✅ admin_users table exists</div>';
        
        // Get current columns
        $columns = [];
        $result = $conn->query("DESCRIBE admin_users");
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        
        // Add missing columns
        $added = [];
        
        if (!in_array('failed_attempts', $columns)) {
            $conn->query("ALTER TABLE admin_users ADD COLUMN failed_attempts INT DEFAULT 0");
            $added[] = 'failed_attempts';
        }
        
        if (!in_array('locked_until', $columns)) {
            $conn->query("ALTER TABLE admin_users ADD COLUMN locked_until TIMESTAMP NULL");
            $added[] = 'locked_until';
        }
        
        if (!empty($added)) {
            echo '<div class="success">✅ Added missing columns: ' . implode(', ', $added) . '</div>';
        } else {
            echo '<div class="info">ℹ️ All required columns already exist</div>';
        }
        
        // Show current admin users
        echo '<h3>Current Admin Users:</h3>';
        echo '<pre>';
        $result = $conn->query("SELECT id, username, email, role, last_login FROM admin_users");
        if ($result->num_rows === 0) {
            echo "No admin users found!";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}, Role: {$row['role']}, Last Login: {$row['last_login']}\n";
            }
        }
        echo '</pre>';
        
        // Handle form submission for adding new admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
            $newUsername = trim($_POST['username'] ?? '');
            $newPassword = $_POST['password'] ?? '';
            $newEmail = trim($_POST['email'] ?? '');
            $newRole = $_POST['role'] ?? 'admin';
            
            if (empty($newUsername) || empty($newPassword)) {
                echo '<div class="error">❌ Username and password are required!</div>';
            } else {
                // Check if username exists
                $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
                $stmt->bind_param("s", $newUsername);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    echo '<div class="error">❌ Username already exists!</div>';
                } else {
                    // Create new admin
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO admin_users (username, password, email, role, failed_attempts, locked_until) VALUES (?, ?, ?, ?, 0, NULL)");
                    $stmt->bind_param("ssss", $newUsername, $hashedPassword, $newEmail, $newRole);
                    
                    if ($stmt->execute()) {
                        echo '<div class="success">✅ Admin user "' . htmlspecialchars($newUsername) . '" created successfully!</div>';
                        echo '<div class="info">ℹ️ <strong>Refresh this page to see the updated user list.</strong></div>';
                    } else {
                        echo '<div class="error">❌ Failed to create admin: ' . $stmt->error . '</div>';
                    }
                }
            }
        }
        
        // Form to add new admin
        ?>
        <h3>Add New Admin User:</h3>
        <form method="POST">
            <input type="hidden" name="add_admin" value="1">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Enter username" required>
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter password" required>
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter email">
            <label>Role:</label>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
            </select>
            <button type="submit">Add Admin User</button>
        </form>
        
        <div class="info" style="margin-top: 20px;">
            <strong>After fixing, try logging in with:</strong><br>
            Username: admin<br>
            Or create a new admin user above.
        </div>
        
        <p style="margin-top: 20px;"><a href="../admin/admin-pages/admin_login.php">← Go to Admin Login</a></p>
        
    <?php
    } catch (Exception $e) {
        echo '<div class="error">❌ ERROR: ' . $e->getMessage() . '</div>';
    }
    ?>
</div>
</body>
</html>
