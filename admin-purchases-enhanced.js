// Purchase Management Functions - Enhanced Version
// Add these functions to admin-script.js

// Calculate total amount with GST
function calculatePurchaseTotal() {
    const quantity = parseFloat(document.getElementById('purchaseQuantity')?.value) || 0;
    const costPrice = parseFloat(document.getElementById('purchaseCostPrice')?.value) || 0;
    const gstPercentage = parseFloat(document.getElementById('purchaseGst')?.value) || 0;
    
    const subtotal = quantity * costPrice;
    const gstAmount = (subtotal * gstPercentage) / 100;
    const totalAmount = subtotal + gstAmount;
    
    // Update display elements
    if (document.getElementById('purchaseSubtotal')) {
        document.getElementById('purchaseSubtotal').textContent = `₹${subtotal.toFixed(2)}`;
    }
    if (document.getElementById('purchaseGstAmount')) {
        document.getElementById('purchaseGstAmount').textContent = `₹${gstAmount.toFixed(2)}`;
    }
    if (document.getElementById('purchaseTotalAmount')) {
        document.getElementById('purchaseTotalAmount').textContent = `₹${totalAmount.toFixed(2)}`;
    }
    
    return { subtotal, gstAmount, totalAmount };
}

// Enhanced save purchase function
function savePurchaseFromModal() {
    // Get all form values
    const supplier = document.getElementById('purchaseSupplier')?.value.trim();
    const supplierPhone = document.getElementById('purchaseSupplierPhone')?.value.trim();
    const supplierEmail = document.getElementById('purchaseSupplierEmail')?.value.trim();
    const city = document.getElementById('purchaseCity')?.value.trim();
    const productName = document.getElementById('purchaseProductName')?.value.trim();
    const category = document.getElementById('purchaseCategory')?.value;
    const quantity = parseFloat(document.getElementById('purchaseQuantity')?.value) || 0;
    const costPrice = parseFloat(document.getElementById('purchaseCostPrice')?.value) || 0;
    const sellingPrice = parseFloat(document.getElementById('purchaseSellingPrice')?.value) || 0;
    const gstPercentage = parseFloat(document.getElementById('purchaseGst')?.value) || 0;
    const paymentMethod = document.getElementById('purchasePaymentMethod')?.value;
    const invoiceNumber = document.getElementById('purchaseInvoiceNumber')?.value.trim();
    const purchaseDate = document.getElementById('purchaseDate')?.value;
    const status = document.getElementById('purchaseStatus')?.value;
    const notes = document.getElementById('purchaseNotes')?.value.trim();
    
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
    
    if (!productName) {
        alert('Please enter product name');
        document.getElementById('purchaseProductName')?.focus();
        return;
    }
    
    if (!category) {
        alert('Please select a category');
        document.getElementById('purchaseCategory')?.focus();
        return;
    }
    
    if (quantity <= 0) {
        alert('Please enter a valid quantity');
        document.getElementById('purchaseQuantity')?.focus();
        return;
    }
    
    if (costPrice <= 0) {
        alert('Please enter a valid purchase price');
        document.getElementById('purchaseCostPrice')?.focus();
        return;
    }
    
    if (sellingPrice <= 0) {
        alert('Please enter a valid sell price');
        document.getElementById('purchaseSellingPrice')?.focus();
        return;
    }
    
    if (!purchaseDate) {
        alert('Please select purchase date');
        document.getElementById('purchaseDate')?.focus();
        return;
    }
    
    // Calculate amounts
    const subtotal = quantity * costPrice;
    const gstAmount = (subtotal * gstPercentage) / 100;
    const totalAmount = subtotal + gstAmount;
    
    // Create purchase object
    const purchase = {
        supplier: supplier,
        supplier_phone: supplierPhone,
        supplier_email: supplierEmail,
        city: city,
        product_name: productName,
        category: category,
        quantity: quantity,
        cost_price: costPrice,
        selling_price: sellingPrice,
        gst_percentage: gstPercentage,
        gst_amount: gstAmount,
        total_amount: totalAmount,
        payment_method: paymentMethod,
        invoice_number: invoiceNumber,
        purchase_date: purchaseDate,
        status: status,
        notes: notes
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

// Enhanced load purchases function
function loadPurchases() {
    const tbody = document.getElementById('purchasesTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="14" style="text-align:center; padding:20px;"><i class="fas fa-spinner fa-spin"></i> Loading purchases...</td></tr>';
    
    fetch('api_purchases.php?action=list')
        .then(response => response.json())
        .then(purchases => {
            if (!purchases || purchases.length === 0) {
                tbody.innerHTML = '<tr><td colspan="14" style="text-align:center; padding:20px; color:#999;">No purchases found</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            purchases.forEach(purchase => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${purchase.invoice_number || '-'}</td>
                    <td>${formatDate(purchase.purchase_date)}</td>
                    <td>
                        <div style="font-weight:600;">${purchase.supplier}</div>
                        ${purchase.supplier_phone ? `<div style="font-size:11px; color:#666;"><i class="fas fa-phone"></i> ${purchase.supplier_phone}</div>` : ''}
                    </td>
                    <td>${purchase.city || '-'}</td>
                    <td>${purchase.product_name}</td>
                    <td><span style="background:#e3f2fd; color:#1976d2; padding:3px 8px; border-radius:4px; font-size:11px;">${purchase.category || '-'}</span></td>
                    <td style="text-align:center;">${purchase.quantity}</td>
                    <td style="text-align:right;">₹${parseFloat(purchase.cost_price || 0).toFixed(2)}</td>
                    <td style="text-align:right;">₹${parseFloat(purchase.selling_price || 0).toFixed(2)}</td>
                    <td style="text-align:center;">${purchase.gst_percentage}%</td>
                    <td style="text-align:right; font-weight:700; color:#00bac7;">₹${parseFloat(purchase.total_amount || 0).toFixed(2)}</td>
                    <td><span style="font-size:11px;">${purchase.payment_method || 'Cash'}</span></td>
                    <td><span class="status-badge ${purchase.status}">${purchase.status}</span></td>
                    <td>
                        <button onclick="viewPurchase(${purchase.id})" class="btn-icon" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="deletePurchase(${purchase.id})" class="btn-icon" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
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

// View purchase details
function viewPurchase(id) {
    fetch(`api_purchases.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(purchase => {
            const details = `
                <div style="padding:20px;">
                    <h3 style="color:#00bac7; margin-top:0;">Purchase Details</h3>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px;">
                        <div>
                            <h4 style="color:#333; border-bottom:2px solid #00bac7; padding-bottom:8px;">Supplier Information</h4>
                            <p><strong>Name:</strong> ${purchase.supplier}</p>
                            ${purchase.supplier_phone ? `<p><strong>Phone:</strong> ${purchase.supplier_phone}</p>` : ''}
                            ${purchase.supplier_email ? `<p><strong>Email:</strong> ${purchase.supplier_email}</p>` : ''}
                            <p><strong>City:</strong> ${purchase.city || '-'}</p>
                        </div>
                        
                        <div>
                            <h4 style="color:#333; border-bottom:2px solid #00bac7; padding-bottom:8px;">Purchase Information</h4>
                            <p><strong>Invoice #:</strong> ${purchase.invoice_number || '-'}</p>
                            <p><strong>Date:</strong> ${formatDate(purchase.purchase_date)}</p>
                            <p><strong>Payment Method:</strong> ${purchase.payment_method || 'Cash'}</p>
                            <p><strong>Status:</strong> <span class="status-badge ${purchase.status}">${purchase.status}</span></p>
                        </div>
                    </div>
                    
                    <div style="margin-top:20px;">
                        <h4 style="color:#333; border-bottom:2px solid #00bac7; padding-bottom:8px;">Product Details</h4>
                        <p><strong>Product:</strong> ${purchase.product_name}</p>
                        <p><strong>Category:</strong> ${purchase.category || '-'}</p>
                        <p><strong>Quantity:</strong> ${purchase.quantity}</p>
                    </div>
                    
                    <div style="margin-top:20px; background:#f8f9fa; padding:15px; border-radius:8px;">
                        <h4 style="color:#333; margin-top:0;">Pricing Details</h4>
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="padding:8px 0;"><strong>Purchase Price (per unit):</strong></td>
                                <td style="text-align:right;">₹${parseFloat(purchase.cost_price).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;"><strong>Sell Price (per unit):</strong></td>
                                <td style="text-align:right;">₹${parseFloat(purchase.selling_price).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;"><strong>Subtotal:</strong></td>
                                <td style="text-align:right;">₹${(purchase.quantity * purchase.cost_price).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;"><strong>GST (${purchase.gst_percentage}%):</strong></td>
                                <td style="text-align:right; color:#ff6b6b;">₹${parseFloat(purchase.gst_amount).toFixed(2)}</td>
                            </tr>
                            <tr style="border-top:2px solid #dee2e6;">
                                <td style="padding:12px 0;"><strong style="font-size:16px;">Total Amount:</strong></td>
                                <td style="text-align:right; font-size:18px; font-weight:700; color:#00bac7;">₹${parseFloat(purchase.total_amount).toFixed(2)}</td>
                            </tr>
                        </table>
                    </div>
                    
                    ${purchase.notes ? `
                    <div style="margin-top:20px;">
                        <h4 style="color:#333; border-bottom:2px solid #00bac7; padding-bottom:8px;">Notes</h4>
                        <p style="background:#fff3cd; padding:12px; border-radius:6px; border-left:4px solid #ffc107;">${purchase.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
            
            showModal('Purchase Details', details);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading purchase details');
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
            showNotification('Error deleting purchase', 'error');
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
    return date.toLocaleDateString('en-US', options);
}

// Helper function to show modal
function showModal(title, content) {
    const modal = document.createElement('div');
    modal.style.cssText = 'position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:10000;';
    modal.innerHTML = `
        <div style="background:white; max-width:800px; width:90%; max-height:90vh; overflow-y:auto; border-radius:8px; position:relative;">
            <button onclick="this.closest('div[style*=fixed]').remove()" style="position:absolute; right:16px; top:16px; border:none; background:transparent; font-size:20px; cursor:pointer; z-index:1;">
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
}

// Set today's date as default
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('purchaseDate');
    if (dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
});
