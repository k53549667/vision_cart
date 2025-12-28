// VisionKart Admin Panel - Database Version
// API Base URL
const API_BASE = '';

// Global data cache
let cachedProducts = [];
let cachedOrders = [];
let cachedCustomers = [];
let cachedPurchases = [];
let cachedCategories = [];
let isEditMode = false;
let editProductId = null;

// Utility function for API calls
async function apiCall(endpoint, options = {}) {
    const url = `${API_BASE}${endpoint}`;
    const config = {
        headers: {
            'Content-Type': 'application/json',
            ...options.headers
        },
        ...options
    };

    try {
        const response = await fetch(url, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || `HTTP ${response.status}`);
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        showNotification(error.message, 'error');
        throw error;
    }
}

// Load data from APIs
async function loadProducts() {
    try {
        cachedProducts = await apiCall('api_products.php?action=list');
        return cachedProducts;
    } catch (error) {
        console.error('Failed to load products:', error);
        return [];
    }
}

async function loadOrders() {
    try {
        cachedOrders = await apiCall('api_orders.php?action=list');
        return cachedOrders;
    } catch (error) {
        console.error('Failed to load orders:', error);
        return [];
    }
}

async function loadCustomers() {
    try {
        cachedCustomers = await apiCall('api_customers.php?action=list');
        return cachedCustomers;
    } catch (error) {
        console.error('Failed to load customers:', error);
        return [];
    }
}

async function loadPurchases() {
    try {
        cachedPurchases = await apiCall('api_purchases.php?action=list');
        return cachedPurchases;
    } catch (error) {
        console.error('Failed to load purchases:', error);
        return [];
    }
}

async function loadCategories() {
    try {
        cachedCategories = await apiCall('api_categories.php?action=list');
        return cachedCategories;
    } catch (error) {
        console.error('Failed to load categories:', error);
        return [];
    }
}

async function loadCategories() {
    try {
        cachedCategories = await apiCall('api_products.php?action=categories');
        return cachedCategories;
    } catch (error) {
        console.error('Failed to load categories:', error);
        return [];
    }
}

// Update dashboard statistics
async function updateDashboardStats() {
    try {
        // Load all data
        const [orders, customers, products] = await Promise.all([
            loadOrders(),
            loadCustomers(),
            loadProducts()
        ]);

        // Total Orders
        const ordersElement = document.querySelector('.stat-card:nth-child(1) .stat-number');
        if (ordersElement) {
            ordersElement.textContent = orders.length;
        }

        // Total Revenue
        const totalRevenue = orders
            .filter(order => order.status === 'completed')
            .reduce((sum, order) => sum + parseFloat(order.total_amount || 0), 0);
        const revenueElement = document.querySelector('.stat-card:nth-child(2) .stat-number');
        if (revenueElement) {
            revenueElement.textContent = `₹${totalRevenue.toLocaleString()}`;
        }

        // Total Products
        const productsElement = document.querySelector('.stat-card:nth-child(3) .stat-number');
        if (productsElement) {
            productsElement.textContent = products.length;
        }

        // Total Customers
        const customersElement = document.querySelector('.stat-card:nth-child(4) .stat-number');
        if (customersElement) {
            customersElement.textContent = customers.length;
        }

    } catch (error) {
        console.error('Failed to update dashboard stats:', error);
    }
}

// Load products table
async function loadProductsTable() {
    const tbody = document.getElementById('productsTableBody');
    if (!tbody) return;

    try {
        const products = await loadProducts();
        tbody.innerHTML = '';

        if (products.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-box" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <p>No products found</p>
                    </td>
                </tr>
            `;
            return;
        }

        products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>#${product.id}</strong></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="${product.image || 'https://via.placeholder.com/40x40'}" alt="${product.name}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                        <div>
                            <div style="font-weight: 500;">${product.name}</div>
                            <div style="font-size: 12px; color: #666;">${product.brand || ''}</div>
                        </div>
                    </div>
                </td>
                <td>${product.category || ''}</td>
                <td>₹${parseFloat(product.price || 0).toLocaleString()}</td>
                <td>${product.stock || 0}</td>
                <td><span class="status-badge ${product.status || 'active'}">${product.status || 'Active'}</span></td>
                <td>${product.gst || 0}%</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn edit" onclick="editProduct(${product.id})" title="Edit Product">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete" onclick="deleteProduct(${product.id})" title="Delete Product">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

    } catch (error) {
        console.error('Failed to load products table:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Failed to load products</p>
                </td>
            </tr>
        `;
    }
}

// Load orders table
async function loadOrdersTable() {
    const tbody = document.getElementById('ordersTableBody');
    if (!tbody) return;

    try {
        const orders = await loadOrders();
        tbody.innerHTML = '';

        if (orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <p>No orders found</p>
                    </td>
                </tr>
            `;
            return;
        }

        orders.forEach(order => {
            const orderDate = new Date(order.order_date || order.created_at).toLocaleDateString('en-IN', {
                day: '2-digit', month: 'short', year: 'numeric'
            });

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${order.id}</strong></td>
                <td>${order.customer_name || 'N/A'}</td>
                <td title="${order.products || ''}">${(order.products || '').substring(0, 30)}${(order.products || '').length > 30 ? '...' : ''}</td>
                <td>₹${parseFloat(order.total_amount || 0).toLocaleString()}</td>
                <td>${orderDate}</td>
                <td><span class="status-badge ${order.status || 'pending'}">${order.status || 'Pending'}</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" onclick="viewOrder('${order.id}')" title="View Order">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="editOrder('${order.id}')" title="Edit Order">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

    } catch (error) {
        console.error('Failed to load orders table:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Failed to load orders</p>
                </td>
            </tr>
        `;
    }
}

// Load customers table
async function loadCustomersTable() {
    const tbody = document.getElementById('customersTableBody');
    if (!tbody) return;

    try {
        const customers = await loadCustomers();
        tbody.innerHTML = '';

        if (customers.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-users" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <p>No customers found</p>
                    </td>
                </tr>
            `;
            return;
        }

        customers.forEach(customer => {
            const joinedDate = new Date(customer.joined_date || customer.created_at).toLocaleDateString('en-IN', {
                day: '2-digit', month: 'short', year: 'numeric'
            });

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>#${customer.id}</strong></td>
                <td>${customer.name}</td>
                <td>${customer.email}</td>
                <td>${customer.phone || 'N/A'}</td>
                <td>${customer.orders_count || 0}</td>
                <td>₹${parseFloat(customer.total_spent || 0).toLocaleString()}</td>
                <td>${joinedDate}</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" onclick="viewCustomer(${customer.id})" title="View Customer">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="editCustomer(${customer.id})" title="Edit Customer">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

    } catch (error) {
        console.error('Failed to load customers table:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Failed to load customers</p>
                </td>
            </tr>
        `;
    }
}

// Load purchases table
async function loadPurchasesTable() {
    const tbody = document.getElementById('purchasesTableBody');
    if (!tbody) return;

    try {
        const purchases = await loadPurchases();
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

        purchases.forEach(purchase => {
            const purchaseDate = new Date(purchase.purchase_date || purchase.created_at).toLocaleDateString('en-IN', {
                day: '2-digit', month: 'short', year: 'numeric'
            });

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>#${purchase.id}</strong></td>
                <td>${purchase.supplier || 'N/A'}</td>
                <td>${purchase.product_name || 'N/A'}</td>
                <td>${purchase.quantity || 0}</td>
                <td>₹${parseFloat(purchase.cost_price || 0).toLocaleString()}</td>
                <td>${purchaseDate}</td>
                <td><span class="status-badge ${purchase.status || 'pending'}">${purchase.status || 'Pending'}</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" onclick="viewPurchase(${purchase.id})" title="View Purchase">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="editPurchase(${purchase.id})" title="Edit Purchase">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

    } catch (error) {
        console.error('Failed to load purchases table:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Failed to load purchases</p>
                </td>
            </tr>
        `;
    }
}

// Load categories grid
async function loadCategoriesTable() {
    const grid = document.getElementById('categoriesGrid');
    if (!grid) return;

    try {
        const categories = await loadCategories();
        grid.innerHTML = '';

        if (categories.length === 0) {
            grid.innerHTML = `
                <div class="no-data">
                    <i class="fas fa-tags" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                    <p>No categories found</p>
                </div>
            `;
            return;
        }

        categories.forEach(category => {
            const categoryCard = document.createElement('div');
            categoryCard.className = 'category-card';
            categoryCard.innerHTML = `
                <div class="category-icon">
                    <i class="fas ${category.icon || 'fa-tag'}"></i>
                </div>
                <div class="category-info">
                    <h3>${category.name}</h3>
                    <p>${category.products_count || 0} products</p>
                </div>
                <div class="category-actions">
                    <button class="action-btn edit" onclick="editCategory(${category.id})" title="Edit Category">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteCategory(${category.id})" title="Delete Category">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            grid.appendChild(categoryCard);
        });

    } catch (error) {
        console.error('Failed to load categories:', error);
        grid.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                <p>Failed to load categories</p>
            </div>
        `;
    }
}

// Product CRUD operations
async function addProduct() {
    const form = document.getElementById('addProductForm');
    if (!form) return;

    const formData = new FormData(form);
    const productData = {
        name: formData.get('name'),
        category: formData.get('category'),
        subcategory: formData.get('subcategory'),
        frametype: formData.get('frametype'),
        hsn: formData.get('hsn'),
        brand: formData.get('brand'),
        price: parseFloat(formData.get('price')),
        originalPrice: parseFloat(formData.get('originalPrice')) || null,
        gst: parseFloat(formData.get('gst')) || 12,
        stock: parseInt(formData.get('stock')) || 0,
        color: formData.get('color'),
        status: formData.get('status') || 'active',
        image: formData.get('image'),
        video: formData.get('video'),
        description: formData.get('description')
    };

    try {
        await apiCall('api_products.php?action=create', {
            method: 'POST',
            body: JSON.stringify(productData)
        });

        showNotification('Product added successfully!', 'success');
        closeModal('addProductModal');
        form.reset();
        loadProductsTable();
        updateDashboardStats();

    } catch (error) {
        showNotification('Failed to add product', 'error');
    }
}

async function editProduct(id) {
    console.log('editProduct called with id:', id);
    isEditMode = true;
    editProductId = id;
    
    try {
        console.log('Fetching product from API...');
        const product = await apiCall(`api_products.php?action=get&id=${id}`);
        console.log('Product received:', product);
        
        // Populate form with product data
        const form = document.getElementById('addProductForm');
        if (form) {
            form.elements['name'].value = product.name || '';
            form.elements['category'].value = product.category || '';
            form.elements['subcategory'].value = product.subcategory || '';
            form.elements['frametype'].value = product.frametype || '';
            form.elements['hsn'].value = product.hsn || '';
            form.elements['brand'].value = product.brand || '';
            form.elements['price'].value = product.price || '';
            form.elements['originalPrice'].value = product.original_price || '';
            form.elements['gst'].value = product.gst || 12;
            form.elements['stock'].value = product.stock || 0;
            form.elements['color'].value = product.color || '';
            form.elements['status'].value = product.status || 'active';
            form.elements['image'].value = product.image || '';
            form.elements['video'].value = product.video || '';
            form.elements['description'].value = product.description || '';
            
            // Load images for editing
            const imageUrls = (product.image || '').split(',').filter(url => url.trim());
            capturedImages = imageUrls;
            updateImagePreview();
        }

        // Change form submit button text
        const submitBtn = document.querySelector('#addProductModal .btn-primary');
        if (submitBtn) {
            submitBtn.textContent = 'Update Product';
        }

        openModal('addProductModal');
        console.log('Modal opened successfully');

    } catch (error) {
        console.error('Edit product error:', error);
        showNotification('Failed to load product details: ' + error.message, 'error');
    }
}

async function updateProduct(id) {
    const form = document.getElementById('addProductForm');
    if (!form) return;

    const formData = new FormData(form);
    const productData = {
        name: formData.get('name'),
        category: formData.get('category'),
        subcategory: formData.get('subcategory'),
        frametype: formData.get('frametype'),
        hsn: formData.get('hsn'),
        brand: formData.get('brand'),
        price: parseFloat(formData.get('price')),
        originalPrice: parseFloat(formData.get('originalPrice')) || null,
        gst: parseFloat(formData.get('gst')) || 12,
        stock: parseInt(formData.get('stock')) || 0,
        color: formData.get('color'),
        status: formData.get('status') || 'active',
        image: formData.get('image'),
        video: formData.get('video'),
        description: formData.get('description')
    };

    try {
        await apiCall(`api_products.php?action=update&id=${id}`, {
            method: 'POST',
            body: JSON.stringify(productData)
        });

        showNotification('Product updated successfully!', 'success');
        closeModal('addProductModal');
        form.reset();
        loadProductsTable();
        updateDashboardStats();

    } catch (error) {
        showNotification('Failed to update product', 'error');
    }
}

async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product?')) return;

    try {
        const result = await apiCall(`api_products.php?id=${id}`, {
            method: 'DELETE'
        });

        if (result.message) {
            showNotification(result.message, 'success');
        } else {
            showNotification('Product deleted successfully!', 'success');
        }
        loadProductsTable();
        updateDashboardStats();

    } catch (error) {
        showNotification('Failed to delete product', 'error');
    }
}
// Category CRUD operations
async function addCategory() {
    const form = document.getElementById('categoryForm');
    if (!form) return;

    const formData = new FormData(form);
    const categoryData = {
        name: formData.get('name'),
        icon: formData.get('icon') || 'fa-tag',
        products_count: 0
    };

    try {
        await apiCall('api_categories.php?action=create', {
            method: 'POST',
            body: JSON.stringify(categoryData)
        });

        showNotification('Category added successfully!', 'success');
        closeModal('categoryModal');
        form.reset();
        loadCategoriesTable();

    } catch (error) {
        showNotification('Failed to add category', 'error');
    }
}

async function editCategory(id) {
    try {
        const category = await apiCall(`api_categories.php?action=get&id=${id}`);
        // Populate form with category data
        const form = document.getElementById('categoryForm');
        if (form) {
            form.elements['name'].value = category.name || '';
            form.elements['icon'].value = category.icon || 'fa-tag';
        }

        // Change form submit action
        const submitBtn = document.querySelector('#categoryModal .submit-btn');
        if (submitBtn) {
            submitBtn.onclick = () => updateCategory(id);
            submitBtn.textContent = 'Update Category';
        }

        openModal('categoryModal');

    } catch (error) {
        showNotification('Failed to load category details', 'error');
    }
}

async function updateCategory(id) {
    const form = document.getElementById('categoryForm');
    if (!form) return;

    const formData = new FormData(form);
    const categoryData = {
        name: formData.get('name'),
        icon: formData.get('icon') || 'fa-tag'
    };

    try {
        await apiCall(`api_categories.php?action=update&id=${id}`, {
            method: 'POST',
            body: JSON.stringify(categoryData)
        });

        showNotification('Category updated successfully!', 'success');
        closeModal('categoryModal');
        form.reset();
        loadCategoriesTable();

    } catch (error) {
        showNotification('Failed to update category', 'error');
    }
}

async function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;

    try {
        await apiCall(`api_categories.php?id=${id}`, {
            method: 'DELETE'
        });

        showNotification('Category deleted successfully!', 'success');
        loadCategoriesTable();

    } catch (error) {
        showNotification('Failed to delete category', 'error');
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

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Camera Feature
let stream = null;
let capturedImages = []; // Array to hold up to 4 images
const MAX_IMAGES = 4;

function initializeCameraFeature() {
    const openCameraBtn = document.getElementById('openCameraBtn');
    const closeCameraBtn = document.getElementById('closeCameraBtn');
    const capturePhotoBtn = document.getElementById('capturePhotoBtn');
    const uploadImageBtn = document.getElementById('uploadImageBtn');
    const imageFileInput = document.getElementById('imageFileInput');
    const removeImageBtn = document.getElementById('removeImageBtn');

    if (openCameraBtn) {
        openCameraBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            startCamera();
        });
    }

    if (closeCameraBtn) {
        closeCameraBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            stopCamera();
        });
    }

    if (capturePhotoBtn) {
        capturePhotoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            capturePhoto();
        });
    }

    if (uploadImageBtn) {
        uploadImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            imageFileInput.click();
        });
    }

    if (imageFileInput) {
        imageFileInput.addEventListener('change', handleFileUpload);
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            removeImage();
        });
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
    if (capturedImages.length >= MAX_IMAGES) {
        showNotification(`Maximum ${MAX_IMAGES} photos allowed!`, 'error');
        return;
    }

    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    
    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert to data URL
    const imageData = canvas.toDataURL('image/jpeg', 0.8);
    
    // Add to images array
    capturedImages.push(imageData);
    
    // Update preview
    updateImagePreview();
    
    // Update form input with comma-separated image URLs
    const imageUrlInput = document.getElementById('imageUrlInput');
    imageUrlInput.value = capturedImages.join(',');
    
    showNotification(`Photo ${capturedImages.length}/${MAX_IMAGES} captured successfully!`, 'success');
    
    // Stop camera if we've reached the maximum
    if (capturedImages.length >= MAX_IMAGES) {
        stopCamera();
        showNotification('Maximum photos reached. Camera stopped.', 'info');
    }
}

function handleFileUpload(e) {
    const files = Array.from(e.target.files);
    
    for (const file of files) {
        if (capturedImages.length >= MAX_IMAGES) {
            showNotification(`Maximum ${MAX_IMAGES} photos allowed!`, 'error');
            break;
        }
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                capturedImages.push(event.target.result);
                updateImagePreview();
                
                // Update form input with comma-separated image URLs
                const imageUrlInput = document.getElementById('imageUrlInput');
                imageUrlInput.value = capturedImages.join(',');
                
                showNotification(`Image ${capturedImages.length}/${MAX_IMAGES} uploaded successfully!`, 'success');
            };
            
            reader.readAsDataURL(file);
        } else {
            showNotification('Please select valid image files.', 'error');
        }
    }
    
    // Clear the file input
    e.target.value = '';
}

function removeImage(index) {
    if (index >= 0 && index < capturedImages.length) {
        capturedImages.splice(index, 1);
        updateImagePreview();
        
        // Update form input
        const imageUrlInput = document.getElementById('imageUrlInput');
        imageUrlInput.value = capturedImages.join(',');
        
        showNotification('Image removed.', 'info');
    }
}

function updateImagePreview() {
    const previewContainer = document.getElementById('previewImage');
    
    if (capturedImages.length === 0) {
        previewContainer.innerHTML = '<span style="color: #999;">No photos yet</span>';
        return;
    }
    
    // Clear existing previews
    previewContainer.innerHTML = '';
    
    // Create preview grid with up to 4 items
    capturedImages.forEach((imageData, index) => {
        const imageItem = document.createElement('div');
        imageItem.style.cssText = `
            position: relative;
            width: 100%;
            padding-bottom: 100%;
            overflow: hidden;
            border-radius: 8px;
            background: #f0f0f0;
        `;
        
        const imgContainer = document.createElement('div');
        imgContainer.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        `;
        
        const img = document.createElement('img');
        img.src = imageData;
        img.style.cssText = `
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 8px;
        `;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.innerHTML = '×';
        removeBtn.style.cssText = `
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(244, 67, 54, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        `;
        removeBtn.onclick = (e) => {
            e.preventDefault();
            removeImage(index);
        };
        
        const photoNumber = document.createElement('div');
        photoNumber.style.cssText = `
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        `;
        photoNumber.textContent = `${index + 1}/${MAX_IMAGES}`;
        
        imgContainer.appendChild(img);
        imgContainer.appendChild(removeBtn);
        imgContainer.appendChild(photoNumber);
        imageItem.appendChild(imgContainer);
        previewContainer.appendChild(imageItem);
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    updateDashboardStats();

    // Set up navigation
    const navItems = document.querySelectorAll('.nav-item[data-section]');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            showSection(section);
        });
    });

    // Handle hash changes (browser back/forward, direct links)
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.substring(1); // Remove the '#'
        if (hash && ['dashboard', 'products', 'orders', 'customers', 'purchases', 'categories'].includes(hash)) {
            showSection(hash);
        }
    });

    // Load section based on current hash or default to dashboard
    const initialHash = window.location.hash.substring(1);
    const initialSection = ['dashboard', 'products', 'orders', 'customers', 'purchases', 'categories'].includes(initialHash)
        ? initialHash
        : 'dashboard';
    showSection(initialSection);

    // Set up add buttons
    const addProductBtn = document.getElementById('addProductBtn');
    if (addProductBtn) {
        addProductBtn.addEventListener('click', () => {
            // Reset edit mode
            isEditMode = false;
            editProductId = null;
            
            // Reset form for adding new product
            const form = document.getElementById('addProductForm');
            if (form) {
                form.reset();
            }
            // Reset images
            capturedImages = [];
            updateImagePreview();
            // Reset submit button
            const submitBtn = document.querySelector('#addProductModal .btn-primary');
            if (submitBtn) {
                submitBtn.textContent = 'Add Product';
            }
            openModal('addProductModal');
        });
    }

    const addCategoryBtn = document.getElementById('addCategoryBtn');
    if (addCategoryBtn) {
        addCategoryBtn.addEventListener('click', () => openModal('categoryModal'));
    }

    // Add Purchase button
    const addPurchaseBtn = document.getElementById('addPurchaseBtn');
    if (addPurchaseBtn) {
        addPurchaseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openPurchaseModal();
        });
    }

    // Set up form submissions
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (isEditMode && editProductId) {
                updateProduct(editProductId);
            } else {
                addProduct();
            }
        });
    }

    // Set up modal close buttons
    const closeButtons = document.querySelectorAll('.modal .close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
                if (modal.id === 'addProductModal') {
                    stopCamera();
                }
            }
        });
    });

    const cancelButtons = document.querySelectorAll('.modal .btn-secondary');
    cancelButtons.forEach(btn => {
        // Skip camera and image buttons
        if (btn.id === 'openCameraBtn' || btn.id === 'uploadImageBtn' || btn.id === 'closeCameraBtn') {
            return;
        }
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
                if (modal.id === 'addProductModal') {
                    stopCamera();
                }
            }
        });
    });

    // Initialize camera feature
    initializeCameraFeature();

    // Handle modal outside clicks
    window.addEventListener('click', function(e) {
        const modals = document.querySelectorAll('.modal.active');
        modals.forEach(modal => {
            if (e.target === modal) {
                closeModal(modal.id);
                stopCamera();
            }
        });
    });
});

// Show section function
function showSection(sectionName) {
    // Hide all sections (remove active class)
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.remove('active'));

    // Show selected section (add active class)
    const targetSection = document.getElementById(sectionName);
    if (targetSection) {
        targetSection.classList.add('active');
    }

    // Update active nav item
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => item.classList.remove('active'));
    const activeNav = document.querySelector(`[data-section="${sectionName}"]`);
    if (activeNav) {
        activeNav.classList.add('active');
    }

    // Update URL hash without triggering hashchange event
    if (window.location.hash !== `#${sectionName}`) {
        history.replaceState(null, null, `#${sectionName}`);
    }

    // Load section data
    switch (sectionName) {
        case 'dashboard':
            updateDashboardStats();
            break;
        case 'products':
            loadProductsTable();
            break;
        case 'orders':
            loadOrdersTable();
            break;
        case 'customers':
            loadCustomersTable();
            break;
        case 'purchases':
            loadPurchasesTable();
            break;
        case 'categories':
            loadCategoriesTable();
            break;
    }
}

// Purchase Modal Functions
function openPurchaseModal() {
    console.log('openPurchaseModal called');
    const modal = document.getElementById('purchaseModal');
    if (!modal) {
        console.log('Purchase modal not found');
        alert('Purchase modal not found');
        return;
    }
    console.log('Modal found, opening');
    
    // Clear all form fields
    const fields = [
        'purchaseSupplier', 'purchaseSupplierPhone', 'purchaseSupplierEmail', 'purchaseCity',
        'purchaseProductName', 'purchaseCategory', 'purchaseQuantity',
        'purchaseCostPrice', 'purchaseSellingPrice', 'purchaseGst',
        'purchasePaymentMethod', 'purchaseInvoiceNumber', 'purchaseDate',
        'purchaseStatus', 'purchaseNotes'
    ];
    
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            if (field.tagName === 'SELECT') {
                field.selectedIndex = 0;
            } else {
                field.value = '';
            }
        }
    });
    
    // Set default values
    if (document.getElementById('purchaseStatus')) {
        document.getElementById('purchaseStatus').value = 'received';
    }
    if (document.getElementById('purchaseGst')) {
        document.getElementById('purchaseGst').value = '18';
    }
    if (document.getElementById('purchasePaymentMethod')) {
        document.getElementById('purchasePaymentMethod').value = 'Cash';
    }
    
    // Set today's date
    const today = new Date().toISOString().slice(0,10);
    if (document.getElementById('purchaseDate')) {
        document.getElementById('purchaseDate').value = today;
    }
    
    // Reset calculation display
    if (document.getElementById('purchaseSubtotal')) {
        document.getElementById('purchaseSubtotal').textContent = '₹0.00';
    }
    if (document.getElementById('purchaseGstAmount')) {
        document.getElementById('purchaseGstAmount').textContent = '₹0.00';
    }
    if (document.getElementById('purchaseTotalAmount')) {
        document.getElementById('purchaseTotalAmount').textContent = '₹0.00';
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        const firstField = document.getElementById('purchaseSupplier');
        if (firstField) firstField.focus();
        // Scroll modal content to top
        modal.scrollTop = 0;
    }, 100);
}

function closePurchaseModal() {
    const modal = document.getElementById('purchaseModal');
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';
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