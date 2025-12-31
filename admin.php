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
                    <div class="notifications" onclick="showSection('orders')" style="cursor:pointer;" title="View Orders">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="ordersBadge">0</span>
                    </div>
                    <div class="admin-profile" onclick="showSection('settings')" style="cursor:pointer;" title="Settings">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($adminUsername); ?>&background=00bac7&color=fff" alt="<?php echo htmlspecialchars($adminUsername); ?>">
                        <span><?php echo htmlspecialchars($adminUsername); ?></span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section active">
                <div class="section-search-bar">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="dashboardSearch" placeholder="Search orders, products, customers..." onkeyup="searchDashboard(this.value)">
                        <button class="clear-search" onclick="clearSearch('dashboardSearch')" style="display:none;"><i class="fas fa-times"></i></button>
                    </div>
                </div>
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

                <div class="section-search-bar">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="purchaseSearch" placeholder="Search by invoice, supplier, product..." onkeyup="searchPurchases(this.value)">
                        <button class="clear-search" onclick="clearSearch('purchaseSearch')" style="display:none;"><i class="fas fa-times"></i></button>
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

            <!-- Purchase Modal - Enhanced with Multiple Products -->
            <div id="purchaseModal" class="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:10003; overflow-y:auto; padding:20px;">
                <div style="background:white; width:1100px; max-width:95%; border-radius:8px; padding:24px; padding-bottom:100px; position:relative; margin:auto; margin-top:20px; margin-bottom:40px; box-shadow:0 4px 20px rgba(0,0,0,0.15); max-height:calc(100vh - 80px); overflow-y:auto;">
                    <button onclick="closePurchaseModal()" style="position:absolute; right:16px; top:16px; border:none; background:transparent; font-size:20px; cursor:pointer; z-index:10;"><i class="fas fa-times"></i></button>
                    <h3 style="margin-top:0; color:#00bac7; border-bottom:2px solid #00bac7; padding-bottom:10px;">
                        <i class="fas fa-truck-loading"></i> Add New Purchase Bill
                    </h3>
                    
                    <!-- Supplier Information Section -->
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:15px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                <i class="fas fa-user"></i> Supplier Name <span style="color:red;">*</span>
                            </label>
                            <input id="purchaseSupplier" type="text" placeholder="Supplier name" required
                                   style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                <i class="fas fa-phone"></i> Supplier Phone
                            </label>
                            <input id="purchaseSupplierPhone" type="tel" placeholder="Phone number"
                                   style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                <i class="fas fa-map-marker-alt"></i> City <span style="color:red;">*</span>
                            </label>
                            <input id="purchaseCity" type="text" placeholder="City" required
                                   style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                <i class="fas fa-file-invoice"></i> Invoice Number
                            </label>
                            <input id="purchaseInvoiceNumber" type="text" placeholder="INV-XXXX"
                                   style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                        </div>
                    </div>

                    <!-- Products Section Header -->
                    <div style="display:flex; justify-content:space-between; align-items:center; margin:20px 0 15px; padding:10px 15px; background:#e3f2fd; border-radius:6px;">
                        <h4 style="color:#1976d2; margin:0;"><i class="fas fa-boxes"></i> Product Items</h4>
                        <button type="button" onclick="addPurchaseProductRow()" style="background:#00bac7; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-size:13px;">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>

                    <!-- Products Table -->
                    <div style="overflow-x:auto; margin-bottom:20px;">
                        <table style="width:100%; border-collapse:collapse; min-width:900px;" id="purchaseProductsTable">
                            <thead>
                                <tr style="background:#f8f9fa;">
                                    <th style="padding:12px 8px; text-align:left; font-size:12px; color:#555; border-bottom:2px solid #dee2e6;">Product Name *</th>
                                    <th style="padding:12px 8px; text-align:left; font-size:12px; color:#555; border-bottom:2px solid #dee2e6;">Category *</th>
                                    <th style="padding:12px 8px; text-align:left; font-size:12px; color:#555; border-bottom:2px solid #dee2e6;">Sub Category</th>
                                    <th style="padding:12px 8px; text-align:left; font-size:12px; color:#555; border-bottom:2px solid #dee2e6;">HSN Code</th>
                                    <th style="padding:12px 8px; text-align:center; font-size:12px; color:#555; border-bottom:2px solid #dee2e6; width:80px;">Qty *</th>
                                    <th style="padding:12px 8px; text-align:right; font-size:12px; color:#555; border-bottom:2px solid #dee2e6; width:100px;">Purchase ₹ *</th>
                                    <th style="padding:12px 8px; text-align:right; font-size:12px; color:#555; border-bottom:2px solid #dee2e6; width:100px;">Sell ₹ *</th>
                                    <th style="padding:12px 8px; text-align:center; font-size:12px; color:#555; border-bottom:2px solid #dee2e6; width:80px;">GST %</th>
                                    <th style="padding:12px 8px; text-align:right; font-size:12px; color:#555; border-bottom:2px solid #dee2e6; width:100px;">Amount</th>
                                    <th style="padding:12px 8px; text-align:center; font-size:12px; color:#555; border-bottom:2px solid #dee2e6; width:50px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="purchaseProductsBody">
                                <!-- Product rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals and Payment Section -->
                    <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-top:20px;">
                        <!-- Left Column - Payment & Notes -->
                        <div>
                            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:15px; margin-bottom:15px;">
                                <div>
                                    <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                        <i class="fas fa-calendar"></i> Purchase Date <span style="color:red;">*</span>
                                    </label>
                                    <input id="purchaseDate" type="date" required
                                           style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;" />
                                </div>
                                <div>
                                    <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
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
                                <div>
                                    <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                        <i class="fas fa-info-circle"></i> Status
                                    </label>
                                    <select id="purchaseStatus" style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px;">
                                        <option value="pending">Pending</option>
                                        <option value="received" selected>Received</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label style="display:block; font-weight:600; margin-bottom:6px; color:#555; font-size:13px;">
                                    <i class="fas fa-sticky-note"></i> Notes
                                </label>
                                <textarea id="purchaseNotes" rows="2" placeholder="Additional notes (optional)"
                                          style="width:100%; padding:10px; border:1px solid #e0e0e0; border-radius:6px; font-size:14px; resize:vertical;"></textarea>
                            </div>
                        </div>

                        <!-- Right Column - Totals -->
                        <div style="background:#f8f9fa; padding:15px; border-radius:8px; border:1px solid #e0e0e0;">
                            <h4 style="margin:0 0 15px; color:#333; font-size:14px;">Bill Summary</h4>
                            <div style="display:flex; justify-content:space-between; margin-bottom:10px; padding-bottom:10px; border-bottom:1px dashed #dee2e6;">
                                <span style="color:#666; font-size:13px;">Total Items:</span>
                                <span style="font-weight:600;" id="purchaseTotalItems">0</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                <span style="color:#666; font-size:13px;">Subtotal:</span>
                                <span style="font-weight:600;" id="purchaseSubtotal">₹0.00</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                <span style="color:#666; font-size:13px;">GST Amount:</span>
                                <span style="font-weight:600; color:#ff6b6b;" id="purchaseGstAmount">₹0.00</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; padding-top:12px; border-top:2px solid #00bac7; margin-top:10px;">
                                <span style="font-weight:700; color:#333;">Grand Total:</span>
                                <span style="font-weight:700; font-size:20px; color:#00bac7;" id="purchaseTotalAmount">₹0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer - Fixed at bottom -->
                    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:24px; padding-top:20px; border-top:1px solid #e0e0e0; background:white; position:sticky; bottom:0;">
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

                <div class="section-search-bar">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="productSearch" placeholder="Search by name, brand, category, HSN..." onkeyup="searchProducts(this.value)">
                        <button class="clear-search" onclick="clearSearch('productSearch')" style="display:none;"><i class="fas fa-times"></i></button>
                    </div>
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
                        <button class="btn-secondary">
                            <i class="fas fa-download"></i> Export Orders
                        </button>
                    </div>
                </div>

                <div class="section-search-bar">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="orderSearch" placeholder="Search by Order ID, customer name, product..." onkeyup="searchOrders(this.value)">
                        <button class="clear-search" onclick="clearSearch('orderSearch')" style="display:none;"><i class="fas fa-times"></i></button>
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

                <div class="section-search-bar">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="customerSearch" placeholder="Search by name, email, phone..." onkeyup="searchCustomers(this.value)">
                        <button class="clear-search" onclick="clearSearch('customerSearch')" style="display:none;"><i class="fas fa-times"></i></button>
                    </div>
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

                <div class="section-search-bar">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="categorySearch" placeholder="Search categories..." onkeyup="searchCategories(this.value)">
                        <button class="clear-search" onclick="clearSearch('categorySearch')" style="display:none;"><i class="fas fa-times"></i></button>
                    </div>
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
                <!-- Basic Information Section -->
                <div class="form-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-box"></i> Product Name</label>
                        <input type="text" name="name" placeholder="Enter product name" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Brand</label>
                        <div class="brand-input-wrapper">
                            <select name="brand" id="brandSelect" required>
                                <option value="">Select Brand</option>
                                <option value="Vincent Chase">Vincent Chase</option>
                                <option value="John Jacobs">John Jacobs</option>
                                <option value="OJOS">OJOS</option>
                                <option value="VisionKart Air">VisionKart Air</option>
                                <option value="__add_new__">+ Add New Brand</option>
                            </select>
                            <div id="newBrandInput">
                                <input type="text" id="customBrand" placeholder="Enter new brand name">
                                <button type="button" id="saveBrandBtn" class="btn-primary"><i class="fas fa-check"></i></button>
                                <button type="button" id="cancelBrandBtn" class="btn-secondary"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-folder"></i> Category</label>
                        <div class="category-input-wrapper">
                            <select name="category" id="mainCategory" required>
                                <option value="">Select Category</option>
                                <option value="Eyeglasses">Eyeglasses</option>
                                <option value="Sunglasses">Sunglasses</option>
                                <option value="Contact Lenses">Contact Lenses</option>
                                <option value="Accessories">Accessories</option>
                                <option value="__add_new__">+ Add New Category</option>
                            </select>
                            <div id="newCategoryInput">
                                <input type="text" id="customCategory" placeholder="Enter new category name">
                                <button type="button" id="saveCategoryBtn" class="btn-primary"><i class="fas fa-check"></i></button>
                                <button type="button" id="cancelCategoryBtn" class="btn-secondary"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tags"></i> Sub Category (Frame Shape)</label>
                        <div class="subcategory-input-wrapper">
                            <select name="subcategory" id="subCategory" required>
                                <option value="">Select Sub Category</option>
                                <option value="__add_new__">+ Add New Sub Category</option>
                            </select>
                            <div id="newSubCategoryInput">
                                <input type="text" id="customSubCategory" placeholder="Enter new sub category name">
                                <button type="button" id="saveSubCategoryBtn" class="btn-primary"><i class="fas fa-check"></i></button>
                                <button type="button" id="cancelSubCategoryBtn" class="btn-secondary"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-glasses"></i> Frame Type</label>
                        <div class="frametype-input-wrapper">
                            <select name="frametype" id="frameTypeSelect" required>
                                <option value="">Select Type</option>
                                <option value="full-rim">Full Rim</option>
                                <option value="half-rim">Half Rim</option>
                                <option value="rimless">Rimless</option>
                                <option value="__add_new__">+ Add New Frame Type</option>
                            </select>
                            <div id="newFrameTypeInput">
                                <input type="text" id="customFrameType" placeholder="Enter new frame type">
                                <button type="button" id="saveFrameTypeBtn" class="btn-primary"><i class="fas fa-check"></i></button>
                                <button type="button" id="cancelFrameTypeBtn" class="btn-secondary"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-barcode"></i> HSN Code</label>
                        <input type="text" name="hsn" placeholder="e.g., 9004" required>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="form-section-title"><i class="fas fa-rupee-sign"></i> Pricing & Tax</div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Sell Price (₹)</label>
                        <input type="number" name="price" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-shopping-cart"></i> Purchase Price (₹)</label>
                        <input type="number" name="originalPrice" step="0.01" placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-percent"></i> GST Rate</label>
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
                        <label><i class="fas fa-palette"></i> Color</label>
                        <input type="text" name="color" placeholder="e.g., Black, Gold">
                    </div>
                </div>

                <!-- Inventory Section -->
                <div class="form-section-title"><i class="fas fa-warehouse"></i> Inventory</div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-cubes"></i> Stock Quantity</label>
                        <input type="number" name="stock" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-toggle-on"></i> Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="form-section-title"><i class="fas fa-images"></i> Product Images</div>

                <div class="form-group">
                    <div class="image-upload-section">
                        <p style="color: #666; margin-bottom: 12px; font-size: 13px;"><i class="fas fa-info-circle"></i> Upload up to 4 product images. First image will be the main display image.</p>
                        <div class="upload-options">
                            <button type="button" class="btn-secondary" id="openCameraBtn">
                                <i class="fas fa-camera"></i> Take Photo
                            </button>
                            <button type="button" class="btn-secondary" id="uploadImageBtn">
                                <i class="fas fa-upload"></i> Upload Image
                            </button>
                            <input type="file" id="imageFileInput" accept="image/*" multiple style="display: none;">
                        </div>
                        <input type="text" name="image" id="imageUrlInput" placeholder="Or paste image URL/path" style="margin-top: 12px;">
                        
                        <!-- Camera Preview -->
                        <div id="cameraSection">
                            <video id="cameraPreview" autoplay playsinline></video>
                            <div style="margin-top: 12px;">
                                <button type="button" class="btn-primary" id="capturePhotoBtn">
                                    <i class="fas fa-camera"></i> Capture
                                </button>
                                <button type="button" class="btn-secondary" id="closeCameraBtn">
                                    <i class="fas fa-times"></i> Close
                                </button>
                            </div>
                        </div>
                        
                        <!-- Image Preview Grid -->
                        <div id="imagePreview">
                            <div id="previewImage">
                                <!-- Photos will be inserted here by JavaScript -->
                            </div>
                            <button type="button" class="btn-secondary" id="removeImageBtn" style="margin-top: 12px;">
                                <i class="fas fa-trash"></i> Clear All Photos
                            </button>
                        </div>
                        
                        <canvas id="photoCanvas" style="display: none;"></canvas>
                    </div>
                </div>

                <!-- Video Section -->
                <div class="form-section-title"><i class="fas fa-video"></i> Product Video (Optional)</div>

                <div class="form-group">
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
                        <input type="url" name="video" id="videoUrlInput" placeholder="Or paste video URL (YouTube, Vimeo, etc.)" style="margin-top: 12px;">
                        
                        <!-- Video Recording Section -->
                        <div id="videoRecordSection">
                            <video id="videoRecordPreview" autoplay playsinline muted></video>
                            <div style="margin-top: 12px;">
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
                        <div id="videoPreview">
                            <video id="previewVideo" controls></video>
                            <button type="button" class="btn-secondary" id="removeVideoBtn" style="margin-top: 12px;">
                                <i class="fas fa-trash"></i> Remove Video
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section-title"><i class="fas fa-align-left"></i> Description</div>

                <div class="form-group">
                    <textarea name="description" rows="4" placeholder="Enter product description..."></textarea>
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
