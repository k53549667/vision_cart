// Product data - Fallback hardcoded products (used if API fails)
const fallbackProducts = [
    {
        id: 1,
        name: "Classic Aviator",
        type: "Sunglasses",
        price: 1499,
        originalPrice: 2999,
        discount: "50% OFF",
        rating: 4.5,
        reviews: 234,
        badge: "BESTSELLER",
        image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop"
    },
    {
        id: 2,
        name: "Vincent Chase Rimmed",
        type: "Eyeglasses",
        price: 999,
        originalPrice: 1999,
        discount: "50% OFF",
        rating: 4.3,
        reviews: 189,
        badge: "NEW",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop"
    },
    {
        id: 3,
        name: "John Jacobs Full Rim",
        type: "Eyeglasses",
        price: 1799,
        originalPrice: 3599,
        discount: "50% OFF",
        rating: 4.7,
        reviews: 456,
        badge: "TRENDING",
        image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop"
    },
    {
        id: 4,
        name: "Cat-Eye Premium",
        type: "Sunglasses",
        price: 1299,
        originalPrice: 2599,
        discount: "50% OFF",
        rating: 4.4,
        reviews: 312,
        badge: "HOT",
        image: "https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?w=400&h=400&fit=crop"
    },
    {
        id: 5,
        name: "Round Frame Classic",
        type: "Eyeglasses",
        price: 899,
        originalPrice: 1799,
        discount: "50% OFF",
        rating: 4.2,
        reviews: 167,
        badge: "SALE",
        image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop"
    },
    {
        id: 6,
        name: "Sport Wrap Around",
        type: "Sunglasses",
        price: 1699,
        originalPrice: 3399,
        discount: "50% OFF",
        rating: 4.6,
        reviews: 289,
        badge: "BESTSELLER",
        image: "https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop"
    },
    {
        id: 7,
        name: "Blue Light Blockers",
        type: "Computer Glasses",
        price: 1199,
        originalPrice: 2399,
        discount: "50% OFF",
        rating: 4.5,
        reviews: 401,
        badge: "POPULAR",
        image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop"
    },
    {
        id: 8,
        name: "Wayfarer Classic",
        type: "Sunglasses",
        price: 1399,
        originalPrice: 2799,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 523,
        badge: "BESTSELLER",
        image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop"
    }
];

// Global products array - populated from database
let products = [];

// Shopping cart (populated from database)
let cart = [];

// Wishlist
let wishlist = [];

// API Base URL
const API_BASE_URL = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));

// Load cart from database API
async function loadCart() {
    try {
        const response = await fetch(`${API_BASE_URL}/api_cart.php?action=list`, {
            credentials: 'include'
        });
        const data = await response.json();
        
        if (data.success) {
            cart = data.items || [];
            updateCartCount();
            console.log('✅ Cart loaded from database:', cart.length, 'items');
        }
    } catch (error) {
        console.error('❌ Error loading cart:', error);
        // Fallback to localStorage for backward compatibility
        const savedCart = localStorage.getItem('visionkart_cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            // Migrate to database
            migrateCartToDatabase();
        }
    }
}

// Migrate localStorage cart to database
async function migrateCartToDatabase() {
    console.log('🔄 Migrating cart from localStorage to database...');
    for (const item of cart) {
        try {
            await fetch(`${API_BASE_URL}/api_cart.php?action=add`, {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: item.id,
                    quantity: item.quantity || 1
                })
            });
        } catch (error) {
            console.error('Error migrating item:', error);
        }
    }
    localStorage.removeItem('visionkart_cart');
    console.log('✅ Cart migration complete');
}

// Save cart to database (deprecated - using API directly now)
function saveCart() {
    // No longer needed - API handles persistence
    // Kept for backward compatibility
    console.log('Cart is automatically saved to database');
}

// Load wishlist from database API
async function loadWishlist() {
    try {
        const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=list`, {
            credentials: 'include'
        });
        const data = await response.json();
        
        if (data.success) {
            wishlist = data.items || [];
            updateWishlistCount();
            console.log('✅ Wishlist loaded from database:', wishlist.length, 'items');
        }
    } catch (error) {
        console.error('❌ Error loading wishlist:', error);
        // Fallback to localStorage for backward compatibility
        const savedWishlist = localStorage.getItem('visionkart_wishlist');
        if (savedWishlist) {
            wishlist = JSON.parse(savedWishlist);
            // Migrate to database
            migrateWishlistToDatabase();
        }
        updateWishlistCount();
    }
}

// Migrate localStorage wishlist to database
async function migrateWishlistToDatabase() {
    console.log('🔄 Migrating wishlist from localStorage to database...');
    for (const item of wishlist) {
        try {
            await fetch(`${API_BASE_URL}/api_wishlist.php?action=add`, {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: item.id })
            });
        } catch (error) {
            console.error('Error migrating wishlist item:', error);
        }
    }
    localStorage.removeItem('visionkart_wishlist');
    console.log('✅ Wishlist migration complete');
}

// Save wishlist (deprecated - using API directly now)
function saveWishlist() {
    // No longer needed - API handles persistence
    console.log('Wishlist is automatically saved to database');
}

// Update wishlist count in header
async function updateWishlistCount() {
    const wishlistCountElement = document.querySelector('.wishlist-count');
    if (wishlistCountElement) {
        wishlistCountElement.textContent = wishlist.length;
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', async function() {
    await loadCart();
    await loadWishlist();
    await loadProducts();
    await loadCategorySections(); // Load products for other sections
    setupEventListeners();
    updateCartCount();
    initializeVideo();
    setupLoginModal();
    setupRegistrationModal();
    setupWishlistSidebar();
    
    // Update wishlist UI after products are loaded
    setTimeout(() => {
        updateWishlistUI();
    }, 500);
});

// Initialize video player
function initializeVideo() {
    const videoWrapper = document.querySelector('.video-wrapper');
    const video = document.getElementById('mainVideo');
    const playBtn = document.getElementById('playBtn');
    const videoOverlay = document.querySelector('.video-overlay');
    
    // Check if video elements exist (only on homepage)
    if (!video || !playBtn || !videoOverlay) {
        return;
    }
    
    // Play button click handler
    playBtn.addEventListener('click', function() {
        if (video.paused) {
            video.play();
            videoWrapper.classList.add('playing');
        } else {
            video.pause();
            videoWrapper.classList.remove('playing');
        }
    });
    
    // Video ended handler - show overlay again
    video.addEventListener('ended', function() {
        videoWrapper.classList.remove('playing');
    });
    
    // Video pause handler
    video.addEventListener('pause', function() {
        if (video.currentTime < video.duration) {
            videoWrapper.classList.remove('playing');
        }
    });
    
    // Video play handler
    video.addEventListener('play', function() {
        videoWrapper.classList.add('playing');
    });
}


// Transform database product to frontend format
function transformProduct(dbProduct) {
    // Calculate discount percentage and original price
    const discountPercent = Math.floor(Math.random() * 30) + 20; // 20-50% discount
    const originalPrice = Math.floor(dbProduct.price * (100 / (100 - discountPercent)));
    
    // Generate random ratings (4.0-5.0)
    const rating = (Math.random() * 1 + 4).toFixed(1);
    const reviews = Math.floor(Math.random() * 500) + 50;
    
    // Determine badge based on stock and price
    let badge = 'NEW';
    if (dbProduct.stock > 50) badge = 'BESTSELLER';
    else if (dbProduct.stock < 20) badge = 'LIMITED';
    else if (dbProduct.price > 1500) badge = 'PREMIUM';
    else if (discountPercent > 40) badge = 'SALE';
    
    return {
        id: dbProduct.id,
        name: dbProduct.name,
        type: dbProduct.category || 'Eyeglasses',
        price: parseFloat(dbProduct.price),
        originalPrice: originalPrice,
        discount: `${discountPercent}% OFF`,
        rating: parseFloat(rating),
        reviews: reviews,
        badge: badge,
        image: getFirstImage(dbProduct.image) || 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop',
        images: dbProduct.image ? dbProduct.image.split(',').map(img => img.trim()).filter(img => img) : [],
        brand: dbProduct.brand || 'VisionKart',
        stock: dbProduct.stock || 0,
        status: dbProduct.status || 'active',
        category: dbProduct.category,
        subcategory: dbProduct.subcategory
    };
}

// Helper function to get first image from comma-separated list
function getFirstImage(imageStr) {
    if (!imageStr) return null;
    const images = imageStr.split(',').map(img => img.trim()).filter(img => img);
    return images.length > 0 ? images[0] : null;
}

// Fetch products from database API
async function fetchProductsFromAPI() {
    try {
        console.log('🌐 Fetching products from database...');
        const response = await fetch('api_products.php?action=list&status=active');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('✅ Received products from API:', data.length);
        
        // Transform database products to frontend format
        const transformedProducts = data.map(transformProduct);
        return transformedProducts;
        
    } catch (error) {
        console.error('❌ Error fetching products from API:', error);
        console.log('⚠️ Falling back to hardcoded products');
        return fallbackProducts;
    }
}

// Load category sections with filtered products
async function loadCategorySections() {
    try {
        const products = await fetchProductsFromAPI();
        
        // Load Sunglasses section
        const sunglassesGrid = document.getElementById('sunglassesGrid');
        if (sunglassesGrid) {
            const sunglasses = products.filter(p => p.category && p.category.toLowerCase().includes('sunglass'));
            if (sunglasses.length > 0) {
                sunglassesGrid.innerHTML = ''; // Clear loading message
                sunglasses.forEach(product => {
                    const card = createProductCard(product);
                    sunglassesGrid.appendChild(card);
                });
            } else {
                sunglassesGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">No sunglasses available at the moment.</p>';
            }
        }
        
        // Load Kids section
        const kidsGrid = document.getElementById('kidsGrid');
        if (kidsGrid) {
            const kidsProducts = products.filter(p => p.category && p.category.toLowerCase().includes('kid'));
            if (kidsProducts.length > 0) {
                kidsGrid.innerHTML = ''; // Clear loading message
                kidsProducts.forEach(product => {
                    const card = createProductCard(product);
                    kidsGrid.appendChild(card);
                });
            } else {
                kidsGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">No kids glasses available at the moment.</p>';
            }
        }
        
        console.log('✅ Category sections loaded successfully');
    } catch (error) {
        console.error('❌ Error loading category sections:', error);
    }
}

// Load products into the grid
async function loadProducts() {
    const productGrid = document.getElementById('productGrid');
    
    // Check if productGrid exists (only load on pages that have it)
    if (!productGrid) {
        console.log('No productGrid element found on this page');
        return;
    }
    
    // Show loading indicator
    productGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #00bac7;"><i class="fas fa-spinner fa-spin"></i> Loading products...</p>';
    
    try {
        // Fetch products from API
        products = await fetchProductsFromAPI();
        
        console.log('🔍 Loading products...');
        console.log('📦 Products from database:', products.length);
        
        // Clear existing products
        productGrid.innerHTML = '';
        
        if (products.length === 0) {
            productGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #999;">No products available</p>';
            return;
        }
        
        products.forEach((product, index) => {
            const productCard = createProductCard(product);
            productGrid.appendChild(productCard);
        });
        
        console.log('✨ Products loaded successfully from database!');
        
    } catch (error) {
        console.error('❌ Error loading products:', error);
        productGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #f44336;">Failed to load products. Please refresh the page.</p>';
    }
}

// Create product card HTML
function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.setAttribute('data-product-id', product.id);
    card.style.cursor = 'pointer';
    
    // Ensure all required fields have defaults
    const productData = {
        id: product.id,
        name: product.name || 'Unnamed Product',
        type: product.type || product.category || 'Eyewear',
        price: product.price || 0,
        originalPrice: product.originalPrice || product.price * 1.5,
        discount: product.discount || '25% OFF',
        rating: product.rating || 4.0,
        reviews: product.reviews || 0,
        badge: product.badge || 'NEW',
        image: product.image || 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop'
    };
    
    card.innerHTML = `
        <a href="product-detail.php?id=${productData.id}" class="product-link">
            <div class="product-image">
                <img src="${productData.image}" alt="${productData.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="eyeglass-img" style="display: none;">
                    <div class="eyeglass">
                        <div class="lens"></div>
                        <div class="bridge"></div>
                        <div class="lens"></div>
                        <div class="temple left"></div>
                        <div class="temple right"></div>
                    </div>
                </div>
                <span class="product-badge">${productData.badge}</span>
            </div>
            <div class="product-info">
                <h3 class="product-name">${productData.name}</h3>
                <p class="product-type">${productData.type}</p>
                <div class="product-rating">
                    <span class="stars">${generateStars(productData.rating)}</span>
                    <span class="rating-count">${productData.rating} <i class="fas fa-star" style="font-size: 10px; margin: 0 2px;"></i> (${productData.reviews})</span>
                </div>
                <div class="product-price">
                    <span class="current-price"><small style="font-size: 10px; color: #666; font-weight: normal;">Sell Price:</small> ₹${productData.price}</span>
                    <span class="original-price"><small style="font-size: 9px; color: #999; font-weight: normal;">Purchase Price:</small> ₹${productData.originalPrice}</span>
                    <span class="discount">${productData.discount}</span>
                </div>
                <p style="font-size: 12px; color: var(--dark-gray); margin-bottom: 12px;">Buy 1 Get 1 Free with Gold Membership</p>
            </div>
        </a>
        <label class="compare-checkbox" onclick="event.preventDefault(); event.stopPropagation();">
            <input type="checkbox" onclick="event.stopPropagation();" onchange="toggleCompare(${productData.id})" class="compare-check">
            <span>Compare</span>
        </label>
        <div class="product-actions">
            <button class="add-to-cart" onclick="event.stopPropagation(); addToCart(${productData.id})">
                <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>
            <button class="wishlist-btn" onclick="event.stopPropagation(); addToWishlist(${productData.id})">
                <i class="fas fa-heart"></i>
            </button>
        </div>
    `;
    
    return card;
}

// Generate star rating HTML
function generateStars(rating) {
    let stars = '';
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    
    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

// Add product to cart
async function addToCart(productId) {
    try {
        // Show loading state
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) {
            cartIcon.style.opacity = '0.5';
        }
        
        // Call API to add to cart
        const response = await fetch(`${API_BASE_URL}/api_cart.php?action=add`, {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload cart from server
            await loadCart();
            
            showNotification(`${data.product_name} added to cart!`, 'success');
            
            // Animate the cart icon
            if (cartIcon) {
                cartIcon.style.opacity = '1';
                cartIcon.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    cartIcon.style.transform = 'scale(1)';
                }, 300);
            }
        } else {
            showNotification(data.error || 'Failed to add to cart', 'error');
            if (cartIcon) cartIcon.style.opacity = '1';
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Failed to add to cart. Please try again.', 'error');
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) cartIcon.style.opacity = '1';
    }
}

// Add product to wishlist (using API)
async function addToWishlist(productId) {
    try {
        // Show loading state on heart button
        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
        const heartBtn = productCard ? productCard.querySelector('.wishlist-btn') : null;
        if (heartBtn) {
            heartBtn.style.opacity = '0.5';
        }
        
        // Call API to toggle wishlist (add if not exists, remove if exists)
        const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=toggle`, {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload wishlist from server
            await loadWishlist();
            
            if (data.action === 'added') {
                showNotification(`${data.product_name} added to wishlist!`, 'success');
                // Animate the heart icon
                if (heartBtn) {
                    heartBtn.style.opacity = '1';
                    heartBtn.style.background = 'var(--accent-color)';
                    heartBtn.style.color = 'var(--white)';
                    heartBtn.innerHTML = '<i class="fas fa-heart"></i>';
                }
            } else {
                showNotification(`${data.product_name} removed from wishlist`, 'info');
                // Reset heart icon
                if (heartBtn) {
                    heartBtn.style.opacity = '1';
                    heartBtn.style.background = '';
                    heartBtn.style.color = '';
                }
            }
        } else {
            showNotification(data.error || 'Failed to update wishlist', 'error');
            if (heartBtn) heartBtn.style.opacity = '1';
        }
    } catch (error) {
        console.error('Error updating wishlist:', error);
        showNotification('Failed to update wishlist. Please try again.', 'error');
        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
        const heartBtn = productCard ? productCard.querySelector('.wishlist-btn') : null;
        if (heartBtn) heartBtn.style.opacity = '1';
    }
}

// Update cart count
function updateCartCount() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    
    // Add styles
    Object.assign(notification.style, {
        position: 'fixed',
        top: '100px',
        right: '20px',
        background: type === 'success' ? '#4caf50' : '#2196f3',
        color: 'white',
        padding: '15px 20px',
        borderRadius: '8px',
        boxShadow: '0 4px 15px rgba(0,0,0,0.2)',
        display: 'flex',
        alignItems: 'center',
        gap: '10px',
        zIndex: '10000',
        animation: 'slideInRight 0.3s ease',
        fontSize: '14px',
        fontWeight: '600'
    });
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Open cart sidebar
function openCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    
    if (cartSidebar && cartOverlay) {
        // Use both class and inline style for reliability
        cartSidebar.classList.add('active');
        cartSidebar.style.right = '0';
        
        cartOverlay.classList.add('active');
        cartOverlay.style.opacity = '1';
        cartOverlay.style.visibility = 'visible';
        
        displayCartItems();
    }
}

// Close cart sidebar
function closeCartSidebar() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    
    if (cartSidebar && cartOverlay) {
        cartSidebar.classList.remove('active');
        cartSidebar.style.right = '-400px';
        
        cartOverlay.classList.remove('active');
        cartOverlay.style.opacity = '0';
        cartOverlay.style.visibility = 'hidden';
    }
}

// Display cart items
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotalElement = document.getElementById('cartTotal');
    
    if (!cartItemsContainer) return;
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <p>Your cart is empty</p>
                <p style="font-size: 14px; color: #999;">Add some products to get started!</p>
            </div>
        `;
        if (cartTotalElement) {
            cartTotalElement.textContent = '₹0';
        }
        return;
    }
    
    let cartHTML = '';
    let total = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        // Get first image from comma-separated list for products with multiple images
        const displayImage = getFirstImage(item.image) || item.image || 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop';
        
        cartHTML += `
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="${displayImage}" alt="${item.name}" onerror="this.src='https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop'">
                </div>
                <div class="cart-item-details">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">₹${item.price}</div>
                    <div class="cart-item-quantity">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="qty-display">${item.quantity}</span>
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="remove-item" onclick="removeFromCart(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItemsContainer.innerHTML = cartHTML;
    
    if (cartTotalElement) {
        cartTotalElement.textContent = `₹${total}`;
    }
}

// Update quantity
function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    
    if (item) {
        item.quantity += change;
        
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            // Update in database
            fetch(`${API_BASE_URL}/api_cart.php?action=update`, {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    product_id: productId,
                    quantity: item.quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('✅ Cart quantity updated in database');
                }
            })
            .catch(error => {
                console.error('❌ Error updating quantity:', error);
            });
            
            updateCartCount();
            displayCartItems();
        }
    }
}

// Remove from cart
function removeFromCart(productId) {
    // Find the cart item to get cart_id
    const item = cart.find(item => item.id === productId);
    
    if (item && item.cart_id) {
        // Remove from API/database using DELETE method
        fetch(`${API_BASE_URL}/api_cart.php?action=remove&id=${item.cart_id}`, {
            method: 'DELETE',
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Item removed from database cart');
            } else {
                console.error('❌ Failed to remove from database:', data.error);
            }
        })
        .catch(error => {
            console.error('❌ Error removing from database:', error);
        });
    }
    
    // Remove from local cart array
    cart = cart.filter(item => item.id !== productId);
    updateCartCount();
    displayCartItems();
    showNotification('Item removed from cart', 'info');
}

// Proceed to checkout
function proceedToCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'error');
        return;
    }
    
    // Redirect to dedicated checkout page
    window.location.href = 'checkout.php';
    
    /* Alternative: Use modal checkout (commented out)
    // Check if user is logged in
    const savedUser = localStorage.getItem('visionkart_user');
    if (!savedUser) {
        showNotification('Please login to proceed with checkout', 'info');
        closeCartSidebar();
        // Open login modal
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.style.display = 'flex';
        }
        return;
    }
    
    // Open checkout modal
    const checkoutModal = document.getElementById('checkoutModal');
    if (checkoutModal) {
        checkoutModal.style.display = 'flex';
        loadCheckoutSummary();
        
        // Pre-fill user details
        const user = JSON.parse(savedUser);
        document.getElementById('deliveryName').value = user.name || '';
        document.getElementById('deliveryPhone').value = user.phone || '';
    }
    
    closeCartSidebar();
    */
}

function loadCheckoutSummary() {
    const itemsList = document.getElementById('checkoutItemsList');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotal');
    
    if (!itemsList) return;
    
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        html += `
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e0e0e0;">
                <img src="${item.image}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: #333; margin-bottom: 3px;">${item.name}</div>
                    <div style="font-size: 14px; color: #666;">Quantity: ${item.quantity}</div>
                </div>
                <div style="font-weight: 700; color: #00bac7;">₹${itemTotal.toLocaleString()}</div>
            </div>
        `;
    });
    
    itemsList.innerHTML = html;
    subtotalEl.textContent = `₹${total.toLocaleString()}`;
    totalEl.textContent = `₹${total.toLocaleString()}`;
}

// Setup event listeners
function setupEventListeners() {
    // Cart icon click
    const cartIcon = document.querySelector('.cart-icon');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    const closeCart = document.getElementById('closeCart');
    const continueShopping = document.getElementById('continueShopping');
    const checkoutBtn = document.querySelector('.checkout-btn');
    
    if (cartIcon) {
        cartIcon.addEventListener('click', function(e) {
            e.preventDefault();
            openCart();
        });
    }
    
    if (closeCart) {
        closeCart.addEventListener('click', closeCartSidebar);
    }
    
    if (continueShopping) {
        continueShopping.addEventListener('click', closeCartSidebar);
    }
    
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCartSidebar);
    }
    
    // Checkout button click
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', proceedToCheckout);
    }
    
    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        console.log('✅ Search input found, attaching event listener');
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('🔍 Searching for:', searchTerm);
            
            // If user is searching, scroll to product grid (not just section)
            if (searchTerm.length > 0) {
                const productGrid = document.getElementById('productGrid');
                if (productGrid) {
                    productGrid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
            
            // Wait a moment for scroll, then filter
            setTimeout(() => {
                filterProducts(searchTerm);
            }, 300);
        });
        
        // Also handle Enter key to trigger search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = e.target.value.toLowerCase();
                const productGrid = document.getElementById('productGrid');
                if (productGrid) {
                    productGrid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                setTimeout(() => {
                    filterProducts(searchTerm);
                }, 300);
            }
        });
    } else {
        console.warn('⚠️ Search input not found');
    }
    
    // Smooth scroll for navigation links
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            
            if (targetId.startsWith('#')) {
                const targetSection = document.querySelector(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Category icon clicks
    document.querySelectorAll('.icon-item').forEach(item => {
        item.addEventListener('click', function() {
            const category = this.querySelector('p').textContent.toLowerCase();
            showNotification(`Showing ${this.querySelector('p').textContent}`, 'info');
            
            // Scroll to products section
            const productsSection = document.querySelector('.products-section');
            if (productsSection) {
                productsSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Filter products based on search term
function filterProducts(searchTerm) {
    // Get all product cards from all sections
    const productCards = document.querySelectorAll('.product-card');
    
    if (productCards.length === 0) {
        console.log('No product cards found');
        return;
    }
    
    let visibleCount = 0;
    
    productCards.forEach(card => {
        const productNameEl = card.querySelector('.product-name');
        const productTypeEl = card.querySelector('.product-type');
        
        if (!productNameEl || !productTypeEl) {
            return;
        }
        
        const productName = productNameEl.textContent.toLowerCase();
        const productType = productTypeEl.textContent.toLowerCase();
        
        if (searchTerm === '' || productName.includes(searchTerm) || productType.includes(searchTerm)) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.3s ease';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show notification if no results
    if (searchTerm !== '' && visibleCount === 0) {
        showNotification('No products found matching your search', 'info');
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .cart-icon {
        transition: transform 0.3s ease;
    }
`;
document.head.appendChild(style);

// Hero slider functionality (simple version)
let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');

function nextSlide() {
    if (slides.length > 1) {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }
}

// Auto-advance slides every 5 seconds
setInterval(nextSlide, 5000);

// Handle window resize
window.addEventListener('resize', function() {
    // Re-layout products if needed
    console.log('Window resized');
});

// Log cart contents (for debugging)
window.viewCart = function() {
    console.log('Cart contents:', cart);
    console.log('Total items:', cart.reduce((sum, item) => sum + item.quantity, 0));
    console.log('Total price:', cart.reduce((sum, item) => sum + (item.price * item.quantity), 0));
};

// ===== FILTER AND COMPARE FUNCTIONALITY =====

let compareList = [];
let activeFilters = {
    priceRange: [0, 5000],
    frameType: [],
    frameSize: [],
    gender: [],
    brand: [],
    color: []
};

// Initialize filter and compare
function initializeFilterCompare() {
    const filterSidebar = document.getElementById('filterSidebar');
    const filterOverlay = document.getElementById('filterOverlay');
    const filterBtn = document.getElementById('filterBtn');
    const closeFilter = document.getElementById('closeFilter');
    const applyFilters = document.getElementById('applyFilters');
    const clearFilters = document.getElementById('clearFilters');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    const compareBtn = document.getElementById('compareBtn');
    const compareModal = document.getElementById('compareModal');
    const compareOverlay = document.getElementById('compareOverlay');
    const closeCompare = document.getElementById('closeCompare');
    
    console.log('🔧 Initializing filters...', {
        filterBtn: !!filterBtn,
        filterSidebar: !!filterSidebar,
        filterOverlay: !!filterOverlay,
        closeFilter: !!closeFilter,
        applyFilters: !!applyFilters
    });
    
    // Toggle filter sidebar
    if (filterBtn && filterSidebar) {
        filterBtn.addEventListener('click', () => {
            console.log('Filter button clicked');
            filterSidebar.classList.add('active');
            if (filterOverlay) filterOverlay.classList.add('active');
        });
    }
    
    // Close filter sidebar
    if (closeFilter && filterSidebar) {
        closeFilter.addEventListener('click', () => {
            console.log('Close filter clicked');
            filterSidebar.classList.remove('active');
            if (filterOverlay) filterOverlay.classList.remove('active');
        });
    }
    
    // Close filter when clicking overlay
    if (filterOverlay && filterSidebar) {
        filterOverlay.addEventListener('click', () => {
            filterSidebar.classList.remove('active');
            filterOverlay.classList.remove('active');
        });
    }
    
    if (applyFilters) {
        applyFilters.addEventListener('click', () => {
            console.log('Apply filters clicked');
            applyProductFilters();
            filterSidebar.classList.remove('active');
            if (filterOverlay) filterOverlay.classList.remove('active');
        });
    }
    
    if (clearFilters) {
        clearFilters.addEventListener('click', () => {
            clearAllFilters();
        });
    }
    
    // Clear filters button in top bar
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            clearAllFilters();
        });
    }
    
    if (priceRange) {
        priceRange.addEventListener('input', (e) => {
            const value = e.target.value;
            priceValue.textContent = value;
            document.getElementById('maxPrice').value = value;
        });
    }
    
    // Update price range when min/max inputs change
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    
    if (minPrice) {
        minPrice.addEventListener('input', (e) => {
            const value = parseInt(e.target.value) || 0;
            if (priceRange) {
                priceRange.min = value;
            }
        });
    }
    
    if (maxPrice) {
        maxPrice.addEventListener('input', (e) => {
            const value = parseInt(e.target.value) || 5000;
            if (priceRange) {
                priceRange.value = value;
                priceValue.textContent = value;
            }
        });
    }
    
    if (compareBtn) {
        compareBtn.addEventListener('click', () => {
            if (compareList.length >= 2) {
                showCompareModal();
            }
        });
    }
    
    if (closeCompare) {
        closeCompare.addEventListener('click', closeCompareModal);
    }
    
    if (compareOverlay) {
        compareOverlay.addEventListener('click', closeCompareModal);
    }
}

// Toggle compare for a product
function toggleCompare(productId) {
    let product = products.find(p => p.id === productId);
    
    if (!product && typeof roundProducts !== 'undefined') {
        product = roundProducts.find(p => p.id === productId);
    }
    if (!product && typeof catEyeProducts !== 'undefined') {
        product = catEyeProducts.find(p => p.id === productId);
    }
    if (!product && typeof clubmasterProducts !== 'undefined') {
        product = clubmasterProducts.find(p => p.id === productId);
    }
    if (!product && typeof transparentProducts !== 'undefined') {
        product = transparentProducts.find(p => p.id === productId);
    }
    
    if (!product) return;
    
    const index = compareList.findIndex(p => p.id === productId);
    
    if (index > -1) {
        compareList.splice(index, 1);
    } else {
        if (compareList.length >= 4) {
            showNotification('You can compare up to 4 products only', 'info');
            const checkbox = document.querySelector(`[data-product-id="${productId}"] .compare-check`);
            if (checkbox) checkbox.checked = false;
            return;
        }
        compareList.push(product);
    }
    
    updateCompareButton();
}

// Update compare button
function updateCompareButton() {
    const compareBtn = document.getElementById('compareBtn');
    const compareCount = document.getElementById('compareCount');
    
    if (compareCount) {
        compareCount.textContent = compareList.length;
    }
    
    if (compareBtn) {
        compareBtn.disabled = compareList.length < 2;
    }
}

// Show compare modal
function showCompareModal() {
    const compareModal = document.getElementById('compareModal');
    const compareOverlay = document.getElementById('compareOverlay');
    const compareBody = document.getElementById('compareBody');
    
    if (!compareModal || !compareBody) return;
    
    let tableHTML = '<table class="compare-table"><tr><th>Feature</th>';
    
    compareList.forEach(product => {
        tableHTML += `<th>${product.name}</th>`;
    });
    tableHTML += '</tr>';
    
    // Image row
    tableHTML += '<tr><td><strong>Image</strong></td>';
    compareList.forEach(product => {
        tableHTML += `<td><img src="${product.image}" alt="${product.name}"></td>`;
    });
    tableHTML += '</tr>';
    
    // Price row
    tableHTML += '<tr><td><strong>Price</strong></td>';
    compareList.forEach(product => {
        tableHTML += `<td><small style="font-size: 11px; color: #666;">Sell Price:</small><br><span style="color: var(--primary-color); font-size: 18px; font-weight: 700;">₹${product.price}</span><br><small style="font-size: 11px; color: #999;">Purchase Price:</small><br><span style="text-decoration: line-through; color: #999;">₹${product.originalPrice}</span></td>`;
    });
    tableHTML += '</tr>';
    
    // Rating row
    tableHTML += '<tr><td><strong>Rating</strong></td>';
    compareList.forEach(product => {
        tableHTML += `<td>${product.rating} ⭐ (${product.reviews} reviews)</td>`;
    });
    tableHTML += '</tr>';
    
    // Type row
    tableHTML += '<tr><td><strong>Type</strong></td>';
    compareList.forEach(product => {
        tableHTML += `<td>${product.type}</td>`;
    });
    tableHTML += '</tr>';
    
    // Brand row (if available)
    if (compareList.some(p => p.brand)) {
        tableHTML += '<tr><td><strong>Brand</strong></td>';
        compareList.forEach(product => {
            tableHTML += `<td>${product.brand || 'N/A'}</td>`;
        });
        tableHTML += '</tr>';
    }
    
    // Action row
    tableHTML += '<tr><td><strong>Action</strong></td>';
    compareList.forEach(product => {
        tableHTML += `<td><button class="btn-primary" onclick="addToCart(${product.id}); closeCompareModal();" style="padding: 10px 20px;">Add to Cart</button></td>`;
    });
    tableHTML += '</tr>';
    
    tableHTML += '</table>';
    
    compareBody.innerHTML = tableHTML;
    compareModal.classList.add('active');
    compareOverlay.classList.add('active');
}

// Close compare modal
function closeCompareModal() {
    const compareModal = document.getElementById('compareModal');
    const compareOverlay = document.getElementById('compareOverlay');
    
    if (compareModal) compareModal.classList.remove('active');
    if (compareOverlay) compareOverlay.classList.remove('active');
}

// Apply product filters
function applyProductFilters() {
    // Get filter values
    const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseInt(document.getElementById('maxPrice').value) || 5000;
    
    activeFilters.priceRange = [minPrice, maxPrice];
    activeFilters.frameType = Array.from(document.querySelectorAll('input[name="frameType"]:checked')).map(cb => cb.value);
    activeFilters.frameSize = Array.from(document.querySelectorAll('input[name="frameSize"]:checked')).map(cb => cb.value);
    activeFilters.gender = Array.from(document.querySelectorAll('input[name="gender"]:checked')).map(cb => cb.value);
    activeFilters.brand = Array.from(document.querySelectorAll('input[name="brand"]:checked')).map(cb => cb.value);
    activeFilters.color = Array.from(document.querySelectorAll('input[name="color"]:checked')).map(cb => cb.value);
    
    // Filter products
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
        const productId = parseInt(card.getAttribute('data-product-id'));
        let product = products.find(p => p.id === productId);
        
        // Check category products
        if (!product && typeof roundProducts !== 'undefined') {
            product = roundProducts.find(p => p.id === productId);
        }
        if (!product && typeof catEyeProducts !== 'undefined') {
            product = catEyeProducts.find(p => p.id === productId);
        }
        if (!product && typeof clubmasterProducts !== 'undefined') {
            product = clubmasterProducts.find(p => p.id === productId);
        }
        if (!product && typeof transparentProducts !== 'undefined') {
            product = transparentProducts.find(p => p.id === productId);
        }
        
        if (!product) return;
        
        let matches = true;
        
        // Price filter
        if (product.price < minPrice || product.price > maxPrice) {
            matches = false;
        }
        
        // Frame type filter
        if (activeFilters.frameType.length > 0 && product.frameType) {
            if (!activeFilters.frameType.includes(product.frameType)) {
                matches = false;
            }
        }
        
        // Frame size filter
        if (activeFilters.frameSize.length > 0 && product.size) {
            const productSize = product.size.toLowerCase().replace(' ', '-');
            if (!activeFilters.frameSize.includes(productSize)) {
                matches = false;
            }
        }
        
        // Gender filter
        if (activeFilters.gender.length > 0 && product.gender) {
            if (!activeFilters.gender.includes(product.gender)) {
                matches = false;
            }
        }
        
        // Brand filter
        if (activeFilters.brand.length > 0 && product.brand) {
            if (!activeFilters.brand.includes(product.brand)) {
                matches = false;
            }
        }
        
        // Color filter
        if (activeFilters.color.length > 0 && product.color) {
            if (!activeFilters.color.includes(product.color)) {
                matches = false;
            }
        }
        
        if (matches) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    updateActiveFiltersDisplay();
    showNotification(`Showing ${visibleCount} products`, 'info');
}

// Update active filters display
function updateActiveFiltersDisplay() {
    const activeFiltersDiv = document.getElementById('activeFilters');
    if (!activeFiltersDiv) return;
    
    let filtersHTML = '';
    
    // Price filter tag
    if (activeFilters.priceRange[0] > 0 || activeFilters.priceRange[1] < 5000) {
        filtersHTML += `<span class="filter-tag">₹${activeFilters.priceRange[0]} - ₹${activeFilters.priceRange[1]} <i class="fas fa-times" onclick="removeFilter('price')"></i></span>`;
    }
    
    // Other filters
    [...activeFilters.frameType, ...activeFilters.frameSize, ...activeFilters.gender, ...activeFilters.brand, ...activeFilters.color].forEach(filter => {
        filtersHTML += `<span class="filter-tag">${filter} <i class="fas fa-times" onclick="removeFilter('${filter}')"></i></span>`;
    });
    
    activeFiltersDiv.innerHTML = filtersHTML;
}

// Remove individual filter
function removeFilter(filterValue) {
    if (filterValue === 'price') {
        document.getElementById('minPrice').value = 0;
        document.getElementById('maxPrice').value = 5000;
        document.getElementById('priceRange').value = 5000;
        document.getElementById('priceValue').textContent = 5000;
    } else {
        const checkbox = document.querySelector(`input[value="${filterValue}"]`);
        if (checkbox) checkbox.checked = false;
    }
    
    applyProductFilters();
}

// Clear all filters
function clearAllFilters() {
    document.querySelectorAll('.filter-sidebar input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.getElementById('minPrice').value = 0;
    document.getElementById('maxPrice').value = 5000;
    document.getElementById('priceRange').value = 5000;
    document.getElementById('priceValue').textContent = 5000;
    
    activeFilters = {
        priceRange: [0, 5000],
        frameType: [],
        frameSize: [],
        gender: [],
        brand: [],
        color: []
    };
    
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = 'block';
    });
    
    updateActiveFiltersDisplay();
    showNotification('All filters cleared', 'info');
}

// Initialize filters when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('filterBtn')) {
        initializeFilterCompare();
        console.log('✅ Filter system initialized');
    }
});

// Setup Login Modal
function setupLoginModal() {
    const loginBtn = document.getElementById('loginBtn');
    const loginModal = document.getElementById('loginModal');
    const closeLoginModal = document.getElementById('closeLoginModal');
    const loginForm = document.getElementById('loginForm');
    
    if (!loginBtn || !loginModal) return;
    
    // Open login modal
    loginBtn.addEventListener('click', function(e) {
        e.preventDefault();
        loginModal.style.display = 'flex';
    });
    
    // Close login modal
    closeLoginModal.addEventListener('click', function() {
        loginModal.style.display = 'none';
    });
    
    // Close on outside click
    loginModal.addEventListener('click', function(e) {
        if (e.target === loginModal) {
            loginModal.style.display = 'none';
        }
    });
    
    // Handle login form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        // Check against registered users
        const existingUsers = JSON.parse(localStorage.getItem('visionkart_users') || '[]');
        const user = existingUsers.find(u => u.email === email && u.password === password);
        
        if (user) {
            // Store logged-in user
            const loggedInUser = { 
                email: user.email, 
                name: user.name,
                loginTime: new Date().toISOString() 
            };
            localStorage.setItem('visionkart_user', JSON.stringify(loggedInUser));
            
            showNotification(`Welcome back, ${user.name}!`, 'success');
            loginModal.style.display = 'none';
            
            // Update login button to show user
            loginBtn.innerHTML = `<i class="fas fa-user-circle"></i><span style="margin-left: 5px;">${user.name}</span>`;
            
            // Clear form
            loginForm.reset();
        } else {
            showNotification('Invalid email or password!', 'error');
        }
    });
    
    // Check if user is already logged in
    const savedUser = localStorage.getItem('visionkart_user');
    if (savedUser) {
        const user = JSON.parse(savedUser);
        const userName = user.name || user.email.split('@')[0];
        loginBtn.innerHTML = `<i class="fas fa-user-circle"></i><span style="margin-left: 5px;">${userName}</span>`;
    }
    
    // Setup switch to registration modal
    const showRegisterBtn = document.getElementById('showRegisterModal');
    const showLoginFromRegister = document.getElementById('showLoginFromRegister');
    const registerModal = document.getElementById('registerModal');
    
    if (showRegisterBtn && registerModal) {
        showRegisterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.style.display = 'none';
            registerModal.style.display = 'flex';
        });
    }
    
    if (showLoginFromRegister && loginModal) {
        showLoginFromRegister.addEventListener('click', function(e) {
            e.preventDefault();
            registerModal.style.display = 'none';
            loginModal.style.display = 'flex';
        });
    }
}

// Setup Registration Modal
function setupRegistrationModal() {
    const registerModal = document.getElementById('registerModal');
    const closeRegisterModal = document.getElementById('closeRegisterModal');
    const registerForm = document.getElementById('registerForm');
    
    if (!registerModal || !registerForm) return;
    
    // Close registration modal
    closeRegisterModal.addEventListener('click', function() {
        registerModal.style.display = 'none';
    });
    
    // Close on outside click
    registerModal.addEventListener('click', function(e) {
        if (e.target === registerModal) {
            registerModal.style.display = 'none';
        }
    });
    
    // Handle registration form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const phone = document.getElementById('registerPhone').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
        const agreeTerms = document.getElementById('agreeTerms').checked;
        
        // Validation
        if (!agreeTerms) {
            showNotification('Please agree to Terms & Conditions', 'error');
            return;
        }
        
        if (password !== confirmPassword) {
            showNotification('Passwords do not match!', 'error');
            return;
        }
        
        if (password.length < 6) {
            showNotification('Password must be at least 6 characters', 'error');
            return;
        }
        
        if (phone.length !== 10) {
            showNotification('Please enter a valid 10-digit phone number', 'error');
            return;
        }
        
        // Check if user already exists
        const existingUsers = JSON.parse(localStorage.getItem('visionkart_users') || '[]');
        const userExists = existingUsers.find(u => u.email === email);
        
        if (userExists) {
            showNotification('Email already registered! Please login.', 'error');
            return;
        }
        
        // Create new user
        const newUser = {
            name: name,
            email: email,
            phone: phone,
            password: password, // In production, this should be hashed
            registeredDate: new Date().toISOString()
        };
        
        // Save to users list
        existingUsers.push(newUser);
        localStorage.setItem('visionkart_users', JSON.stringify(existingUsers));
        
        // Auto-login the user
        const user = { email: email, name: name, loginTime: new Date().toISOString() };
        localStorage.setItem('visionkart_user', JSON.stringify(user));
        
        // Close modal and show success
        registerModal.style.display = 'none';
        showNotification(`Welcome ${name}! Your account has been created.`, 'success');
        
        // Update login button
        const loginBtn = document.getElementById('loginBtn');
        if (loginBtn) {
            loginBtn.innerHTML = `<i class="fas fa-user-circle"></i><span style="margin-left: 5px;">${name}</span>`;
        }
        
        // Clear form
        registerForm.reset();
    });
}

// Setup Wishlist Sidebar
function setupWishlistSidebar() {
    const wishlistBtn = document.getElementById('wishlistBtn');
    const wishlistSidebar = document.getElementById('wishlistSidebar');
    const wishlistOverlay = document.getElementById('wishlistOverlay');
    const closeWishlist = document.getElementById('closeWishlist');
    
    if (!wishlistBtn || !wishlistSidebar) {
        console.warn('Wishlist elements not found');
        return;
    }
    
    // Open wishlist
    wishlistBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Wishlist button clicked');
        openWishlist();
    });
    
    // Close wishlist
    if (closeWishlist) {
        closeWishlist.addEventListener('click', function() {
            console.log('Close wishlist clicked');
            closeWishlistSidebar();
        });
    }
    
    // Close on overlay click
    if (wishlistOverlay) {
        wishlistOverlay.addEventListener('click', function() {
            console.log('Wishlist overlay clicked');
            closeWishlistSidebar();
        });
    }
}

// Open wishlist sidebar
function openWishlist() {
    const wishlistSidebar = document.getElementById('wishlistSidebar');
    const wishlistOverlay = document.getElementById('wishlistOverlay');
    
    console.log('Opening wishlist sidebar...');
    console.log('Sidebar element:', wishlistSidebar);
    console.log('Overlay element:', wishlistOverlay);
    
    if (!wishlistSidebar || !wishlistOverlay) {
        console.error('Wishlist sidebar or overlay not found!');
        return;
    }
    
    wishlistSidebar.style.right = '0';
    wishlistOverlay.style.opacity = '1';
    wishlistOverlay.style.visibility = 'visible';
    
    console.log('Wishlist sidebar opened');
    displayWishlistItems();
}

// Close wishlist sidebar
function closeWishlistSidebar() {
    const wishlistSidebar = document.getElementById('wishlistSidebar');
    const wishlistOverlay = document.getElementById('wishlistOverlay');
    
    wishlistSidebar.style.right = '-400px';
    wishlistOverlay.style.opacity = '0';
    wishlistOverlay.style.visibility = 'hidden';
}

// Display wishlist items
function displayWishlistItems() {
    const wishlistItemsDiv = document.getElementById('wishlistItems');
    
    if (!wishlistItemsDiv) return;
    
    if (wishlist.length === 0) {
        wishlistItemsDiv.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; color: #999;">
                <i class="fas fa-heart" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3;"></i>
                <h3 style="color: #666; font-size: 18px; margin: 0;">Your wishlist is empty</h3>
                <p style="font-size: 14px;">Add products you love to your wishlist</p>
            </div>
        `;
        return;
    }
    
    let itemsHTML = '';
    wishlist.forEach(product => {
        const productType = product.type || product.category || 'Eyewear';
        const productImage = product.image || 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop';
        const originalPrice = product.original_price || product.originalPrice;
        const stockStatus = product.in_stock !== false && product.stock > 0;
        
        itemsHTML += `
            <div class="wishlist-item" style="display: flex; gap: 15px; padding: 15px; border-bottom: 1px solid #e0e0e0; background: #f9f9f9; border-radius: 8px; margin-bottom: 15px;">
                <a href="product-detail.php?id=${product.id}" style="flex-shrink: 0;">
                    <img src="${productImage}" alt="${product.name}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                </a>
                <div style="flex: 1;">
                    <a href="product-detail.php?id=${product.id}" style="text-decoration: none;">
                        <h4 style="margin: 0 0 5px 0; color: #333; font-size: 14px;">${product.name}</h4>
                    </a>
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 12px;">${productType}</p>
                    ${product.brand ? `<p style="margin: 0 0 10px 0; color: #999; font-size: 11px;">${product.brand}</p>` : ''}
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span style="font-size: 16px; font-weight: bold; color: #00bac7;">₹${product.price}</span>
                        ${originalPrice && originalPrice > product.price ? `<span style="text-decoration: line-through; color: #999; font-size: 12px;">₹${originalPrice}</span>` : ''}
                        ${product.discount ? `<span style="background: #ff6b6b; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px;">${product.discount}% OFF</span>` : ''}
                    </div>
                    ${!stockStatus ? '<p style="color: #f44336; font-size: 11px; margin: 5px 0 0 0;">Out of Stock</p>' : ''}
                    <div style="margin-top: 10px; display: flex; gap: 10px;">
                        <button onclick="moveToCart(${product.id})" style="flex: 1; padding: 8px; background: ${stockStatus ? '#00bac7' : '#ccc'}; color: white; border: none; border-radius: 5px; cursor: ${stockStatus ? 'pointer' : 'not-allowed'}; font-size: 12px;" ${!stockStatus ? 'disabled' : ''}>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button onclick="removeFromWishlist(${product.id})" style="padding: 8px 12px; background: #ff6b6b; color: white; border: none; border-radius: 5px; cursor: pointer;" title="Remove from wishlist">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    // Add clear all button if there are items
    itemsHTML += `
        <div style="padding: 15px; border-top: 2px solid #e0e0e0; margin-top: 10px;">
            <button onclick="clearWishlist()" style="width: 100%; padding: 12px; background: transparent; color: #ff6b6b; border: 2px solid #ff6b6b; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='#ff6b6b'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#ff6b6b';">
                <i class="fas fa-trash-alt"></i> Clear Wishlist
            </button>
        </div>
    `;
    
    wishlistItemsDiv.innerHTML = itemsHTML;
}

// Remove from wishlist (using API)
async function removeFromWishlist(productId) {
    try {
        const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=remove&product_id=${productId}`, {
            method: 'DELETE',
            credentials: 'include'
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload wishlist from server
            await loadWishlist();
            displayWishlistItems();
            showNotification(data.product_name ? `${data.product_name} removed from wishlist` : 'Removed from wishlist', 'info');
            
            // Reset heart icon if product card is visible
            const productCard = document.querySelector(`[data-product-id="${productId}"]`);
            if (productCard) {
                const heartBtn = productCard.querySelector('.wishlist-btn');
                if (heartBtn) {
                    heartBtn.style.background = '';
                    heartBtn.style.color = '';
                }
            }
        } else {
            showNotification(data.error || 'Failed to remove from wishlist', 'error');
        }
    } catch (error) {
        console.error('Error removing from wishlist:', error);
        showNotification('Failed to remove from wishlist', 'error');
    }
}

// Move item from wishlist to cart
async function moveToCart(productId) {
    try {
        // Add to cart
        await addToCart(productId);
        
        // Remove from wishlist
        await removeFromWishlist(productId);
        
        showNotification('Item moved to cart!', 'success');
    } catch (error) {
        console.error('Error moving to cart:', error);
    }
}

// Clear entire wishlist
async function clearWishlist() {
    if (!confirm('Are you sure you want to clear your entire wishlist?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/api_wishlist.php?action=clear`, {
            method: 'DELETE',
            credentials: 'include'
        });
        
        const data = await response.json();
        
        if (data.success) {
            wishlist = [];
            updateWishlistCount();
            displayWishlistItems();
            showNotification('Wishlist cleared', 'info');
            
            // Reset all heart icons
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                btn.style.background = '';
                btn.style.color = '';
            });
        } else {
            showNotification(data.error || 'Failed to clear wishlist', 'error');
        }
    } catch (error) {
        console.error('Error clearing wishlist:', error);
        showNotification('Failed to clear wishlist', 'error');
    }
}

// Check if product is in wishlist and update UI
async function updateWishlistUI() {
    try {
        // Update heart icons for products in wishlist
        wishlist.forEach(item => {
            const productCard = document.querySelector(`[data-product-id="${item.id}"]`);
            if (productCard) {
                const heartBtn = productCard.querySelector('.wishlist-btn');
                if (heartBtn) {
                    heartBtn.style.background = 'var(--accent-color)';
                    heartBtn.style.color = 'var(--white)';
                }
            }
        });
    } catch (error) {
        console.error('Error updating wishlist UI:', error);
    }
}

console.log('VisionKart website loaded successfully! 👓');
console.log('Use viewCart() in console to see cart contents');

// Checkout Modal Functionality
function setupCheckoutModal() {
    const closeCheckoutModal = document.getElementById('closeCheckoutModal');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const paymentOptions = document.querySelectorAll('input[name="paymentMethod"]');
    const cardDetails = document.getElementById('cardDetails');
    const upiDetails = document.getElementById('upiDetails');
    
    // Close modal
    if (closeCheckoutModal) {
        closeCheckoutModal.addEventListener('click', function() {
            document.getElementById('checkoutModal').style.display = 'none';
        });
    }
    
    // Payment method toggle
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (cardDetails) cardDetails.style.display = this.value === 'card' ? 'block' : 'none';
            if (upiDetails) upiDetails.style.display = this.value === 'upi' ? 'block' : 'none';
            
            // Highlight selected option
            document.querySelectorAll('.payment-option').forEach(label => {
                label.style.borderColor = '#e0e0e0';
                label.style.background = 'white';
            });
            this.closest('.payment-option').style.borderColor = '#00bac7';
            this.closest('.payment-option').style.background = '#f0fbfc';
        });
    });
    
    // Place Order
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            const name = document.getElementById('deliveryName').value;
            const phone = document.getElementById('deliveryPhone').value;
            const address = document.getElementById('deliveryAddress').value;
            const city = document.getElementById('deliveryCity').value;
            const pincode = document.getElementById('deliveryPincode').value;
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            // Validate
            if (!name || !phone || !address || !city || !pincode) {
                showNotification('Please fill all delivery details', 'error');
                return;
            }
            
            if (paymentMethod === 'card') {
                const cardNumber = document.getElementById('cardNumber').value;
                const cardExpiry = document.getElementById('cardExpiry').value;
                const cardCVV = document.getElementById('cardCVV').value;
                
                if (!cardNumber || !cardExpiry || !cardCVV) {
                    showNotification('Please fill all card details', 'error');
                    return;
                }
            }
            
            if (paymentMethod === 'upi') {
                const upiId = document.getElementById('upiId').value;
                if (!upiId) {
                    showNotification('Please enter UPI ID', 'error');
                    return;
                }
            }
            
            // Calculate total
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const savedUser = localStorage.getItem('visionkart_user');
            const user = JSON.parse(savedUser);
            
            // Create order
            const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
            const newOrder = {
                id: Date.now(),
                customer: user.email,
                items: [...cart],
                total: total,
                date: new Date().toISOString(),
                status: 'Pending',
                paymentMethod: paymentMethod,
                deliveryAddress: {
                    name: name,
                    phone: phone,
                    address: address,
                    address2: document.getElementById('deliveryAddress2').value,
                    city: city,
                    pincode: pincode
                }
            };
            
            orders.push(newOrder);
            localStorage.setItem('visionkart_orders', JSON.stringify(orders));
            
            console.log('✅ Order placed:', newOrder);
            
            // Clear cart
            cart = [];
            saveCart();
            updateCartCount();
            displayCartItems();
            
            // Close modal
            document.getElementById('checkoutModal').style.display = 'none';
            
            // Show success
            let paymentText = '';
            if (paymentMethod === 'cod') {
                paymentText = 'Cash on Delivery';
            } else if (paymentMethod === 'card') {
                paymentText = 'Card Payment';
            } else if (paymentMethod === 'upi') {
                paymentText = 'UPI Payment';
            } else {
                paymentText = 'Net Banking';
            }
            
            showNotification(`🎉 Order placed successfully! Order ID: #${newOrder.id}`, 'success');
            
            setTimeout(() => {
                alert(`✅ Order Confirmed!\n\nOrder ID: #${newOrder.id}\nTotal: ₹${total.toLocaleString()}\nPayment: ${paymentText}\n\nDelivery to:\n${name}\n${address}, ${city} - ${pincode}\n\nThank you for shopping with VisionKart! 👓`);
            }, 500);
        });
    }
    
    // Card number formatting
    const cardNumber = document.getElementById('cardNumber');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    // Expiry formatting
    const cardExpiry = document.getElementById('cardExpiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    }
}

setupCheckoutModal();
