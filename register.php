<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VisionKart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: flex;
        }

        .register-left {
            flex: 1;
            background: linear-gradient(135deg, #00bac7 0%, #667eea 100%);
            padding: 60px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-left h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .register-left p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .benefits {
            list-style: none;
        }

        .benefits li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .benefits i {
            margin-right: 10px;
            font-size: 20px;
        }

        .register-right {
            flex: 1;
            padding: 60px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h2 {
            color: #00bac7;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .logo p {
            color: #666;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 20px;
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00bac7;
        }

        .password-strength {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s;
        }

        .password-strength-bar.weak {
            width: 33%;
            background: #ff6b6b;
        }

        .password-strength-bar.medium {
            width: 66%;
            background: #ffa500;
        }

        .password-strength-bar.strong {
            width: 100%;
            background: #4caf50;
        }

        .terms {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .terms input {
            margin-right: 10px;
        }

        .terms a {
            color: #00bac7;
            text-decoration: none;
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #00bac7 0%, #667eea 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .register-btn:hover {
            transform: translateY(-2px);
        }

        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            color: #999;
            position: relative;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #00bac7;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            color: #666;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .back-home a:hover {
            color: #00bac7;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .register-left {
                padding: 40px 30px;
            }

            .register-right {
                padding: 40px 30px;
            }

            .register-left h1 {
                font-size: 32px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <h1><i class="fas fa-glasses"></i> VisionKart</h1>
            <p>Join thousands of satisfied customers</p>
            <ul class="benefits">
                <li><i class="fas fa-check-circle"></i> Exclusive member discounts</li>
                <li><i class="fas fa-check-circle"></i> Early access to new collections</li>
                <li><i class="fas fa-check-circle"></i> Personalized recommendations</li>
                <li><i class="fas fa-check-circle"></i> Order tracking & history</li>
                <li><i class="fas fa-check-circle"></i> Saved addresses & wishlists</li>
                <li><i class="fas fa-check-circle"></i> Priority customer support</li>
            </ul>
        </div>

        <div class="register-right">
            <div class="logo">
                <h2>Create Account</h2>
                <p>Join VisionKart today</p>
            </div>

            <div id="alertBox" class="alert"></div>

            <form id="registerForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" id="firstName" name="first_name" placeholder="First name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" id="lastName" name="last_name" placeholder="Last name" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number (Optional)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="+91 12345 67890">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="terms">
                    <input type="checkbox" id="agreeTerms" required>
                    <label for="agreeTerms">I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></label>
                </div>

                <button type="submit" class="register-btn" id="registerBtn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="divider">OR</div>

            <div class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </div>

            <div class="back-home">
                <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');
        const alertBox = document.getElementById('alertBox');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');

        // Show alert message
        function showAlert(message, type = 'error') {
            alertBox.textContent = message;
            alertBox.className = `alert alert-${type} show`;
            setTimeout(() => {
                alertBox.classList.remove('show');
            }, 5000);
        }

        // Check password strength
        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            passwordStrengthBar.className = 'password-strength-bar';
            
            if (strength <= 2) {
                passwordStrengthBar.classList.add('weak');
            } else if (strength <= 3) {
                passwordStrengthBar.classList.add('medium');
            } else {
                passwordStrengthBar.classList.add('strong');
            }
        });

        // Handle registration form submission
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const agreeTerms = document.getElementById('agreeTerms').checked;

            // Validation
            if (!firstName || !lastName || !email || !password || !confirmPassword) {
                showAlert('Please fill in all required fields');
                return;
            }

            if (!agreeTerms) {
                showAlert('Please agree to the Terms & Conditions');
                return;
            }

            if (password.length < 6) {
                showAlert('Password must be at least 6 characters long');
                return;
            }

            if (password !== confirmPassword) {
                showAlert('Passwords do not match');
                return;
            }

            registerBtn.disabled = true;
            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';

            try {
                const response = await fetch('api_auth.php?action=register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        phone: phone || null,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('Account created successfully! Redirecting...', 'success');
                    
                    // Store user data in localStorage
                    localStorage.setItem('visionkart_user', JSON.stringify(data.user));
                    
                    // Redirect after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1500);
                } else {
                    showAlert(data.message || 'Registration failed');
                    registerBtn.disabled = false;
                    registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Account';
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
                registerBtn.disabled = false;
                registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Account';
            }
        });

        // Check if already logged in
        window.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('api_auth.php?action=check-session');
                const data = await response.json();
                
                if (data.authenticated) {
                    window.location.href = 'index.php';
                }
            } catch (error) {
                console.error('Session check failed:', error);
            }
        });
    </script>
</body>
</html>
