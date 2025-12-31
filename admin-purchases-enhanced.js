// Purchase Management Functions - Enhanced Version with Multiple Products Support

// Store purchase product items
let purchaseProductItems = [];
let purchaseProductRowCounter = 0;

// Store custom categories and subcategories
let customPurchaseCategories = [];
let customPurchaseSubCategories = [];

// Load custom categories from database on page load
async function loadCustomPurchaseCategories() {
    try {
        const response = await fetch('api_purchase_categories.php?action=all');
        const data = await response.json();
        if (data.categories) {
            customPurchaseCategories = data.categories;
        }
        if (data.subcategories) {
            customPurchaseSubCategories = data.subcategories;
        }
        console.log('Loaded custom categories:', customPurchaseCategories);
        console.log('Loaded custom subcategories:', customPurchaseSubCategories);
    } catch (error) {
        console.error('Error loading custom categories:', error);
    }
}

// Save category to database
async function saveCustomCategory(name, type, parentCategory = null) {
    try {
        const response = await fetch('api_purchase_categories.php?action=create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, type, parent_category: parentCategory })
        });
        const data = await response.json();
        return data.success;
    } catch (error) {
        console.error('Error saving category:', error);
        return false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCustomPurchaseCategories();
});

// Initialize purchase modal
function initPurchaseModal() {
    purchaseProductItems = [];
    purchaseProductRowCounter = 0;
    const tbody = document.getElementById('purchaseProductsBody');
    if (tbody) tbody.innerHTML = '';
    addPurchaseProductRow(); // Add first row by default
}

// Get all category options including custom ones
function getCategoryOptionsHTML() {
    const defaultCategories = ['Eyeglasses', 'Sunglasses', 'Contact Lenses', 'Kids Glasses', 'Accessories', 'Other'];
    const allCategories = [...new Set([...defaultCategories, ...customPurchaseCategories])];
    
    let html = '<option value="">Select</option>';
    allCategories.forEach(cat => {
        html += `<option value="${cat}">${cat}</option>`;
    });
    html += '<option value="__add_new__" style="color:#00bac7; font-weight:600;">+ Add New Category</option>';
    return html;
}

// Get all subcategory options including custom ones
function getSubCategoryOptionsHTML() {
    const defaultSubCategories = ['Round', 'Cat-Eye', 'Clubmaster', 'Aviator', 'Wayfarer', 'Transparent', 'Sports', 'Rectangle', 'Oval', 'Square', 'Other'];
    const allSubCategories = [...new Set([...defaultSubCategories, ...customPurchaseSubCategories])];
    
    let html = '<option value="">Select</option>';
    allSubCategories.forEach(subcat => {
        html += `<option value="${subcat}">${subcat}</option>`;
    });
    html += '<option value="__add_new__" style="color:#00bac7; font-weight:600;">+ Add New Sub Category</option>';
    return html;
}

// Handle category change - check for "Add New" selection
function handleCategoryChange(rowId) {
    const select = document.getElementById(`ppCategory_${rowId}`);
    if (!select) return;
    
    if (select.value === '__add_new__') {
        showAddNewCategoryModal(rowId);
        select.value = ''; // Reset selection
    } else {
        updateSubcategoryOptions(rowId);
    }
}

// Handle subcategory change - check for "Add New" selection
function handleSubCategoryChange(rowId) {
    const select = document.getElementById(`ppSubCategory_${rowId}`);
    if (!select) return;
    
    if (select.value === '__add_new__') {
        showAddNewSubCategoryModal(rowId);
        select.value = ''; // Reset selection
    }
}

// Show modal to add new category
async function showAddNewCategoryModal(rowId) {
    const newCategory = prompt('Enter new category name:');
    if (newCategory && newCategory.trim()) {
        const trimmedCategory = newCategory.trim();
        if (!customPurchaseCategories.includes(trimmedCategory)) {
            customPurchaseCategories.push(trimmedCategory);
            // Save to database
            const saved = await saveCustomCategory(trimmedCategory, 'category');
            if (saved) {
                console.log('Category saved to database:', trimmedCategory);
            }
        }
        // Update all category dropdowns
        updateAllCategoryDropdowns();
        // Set the new category in the current row
        const select = document.getElementById(`ppCategory_${rowId}`);
        if (select) {
            select.value = trimmedCategory;
        }
        showNotification(`Category "${trimmedCategory}" added successfully!`, 'success');
    }
}

// Show modal to add new subcategory
async function showAddNewSubCategoryModal(rowId) {
    const newSubCategory = prompt('Enter new sub category name:');
    if (newSubCategory && newSubCategory.trim()) {
        const trimmedSubCategory = newSubCategory.trim();
        // Get current category for parent reference
        const categorySelect = document.getElementById(`ppCategory_${rowId}`);
        const parentCategory = categorySelect ? categorySelect.value : null;
        
        if (!customPurchaseSubCategories.includes(trimmedSubCategory)) {
            customPurchaseSubCategories.push(trimmedSubCategory);
            // Save to database
            const saved = await saveCustomCategory(trimmedSubCategory, 'subcategory', parentCategory);
            if (saved) {
                console.log('Subcategory saved to database:', trimmedSubCategory);
            }
        }
        // Update all subcategory dropdowns
        updateAllSubCategoryDropdowns();
        // Update current row's subcategory dropdown with new options
        updateSubcategoryOptions(rowId);
        // Set the new subcategory in the current row
        const select = document.getElementById(`ppSubCategory_${rowId}`);
        if (select) {
            select.value = trimmedSubCategory;
        }
        showNotification(`Sub Category "${trimmedSubCategory}" added successfully!`, 'success');
    }
}

// Update all category dropdowns in all rows
function updateAllCategoryDropdowns() {
    const tbody = document.getElementById('purchaseProductsBody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        const rowId = row.id.replace('purchaseProductRow_', '');
        const select = document.getElementById(`ppCategory_${rowId}`);
        if (select) {
            const currentValue = select.value;
            select.innerHTML = getCategoryOptionsHTML();
            if (currentValue && currentValue !== '__add_new__') {
                select.value = currentValue;
            }
        }
    });
}

// Update all subcategory dropdowns in all rows
function updateAllSubCategoryDropdowns() {
    const tbody = document.getElementById('purchaseProductsBody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        const rowId = row.id.replace('purchaseProductRow_', '');
        const select = document.getElementById(`ppSubCategory_${rowId}`);
        if (select) {
            const currentValue = select.value;
            select.innerHTML = getSubCategoryOptionsHTML();
            if (currentValue && currentValue !== '__add_new__') {
                select.value = currentValue;
            }
        }
    });
}

// Add a new product row to the purchase
function addPurchaseProductRow() {
    purchaseProductRowCounter++;
    const rowId = purchaseProductRowCounter;
    const tbody = document.getElementById('purchaseProductsBody');
    if (!tbody) return;

    const row = document.createElement('tr');
    row.id = `purchaseProductRow_${rowId}`;
    row.innerHTML = `
        <td style="padding:8px 4px;">
            <input type="text" id="ppName_${rowId}" placeholder="Product name" 
                   style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px;" />
        </td>
        <td style="padding:8px 4px;">
            <select id="ppCategory_${rowId}" style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px;" onchange="handleCategoryChange(${rowId})">
                ${getCategoryOptionsHTML()}
            </select>
        </td>
        <td style="padding:8px 4px;">
            <select id="ppSubCategory_${rowId}" style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px;" onchange="handleSubCategoryChange(${rowId})">
                ${getSubCategoryOptionsHTML()}
            </select>
        </td>
        <td style="padding:8px 4px;">
            <input type="text" id="ppHsn_${rowId}" placeholder="9004" value="9004"
                   style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px;" />
        </td>
        <td style="padding:8px 4px;">
            <input type="number" id="ppQty_${rowId}" min="1" value="1" 
                   style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px; text-align:center;"
                   onchange="calculateRowTotal(${rowId})" onkeyup="calculateRowTotal(${rowId})" />
        </td>
        <td style="padding:8px 4px;">
            <input type="number" id="ppPurchasePrice_${rowId}" step="0.01" min="0" placeholder="0.00"
                   style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px; text-align:right;"
                   onchange="calculateRowTotal(${rowId})" onkeyup="calculateRowTotal(${rowId})" />
        </td>
        <td style="padding:8px 4px;">
            <input type="number" id="ppSellPrice_${rowId}" step="0.01" min="0" placeholder="0.00"
                   style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px; text-align:right;" />
        </td>
        <td style="padding:8px 4px;">
            <select id="ppGst_${rowId}" style="width:100%; padding:8px; border:1px solid #e0e0e0; border-radius:4px; font-size:13px;"
                    onchange="calculateRowTotal(${rowId})">
                <option value="0">0%</option>
                <option value="5">5%</option>
                <option value="12" selected>12%</option>
                <option value="18">18%</option>
                <option value="28">28%</option>
            </select>
        </td>
        <td style="padding:8px 4px; text-align:right;">
            <span id="ppAmount_${rowId}" style="font-weight:600; color:#00bac7;">₹0.00</span>
        </td>
        <td style="padding:8px 4px; text-align:center;">
            <button type="button" onclick="removePurchaseProductRow(${rowId})" 
                    style="background:#ff4444; color:white; border:none; width:30px; height:30px; border-radius:4px; cursor:pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    calculateAllTotals();
}

// Update subcategory options based on category selection
function updateSubcategoryOptions(rowId) {
    const category = document.getElementById(`ppCategory_${rowId}`)?.value;
    const subCategorySelect = document.getElementById(`ppSubCategory_${rowId}`);
    if (!subCategorySelect) return;

    let baseOptions = [];
    
    if (category === 'Eyeglasses' || category === 'Sunglasses') {
        baseOptions = ['Round', 'Cat-Eye', 'Clubmaster', 'Aviator', 'Wayfarer', 'Rectangle', 'Oval', 'Square', 'Sports', 'Transparent', 'Other'];
    } else if (category === 'Contact Lenses') {
        baseOptions = ['Daily', 'Weekly', 'Monthly', 'Yearly', 'Colored', 'Other'];
    } else if (category === 'Kids Glasses') {
        baseOptions = ['Round', 'Rectangle', 'Oval', 'Sports', 'Other'];
    } else if (category === 'Accessories') {
        baseOptions = ['Cases', 'Cleaning Cloth', 'Chains', 'Nose Pads', 'Temple Tips', 'Other'];
    } else {
        baseOptions = ['Other'];
    }
    
    // Merge with custom subcategories
    const allSubCategories = [...new Set([...baseOptions, ...customPurchaseSubCategories])];
    
    let options = '<option value="">Select</option>';
    allSubCategories.forEach(subcat => {
        options += `<option value="${subcat}">${subcat}</option>`;
    });
    options += '<option value="__add_new__" style="color:#00bac7; font-weight:600;">+ Add New Sub Category</option>';
    
    subCategorySelect.innerHTML = options;
}

// Remove a product row
function removePurchaseProductRow(rowId) {
    const row = document.getElementById(`purchaseProductRow_${rowId}`);
    if (row) {
        row.remove();
        calculateAllTotals();
    }
    
    // Ensure at least one row exists
    const tbody = document.getElementById('purchaseProductsBody');
    if (tbody && tbody.children.length === 0) {
        addPurchaseProductRow();
    }
}

// Calculate total for a single row
function calculateRowTotal(rowId) {
    const qty = parseFloat(document.getElementById(`ppQty_${rowId}`)?.value) || 0;
    const price = parseFloat(document.getElementById(`ppPurchasePrice_${rowId}`)?.value) || 0;
    const gst = parseFloat(document.getElementById(`ppGst_${rowId}`)?.value) || 0;
    
    const subtotal = qty * price;
    const gstAmount = (subtotal * gst) / 100;
    const total = subtotal + gstAmount;
    
    const amountSpan = document.getElementById(`ppAmount_${rowId}`);
    if (amountSpan) {
        amountSpan.textContent = `₹${total.toFixed(2)}`;
    }
    
    calculateAllTotals();
}

// Calculate all totals
function calculateAllTotals() {
    const tbody = document.getElementById('purchaseProductsBody');
    if (!tbody) return;
    
    let totalItems = 0;
    let totalSubtotal = 0;
    let totalGst = 0;
    
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        const rowId = row.id.replace('purchaseProductRow_', '');
        const qty = parseFloat(document.getElementById(`ppQty_${rowId}`)?.value) || 0;
        const price = parseFloat(document.getElementById(`ppPurchasePrice_${rowId}`)?.value) || 0;
        const gst = parseFloat(document.getElementById(`ppGst_${rowId}`)?.value) || 0;
        
        const subtotal = qty * price;
        const gstAmount = (subtotal * gst) / 100;
        
        totalItems += qty;
        totalSubtotal += subtotal;
        totalGst += gstAmount;
    });
    
    const grandTotal = totalSubtotal + totalGst;
    
    // Update display
    if (document.getElementById('purchaseTotalItems')) {
        document.getElementById('purchaseTotalItems').textContent = totalItems;
    }
    if (document.getElementById('purchaseSubtotal')) {
        document.getElementById('purchaseSubtotal').textContent = `₹${totalSubtotal.toFixed(2)}`;
    }
    if (document.getElementById('purchaseGstAmount')) {
        document.getElementById('purchaseGstAmount').textContent = `₹${totalGst.toFixed(2)}`;
    }
    if (document.getElementById('purchaseTotalAmount')) {
        document.getElementById('purchaseTotalAmount').textContent = `₹${grandTotal.toFixed(2)}`;
    }
}

// Legacy function for backward compatibility
function calculatePurchaseTotal() {
    calculateAllTotals();
}

// Open purchase modal
function openPurchaseModal() {
    const modal = document.getElementById('purchaseModal');
    if (!modal) return alert('Purchase modal not found');
    
    // Reset form fields
    document.getElementById('purchaseSupplier').value = '';
    document.getElementById('purchaseSupplierPhone').value = '';
    document.getElementById('purchaseCity').value = '';
    document.getElementById('purchaseInvoiceNumber').value = '';
    document.getElementById('purchaseNotes').value = '';
    document.getElementById('purchaseStatus').value = 'received';
    document.getElementById('purchasePaymentMethod').value = 'Cash';
    document.getElementById('purchaseDate').value = new Date().toISOString().split('T')[0];
    
    // Initialize product rows
    initPurchaseModal();
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => document.getElementById('purchaseSupplier')?.focus(), 50);
}

// Close purchase modal
function closePurchaseModal() {
    const modal = document.getElementById('purchaseModal');
    if (!modal) return;
    
    modal.style.display = 'none';
    document.body.style.overflow = '';
    purchaseProductItems = [];
}

// Collect all product items from the form
function collectProductItems() {
    const tbody = document.getElementById('purchaseProductsBody');
    if (!tbody) return [];
    
    const items = [];
    const rows = tbody.querySelectorAll('tr');
    
    rows.forEach(row => {
        const rowId = row.id.replace('purchaseProductRow_', '');
        const name = document.getElementById(`ppName_${rowId}`)?.value?.trim();
        const category = document.getElementById(`ppCategory_${rowId}`)?.value;
        const subcategory = document.getElementById(`ppSubCategory_${rowId}`)?.value;
        const hsn = document.getElementById(`ppHsn_${rowId}`)?.value?.trim();
        const qty = parseFloat(document.getElementById(`ppQty_${rowId}`)?.value) || 0;
        const purchasePrice = parseFloat(document.getElementById(`ppPurchasePrice_${rowId}`)?.value) || 0;
        const sellPrice = parseFloat(document.getElementById(`ppSellPrice_${rowId}`)?.value) || 0;
        const gst = parseFloat(document.getElementById(`ppGst_${rowId}`)?.value) || 0;
        
        if (name && qty > 0 && purchasePrice > 0) {
            const subtotal = qty * purchasePrice;
            const gstAmount = (subtotal * gst) / 100;
            
            items.push({
                name,
                category,
                subcategory,
                hsn,
                quantity: qty,
                purchase_price: purchasePrice,
                sell_price: sellPrice,
                gst_percentage: gst,
                gst_amount: gstAmount,
                total: subtotal + gstAmount
            });
        }
    });
    
    return items;
}

// Save purchase with multiple products
function savePurchaseFromModal() {
    const supplier = document.getElementById('purchaseSupplier')?.value?.trim();
    const supplierPhone = document.getElementById('purchaseSupplierPhone')?.value?.trim();
    const city = document.getElementById('purchaseCity')?.value?.trim();
    const invoiceNumber = document.getElementById('purchaseInvoiceNumber')?.value?.trim();
    const purchaseDate = document.getElementById('purchaseDate')?.value;
    const paymentMethod = document.getElementById('purchasePaymentMethod')?.value;
    const status = document.getElementById('purchaseStatus')?.value;
    const notes = document.getElementById('purchaseNotes')?.value?.trim();
    
    // Validation
    if (!supplier) {
        alert('Please enter supplier name');
        document.getElementById('purchaseSupplier')?.focus();
        return;
    }
    
    if (!city) {
        alert('Please enter city');
        document.getElementById('purchaseCity')?.focus();
        return;
    }
    
    if (!purchaseDate) {
        alert('Please select purchase date');
        document.getElementById('purchaseDate')?.focus();
        return;
    }
    
    // Collect product items
    const items = collectProductItems();
    
    if (items.length === 0) {
        alert('Please add at least one product with valid details');
        return;
    }
    
    // Calculate totals
    const totalItems = items.reduce((sum, item) => sum + item.quantity, 0);
    const totalSubtotal = items.reduce((sum, item) => sum + (item.quantity * item.purchase_price), 0);
    const totalGst = items.reduce((sum, item) => sum + item.gst_amount, 0);
    const grandTotal = totalSubtotal + totalGst;
    
    // Create purchase object
    const purchase = {
        supplier,
        supplier_phone: supplierPhone,
        city,
        invoice_number: invoiceNumber,
        purchase_date: purchaseDate,
        payment_method: paymentMethod,
        status,
        notes,
        items: JSON.stringify(items),
        total_items: totalItems,
        subtotal: totalSubtotal,
        total_gst: totalGst,
        total_amount: grandTotal
    };
    
    // Send to API
    fetch('api_purchases.php?action=create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(purchase)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.id) {
            showNotification('Purchase saved successfully!', 'success');
            closePurchaseModal();
            loadPurchases();
        } else {
            showNotification('Error saving purchase: ' + (data.error || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error saving purchase: ' + error.message, 'error');
    });
}

// Load purchases from API
function loadPurchases() {
    const tbody = document.getElementById('purchasesTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="14" style="text-align:center; padding:20px;"><i class="fas fa-spinner fa-spin"></i> Loading purchases...</td></tr>';
    
    fetch('api_purchases.php?action=list')
        .then(response => response.json())
        .then(purchases => {
            if (!purchases || purchases.length === 0) {
                tbody.innerHTML = '<tr><td colspan="14" style="text-align:center; padding:40px; color:#999;"><i class="fas fa-boxes" style="font-size:48px; margin-bottom:10px; opacity:0.3; display:block;"></i>No purchases found</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            purchases.forEach(purchase => {
                // Parse items if stored as JSON string
                let items = [];
                try {
                    items = typeof purchase.items === 'string' ? JSON.parse(purchase.items) : (purchase.items || []);
                } catch (e) {
                    items = [];
                }
                
                const itemCount = items.length || 1;
                const firstItem = items[0] || {};
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>#${purchase.invoice_number || purchase.id}</strong></td>
                    <td>${formatDate(purchase.purchase_date)}</td>
                    <td>
                        <div style="font-weight:600;">${purchase.supplier}</div>
                        ${purchase.supplier_phone ? `<div style="font-size:11px; color:#666;"><i class="fas fa-phone"></i> ${purchase.supplier_phone}</div>` : ''}
                    </td>
                    <td>${purchase.city || '-'}</td>
                    <td>
                        <span title="${items.map(i => i.name).join(', ')}">${firstItem.name || purchase.product_name || '-'}</span>
                        ${itemCount > 1 ? `<span style="background:#e3f2fd; color:#1976d2; padding:2px 6px; border-radius:10px; font-size:10px; margin-left:5px;">+${itemCount - 1}</span>` : ''}
                    </td>
                    <td><span style="background:#e3f2fd; color:#1976d2; padding:3px 8px; border-radius:4px; font-size:11px;">${firstItem.category || purchase.category || '-'}</span></td>
                    <td style="text-align:center;">${purchase.total_items || items.reduce((s, i) => s + (i.quantity || 0), 0) || purchase.quantity || 0}</td>
                    <td style="text-align:right;">₹${parseFloat(firstItem.purchase_price || purchase.cost_price || 0).toFixed(2)}</td>
                    <td style="text-align:right;">₹${parseFloat(firstItem.sell_price || purchase.selling_price || 0).toFixed(2)}</td>
                    <td style="text-align:center;">${firstItem.gst_percentage || purchase.gst_percentage || 0}%</td>
                    <td style="text-align:right; font-weight:700; color:#00bac7;">₹${parseFloat(purchase.total_amount || 0).toFixed(2)}</td>
                    <td><span style="font-size:11px;">${purchase.payment_method || 'Cash'}</span></td>
                    <td><span class="status-badge ${purchase.status}">${purchase.status}</span></td>
                    <td>
                        <div class="action-btns">
                            <button onclick="viewPurchase(${purchase.id})" class="action-btn view" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="printPurchaseInvoice(${purchase.id})" class="action-btn invoice" title="Print Invoice">
                                <i class="fas fa-print"></i>
                            </button>
                            <button onclick="deletePurchase(${purchase.id})" class="action-btn delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading purchases:', error);
            tbody.innerHTML = '<tr><td colspan="14" style="text-align:center; padding:20px; color:#f44336;">Error loading purchases</td></tr>';
        });
}

// View purchase details with all products
function viewPurchase(id) {
    fetch(`api_purchases.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(purchase => {
            // Parse items
            let items = [];
            try {
                items = typeof purchase.items === 'string' ? JSON.parse(purchase.items) : (purchase.items || []);
            } catch (e) {
                // If parsing fails, create single item from old format
                items = [{
                    name: purchase.product_name,
                    category: purchase.category,
                    subcategory: '',
                    hsn: purchase.hsn_code || '9004',
                    quantity: purchase.quantity,
                    purchase_price: purchase.cost_price,
                    sell_price: purchase.selling_price,
                    gst_percentage: purchase.gst_percentage,
                    gst_amount: purchase.gst_amount,
                    total: purchase.total_amount
                }];
            }
            
            // Build items table
            let itemsTable = '';
            if (items && items.length > 0) {
                itemsTable = `
                    <table style="width:100%; border-collapse:collapse; margin-top:10px;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="padding:10px 8px; text-align:left; border-bottom:2px solid #dee2e6; font-size:12px;">#</th>
                                <th style="padding:10px 8px; text-align:left; border-bottom:2px solid #dee2e6; font-size:12px;">Product</th>
                                <th style="padding:10px 8px; text-align:left; border-bottom:2px solid #dee2e6; font-size:12px;">Category</th>
                                <th style="padding:10px 8px; text-align:left; border-bottom:2px solid #dee2e6; font-size:12px;">Sub Category</th>
                                <th style="padding:10px 8px; text-align:center; border-bottom:2px solid #dee2e6; font-size:12px;">HSN</th>
                                <th style="padding:10px 8px; text-align:center; border-bottom:2px solid #dee2e6; font-size:12px;">Qty</th>
                                <th style="padding:10px 8px; text-align:right; border-bottom:2px solid #dee2e6; font-size:12px;">Purchase ₹</th>
                                <th style="padding:10px 8px; text-align:right; border-bottom:2px solid #dee2e6; font-size:12px;">Sell ₹</th>
                                <th style="padding:10px 8px; text-align:center; border-bottom:2px solid #dee2e6; font-size:12px;">GST %</th>
                                <th style="padding:10px 8px; text-align:right; border-bottom:2px solid #dee2e6; font-size:12px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map((item, index) => `
                                <tr>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee;">${index + 1}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; font-weight:600;">${item.name || '-'}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee;">${item.category || '-'}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee;">${item.subcategory || '-'}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; text-align:center;">${item.hsn || '9004'}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; text-align:center;">${item.quantity || 0}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; text-align:right;">₹${parseFloat(item.purchase_price || 0).toFixed(2)}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; text-align:right;">₹${parseFloat(item.sell_price || 0).toFixed(2)}</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; text-align:center;">${item.gst_percentage || 0}%</td>
                                    <td style="padding:10px 8px; border-bottom:1px solid #eee; text-align:right; font-weight:600; color:#00bac7;">₹${parseFloat(item.total || 0).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            }
            
            const content = `
                <div style="padding:20px;" id="purchaseDetailsContent">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; padding-bottom:15px; border-bottom:2px solid #00bac7;">
                        <div>
                            <h3 style="color:#00bac7; margin:0;">Purchase Details</h3>
                            <p style="margin:5px 0 0; color:#666; font-size:13px;">Invoice #${purchase.invoice_number || purchase.id}</p>
                        </div>
                        <button onclick="printPurchaseInvoice(${purchase.id})" style="background:#00bac7; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-print"></i> Print Invoice
                        </button>
                    </div>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div style="background:#f8f9fa; padding:15px; border-radius:8px;">
                            <h4 style="color:#333; margin:0 0 10px; font-size:14px;">Supplier Information</h4>
                            <p style="margin:5px 0;"><strong>Name:</strong> ${purchase.supplier || '-'}</p>
                            <p style="margin:5px 0;"><strong>Phone:</strong> ${purchase.supplier_phone || '-'}</p>
                            <p style="margin:5px 0;"><strong>City:</strong> ${purchase.city || '-'}</p>
                        </div>
                        <div style="background:#f8f9fa; padding:15px; border-radius:8px;">
                            <h4 style="color:#333; margin:0 0 10px; font-size:14px;">Purchase Information</h4>
                            <p style="margin:5px 0;"><strong>Date:</strong> ${formatDate(purchase.purchase_date)}</p>
                            <p style="margin:5px 0;"><strong>Payment:</strong> ${purchase.payment_method || 'Cash'}</p>
                            <p style="margin:5px 0;"><strong>Status:</strong> <span class="status-badge ${purchase.status}">${purchase.status}</span></p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom:20px;">
                        <h4 style="color:#333; margin:0 0 10px; font-size:14px;"><i class="fas fa-boxes"></i> Product Items (${items.length})</h4>
                        ${itemsTable}
                    </div>
                    
                    <div style="display:flex; justify-content:flex-end;">
                        <div style="background:#f8f9fa; padding:15px 25px; border-radius:8px; min-width:250px;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                <span style="color:#666;">Subtotal:</span>
                                <span style="font-weight:600;">₹${parseFloat(purchase.subtotal || 0).toFixed(2)}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                <span style="color:#666;">GST:</span>
                                <span style="font-weight:600; color:#ff6b6b;">₹${parseFloat(purchase.total_gst || purchase.gst_amount || 0).toFixed(2)}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; padding-top:10px; border-top:2px solid #00bac7;">
                                <span style="font-weight:700;">Grand Total:</span>
                                <span style="font-weight:700; font-size:18px; color:#00bac7;">₹${parseFloat(purchase.total_amount || 0).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${purchase.notes ? `
                        <div style="margin-top:20px; background:#fff3cd; padding:12px; border-radius:6px; border-left:4px solid #ffc107;">
                            <strong>Notes:</strong> ${purchase.notes}
                        </div>
                    ` : ''}
                </div>
            `;
            
            showModal('Purchase Details', content);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading purchase details');
        });
}

// Print purchase invoice
function printPurchaseInvoice(id) {
    fetch(`api_purchases.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(purchase => {
            // Parse items
            let items = [];
            try {
                items = typeof purchase.items === 'string' ? JSON.parse(purchase.items) : (purchase.items || []);
            } catch (e) {
                items = [{
                    name: purchase.product_name,
                    category: purchase.category,
                    subcategory: '',
                    hsn: purchase.hsn_code || '9004',
                    quantity: purchase.quantity,
                    purchase_price: purchase.cost_price,
                    sell_price: purchase.selling_price,
                    gst_percentage: purchase.gst_percentage,
                    gst_amount: purchase.gst_amount,
                    total: purchase.total_amount
                }];
            }
            
            const printWindow = window.open('', '_blank');
            if (!printWindow) {
                alert('Please allow popups to print the invoice');
                return;
            }
            
            const html = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Purchase Invoice - ${purchase.invoice_number || purchase.id}</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { font-family: 'Segoe UI', Arial, sans-serif; padding: 20px; color: #333; }
                        .invoice-header { text-align: center; border-bottom: 3px solid #00bac7; padding-bottom: 15px; margin-bottom: 20px; }
                        .invoice-header h1 { color: #00bac7; font-size: 28px; margin-bottom: 5px; }
                        .invoice-header p { color: #666; font-size: 12px; }
                        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
                        .info-box { width: 48%; }
                        .info-box h3 { font-size: 14px; color: #00bac7; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
                        .info-box p { font-size: 12px; margin: 5px 0; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th { background: #00bac7; color: white; padding: 10px 8px; text-align: left; font-size: 11px; }
                        td { padding: 10px 8px; border-bottom: 1px solid #eee; font-size: 12px; }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .totals { display: flex; justify-content: flex-end; }
                        .totals-box { width: 300px; }
                        .totals-box .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #ddd; }
                        .totals-box .total-row { border-top: 2px solid #00bac7; border-bottom: none; font-weight: bold; font-size: 16px; padding-top: 10px; }
                        .totals-box .total-row span:last-child { color: #00bac7; }
                        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
                        .status-badge { padding: 3px 10px; border-radius: 12px; font-size: 11px; }
                        .status-badge.received { background: #d4edda; color: #155724; }
                        .status-badge.pending { background: #fff3cd; color: #856404; }
                        .status-badge.cancelled { background: #f8d7da; color: #721c24; }
                        @media print {
                            body { padding: 10px; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-header">
                        <h1><i class="fas fa-glasses"></i> VisionKart</h1>
                        <p>Purchase Invoice</p>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-box">
                            <h3>Supplier Details</h3>
                            <p><strong>Name:</strong> ${purchase.supplier || '-'}</p>
                            <p><strong>Phone:</strong> ${purchase.supplier_phone || '-'}</p>
                            <p><strong>City:</strong> ${purchase.city || '-'}</p>
                        </div>
                        <div class="info-box" style="text-align:right;">
                            <h3>Invoice Details</h3>
                            <p><strong>Invoice #:</strong> ${purchase.invoice_number || purchase.id}</p>
                            <p><strong>Date:</strong> ${formatDate(purchase.purchase_date)}</p>
                            <p><strong>Payment:</strong> ${purchase.payment_method || 'Cash'}</p>
                            <p><strong>Status:</strong> <span class="status-badge ${purchase.status}">${purchase.status}</span></p>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th class="text-center">HSN</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Purchase ₹</th>
                                <th class="text-right">Sell ₹</th>
                                <th class="text-center">GST %</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map((item, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td><strong>${item.name || '-'}</strong></td>
                                    <td>${item.category || '-'}</td>
                                    <td>${item.subcategory || '-'}</td>
                                    <td class="text-center">${item.hsn || '9004'}</td>
                                    <td class="text-center">${item.quantity || 0}</td>
                                    <td class="text-right">₹${parseFloat(item.purchase_price || 0).toFixed(2)}</td>
                                    <td class="text-right">₹${parseFloat(item.sell_price || 0).toFixed(2)}</td>
                                    <td class="text-center">${item.gst_percentage || 0}%</td>
                                    <td class="text-right"><strong>₹${parseFloat(item.total || 0).toFixed(2)}</strong></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    
                    <div class="totals">
                        <div class="totals-box">
                            <div class="row">
                                <span>Subtotal:</span>
                                <span>₹${parseFloat(purchase.subtotal || 0).toFixed(2)}</span>
                            </div>
                            <div class="row">
                                <span>GST Amount:</span>
                                <span>₹${parseFloat(purchase.total_gst || purchase.gst_amount || 0).toFixed(2)}</span>
                            </div>
                            <div class="row total-row">
                                <span>Grand Total:</span>
                                <span>₹${parseFloat(purchase.total_amount || 0).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${purchase.notes ? `<div style="margin-top:20px; background:#fff3cd; padding:10px; border-radius:4px; font-size:12px;"><strong>Notes:</strong> ${purchase.notes}</div>` : ''}
                    
                    <div class="footer">
                        <p>Printed on: ${new Date().toLocaleString()}</p>
                        <p>VisionKart - Your Vision, Our Priority</p>
                    </div>
                    
                    <div class="no-print" style="margin-top:20px; text-align:center;">
                        <button onclick="window.print()" style="background:#00bac7; color:white; border:none; padding:12px 30px; border-radius:6px; cursor:pointer; font-size:14px;">
                            <i class="fas fa-print"></i> Print Invoice
                        </button>
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(html);
            printWindow.document.close();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading purchase for printing');
        });
}

// Delete purchase
function deletePurchase(id) {
    if (!confirm('Are you sure you want to delete this purchase?')) return;
    
    fetch(`api_purchases.php?id=${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Purchase deleted successfully', 'success');
            loadPurchases();
        } else {
            showNotification('Error deleting purchase: ' + (data.error || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error deleting purchase', 'error');
    });
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-IN', options);
}

// Helper function to show modal
function showModal(title, content) {
    // Remove existing modal if any
    const existingModal = document.querySelector('.custom-view-modal');
    if (existingModal) existingModal.remove();
    
    const modal = document.createElement('div');
    modal.className = 'custom-view-modal';
    modal.style.cssText = 'position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:10010; padding:20px;';
    modal.innerHTML = `
        <div style="background:white; max-width:1000px; width:100%; max-height:90vh; overflow-y:auto; border-radius:10px; position:relative; box-shadow:0 4px 20px rgba(0,0,0,0.2);">
            <button onclick="this.closest('.custom-view-modal').remove()" style="position:sticky; top:10px; right:10px; float:right; margin:10px; border:none; background:#f0f0f0; width:35px; height:35px; border-radius:50%; font-size:16px; cursor:pointer; z-index:1;">
                <i class="fas fa-times"></i>
            </button>
            ${content}
        </div>
    `;
    document.body.appendChild(modal);
    
    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
    
    // Close on escape key
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            modal.remove();
            document.removeEventListener('keydown', escHandler);
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('purchaseDate');
    if (dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
});
