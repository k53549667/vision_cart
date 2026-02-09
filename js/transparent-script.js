// Transparent eyeglasses products data
const transparentProducts = [
    {
        id: 401,
        name: "OJOS Clear Round",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.8,
        reviews: 145,
        badge: "BESTSELLER",
        frameType: "full-rim",
        shape: "round",
        color: "transparent",
        gender: "unisex",
        material: "acetate",
        size: "medium",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 402,
        name: "Vincent Chase Clear Cat-Eye",
        brand: "Vincent Chase",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 267,
        badge: "TRENDING",
        frameType: "full-rim",
        shape: "cat-eye",
        color: "transparent",
        gender: "female",
        material: "acetate",
        size: "medium",
        image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 403,
        name: "OJOS Geometric Clear",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 5.0,
        reviews: 82,
        badge: "NEW",
        frameType: "full-rim",
        shape: "geometric",
        color: "transparent",
        gender: "unisex",
        material: "tr90",
        size: "wide",
        image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 404,
        name: "VisionKart Air Clear",
        brand: "VisionKart Air",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 312,
        badge: "POPULAR",
        frameType: "full-rim",
        shape: "square",
        color: "transparent",
        gender: "male",
        material: "plastic",
        size: "medium",
        image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 405,
        name: "OJOS Clear Rectangle",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.6,
        reviews: 198,
        badge: "SALE",
        frameType: "full-rim",
        shape: "rectangle",
        color: "transparent",
        gender: "unisex",
        material: "acetate",
        size: "extra-wide",
        image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 406,
        name: "Vincent Chase Clear Blue Tint",
        brand: "Vincent Chase",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 223,
        badge: "TRENDING",
        frameType: "full-rim",
        shape: "round",
        color: "clear-blue",
        gender: "unisex",
        material: "acetate",
        size: "medium",
        image: "https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 407,
        name: "OJOS Clear Pink Round",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.7,
        reviews: 156,
        badge: "HOT",
        frameType: "full-rim",
        shape: "round",
        color: "clear-pink",
        gender: "female",
        material: "plastic",
        size: "medium",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 408,
        name: "VisionKart Air Clear Wayfarer",
        brand: "VisionKart Air",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 445,
        badge: "BESTSELLER",
        frameType: "full-rim",
        shape: "wayfarer",
        color: "transparent",
        gender: "unisex",
        material: "acetate",
        size: "wide",
        image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 409,
        name: "OJOS Clear Green Tint",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.7,
        reviews: 89,
        badge: "NEW",
        frameType: "full-rim",
        shape: "geometric",
        color: "clear-green",
        gender: "unisex",
        material: "tr90",
        size: "narrow",
        image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 410,
        name: "Vincent Chase Clear Square",
        brand: "Vincent Chase",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 334,
        badge: "POPULAR",
        frameType: "full-rim",
        shape: "square",
        color: "transparent",
        gender: "male",
        material: "acetate",
        size: "wide",
        image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 411,
        name: "OJOS Clear Purple Tint",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.9,
        reviews: 112,
        badge: "TRENDING",
        frameType: "full-rim",
        shape: "cat-eye",
        color: "clear-purple",
        gender: "female",
        material: "plastic",
        size: "medium",
        image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 412,
        name: "VisionKart Air Clear Rimless",
        brand: "VisionKart Air",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 278,
        badge: "PREMIUM",
        frameType: "rimless",
        shape: "rectangle",
        color: "transparent",
        gender: "unisex",
        material: "plastic",
        size: "medium",
        image: "https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 413,
        name: "OJOS Clear Oversized",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.6,
        reviews: 167,
        badge: "SALE",
        frameType: "full-rim",
        shape: "round",
        color: "transparent",
        gender: "female",
        material: "acetate",
        size: "extra-wide",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 414,
        name: "Vincent Chase Clear Geometric",
        brand: "Vincent Chase",
        type: "Transparent Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 201,
        badge: "HOT",
        frameType: "full-rim",
        shape: "geometric",
        color: "transparent",
        gender: "unisex",
        material: "tr90",
        size: "medium",
        image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop&q=80"
    },
    {
        id: 415,
        name: "OJOS Clear Half-Rim",
        brand: "OJOS",
        type: "Transparent Eyeglasses",
        price: 750,
        originalPrice: 2000,
        discount: "63% OFF",
        rating: 4.8,
        reviews: 143,
        badge: "NEW",
        frameType: "half-rim",
        shape: "rectangle",
        color: "transparent",
        gender: "male",
        material: "plastic",
        size: "wide",
        image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop&q=80"
    }
];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadTransparentProducts();
    setupTransparentFilters();
    setupTransparentSort();
    setupTransparentSearch();
});

// Load products into grid
function loadTransparentProducts(productsToShow = transparentProducts) {
    const productGrid = document.getElementById('transparentProductGrid');
    
    if (!productGrid) {
        return;
    }
    
    productGrid.innerHTML = '';
    
    productsToShow.forEach(product => {
        const productCard = createTransparentProductCard(product);
        productGrid.appendChild(productCard);
    });
    
    updateTransparentProductCount(productsToShow.length);
}

// Create product card
function createTransparentProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.setAttribute('data-product-id', product.id);
    card.style.cursor = 'pointer';
    
    const sizeDisplay = product.size.charAt(0).toUpperCase() + product.size.slice(1).replace('-', ' ');
    
    card.innerHTML = `
        <a href="product-detail.php?id=${product.id}" class="product-link">
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="eyeglass-img" style="display: none;">
                    <div class="eyeglass">
                        <div class="lens ${product.shape} transparent-frame"></div>
                        <div class="bridge"></div>
                        <div class="lens ${product.shape} transparent-frame"></div>
                        <div class="temple left"></div>
                        <div class="temple right"></div>
                    </div>
                </div>
                <span class="product-badge">${product.badge}</span>
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-type">${product.brand} • Size: ${sizeDisplay}</p>
                <div class="product-rating">
                    <span class="stars">${generateStars(product.rating)}</span>
                    <span class="rating-count">${product.rating} <i class="fas fa-star" style="font-size: 11px; margin: 0 2px;"></i> (${product.reviews})</span>
                </div>
                <div class="product-price">
                    <span class="current-price">₹${product.price}</span>
                    <span class="original-price">₹${product.originalPrice}</span>
                </div>
                <div class="discount" style="color: var(--accent-color); font-weight: 600; margin-bottom: 10px;">${product.discount}</div>
                <p style="font-size: 13px; color: var(--dark-gray); margin-bottom: 12px;">Buy 1 Get 1 Free with Gold Membership</p>
            </div>
        </a>
        <div class="product-actions">
            <button class="add-to-cart" onclick="event.stopPropagation(); addToCart(${product.id})">
                <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>
            <button class="wishlist-btn" onclick="event.stopPropagation(); addToWishlist(${product.id})">
                <i class="fas fa-heart"></i>
            </button>
        </div>
    `;
    
    return card;
}

// Generate stars
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

// Setup filters
function setupTransparentFilters() {
    const filterCheckboxes = document.querySelectorAll('.filter-options input[type="checkbox"]');
    
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', applyTransparentFilters);
    });
    
    const clearButton = document.querySelector('.clear-filters');
    if (clearButton) {
        clearButton.addEventListener('click', clearTransparentFilters);
    }
}

// Apply filters
function applyTransparentFilters() {
    const selectedFilters = {
        frameType: [],
        shape: [],
        color: [],
        brand: [],
        price: [],
        size: [],
        gender: [],
        material: []
    };
    
    document.querySelectorAll('.filter-options input[type="checkbox"]:checked').forEach(checkbox => {
        const filterType = checkbox.name;
        const filterValue = checkbox.value;
        if (selectedFilters[filterType]) {
            selectedFilters[filterType].push(filterValue);
        }
    });
    
    let filteredProducts = transparentProducts.filter(product => {
        if (selectedFilters.frameType.length > 0 && !selectedFilters.frameType.includes(product.frameType)) {
            return false;
        }
        
        if (selectedFilters.shape.length > 0 && !selectedFilters.shape.includes(product.shape)) {
            return false;
        }
        
        if (selectedFilters.color.length > 0 && !selectedFilters.color.includes(product.color)) {
            return false;
        }
        
        if (selectedFilters.brand.length > 0) {
            const brandMatch = selectedFilters.brand.some(brand => 
                product.brand.toLowerCase().includes(brand.replace('-', ' '))
            );
            if (!brandMatch) return false;
        }
        
        if (selectedFilters.price.length > 0) {
            const priceMatch = selectedFilters.price.some(range => {
                if (range === '0-1000') return product.price < 1000;
                if (range === '1000-2000') return product.price >= 1000 && product.price < 2000;
                if (range === '2000-3000') return product.price >= 2000 && product.price < 3000;
                return false;
            });
            if (!priceMatch) return false;
        }
        
        if (selectedFilters.size.length > 0 && !selectedFilters.size.includes(product.size)) {
            return false;
        }
        
        if (selectedFilters.gender.length > 0 && !selectedFilters.gender.includes(product.gender)) {
            return false;
        }
        
        if (selectedFilters.material.length > 0 && !selectedFilters.material.includes(product.material)) {
            return false;
        }
        
        return true;
    });
    
    loadTransparentProducts(filteredProducts);
}

// Clear all filters
function clearTransparentFilters() {
    document.querySelectorAll('.filter-options input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    loadTransparentProducts(transparentProducts);
}

// Setup sort
function setupTransparentSort() {
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortTransparentProducts(this.value);
        });
    }
}

// Sort products
function sortTransparentProducts(sortBy) {
    let sortedProducts = [...transparentProducts];
    
    switch(sortBy) {
        case 'price-low':
            sortedProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            sortedProducts.sort((a, b) => b.price - a.price);
            break;
        case 'rating':
            sortedProducts.sort((a, b) => b.rating - a.rating);
            break;
        case 'newest':
            sortedProducts.reverse();
            break;
        default:
            sortedProducts.sort((a, b) => b.reviews - a.reviews);
    }
    
    loadTransparentProducts(sortedProducts);
}

// Setup search
function setupTransparentSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchTransparentProducts(this.value);
        });
    }
}

// Search products
function searchTransparentProducts(searchTerm) {
    const filtered = transparentProducts.filter(product => {
        const searchLower = searchTerm.toLowerCase();
        return product.name.toLowerCase().includes(searchLower) ||
               product.brand.toLowerCase().includes(searchLower) ||
               product.type.toLowerCase().includes(searchLower);
    });
    
    loadTransparentProducts(filtered);
}

// Update product count
function updateTransparentProductCount(count) {
    const productCountElement = document.getElementById('productCount');
    if (productCountElement) {
        productCountElement.textContent = count;
    }
}

// Load more button
const loadMoreBtn = document.querySelector('.load-more button');
if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        showNotification('More products loaded!', 'success');
    });
}

console.log('Transparent Eyeglasses page loaded with ' + transparentProducts.length + ' products! ✨');
