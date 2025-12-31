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

// Helper function to get first image from comma-separated list
function getFirstImage(imageStr) {
    if (!imageStr) return null;
    const images = imageStr.split(',').map(img => img.trim()).filter(img => img);
    return images.length > 0 ? images[0] : null;
}

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

// Populate subcategory dropdown from database
async function populateSubcategoryDropdown() {
    const subCategorySelect = document.getElementById('subCategory');
    if (!subCategorySelect) return;
    
    try {
        const categories = await loadCategories();
        
        // Keep the first "Select" option
        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        
        // Add categories from database
        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.name;
            option.textContent = cat.name;
            subCategorySelect.appendChild(option);
        });
        
        // Add "Add New" option at the end
        const addNewOption = document.createElement('option');
        addNewOption.value = '__add_new__';
        addNewOption.textContent = '+ Add New Sub Category';
        subCategorySelect.appendChild(addNewOption);
    } catch (error) {
        console.error('Failed to populate subcategory dropdown:', error);
    }
}

// Initialize category/subcategory add new functionality
function initializeCategoryAddNew() {
    const mainCategory = document.getElementById('mainCategory');
    const subCategory = document.getElementById('subCategory');
    const newCategoryInput = document.getElementById('newCategoryInput');
    const newSubCategoryInput = document.getElementById('newSubCategoryInput');
    const customCategory = document.getElementById('customCategory');
    const customSubCategory = document.getElementById('customSubCategory');
    const saveCategoryBtn = document.getElementById('saveCategoryBtn');
    const cancelCategoryBtn = document.getElementById('cancelCategoryBtn');
    const saveSubCategoryBtn = document.getElementById('saveSubCategoryBtn');
    const cancelSubCategoryBtn = document.getElementById('cancelSubCategoryBtn');

    // Main Category - Show input when "Add New" is selected
    if (mainCategory) {
        mainCategory.addEventListener('change', function() {
            if (this.value === '__add_new__') {
                newCategoryInput.style.display = 'block';
                customCategory.focus();
            } else {
                newCategoryInput.style.display = 'none';
            }
        });
    }

    // Save new category
    if (saveCategoryBtn) {
        saveCategoryBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            const newCatName = customCategory.value.trim();
            if (!newCatName) {
                showNotification('Please enter a category name', 'error');
                return;
            }
            
            // Add to dropdown
            const option = document.createElement('option');
            option.value = newCatName;
            option.textContent = newCatName;
            
            // Insert before "Add New" option
            const addNewOpt = mainCategory.querySelector('option[value="__add_new__"]');
            mainCategory.insertBefore(option, addNewOpt);
            
            // Select the new option
            mainCategory.value = newCatName;
            newCategoryInput.style.display = 'none';
            customCategory.value = '';
            
            showNotification('Category added to list!', 'success');
        });
    }

    // Cancel new category
    if (cancelCategoryBtn) {
        cancelCategoryBtn.addEventListener('click', function(e) {
            e.preventDefault();
            mainCategory.value = '';
            newCategoryInput.style.display = 'none';
            customCategory.value = '';
        });
    }

    // Sub Category - Show input when "Add New" is selected
    if (subCategory) {
        subCategory.addEventListener('change', function() {
            if (this.value === '__add_new__') {
                newSubCategoryInput.style.display = 'block';
                customSubCategory.focus();
            } else {
                newSubCategoryInput.style.display = 'none';
            }
        });
    }

    // Save new subcategory (also saves to backend)
    if (saveSubCategoryBtn) {
        saveSubCategoryBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            const newSubCatName = customSubCategory.value.trim();
            if (!newSubCatName) {
                showNotification('Please enter a sub category name', 'error');
                return;
            }
            
            try {
                // Save to backend
                await apiCall('api_categories.php?action=create', {
                    method: 'POST',
                    body: JSON.stringify({
                        name: newSubCatName,
                        icon: 'fa-tag'
                    })
                });
                
                // Add to dropdown
                const option = document.createElement('option');
                option.value = newSubCatName;
                option.textContent = newSubCatName;
                
                // Insert before "Add New" option
                const addNewOpt = subCategory.querySelector('option[value="__add_new__"]');
                subCategory.insertBefore(option, addNewOpt);
                
                // Select the new option
                subCategory.value = newSubCatName;
                newSubCategoryInput.style.display = 'none';
                customSubCategory.value = '';
                
                showNotification('Sub category saved successfully!', 'success');
                
                // Refresh categories list in background
                loadCategoriesTable();
                
            } catch (error) {
                console.error('Failed to save subcategory:', error);
                showNotification('Failed to save sub category', 'error');
            }
        });
    }

    // Cancel new subcategory
    if (cancelSubCategoryBtn) {
        cancelSubCategoryBtn.addEventListener('click', function(e) {
            e.preventDefault();
            subCategory.value = '';
            newSubCategoryInput.style.display = 'none';
            customSubCategory.value = '';
        });
    }

    // ============ BRAND ADD NEW ============
    const brandSelect = document.getElementById('brandSelect');
    const newBrandInput = document.getElementById('newBrandInput');
    const customBrand = document.getElementById('customBrand');
    const saveBrandBtn = document.getElementById('saveBrandBtn');
    const cancelBrandBtn = document.getElementById('cancelBrandBtn');

    // Brand - Show input when "Add New" is selected
    if (brandSelect) {
        brandSelect.addEventListener('change', function() {
            if (this.value === '__add_new__') {
                newBrandInput.classList.add('active');
                customBrand.focus();
            } else {
                newBrandInput.classList.remove('active');
            }
        });
    }

    // Save new brand
    if (saveBrandBtn) {
        saveBrandBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const newBrandName = customBrand.value.trim();
            if (!newBrandName) {
                showNotification('Please enter a brand name', 'error');
                return;
            }
            
            // Add to dropdown
            const option = document.createElement('option');
            option.value = newBrandName;
            option.textContent = newBrandName;
            
            // Insert before "Add New" option
            const addNewOpt = brandSelect.querySelector('option[value="__add_new__"]');
            brandSelect.insertBefore(option, addNewOpt);
            
            // Select the new option
            brandSelect.value = newBrandName;
            newBrandInput.classList.remove('active');
            customBrand.value = '';
            
            showNotification('Brand added successfully!', 'success');
        });
    }

    // Cancel new brand
    if (cancelBrandBtn) {
        cancelBrandBtn.addEventListener('click', function(e) {
            e.preventDefault();
            brandSelect.value = '';
            newBrandInput.classList.remove('active');
            customBrand.value = '';
        });
    }

    // ============ FRAME TYPE ADD NEW ============
    const frameTypeSelect = document.getElementById('frameTypeSelect');
    const newFrameTypeInput = document.getElementById('newFrameTypeInput');
    const customFrameType = document.getElementById('customFrameType');
    const saveFrameTypeBtn = document.getElementById('saveFrameTypeBtn');
    const cancelFrameTypeBtn = document.getElementById('cancelFrameTypeBtn');

    // Frame Type - Show input when "Add New" is selected
    if (frameTypeSelect) {
        frameTypeSelect.addEventListener('change', function() {
            if (this.value === '__add_new__') {
                newFrameTypeInput.classList.add('active');
                customFrameType.focus();
            } else {
                newFrameTypeInput.classList.remove('active');
            }
        });
    }

    // Save new frame type
    if (saveFrameTypeBtn) {
        saveFrameTypeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const newFrameTypeName = customFrameType.value.trim();
            if (!newFrameTypeName) {
                showNotification('Please enter a frame type', 'error');
                return;
            }
            
            // Add to dropdown
            const option = document.createElement('option');
            option.value = newFrameTypeName.toLowerCase().replace(/\s+/g, '-');
            option.textContent = newFrameTypeName;
            
            // Insert before "Add New" option
            const addNewOpt = frameTypeSelect.querySelector('option[value="__add_new__"]');
            frameTypeSelect.insertBefore(option, addNewOpt);
            
            // Select the new option
            frameTypeSelect.value = newFrameTypeName.toLowerCase().replace(/\s+/g, '-');
            newFrameTypeInput.classList.remove('active');
            customFrameType.value = '';
            
            showNotification('Frame type added successfully!', 'success');
        });
    }

    // Cancel new frame type
    if (cancelFrameTypeBtn) {
        cancelFrameTypeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            frameTypeSelect.value = '';
            newFrameTypeInput.classList.remove('active');
            customFrameType.value = '';
        });
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

        // Update orders badge in header
        const ordersBadge = document.getElementById('ordersBadge');
        if (ordersBadge) {
            const pendingOrders = orders.filter(o => o.status === 'pending' || o.status === 'processing').length;
            ordersBadge.textContent = pendingOrders;
            ordersBadge.style.display = pendingOrders > 0 ? 'flex' : 'none';
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
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
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
                <td>
                    <img src="${getFirstImage(product.image) || 'https://via.placeholder.com/50x50'}" alt="${product.name}" style="width: 50px; height: 50px; border-radius: 6px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/50x50?text=No+Image'">
                </td>
                <td>
                    <div style="font-weight: 600;">${product.name}</div>
                    <div style="font-size: 11px; color: #888;">#${product.id}</div>
                </td>
                <td>
                    <div>${product.category || '-'}</div>
                    <div style="font-size: 11px; color: #666;">${product.subcategory || ''}</div>
                </td>
                <td>${product.hsn || '-'}</td>
                <td>${product.brand || '-'}</td>
                <td><strong>₹${parseFloat(product.price || 0).toLocaleString()}</strong></td>
                <td>${product.gst || 0}%</td>
                <td>${product.stock || 0}</td>
                <td><span class="status-badge ${(product.status || 'active').toLowerCase()}">${product.status || 'Active'}</span></td>
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
                <td colspan="10" style="text-align: center; padding: 40px; color: #f44336;">
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
                    <p style="font-size: 12px; color: #666;"><i class="fas fa-shopping-cart"></i> ${category.orders_count || 0} orders</p>
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
    
    // Use captured images array if available, otherwise use form input
    let imageValue = capturedImages.length > 0 ? capturedImages.join(',') : formData.get('image');
    
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
        image: imageValue,
        video: formData.get('video'),
        description: formData.get('description')
    };

    console.log('Adding product with images:', imageValue);

    try {
        await apiCall('api_products.php?action=create', {
            method: 'POST',
            body: JSON.stringify(productData)
        });

        showNotification('Product added successfully!', 'success');
        closeModal('addProductModal');
        form.reset();
        capturedImages = []; // Clear captured images after successful save
        updateImagePreview();
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
        
        // Populate subcategory dropdown from database first
        await populateSubcategoryDropdown();
        
        // Populate form with product data
        const form = document.getElementById('addProductForm');
        if (form) {
            form.elements['name'].value = product.name || '';
            
            // Handle Brand dropdown - add option if not exists
            const brandSelect = form.elements['brand'];
            const brandValue = product.brand || '';
            if (brandValue && brandSelect) {
                let optionExists = Array.from(brandSelect.options).some(opt => opt.value === brandValue);
                if (!optionExists && brandValue) {
                    // Add the brand option before the "Add New" option
                    const addNewOption = brandSelect.querySelector('option[value="__add_new__"]');
                    const newOption = document.createElement('option');
                    newOption.value = brandValue;
                    newOption.textContent = brandValue;
                    if (addNewOption) {
                        brandSelect.insertBefore(newOption, addNewOption);
                    } else {
                        brandSelect.appendChild(newOption);
                    }
                }
                brandSelect.value = brandValue;
                console.log('Setting Brand value:', brandValue);
            }
            
            // Handle Category dropdown - add option if not exists
            const categorySelect = form.elements['category'];
            const categoryValue = product.category || '';
            if (categoryValue && categorySelect) {
                let optionExists = Array.from(categorySelect.options).some(opt => opt.value === categoryValue);
                if (!optionExists && categoryValue) {
                    // Add the category option before the "Add New" option
                    const addNewOption = categorySelect.querySelector('option[value="__add_new__"]');
                    const newOption = document.createElement('option');
                    newOption.value = categoryValue;
                    newOption.textContent = categoryValue;
                    if (addNewOption) {
                        categorySelect.insertBefore(newOption, addNewOption);
                    } else {
                        categorySelect.appendChild(newOption);
                    }
                }
                categorySelect.value = categoryValue;
                console.log('Setting Category value:', categoryValue);
            }
            
            form.elements['subcategory'].value = product.subcategory || '';
            form.elements['frametype'].value = product.frametype || '';
            form.elements['hsn'].value = product.hsn || '';
            form.elements['price'].value = product.price || '';
            form.elements['originalPrice'].value = product.original_price || '';
            
            // Handle GST dropdown - convert to string and match
            const gstSelect = form.elements['gst'];
            const gstValue = (product.gst !== undefined && product.gst !== null && product.gst !== '') ? String(product.gst) : '12';
            if (gstSelect) {
                // Check if option exists, if not try to match numeric value
                let optionExists = Array.from(gstSelect.options).some(opt => opt.value === gstValue);
                if (optionExists) {
                    gstSelect.value = gstValue;
                } else {
                    // Try matching without decimals (e.g., "12.00" -> "12")
                    const gstInt = String(parseInt(gstValue));
                    optionExists = Array.from(gstSelect.options).some(opt => opt.value === gstInt);
                    if (optionExists) {
                        gstSelect.value = gstInt;
                    } else {
                        gstSelect.value = '12'; // Default fallback
                    }
                }
                console.log('Setting GST value:', gstSelect.value, 'from product.gst:', product.gst);
            }
            
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
    
    // Use captured images array if available, otherwise use form input
    let imageValue = capturedImages.length > 0 ? capturedImages.join(',') : formData.get('image');
    
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
        image: imageValue,
        video: formData.get('video'),
        description: formData.get('description')
    };

    console.log('Updating product with images:', imageValue);

    try {
        await apiCall(`api_products.php?action=update&id=${id}`, {
            method: 'POST',
            body: JSON.stringify(productData)
        });

        showNotification('Product updated successfully!', 'success');
        closeModal('addProductModal');
        form.reset();
        capturedImages = []; // Clear captured images after successful save
        updateImagePreview();
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

async function capturePhoto() {
    if (capturedImages.length >= MAX_IMAGES) {
        showNotification(`Maximum ${MAX_IMAGES} photos allowed!`, 'error');
        return;
    }

    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    
    if (!video || !video.videoWidth) {
        showNotification('Camera not ready. Please wait...', 'error');
        return;
    }
    
    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert to data URL with good quality
    const base64Image = canvas.toDataURL('image/jpeg', 0.85);
    
    showNotification('Uploading photo...', 'info');
    
    try {
        // Upload the image to server
        const response = await fetch('api_upload.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ image: base64Image })
        });
        
        const result = await response.json();
        console.log('Upload result:', result);
        
        if (result.success && result.url) {
            // Add server URL to images array
            capturedImages.push(result.url);
            console.log('Captured images:', capturedImages);
            
            // Update preview
            updateImagePreview();
            
            // Update form input with comma-separated image URLs
            const imageUrlInput = document.getElementById('imageUrlInput');
            imageUrlInput.value = capturedImages.join(',');
            
            showNotification(`Photo ${capturedImages.length}/${MAX_IMAGES} captured and uploaded!`, 'success');
            
            // Stop camera if we've reached the maximum
            if (capturedImages.length >= MAX_IMAGES) {
                stopCamera();
                showNotification('Maximum photos reached. Camera stopped.', 'info');
            }
        } else {
            showNotification('Failed to upload photo: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error uploading image:', error);
        showNotification('Failed to upload photo. Please try again.', 'error');
    }
}

async function handleFileUpload(e) {
    const files = Array.from(e.target.files);
    
    for (const file of files) {
        if (capturedImages.length >= MAX_IMAGES) {
            showNotification(`Maximum ${MAX_IMAGES} photos allowed!`, 'error');
            break;
        }
        
        if (file && file.type.startsWith('image/')) {
            showNotification('Uploading image...', 'info');
            
            try {
                // Upload file to server
                const formData = new FormData();
                formData.append('image', file);
                
                const response = await fetch('api_upload.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success && result.url) {
                    capturedImages.push(result.url);
                    updateImagePreview();
                    
                    // Update form input with comma-separated image URLs
                    const imageUrlInput = document.getElementById('imageUrlInput');
                    imageUrlInput.value = capturedImages.join(',');
                    
                    showNotification(`Image ${capturedImages.length}/${MAX_IMAGES} uploaded successfully!`, 'success');
                } else {
                    showNotification('Failed to upload image: ' + (result.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                showNotification('Failed to upload image. Please try again.', 'error');
            }
        } else {
            showNotification('Please select valid image files.', 'error');
        }
    }
    
    // Clear the file input
    e.target.value = '';
}

function removeImage(index) {
    // If no index provided, clear all images
    if (index === undefined) {
        capturedImages = [];
        updateImagePreview();
        
        const imageUrlInput = document.getElementById('imageUrlInput');
        imageUrlInput.value = '';
        
        const imagePreview = document.getElementById('imagePreview');
        if (imagePreview) {
            imagePreview.style.display = 'none';
        }
        
        showNotification('All images cleared.', 'info');
        return;
    }
    
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
    const imagePreview = document.getElementById('imagePreview');
    const imageUrlInput = document.getElementById('imageUrlInput');
    
    if (capturedImages.length === 0) {
        previewContainer.innerHTML = '<span style="color: #999; padding: 20px; display: block; text-align: center;">No photos captured yet</span>';
        previewContainer.style.cssText = 'display: flex; align-items: center; justify-content: center; min-height: 100px; border: 2px dashed #ddd; border-radius: 8px; background: #f9f9f9;';
        if (imagePreview) imagePreview.style.display = 'none';
        if (imageUrlInput) imageUrlInput.value = '';
        return;
    }
    
    // Show the preview section
    if (imagePreview) imagePreview.style.display = 'block';
    
    // Update the hidden input
    if (imageUrlInput) imageUrlInput.value = capturedImages.join(',');
    
    // Set grid layout for previews
    previewContainer.style.cssText = 'display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;';
    
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
            border-radius: 10px;
            background: #f0f0f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        img.onerror = function() {
            this.src = 'https://via.placeholder.com/150x150?text=Error';
        };
        img.style.cssText = `
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        `;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.innerHTML = '×';
        removeBtn.style.cssText = `
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(244, 67, 54, 0.95);
            color: white;
            border: none;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        `;
        removeBtn.onmouseover = () => removeBtn.style.transform = 'scale(1.1)';
        removeBtn.onmouseout = () => removeBtn.style.transform = 'scale(1)';
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
        addProductBtn.addEventListener('click', async () => {
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
            // Hide custom category/subcategory inputs
            const newCategoryInput = document.getElementById('newCategoryInput');
            const newSubCategoryInput = document.getElementById('newSubCategoryInput');
            if (newCategoryInput) newCategoryInput.style.display = 'none';
            if (newSubCategoryInput) newSubCategoryInput.style.display = 'none';
            
            // Populate subcategory dropdown from database
            await populateSubcategoryDropdown();
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
        // Skip camera, image, and category buttons
        if (btn.id === 'openCameraBtn' || btn.id === 'uploadImageBtn' || btn.id === 'closeCameraBtn' ||
            btn.id === 'cancelCategoryBtn' || btn.id === 'cancelSubCategoryBtn') {
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

    // Initialize category add new functionality
    initializeCategoryAddNew();

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

// ==================== ORDER FUNCTIONS ====================

// View Order Details
async function viewOrder(orderId) {
    console.log('viewOrder called with id:', orderId);
    
    try {
        const response = await fetch(`api_orders.php?action=get&id=${orderId}`);
        const order = await response.json();
        
        if (order.error) {
            showNotification('Order not found', 'error');
            return;
        }
        
        const orderDate = new Date(order.order_date || order.created_at).toLocaleDateString('en-IN', {
            day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
        });
        
        // Build order items HTML
        let itemsHTML = '';
        if (order.items && order.items.length > 0) {
            itemsHTML = `
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Product</th>
                            <th style="padding: 10px; text-align: center; border-bottom: 2px solid #ddd;">Qty</th>
                            <th style="padding: 10px; text-align: right; border-bottom: 2px solid #ddd;">Price</th>
                            <th style="padding: 10px; text-align: right; border-bottom: 2px solid #ddd;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${order.items.map(item => `
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">${item.product_name}</td>
                                <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">${item.quantity}</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">₹${parseFloat(item.price).toLocaleString()}</td>
                                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">₹${parseFloat(item.total).toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else if (order.products) {
            itemsHTML = `<p style="padding: 10px; background: #f9f9f9; border-radius: 8px;">${order.products}</p>`;
        }
        
        const detailsHTML = `
            <div style="display: grid; gap: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                        <h4 style="margin: 0 0 10px; color: #00bac7;"><i class="fas fa-info-circle"></i> Order Info</h4>
                        <p><strong>Order ID:</strong> ${order.id}</p>
                        <p><strong>Date:</strong> ${orderDate}</p>
                        <p><strong>Status:</strong> <span class="status-badge ${order.status || 'pending'}">${order.status || 'Pending'}</span></p>
                        <p><strong>Payment:</strong> ${order.payment_method || 'N/A'}</p>
                    </div>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                        <h4 style="margin: 0 0 10px; color: #00bac7;"><i class="fas fa-user"></i> Customer Info</h4>
                        <p><strong>Name:</strong> ${order.customer_name || 'N/A'}</p>
                        <p><strong>Address:</strong> ${order.shipping_address || 'N/A'}</p>
                    </div>
                </div>
                
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <h4 style="margin: 0 0 10px; color: #00bac7;"><i class="fas fa-shopping-bag"></i> Order Items</h4>
                    ${itemsHTML}
                </div>
                
                <div style="background: #00bac7; color: white; padding: 15px; border-radius: 8px; text-align: right;">
                    <h3 style="margin: 0;">Total: ₹${parseFloat(order.total_amount || 0).toLocaleString()}</h3>
                </div>
            </div>
        `;
        
        document.getElementById('orderDetailsContent').innerHTML = detailsHTML;
        
        // Store current order ID for printing
        window.currentOrderId = orderId;
        window.currentOrder = order;
        
        openModal('viewOrderModal');
        
    } catch (error) {
        console.error('Error fetching order:', error);
        showNotification('Failed to load order details', 'error');
    }
}

// Edit Order
async function editOrder(orderId) {
    console.log('editOrder called with id:', orderId);
    
    try {
        const response = await fetch(`api_orders.php?action=get&id=${orderId}`);
        const order = await response.json();
        
        if (order.error) {
            showNotification('Order not found', 'error');
            return;
        }
        
        // Populate form fields
        document.getElementById('editOrderId').value = order.id;
        document.getElementById('editOrderIdDisplay').value = order.id;
        document.getElementById('editOrderCustomer').value = order.customer_name || '';
        document.getElementById('editOrderStatus').value = order.status || 'pending';
        document.getElementById('editOrderPayment').value = order.payment_method || '';
        document.getElementById('editOrderAddress').value = order.shipping_address || '';
        document.getElementById('editOrderTotal').value = order.total_amount || 0;
        
        openModal('editOrderModal');
        
    } catch (error) {
        console.error('Error fetching order:', error);
        showNotification('Failed to load order details', 'error');
    }
}

// Save Order Changes
async function saveOrderChanges(event) {
    event.preventDefault();
    
    const orderId = document.getElementById('editOrderId').value;
    const orderData = {
        customer_name: document.getElementById('editOrderCustomer').value,
        status: document.getElementById('editOrderStatus').value,
        payment_method: document.getElementById('editOrderPayment').value,
        shipping_address: document.getElementById('editOrderAddress').value
    };
    
    try {
        const response = await fetch(`api_orders.php?action=update&id=${orderId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Order updated successfully!', 'success');
            closeModal('editOrderModal');
            loadOrdersTable();
        } else {
            showNotification('Failed to update order: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error updating order:', error);
        showNotification('Failed to update order', 'error');
    }
}

// Print Order
function printOrder() {
    const order = window.currentOrder;
    if (!order) {
        showNotification('No order to print', 'error');
        return;
    }
    
    const orderDate = new Date(order.order_date || order.created_at).toLocaleDateString('en-IN', {
        day: '2-digit', month: 'long', year: 'numeric'
    });
    
    let itemsHTML = '';
    if (order.items && order.items.length > 0) {
        itemsHTML = order.items.map(item => `
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">${item.product_name}</td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${item.quantity}</td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">₹${parseFloat(item.price).toLocaleString()}</td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">₹${parseFloat(item.total).toLocaleString()}</td>
            </tr>
        `).join('');
    } else if (order.products) {
        itemsHTML = `<tr><td colspan="4" style="padding: 8px; border: 1px solid #ddd;">${order.products}</td></tr>`;
    }
    
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order ${order.id} - VisionKart</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h1 { color: #00bac7; }
                .info-grid { display: flex; gap: 40px; margin-bottom: 20px; }
                .info-section { flex: 1; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background: #00bac7; color: white; padding: 10px; text-align: left; }
                .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; }
                @media print { body { padding: 0; } }
            </style>
        </head>
        <body>
            <h1>VisionKart - Order Invoice</h1>
            <hr>
            <div class="info-grid">
                <div class="info-section">
                    <h3>Order Details</h3>
                    <p><strong>Order ID:</strong> ${order.id}</p>
                    <p><strong>Date:</strong> ${orderDate}</p>
                    <p><strong>Status:</strong> ${order.status || 'Pending'}</p>
                    <p><strong>Payment:</strong> ${order.payment_method || 'N/A'}</p>
                </div>
                <div class="info-section">
                    <h3>Customer Details</h3>
                    <p><strong>Name:</strong> ${order.customer_name || 'N/A'}</p>
                    <p><strong>Address:</strong> ${order.shipping_address || 'N/A'}</p>
                </div>
            </div>
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML}
                </tbody>
            </table>
            <div class="total">
                <strong>Grand Total: ₹${parseFloat(order.total_amount || 0).toLocaleString()}</strong>
            </div>
            <hr>
            <p style="text-align: center; color: #666; margin-top: 30px;">Thank you for shopping with VisionKart!</p>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
    }, 250);
}

// Initialize edit order form listener
document.addEventListener('DOMContentLoaded', function() {
    const editOrderForm = document.getElementById('editOrderForm');
    if (editOrderForm) {
        editOrderForm.addEventListener('submit', saveOrderChanges);
    }
    
    const editCustomerForm = document.getElementById('editCustomerForm');
    if (editCustomerForm) {
        editCustomerForm.addEventListener('submit', saveCustomerChanges);
    }
});

// ==================== CUSTOMER FUNCTIONS ====================

// View Customer Details
async function viewCustomer(customerId) {
    console.log('viewCustomer called with id:', customerId);
    
    try {
        const response = await fetch(`api_customers.php?action=get&id=${customerId}`);
        const customer = await response.json();
        
        if (customer.error) {
            showNotification('Customer not found', 'error');
            return;
        }
        
        const joinedDate = new Date(customer.created_at).toLocaleDateString('en-IN', {
            day: '2-digit', month: 'long', year: 'numeric'
        });
        
        const lastLogin = customer.last_login 
            ? new Date(customer.last_login).toLocaleDateString('en-IN', {
                day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            })
            : 'Never';
        
        // Build orders HTML
        let ordersHTML = '';
        if (customer.orders && customer.orders.length > 0) {
            ordersHTML = `
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Order ID</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Date</th>
                            <th style="padding: 10px; text-align: center; border-bottom: 2px solid #ddd;">Status</th>
                            <th style="padding: 10px; text-align: right; border-bottom: 2px solid #ddd;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${customer.orders.map(order => {
                            const orderDate = new Date(order.order_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
                            return `
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;">${order.id}</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;">${orderDate}</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">
                                        <span class="status-badge ${order.status || 'pending'}">${order.status || 'Pending'}</span>
                                    </td>
                                    <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">₹${parseFloat(order.total_amount).toLocaleString()}</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;
        } else {
            ordersHTML = `<p style="padding: 10px; background: #f9f9f9; border-radius: 8px; text-align: center; color: #999;">No orders yet</p>`;
        }
        
        const detailsHTML = `
            <div style="display: grid; gap: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                        <h4 style="margin: 0 0 10px; color: #00bac7;"><i class="fas fa-user"></i> Personal Info</h4>
                        <p><strong>ID:</strong> #${customer.id}</p>
                        <p><strong>Name:</strong> ${customer.name || 'N/A'}</p>
                        <p><strong>Email:</strong> ${customer.email}</p>
                        <p><strong>Phone:</strong> ${customer.phone || 'N/A'}</p>
                        <p><strong>Role:</strong> <span style="text-transform: capitalize;">${customer.role || 'Customer'}</span></p>
                        <p><strong>Status:</strong> <span class="status-badge ${customer.status || 'active'}">${customer.status || 'Active'}</span></p>
                    </div>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                        <h4 style="margin: 0 0 10px; color: #00bac7;"><i class="fas fa-chart-line"></i> Activity</h4>
                        <p><strong>Joined:</strong> ${joinedDate}</p>
                        <p><strong>Last Login:</strong> ${lastLogin}</p>
                        <p><strong>Total Orders:</strong> ${customer.orders_count || 0}</p>
                        <p><strong>Total Spent:</strong> ₹${parseFloat(customer.total_spent || 0).toLocaleString()}</p>
                    </div>
                </div>
                
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <h4 style="margin: 0 0 10px; color: #00bac7;"><i class="fas fa-shopping-bag"></i> Order History</h4>
                    ${ordersHTML}
                </div>
            </div>
        `;
        
        document.getElementById('customerDetailsContent').innerHTML = detailsHTML;
        
        // Store current customer ID for edit
        window.currentCustomerId = customerId;
        window.currentCustomer = customer;
        
        openModal('viewCustomerModal');
        
    } catch (error) {
        console.error('Error fetching customer:', error);
        showNotification('Failed to load customer details', 'error');
    }
}

// Edit Customer
async function editCustomer(customerId) {
    console.log('editCustomer called with id:', customerId);
    
    try {
        const response = await fetch(`api_customers.php?action=get&id=${customerId}`);
        const customer = await response.json();
        
        if (customer.error) {
            showNotification('Customer not found', 'error');
            return;
        }
        
        // Parse name into first and last name
        const nameParts = (customer.name || '').split(' ');
        const firstName = nameParts[0] || '';
        const lastName = nameParts.slice(1).join(' ') || '';
        
        // Populate form fields
        document.getElementById('editCustomerId').value = customer.id;
        document.getElementById('editCustomerFirstName').value = firstName;
        document.getElementById('editCustomerLastName').value = lastName;
        document.getElementById('editCustomerEmail').value = customer.email || '';
        document.getElementById('editCustomerPhone').value = customer.phone || '';
        document.getElementById('editCustomerStatus').value = customer.status || 'active';
        document.getElementById('editCustomerRole').value = customer.role || 'customer';
        
        openModal('editCustomerModal');
        
    } catch (error) {
        console.error('Error fetching customer:', error);
        showNotification('Failed to load customer details', 'error');
    }
}

// Edit customer from view modal
function editCustomerFromView() {
    const customerId = window.currentCustomerId;
    if (customerId) {
        closeModal('viewCustomerModal');
        setTimeout(() => {
            editCustomer(customerId);
        }, 300);
    }
}

// Save Customer Changes
async function saveCustomerChanges(event) {
    event.preventDefault();
    
    const customerId = document.getElementById('editCustomerId').value;
    const customerData = {
        first_name: document.getElementById('editCustomerFirstName').value,
        last_name: document.getElementById('editCustomerLastName').value,
        email: document.getElementById('editCustomerEmail').value,
        phone: document.getElementById('editCustomerPhone').value,
        status: document.getElementById('editCustomerStatus').value,
        role: document.getElementById('editCustomerRole').value
    };
    
    try {
        const response = await fetch(`api_customers.php?action=update&id=${customerId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(customerData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Customer updated successfully!', 'success');
            closeModal('editCustomerModal');
            loadCustomersTable();
        } else {
            showNotification('Failed to update customer: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error updating customer:', error);
        showNotification('Failed to update customer', 'error');
    }
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
        to { transform: translateX(0); opacity: 0; }
    }
`;
document.head.appendChild(style);

// =============================================
// SEARCH FUNCTIONALITY
// =============================================

// Clear search input
function clearSearch(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = '';
        input.dispatchEvent(new Event('keyup'));
        
        // Hide clear button
        const clearBtn = input.parentElement.querySelector('.clear-search');
        if (clearBtn) clearBtn.style.display = 'none';
    }
}

// Show/hide clear button based on input value
function updateClearButton(input) {
    const clearBtn = input.parentElement.querySelector('.clear-search');
    if (clearBtn) {
        clearBtn.style.display = input.value.length > 0 ? 'block' : 'none';
    }
}

// Dashboard Search - searches recent orders table
function searchDashboard(query) {
    const input = document.getElementById('dashboardSearch');
    updateClearButton(input);
    
    query = query.toLowerCase().trim();
    const tableBody = document.querySelector('#dashboard .recent-orders tbody');
    
    if (!tableBody) return;
    
    const rows = tableBody.querySelectorAll('tr');
    let hasResults = false;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowText = '';
        cells.forEach(cell => {
            rowText += cell.textContent.toLowerCase() + ' ';
        });
        
        if (query === '' || rowText.includes(query)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show no results message if needed
    showNoResultsMessage(tableBody.closest('.table-container') || tableBody.closest('.recent-orders'), hasResults, query);
}

// Products Search
function searchProducts(query) {
    const input = document.getElementById('productSearch');
    updateClearButton(input);
    
    query = query.toLowerCase().trim();
    const tableBody = document.getElementById('productsTableBody');
    
    if (!tableBody) return;
    
    const rows = tableBody.querySelectorAll('tr');
    let hasResults = false;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowText = '';
        cells.forEach(cell => {
            rowText += cell.textContent.toLowerCase() + ' ';
        });
        
        if (query === '' || rowText.includes(query)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    });
    
    showNoResultsMessage(tableBody.closest('.table-container'), hasResults, query);
}

// Orders Search
function searchOrders(query) {
    const input = document.getElementById('orderSearch');
    updateClearButton(input);
    
    query = query.toLowerCase().trim();
    const tableBody = document.getElementById('ordersTableBody');
    
    if (!tableBody) return;
    
    const rows = tableBody.querySelectorAll('tr');
    let hasResults = false;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowText = '';
        cells.forEach(cell => {
            rowText += cell.textContent.toLowerCase() + ' ';
        });
        
        if (query === '' || rowText.includes(query)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    });
    
    showNoResultsMessage(tableBody.closest('.table-container'), hasResults, query);
}

// Purchases Search
function searchPurchases(query) {
    const input = document.getElementById('purchaseSearch');
    updateClearButton(input);
    
    query = query.toLowerCase().trim();
    const tableBody = document.getElementById('purchasesTableBody');
    
    if (!tableBody) return;
    
    const rows = tableBody.querySelectorAll('tr');
    let hasResults = false;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowText = '';
        cells.forEach(cell => {
            rowText += cell.textContent.toLowerCase() + ' ';
        });
        
        if (query === '' || rowText.includes(query)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    });
    
    showNoResultsMessage(tableBody.closest('.table-container'), hasResults, query);
}

// Customers Search
function searchCustomers(query) {
    const input = document.getElementById('customerSearch');
    updateClearButton(input);
    
    query = query.toLowerCase().trim();
    const tableBody = document.getElementById('customersTableBody');
    
    if (!tableBody) return;
    
    const rows = tableBody.querySelectorAll('tr');
    let hasResults = false;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let rowText = '';
        cells.forEach(cell => {
            rowText += cell.textContent.toLowerCase() + ' ';
        });
        
        if (query === '' || rowText.includes(query)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    });
    
    showNoResultsMessage(tableBody.closest('.table-container'), hasResults, query);
}

// Categories Search
function searchCategories(query) {
    const input = document.getElementById('categorySearch');
    updateClearButton(input);
    
    query = query.toLowerCase().trim();
    const categoriesGrid = document.getElementById('categoriesGrid');
    
    if (!categoriesGrid) return;
    
    const cards = categoriesGrid.querySelectorAll('.category-card');
    let hasResults = false;
    
    cards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        
        if (query === '' || cardText.includes(query)) {
            card.style.display = '';
            hasResults = true;
        } else {
            card.style.display = 'none';
        }
    });
    
    showNoResultsMessage(categoriesGrid, hasResults, query, true);
}

// Show "No Results" message
function showNoResultsMessage(container, hasResults, query, isGrid = false) {
    if (!container) return;
    
    // Remove existing no results message
    const existingMsg = container.querySelector('.no-results-message');
    if (existingMsg) {
        existingMsg.remove();
    }
    
    // Add no results message if no results and query is not empty
    if (!hasResults && query !== '') {
        const noResultsDiv = document.createElement('div');
        noResultsDiv.className = 'no-results-message';
        noResultsDiv.innerHTML = `
            <i class="fas fa-search"></i>
            <p>No results found for "<strong>${escapeHtml(query)}</strong>"</p>
            <p style="font-size: 12px; margin-top: 5px;">Try a different search term</p>
        `;
        
        if (isGrid) {
            container.appendChild(noResultsDiv);
        } else {
            container.appendChild(noResultsDiv);
        }
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}