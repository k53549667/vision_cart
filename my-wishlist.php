<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - VisionKart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .wishlist-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .wishlist-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .wishlist-header h1 {
            color: #00bac7;
            margin-bottom: 10px;
            font-size: 36px;
        }

        .wishlist-header p {
            color: #666;
            font-size: 16px;
        }

        .wishlist-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .wishlist-count-badge {
            background: #00bac7;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .wishlist-product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
        }

        .wishlist-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,186,199,0.2);
        }

        .wishlist-product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            cursor: pointer;
        }

        .wishlist-product-content {
            padding: 20px;
        }

        .wishlist-product-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 40px;
        }

        .wishlist-product-brand {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }

        .wishlist-product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 22px;
            font-weight: bold;
            color: #00bac7;
        }

        .original-price {
            font-size: 15px;
            color: #999;
            text-decoration: line-through;
        }

        .discount-badge {
            background: #ff6b6b;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .stock-status {
            font-size: 13px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stock-status.in-stock {
            color: #4caf50;
        }

        .stock-status.out-of-stock {
            color: #f44336;
        }

        .wishlist-product-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 186, 199, 0.3);
        }

        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .btn-danger {
            background: #f44336;
            color: white;
            flex: 0 0 auto;
            padding: 12px 15px;
        }

        .btn-danger:hover {
            background: #d32f2f;
        }

        .remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            color: #f44336;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: all 0.3s;
        }

        .remove-btn:hover {
            background: #f44336;
            color: white;
            transform: scale(1.1);
        }

        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
        }

        .empty-wishlist i {
            font-size: 80px;
            color: #e0e0e0;
            margin-bottom: 20px;
        }

        .empty-wishlist h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .empty-wishlist p {
            color: #666;
            margin-bottom: 30px;
        }

        .clear-all-btn {
            background: transparent;
            border: 2px solid #f44336;
            color: #f44336;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .clear-all-btn:hover {
            background: #f44336;
            color: white;
        }

        .loading {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .loading i {
            font-size: 50px;
            color: #00bac7;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Top Banner -->
    <div class="top-banner">
        <p>🎉 BUY 1 GET 1 FREE on all eyewear | Free Home Eye Test Available</p>
    </div>

    <!-- Header & Navigation -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1 onclick="window.location.href='index.php'" style="cursor: pointer;">VisionKart</h1>
                </div>
                
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="index.php#eyeglasses">EYEGLASSES</a></li>
                        <li><a href="index.php#sunglasses">SUNGLASSES</a></li>
                        <li><a href="dashboard.php">DASHBOARD</a></li>
                    </ul>
                </nav>

                <div class="header-actions">
                    <div class="action-icons">
                        <a href="dashboard.php" class="icon-link"><i class="fas fa-user"></i></a>
                        <a href="my-wishlist.php" class="icon-link wishlist-icon">
                            <i class="fas fa-heart"></i>
                            <span class="wishlist-count">0</span>
                        </a>
                        <a href="#" class="icon-link cart-icon" id="cartIconLink">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">0</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Wishlist Page Content -->
    <div class="wishlist-page">
        <div class="wishlist-header">
            <h1><i class="fas fa-heart"></i> My Wishlist</h1>
            <p>Products you've saved for later</p>
        </div>

        <div class="wishlist-actions">
            <div class="wishlist-count-badge">
                <i class="fas fa-heart"></i> <span id="wishlistCountText">0</span> items
            </div>
            <button class="clear-all-btn" id="clearAllBtn" style="display: none;" onclick="clearAllWishlist()">
                <i class="fas fa-trash-alt"></i> Clear All
            </button>
        </div>

        <div id="wishlistContainer">
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <p>Loading your wishlist...</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>VisionKart</h3>
                    <p>Your trusted eyewear destination</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="my-wishlist.php">Wishlist</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 VisionKart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="auth.js"></script>
    <script>
        const API_BASE_URL = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
        let wishlist = [];

        // Load wishlist on page load
        async function loadWishlist() {
            try {
                const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=list`, {
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success && data.items) {
                    wishlist = data.items;
                    displayWishlist();
                    updateCount();
                } else {
                    displayEmptyWishlist();
                }
            } catch (error) {
                console.error('Error loading wishlist:', error);
                displayError();
            }
        }

        function displayWishlist() {
            const container = document.getElementById('wishlistContainer');
            const clearBtn = document.getElementById('clearAllBtn');

            if (wishlist.length === 0) {
                displayEmptyWishlist();
                clearBtn.style.display = 'none';
                return;
            }

            clearBtn.style.display = 'block';

            container.innerHTML = `
                <div class="wishlist-grid">
                    ${wishlist.map(item => `
                        <div class="wishlist-product-card">
                            <button class="remove-btn" onclick="removeItem(${item.id})" title="Remove from wishlist">
                                <i class="fas fa-times"></i>
                            </button>
                            <img src="${item.image || 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop'}" 
                                 alt="${item.name}" 
                                 class="wishlist-product-image"
                                 onclick="window.location.href='product-detail.php?id=${item.id}'">
                            <div class="wishlist-product-content">
                                <div class="wishlist-product-name" onclick="window.location.href='product-detail.php?id=${item.id}'" style="cursor: pointer;">
                                    ${item.name}
                                </div>
                                ${item.brand ? `<div class="wishlist-product-brand">${item.brand}</div>` : ''}
                                <div class="wishlist-product-price">
                                    <span class="current-price">₹${item.price.toLocaleString()}</span>
                                    ${item.original_price && item.original_price > item.price ? 
                                        `<span class="original-price">₹${item.original_price.toLocaleString()}</span>
                                         <span class="discount-badge">${item.discount}% OFF</span>` : ''}
                                </div>
                                <div class="stock-status ${item.in_stock ? 'in-stock' : 'out-of-stock'}">
                                    <i class="fas ${item.in_stock ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                                    ${item.in_stock ? 'In Stock' : 'Out of Stock'}
                                </div>
                                <div class="wishlist-product-actions">
                                    <button class="btn btn-primary" onclick="addToCart(${item.id})" ${!item.in_stock ? 'disabled' : ''}>
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function displayEmptyWishlist() {
            document.getElementById('wishlistContainer').innerHTML = `
                <div class="empty-wishlist">
                    <i class="fas fa-heart"></i>
                    <h2>Your Wishlist is Empty</h2>
                    <p>Save products you love to your wishlist and shop them anytime!</p>
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Browse Products
                    </a>
                </div>
            `;
            document.getElementById('clearAllBtn').style.display = 'none';
        }

        function displayError() {
            document.getElementById('wishlistContainer').innerHTML = `
                <div class="empty-wishlist">
                    <i class="fas fa-exclamation-circle" style="color: #f44336;"></i>
                    <h2>Error Loading Wishlist</h2>
                    <p>Sorry, we couldn't load your wishlist. Please try again.</p>
                    <button class="btn btn-primary" onclick="loadWishlist()">
                        <i class="fas fa-sync-alt"></i> Retry
                    </button>
                </div>
            `;
        }

        function updateCount() {
            document.getElementById('wishlistCountText').textContent = wishlist.length;
            const countBadges = document.querySelectorAll('.wishlist-count');
            countBadges.forEach(badge => badge.textContent = wishlist.length);
        }

        async function removeItem(productId) {
            if (!confirm('Remove this item from your wishlist?')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=remove&product_id=${productId}`, {
                    method: 'DELETE',
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    await loadWishlist();
                    showNotification('Item removed from wishlist', 'success');
                } else {
                    showNotification('Failed to remove item', 'error');
                }
            } catch (error) {
                console.error('Error removing item:', error);
                showNotification('An error occurred', 'error');
            }
        }

        async function clearAllWishlist() {
            if (!confirm('Are you sure you want to clear your entire wishlist?')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=clear`, {
                    method: 'DELETE',
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    await loadWishlist();
                    showNotification('Wishlist cleared', 'success');
                } else {
                    showNotification('Failed to clear wishlist', 'error');
                }
            } catch (error) {
                console.error('Error clearing wishlist:', error);
                showNotification('An error occurred', 'error');
            }
        }

        async function addToCart(productId) {
            try {
                const response = await fetch(`${API_BASE_URL}/api_cart.php?action=add`, {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
                });
                const data = await response.json();

                if (data.success) {
                    showNotification(`${data.product_name || 'Product'} added to cart!`, 'success');
                    // Update cart count if element exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.count) {
                        cartCount.textContent = data.count;
                    }
                } else {
                    showNotification(data.error || 'Failed to add to cart', 'error');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('An error occurred', 'error');
            }
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: ${type === 'success' ? '#4caf50' : '#f44336'};
                color: white;
                padding: 15px 25px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> ${message}`;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Load wishlist on page load
        window.addEventListener('DOMContentLoaded', loadWishlist);
    </script>
</body>
</html>
