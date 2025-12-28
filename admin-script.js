// Sample Data
const sampleProducts = [
    { id: 1, name: "Vincent Chase Round Classic", category: "Eyeglasses", subcategory: "Round", hsn: "9004", brand: "Vincent Chase", price: 1900, gst: 12, stock: 45, status: "active", image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=100&h=100&fit=crop" },
    { id: 2, name: "Cat-Eye Transparent", category: "Eyeglasses", subcategory: "Cat-Eye", hsn: "9004", brand: "Vincent Chase", price: 1900, gst: 12, stock: 32, status: "active", image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=100&h=100&fit=crop" },
    { id: 3, name: "Clubmaster Classic", category: "Sunglasses", subcategory: "Clubmaster", hsn: "9004", brand: "John Jacobs", price: 2000, gst: 12, stock: 28, status: "active", image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=100&h=100&fit=crop" },
    { id: 4, name: "OJOS Clear Round", category: "Eyeglasses", subcategory: "Transparent", hsn: "9004", brand: "OJOS", price: 750, gst: 12, stock: 56, status: "active", image: "https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=100&h=100&fit=crop" },
    { id: 5, name: "VisionKart Air Round", category: "Eyeglasses", subcategory: "Round", hsn: "9004", brand: "VisionKart Air", price: 1900, gst: 12, stock: 38, status: "active", image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=100&h=100&fit=crop" },
];

const sampleOrders = [
    { id: "ORD-001", customer: "John Doe", products: "Round Eyeglasses", amount: 1900, date: "2024-12-05", status: "pending" },
    { id: "ORD-002", customer: "Jane Smith", products: "Cat-Eye Frames", amount: 1900, date: "2024-12-04", status: "completed" },
    { id: "ORD-003", customer: "Mike Johnson", products: "Clubmaster", amount: 2000, date: "2024-12-03", status: "processing" },
    { id: "ORD-004", customer: "Sarah Williams", products: "Transparent Round", amount: 750, date: "2024-12-02", status: "completed" },
    { id: "ORD-005", customer: "David Brown", products: "Round + Cat-Eye", amount: 3800, date: "2024-12-01", status: "cancelled" },
];

const sampleCustomers = [
    { id: "CUST-001", name: "John Doe", email: "john@example.com", phone: "+91 9876543210", orders: 5, totalSpent: 9500, joined: "2024-01-15" },
    { id: "CUST-002", name: "Jane Smith", email: "jane@example.com", phone: "+91 9876543211", orders: 3, totalSpent: 5700, joined: "2024-02-20" },
    { id: "CUST-003", name: "Mike Johnson", email: "mike@example.com", phone: "+91 9876543212", orders: 7, totalSpent: 13300, joined: "2024-01-10" },
    { id: "CUST-004", name: "Sarah Williams", email: "sarah@example.com", phone: "+91 9876543213", orders: 2, totalSpent: 3800, joined: "2024-03-05" },
    { id: "CUST-005", name: "David Brown", email: "david@example.com", phone: "+91 9876543214", orders: 4, totalSpent: 7600, joined: "2024-02-28" },
];

const categories = [
    { name: "Round", icon: "fa-circle", products: 15 },
    { name: "Cat-Eye", icon: "fa-cat", products: 15 },
    { name: "Clubmaster", icon: "fa-glasses", products: 15 },
    { name: "Transparent", icon: "fa-eye", products: 15 },
];

// Load Purchases
function loadPurchases() {
    const tbody = document.getElementById('purchasesTableBody');
    if (!tbody) return;

    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    tbody.innerHTML = '';

    if (purchases.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-boxes" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                    <p>No purchases recorded</p>
                </td>
            </tr>
        `;
        return;
    }

    purchases.sort((a, b) => new Date(b.date) - new Date(a.date));

    purchases.forEach(purchase => {
        const purchaseDate = new Date(purchase.date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
        const itemsText = (purchase.items || []).map(i => `${i.name} (x${i.quantity})`).join(', ');

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>#${purchase.id}</strong></td>
            <td>${purchase.supplier || 'N/A'}</td>
            <td title="${itemsText}">${(purchase.items || []).length} item(s)</td>
            <td>₹${(purchase.total || 0).toLocaleString()}</td>
            <td>${purchaseDate}</td>
            <td><span class="status-badge ${String(purchase.status || '').toLowerCase()}">${purchase.status || 'Pending'}</span></td>
            <td>${purchase.category || ''}</td>
            <td>${purchase.city || ''}</td>
            <td>${purchase.gst || 0}%</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn invoice" onclick="generatePurchaseInvoice(${JSON.stringify(purchase.id)})" title="Purchase Invoice">
                        <i class="fas fa-file-invoice"></i>
                    </button>
                    <button class="action-btn view" onclick="viewPurchase(${JSON.stringify(purchase.id)})">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Open the purchase modal
function addPurchase() {
    openPurchaseModal();
}

function openPurchaseModal() {
    console.log('openPurchaseModal called');
    const modal = document.getElementById('purchaseModal');
    if (!modal) {
        console.log('Purchase modal not found');
        return alert('Purchase modal not found');
    }
    console.log('Modal found, opening');
    document.getElementById('purchaseSupplier').value = '';
    document.getElementById('purchaseItems').value = '';
    document.getElementById('purchaseStatus').value = 'Received';
    const today = new Date().toISOString().slice(0,10);
    document.getElementById('purchaseDate').value = today;
    modal.style.display = 'flex';
    // Prevent background scrolling
    document.body.style.overflow = 'hidden';

    // Focus first field
    setTimeout(() => document.getElementById('purchaseSupplier').focus(), 50);

    // Click-outside handler
    const outsideHandler = function(e) {
        if (e.target === modal) {
            closePurchaseModal();
        }
    };
    modal._outsideHandler = outsideHandler;
    document.addEventListener('click', outsideHandler);

    // Escape key handler
    const escHandler = function(e) {
        if (e.key === 'Escape') closePurchaseModal();
    };
    modal._escHandler = escHandler;
    document.addEventListener('keydown', escHandler);
}

function closePurchaseModal() {
    const modal = document.getElementById('purchaseModal');
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';

    // Remove handlers if attached
    if (modal._outsideHandler) {
        document.removeEventListener('click', modal._outsideHandler);
        modal._outsideHandler = null;
    }
    if (modal._escHandler) {
        document.removeEventListener('keydown', modal._escHandler);
        modal._escHandler = null;
    }
}

function savePurchaseFromModal() {
    const supplier = document.getElementById('purchaseSupplier').value.trim();
    const itemsRaw = document.getElementById('purchaseItems').value.trim();
    const status = document.getElementById('purchaseStatus').value;
    const dateVal = document.getElementById('purchaseDate').value;

    if (!supplier) return alert('Please enter supplier name');
    if (!itemsRaw) return alert('Please add at least one item');

    const items = itemsRaw.split('\n').map(line => {
        const parts = line.split(',').map(p => p.trim());
        return { name: parts[0] || 'Item', quantity: Number(parts[1]) || 1, price: Number(parts[2]) || 0 };
    }).filter(i => i.name);

    const total = items.reduce((s, it) => s + (it.quantity * it.price), 0);
    const purchase = {
        id: 'PUR-' + Date.now(),
        supplier,
        items,
        total,
        date: dateVal ? new Date(dateVal).toISOString() : new Date().toISOString(),
        status: status || 'Received'
    };

    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    purchases.push(purchase);
    localStorage.setItem('visionkart_purchases', JSON.stringify(purchases));

    // Update product stocks based on purchase items
    try {
        const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');

        purchase.items.forEach(it => {
            // Try to find by exact id match first
            let prod = storedProducts.find(p => p.id == it.id) || sampleProducts.find(p => String(p.id) === String(it.id));

            // If not found by id, try to match by name (case-insensitive contains)
            if (!prod) {
                const nameLower = (it.name || '').toLowerCase();
                prod = storedProducts.find(p => (p.name || '').toLowerCase().includes(nameLower)) || sampleProducts.find(p => (p.name || '').toLowerCase().includes(nameLower));
            }

            if (prod) {
                prod.stock = (Number(prod.stock) || 0) + (Number(it.quantity) || 0);

                // If product exists in storedProducts update it there as well
                const idx = storedProducts.findIndex(p => p.id == prod.id);
                if (idx > -1) storedProducts[idx] = prod;
            }
        });

        localStorage.setItem('visionkart_products', JSON.stringify(storedProducts));
        // Also update in-memory sampleProducts where applicable
        purchase.items.forEach(it => {
            const idx = sampleProducts.findIndex(p => (p.name || '').toLowerCase().includes((it.name||'').toLowerCase()));
            if (idx > -1) sampleProducts[idx].stock = (Number(sampleProducts[idx].stock) || 0) + (Number(it.quantity) || 0);
        });
    } catch (e) {
        console.error('Error updating product stocks from purchase', e);
    }

    closePurchaseModal();
    loadPurchases();
    loadProducts();
    showNotification('Purchase added and stock updated: ' + purchase.id, 'success');
}

// Generate printable invoice for a purchase and open in new window
function generatePurchaseInvoice(purchaseId) {
    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    const p = purchases.find(x => String(x.id) === String(purchaseId));
    if (!p) return alert('Purchase not found');

    const invoiceDate = new Date(p.date).toLocaleString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    const itemsRows = (p.items || []).map(it => `
        <tr>
            <td>${it.name}</td>
            <td style="text-align:center">${it.quantity}</td>
            <td style="text-align:right">₹${it.price.toLocaleString()}</td>
            <td style="text-align:right">₹${(it.price * it.quantity).toLocaleString()}</td>
        </tr>
    `).join('');

    const subtotal = (p.items || []).reduce((s, it) => s + (it.price * it.quantity), 0);

    const html = `
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8" />
            <title>Purchase Invoice - ${p.id}</title>
            <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px;color:#222}table{width:100%;border-collapse:collapse;margin-top:12px}th,td{padding:8px;border:1px solid #eee}th{background:#f7f7f7;text-align:left}.right{text-align:right}.muted{color:#666}</style>
        </head>
        <body>
            <h2>VisionKart — Purchase Invoice</h2>
            <div style="display:flex; justify-content:space-between; margin-top:8px">
                <div><strong>Supplier:</strong> ${p.supplier}</div>
                <div style="text-align:right"><strong>Purchase #</strong> ${p.id}<br/><span class="muted">${invoiceDate}</span></div>
            </div>
            <table>
                <thead><tr><th>Item</th><th style="width:80px;text-align:center">Qty</th><th style="width:140px;text-align:right">Rate</th><th style="width:160px;text-align:right">Amount</th></tr></thead>
                <tbody>${itemsRows}</tbody>
                <tfoot>
                    <tr><td class="no-border"></td><td class="no-border"></td><td class="right"><strong>Subtotal</strong></td><td class="right">₹${subtotal.toLocaleString()}</td></tr>
                    <tr><td class="no-border"></td><td class="no-border"></td><td class="right"><strong>Total</strong></td><td class="right"><strong>₹${p.total.toLocaleString()}</strong></td></tr>
                </tfoot>
            </table>
            <div style="margin-top:14px"><button onclick="window.print()" style="padding:10px 14px;background:#00bac7;color:#fff;border:none;border-radius:6px;cursor:pointer">Print / Save as PDF</button></div>
        </body>
        </html>
    `;

    const w = window.open('', '_blank');
    if (!w) return alert('Popup blocked. Allow popups to generate the invoice.');
    w.document.write(html);
    w.document.close();
}

function viewPurchase(purchaseId) {
    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    const p = purchases.find(x => String(x.id) === String(purchaseId));
    if (!p) return alert('Purchase not found');

    const itemsText = (p.items || []).map(i => `${i.name} — ${i.quantity} x ₹${i.price}`).join('\n');
    alert(`Purchase ${p.id}\nSupplier: ${p.supplier}\nTotal: ₹${p.total}\nDate: ${new Date(p.date).toLocaleString()}\n\nItems:\n${itemsText}`);
}

// Navigation
document.addEventListener('DOMContentLoaded', function() {
    initializeAdmin();
    
    // Check if there are no orders, add sample order for demonstration
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    if (orders.length === 0) {
        const sampleOrder = {
            id: Date.now(),
            customer: "demo@visionkart.com",
            items: [
                { 
                    id: 1, 
                    name: "Vincent Chase Round Eyeglasses", 
                    quantity: 1, 
                    price: 1999,
                    image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=100"
                }
            ],
            total: 1999,
            date: new Date().toISOString(),
            status: "Pending"
        };
        localStorage.setItem('visionkart_orders', JSON.stringify([sampleOrder]));
    }
    
    // Load all data
    loadProducts();
    loadOrders();
    loadCustomers();
    loadCategories();
    updateDashboardStats();
});

function initializeAdmin() {
    // Navigation click handlers
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            if (section) {
                switchSection(section);
            }
        });
    });

    // Menu toggle for mobile
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Add Product Modal
    const addProductBtn = document.getElementById('addProductBtn');
    const modal = document.getElementById('addProductModal');
    const closeModal = modal.querySelector('.close');
    const cancelBtn = document.getElementById('cancelAddProduct');

    if (addProductBtn) {
        addProductBtn.addEventListener('click', function() {
            modal.classList.add('active');
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function() {
            modal.classList.remove('active');
            stopCamera();
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.classList.remove('active');
            stopCamera();
        });
    }

    // Close modal on outside click
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            stopCamera();
        }
    });

    // Camera functionality
    initializeCameraFeature();
    
    // Video functionality
    initializeVideoFeature();
    
    // GST calculation
    setupGSTCalculation();

    // Add Product Form
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addNewProduct(new FormData(this));
            modal.classList.remove('active');
            this.reset();
        });
    }

    // Add Purchase button (open modal)
    const addPurchaseBtn = document.getElementById('addPurchaseBtn');
    if (addPurchaseBtn) {
        addPurchaseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openPurchaseModal();
        });
    }

    // Logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'index.php';
            }
        });
    }
}

function switchSection(sectionName) {
    // Remove active class from all sections and nav items
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Add active class to selected section and nav item
    const section = document.getElementById(sectionName);
    if (section) {
        section.classList.add('active');
    }

    const navItem = document.querySelector(`[data-section="${sectionName}"]`);
    if (navItem) {
        navItem.classList.add('active');
    }

    // Update page title
    const pageTitle = document.getElementById('pageTitle');
    if (pageTitle) {
        pageTitle.textContent = sectionName.charAt(0).toUpperCase() + sectionName.slice(1);
    }
    
    // Reload data when switching to specific sections
    if (sectionName === 'orders') {
        loadOrders();
        // Set up auto-refresh for orders page
        clearInterval(window.ordersRefreshInterval);
        window.ordersRefreshInterval = setInterval(() => {
            if (document.getElementById('orders')?.classList.contains('active')) {
                console.log('Auto-refreshing orders...');
                loadOrders();
            }
        }, 3000); // Refresh every 3 seconds
    } else {
        // Clear auto-refresh when leaving orders page
        clearInterval(window.ordersRefreshInterval);
        
        if (sectionName === 'purchases') {
            loadPurchases();
        } else if (sectionName === 'customers') {
            loadCustomers();
        } else if (sectionName === 'products') {
            loadProducts();
        } else if (sectionName === 'dashboard') {
            updateDashboardStats();
        }
    }
}

// Load Products
function loadProducts() {
    const tbody = document.getElementById('productsTableBody');
    if (!tbody) return;

    // Get both hardcoded and localStorage products
    const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');
    const deletedProductIds = JSON.parse(localStorage.getItem('visionkart_deleted_products') || '[]');
    
    // Merge all products and filter out deleted ones
    let allProducts = [...sampleProducts, ...storedProducts].filter(p => !deletedProductIds.includes(p.id));
    
    // Remove duplicates by ID
    allProducts = allProducts.filter((product, index, self) => 
        index === self.findIndex(p => p.id === product.id)
    );

    tbody.innerHTML = '';
    
    if (allProducts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align: center; padding: 30px;">No products available</td></tr>';
        return;
    }
    
    allProducts.forEach(product => {
        const priceWithGst = (product.price * (1 + (product.gst || 12) / 100)).toFixed(2);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><img src="${product.image}" alt="${product.name}" class="product-img"></td>
            <td>${product.name}</td>
            <td>${product.category || product.type || 'N/A'} / ${product.subcategory || product.category || 'N/A'}</td>
            <td>${product.hsn || 'N/A'}</td>
            <td>${product.brand || 'N/A'}</td>
            <td>₹${product.price}<br><small style="color: #666;">(₹${priceWithGst} with GST)</small></td>
            <td>${product.gst || 12}%</td>
            <td>${product.stock || 0}</td>
            <td><span class="status-badge ${product.status || 'active'}">${product.status || 'active'}</span></td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" onclick="editProduct(${product.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteProduct(${product.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Load Orders
function loadOrders() {
    console.log('=== loadOrders function called ===');
    
    const tbody = document.getElementById('ordersTableBody');
    console.log('tbody element:', tbody);
    
    if (!tbody) {
        console.error('ordersTableBody element not found!');
        return;
    }

    // Load orders from localStorage
    const ordersString = localStorage.getItem('visionkart_orders');
    console.log('Raw localStorage data:', ordersString);
    
    const orders = JSON.parse(ordersString || '[]');
    
    console.log('Loading orders:', orders.length, 'orders found');
    console.log('Orders data:', orders);
    
    tbody.innerHTML = '';
    
    if (orders.length === 0) {
        console.log('No orders found, showing empty state');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                    <p>No orders yet</p>
                    <p style="font-size: 14px;">Orders will appear here when customers place orders</p>
                </td>
            </tr>
        `;
        return;
    }
    
    console.log('Building order rows...');
    
    // Sort orders by date (newest first)
    orders.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    orders.forEach((order, index) => {
        console.log(`Processing order ${index + 1}:`, order);
        
        // Format date
        const orderDate = new Date(order.date);
        const formattedDate = orderDate.toLocaleDateString('en-IN', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
        
        // Get product names
        const productNames = order.items.map(item => `${item.name} (x${item.quantity})`).join(', ');
        const productCount = order.items.reduce((sum, item) => sum + item.quantity, 0);
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>#${order.id}</strong></td>
            <td>${order.customer}</td>
            <td>
                <span title="${productNames}">${productCount} item(s)</span>
            </td>
            <td>₹${order.total.toLocaleString()}</td>
            <td>${formattedDate}</td>
            <td><span class="status-badge ${order.status.toLowerCase()}">${order.status}</span></td>
            <td>
                <div class="action-btns">
                    <button class="action-btn view" onclick="viewOrder(${JSON.stringify(order.id)})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn invoice" onclick="generateInvoice(${JSON.stringify(order.id)})" title="Generate Invoice">
                        <i class="fas fa-file-invoice"></i>
                    </button>
                    <button class="action-btn edit" onclick="updateOrderStatus(${JSON.stringify(order.id)})">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
        console.log(`Row ${index + 1} added to table`);
    });
    
    console.log('All rows added. Table has', tbody.children.length, 'rows');
    
    // Check if the orders section is visible
    const ordersSection = document.getElementById('orders');
    console.log('Orders section element:', ordersSection);
    console.log('Orders section has active class:', ordersSection?.classList.contains('active'));
    console.log('Orders section display style:', ordersSection ? window.getComputedStyle(ordersSection).display : 'N/A');
    
    // Update dashboard stats
    updateDashboardStats();
}

// Generate printable invoice for an order and open in new window
function generateInvoice(orderId) {
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    const order = orders.find(o => String(o.id) === String(orderId));
    if (!order) {
        alert('Order not found');
        return;
    }

    const invoiceDate = new Date(order.date).toLocaleString('en-IN', {
        day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });

    const itemsRows = (order.items || []).map(it => `
        <tr>
            <td>${it.name}</td>
            <td style="text-align:center">${it.quantity}</td>
            <td style="text-align:right">₹${it.price.toLocaleString()}</td>
            <td style="text-align:right">₹${(it.price * it.quantity).toLocaleString()}</td>
        </tr>
    `).join('');

    const subtotal = (order.items || []).reduce((s, it) => s + (it.price * it.quantity), 0);

    const invoiceHTML = `
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8" />
            <title>Invoice - ${order.id}</title>
            <style>
                body { font-family: Arial, Helvetica, sans-serif; color: #222; padding: 24px; }
                .inv-header { display:flex; justify-content:space-between; align-items:center; }
                h2 { margin: 0; }
                table { width:100%; border-collapse: collapse; margin-top: 18px; }
                th, td { padding: 8px; border: 1px solid #eee; }
                th { background: #f7f7f7; text-align: left; }
                .right { text-align: right; }
                .no-border { border: none; }
                .muted { color: #666; font-size: 13px; }
                .actions { margin-top: 18px; }
                .btn { padding: 10px 14px; background: #00bac7; color: white; border: none; border-radius: 6px; cursor: pointer; }
            </style>
        </head>
        <body>
            <div class="inv-header">
                <div>
                    <h2>VisionKart</h2>
                    <div class="muted">Premium Eyewear | GSTIN: XX-XXXXX</div>
                </div>
                <div style="text-align:right">
                    <h3>Invoice</h3>
                    <div>Invoice #: <strong>${order.id}</strong></div>
                    <div>Date: ${invoiceDate}</div>
                </div>
            </div>

            <hr />

            <div style="display:flex; justify-content:space-between; gap:20px; margin-top:12px;">
                <div>
                    <div><strong>Bill To:</strong></div>
                    <div>${order.customer}</div>
                    ${order.deliveryAddress ? `<div>${order.deliveryAddress.name || ''}</div><div>${order.deliveryAddress.phone || ''}</div><div class='muted'>${order.deliveryAddress.address || ''}</div>` : ''}
                </div>
                <div style="text-align:right">
                    <div><strong>Payment:</strong> ${order.paymentMethod || 'N/A'}</div>
                    <div class="muted">Status: ${order.status}</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="width:80px; text-align:center">Qty</th>
                        <th style="width:140px; text-align:right">Rate</th>
                        <th style="width:160px; text-align:right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsRows}
                </tbody>
                <tfoot>
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="right"><strong>Subtotal</strong></td>
                        <td class="right">₹${subtotal.toLocaleString()}</td>
                    </tr>
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border"></td>
                        <td class="right"><strong>Total</strong></td>
                        <td class="right"><strong>₹${order.total.toLocaleString()}</strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="actions">
                <button class="btn" onclick="window.print()">Print / Save as PDF</button>
            </div>

        </body>
        </html>
    `;

    const w = window.open('', '_blank');
    if (!w) {
        alert('Popup blocked. Please allow popups for this site to generate the invoice.');
        return;
    }
    w.document.write(invoiceHTML);
    w.document.close();
}

// Load Customers
function loadCustomers() {
    const tbody = document.getElementById('customersTableBody');
    if (!tbody) return;

// Load Purchases
function loadPurchases() {
    const tbody = document.getElementById('purchasesTableBody');
    if (!tbody) return;

    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    tbody.innerHTML = '';

    if (purchases.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-boxes" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                    <p>No purchases recorded</p>
                </td>
            </tr>
        `;
        return;
    }

    purchases.sort((a, b) => new Date(b.date) - new Date(a.date));

    purchases.forEach(purchase => {
        const purchaseDate = new Date(purchase.date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
        const itemsText = (purchase.items || []).map(i => `${i.name} (x${i.quantity})`).join(', ');

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>#${purchase.id}</strong></td>
            <td>${purchase.supplier || 'N/A'}</td>
            <td title="${itemsText}">${(purchase.items || []).length} item(s)</td>
            <td>₹${(purchase.total || 0).toLocaleString()}</td>
            <td>${purchaseDate}</td>
            <td><span class="status-badge ${String(purchase.status || '').toLowerCase()}">${purchase.status || 'Pending'}</span></td>
            <td>
                        <div class="action-btns">
                            <button class="action-btn invoice" onclick="generatePurchaseInvoice(${JSON.stringify(purchase.id)})" title="Purchase Invoice">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                            <button class="action-btn view" onclick="viewPurchase(${JSON.stringify(purchase.id)})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Open the purchase modal
function addPurchase() {
    openPurchaseModal();
}

function openPurchaseModal() {
    console.log('openPurchaseModal called');
    const modal = document.getElementById('purchaseModal');
    if (!modal) {
        console.log('Purchase modal not found');
        return alert('Purchase modal not found');
    }
    console.log('Modal found, opening');
    document.getElementById('purchaseSupplier').value = '';
    document.getElementById('purchaseItems').value = '';
    document.getElementById('purchaseStatus').value = 'Received';
    const today = new Date().toISOString().slice(0,10);
    document.getElementById('purchaseDate').value = today;
    modal.style.display = 'flex';
    // Prevent background scrolling
    document.body.style.overflow = 'hidden';

    // Focus first field
    setTimeout(() => document.getElementById('purchaseSupplier').focus(), 50);

    // Click-outside handler
    const outsideHandler = function(e) {
        if (e.target === modal) {
            closePurchaseModal();
        }
    };
    modal._outsideHandler = outsideHandler;
    document.addEventListener('click', outsideHandler);

    // Escape key handler
    const escHandler = function(e) {
        if (e.key === 'Escape') closePurchaseModal();
    };
    modal._escHandler = escHandler;
    document.addEventListener('keydown', escHandler);
}

function closePurchaseModal() {
    const modal = document.getElementById('purchaseModal');
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';

    // Remove handlers if attached
    if (modal._outsideHandler) {
        document.removeEventListener('click', modal._outsideHandler);
        modal._outsideHandler = null;
    }
    if (modal._escHandler) {
        document.removeEventListener('keydown', modal._escHandler);
        modal._escHandler = null;
    }
}

function savePurchaseFromModal() {
    const supplier = document.getElementById('purchaseSupplier').value.trim();
    const itemsRaw = document.getElementById('purchaseItems').value.trim();
    const status = document.getElementById('purchaseStatus').value;
    const dateVal = document.getElementById('purchaseDate').value;

    if (!supplier) return alert('Please enter supplier name');
    if (!itemsRaw) return alert('Please add at least one item');

    const items = itemsRaw.split('\n').map(line => {
        const parts = line.split(',').map(p => p.trim());
        return { name: parts[0] || 'Item', quantity: Number(parts[1]) || 1, price: Number(parts[2]) || 0 };
    }).filter(i => i.name);

    const total = items.reduce((s, it) => s + (it.quantity * it.price), 0);
    const purchase = {
        id: 'PUR-' + Date.now(),
        supplier,
        items,
        total,
        date: dateVal ? new Date(dateVal).toISOString() : new Date().toISOString(),
        status: status || 'Received'
    };

    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    purchases.push(purchase);
    localStorage.setItem('visionkart_purchases', JSON.stringify(purchases));

    // Update product stocks based on purchase items
    try {
        const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');

        purchase.items.forEach(it => {
            // Try to find by exact id match first
            let prod = storedProducts.find(p => p.id == it.id) || sampleProducts.find(p => String(p.id) === String(it.id));

            // If not found by id, try to match by name (case-insensitive contains)
            if (!prod) {
                const nameLower = (it.name || '').toLowerCase();
                prod = storedProducts.find(p => (p.name || '').toLowerCase().includes(nameLower)) || sampleProducts.find(p => (p.name || '').toLowerCase().includes(nameLower));
            }

            if (prod) {
                prod.stock = (Number(prod.stock) || 0) + (Number(it.quantity) || 0);

                // If product exists in storedProducts update it there as well
                const idx = storedProducts.findIndex(p => p.id == prod.id);
                if (idx > -1) storedProducts[idx] = prod;
            }
        });

        localStorage.setItem('visionkart_products', JSON.stringify(storedProducts));
        // Also update in-memory sampleProducts where applicable
        purchase.items.forEach(it => {
            const idx = sampleProducts.findIndex(p => (p.name || '').toLowerCase().includes((it.name||'').toLowerCase()));
            if (idx > -1) sampleProducts[idx].stock = (Number(sampleProducts[idx].stock) || 0) + (Number(it.quantity) || 0);
        });
    } catch (e) {
        console.error('Error updating product stocks from purchase', e);
    }

    closePurchaseModal();
    loadPurchases();
    loadProducts();
    showNotification('Purchase added and stock updated: ' + purchase.id, 'success');
}

// Generate printable invoice for a purchase and open in new window
function generatePurchaseInvoice(purchaseId) {
    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    const p = purchases.find(x => String(x.id) === String(purchaseId));
    if (!p) return alert('Purchase not found');

    const invoiceDate = new Date(p.date).toLocaleString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    const itemsRows = (p.items || []).map(it => `
        <tr>
            <td>${it.name}</td>
            <td style="text-align:center">${it.quantity}</td>
            <td style="text-align:right">₹${it.price.toLocaleString()}</td>
            <td style="text-align:right">₹${(it.price * it.quantity).toLocaleString()}</td>
        </tr>
    `).join('');

    const subtotal = (p.items || []).reduce((s, it) => s + (it.price * it.quantity), 0);

    const html = `
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8" />
            <title>Purchase Invoice - ${p.id}</title>
            <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px;color:#222}table{width:100%;border-collapse:collapse;margin-top:12px}th,td{padding:8px;border:1px solid #eee}th{background:#f7f7f7;text-align:left}.right{text-align:right}.muted{color:#666}</style>
        </head>
        <body>
            <h2>VisionKart — Purchase Invoice</h2>
            <div style="display:flex; justify-content:space-between; margin-top:8px">
                <div><strong>Supplier:</strong> ${p.supplier}</div>
                <div style="text-align:right"><strong>Purchase #</strong> ${p.id}<br/><span class="muted">${invoiceDate}</span></div>
            </div>
            <table>
                <thead><tr><th>Item</th><th style="width:80px;text-align:center">Qty</th><th style="width:140px;text-align:right">Rate</th><th style="width:160px;text-align:right">Amount</th></tr></thead>
                <tbody>${itemsRows}</tbody>
                <tfoot>
                    <tr><td class="no-border"></td><td class="no-border"></td><td class="right"><strong>Subtotal</strong></td><td class="right">₹${subtotal.toLocaleString()}</td></tr>
                    <tr><td class="no-border"></td><td class="no-border"></td><td class="right"><strong>Total</strong></td><td class="right"><strong>₹${p.total.toLocaleString()}</strong></td></tr>
                </tfoot>
            </table>
            <div style="margin-top:14px"><button onclick="window.print()" style="padding:10px 14px;background:#00bac7;color:#fff;border:none;border-radius:6px;cursor:pointer">Print / Save as PDF</button></div>
        </body>
        </html>
    `;

    const w = window.open('', '_blank');
    if (!w) return alert('Popup blocked. Allow popups to generate the invoice.');
    w.document.write(html);
    w.document.close();
}

function viewPurchase(purchaseId) {
    const purchases = JSON.parse(localStorage.getItem('visionkart_purchases') || '[]');
    const p = purchases.find(x => String(x.id) === String(purchaseId));
    if (!p) return alert('Purchase not found');

    const itemsText = (p.items || []).map(i => `${i.name} — ${i.quantity} x ₹${i.price}`).join('\n');
    alert(`Purchase ${p.id}\nSupplier: ${p.supplier}\nTotal: ₹${p.total}\nDate: ${new Date(p.date).toLocaleString()}\n\nItems:\n${itemsText}`);
}

    // Load users from localStorage
    const users = JSON.parse(localStorage.getItem('visionkart_users') || '[]');
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    
    tbody.innerHTML = '';
    
    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-users" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                    <p>No registered customers yet</p>
                </td>
            </tr>
        `;
        return;
    }
    
    users.forEach((user, index) => {
        // Calculate customer's orders and total spent
        const customerOrders = orders.filter(order => order.customer === user.email);
        const orderCount = customerOrders.length;
        const totalSpent = customerOrders.reduce((sum, order) => sum + order.total, 0);
        
        // Format date
        const joinedDate = new Date(user.registeredDate);
        const formattedDate = joinedDate.toLocaleDateString('en-IN', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>CUST-${String(index + 1).padStart(3, '0')}</strong></td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.phone}</td>
            <td>${orderCount}</td>
            <td>₹${totalSpent.toLocaleString()}</td>
            <td>${formattedDate}</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn view" onclick="viewCustomer('${user.email}')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editCustomer('${user.email}')">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Load Categories
function loadCategories() {
    const grid = document.getElementById('categoriesGrid');
    if (!grid) return;

    grid.innerHTML = '';
    categories.forEach(category => {
        const card = document.createElement('div');
        card.className = 'category-card';
        card.innerHTML = `
            <i class="fas ${category.icon}"></i>
            <h3>${category.name}</h3>
            <p>${category.products} Products</p>
            <div class="action-btns" style="margin-top: 15px; justify-content: center;">
                <button class="action-btn edit" onclick="editCategory('${category.name}')">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="action-btn delete" onclick="deleteCategory('${category.name}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// CRUD Functions
function addNewProduct(formData) {
    // Get existing products from localStorage
    const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');
    
    const price = parseInt(formData.get('price'));
    const originalPrice = parseInt(formData.get('originalPrice')) || price * 2;
    const discount = Math.round(((originalPrice - price) / originalPrice) * 100) + "% OFF";
    
    const newProduct = {
        id: Date.now(), // Use timestamp for unique ID
        name: formData.get('name'),
        type: formData.get('category') === 'eyeglasses' ? 'Eyeglasses' : 'Sunglasses',
        category: formData.get('subcategory'),
        price: price,
        originalPrice: originalPrice,
        discount: discount,
        rating: 4.5,
        reviews: 0,
        badge: "NEW",
        image: formData.get('image') || "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop",
        hsn: formData.get('hsn'),
        brand: formData.get('brand'),
        gst: parseInt(formData.get('gst')),
        stock: parseInt(formData.get('stock')),
        color: formData.get('color'),
        frameType: formData.get('frametype'),
        status: 'active'
    };
    
    storedProducts.push(newProduct);
    localStorage.setItem('visionkart_products', JSON.stringify(storedProducts));
    
    console.log('✅ Product added to localStorage:', newProduct);
    console.log('📦 Total products in storage:', storedProducts.length);
    
    // Also add to sampleProducts for admin display
    sampleProducts.push(newProduct);
    
    loadProducts();
    showNotification(`✅ Product "${newProduct.name}" added! Refresh the website homepage to see it.`, 'success');
}

function editProduct(id) {
    const product = sampleProducts.find(p => p.id === id);
    if (product) {
        showNotification(`Editing product: ${product.name}`, 'info');
        // Here you would open an edit modal with the product data
    }
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        // Add to deleted products list
        const deletedProductIds = JSON.parse(localStorage.getItem('visionkart_deleted_products') || '[]');
        if (!deletedProductIds.includes(id)) {
            deletedProductIds.push(id);
            localStorage.setItem('visionkart_deleted_products', JSON.stringify(deletedProductIds));
        }
        
        // Remove from localStorage
        const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');
        const storageIndex = storedProducts.findIndex(p => p.id === id);
        
        if (storageIndex > -1) {
            const deletedProduct = storedProducts[storageIndex];
            storedProducts.splice(storageIndex, 1);
            localStorage.setItem('visionkart_products', JSON.stringify(storedProducts));
            console.log('✅ Product deleted from localStorage:', deletedProduct.name);
        }
        
        // Also remove from sampleProducts array for admin display
        const index = sampleProducts.findIndex(p => p.id === id);
        if (index > -1) {
            sampleProducts.splice(index, 1);
        }
        
        console.log('📦 Remaining products in storage:', storedProducts.length);
        console.log('🗑️ Total deleted products:', deletedProductIds.length);
        
        loadProducts();
        showNotification('✅ Product deleted! It will not appear on the website anymore.', 'success');
    }
}

function viewOrder(id) {
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    const order = orders.find(o => o.id == id);
    
    if (order) {
        const orderDate = new Date(order.date);
        const formattedDate = orderDate.toLocaleDateString('en-IN', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        let itemsList = '';
        order.items.forEach(item => {
            itemsList += `\n  • ${item.name} x${item.quantity} - ₹${item.price * item.quantity}`;
        });
        
        alert(`ORDER DETAILS\n\nOrder ID: #${order.id}\nCustomer: ${order.customer}\nDate: ${formattedDate}\nStatus: ${order.status}\n\nItems:${itemsList}\n\nTotal: ₹${order.total.toLocaleString()}`);
    }
}

function updateOrderStatus(id) {
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    const orderIndex = orders.findIndex(o => o.id == id);
    
    if (orderIndex !== -1) {
        const newStatus = prompt('Enter new status (Pending/Processing/Completed/Cancelled):', orders[orderIndex].status);
        
        if (newStatus) {
            const validStatuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
            const capitalizedStatus = newStatus.charAt(0).toUpperCase() + newStatus.slice(1).toLowerCase();
            
            if (validStatuses.includes(capitalizedStatus)) {
                orders[orderIndex].status = capitalizedStatus;
                localStorage.setItem('visionkart_orders', JSON.stringify(orders));
                showNotification(`Order #${id} status updated to ${capitalizedStatus}`, 'success');
                loadOrders();
            } else {
                showNotification('Invalid status. Please use: Pending, Processing, Completed, or Cancelled', 'error');
            }
        }
    }
}

function editOrder(id) {
    updateOrderStatus(id);
}

function viewCustomer(email) {
    const users = JSON.parse(localStorage.getItem('visionkart_users') || '[]');
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    const customer = users.find(u => u.email === email);
    
    if (customer) {
        const customerOrders = orders.filter(order => order.customer === email);
        const totalSpent = customerOrders.reduce((sum, order) => sum + order.total, 0);
        const joinedDate = new Date(customer.registeredDate).toLocaleDateString('en-IN');
        
        alert(`CUSTOMER DETAILS\n\nName: ${customer.name}\nEmail: ${customer.email}\nPhone: ${customer.phone}\nJoined: ${joinedDate}\nTotal Orders: ${customerOrders.length}\nTotal Spent: ₹${totalSpent.toLocaleString()}`);
    }
}

function editCustomer(email) {
    showNotification('Customer editing feature coming soon', 'info');
}

function editCategory(name) {
    showNotification(`Editing category: ${name}`, 'info');
}

function deleteCategory(name) {
    if (confirm(`Are you sure you want to delete the ${name} category?`)) {
        showNotification(`Category ${name} deleted!`, 'success');
    }
}

// Update dashboard statistics
function updateDashboardStats() {
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    const users = JSON.parse(localStorage.getItem('visionkart_users') || '[]');
    
    // Total Orders (1st card)
    const ordersElement = document.querySelector('.stat-card:nth-child(1) .stat-number');
    if (ordersElement) {
        ordersElement.textContent = orders.length;
    }
    
    // Total Revenue (2nd card)
    const totalRevenue = orders.reduce((sum, order) => sum + order.total, 0);
    const revenueElement = document.querySelector('.stat-card:nth-child(2) .stat-number');
    if (revenueElement) {
        revenueElement.textContent = `₹${totalRevenue.toLocaleString()}`;
    }
    
    // Products (3rd card) - keep static for now
    
    // Total Customers (4th card)
    const customersElement = document.querySelector('.stat-card:nth-child(4) .stat-number');
    if (customersElement) {
        customersElement.textContent = users.length;
    }
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Filters
const categoryFilter = document.getElementById('categoryFilter');
const brandFilter = document.getElementById('brandFilter');
const statusFilter = document.getElementById('statusFilter');

if (categoryFilter) {
    categoryFilter.addEventListener('change', filterProducts);
}
if (brandFilter) {
    brandFilter.addEventListener('change', filterProducts);
}
if (statusFilter) {
    statusFilter.addEventListener('change', filterProducts);
}

function filterProducts() {
    const category = categoryFilter?.value || 'all';
    const brand = brandFilter?.value || 'all';
    const status = statusFilter?.value || 'all';

    // Get both hardcoded and localStorage products
    const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');
    const deletedProductIds = JSON.parse(localStorage.getItem('visionkart_deleted_products') || '[]');
    
    // Merge all products and filter out deleted ones
    let allProducts = [...sampleProducts, ...storedProducts].filter(p => !deletedProductIds.includes(p.id));
    
    // Remove duplicates by ID
    allProducts = allProducts.filter((product, index, self) => 
        index === self.findIndex(p => p.id === product.id)
    );

    let filtered = allProducts;

    if (category !== 'all') {
        filtered = filtered.filter(p => {
            const productCategory = (p.category || p.subcategory || '').toLowerCase();
            return productCategory.includes(category);
        });
    }
    if (brand !== 'all') {
        filtered = filtered.filter(p => {
            const productBrand = (p.brand || '').toLowerCase();
            return productBrand.includes(brand.replace('-', ' '));
        });
    }
    if (status !== 'all') {
        filtered = filtered.filter(p => p.status === status);
    }

    const tbody = document.getElementById('productsTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    
    if (filtered.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align: center; padding: 30px;">No products found</td></tr>';
        return;
    }
    
    filtered.forEach(product => {
        const priceWithGst = (product.price * (1 + product.gst / 100)).toFixed(2);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><img src="${product.image}" alt="${product.name}" class="product-img"></td>
            <td>${product.name}</td>
            <td>${product.category || product.type || 'N/A'} / ${product.subcategory || product.category || 'N/A'}</td>
            <td>${product.hsn || 'N/A'}</td>
            <td>${product.brand || 'N/A'}</td>
            <td>₹${product.price}<br><small style="color: #666;">(₹${priceWithGst} with GST)</small></td>
            <td>${product.gst || 12}%</td>
            <td>${product.stock || 0}</td>
            <td><span class="status-badge ${product.status || 'active'}">${product.status || 'active'}</span></td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" onclick="editProduct(${product.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteProduct(${product.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Camera Feature
let stream = null;
let capturedImageData = null;

function initializeCameraFeature() {
    const openCameraBtn = document.getElementById('openCameraBtn');
    const closeCameraBtn = document.getElementById('closeCameraBtn');
    const capturePhotoBtn = document.getElementById('capturePhotoBtn');
    const uploadImageBtn = document.getElementById('uploadImageBtn');
    const imageFileInput = document.getElementById('imageFileInput');
    const removeImageBtn = document.getElementById('removeImageBtn');

    if (openCameraBtn) {
        openCameraBtn.addEventListener('click', startCamera);
    }

    if (closeCameraBtn) {
        closeCameraBtn.addEventListener('click', stopCamera);
    }

    if (capturePhotoBtn) {
        capturePhotoBtn.addEventListener('click', capturePhoto);
    }

    if (uploadImageBtn) {
        uploadImageBtn.addEventListener('click', function() {
            imageFileInput.click();
        });
    }

    if (imageFileInput) {
        imageFileInput.addEventListener('change', handleFileUpload);
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', removeImage);
    }
}

async function startCamera() {
    try {
        const cameraSection = document.getElementById('cameraSection');
        const video = document.getElementById('cameraPreview');
        
        // Request camera access
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment', // Use back camera on mobile
                width: { ideal: 1280 },
                height: { ideal: 720 }
            } 
        });
        
        video.srcObject = stream;
        cameraSection.style.display = 'block';
        
        showNotification('Camera started successfully!', 'success');
    } catch (error) {
        console.error('Error accessing camera:', error);
        showNotification('Could not access camera. Please check permissions.', 'error');
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    const cameraSection = document.getElementById('cameraSection');
    const video = document.getElementById('cameraPreview');
    
    if (video) {
        video.srcObject = null;
    }
    
    if (cameraSection) {
        cameraSection.style.display = 'none';
    }
}

function capturePhoto() {
    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const imageUrlInput = document.getElementById('imageUrlInput');
    
    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert to data URL
    capturedImageData = canvas.toDataURL('image/jpeg', 0.8);
    
    // Show preview
    previewImage.src = capturedImageData;
    imagePreview.style.display = 'block';
    
    // Set the image URL input
    imageUrlInput.value = capturedImageData;
    
    // Stop camera
    stopCamera();
    
    showNotification('Photo captured successfully!', 'success');
}

function handleFileUpload(e) {
    const file = e.target.files[0];
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            capturedImageData = event.target.result;
            
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const imageUrlInput = document.getElementById('imageUrlInput');
            
            previewImage.src = capturedImageData;
            imagePreview.style.display = 'block';
            imageUrlInput.value = capturedImageData;
            
            showNotification('Image uploaded successfully!', 'success');
        };
        
        reader.readAsDataURL(file);
    } else {
        showNotification('Please select a valid image file.', 'error');
    }
}

function removeImage() {
    capturedImageData = null;
    
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const imageUrlInput = document.getElementById('imageUrlInput');
    const imageFileInput = document.getElementById('imageFileInput');
    
    previewImage.src = '';
    imagePreview.style.display = 'none';
    imageUrlInput.value = '';
    imageFileInput.value = '';
    
    showNotification('Image removed.', 'info');
}

// GST Calculation
function setupGSTCalculation() {
    const priceInput = document.querySelector('input[name="price"]');
    const gstSelect = document.querySelector('select[name="gst"]');
    const priceWithGstInput = document.getElementById('priceWithGst');
    
    function calculateGST() {
        const price = parseFloat(priceInput.value) || 0;
        const gstRate = parseFloat(gstSelect.value) || 0;
        const priceWithGst = price * (1 + gstRate / 100);
        priceWithGstInput.value = priceWithGst.toFixed(2);
    }
    
    if (priceInput && gstSelect && priceWithGstInput) {
        priceInput.addEventListener('input', calculateGST);
        gstSelect.addEventListener('change', calculateGST);
    }
}

// Video Feature
let videoStream = null;
let mediaRecorder = null;
let recordedChunks = [];
let recordingTimer = null;
let recordingSeconds = 0;

function initializeVideoFeature() {
    const recordVideoBtn = document.getElementById('recordVideoBtn');
    const uploadVideoBtn = document.getElementById('uploadVideoBtn');
    const videoFileInput = document.getElementById('videoFileInput');
    const startRecordingBtn = document.getElementById('startRecordingBtn');
    const stopRecordingBtn = document.getElementById('stopRecordingBtn');
    const closeVideoBtn = document.getElementById('closeVideoBtn');
    const removeVideoBtn = document.getElementById('removeVideoBtn');

    if (recordVideoBtn) {
        recordVideoBtn.addEventListener('click', startVideoRecording);
    }

    if (uploadVideoBtn) {
        uploadVideoBtn.addEventListener('click', function() {
            videoFileInput.click();
        });
    }

    if (videoFileInput) {
        videoFileInput.addEventListener('change', handleVideoUpload);
    }

    if (startRecordingBtn) {
        startRecordingBtn.addEventListener('click', beginRecording);
    }

    if (stopRecordingBtn) {
        stopRecordingBtn.addEventListener('click', stopRecording);
    }

    if (closeVideoBtn) {
        closeVideoBtn.addEventListener('click', closeVideoRecording);
    }

    if (removeVideoBtn) {
        removeVideoBtn.addEventListener('click', removeVideo);
    }
}

async function startVideoRecording() {
    try {
        const videoRecordSection = document.getElementById('videoRecordSection');
        const videoPreview = document.getElementById('videoRecordPreview');
        
        // Request camera and microphone access
        videoStream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: true
        });
        
        videoPreview.srcObject = videoStream;
        videoRecordSection.style.display = 'block';
        
        showNotification('Camera ready! Click "Start Recording" to begin.', 'success');
    } catch (error) {
        console.error('Error accessing camera/microphone:', error);
        showNotification('Could not access camera/microphone. Please check permissions.', 'error');
    }
}

function beginRecording() {
    try {
        recordedChunks = [];
        recordingSeconds = 0;
        
        // Create MediaRecorder
        const options = { mimeType: 'video/webm;codecs=vp9' };
        if (!MediaRecorder.isTypeSupported(options.mimeType)) {
            options.mimeType = 'video/webm';
        }
        
        mediaRecorder = new MediaRecorder(videoStream, options);
        
        mediaRecorder.ondataavailable = function(event) {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };
        
        mediaRecorder.onstop = function() {
            const blob = new Blob(recordedChunks, { type: 'video/webm' });
            const videoUrl = URL.createObjectURL(blob);
            
            // Convert to base64
            const reader = new FileReader();
            reader.onloadend = function() {
                const base64Video = reader.result;
                displayVideoPreview(base64Video);
            };
            reader.readAsDataURL(blob);
        };
        
        mediaRecorder.start();
        
        // Update UI
        document.getElementById('startRecordingBtn').style.display = 'none';
        document.getElementById('stopRecordingBtn').style.display = 'inline-block';
        document.getElementById('recordingTimer').style.display = 'block';
        
        // Start timer
        recordingTimer = setInterval(updateRecordingTimer, 1000);
        
        showNotification('Recording started!', 'success');
    } catch (error) {
        console.error('Error starting recording:', error);
        showNotification('Could not start recording.', 'error');
    }
}

function stopRecording() {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        
        // Stop timer
        clearInterval(recordingTimer);
        
        // Update UI
        document.getElementById('startRecordingBtn').style.display = 'inline-block';
        document.getElementById('stopRecordingBtn').style.display = 'none';
        document.getElementById('recordingTimer').style.display = 'none';
        
        // Close video recording
        closeVideoRecording();
        
        showNotification('Recording stopped!', 'success');
    }
}

function updateRecordingTimer() {
    recordingSeconds++;
    const minutes = Math.floor(recordingSeconds / 60);
    const seconds = recordingSeconds % 60;
    const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    document.getElementById('timerDisplay').textContent = display;
}

function closeVideoRecording() {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    
    const videoRecordSection = document.getElementById('videoRecordSection');
    const videoPreview = document.getElementById('videoRecordPreview');
    
    if (videoPreview) {
        videoPreview.srcObject = null;
    }
    
    if (videoRecordSection) {
        videoRecordSection.style.display = 'none';
    }
    
    // Reset recording UI
    document.getElementById('startRecordingBtn').style.display = 'inline-block';
    document.getElementById('stopRecordingBtn').style.display = 'none';
    document.getElementById('recordingTimer').style.display = 'none';
    
    if (recordingTimer) {
        clearInterval(recordingTimer);
    }
}

function handleVideoUpload(e) {
    const file = e.target.files[0];
    
    if (file && file.type.startsWith('video/')) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            const videoData = event.target.result;
            displayVideoPreview(videoData);
            showNotification('Video uploaded successfully!', 'success');
        };
        
        reader.readAsDataURL(file);
    } else {
        showNotification('Please select a valid video file.', 'error');
    }
}

function displayVideoPreview(videoData) {
    const videoPreview = document.getElementById('videoPreview');
    const previewVideo = document.getElementById('previewVideo');
    const videoUrlInput = document.getElementById('videoUrlInput');
    
    previewVideo.src = videoData;
    videoPreview.style.display = 'block';
    videoUrlInput.value = videoData;
}

function removeVideo() {
    const videoPreview = document.getElementById('videoPreview');
    const previewVideo = document.getElementById('previewVideo');
    const videoUrlInput = document.getElementById('videoUrlInput');
    const videoFileInput = document.getElementById('videoFileInput');
    
    previewVideo.src = '';
    videoPreview.style.display = 'none';
    videoUrlInput.value = '';
    videoFileInput.value = '';
    
    showNotification('Video removed.', 'info');
}

console.log('Admin Panel initialized! 🎉');
