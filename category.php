<?php
require_once 'config.php';
session_start();

// Get category from URL parameter
$shape = isset($_GET['shape']) ? strtolower(trim($_GET['shape'])) : '';
$category = isset($_GET['category']) ? strtolower(trim($_GET['category'])) : 'eyeglasses';

// Valid shapes
$validShapes = ['round', 'cat-eye', 'clubmaster', 'transparent', 'aviator', 'square', 'rectangle', 'oval', 'hexagonal', 'wayfarer'];

if (!in_array($shape, $validShapes)) {
    $shape = '';
}

// Category display info
$categoryInfo = [
    'round' => [
        'title' => 'ROUND EYEGLASSES FRAMES',
        'subtitle' => 'Timeless style from the retro era - Perfect for square and rectangular faces'
    ],
    'cat-eye' => [
        'title' => 'CAT-EYE EYEGLASSES FRAMES',
        'subtitle' => 'Elegant and feminine - Perfect for adding a vintage flair'
    ],
    'clubmaster' => [
        'title' => 'CLUBMASTER EYEGLASSES FRAMES',
        'subtitle' => 'Sophisticated retro style - Perfect for oval and round faces'
    ],
    'transparent' => [
        'title' => 'TRANSPARENT EYEGLASSES FRAMES',
        'subtitle' => 'Modern and minimalist - Perfect for any face shape'
    ],
    'aviator' => [
        'title' => 'AVIATOR EYEGLASSES FRAMES',
        'subtitle' => 'Classic pilot style - Perfect for oval and heart-shaped faces'
    ],
    'square' => [
        'title' => 'SQUARE EYEGLASSES FRAMES',
        'subtitle' => 'Bold and contemporary - Perfect for round and oval faces'
    ],
    'rectangle' => [
        'title' => 'RECTANGLE EYEGLASSES FRAMES',
        'subtitle' => 'Professional and classic - Perfect for round faces'
    ],
    'wayfarer' => [
        'title' => 'WAYFARER EYEGLASSES FRAMES',
        'subtitle' => 'Iconic and versatile - Perfect for any face shape'
    ]
];

$info = isset($categoryInfo[$shape]) ? $categoryInfo[$shape] : [
    'title' => strtoupper($shape) . ' EYEGLASSES FRAMES',
    'subtitle' => 'Find your perfect pair'
];

// Get filter values from URL
$filters = [
    'color' => isset($_GET['color']) ? $_GET['color'] : '',
    'brand' => isset($_GET['brand']) ? $_GET['brand'] : '',
    'frametype' => isset($_GET['frametype']) ? $_GET['frametype'] : '',
    'min_price' => isset($_GET['min_price']) ? $_GET['min_price'] : '',
    'max_price' => isset($_GET['max_price']) ? $_GET['max_price'] : '',
    'gender' => isset($_GET['gender']) ? $_GET['gender'] : '',
    'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest'
];

// Fetch products from database
function getFilteredProducts($shape, $filters = []) {
    $sql = "SELECT * FROM products WHERE status = 'active'";
    $params = [];
    
    // Filter by shape/frame type
    if ($shape) {
        $sql .= " AND (LOWER(subcategory) LIKE ? OR LOWER(frametype) LIKE ? OR LOWER(name) LIKE ?)";
        $searchTerm = '%' . $shape . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Filter by color
    if (!empty($filters['color'])) {
        $sql .= " AND LOWER(color) LIKE ?";
        $params[] = '%' . strtolower($filters['color']) . '%';
    }
    
    // Filter by brand
    if (!empty($filters['brand'])) {
        $sql .= " AND LOWER(brand) LIKE ?";
        $params[] = '%' . strtolower($filters['brand']) . '%';
    }
    
    // Filter by frame type
    if (!empty($filters['frametype'])) {
        $sql .= " AND LOWER(frametype) LIKE ?";
        $params[] = '%' . strtolower($filters['frametype']) . '%';
    }
    
    // Filter by price range
    if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
        $sql .= " AND price >= ?";
        $params[] = floatval($filters['min_price']);
    }
    
    if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
        $sql .= " AND price <= ?";
        $params[] = floatval($filters['max_price']);
    }
    
    // Filter by gender
    if (!empty($filters['gender'])) {
        $sql .= " AND LOWER(gender) = ?";
        $params[] = strtolower($filters['gender']);
    }
    
    // Sorting
    $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
    switch ($sort) {
        case 'price-low':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price-high':
            $sql .= " ORDER BY price DESC";
            break;
        case 'name':
            $sql .= " ORDER BY name ASC";
            break;
        default:
            $sql .= " ORDER BY created_at DESC";
    }
    
    return getRows($sql, $params);
}

$products = getFilteredProducts($shape, $filters);
$productCount = count($products);

// Get unique brands for filters
$allBrands = getRows("SELECT DISTINCT brand FROM products WHERE status = 'active' AND brand IS NOT NULL AND brand != '' ORDER BY brand");
$brands = array_column($allBrands, 'brand');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(ucfirst($shape)); ?> Eyeglasses - VisionKart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="round-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,186,199,0.2);
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-info { padding: 15px; }
        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 40px;
        }
        .product-brand { font-size: 12px; color: #666; margin-bottom: 8px; }
        .product-price { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .current-price { font-size: 18px; font-weight: bold; color: #00bac7; }
        .original-price { font-size: 14px; color: #999; text-decoration: line-through; }
        .discount-badge {
            position: absolute; top: 10px; left: 10px;
            background: #ff6b6b; color: white;
            padding: 4px 10px; border-radius: 4px;
            font-size: 12px; font-weight: 600;
        }
        .wishlist-btn {
            position: absolute; top: 10px; right: 10px;
            background: white; border: none;
            width: 35px; height: 35px; border-radius: 50%;
            cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s; color: #666;
        }
        .wishlist-btn:hover, .wishlist-btn.active { background: #ff6b6b; color: white; }
        .product-actions { padding: 0 15px 15px; }
        .btn-add-cart {
            width: 100%; padding: 10px;
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white; border: none; border-radius: 6px;
            font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 14px;
        }
        .btn-add-cart:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,186,199,0.3); }
        .no-products {
            grid-column: 1 / -1; text-align: center;
            padding: 60px 20px; background: #f9f9f9; border-radius: 12px;
        }
        .no-products i { font-size: 60px; color: #ddd; margin-bottom: 20px; }
        .no-products h3 { color: #666; margin-bottom: 10px; }
        .no-products p { color: #999; }
        .color-box {
            width: 18px; height: 18px; border-radius: 3px;
            border: 1px solid #ddd; display: inline-block;
        }
        .color-box.transparent { background: linear-gradient(135deg, #fff 50%, #f0f0f0 50%); }
        .color-box.gold { background: #FFD700; }
        .color-box.black { background: #000; }
        .color-box.brown { background: #8B4513; }
        .color-box.silver { background: #C0C0C0; }
        .color-box.blue { background: #4169E1; }
        .color-box.red { background: #DC143C; }
        .color-box.green { background: #228B22; }
        .active-filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
        .filter-tag {
            background: #e3f2fd; color: #00bac7;
            padding: 6px 12px; border-radius: 20px; font-size: 13px;
            display: flex; align-items: center; gap: 8px;
        }
        .filter-tag button {
            background: none; border: none; color: #00bac7;
            cursor: pointer; font-size: 16px; padding: 0; line-height: 1;
        }
        .filter-tag button:hover { color: #ff6b6b; }
    </style>
</head>
<body>
    <!-- Top Banner -->
    <div class="top-banner">
        <p>🎉 BUY 1 GET 1 FREE on all eyewear | Free Home Eye Test Available</p>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php" style="text-decoration: none; color: inherit;">VisionKart</a></h1>
                </div>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php#eyeglasses">EYEGLASSES</a></li>
                        <li><a href="index.php#sunglasses">SUNGLASSES</a></li>
                        <li><a href="index.php#contact-lenses">CONTACT LENSES</a></li>
                        <li><a href="index.php#kids">KIDS GLASSES</a></li>
                        <li><a href="index.php#services">SERVICES</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search eyeglasses...">
                        <i class="fas fa-search"></i>
                    </div>
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> / <a href="index.php#eyeglasses">Eyeglasses</a> / <span><?php echo htmlspecialchars(ucfirst($shape)); ?></span>
        </div>
    </div>

    <!-- Page Title -->
    <section class="page-title-section">
        <div class="container">
            <h1><?php echo htmlspecialchars($info['title']); ?></h1>
            <p class="subtitle"><?php echo htmlspecialchars($info['subtitle']); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="content-layout">
                <!-- Sidebar Filters -->
                <aside class="filter-sidebar">
                    <div class="filter-header">
                        <h3><i class="fas fa-filter"></i> FILTERS</h3>
                        <a href="category.php?shape=<?php echo htmlspecialchars($shape); ?>" class="clear-filters">Clear All</a>
                    </div>

                    <div class="filter-section">
                        <h4>FRAME TYPE</h4>
                        <div class="filter-options">
                            <label><input type="radio" name="frametype" value="full-rim" <?php echo $filters['frametype'] === 'full-rim' ? 'checked' : ''; ?> onchange="applyFilter('frametype', this.value)"> Full Rim</label>
                            <label><input type="radio" name="frametype" value="rimless" <?php echo $filters['frametype'] === 'rimless' ? 'checked' : ''; ?> onchange="applyFilter('frametype', this.value)"> Rimless</label>
                            <label><input type="radio" name="frametype" value="half-rim" <?php echo $filters['frametype'] === 'half-rim' ? 'checked' : ''; ?> onchange="applyFilter('frametype', this.value)"> Half Rim</label>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h4>FRAME COLOR</h4>
                        <div class="filter-options color-options">
                            <?php foreach (['transparent', 'gold', 'black', 'brown', 'silver', 'blue'] as $color): ?>
                            <label>
                                <input type="radio" name="color" value="<?php echo $color; ?>" <?php echo $filters['color'] === $color ? 'checked' : ''; ?> onchange="applyFilter('color', this.value)">
                                <span class="color-box <?php echo $color; ?>"></span> <?php echo ucfirst($color); ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h4>BRANDS</h4>
                        <div class="filter-options">
                            <?php foreach ($brands as $brand): ?>
                            <label>
                                <input type="radio" name="brand" value="<?php echo htmlspecialchars($brand); ?>" <?php echo $filters['brand'] === $brand ? 'checked' : ''; ?> onchange="applyFilter('brand', this.value)">
                                <?php echo htmlspecialchars($brand); ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h4>PRICE RANGE</h4>
                        <div class="filter-options">
                            <label><input type="radio" name="price_range" <?php echo ($filters['min_price'] == '0' && $filters['max_price'] == '1000') ? 'checked' : ''; ?> onchange="applyPriceFilter(0, 1000)"> Under ₹1000</label>
                            <label><input type="radio" name="price_range" <?php echo ($filters['min_price'] == '1000' && $filters['max_price'] == '2000') ? 'checked' : ''; ?> onchange="applyPriceFilter(1000, 2000)"> ₹1000 - ₹2000</label>
                            <label><input type="radio" name="price_range" <?php echo ($filters['min_price'] == '2000' && $filters['max_price'] == '3000') ? 'checked' : ''; ?> onchange="applyPriceFilter(2000, 3000)"> ₹2000 - ₹3000</label>
                            <label><input type="radio" name="price_range" <?php echo ($filters['min_price'] == '3000') ? 'checked' : ''; ?> onchange="applyPriceFilter(3000, 99999)"> Above ₹3000</label>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h4>GENDER</h4>
                        <div class="filter-options">
                            <label><input type="radio" name="gender" value="male" <?php echo $filters['gender'] === 'male' ? 'checked' : ''; ?> onchange="applyFilter('gender', this.value)"> Male</label>
                            <label><input type="radio" name="gender" value="female" <?php echo $filters['gender'] === 'female' ? 'checked' : ''; ?> onchange="applyFilter('gender', this.value)"> Female</label>
                            <label><input type="radio" name="gender" value="unisex" <?php echo $filters['gender'] === 'unisex' ? 'checked' : ''; ?> onchange="applyFilter('gender', this.value)"> Unisex</label>
                        </div>
                    </div>
                </aside>

                <!-- Products Area -->
                <div class="products-area">
                    <div class="products-header">
                        <div class="results-info">
                            Showing <span id="productCount"><?php echo $productCount; ?></span> Results
                            <?php if ($shape): ?> for <strong><?php echo htmlspecialchars(ucfirst($shape)); ?></strong><?php endif; ?>
                        </div>
                        <div class="sort-options">
                            <label>SORT BY:</label>
                            <select id="sortSelect" onchange="applyFilter('sort', this.value)">
                                <option value="newest" <?php echo $filters['sort'] === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="price-low" <?php echo $filters['sort'] === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price-high" <?php echo $filters['sort'] === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="name" <?php echo $filters['sort'] === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    <?php 
                    $hasActiveFilters = !empty($filters['color']) || !empty($filters['brand']) || !empty($filters['frametype']) || !empty($filters['gender']) || !empty($filters['min_price']);
                    if ($hasActiveFilters): 
                    ?>
                    <div class="active-filters">
                        <?php if (!empty($filters['color'])): ?>
                        <span class="filter-tag">Color: <?php echo ucfirst($filters['color']); ?> <button onclick="removeFilter('color')">&times;</button></span>
                        <?php endif; ?>
                        <?php if (!empty($filters['brand'])): ?>
                        <span class="filter-tag">Brand: <?php echo htmlspecialchars($filters['brand']); ?> <button onclick="removeFilter('brand')">&times;</button></span>
                        <?php endif; ?>
                        <?php if (!empty($filters['frametype'])): ?>
                        <span class="filter-tag">Frame: <?php echo ucfirst($filters['frametype']); ?> <button onclick="removeFilter('frametype')">&times;</button></span>
                        <?php endif; ?>
                        <?php if (!empty($filters['gender'])): ?>
                        <span class="filter-tag">Gender: <?php echo ucfirst($filters['gender']); ?> <button onclick="removeFilter('gender')">&times;</button></span>
                        <?php endif; ?>
                        <?php if (!empty($filters['min_price'])): ?>
                        <span class="filter-tag">Price: ₹<?php echo $filters['min_price']; ?>+ <button onclick="removeFilter('min_price'); removeFilter('max_price');">&times;</button></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Product Grid -->
                    <div class="round-product-grid" id="productGrid">
                        <?php if ($productCount > 0): ?>
                            <?php foreach ($products as $product): 
                                $discount = 0;
                                if (!empty($product['original_price']) && $product['original_price'] > $product['price']) {
                                    $discount = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                                }
                                $imageUrl = !empty($product['image']) ? $product['image'] : 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop';
                            ?>
                            <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                                <?php if ($discount > 0): ?>
                                <span class="discount-badge"><?php echo $discount; ?>% OFF</span>
                                <?php endif; ?>
                                <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop'">
                                </a>
                                <div class="product-info">
                                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                    </a>
                                    <?php if (!empty($product['brand'])): ?>
                                    <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                    <?php endif; ?>
                                    <div class="product-price">
                                        <span class="current-price">₹<?php echo number_format($product['price']); ?></span>
                                        <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <span class="original-price">₹<?php echo number_format($product['original_price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-products">
                                <i class="fas fa-glasses"></i>
                                <h3>No products found</h3>
                                <p>Try adjusting your filters or browse other categories</p>
                                <a href="category.php?shape=<?php echo htmlspecialchars($shape); ?>" style="display: inline-block; margin-top: 20px; padding: 12px 30px; text-decoration: none; background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%); color: white; border-radius: 8px;">Clear Filters</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Shapes Section -->
    <section style="padding: 60px 0; background: #f9f9f9;">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 40px; font-size: 28px; color: #333;">Explore Other Frame Shapes</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                <?php 
                $shapes = ['round' => 'Round', 'cat-eye' => 'Cat-Eye', 'clubmaster' => 'Clubmaster', 'transparent' => 'Transparent', 'aviator' => 'Aviator', 'square' => 'Square', 'wayfarer' => 'Wayfarer'];
                foreach ($shapes as $shapeKey => $shapeName):
                    if ($shapeKey === $shape) continue;
                ?>
                <a href="category.php?shape=<?php echo $shapeKey; ?>" style="background: white; padding: 25px; border-radius: 12px; text-align: center; text-decoration: none; color: #333; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <i class="fas fa-glasses fa-2x" style="color: #00bac7; margin-bottom: 15px;"></i>
                    <h3 style="font-size: 16px; margin: 0;"><?php echo $shapeName; ?></h3>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 VisionKart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="auth.js"></script>
    <script>
        const API_BASE = window.location.origin + '/vini';
        
        // Apply filter - updates URL and reloads page
        function applyFilter(filterName, filterValue) {
            const url = new URL(window.location.href);
            if (filterValue) {
                url.searchParams.set(filterName, filterValue);
            } else {
                url.searchParams.delete(filterName);
            }
            window.location.href = url.toString();
        }
        
        // Apply price filter
        function applyPriceFilter(min, max) {
            const url = new URL(window.location.href);
            url.searchParams.set('min_price', min);
            url.searchParams.set('max_price', max);
            window.location.href = url.toString();
        }
        
        // Remove a specific filter
        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        }
        
        // Add to cart
        async function addToCart(productId) {
            try {
                const response = await fetch(`${API_BASE}/api_cart.php?action=add`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
                });
                const data = await response.json();
                if (data.success) {
                    showNotification('Product added to cart!', 'success');
                    updateCartCount();
                } else {
                    showNotification(data.error || 'Failed to add to cart', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }
        
        // Toggle wishlist
        async function toggleWishlist(productId, btn) {
            try {
                const response = await fetch(`${API_BASE}/api_wishlist.php?action=toggle`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ product_id: productId })
                });
                const data = await response.json();
                if (data.success) {
                    const icon = btn.querySelector('i');
                    if (data.action === 'added') {
                        btn.classList.add('active');
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        showNotification('Added to wishlist!', 'success');
                    } else {
                        btn.classList.remove('active');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        showNotification('Removed from wishlist', 'success');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
        
        // Update cart count
        async function updateCartCount() {
            try {
                const response = await fetch(`${API_BASE}/api_cart.php?action=count`, { credentials: 'include' });
                const data = await response.json();
                document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.count || 0);
            } catch (error) { console.error('Error:', error); }
        }
        
        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `position: fixed; top: 100px; right: 20px; background: ${type === 'success' ? '#4caf50' : '#f44336'}; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 10000;`;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> ${message}`;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>
