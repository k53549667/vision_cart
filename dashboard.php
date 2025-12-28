<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - VisionKart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 186, 199, 0.3);
        }

        .dashboard-header h1 {
            margin: 0 0 10px 0;
            font-size: 32px;
        }

        .dashboard-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        .dashboard-sidebar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: fit-content;
            sticky: top;
            position: sticky;
            top: 90px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sidebar-menu a i {
            width: 25px;
            margin-right: 15px;
            font-size: 18px;
        }

        .sidebar-menu a:hover {
            background: #f0f9fa;
            color: #00bac7;
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
        }

        .dashboard-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            min-height: 500px;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-title i {
            color: #00bac7;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 186, 199, 0.2);
        }

        .stat-card i {
            font-size: 32px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #00bac7;
            box-shadow: 0 0 0 3px rgba(0, 186, 199, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 186, 199, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .orders-list {
            margin-top: 30px;
        }

        .order-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .order-card:hover {
            border-color: #00bac7;
            box-shadow: 0 3px 15px rgba(0, 186, 199, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .order-id {
            font-weight: 600;
            color: #333;
            font-size: 16px;
        }

        .order-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cfe2ff;
            color: #084298;
        }

        .status-shipped {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .order-detail-item {
            font-size: 14px;
        }

        .order-detail-label {
            color: #666;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .order-detail-value {
            color: #333;
            font-weight: 600;
        }

        .addresses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .address-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            transition: all 0.3s;
        }

        .address-card:hover {
            border-color: #00bac7;
            box-shadow: 0 3px 15px rgba(0, 186, 199, 0.1);
        }

        .address-card.default {
            border-color: #00bac7;
            background: #f0f9fa;
        }

        .default-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #00bac7;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .address-type {
            display: inline-block;
            background: #f0f0f0;
            padding: 4px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .address-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .address-text {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .address-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 8px 15px;
            font-size: 13px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #000;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #999;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #aaa;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .loading i {
            font-size: 40px;
            color: #00bac7;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-sidebar {
                position: static;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .addresses-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Top Banner -->
    <div class="top-banner">
        🎉 Special Offer: Get 20% OFF on your first order! Use code: FIRST20
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1 onclick="window.location.href='index.php'">VisionKart</h1>
                </div>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="dashboard.php" class="active">Dashboard</a></li>
                        <li><a href="#" onclick="authManager.logout()">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Welcome, <span id="userName">User</span>!</h1>
            <p>Manage your profile, orders, and addresses</p>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Sidebar -->
            <aside class="dashboard-sidebar">
                <ul class="sidebar-menu">
                    <li><a href="#" class="menu-item active" data-section="overview">
                        <i class="fas fa-th-large"></i> Overview
                    </a></li>
                    <li><a href="#" class="menu-item" data-section="profile">
                        <i class="fas fa-user"></i> My Profile
                    </a></li>
                    <li><a href="#" class="menu-item" data-section="orders">
                        <i class="fas fa-shopping-bag"></i> My Orders
                    </a></li>
                    <li><a href="#" class="menu-item" data-section="addresses">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                    </a></li>
                    <li><a href="#" class="menu-item" data-section="password">
                        <i class="fas fa-lock"></i> Change Password
                    </a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="dashboard-content">
                <!-- Overview Section -->
                <section id="overview" class="section active">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Dashboard Overview
                    </h2>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-shopping-bag"></i>
                            <div class="stat-value" id="totalOrders">0</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-rupee-sign"></i>
                            <div class="stat-value">₹<span id="totalSpent">0</span></div>
                            <div class="stat-label">Total Spent</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-clock"></i>
                            <div class="stat-value" id="pendingOrders">0</div>
                            <div class="stat-label">Pending Orders</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-heart"></i>
                            <div class="stat-value" id="wishlistCount">0</div>
                            <div class="stat-label">Wishlist Items</div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Quick Tip:</strong> Keep your profile and addresses updated for faster checkout!
                        </div>
                    </div>
                </section>

                <!-- Profile Section -->
                <section id="profile" class="section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        My Profile
                    </h2>

                    <div id="profileAlert"></div>

                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" disabled>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Profile
                        </button>
                    </form>
                </section>

                <!-- Orders Section -->
                <section id="orders" class="section">
                    <h2 class="section-title">
                        <i class="fas fa-shopping-bag"></i>
                        My Orders
                    </h2>

                    <div id="ordersAlert"></div>
                    <div id="ordersList" class="orders-list">
                        <div class="loading">
                            <i class="fas fa-spinner"></i>
                            <p>Loading your orders...</p>
                        </div>
                    </div>
                </section>

                <!-- Addresses Section -->
                <section id="addresses" class="section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        My Addresses
                    </h2>

                    <button class="btn btn-primary" onclick="openAddressModal()">
                        <i class="fas fa-plus"></i>
                        Add New Address
                    </button>

                    <div id="addressesAlert"></div>
                    <div id="addressesList" class="addresses-grid">
                        <div class="loading">
                            <i class="fas fa-spinner"></i>
                            <p>Loading your addresses...</p>
                        </div>
                    </div>
                </section>

                <!-- Change Password Section -->
                <section id="password" class="section">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i>
                        Change Password
                    </h2>

                    <div id="passwordAlert"></div>

                    <form id="passwordForm" style="max-width: 500px;">
                        <div class="form-group">
                            <label for="currentPassword">Current Password *</label>
                            <input type="password" id="currentPassword" name="current_password" required>
                        </div>

                        <div class="form-group">
                            <label for="newPassword">New Password *</label>
                            <input type="password" id="newPassword" name="new_password" required minlength="6">
                            <small style="color: #666; font-size: 13px;">Minimum 6 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password *</label>
                            <input type="password" id="confirmPassword" name="confirm_password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i>
                            Change Password
                        </button>
                    </form>
                </section>
            </main>
        </div>
    </div>

    <!-- Address Modal -->
    <div id="addressModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="addressModalTitle">Add New Address</h2>
                <button class="modal-close" onclick="closeAddressModal()">&times;</button>
            </div>

            <form id="addressForm">
                <input type="hidden" id="addressId" name="address_id">

                <div class="form-group">
                    <label for="addressType">Address Type *</label>
                    <select id="addressType" name="address_type" required>
                        <option value="home">Home</option>
                        <option value="work">Work</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="addressPhone">Phone Number *</label>
                    <input type="tel" id="addressPhone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="addressLine1">Address Line 1 *</label>
                    <input type="text" id="addressLine1" name="address_line1" placeholder="House No., Building Name" required>
                </div>

                <div class="form-group">
                    <label for="addressLine2">Address Line 2</label>
                    <input type="text" id="addressLine2" name="address_line2" placeholder="Road Name, Area, Colony">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State *</label>
                        <input type="text" id="state" name="state" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="postalCode">Postal Code *</label>
                        <input type="text" id="postalCode" name="postal_code" pattern="[0-9]{6}" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="India" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="isDefault" name="is_default" value="1">
                        Set as default address
                    </label>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Address
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddressModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: #333; color: white; text-align: center; padding: 20px; margin-top: 50px;">
        <p>&copy; 2025 VisionKart. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="auth.js"></script>
    <script>
        const authManager = new AuthManager();
        let currentUser = null;

        // Initialize dashboard
        async function initDashboard() {
            // Check authentication
            const isAuth = await authManager.checkSession();
            if (!isAuth) {
                window.location.href = 'login.php?redirect=dashboard.php';
                return;
            }

            // Load user data
            await loadUserProfile();
            await loadUserStats();
            await loadUserOrders();
            await loadUserAddresses();
        }

        // Load user profile
        async function loadUserProfile() {
            try {
                const response = await fetch('api_users.php?action=profile', {
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    currentUser = data.user;
                    document.getElementById('userName').textContent = data.user.full_name;
                    document.getElementById('firstName').value = data.user.first_name;
                    document.getElementById('lastName').value = data.user.last_name;
                    document.getElementById('email').value = data.user.email;
                    document.getElementById('phone').value = data.user.phone || '';
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load user statistics
        async function loadUserStats() {
            try {
                const response = await fetch('api_users.php?action=stats', {
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('totalOrders').textContent = data.stats.total_orders;
                    document.getElementById('totalSpent').textContent = data.stats.total_spent.toFixed(2);
                    document.getElementById('pendingOrders').textContent = data.stats.pending_orders;
                    document.getElementById('wishlistCount').textContent = data.stats.wishlist_count;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load user orders
        async function loadUserOrders() {
            try {
                const response = await fetch('api_users.php?action=orders', {
                    credentials: 'include'
                });
                const data = await response.json();

                const ordersList = document.getElementById('ordersList');

                if (data.success && data.orders.length > 0) {
                    ordersList.innerHTML = data.orders.map(order => `
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">Order #${order.id}</div>
                                <div class="order-status status-${order.status}">${order.status.toUpperCase()}</div>
                            </div>
                            <div class="order-details">
                                <div class="order-detail-item">
                                    <div class="order-detail-label">Date</div>
                                    <div class="order-detail-value">${new Date(order.created_at).toLocaleDateString()}</div>
                                </div>
                                <div class="order-detail-item">
                                    <div class="order-detail-label">Items</div>
                                    <div class="order-detail-value">${order.item_count} item(s)</div>
                                </div>
                                <div class="order-detail-item">
                                    <div class="order-detail-label">Total Amount</div>
                                    <div class="order-detail-value">₹${parseFloat(order.total_amount).toFixed(2)}</div>
                                </div>
                                <div class="order-detail-item">
                                    <div class="order-detail-label">Payment Method</div>
                                    <div class="order-detail-value">${order.payment_method || 'N/A'}</div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    ordersList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>No Orders Yet</h3>
                            <p>Start shopping to see your orders here!</p>
                            <br>
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i>
                                Start Shopping
                            </a>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                document.getElementById('ordersList').innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        Failed to load orders. Please try again.
                    </div>
                `;
            }
        }

        // Load user addresses
        async function loadUserAddresses() {
            try {
                const response = await fetch('api_users.php?action=addresses', {
                    credentials: 'include'
                });
                const data = await response.json();

                const addressesList = document.getElementById('addressesList');

                if (data.success && data.addresses.length > 0) {
                    addressesList.innerHTML = data.addresses.map(address => `
                        <div class="address-card ${address.is_default ? 'default' : ''}">
                            ${address.is_default ? '<div class="default-badge">DEFAULT</div>' : ''}
                            <div class="address-type">${address.address_type}</div>
                            <div class="address-name">${address.full_name}</div>
                            <div class="address-text">
                                ${address.address_line1}<br>
                                ${address.address_line2 ? address.address_line2 + '<br>' : ''}
                                ${address.city}, ${address.state} ${address.postal_code}<br>
                                Phone: ${address.phone}
                            </div>
                            <div class="address-actions">
                                ${!address.is_default ? `<button class="btn btn-primary btn-small" onclick="setDefaultAddress(${address.id})">
                                    <i class="fas fa-check"></i> Set Default
                                </button>` : ''}
                                <button class="btn btn-secondary btn-small" onclick="editAddress(${address.id})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-small" onclick="deleteAddress(${address.id})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    addressesList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>No Addresses Saved</h3>
                            <p>Add an address for faster checkout!</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading addresses:', error);
                document.getElementById('addressesList').innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        Failed to load addresses. Please try again.
                    </div>
                `;
            }
        }

        // Update profile
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                phone: document.getElementById('phone').value
            };

            try {
                const response = await fetch('api_users.php?action=update-profile', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                const alertDiv = document.getElementById('profileAlert');

                if (data.success) {
                    alertDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            ${data.message}
                        </div>
                    `;
                    await loadUserProfile();
                } else {
                    alertDiv.innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            ${data.message}
                        </div>
                    `;
                }

                setTimeout(() => {
                    alertDiv.innerHTML = '';
                }, 5000);

            } catch (error) {
                console.error('Error updating profile:', error);
            }
        });

        // Change password
        document.getElementById('passwordForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                document.getElementById('passwordAlert').innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        New passwords do not match!
                    </div>
                `;
                return;
            }

            try {
                const response = await fetch('api_auth.php?action=change-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword
                    })
                });

                const data = await response.json();
                const alertDiv = document.getElementById('passwordAlert');

                if (data.success) {
                    alertDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            ${data.message}
                        </div>
                    `;
                    document.getElementById('passwordForm').reset();
                } else {
                    alertDiv.innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            ${data.message}
                        </div>
                    `;
                }

                setTimeout(() => {
                    alertDiv.innerHTML = '';
                }, 5000);

            } catch (error) {
                console.error('Error changing password:', error);
            }
        });

        // Address modal functions
        function openAddressModal(addressId = null) {
            const modal = document.getElementById('addressModal');
            const form = document.getElementById('addressForm');
            const title = document.getElementById('addressModalTitle');

            form.reset();
            document.getElementById('country').value = 'India';

            if (addressId) {
                title.textContent = 'Edit Address';
                loadAddressData(addressId);
            } else {
                title.textContent = 'Add New Address';
                document.getElementById('addressId').value = '';
            }

            modal.classList.add('show');
        }

        function closeAddressModal() {
            document.getElementById('addressModal').classList.remove('show');
        }

        async function loadAddressData(addressId) {
            try {
                const response = await fetch(`api_users.php?action=address&id=${addressId}`, {
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    const address = data.address;
                    document.getElementById('addressId').value = address.id;
                    document.getElementById('addressType').value = address.address_type;
                    document.getElementById('fullName').value = address.full_name;
                    document.getElementById('addressPhone').value = address.phone;
                    document.getElementById('addressLine1').value = address.address_line1;
                    document.getElementById('addressLine2').value = address.address_line2 || '';
                    document.getElementById('city').value = address.city;
                    document.getElementById('state').value = address.state;
                    document.getElementById('postalCode').value = address.postal_code;
                    document.getElementById('country').value = address.country;
                    document.getElementById('isDefault').checked = address.is_default == 1;
                }
            } catch (error) {
                console.error('Error loading address:', error);
            }
        }

        // Save address
        document.getElementById('addressForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const addressId = document.getElementById('addressId').value;
            const formData = {
                address_type: document.getElementById('addressType').value,
                full_name: document.getElementById('fullName').value,
                phone: document.getElementById('addressPhone').value,
                address_line1: document.getElementById('addressLine1').value,
                address_line2: document.getElementById('addressLine2').value,
                city: document.getElementById('city').value,
                state: document.getElementById('state').value,
                postal_code: document.getElementById('postalCode').value,
                country: document.getElementById('country').value,
                is_default: document.getElementById('isDefault').checked ? 1 : 0
            };

            try {
                const url = addressId 
                    ? `api_users.php?action=update-address&id=${addressId}`
                    : 'api_users.php?action=add-address';

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    closeAddressModal();
                    await loadUserAddresses();
                    document.getElementById('addressesAlert').innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            ${data.message}
                        </div>
                    `;
                    setTimeout(() => {
                        document.getElementById('addressesAlert').innerHTML = '';
                    }, 5000);
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error saving address:', error);
                alert('Failed to save address. Please try again.');
            }
        });

        function editAddress(addressId) {
            openAddressModal(addressId);
        }

        async function setDefaultAddress(addressId) {
            if (!confirm('Set this as your default address?')) return;

            try {
                const response = await fetch(`api_users.php?action=set-default-address&id=${addressId}`, {
                    method: 'POST',
                    credentials: 'include'
                });

                const data = await response.json();

                if (data.success) {
                    await loadUserAddresses();
                    document.getElementById('addressesAlert').innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Default address updated!
                        </div>
                    `;
                    setTimeout(() => {
                        document.getElementById('addressesAlert').innerHTML = '';
                    }, 3000);
                }
            } catch (error) {
                console.error('Error setting default address:', error);
            }
        }

        async function deleteAddress(addressId) {
            if (!confirm('Are you sure you want to delete this address?')) return;

            try {
                const response = await fetch(`api_users.php?action=address&id=${addressId}`, {
                    method: 'DELETE',
                    credentials: 'include'
                });

                const data = await response.json();

                if (data.success) {
                    await loadUserAddresses();
                    document.getElementById('addressesAlert').innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Address deleted successfully!
                        </div>
                    `;
                    setTimeout(() => {
                        document.getElementById('addressesAlert').innerHTML = '';
                    }, 3000);
                }
            } catch (error) {
                console.error('Error deleting address:', error);
            }
        }

        // Sidebar navigation
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();

                // Update active menu item
                document.querySelectorAll('.menu-item').forEach(mi => mi.classList.remove('active'));
                item.classList.add('active');

                // Show corresponding section
                const sectionId = item.getAttribute('data-section');
                document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
                document.getElementById(sectionId).classList.add('active');

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Close modal on background click
        document.getElementById('addressModal').addEventListener('click', (e) => {
            if (e.target.id === 'addressModal') {
                closeAddressModal();
            }
        });

        // Handle hash navigation
        function handleHashNavigation() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                const menuItem = document.querySelector(`[data-section="${hash}"]`);
                if (menuItem) {
                    menuItem.click();
                }
            }
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', () => {
            initDashboard();
            handleHashNavigation();
        });

        // Handle hash changes
        window.addEventListener('hashchange', handleHashNavigation);
    </script>
</body>
</html>
