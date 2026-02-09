<?php
/**
 * Secure Admin Setup Script
 * This script should be run ONCE to create the initial admin user
 * DELETE THIS FILE AFTER RUNNING IT IN PRODUCTION
 */

session_start();

require_once __DIR__ . '/../config.php';

// Check if already run (admin exists)
$conn = getDBConnection();

// Create admin_users table if not exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'admin_users'");
if ($tableCheck->num_rows === 0) {
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
            locked_until TIMESTAMP NULL,
            must_change_password TINYINT(1) DEFAULT 1
        )
    ");
}

// Check if admin already exists
$adminCheck = $conn->query("SELECT COUNT(*) as count FROM admin_users");
$row = $adminCheck->fetch_assoc();

if ($row['count'] > 0) {
    echo "<!DOCTYPE html>
<html>
<head><title>Admin Setup</title></head>
<body style='font-family: Arial; padding: 40px;'>
<h2 style='color: orange;'>⚠️ Admin user already exists</h2>
<p>The admin setup has already been completed.</p>
<p><strong>Security Warning:</strong> Please delete this file: <code>setup/secure_admin_setup.php</code></p>
<a href='../admin/admin-pages/admin_login.php'>Go to Admin Login</a>
</body>
</html>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || strlen($username) < 4) {
        $error = 'Username must be at least 4 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Password must contain at least one uppercase letter';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = 'Password must contain at least one lowercase letter';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        // Create admin user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        $stmt = $conn->prepare("INSERT INTO admin_users (username, password, email, role, must_change_password) VALUES (?, ?, ?, 'admin', 0)");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        
        if ($stmt->execute()) {
            $success = "Admin user created successfully! Please delete this file now.";
        } else {
            $error = 'Failed to create admin user: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Admin Setup - VisionKart</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .warning strong { color: #856404; }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .btn:hover { transform: translateY(-2px); }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>🔐 Secure Admin Setup</h1>
        <p class="subtitle">Create your admin account for VisionKart</p>
        
        <div class="warning">
            <strong>⚠️ Security Notice:</strong><br>
            This script should only be run once. After creating your admin account, 
            <strong>delete this file</strong> from your server.
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <?php echo htmlspecialchars($success); ?><br><br>
                <a href="../admin/admin-pages/admin_login.php">Go to Admin Login →</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required minlength="4" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="8">
                    <p class="password-requirements">
                        Minimum 8 characters, 1 uppercase, 1 lowercase, 1 number
                    </p>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn">Create Admin Account</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
