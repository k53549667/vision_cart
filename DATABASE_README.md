# VisionKart Database Setup Guide

## Overview
VisionKart is an eyewear e-commerce platform that now uses a MySQL database for data persistence instead of localStorage.

## Database Setup

### Prerequisites
- XAMPP installed with Apache and MySQL running
- PHP enabled

### Setup Steps

1. **Start XAMPP Services**
   - Start Apache and MySQL from XAMPP Control Panel

2. **Run Database Setup**
   - Open command prompt or terminal
   - Navigate to your project directory: `cd d:\xampp\htdocs\vini`
   - Run: `d:\xampp\php\php.exe setup_database.php`

3. **Seed Sample Data** (Optional)
   - Run: `d:\xampp\php\php.exe seed_database.php`

### Database Structure

The database `visionkart_db` contains the following tables:

- **products**: Product catalog with pricing, inventory, and details
- **orders**: Customer orders with status tracking
- **order_items**: Individual items within orders
- **users**: User accounts (customers and admins with role-based access)
- **user_addresses**: Customer shipping addresses
- **user_sessions**: Session management for cart persistence
- **purchases**: Inventory purchase records
- **categories**: Product categories
- **cart**: Shopping cart items
- **wishlist**: Wishlist items
- **admin_users**: Legacy admin panel access (default: admin/admin123)

### API Endpoints

- `api_products.php` - Product management
- `api_orders.php` - Order management
- `api_customers.php` - Customer management
- `api_purchases.php` - Purchase management

### Admin Panel

Access the admin panel at: `http://localhost/vini/admin.html`

Default login credentials:
- Username: admin
- Password: admin123

### Features

- Real-time dashboard with statistics
- Product inventory management
- Order processing and tracking
- Customer management
- Purchase record keeping
- Category organization

### File Structure

```
/vini/
├── config.php              # Database configuration
├── setup_database.php      # Database setup script
├── seed_database.php       # Sample data seeder
├── api_products.php        # Products API
├── api_orders.php          # Orders API
├── api_customers.php       # Customers API
├── api_purchases.php       # Purchases API
├── admin.html              # Admin panel
├── admin-script-new.js     # Updated admin JavaScript
└── [other frontend files]
```

### Troubleshooting

1. **Database Connection Issues**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in `config.php`

2. **API Errors**
   - Verify PHP files are accessible
   - Check browser console for CORS issues

3. **Admin Panel Not Loading**
   - Ensure `admin-script-new.js` is properly linked
   - Check for JavaScript errors in browser console

### Security Notes

- Default admin password should be changed in production
- Database credentials should be secured
- Consider implementing user authentication for the frontend
- Add input validation and sanitization

### Next Steps

- Implement user registration and login
- Add payment gateway integration
- Create customer-facing order tracking
- Add email notifications
- Implement product image upload functionality