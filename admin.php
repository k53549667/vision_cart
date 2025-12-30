<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
$adminRole = $_SESSION['admin_role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - VisionKart</title>
    <link rel="stylesheet" href="admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2><i class="fas fa-glasses"></i> VisionKart</h2>
                <p>Admin Panel</p>
            </div>
            
            <nav class="admin-nav">
                <a href="#dashboard" class="nav-item active" data-section="dashboard">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="#products" class="nav-item" data-section="products">
                    <i class="fas fa-box"></i> Products
                </a>
                <a href="#orders" class="nav-item" data-section="orders">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
                <a href="#purchases" class="nav-item" data-section="purchases">
                    <i class="fas fa-truck-loading"></i> Purchases
                </a>
                <a href="#customers" class="nav-item" data-section="customers">
                    <i class="fas fa-users"></i> Customers
                </a>
                <a href="#categories" class="nav-item" data-section="categories">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="#analytics" class="nav-item" data-section="analytics">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
                <a href="#settings" class="nav-item" data-section="settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="index.php" class="nav-item">
                    <i class="fas fa-home"></i> Back to Website
                </a>
                <a href="#" class="nav-item" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 id="pageTitle">Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">5</span>
                    </div>
                    <div class="admin-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($adminUsername); ?>&background=00bac7&color=fff" alt="<?php echo htmlspecialchars($adminUsername); ?>">
                        <span><?php echo htmlspecialchars($adminUsername); ?></span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Total Orders</h3>
                            <p class="stat-number">1,234</p>
                            <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 12% from last month</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Revenue</h3>
                            <p class="stat-number">₹2,45,678</p>
                            <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 8% from last month</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Products</h3>
                            <p class="stat-number">156</p>
                            <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 5 new this week</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h3>Customers</h3>
                            <p class="stat-number">892</p>
                            <span class="stat-change positive"><i class="fas fa-arrow-up"></i> 15% from last month</span>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Recent Orders</h3>
                            <a href="#orders" class="view-all">View All</a>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-001</td>
                                    <td>John Doe</td>
                                    <td>Round Eyeglasses</td>
                                    <td>₹1,900</td>
                                    <td><span class="status-badge pending">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD-002</td>
                                    <td>Jane Smith</td>
                                    <td>Cat-Eye Frames</td>
                                    <td>₹1,900</td>
                                    <td><span class="status-badge completed">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD-003</td>
                                    <td>Mike Johnson</td>
                                    <td>Clubmaster</td>
                                    <td>₹2,000</td>
                                    <td><span class="status-badge processing">Processing</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Top Selling Products</h3>
                        </div>
                        <div class="product-list">
                            <div class="product-item">
                                <div class="product-info">
                                    <h4>Vincent Chase Round</h4>
                                    <p>156 sold</p>
                                </div>
                                <div class="product-sales">₹2,96,400</div>
                            </div>
                            <div class="product-item">
                                <div class="product-info">
                                    <h4>Cat-Eye Transparent</h4>
                                    <p>134 sold</p>
                                </div>
                                <div class="product-sales">₹2,54,600</div>
                            </div>
                            <div class="product-item">
                                <div class="product-info">
                                    <h4>Clubmaster Classic</h4>
                                    <p>98 sold</p>
                                </div>
                                <div class="product-sales">₹1,96,000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Purchases Section -->
            <section id="purchases" class="content-section">
                <div class="section-header">
                    <h2>Purchases Management</h2>
                    <div>
                        <button class="btn-primary" id="addPurchaseBtn">
                            <i class="fas fa-plus"></i> Add Purchase
                        </button>
                        <button class="btn-secondary" onclick="loadPurchases()" style="margin-left:10px;">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="filters-bar">
                    <select id="purchaseStatusFilter">
                        <option value="all">All</option>
                        <option value="received">Received</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <input type="date" id="purchaseDateFilter">
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>City</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th>Purchase Price</th>
                                <th>Sell Price</th>
                                <th>GST %</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="purchasesTableBody">
                            <!-- Purchases will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Purchase Modal -->
            <div id="purchaseModal" class="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:10003; overflow-y:auto; padding:20px;">
                <div style="background:white; width:900px; max-width:95%; border-radius:8px; padding:24px; position:relative; margin:auto; margin-top:20px; margin-bottom:20px; box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                    <button onclick="closePurchaseModal()" style="position:absolute; right:16px; top:16px; border:none; background:transparent; font-size:20px; cursor:pointer;"><i class="fas fa-times"></i></button>
                    <h3 style="margin-top:0; color:#00bac7; border-bottom:2px solid #00bac7; padding-bottom:10px;">
                        <i class="fas fa-truck-loading"></i> Add New Purchase
                    </h3>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <!-- Left Column -->
                        <div>
                            <h4 style="color:#333; margin-bottom:15px;">Supplier Information</h4>
                            
                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-user"></i> Supplier Name <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseSupplier" type="text" placeholder="Enter supplier name" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-phone"></i> Supplier Phone
                                </label>
                                <input id="purchaseSupplierPhone" type="tel" placeholder="Enter phone number"
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-envelope"></i> Supplier Email
                                </label>
                                <input id="purchaseSupplierEmail" type="email" placeholder="Enter email address"
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-map-marker-alt"></i> City <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseCity" type="text" placeholder="Enter city" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <h4 style="color:#333; margin:20px 0 15px;">Product Details</h4>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-box"></i> Product Name <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseProductName" type="text" placeholder="Enter product name" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-tags"></i> Category <span style="color:red;">*</span>
                                </label>
                                <select id="purchaseCategory" required style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;">
                                    <option value="">Select Category</option>
                                    <option value="Eyeglasses">Eyeglasses</option>
                                    <option value="Sunglasses">Sunglasses</option>
                                    <option value="Contact Lenses">Contact Lenses</option>
                                    <option value="Kids Glasses">Kids Glasses</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-list-ol"></i> Quantity <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseQuantity" type="number" min="1" placeholder="Enter quantity" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" 
                                       onchange="calculatePurchaseTotal()" />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <h4 style="color:#333; margin-bottom:15px;">Pricing & Payment</h4>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-rupee-sign"></i> Purchase Price (per unit) <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseCostPrice" type="number" step="0.01" min="0" placeholder="0.00" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;"
                                       onchange="calculatePurchaseTotal()" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-tag"></i> Sell Price (per unit) <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseSellingPrice" type="number" step="0.01" min="0" placeholder="0.00" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-percent"></i> GST Percentage <span style="color:red;">*</span>
                                </label>
                                <select id="purchaseGst" required style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;"
                                        onchange="calculatePurchaseTotal()">
                                    <option value="0">0% - No GST</option>
                                    <option value="5">5% - Essential Goods</option>
                                    <option value="12">12% - Standard</option>
                                    <option value="18" selected>18% - Most Products</option>
                                    <option value="28">28% - Luxury Goods</option>
                                </select>
                            </div>

                            <div style="background:#f8f9fa; padding:12px; border-radius:6px; margin-bottom:15px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="color:#666;">Subtotal:</span>
                                    <span style="font-weight:600;" id="purchaseSubtotal">₹0.00</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="color:#666;">GST Amount:</span>
                                    <span style="font-weight:600; color:#ff6b6b;" id="purchaseGstAmount">₹0.00</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; padding-top:8px; border-top:2px solid #dee2e6;">
                                    <span style="font-weight:700; color:#333;">Total Amount:</span>
                                    <span style="font-weight:700; font-size:18px; color:#00bac7;" id="purchaseTotalAmount">₹0.00</span>
                                </div>
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-credit-card"></i> Payment Method
                                </label>
                                <select id="purchasePaymentMethod" style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Credit">Credit</option>
                                    <option value="UPI">UPI</option>
                                </select>
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-file-invoice"></i> Invoice Number
                                </label>
                                <input id="purchaseInvoiceNumber" type="text" placeholder="INV-XXXX"
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-calendar"></i> Purchase Date <span style="color:red;">*</span>
                                </label>
                                <input id="purchaseDate" type="date" required
                                       style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-info-circle"></i> Status
                                </label>
                                <select id="purchaseStatus" style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;">
                                    <option value="pending">Pending</option>
                                    <option value="received" selected>Received</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555;">
                                    <i class="fas fa-sticky-note"></i> Notes
                                </label>
                                <textarea id="purchaseNotes" rows="3" placeholder="Additional notes (optional)"
                                          style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px; resize:vertical;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:24px; padding-top:20px; border-top:1px solid #e0e0e0;">
                        <button class="btn-secondary" onclick="closePurchaseModal()" style="padding:12px 24px;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="btn-primary" onclick="savePurchaseFromModal()" style="padding:12px 24px;">
                            <i class="fas fa-save"></i> Save Purchase
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <section id="products" class="content-section">
                <div class="section-header">
                    <h2>Products Management</h2>
                    <button class="btn-primary" id="addProductBtn">
                        <i class="fas fa-plus"></i> Add New Product
                    </button>
                </div>

                <div class="filters-bar">
                    <select id="categoryFilter">
                        <option value="all">All Categories</option>
                        <option value="round">Round</option>
                        <option value="cat-eye">Cat-Eye</option>
                        <option value="clubmaster">Clubmaster</option>
                        <option value="transparent">Transparent</option>
                    </select>
                    <select id="brandFilter">
                        <option value="all">All Brands</option>
                        <option value="vincent-chase">Vincent Chase</option>
                        <option value="john-jacobs">John Jacobs</option>
                        <option value="ojos">OJOS</option>
                    </select>
                    <select id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category / Sub</th>
                                <th>HSN Code</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>GST %</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <!-- Products will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Orders Section -->
            <section id="orders" class="content-section">
                <div class="section-header">
                    <h2>Orders Management</h2>
                    <div>
                        <button class="btn-secondary" onclick="loadOrders()" style="margin-right: 10px;">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="btn-secondary" onclick="alert('Orders in localStorage: ' + JSON.parse(localStorage.getItem('visionkart_orders') || '[]').length)">
                            <i class="fas fa-info-circle"></i> Check Storage
                        </button>
                        <button class="btn-secondary" style="margin-left: 10px;">
                            <i class="fas fa-download"></i> Export Orders
                        </button>
                    </div>
                </div>

                <div class="filters-bar">
                    <select id="orderStatusFilter">
                        <option value="all">All Orders</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <input type="date" id="orderDateFilter">
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <!-- Orders will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Customers Section -->
            <section id="customers" class="content-section">
                <div class="section-header">
                    <h2>Customers Management</h2>
                    <button class="btn-secondary">
                        <i class="fas fa-download"></i> Export Customers
                    </button>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody">
                            <!-- Customers will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Categories Section -->
            <section id="categories" class="content-section">
                <div class="section-header">
                    <h2>Categories Management</h2>
                    <button class="btn-primary" id="addCategoryBtn">
                        <i class="fas fa-plus"></i> Add New Category
                    </button>
                </div>

                <div class="categories-grid" id="categoriesGrid">
                    <!-- Categories will be loaded here -->
                </div>
            </section>

            <!-- Analytics Section -->
            <section id="analytics" class="content-section">
                <div class="section-header">
                    <h2>Analytics & Reports</h2>
                    <div class="date-range">
                        <input type="date" id="startDate">
                        <span>to</span>
                        <input type="date" id="endDate">
                    </div>
                </div>

                <div class="analytics-grid">
                    <div class="analytics-card">
                        <h3>Sales Overview</h3>
                        <div class="chart-placeholder">
                            <i class="fas fa-chart-line fa-3x"></i>
                            <p>Sales chart will be displayed here</p>
                        </div>
                    </div>
                    <div class="analytics-card">
                        <h3>Revenue Trend</h3>
                        <div class="chart-placeholder">
                            <i class="fas fa-chart-bar fa-3x"></i>
                            <p>Revenue chart will be displayed here</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Settings Section -->
            <section id="settings" class="content-section">
                <div class="section-header">
                    <h2>Settings</h2>
                </div>

                <div class="settings-container">
                    <div class="settings-card">
                        <h3>General Settings</h3>
                        <form class="settings-form">
                            <div class="form-group">
                                <label>Website Name</label>
                                <input type="text" value="VisionKart">
                            </div>
                            <div class="form-group">
                                <label>Contact Email</label>
                                <input type="email" value="info@visionkart.com">
                            </div>
                            <div class="form-group">
                                <label>Contact Phone</label>
                                <input type="tel" value="+91 9999899998">
                            </div>
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </form>
                    </div>

                    <div class="settings-card">
                        <h3>Payment Settings</h3>
                        <form class="settings-form">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" checked> Enable Cash on Delivery
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" checked> Enable Online Payment
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <select>
                                    <option value="INR">INR (₹)</option>
                                    <option value="USD">USD ($)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <span class="close">&times;</span>
            </div>
            <form id="addProductForm" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand" required>
                            <option value="">Select Brand</option>
                            <option value="Vincent Chase">Vincent Chase</option>
                            <option value="John Jacobs">John Jacobs</option>
                            <option value="OJOS">OJOS</option>
                            <option value="VisionKart Air">VisionKart Air</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" id="mainCategory" required>
                            <option value="">Select Category</option>
                            <option value="Eyeglasses">Eyeglasses</option>
                            <option value="Sunglasses">Sunglasses</option>
                            <option value="Contact Lenses">Contact Lenses</option>
                            <option value="Accessories">Accessories</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sub Category (Frame Shape)</label>
                        <select name="subcategory" id="subCategory" required>
                            <option value="">Select Sub Category</option>
                            <!-- Will be populated from database -->
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Frame Type</label>
                        <select name="frametype" required>
                            <option value="">Select Type</option>
                            <option value="full-rim">Full Rim</option>
                            <option value="half-rim">Half Rim</option>
                            <option value="rimless">Rimless</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>HSN Code</label>
                        <input type="text" name="hsn" placeholder="e.g., 9004" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Sell Price (₹)</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Purchase Price (₹)</label>
                        <input type="number" name="originalPrice" step="0.01" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>GST Rate (%)</label>
                        <select name="gst" required>
                            <option value="">Select GST %</option>
                            <option value="0">0%</option>
                            <option value="5">5%</option>
                            <option value="12" selected>12%</option>
                            <option value="18">18%</option>
                            <option value="28">28%</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- Empty column for alignment -->
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" required>
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="text" name="color">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- Empty column for alignment -->
                    </div>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <div class="image-upload-section">
                        <div class="upload-options">
                            <button type="button" class="btn-secondary" id="openCameraBtn">
                                <i class="fas fa-camera"></i> Take Photo
                            </button>
                            <button type="button" class="btn-secondary" id="uploadImageBtn">
                                <i class="fas fa-upload"></i> Upload Image
                            </button>
                            <input type="file" id="imageFileInput" accept="image/*" style="display: none;">
                        </div>
                        <input type="text" name="image" id="imageUrlInput" placeholder="Or paste image URL/path" style="margin-top: 10px;">
                        
                        <!-- Camera Preview -->
                        <div id="cameraSection" style="display: none; margin-top: 15px;">
                            <video id="cameraPreview" autoplay playsinline style="width: 100%; max-width: 400px; border-radius: 8px;"></video>
                            <div style="margin-top: 10px;">
                                <button type="button" class="btn-primary" id="capturePhotoBtn">
                                    <i class="fas fa-camera"></i> Capture Photo
                                </button>
                                <button type="button" class="btn-secondary" id="closeCameraBtn">
                                    <i class="fas fa-times"></i> Close Camera
                                </button>
                            </div>
                        </div>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" style="display: none; margin-top: 15px;">
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px;">
                                <div id="previewImage" style="position: relative; display: flex; align-items: center; justify-content: center; min-height: 100px; border: 2px dashed #ddd; border-radius: 8px; background: #f9f9f9;">
                                    <!-- Photos will be inserted here by JavaScript -->
                                </div>
                            </div>
                            <button type="button" class="btn-secondary" id="removeImageBtn" style="margin-top: 10px;">
                                <i class="fas fa-trash"></i> Clear All Photos
                            </button>
                        </div>
                        
                        <canvas id="photoCanvas" style="display: none;"></canvas>
                    </div>
                </div>

                <div class="form-group">
                    <label>Product Video (Optional)</label>
                    <div class="video-upload-section">
                        <div class="upload-options">
                            <button type="button" class="btn-secondary" id="recordVideoBtn">
                                <i class="fas fa-video"></i> Record Video
                            </button>
                            <button type="button" class="btn-secondary" id="uploadVideoBtn">
                                <i class="fas fa-upload"></i> Upload Video
                            </button>
                            <input type="file" id="videoFileInput" accept="video/*" style="display: none;">
                        </div>
                        <input type="url" name="video" id="videoUrlInput" placeholder="Or paste video URL (YouTube, Vimeo, etc.)" style="margin-top: 10px;">
                        
                        <!-- Video Recording Section -->
                        <div id="videoRecordSection" style="display: none; margin-top: 15px;">
                            <video id="videoRecordPreview" autoplay playsinline muted style="width: 100%; max-width: 400px; border-radius: 8px;"></video>
                            <div style="margin-top: 10px;">
                                <button type="button" class="btn-primary" id="startRecordingBtn">
                                    <i class="fas fa-circle"></i> Start Recording
                                </button>
                                <button type="button" class="btn-danger" id="stopRecordingBtn" style="display: none;">
                                    <i class="fas fa-stop"></i> Stop Recording
                                </button>
                                <button type="button" class="btn-secondary" id="closeVideoBtn">
                                    <i class="fas fa-times"></i> Close
                                </button>
                                <p id="recordingTimer" style="margin-top: 10px; font-weight: bold; color: #f44336; display: none;">
                                    <i class="fas fa-circle" style="animation: blink 1s infinite;"></i> Recording: <span id="timerDisplay">00:00</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Video Preview -->
                        <div id="videoPreview" style="display: none; margin-top: 15px;">
                            <video id="previewVideo" controls style="width: 100%; max-width: 400px; border-radius: 8px;"></video>
                            <button type="button" class="btn-secondary" id="removeVideoBtn" style="margin-top: 10px;">
                                <i class="fas fa-trash"></i> Remove Video
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelAddProduct">Cancel</button>
                    <button type="submit" class="btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Category</h3>
                <button onclick="closeModal('categoryModal')" class="close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="categoryForm" onsubmit="event.preventDefault(); addCategory();">
                <div class="form-group">
                    <label for="categoryName">Category Name *</label>
                    <input type="text" id="categoryName" name="name" required>
                </div>

                <div class="form-group">
                    <label for="categoryIcon">Icon Class</label>
                    <input type="text" id="categoryIcon" name="icon" placeholder="fa-tag" value="fa-tag">
                    <small>Use FontAwesome icon classes (e.g., fa-tag, fa-glasses, fa-eye)</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('categoryModal')">Cancel</button>
                    <button type="submit" class="btn-primary submit-btn">Add Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Order Modal -->
    <div id="viewOrderModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2><i class="fas fa-receipt"></i> Order Details</h2>
                <span class="close" onclick="closeModal('viewOrderModal')">&times;</span>
            </div>
            <div id="orderDetailsContent" class="modal-body" style="padding: 20px;">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('viewOrderModal')">Close</button>
                <button type="button" class="btn-primary" onclick="printOrder()"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div id="editOrderModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Edit Order</h2>
                <span class="close" onclick="closeModal('editOrderModal')">&times;</span>
            </div>
            <form id="editOrderForm" class="modal-form">
                <input type="hidden" id="editOrderId" name="orderId">
                
                <div class="form-group">
                    <label>Order ID</label>
                    <input type="text" id="editOrderIdDisplay" readonly style="background: #f5f5f5;">
                </div>

                <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" id="editOrderCustomer" name="customer_name" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="editOrderStatus" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select id="editOrderPayment" name="payment_method">
                        <option value="">Select Payment Method</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="upi">UPI</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="netbanking">Net Banking</option>
                        <option value="wallet">Wallet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Shipping Address</label>
                    <textarea id="editOrderAddress" name="shipping_address" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Total Amount (₹)</label>
                    <input type="number" id="editOrderTotal" name="total_amount" step="0.01" readonly style="background: #f5f5f5;">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editOrderModal')">Cancel</button>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Customer Modal -->
    <div id="viewCustomerModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h2><i class="fas fa-user-circle"></i> Customer Details</h2>
                <span class="close" onclick="closeModal('viewCustomerModal')">&times;</span>
            </div>
            <div id="customerDetailsContent" class="modal-body" style="padding: 20px;">
                <!-- Customer details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('viewCustomerModal')">Close</button>
                <button type="button" class="btn-primary" onclick="editCustomerFromView()"><i class="fas fa-edit"></i> Edit Customer</button>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div id="editCustomerModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2><i class="fas fa-user-edit"></i> Edit Customer</h2>
                <span class="close" onclick="closeModal('editCustomerModal')">&times;</span>
            </div>
            <form id="editCustomerForm" class="modal-form">
                <input type="hidden" id="editCustomerId" name="customerId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="editCustomerFirstName" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="editCustomerLastName" name="last_name">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="editCustomerEmail" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" id="editCustomerPhone" name="phone">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="editCustomerStatus" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select id="editCustomerRole" name="role" required>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editCustomerModal')">Cancel</button>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="admin-script-new.js"></script>
    <script src="admin-purchases-enhanced.js"></script>
    <script>
        // Logout handler - wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (confirm('Are you sure you want to logout?')) {
                        try {
                            const response = await fetch('api_admin_auth.php?action=logout', {
                                method: 'POST',
                                credentials: 'include'
                            });
                            const data = await response.json();
                            
                            if (data.success) {
                                window.location.href = 'admin_login.php';
                            } else {
                                window.location.href = 'admin_login.php';
                            }
                        } catch (error) {
                            console.error('Logout error:', error);
                            // Redirect anyway
                            window.location.href = 'admin_login.php';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
