// Product Detail Page JavaScript

// Get product ID from URL
function getProductIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

// All product arrays
const products = [
        {
            id: 1,
            name: "Classic Round",
            price: 1499,
            originalPrice: 2999,
            image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop",
            category: "Round",
            brand: "Vincent Chase",
            frameType: "Full Rim",
            gender: "Unisex",
            rating: 4.5,
            reviews: 234
        },
        {
            id: 2,
            name: "Aviator Gold",
            price: 1999,
            originalPrice: 3999,
            image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop",
            category: "Aviator",
            brand: "John Jacobs",
            frameType: "Full Rim",
            gender: "Men",
            rating: 4.7,
            reviews: 189
        },
        {
            id: 3,
            name: "Cat Eye Pink",
            price: 1299,
            originalPrice: 2499,
            image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop",
            category: "Cat-Eye",
            brand: "Vincent Chase",
            frameType: "Full Rim",
            gender: "Women",
            rating: 4.3,
            reviews: 156
        },
        {
            id: 4,
            name: "Rectangular Black",
            price: 1799,
            originalPrice: 3499,
            image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop",
            category: "Rectangle",
            brand: "John Jacobs",
            frameType: "Full Rim",
            gender: "Unisex",
            rating: 4.6,
            reviews: 201
        },
        {
            id: 5,
            name: "Oversized Square",
            price: 2199,
            originalPrice: 4299,
            image: "https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop",
            category: "Square",
            brand: "Vincent Chase",
            frameType: "Full Rim",
            gender: "Women",
            rating: 4.4,
            reviews: 178
        },
        {
            id: 6,
            name: "Clubmaster Brown",
            price: 1599,
            originalPrice: 2999,
            image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop",
            category: "Clubmaster",
            brand: "John Jacobs",
            frameType: "Half Rim",
            gender: "Men",
            rating: 4.8,
            reviews: 267
        },
        {
            id: 7,
            name: "Transparent Clear",
            price: 1399,
            originalPrice: 2699,
            image: "https://images.unsplash.com/photo-1564404805960-4f65c76d4f3e?w=400&h=400&fit=crop",
            category: "Transparent",
            brand: "Vincent Chase",
            frameType: "Full Rim",
            gender: "Unisex",
            rating: 4.2,
            reviews: 142
        },
        {
            id: 8,
            name: "Wayfarer Blue",
            price: 1899,
            originalPrice: 3799,
            image: "https://images.unsplash.com/photo-1609497181082-9c2e6815a07f?w=400&h=400&fit=crop",
            category: "Wayfarer",
            brand: "John Jacobs",
            frameType: "Full Rim",
            gender: "Unisex",
            rating: 4.5,
            reviews: 223
        }
    ];

// Round category products
const roundProducts = [
    { id: 101, name: "Classic Round Black", price: 1499, originalPrice: 2999, image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.5 },
    { id: 102, name: "Metal Round Gold", price: 1799, originalPrice: 3499, image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.6 },
    { id: 103, name: "Vintage Round Brown", price: 1599, originalPrice: 2999, image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.4 },
    { id: 104, name: "Thin Round Silver", price: 1699, originalPrice: 3299, image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.7 },
    { id: 105, name: "Retro Round Tortoise", price: 1399, originalPrice: 2699, image: "https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.3 },
    { id: 106, name: "Modern Round Blue", price: 1899, originalPrice: 3699, image: "https://images.unsplash.com/photo-1609497181082-9c2e6815a07f?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.5 },
    { id: 107, name: "Premium Round Rose Gold", price: 2199, originalPrice: 4299, image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.8 },
    { id: 108, name: "Lightweight Round Titanium", price: 2499, originalPrice: 4899, image: "https://images.unsplash.com/photo-1564404805960-4f65c76d4f3e?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.9 },
    { id: 109, name: "Classic Round Navy", price: 1599, originalPrice: 3099, image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.4 },
    { id: 110, name: "Round Gradient Purple", price: 1799, originalPrice: 3499, image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.6 },
    { id: 111, name: "Bold Round Red", price: 1699, originalPrice: 3299, image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.5 },
    { id: 112, name: "Slim Round Green", price: 1499, originalPrice: 2899, image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.3 },
    { id: 113, name: "Round Wood Pattern", price: 1899, originalPrice: 3699, image: "https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.7 },
    { id: 114, name: "Round Marble Design", price: 2099, originalPrice: 4099, image: "https://images.unsplash.com/photo-1609497181082-9c2e6815a07f?w=400&h=400&fit=crop", category: "Round", brand: "John Jacobs", frameType: "Full Rim", rating: 4.6 },
    { id: 115, name: "Round Crystal Clear", price: 1599, originalPrice: 3099, image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop", category: "Round", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.4 }
];

// Cat-Eye category products
const catEyeProducts = [
    { id: 201, name: "Classic Cat Eye Pink", price: 1299, originalPrice: 2499, image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.3 },
    { id: 202, name: "Vintage Cat Eye Black", price: 1499, originalPrice: 2899, image: "https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.5 },
    { id: 203, name: "Modern Cat Eye Tortoise", price: 1399, originalPrice: 2699, image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.4 },
    { id: 204, name: "Bold Cat Eye Red", price: 1599, originalPrice: 3099, image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.6 },
    { id: 205, name: "Elegant Cat Eye Brown", price: 1699, originalPrice: 3299, image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.5 },
    { id: 206, name: "Oversized Cat Eye Blue", price: 1799, originalPrice: 3499, image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.7 },
    { id: 207, name: "Cat Eye Crystal", price: 1899, originalPrice: 3699, image: "https://images.unsplash.com/photo-1609497181082-9c2e6815a07f?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.6 },
    { id: 208, name: "Retro Cat Eye Purple", price: 1499, originalPrice: 2899, image: "https://images.unsplash.com/photo-1564404805960-4f65c76d4f3e?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.4 },
    { id: 209, name: "Cat Eye Gradient", price: 1599, originalPrice: 3099, image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.5 },
    { id: 210, name: "Designer Cat Eye Gold", price: 2199, originalPrice: 4299, image: "https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.8 },
    { id: 211, name: "Cat Eye Silver Frame", price: 1799, originalPrice: 3499, image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.6 },
    { id: 212, name: "Cat Eye Marble Pattern", price: 1899, originalPrice: 3699, image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.7 },
    { id: 213, name: "Cat Eye Floral Design", price: 1699, originalPrice: 3299, image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.5 },
    { id: 214, name: "Cat Eye Transparent", price: 1399, originalPrice: 2699, image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "John Jacobs", frameType: "Full Rim", rating: 4.3 },
    { id: 215, name: "Cat Eye Acetate", price: 1599, originalPrice: 3099, image: "https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop", category: "Cat-Eye", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.4 }
];

// Clubmaster category products
const clubmasterProducts = [
    { id: 301, name: "Clubmaster Brown", price: 1599, originalPrice: 2999, image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop", category: "Clubmaster", brand: "John Jacobs", frameType: "Half Rim", rating: 4.8 },
    { id: 302, name: "Classic Clubmaster Black", price: 1499, originalPrice: 2899, image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop", category: "Clubmaster", brand: "Vincent Chase", frameType: "Half Rim", rating: 4.7 },
    { id: 303, name: "Clubmaster Tortoise", price: 1699, originalPrice: 3299, image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop", category: "Clubmaster", brand: "John Jacobs", frameType: "Half Rim", rating: 4.6 }
];

// Transparent category products
const transparentProducts = [
    { id: 401, name: "Transparent Clear", price: 1399, originalPrice: 2699, image: "https://images.unsplash.com/photo-1564404805960-4f65c76d4f3e?w=400&h=400&fit=crop", category: "Transparent", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.2 },
    { id: 402, name: "Transparent Blue Tint", price: 1499, originalPrice: 2899, image: "https://images.unsplash.com/photo-1609497181082-9c2e6815a07f?w=400&h=400&fit=crop", category: "Transparent", brand: "John Jacobs", frameType: "Full Rim", rating: 4.4 },
    { id: 403, name: "Transparent Pink", price: 1299, originalPrice: 2499, image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop", category: "Transparent", brand: "Vincent Chase", frameType: "Full Rim", rating: 4.3 }
];

// Cart array
let cart = [];

// Load cart from localStorage
async function loadCart() {
    try {
        const response = await fetch('api_cart.php?action=list', {
            credentials: 'include'
        });
        const data = await response.json();
        
        if (data.success && Array.isArray(data.items)) {
            cart = data.items;
            updateCartCount();
        } else {
            cart = [];
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        // Fallback to localStorage
        const savedCart = localStorage.getItem('visionkart_cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
        }
    }
}

// Save cart to localStorage (deprecated - using API now)
function saveCart() {
    localStorage.setItem('visionkart_cart', JSON.stringify(cart));
}

// Update cart count
function updateCartCount() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
    }
}

// Add product to cart
async function addToCart(productId) {
    try {
        // Show loading state
        const addToCartBtn = document.querySelector('.add-to-cart-detail');
        const originalText = addToCartBtn ? addToCartBtn.textContent : '';
        if (addToCartBtn) {
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = 'Adding...';
        }
        
        // First, try to add using the product ID (for database products)
        let response = await fetch(`api_cart.php?action=add`, {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });
        
        let data = await response.json();
        
        // If product not found in database, try to find it locally and sync to database first
        if (!data.success && data.error === 'Product not found') {
            console.log('Product not found in database, attempting to sync from local storage...');
            
            // Find the product in local sources
            const localProduct = await findProductById(productId);
            
            if (localProduct) {
                // Try to create the product in the database first
                const createResponse = await fetch('api_products.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        name: localProduct.name,
                        category: localProduct.type || 'eyeglasses',
                        subcategory: localProduct.category || 'round',
                        brand: localProduct.brand || 'VisionKart',
                        price: localProduct.price,
                        originalPrice: localProduct.originalPrice || localProduct.price * 2,
                        gst: localProduct.gst || 12,
                        stock: localProduct.stock || 100,
                        status: 'active',
                        image: localProduct.image,
                        frametype: localProduct.frameType || 'full-rim',
                        color: localProduct.color || '',
                        description: localProduct.description || ''
                    })
                });
                
                const createResult = await createResponse.json();
                
                if (createResult.success && createResult.id) {
                    // Product created in database, now add to cart with new ID
                    response = await fetch(`api_cart.php?action=add`, {
                        method: 'POST',
                        credentials: 'include',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            product_id: createResult.id,
                            quantity: 1
                        })
                    });
                    data = await response.json();
                    
                    if (data.success) {
                        console.log('Product synced to database and added to cart with new ID:', createResult.id);
                    }
                } else {
                    console.error('Failed to sync product to database:', createResult.error);
                }
            }
        }
        
        if (data.success) {
            // Reload cart from server
            await loadCart();
            
            alert(`${data.product_name || 'Product'} added to cart!`);
            
            // Animate the cart icon
            const cartIcon = document.querySelector('.cart-icon');
            if (cartIcon) {
                cartIcon.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    cartIcon.style.transform = 'scale(1)';
                }, 300);
            }
            
            // Reset button
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = originalText;
            }
        } else {
            alert(data.error || 'Failed to add to cart');
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = originalText;
            }
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Failed to add to cart. Please try again.');
        const addToCartBtn = document.querySelector('.add-to-cart-detail');
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = 'Add to Cart';
        }
    }
}

// Display cart items
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotalElement = document.getElementById('cartTotal');
    
    if (!cartItemsContainer) return;
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="cart-empty" style="text-align: center; padding: 40px 20px;">
                <i class="fas fa-shopping-cart" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                <p style="font-size: 16px; color: #333; margin-bottom: 5px;">Your cart is empty</p>
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
        
        cartHTML += `
            <div class="cart-item" style="display: flex; gap: 15px; padding: 15px; border-bottom: 1px solid #e0e0e0;">
                <div class="cart-item-image" style="width: 80px; height: 80px; flex-shrink: 0;">
                    <img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                </div>
                <div class="cart-item-details" style="flex: 1;">
                    <div class="cart-item-name" style="font-weight: 600; color: #333; margin-bottom: 5px;">${item.name}</div>
                    <div class="cart-item-price" style="color: #00bac7; font-weight: 600; margin-bottom: 10px;">₹${item.price}</div>
                    <div class="cart-item-quantity" style="display: flex; align-items: center; gap: 10px;">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)" style="width: 30px; height: 30px; border: 1px solid #e0e0e0; background: white; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-minus" style="font-size: 10px;"></i>
                        </button>
                        <span class="qty-display" style="min-width: 30px; text-align: center; font-weight: 600;">${item.quantity}</span>
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)" style="width: 30px; height: 30px; border: 1px solid #e0e0e0; background: white; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-plus" style="font-size: 10px;"></i>
                        </button>
                        <button class="remove-item" onclick="removeFromCart(${item.id})" style="margin-left: auto; background: none; border: none; color: #ff6b6b; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItemsContainer.innerHTML = cartHTML;
    
    if (cartTotalElement) {
        cartTotalElement.textContent = `₹${total.toLocaleString()}`;
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
            saveCart();
            updateCartCount();
            displayCartItems();
        }
    }
}

// Remove from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    updateCartCount();
    displayCartItems();
}

// Proceed to checkout
function proceedToCheckout() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    // Check if user is logged in
    const savedUser = localStorage.getItem('visionkart_user');
    if (!savedUser) {
        alert('Please login to proceed with checkout');
        closeCartSidebar();
        // Redirect to homepage to login
        window.location.href = 'index.php';
        return;
    }
    
    // Close cart sidebar and redirect to checkout page
    closeCartSidebar();
    window.location.href = 'checkout.php';
}

// Find product by ID
async function findProductById(id) {
    try {
        // Fetch from database API
        const response = await fetch(`api_products.php?action=get&id=${id}`);
        if (response.ok) {
            const product = await response.json();
            if (product && !product.error) {
                console.log('✅ Found product in database:', product);
                // Map database fields to expected format
                return {
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    originalPrice: parseFloat(product.original_price) || parseFloat(product.price) * 2,
                    image: product.image,
                    category: product.subcategory || product.category,
                    type: product.category,
                    brand: product.brand,
                    frameType: product.frametype || 'Full Rim',
                    gender: 'Unisex',
                    rating: 4.5,
                    reviews: 0,
                    description: product.description,
                    video: product.video,
                    color: product.color,
                    hsn: product.hsn,
                    gst: product.gst,
                    stock: product.stock
                };
            }
        }
    } catch (error) {
        console.error('Error fetching product from API:', error);
    }
    
    // Fallback: check localStorage products
    const storedProducts = JSON.parse(localStorage.getItem('visionkart_products') || '[]');
    const deletedProductIds = JSON.parse(localStorage.getItem('visionkart_deleted_products') || '[]');
    
    // Filter out deleted products from localStorage
    const activeStoredProducts = storedProducts.filter(p => !deletedProductIds.includes(p.id));
    
    // Search in localStorage
    const storedProduct = activeStoredProducts.find(p => p.id == id);
    if (storedProduct) {
        console.log('✅ Found product in localStorage:', storedProduct);
        return storedProduct;
    }
    
    // Last fallback: search in hardcoded products
    const allProducts = [...products, ...roundProducts, ...catEyeProducts, ...clubmasterProducts, ...transparentProducts];
    const hardcodedProduct = allProducts.find(p => p.id == id);
    
    if (hardcodedProduct) {
        console.log('✅ Found product in hardcoded array:', hardcodedProduct);
    } else {
        console.error('❌ Product not found with ID:', id);
    }
    
    return hardcodedProduct;
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

// Load product details
async function loadProductDetails() {
    const productId = getProductIdFromURL();
    
    if (!productId) {
        window.location.href = 'index.php';
        return;
    }

    const product = await findProductById(productId);
    
    if (!product) {
        alert('Product not found!');
        window.location.href = 'index.php';
        return;
    }

    // Update page elements
    document.getElementById('productName').textContent = product.name;
    document.getElementById('productTitle').textContent = product.name;
    document.querySelector('.product-brand').textContent = product.brand || 'VisionKart';
    
    // Update price
    document.querySelector('.current-price').textContent = `₹${product.price.toLocaleString()}`;
    
    const originalPrice = product.originalPrice || product.price * 2;
    document.querySelector('.original-price').textContent = `₹${originalPrice.toLocaleString()}`;
    
    const discount = Math.round(((originalPrice - product.price) / originalPrice) * 100);
    document.querySelector('.discount-badge').textContent = `${discount}% OFF`;
    
    // Update rating
    if (product.rating) {
        document.querySelector('.rating-text').textContent = `${product.rating} ⭐ (${product.reviews || 0} reviews)`;
    }
    
    // Update images
    document.getElementById('mainProductImage').src = product.image;
    document.querySelector('.thumbnail-images .thumb').src = product.image;
    
    // Update subtitle - handle missing fields gracefully
    const frameType = product.frameType || 'Full Rim';
    const category = product.category || product.type || 'Eyeglasses';
    const gender = product.gender || 'Unisex';
    document.querySelector('.product-subtitle').textContent = `${frameType} • ${category} • ${gender}`;
    
    // Add to cart functionality
    document.querySelector('.add-to-cart-detail').addEventListener('click', function() {
        addToCart(product.id);
    });

    // Load related products
    loadRelatedProducts(product.category || product.type, product.id);
}

// Change main image
function changeImage(thumb) {
    const mainImg = document.getElementById('mainProductImage');
    mainImg.src = thumb.src.replace('w=100&h=100', 'w=600&h=600');
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

// Tab functionality
function openTab(evt, tabName) {
    // Hide all tab contents
    const tabContents = document.getElementsByClassName('tab-content');
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove('active');
    }
    
    // Remove active class from all tab buttons
    const tabBtns = document.getElementsByClassName('tab-btn');
    for (let i = 0; i < tabBtns.length; i++) {
        tabBtns[i].classList.remove('active');
    }
    
    // Show current tab and mark button as active
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}

// Initialize everything on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load cart first
    loadCart();
    
    // Load product details
    loadProductDetails();
    
    // Initialize cart
    updateCartCount();
    
    // Checkout button event listener
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', proceedToCheckout);
    }
    
    // Color selector - using event delegation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('color-btn')) {
            document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
        }
    });

    // Size selector - using event delegation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('size-btn')) {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            const selectedSizeEl = document.querySelector('.selected-size');
            if (selectedSizeEl) {
                selectedSizeEl.textContent = e.target.textContent;
            }
        }
    });

    // Try-On button
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('try-on-btn') || e.target.closest('.try-on-btn')) {
            e.preventDefault();
            openTryOnCamera();
        }
    });

    // Thumbnail images
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('thumb')) {
            changeImage(e.target);
        }
    });
});

// Load related products
function loadRelatedProducts(category, currentId) {
    let relatedProducts = [];
    
    // Get products from same category
    if (category === 'Round') {
        relatedProducts = roundProducts.filter(p => p.id !== currentId).slice(0, 4);
    } else if (category === 'Cat-Eye') {
        relatedProducts = catEyeProducts.filter(p => p.id !== currentId).slice(0, 4);
    } else if (category === 'Clubmaster') {
        relatedProducts = [...clubmasterProducts.filter(p => p.id !== currentId), ...products.slice(0, 3)].slice(0, 4);
    } else if (category === 'Transparent') {
        relatedProducts = [...transparentProducts.filter(p => p.id !== currentId), ...products.slice(0, 3)].slice(0, 4);
    } else {
        relatedProducts = products.filter(p => p.id !== currentId).slice(0, 4);
    }
    
    const relatedGrid = document.querySelector('.related-grid');
    relatedGrid.innerHTML = '';
    
    relatedProducts.forEach(product => {
        const productCard = `
            <div class="product-card">
                <a href="product-detail.php?id=${product.id}" style="text-decoration: none; color: inherit;">
                    <div class="product-image">
                        <img src="${product.image}" alt="${product.name}">
                        <div class="product-overlay">
                            <button class="quick-view-btn">Quick View</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>${product.name}</h3>
                        <p class="brand">${product.brand}</p>
                        <div class="price">
                            <span class="current">₹${product.price}</span>
                            <span class="original">₹${product.originalPrice}</span>
                        </div>
                    </div>
                </a>
            </div>
        `;
        relatedGrid.innerHTML += productCard;
    });
}

// Cart icon click
document.querySelector('.cart-icon').addEventListener('click', function(e) {
    e.preventDefault();
    openCart();
});

// Close cart
document.getElementById('closeCart').addEventListener('click', closeCartSidebar);
document.getElementById('continueShopping').addEventListener('click', closeCartSidebar);
document.getElementById('cartOverlay').addEventListener('click', closeCartSidebar);

// Open cart sidebar
async function openCart() {
    // Load fresh cart data from API
    await loadCart();
    displayCartItems();
    document.getElementById('cartSidebar').style.right = '0';
    document.getElementById('cartOverlay').style.opacity = '1';
    document.getElementById('cartOverlay').style.visibility = 'visible';
}

// Close cart sidebar
function closeCartSidebar() {
    document.getElementById('cartSidebar').style.right = '-400px';
    document.getElementById('cartOverlay').style.opacity = '0';
    document.getElementById('cartOverlay').style.visibility = 'hidden';
}

// Virtual Try-On Camera Functionality
let stream = null;
let tryOnModal = null;

function openTryOnCamera() {
    // Create modal if it doesn't exist
    if (!tryOnModal) {
        tryOnModal = document.createElement('div');
        tryOnModal.id = 'tryOnModal';
        tryOnModal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 10002;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        
        tryOnModal.innerHTML = `
            <div style="position: relative; max-width: 90%; max-height: 90%; background: white; border-radius: 15px; overflow: hidden;">
                <div style="position: relative;">
                    <video id="tryOnVideo" autoplay playsinline style="width: 100%; max-width: 640px; display: block; border-radius: 15px;"></video>
                    <canvas id="tryOnCanvas" style="display: none;"></canvas>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; opacity: 0.3;">
                        <img id="glassesOverlay" src="" style="width: 300px; height: auto;">
                    </div>
                </div>
                <div style="padding: 20px; text-align: center; background: white;">
                    <p style="margin-bottom: 15px; color: #333; font-size: 14px;">Position your face in the center to see how the glasses look</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button id="captureBtn" style="padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-camera"></i> Capture Photo
                        </button>
                        <button id="closeTryOnBtn" style="padding: 12px 30px; background: #ff6b6b; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(tryOnModal);
        
        // Set glasses overlay image
        const productImage = document.getElementById('mainProductImage').src;
        document.getElementById('glassesOverlay').src = productImage;
        
        // Event listeners
        document.getElementById('captureBtn').addEventListener('click', capturePhoto);
        document.getElementById('closeTryOnBtn').addEventListener('click', closeTryOnCamera);
    }
    
    // Show modal
    tryOnModal.style.display = 'flex';
    
    // Start camera
    startCamera();
}

function startCamera() {
    const video = document.getElementById('tryOnVideo');
    
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            width: { ideal: 640 },
            height: { ideal: 480 },
            facingMode: 'user'
        } 
    })
    .then(function(mediaStream) {
        stream = mediaStream;
        video.srcObject = stream;
    })
    .catch(function(err) {
        console.error('Camera error:', err);
        alert('Unable to access camera. Please make sure you have granted camera permissions.');
        closeTryOnCamera();
    });
}

function capturePhoto() {
    const video = document.getElementById('tryOnVideo');
    const canvas = document.getElementById('tryOnCanvas');
    const glassesOverlay = document.getElementById('glassesOverlay');
    
    // Set canvas size to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    const ctx = canvas.getContext('2d');
    
    // Draw video frame
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Draw glasses overlay on top
    const glassesWidth = canvas.width * 0.5;
    const glassesHeight = (glassesOverlay.naturalHeight / glassesOverlay.naturalWidth) * glassesWidth;
    const glassesX = (canvas.width - glassesWidth) / 2;
    const glassesY = (canvas.height - glassesHeight) / 2;
    
    ctx.drawImage(glassesOverlay, glassesX, glassesY, glassesWidth, glassesHeight);
    
    // Convert to image and download
    canvas.toBlob(function(blob) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'virtual-tryon-' + Date.now() + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        alert('Photo captured and saved!');
    });
}

function closeTryOnCamera() {
    // Stop camera stream
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    // Hide modal
    if (tryOnModal) {
        tryOnModal.style.display = 'none';
    }
}

// Add smooth scroll behavior
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading animation
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});
