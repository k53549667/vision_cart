# 🚀 Quick Start - User Authentication System

## ✅ Setup Complete!

Your VisionKart authentication system is now installed and ready to use!

---

## 🎯 What You Can Do Now

### For Customers:
1. **Register** for a new account
2. **Login** to existing account
3. **Save addresses** for faster checkout
4. **View order history**
5. **Manage profile** information

### For Admins:
- Login with default admin account
- Access admin panel
- Manage all users and orders

---

## 🔑 Default Admin Account

**Email**: `admin@visionkart.com`  
**Password**: `admin123`

⚠️ **Important**: Change this password in production!

---

## 📋 Quick Test Guide

### Test 1: Registration Flow
1. Open `http://localhost/vini/register.html`
2. Fill in the registration form
3. Submit and you'll be auto-logged in
4. Redirected to home page with user menu

### Test 2: Login Flow
1. Open `http://localhost/vini/login.html`
2. Use admin credentials or your registered account
3. Click Login
4. You'll see your name in the header menu

### Test 3: API Testing
1. Open `http://localhost/vini/test-auth.html`
2. Click "Check Session" to verify API is working
3. Try other test buttons to test different endpoints
4. Results will display in JSON format

### Test 4: User Menu
1. Login to the website
2. Click on your name in the header
3. See dropdown with options:
   - My Profile
   - My Orders
   - My Addresses
   - Admin Panel (if admin)
   - Logout

---

## 🎨 Pages Available

| Page | URL | Description |
|------|-----|-------------|
| Home | `index.html` | Main website with auth integration |
| Login | `login.html` | User login page |
| Register | `register.html` | New user registration |
| Test Auth | `test-auth.html` | API testing tool |

---

## 🔗 API Endpoints Summary

### Authentication
- `POST api_auth.php?action=register` - Create account
- `POST api_auth.php?action=login` - Login
- `POST api_auth.php?action=logout` - Logout
- `GET api_auth.php?action=check-session` - Check if logged in
- `POST api_auth.php?action=forgot-password` - Request password reset
- `POST api_auth.php?action=reset-password` - Reset with token

### User Management
- `GET api_users.php?action=profile` - Get user details
- `POST api_users.php?action=update-profile` - Update profile
- `GET api_users.php?action=addresses` - Get all addresses
- `POST api_users.php?action=add-address` - Add new address
- `GET api_users.php?action=orders` - Get user orders
- `GET api_users.php?action=stats` - Get user statistics

---

## 💻 Using in Your Code

### JavaScript Example

```javascript
// Check if user is logged in
if (authManager.isAuthenticated) {
    console.log('Welcome, ' + authManager.user.name);
}

// Get current user info
const user = authManager.getUser();
console.log('Email:', user.email);
console.log('Role:', user.role);

// Require login for a page
authManager.requireAuth(); // Redirects to login if not authenticated

// Logout
await authManager.logout();
```

### PHP Example

```php
<?php
session_start();

// Check if logged in
if (isset($_SESSION['user_id'])) {
    echo "Welcome, " . $_SESSION['user_name'];
} else {
    header('Location: login.html');
    exit();
}
?>
```

---

## 🔐 Security Best Practices

✅ **Passwords are hashed** using bcrypt
✅ **SQL injection protected** with prepared statements
✅ **Session-based authentication** with PHP sessions
✅ **Input validation** on both frontend and backend
✅ **HTTPS ready** - just add SSL certificate

---

## 🐛 Troubleshooting

### "Not authenticated" errors
- Clear browser cookies
- Check if MySQL is running
- Verify session.save_path in php.ini

### Can't register new users
- Check database connection in config.php
- Ensure users table exists (run setup_users_table.php)
- Check browser console for JavaScript errors

### Login doesn't persist
- Enable sessions in PHP
- Check session.cookie_lifetime in php.ini
- Verify cookies are not blocked in browser

---

## 📚 Full Documentation

See [AUTH_SYSTEM_README.md](AUTH_SYSTEM_README.md) for complete documentation including:
- Detailed API documentation
- Database schema
- Security features
- Integration examples
- Advanced usage

---

## 🎉 You're All Set!

Your authentication system is ready to use. Start by:

1. Testing login with admin account
2. Registering a new user
3. Exploring the user menu
4. Building user-specific features

**Happy coding! 🚀**

---

## 📞 Need Help?

Check the documentation files:
- `AUTH_SYSTEM_README.md` - Complete auth documentation
- `DATABASE_README.md` - Database setup guide
- `README.md` - Main project documentation

---

**VisionKart Authentication System v1.0**  
*Secure • Scalable • Production-Ready*
