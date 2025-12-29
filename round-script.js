// Round Eyeglasses Page JavaScript

// Round eyeglasses products data
const roundProducts = [
    {
        id: 101,
        name: "VisionKart Air Round",
        brand: "VisionKart Air",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 2548,
        badge: "BESTSELLER",
        frameType: "full-rim",
        color: "black",
        gender: "unisex",
        material: "metal",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop"
    },
    {
        id: 102,
        name: "Vincent Chase Classic Round",
        brand: "Vincent Chase",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 2391,
        badge: "TRENDING",
        frameType: "full-rim",
        color: "brown",
        gender: "male",
        material: "acetate",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop"
    },
    {
        id: 103,
        name: "John Jacobs Premium Round",
        brand: "John Jacobs",
        type: "Round Eyeglasses",
        price: 3900,
        originalPrice: 7800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 687,
        badge: "PREMIUM",
        frameType: "full-rim",
        color: "gold",
        gender: "unisex",
        material: "metal",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop"
    },
    {
        id: 104,
        name: "VisionKart Air Transparent",
        brand: "VisionKart Air",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 1668,
        badge: "NEW",
        frameType: "full-rim",
        color: "transparent",
        gender: "unisex",
        material: "plastic",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop"
    },
    {
        id: 105,
        name: "Vintage Wire Round",
        brand: "Vincent Chase",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.7,
        reviews: 1422,
        badge: "RETRO",
        frameType: "rimless",
        color: "silver",
        gender: "unisex",
        material: "metal",
        size: "Small",
        image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop"
    },
    {
        id: 106,
        name: "John Jacobs Bold Round",
        brand: "John Jacobs",
        type: "Round Eyeglasses",
        price: 3900,
        originalPrice: 7800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 565,
        badge: "HOT",
        frameType: "full-rim",
        color: "black",
        gender: "male",
        material: "acetate",
        size: "Large",
        image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop"
    },
    {
        id: 107,
        name: "VisionKart Air Pro Grip",
        brand: "VisionKart Air",
        type: "Round Eyeglasses",
        price: 2900,
        originalPrice: 5800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 462,
        badge: "BESTSELLER",
        frameType: "full-rim",
        color: "blue",
        gender: "unisex",
        material: "plastic",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop"
    },
    {
        id: 108,
        name: "Vincent Chase Blue Light",
        brand: "Vincent Chase",
        type: "Round Computer Glasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 1608,
        badge: "POPULAR",
        frameType: "full-rim",
        color: "brown",
        gender: "unisex",
        material: "acetate",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop"
    },
    {
        id: 109,
        name: "Retro Round Metal",
        brand: "VisionKart Air",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 1006,
        badge: "SALE",
        frameType: "full-rim",
        color: "gold",
        gender: "female",
        material: "metal",
        size: "Small",
        image: "https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop"
    },
    {
        id: 110,
        name: "John Jacobs Elegant",
        brand: "John Jacobs",
        type: "Round Eyeglasses",
        price: 3900,
        originalPrice: 7800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 423,
        badge: "PREMIUM",
        frameType: "half-rim",
        color: "silver",
        gender: "unisex",
        material: "metal",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=400&h=400&fit=crop"
    },
    {
        id: 111,
        name: "Vincent Chase Color Pop",
        brand: "Vincent Chase",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 1694,
        badge: "TRENDING",
        frameType: "full-rim",
        color: "blue",
        gender: "unisex",
        material: "plastic",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop"
    },
    {
        id: 112,
        name: "Classic Tortoise Round",
        brand: "Vincent Chase",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.7,
        reviews: 288,
        badge: "NEW",
        frameType: "full-rim",
        color: "brown",
        gender: "unisex",
        material: "acetate",
        size: "Large",
        image: "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop"
    },
    {
        id: 113,
        name: "VisionKart Air Slim",
        brand: "VisionKart Air",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 367,
        badge: "LIGHTWEIGHT",
        frameType: "full-rim",
        color: "transparent",
        gender: "female",
        material: "plastic",
        size: "Small",
        image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop"
    },
    {
        id: 114,
        name: "John Jacobs Luxury",
        brand: "John Jacobs",
        type: "Round Eyeglasses",
        price: 3900,
        originalPrice: 7800,
        discount: "50% OFF",
        rating: 4.9,
        reviews: 892,
        badge: "PREMIUM",
        frameType: "full-rim",
        color: "gold",
        gender: "male",
        material: "metal",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop"
    },
    {
        id: 115,
        name: "Vincent Chase Minimal",
        brand: "Vincent Chase",
        type: "Round Eyeglasses",
        price: 1900,
        originalPrice: 3800,
        discount: "50% OFF",
        rating: 4.8,
        reviews: 83,
        badge: "NEW ARRIVAL",
        frameType: "rimless",
        color: "silver",
        gender: "unisex",
        material: "metal",
        size: "Medium",
        image: "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop"
    }
];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadRoundProducts();
    setupFilters();
    setupSort();
    setupSearch();
});

// Load products into grid
function loadRoundProducts(productsToShow = roundProducts) {
    const productGrid = document.getElementById('roundProductGrid');
    productGrid.innerHTML = '';
    
    productsToShow.forEach(product => {
        const productCard = createRoundProductCard(product);
        productGrid.appendChild(productCard);
    });
    
    updateProductCount(productsToShow.length);
}

// Create product card
function createRoundProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.setAttribute('data-product-id', product.id);
    card.style.cursor = 'pointer';
    
    card.innerHTML = `
        <a href="product-detail.php?id=${product.id}" class="product-link">
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="eyeglass-img" style="display: none;">
                    <div class="eyeglass">
                        <div class="lens ${product.color}-frame"></div>
                        <div class="bridge"></div>
                        <div class="lens ${product.color}-frame"></div>
                        <div class="temple left"></div>
                        <div class="temple right"></div>
                    </div>
                </div>
                <span class="product-badge">${product.badge}</span>
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-type">${product.brand} • Size: ${product.size}</p>
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
function setupFilters() {
    const filterCheckboxes = document.querySelectorAll('.filter-options input[type="checkbox"]');
    
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
    
    const clearButton = document.querySelector('.clear-filters');
    clearButton.addEventListener('click', clearAllFilters);
}

// Apply filters
function applyFilters() {
    const selectedFilters = {
        frameType: [],
        color: [],
        brand: [],
        price: [],
        gender: [],
        material: []
    };
    
    // Collect selected filters
    document.querySelectorAll('.filter-options input[type="checkbox"]:checked').forEach(checkbox => {
        const filterType = checkbox.name;
        const filterValue = checkbox.value;
        if (selectedFilters[filterType]) {
            selectedFilters[filterType].push(filterValue);
        }
    });
    
    // Filter products
    let filteredProducts = roundProducts.filter(product => {
        // Frame Type filter
        if (selectedFilters.frameType.length > 0 && !selectedFilters.frameType.includes(product.frameType)) {
            return false;
        }
        
        // Color filter
        if (selectedFilters.color.length > 0 && !selectedFilters.color.includes(product.color)) {
            return false;
        }
        
        // Brand filter
        if (selectedFilters.brand.length > 0) {
            const brandMatch = selectedFilters.brand.some(brand => 
                product.brand.toLowerCase().includes(brand.replace('-', ' '))
            );
            if (!brandMatch) return false;
        }
        
        // Price filter
        if (selectedFilters.price.length > 0) {
            const priceMatch = selectedFilters.price.some(range => {
                if (range === '0-1000') return product.price < 1000;
                if (range === '1000-2000') return product.price >= 1000 && product.price < 2000;
                if (range === '2000-3000') return product.price >= 2000 && product.price < 3000;
                if (range === '3000+') return product.price >= 3000;
                return false;
            });
            if (!priceMatch) return false;
        }
        
        // Gender filter
        if (selectedFilters.gender.length > 0 && !selectedFilters.gender.includes(product.gender)) {
            return false;
        }
        
        // Material filter
        if (selectedFilters.material.length > 0 && !selectedFilters.material.includes(product.material)) {
            return false;
        }
        
        return true;
    });
    
    loadRoundProducts(filteredProducts);
}

// Clear all filters
function clearAllFilters() {
    document.querySelectorAll('.filter-options input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    loadRoundProducts(roundProducts);
}

// Setup sort
function setupSort() {
    const sortSelect = document.getElementById('sortSelect');
    sortSelect.addEventListener('change', function() {
        sortProducts(this.value);
    });
}

// Sort products
function sortProducts(sortBy) {
    let sortedProducts = [...roundProducts];
    
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
    
    loadRoundProducts(sortedProducts);
}

// Setup search
function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        searchProducts(this.value);
    });
}

// Search products
function searchProducts(searchTerm) {
    const filtered = roundProducts.filter(product => {
        const searchLower = searchTerm.toLowerCase();
        return product.name.toLowerCase().includes(searchLower) ||
               product.brand.toLowerCase().includes(searchLower) ||
               product.type.toLowerCase().includes(searchLower);
    });
    
    loadRoundProducts(filtered);
}

// Update product count
function updateProductCount(count) {
    document.getElementById('productCount').textContent = count;
}

// Load more button
document.querySelector('.load-more button')?.addEventListener('click', function() {
    showNotification('More products loaded!', 'success');
});

console.log('Round Eyeglasses page loaded with ' + roundProducts.length + ' products! 🔵');
