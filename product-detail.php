<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - VisionKart</title>
    <link rel="stylesheet" href="style.css?v=2">
    <link rel="stylesheet" href="product-detail.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-info">
                    <span><i class="fas fa-phone"></i> 1800-123-4567</span>
                    <span><i class="fas fa-envelope"></i> support@visionkart.com</span>
                    <span class="header-right">
                        <a href="#">Track Order</a>
                        <a href="#">Store Locator</a>
                    </span>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="index.php">
                            <h1>VisionKart</h1>
                        </a>
                    </div>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="index.php">HOME</a></li>
                            <li><a href="#">EYEGLASSES</a></li>
                            <li><a href="#">SUNGLASSES</a></li>
                            <li><a href="#">CONTACT LENSES</a></li>
                            <li><a href="#">HOME EYE TEST</a></li>
                        </ul>
                    </nav>
                    <div class="header-actions">
                        <div class="search-box">
                            <input type="text" placeholder="Search for products...">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="action-icons">
                            <a href="#" class="icon-link"><i class="fas fa-phone"></i></a>
                            <a href="#" class="icon-link"><i class="fas fa-user"></i></a>
                            <a href="#" class="icon-link"><i class="fas fa-heart"></i></a>
                            <a href="#" class="icon-link cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count">0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <section class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> / <a href="#">Eyeglasses</a> / <span id="productName">Vincent Chase Classic Round</span>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail-grid">
                <!-- Product Images -->
                <div class="product-images">
                    <div class="main-image">
                        <img id="mainProductImage" src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=600&h=600&fit=crop" alt="Product">
                        <button class="try-on-btn">
                            <i class="fas fa-camera"></i> Try On
                        </button>
                    </div>
                    <div class="thumbnail-images">
                        <img src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=100&h=100&fit=crop" class="thumb active">
                        <img src="https://images.unsplash.com/photo-1577803645773-f96470509666?w=100&h=100&fit=crop" class="thumb">
                        <img src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=100&h=100&fit=crop" class="thumb">
                        <img src="https://images.unsplash.com/photo-1580836021208-307460e21b2e?w=100&h=100&fit=crop" class="thumb">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info-detail">
                    <div class="product-brand">Vincent Chase</div>
                    <h1 class="product-title" id="productTitle">Classic Round Eyeglasses</h1>
                    <p class="product-subtitle">Full Rim • Round • Unisex</p>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-text">4.5 <i class="fas fa-star"></i> (234 reviews)</span>
                    </div>

                    <div class="product-price-section">
                        <div class="price-info">
                            <span class="current-price">₹1,499</span>
                            <span class="original-price">₹2,999</span>
                            <span class="discount-badge">50% OFF</span>
                        </div>
                        <p class="tax-info">inclusive of all taxes</p>
                    </div>

                    <!-- Product Options -->
                    <div class="product-options">
                        <div class="option-group">
                            <label>Frame Color:</label>
                            <div class="color-selector">
                                <button class="color-btn active" style="background: #000;" data-color="Black"></button>
                                <button class="color-btn" style="background: #8B4513;" data-color="Brown"></button>
                                <button class="color-btn" style="background: #0066CC;" data-color="Blue"></button>
                                <button class="color-btn" style="background: #FFD700;" data-color="Gold"></button>
                            </div>
                        </div>

                        <div class="option-group">
                            <label>Frame Size: <span class="selected-size">Medium</span></label>
                            <div class="size-selector">
                                <button class="size-btn">Small</button>
                                <button class="size-btn active">Medium</button>
                                <button class="size-btn">Large</button>
                            </div>
                            <a href="#" class="size-guide-link"><i class="fas fa-ruler"></i> Size Guide</a>
                        </div>

                        <div class="option-group">
                            <label>Lens Type:</label>
                            <select class="lens-select">
                                <option value="single">Single Vision</option>
                                <option value="bifocal">Bifocal</option>
                                <option value="progressive">Progressive</option>
                                <option value="blue">Blue Light Blocking</option>
                            </select>
                        </div>
                    </div>

                    <!-- Add to Cart Actions -->
                    <div class="product-actions-detail">
                        <button class="btn-primary add-to-cart-detail">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn-secondary wishlist-detail">
                            <i class="fas fa-heart"></i> Wishlist
                        </button>
                    </div>

                    <!-- Offers -->
                    <div class="offers-section">
                        <h3><i class="fas fa-tag"></i> Available Offers</h3>
                        <ul class="offers-list">
                            <li><i class="fas fa-gift"></i> Buy 1 Get 1 Free with Gold Membership</li>
                            <li><i class="fas fa-percent"></i> Extra 10% off on purchase above ₹3000</li>
                            <li><i class="fas fa-truck"></i> Free Home Delivery</li>
                            <li><i class="fas fa-shield-alt"></i> 14 Days Return Policy</li>
                        </ul>
                    </div>

                    <!-- Delivery Options -->
                    <div class="delivery-section">
                        <h3>Check Delivery Options</h3>
                        <div class="pincode-checker">
                            <input type="text" placeholder="Enter Pincode" maxlength="6">
                            <button class="btn-check">Check</button>
                        </div>
                        <p class="delivery-info"><i class="fas fa-truck"></i> Usually delivered in 3-5 business days</p>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="product-tabs">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="openTab(event, 'specifications')">Specifications</button>
                    <button class="tab-btn" onclick="openTab(event, 'description')">Description</button>
                    <button class="tab-btn" onclick="openTab(event, 'reviews')">Reviews (234)</button>
                </div>

                <div id="specifications" class="tab-content active">
                    <h3>Technical Information</h3>
                    <table class="specs-table">
                        <tr>
                            <td>Product ID</td>
                            <td>215394</td>
                        </tr>
                        <tr>
                            <td>Model No.</td>
                            <td>VC E16668</td>
                        </tr>
                        <tr>
                            <td>Frame Size</td>
                            <td>Medium</td>
                        </tr>
                        <tr>
                            <td>Frame Width</td>
                            <td>137 mm</td>
                        </tr>
                        <tr>
                            <td>Frame Dimensions</td>
                            <td>54-14-140</td>
                        </tr>
                        <tr>
                            <td>Frame Type</td>
                            <td>Full Rim</td>
                        </tr>
                        <tr>
                            <td>Frame Shape</td>
                            <td>Round</td>
                        </tr>
                        <tr>
                            <td>Frame Material</td>
                            <td>Acetate</td>
                        </tr>
                        <tr>
                            <td>Frame Color</td>
                            <td>Black</td>
                        </tr>
                        <tr>
                            <td>Suitable For</td>
                            <td>Men & Women</td>
                        </tr>
                        <tr>
                            <td>Weight</td>
                            <td>18 grams</td>
                        </tr>
                        <tr>
                            <td>Warranty</td>
                            <td>1 Year Manufacturer Warranty</td>
                        </tr>
                    </table>
                </div>

                <div id="description" class="tab-content">
                    <h3>Product Description</h3>
                    <p>Introducing the Vincent Chase Classic Round Eyeglasses - a perfect blend of timeless style and modern comfort. These full-rim round frames are crafted from premium acetate material, ensuring durability and a lightweight feel.</p>
                    <p>The classic round shape suits various face types and adds a sophisticated touch to any outfit. Whether you're at work or leisure, these eyeglasses provide the perfect combination of functionality and fashion.</p>
                    <h4>Key Features:</h4>
                    <ul>
                        <li>Premium Acetate Construction</li>
                        <li>Lightweight and Comfortable</li>
                        <li>Unisex Design</li>
                        <li>Scratch-Resistant Coating</li>
                        <li>UV Protection</li>
                        <li>Adjustable Nose Pads</li>
                        <li>Spring Hinges for Flexibility</li>
                    </ul>
                </div>

                <div id="reviews" class="tab-content">
                    <div class="reviews-summary">
                        <div class="rating-overview">
                            <div class="rating-score">
                                <span class="big-rating">4.5</span>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p>Based on 234 reviews</p>
                            </div>
                            <div class="rating-bars">
                                <div class="rating-bar-item">
                                    <span>5 <i class="fas fa-star"></i></span>
                                    <div class="bar"><div class="bar-fill" style="width: 70%;"></div></div>
                                    <span>164</span>
                                </div>
                                <div class="rating-bar-item">
                                    <span>4 <i class="fas fa-star"></i></span>
                                    <div class="bar"><div class="bar-fill" style="width: 20%;"></div></div>
                                    <span>47</span>
                                </div>
                                <div class="rating-bar-item">
                                    <span>3 <i class="fas fa-star"></i></span>
                                    <div class="bar"><div class="bar-fill" style="width: 6%;"></div></div>
                                    <span>14</span>
                                </div>
                                <div class="rating-bar-item">
                                    <span>2 <i class="fas fa-star"></i></span>
                                    <div class="bar"><div class="bar-fill" style="width: 3%;"></div></div>
                                    <span>7</span>
                                </div>
                                <div class="rating-bar-item">
                                    <span>1 <i class="fas fa-star"></i></span>
                                    <div class="bar"><div class="bar-fill" style="width: 1%;"></div></div>
                                    <span>2</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-list">
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <span class="reviewer-name">Rajesh Kumar</span>
                                    <div class="review-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <span class="review-date">2 days ago</span>
                            </div>
                            <p class="review-text">Excellent quality frames! Very comfortable to wear for long hours. The build quality is top-notch and the design is classic. Highly recommended!</p>
                        </div>

                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <span class="reviewer-name">Priya Sharma</span>
                                    <div class="review-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <span class="review-date">1 week ago</span>
                            </div>
                            <p class="review-text">Good product. The frames are stylish and fit well. Only minor issue is that they feel a bit loose, but overall satisfied with the purchase.</p>
                        </div>

                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <span class="reviewer-name">Amit Patel</span>
                                    <div class="review-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <span class="review-date">2 weeks ago</span>
                            </div>
                            <p class="review-text">Perfect! Exactly what I was looking for. The round shape suits my face perfectly and the quality is excellent. Worth every penny!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="related-products">
                <h2 class="section-title">Similar Products</h2>
                <div class="related-grid">
                    <!-- Product cards will be loaded here via JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#">Store Locator</a></li>
                        <li><a href="#">Buying Guide</a></li>
                        <li><a href="#">Frame Size</a></li>
                        <li><a href="#">Track Order</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>About Us</h4>
                    <ul>
                        <li><a href="#">We Are Hiring</a></li>
                        <li><a href="#">Refer And Earn</a></li>
                        <li><a href="#">Coupons</a></li>
                        <li><a href="#">About Company</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Help</h4>
                    <ul>
                        <li><a href="#">FAQ's</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 VisionKart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Cart Sidebar (same as index.php) -->
    <div class="cart-sidebar" id="cartSidebar" style="position: fixed; top: 0; right: -400px; width: 400px; height: 100vh; background: white; box-shadow: -2px 0 10px rgba(0,0,0,0.3); z-index: 10001; transition: right 0.3s ease;">
        <div class="cart-header" style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333; font-size: 22px;">Shopping Cart</h3>
            <button class="close-cart" id="closeCart" style="background: none; border: none; font-size: 24px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cart-items" id="cartItems" style="flex: 1; overflow-y: auto; padding: 20px;">
            <!-- Cart items will be dynamically loaded here -->
        </div>
        <div class="cart-footer" style="padding: 20px; border-top: 2px solid #e0e0e0; background: #f9f9f9;">
            <div class="cart-total" style="display: flex; justify-content: space-between; font-size: 20px; font-weight: 700; margin-bottom: 15px;">
                <span>Total:</span>
                <span class="total-amount" id="cartTotal">₹0</span>
            </div>
            <button class="btn-primary checkout-btn" style="width: 100%; margin-bottom: 10px;">Proceed to Checkout</button>
            <button class="btn-secondary continue-shopping" id="continueShopping" style="width: 100%;">Continue Shopping</button>
        </div>
    </div>
    <div class="cart-overlay" id="cartOverlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; opacity: 0; visibility: hidden; transition: all 0.3s;"></div>

    <script src="product-detail.js?v=3"></script>
</body>
</html>
