# VisionKart (snoptical) - E-Commerce Eyewear Platform

## Overview
VisionKart is a full-featured e-commerce platform for premium eyewear. Built with PHP and MySQL, it includes customer-facing shopping features and a complete admin management system.

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite enabled
- cURL extension for PHP

### Installation

1. **Clone/Copy to Web Root**
   ```bash
   # For XAMPP
   cd C:\xampp\htdocs
   git clone <repository> snoptical
   ```

2. **Configure Environment**
   ```bash
   cd snoptical
   copy .env.example .env
   ```
   Edit `.env` with your settings:
   ```env
   DB_HOST=localhost
   DB_NAME=your_database_name
   DB_USER=your_db_user
   DB_PASS=your_secure_password
   
   RAZORPAY_KEY_ID=rzp_live_xxxx
   RAZORPAY_KEY_SECRET=your_secret_key
   ```

3. **Set Up Database**
   ```bash
   # Import main schema
   mysql -u root -p your_database < DataBase/snoptical.sql
   
   # Run security migration
   http://localhost/snoptical/setup/security_tables_migration.php
   ```

4. **Create Admin Account**
   ```bash
   http://localhost/snoptical/setup/secure_admin_setup.php
   ```

5. **Verify Installation**
   - Frontend: `http://localhost/snoptical/`
   - Admin: `http://localhost/snoptical/admin/admin-pages/admin.php`

---

## 🔒 Security Configuration

### Environment Variables (.env)
**CRITICAL: Never commit `.env` to version control!**

| Variable | Description | Required |
|----------|-------------|----------|
| `DB_HOST` | Database hostname | Yes |
| `DB_NAME` | Database name | Yes |
| `DB_USER` | Database username | Yes |
| `DB_PASS` | Database password | Yes |
| `RAZORPAY_KEY_ID` | Razorpay public key | For payments |
| `RAZORPAY_KEY_SECRET` | Razorpay secret key | For payments |
| `RAZORPAY_WEBHOOK_SECRET` | Webhook verification | For payments |

### Security Features Implemented

#### ✅ CSRF Protection
- Session-based tokens for forms
- Token validation on all POST requests
- 1-hour token expiration

#### ✅ Rate Limiting
- Login: 5 attempts per 15 minutes per IP
- Registration: 3 attempts per hour per IP
- API: Configurable per-endpoint limits

#### ✅ Security Headers
- Content-Security-Policy
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- Referrer-Policy: strict-origin-when-cross-origin

#### ✅ Audit Logging
All security events logged:
- Login attempts (success/failure)
- Password changes
- Admin actions
- Payment transactions
- Unauthorized access attempts

---

## 📁 Project Structure

```
snoptical/
├── .env                    # Environment config (do not commit!)
├── .env.example           # Template for .env
├── .htaccess              # Apache security rules
├── config.php             # Application configuration
├── index.php              # Main entry point
├── session_manager.php    # Session handling
│
├── includes/              # Security utilities
│   ├── csrf.php           # CSRF protection
│   ├── rate_limiter.php   # Rate limiting
│   ├── security_headers.php # HTTP security headers
│   └── audit_log.php      # Security audit logging
│
├── api/                   # Customer APIs
│   ├── api_auth.php       # Authentication
│   ├── api_cart.php       # Shopping cart
│   ├── api_orders.php     # Order management
│   ├── api_payment.php    # Payment processing
│   ├── api_products.php   # Product catalog
│   ├── api_users.php      # User profiles
│   └── api_wishlist.php   # Wishlist
│
├── admin/                 # Admin panel
│   ├── admin-api/         # Admin APIs
│   ├── admin-css/         # Admin styles
│   ├── admin-js/          # Admin scripts
│   └── admin-pages/       # Admin UI pages
│
├── pages/                 # Customer pages
├── css/                   # Stylesheets
├── js/                    # JavaScript
├── assets/                # Images/media
├── setup/                 # Installation scripts
└── DataBase/              # SQL schemas
```

---

## 🔧 Production Deployment Checklist

### Pre-Deployment

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Use strong, unique database password
- [ ] Configure Razorpay LIVE keys (not test keys)
- [ ] Change all default passwords in database
- [ ] Remove or protect `/setup/` directory
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Configure proper CORS origins in `security_headers.php`

### Server Configuration

- [ ] PHP `display_errors = Off` in production
- [ ] PHP `log_errors = On` with secure log path
- [ ] Set `upload_max_filesize` and `post_max_size` appropriately
- [ ] Enable OPcache for performance
- [ ] Configure proper file permissions (755 for dirs, 644 for files)

### Database

- [ ] Run `security_tables_migration.php`
- [ ] Enable MySQL event scheduler for cleanup jobs
- [ ] Create separate database user with minimal privileges
- [ ] Regular backup schedule

### Apache/.htaccess

The included `.htaccess` handles:
- Setup directory protection
- Security headers
- CORS configuration
- PHP error hiding
- Common attack prevention

### Post-Deployment

- [ ] Test all payment flows with small transactions
- [ ] Verify email notifications work
- [ ] Check audit log is recording events
- [ ] Test rate limiting on login
- [ ] Verify admin panel security
- [ ] Run vulnerability scan

---

## 🏗️ API Reference

### Authentication
| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/api_auth.php?action=login` | POST | User login |
| `/api/api_auth.php?action=register` | POST | User registration |
| `/api/api_auth.php?action=logout` | POST | User logout |

### Products
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/api_products.php?action=all` | GET | No | List all products |
| `/api/api_products.php?action=get&id={id}` | GET | No | Get single product |
| `/api/api_products.php?action=category&slug={slug}` | GET | No | Products by category |

### Cart
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/api_cart.php?action=list` | GET | Session | Get cart items |
| `/api/api_cart.php?action=add` | POST | Session | Add to cart |
| `/api/api_cart.php?action=remove` | DELETE | Session | Remove from cart |

### Orders
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/api_orders.php?action=create` | POST | Yes | Create order |
| `/api/api_orders.php?action=get&id={id}` | GET | Yes | Get order (own only) |
| `/api/api_orders.php?action=list` | GET | Yes | List user's orders |

### Payments
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/api_payment.php?action=config` | GET | No | Get payment config |
| `/api/api_payment.php?action=create-order` | POST | Yes | Create Razorpay order |
| `/api/api_payment.php?action=verify` | POST | Yes | Verify payment |

---

## 🛡️ Security Best Practices

### For Developers

1. **Never hardcode credentials** - Use `.env` for all secrets
2. **Validate all input** - Use prepared statements for SQL
3. **Escape all output** - Use `htmlspecialchars()` for HTML
4. **Check authorization** - Verify user owns resources before access
5. **Log security events** - Use `audit_log()` for sensitive actions

### For Deployment

1. **Use HTTPS only** - Configure redirect in `.htaccess`
2. **Regular updates** - Keep PHP and MySQL updated
3. **Monitor logs** - Check audit_log table regularly
4. **Backup data** - Daily automated backups
5. **Penetration testing** - Regular security audits

---

## 🐛 Troubleshooting

### Common Issues

**"Database connection failed"**
- Check `.env` database credentials
- Verify MySQL is running
- Check database exists

**"CSRF token invalid"**
- Clear browser cookies
- Check session is starting properly
- Verify CSRF token is included in forms

**"Rate limit exceeded"**
- Wait 15 minutes for login attempts
- Check `rate_limit_attempts` table
- Clear old entries if needed

**"Payment verification failed"**
- Verify Razorpay keys in `.env`
- Check webhook secret matches
- Enable cURL extension

---

## 📝 License

Proprietary software. All rights reserved.

---

## 📧 Support

For technical support, contact the development team.

---

*Last Updated: June 2025*
