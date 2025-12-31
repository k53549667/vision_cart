<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VisionKart - Premium Eyewear & Sunglasses Online</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* User dropdown menu styles */
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            padding: 10px 0;
            margin-top: 10px;
            display: none;
            z-index: 1000;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }

        .user-dropdown a:hover {
            background: #f5f5f5;
            color: #00bac7;
        }

        .user-dropdown a i {
            margin-right: 10px;
            width: 20px;
        }

        .user-dropdown hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #e0e0e0;
        }

        #loginBtn {
            position: relative;
        }

        #loginBtn span {
            margin-left: 5px;
            font-size: 14px;
        }

        .signup-btn {
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin-left: 10px;
        }

        .signup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 186, 199, 0.3);
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .signup-btn {
                padding: 6px 15px;
                font-size: 12px;
            }
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
                    <h1>VisionKart</h1>
                </div>
                
                <nav class="nav-menu">
                    <ul>
                        <li><a href="#eyeglasses">EYEGLASSES</a></li>
                        <li><a href="#sunglasses">SUNGLASSES</a></li>
                        <li><a href="#contact-lenses">CONTACT LENSES</a></li>
                        <li><a href="#kids">KIDS GLASSES</a></li>
                        <li><a href="#services">SERVICES</a></li>
                    </ul>
                </nav>

                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search for products...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="action-icons">
                        <a href="#" class="icon-link"><i class="fas fa-phone"></i></a>
                        <div class="auth-buttons" id="authContainer">
                            <a href="login.php" class="icon-link" id="loginBtn" title="Login"><i class="fas fa-user"></i></a>
                            <a href="login.php" class="signup-btn" id="signupBtn">Sign In</a>
                        </div>
                        <a href="#" class="icon-link wishlist-icon" id="wishlistBtn">
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-slider">
            <div class="hero-slide active">
                <div class="container">
                    <div class="hero-content">
                        <h2>WEAR THE TREND</h2>
                        <h3>New Collection 2024</h3>
                        <p>Discover the latest eyewear styles</p>
                        <button class="btn-primary">Shop Now</button>
                    </div>
                    <div class="hero-image">
                        <div class="placeholder-img hero-placeholder">
                            <div class="eyeglass-img">
                                <div class="eyeglass">
                                    <div class="lens wayfarer" style="width: 120px; height: 100px;"></div>
                                    <div class="bridge" style="width: 20px;"></div>
                                    <div class="lens wayfarer" style="width: 120px; height: 100px;"></div>
                                    <div class="temple left" style="width: 60px;"></div>
                                    <div class="temple right" style="width: 60px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Icons -->
    <section class="category-icons">
        <div class="container">
            <div class="icon-grid">
                <div class="icon-item">
                    <div class="icon-circle">
                        <i class="fas fa-glasses"></i>
                    </div>
                    <p>Eyeglasses</p>
                </div>
                <div class="icon-item">
                    <div class="icon-circle">
                        <i class="fas fa-eye"></i>
                    </div>
                    <p>Zero Power</p>
                </div>
                <div class="icon-item">
                    <div class="icon-circle">
                        <i class="fas fa-circle"></i>
                    </div>
                    <p>Contact Lenses</p>
                </div>
                <div class="icon-item">
                    <div class="icon-circle">
                        <i class="fas fa-child"></i>
                    </div>
                    <p>Kids Glasses</p>
                </div>
                <div class="icon-item">
                    <div class="icon-circle">
                        <i class="fas fa-sun"></i>
                    </div>
                    <p>Sunglasses</p>
                </div>
                <div class="icon-item">
                    <div class="icon-circle">
                        <i class="fas fa-align-center"></i>
                    </div>
                    <p>Progressive</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="featured-categories">
        <div class="container">
            <h2 class="section-title">WEAR THE TREND</h2>
            <p class="section-subtitle">Our hottest collections</p>
            
            <div class="category-grid">
                <div class="category-card" onclick="window.location.href='category.php?shape=round'">
                    <div class="placeholder-img category-placeholder">
                        <div class="eyeglass-img">
                            <div class="eyeglass">
                                <div class="lens" style="width: 60px; height: 60px;"></div>
                                <div class="bridge"></div>
                                <div class="lens" style="width: 60px; height: 60px;"></div>
                                <div class="temple left" style="width: 35px;"></div>
                                <div class="temple right" style="width: 35px;"></div>
                            </div>
                        </div>
                    </div>
                    <h3>Round</h3>
                    <a href="category.php?shape=round" class="explore-link">Explore</a>
                </div>
                <div class="category-card" onclick="window.location.href='category.php?shape=cat-eye'">
                    <div class="placeholder-img category-placeholder">
                        <div class="eyeglass-img">
                            <div class="eyeglass">
                                <div class="lens cat-eye" style="width: 60px; height: 55px;"></div>
                                <div class="bridge"></div>
                                <div class="lens cat-eye" style="width: 60px; height: 55px;"></div>
                                <div class="temple left" style="width: 35px;"></div>
                                <div class="temple right" style="width: 35px;"></div>
                            </div>
                        </div>
                    </div>
                    <h3>Cat-Eye</h3>
                    <a href="category.php?shape=cat-eye" class="explore-link">Explore</a>
                </div>
                <div class="category-card" onclick="window.location.href='category.php?shape=clubmaster'">
                    <div class="placeholder-img category-placeholder">
                        <div class="eyeglass-img">
                            <div class="eyeglass">
                                <div class="lens wayfarer" style="width: 65px; height: 55px;"></div>
                                <div class="bridge"></div>
                                <div class="lens wayfarer" style="width: 65px; height: 55px;"></div>
                                <div class="temple left" style="width: 35px;"></div>
                                <div class="temple right" style="width: 35px;"></div>
                            </div>
                        </div>
                    </div>
                    <h3>Clubmaster</h3>
                    <a href="category.php?shape=clubmaster" class="explore-link">Explore</a>
                </div>
                <div class="category-card" onclick="window.location.href='category.php?shape=transparent'">
                    <div class="placeholder-img category-placeholder">
                        <div class="eyeglass-img">
                            <div class="eyeglass">
                                <div class="lens square" style="width: 60px; height: 60px; border-color: rgba(200,200,200,0.5); border-width: 4px;"></div>
                                <div class="bridge" style="background: rgba(200,200,200,0.5);"></div>
                                <div class="lens square" style="width: 60px; height: 60px; border-color: rgba(200,200,200,0.5); border-width: 4px;"></div>
                                <div class="temple left" style="width: 35px; background: rgba(200,200,200,0.5);"></div>
                                <div class="temple right" style="width: 35px; background: rgba(200,200,200,0.5);"></div>
                            </div>
                        </div>
                    </div>
                    <h3>Transparent</h3>
                    <a href="category.php?shape=transparent" class="explore-link">Explore</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section">
        <div class="container">
            <h2 class="section-title">Experience VisionKart</h2>
            <p class="section-subtitle">See how we're revolutionizing eyewear shopping</p>
            
            <div class="video-container">
                <div class="video-wrapper">
                    <video id="mainVideo" controls poster="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=1200&h=675&fit=crop&q=80">
                        <source src="videos/eyewear-promo.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-overlay">
                        <button class="play-btn" id="playBtn">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                </div>
                <div class="video-info">
                    <h3>Why Choose VisionKart?</h3>
                    <ul class="video-features">
                        <li><i class="fas fa-check-circle"></i> Try before you buy with virtual try-on</li>
                        <li><i class="fas fa-check-circle"></i> Free home eye test by certified optometrists</li>
                        <li><i class="fas fa-check-circle"></i> 14-day return policy on all products</li>
                        <li><i class="fas fa-check-circle"></i> Premium quality at affordable prices</li>
                        <li><i class="fas fa-check-circle"></i> 1-year warranty on all frames</li>
                    </ul>
                    <button class="btn-primary">Shop Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="services-grid">
        <div class="container">
            <div class="service-cards">
                <div class="service-card">
                    <i class="fas fa-sync-alt"></i>
                    <h4>FREE LENS REPLACEMENT</h4>
                    <p>At all stores</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-gift"></i>
                    <h4>BUY ONE GET ONE FREE</h4>
                    <p>On all eyewear</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-home"></i>
                    <h4>BOOK EYE TEST AT HOME</h4>
                    <p>Free consultation</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-laptop"></i>
                    <h4>FREE ONLINE EYE TEST</h4>
                    <p>Test from anywhere</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-star"></i>
                    <h4>PREMIUM EYEWEAR</h4>
                    <p>Exclusive collection</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-tv"></i>
                    <h4>AS SEEN ON TV</h4>
                    <p>Popular collection</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Section -->
    <section class="products-section" id="eyeglasses">
        <div class="container">
            <h2 class="section-title">FIND THE PERFECT FIT</h2>
            <p class="section-subtitle">Browse our extensive collection</p>

            <!-- Filter and Compare Bar -->
            <div class="filter-compare-bar">
                <div class="filter-section">
                    <button class="filter-btn" id="filterBtn">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <div class="active-filters" id="activeFilters">
                        <!-- Active filter tags will appear here -->
                    </div>
                </div>
                <div class="compare-section">
                    <button class="compare-btn" id="compareBtn" disabled>
                        <i class="fas fa-balance-scale"></i> Compare (<span id="compareCount">0</span>)
                    </button>
                    <button class="btn-secondary" id="clearFiltersBtn" style="padding: 12px 24px; background: #ff6b6b; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-times"></i> Clear All
                    </button>
                </div>
            </div>

            <!-- Filter Overlay -->
            <div class="filter-overlay" id="filterOverlay"></div>

            <!-- Filter Sidebar -->
            <div class="filter-sidebar" id="filterSidebar">
                <div class="filter-header">
                    <h3>Filters</h3>
                    <button class="close-filter" id="closeFilter">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="filter-content">
                    <!-- Price Filter -->
                    <div class="filter-group">
                        <h4>Price Range</h4>
                        <div class="price-inputs">
                            <input type="number" id="minPrice" placeholder="Min" value="0">
                            <span>-</span>
                            <input type="number" id="maxPrice" placeholder="Max" value="5000">
                        </div>
                        <input type="range" id="priceRange" min="0" max="5000" value="5000" step="100">
                        <div class="price-display">₹0 - ₹<span id="priceValue">5000</span></div>
                    </div>

                    <!-- Frame Type Filter -->
                    <div class="filter-group">
                        <h4>Frame Type</h4>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameType" value="full-rim">
                            <span>Full Rim</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameType" value="half-rim">
                            <span>Half Rim</span>
                        </label>
                    </div>

                    <!-- Frame Size Filter -->
                    <div class="filter-group">
                        <h4>Frame Size</h4>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameSize" value="extra-narrow">
                            <span>Extra Narrow</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameSize" value="narrow">
                            <span>Narrow</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameSize" value="medium">
                            <span>Medium</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameSize" value="wide">
                            <span>Wide</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="frameSize" value="extra-wide">
                            <span>Extra Wide</span>
                        </label>
                    </div>

                    <!-- Frame Color Filter -->
                    <div class="filter-group">
                        <h4>Frame Color</h4>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="color" value="black">
                            <span>Black</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="color" value="brown">
                            <span>Brown</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="color" value="havana">
                            <span>Havana</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="color" value="gold">
                            <span>Gold</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="color" value="silver">
                            <span>Silver</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="color" value="blue">
                            <span>Blue</span>
                        </label>
                    </div>
                </div>
                <div class="filter-footer">
                    <button class="btn-secondary" id="clearFilters">Clear All</button>
                    <button class="btn-primary" id="applyFilters">Apply Filters</button>
                </div>
            </div>

            <div class="product-grid" id="productGrid">
                <!-- Products will be dynamically loaded by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Compare Modal -->
    <div class="compare-modal" id="compareModal">
        <div class="compare-content">
            <div class="compare-header">
                <h3>Compare Products</h3>
                <button class="close-compare" id="closeCompare">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="compare-body" id="compareBody">
                <!-- Comparison table will be generated here -->
            </div>
        </div>
    </div>
    <div class="compare-overlay" id="compareOverlay"></div>

    <!-- Brands Section -->
    <section class="brands-section">
        <div class="container">
            <h2 class="section-title">OUR BRANDS</h2>
            
            <div class="brands-grid">
                <div class="brand-card">
                    <div class="brand-logo">
                        <h3>Vincent Chase</h3>
                    </div>
                    <div class="brand-categories">
                        <div class="brand-category">
                            <span>EYEGLASSES</span>
                            <a href="#">View Range</a>
                        </div>
                        <div class="brand-category">
                            <span>SUNGLASSES</span>
                            <a href="#">View Range</a>
                        </div>
                    </div>
                </div>
                <div class="brand-card">
                    <div class="brand-logo">
                        <h3>John Jacobs</h3>
                    </div>
                    <div class="brand-categories">
                        <div class="brand-category">
                            <span>EYEGLASSES</span>
                            <a href="#">View Range</a>
                        </div>
                        <div class="brand-category">
                            <span>SUNGLASSES</span>
                            <a href="#">View Range</a>
                        </div>
                    </div>
                </div>
                <div class="brand-card">
                    <div class="brand-logo">
                        <h3>Air Collection</h3>
                    </div>
                    <div class="brand-categories">
                        <div class="brand-category">
                            <span>EYEGLASSES</span>
                            <a href="#">View Range</a>
                        </div>
                        <div class="brand-category">
                            <span>SUNGLASSES</span>
                            <a href="#">View Range</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sunglasses Section -->
    <section class="products-section" id="sunglasses" style="background: #f9f9f9; padding: 60px 0;">
        <div class="container">
            <h2 class="section-title">SUNGLASSES COLLECTION</h2>
            <p class="section-subtitle">Protect your eyes in style</p>
            <div class="product-grid" id="sunglassesGrid">
                <p style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">Loading sunglasses collection...</p>
            </div>
        </div>
    </section>

    <!-- Contact Lenses Section -->
    <section class="products-section" id="contact-lenses" style="padding: 60px 0;">
        <div class="container">
            <h2 class="section-title">CONTACT LENSES</h2>
            <p class="section-subtitle">Clear vision, all-day comfort</p>
            <div style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #00bac7 0%, #667eea 100%); border-radius: 20px; color: white;">
                <i class="fas fa-eye" style="font-size: 64px; margin-bottom: 20px;"></i>
                <h3 style="margin: 20px 0;">Contact Lenses Coming Soon!</h3>
                <p style="font-size: 18px; margin-bottom: 30px;">We're preparing the best collection of contact lenses for you.</p>
                <button style="padding: 15px 40px; background: white; color: #00bac7; border: none; border-radius: 25px; font-size: 16px; font-weight: 600; cursor: pointer;">
                    Notify Me
                </button>
            </div>
        </div>
    </section>

    <!-- Kids Glasses Section -->
    <section class="products-section" id="kids" style="background: #fff5e6; padding: 60px 0;">
        <div class="container">
            <h2 class="section-title">KIDS EYEWEAR</h2>
            <p class="section-subtitle">Fun, safe, and durable glasses for children</p>
            <div class="product-grid" id="kidsGrid">
                <p style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">Loading kids glasses collection...</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services" style="padding: 80px 0; background: white;">
        <div class="container">
            <h2 class="section-title">OUR SERVICES</h2>
            <p class="section-subtitle">Complete eyecare solutions for you</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 50px;">
                <div style="text-align: center; padding: 40px 30px; background: #f9f9f9; border-radius: 15px; transition: all 0.3s;">
                    <i class="fas fa-home" style="font-size: 48px; color: #00bac7; margin-bottom: 20px;"></i>
                    <h3 style="margin: 20px 0; color: #333;">Home Eye Test</h3>
                    <p style="color: #666; line-height: 1.6;">Get your eyes tested at home by certified professionals. Free service!</p>
                    <button style="margin-top: 20px; padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                        Book Now
                    </button>
                </div>

                <div style="text-align: center; padding: 40px 30px; background: #f9f9f9; border-radius: 15px; transition: all 0.3s;">
                    <i class="fas fa-glasses" style="font-size: 48px; color: #00bac7; margin-bottom: 20px;"></i>
                    <h3 style="margin: 20px 0; color: #333;">Free Trial at Home</h3>
                    <p style="color: #666; line-height: 1.6;">Try up to 5 frames at home before buying. 5-day trial period.</p>
                    <button style="margin-top: 20px; padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                        Select Frames
                    </button>
                </div>

                <div style="text-align: center; padding: 40px 30px; background: #f9f9f9; border-radius: 15px; transition: all 0.3s;">
                    <i class="fas fa-store" style="font-size: 48px; color: #00bac7; margin-bottom: 20px;"></i>
                    <h3 style="margin: 20px 0; color: #333;">Store Locator</h3>
                    <p style="color: #666; line-height: 1.6;">Visit our 300+ stores nationwide for personalized service.</p>
                    <button style="margin-top: 20px; padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                        Find Store
                    </button>
                </div>

                <div style="text-align: center; padding: 40px 30px; background: #f9f9f9; border-radius: 15px; transition: all 0.3s;">
                    <i class="fas fa-sync" style="font-size: 48px; color: #00bac7; margin-bottom: 20px;"></i>
                    <h3 style="margin: 20px 0; color: #333;">14-Day Exchange</h3>
                    <p style="color: #666; line-height: 1.6;">Don't like your glasses? Exchange them within 14 days!</p>
                    <button style="margin-top: 20px; padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                        Learn More
                    </button>
                </div>

                <div style="text-align: center; padding: 40px 30px; background: #f9f9f9; border-radius: 15px; transition: all 0.3s;">
                    <i class="fas fa-shield-alt" style="font-size: 48px; color: #00bac7; margin-bottom: 20px;"></i>
                    <h3 style="margin: 20px 0; color: #333;">1 Year Warranty</h3>
                    <p style="color: #666; line-height: 1.6;">All our products come with 1-year manufacturer warranty.</p>
                    <button style="margin-top: 20px; padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                        View Details
                    </button>
                </div>

                <div style="text-align: center; padding: 40px 30px; background: #f9f9f9; border-radius: 15px; transition: all 0.3s;">
                    <i class="fas fa-cut" style="font-size: 48px; color: #00bac7; margin-bottom: 20px;"></i>
                    <h3 style="margin: 20px 0; color: #333;">Free Eye Checkup</h3>
                    <p style="color: #666; line-height: 1.6;">Get free comprehensive eye checkup at any of our stores.</p>
                    <button style="margin-top: 20px; padding: 12px 30px; background: #00bac7; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                        Book Appointment
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Buy Your Way Section -->
    <section class="buy-your-way">
        <div class="container">
            <h2 class="section-title">BUY IT YOUR WAY</h2>
            
            <div class="buying-options">
                <div class="option-card">
                    <i class="fas fa-home fa-3x"></i>
                    <h4>Home Trial</h4>
                    <p>Try before you buy</p>
                    <a href="#" class="btn-secondary">Book Now</a>
                </div>
                <div class="option-card">
                    <i class="fab fa-whatsapp fa-3x"></i>
                    <h4>WhatsApp Order</h4>
                    <p>Order via chat</p>
                    <a href="#" class="btn-secondary">Chat Now</a>
                </div>
                <div class="option-card">
                    <i class="fas fa-store fa-3x"></i>
                    <h4>Visit Store</h4>
                    <p>300+ stores nationwide</p>
                    <a href="#" class="btn-secondary">Find Store</a>
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
                        <li><a href="#">Grievance Redressal</a></li>
                        <li><a href="#">EMI Options</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="app-download">
                        <h5>Download App</h5>
                        <a href="#" class="app-btn"><i class="fab fa-google-play"></i> Google Play</a>
                        <a href="#" class="app-btn"><i class="fab fa-app-store"></i> App Store</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 VisionKart. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#">Terms & Conditions</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Disclaimer</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Sidebar -->
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



    <!-- Wishlist Sidebar -->
    <div id="wishlistSidebar" style="position: fixed; top: 0; right: -400px; width: 400px; height: 100vh; background: white; box-shadow: -2px 0 10px rgba(0,0,0,0.3); z-index: 10001; transition: right 0.3s ease;">
        <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333; font-size: 22px;">My Wishlist</h3>
            <button id="closeWishlist" style="background: none; border: none; font-size: 24px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="wishlistItems" style="flex: 1; overflow-y: auto; padding: 20px; height: calc(100vh - 160px);">
            <!-- Wishlist items will be loaded here -->
        </div>
        <div style="padding: 15px 20px; border-top: 1px solid #e0e0e0; background: #f5f5f5;">
            <a href="my-wishlist.php" style="display: block; background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%); color: white; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-heart"></i> View Full Wishlist
            </a>
        </div>
    </div>
    <div id="wishlistOverlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; opacity: 0; visibility: hidden; transition: all 0.3s;"></div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10003; display: none; align-items: center; justify-content: center; overflow-y: auto; padding: 20px;">
        <div style="background: white; border-radius: 15px; width: 90%; max-width: 900px; position: relative; max-height: 90vh; overflow-y: auto;">
            <button id="closeCheckoutModal" style="position: absolute; top: 15px; right: 15px; background: white; border: none; font-size: 24px; cursor: pointer; color: #666; z-index: 1; width: 40px; height: 40px; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <i class="fas fa-times"></i>
            </button>
            
            <div style="padding: 40px;">
                <h2 style="margin: 0 0 30px 0; color: #333; text-align: center; font-size: 28px;">
                    <i class="fas fa-shopping-bag" style="color: #00bac7;"></i> Checkout
                </h2>

                <!-- Order Summary -->
                <div style="background: #f9f9f9; border-radius: 12px; padding: 25px; margin-bottom: 30px;">
                    <h3 style="margin: 0 0 20px 0; color: #333; font-size: 20px;">Order Summary</h3>
                    <div id="checkoutItemsList" style="margin-bottom: 20px;">
                        <!-- Items will be loaded here -->
                    </div>
                    <div style="border-top: 2px solid #e0e0e0; padding-top: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px;">
                            <span>Subtotal:</span>
                            <span id="checkoutSubtotal">₹0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px;">
                            <span>Shipping:</span>
                            <span style="color: #00bac7; font-weight: 600;">FREE</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 22px; font-weight: 700; color: #00bac7;">
                            <span>Total:</span>
                            <span id="checkoutTotal">₹0</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div style="background: #fff; border: 2px solid #e0e0e0; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                    <h3 style="margin: 0 0 20px 0; color: #333; font-size: 18px;">
                        <i class="fas fa-map-marker-alt" style="color: #00bac7;"></i> Delivery Address
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <input type="text" id="deliveryName" placeholder="Full Name" required style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <input type="tel" id="deliveryPhone" placeholder="Phone Number" required style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <input type="text" id="deliveryAddress" placeholder="Address Line 1" required style="grid-column: 1 / -1; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <input type="text" id="deliveryAddress2" placeholder="Address Line 2 (Optional)" style="grid-column: 1 / -1; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <input type="text" id="deliveryCity" placeholder="City" required style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        <input type="text" id="deliveryPincode" placeholder="Pincode" required style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <!-- Payment Method -->
                <div style="background: #fff; border: 2px solid #e0e0e0; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                    <h3 style="margin: 0 0 20px 0; color: #333; font-size: 18px;">
                        <i class="fas fa-credit-card" style="color: #00bac7;"></i> Payment Method
                    </h3>
                    
                    <div style="display: grid; gap: 15px;">
                        <!-- Credit/Debit Card -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="paymentMethod" value="card" checked style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <i class="fas fa-credit-card" style="font-size: 24px; margin-right: 15px; color: #00bac7;"></i>
                            <div>
                                <div style="font-weight: 600; color: #333; margin-bottom: 3px;">Credit / Debit Card</div>
                                <div style="font-size: 12px; color: #999;">Visa, Mastercard, RuPay</div>
                            </div>
                        </label>

                        <!-- UPI -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="paymentMethod" value="upi" style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <i class="fab fa-google-pay" style="font-size: 24px; margin-right: 15px; color: #00bac7;"></i>
                            <div>
                                <div style="font-weight: 600; color: #333; margin-bottom: 3px;">UPI</div>
                                <div style="font-size: 12px; color: #999;">Google Pay, PhonePe, Paytm</div>
                            </div>
                        </label>

                        <!-- Net Banking -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="paymentMethod" value="netbanking" style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <i class="fas fa-university" style="font-size: 24px; margin-right: 15px; color: #00bac7;"></i>
                            <div>
                                <div style="font-weight: 600; color: #333; margin-bottom: 3px;">Net Banking</div>
                                <div style="font-size: 12px; color: #999;">All major banks</div>
                            </div>
                        </label>

                        <!-- Cash on Delivery -->
                        <label class="payment-option" style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="paymentMethod" value="cod" style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <i class="fas fa-money-bill-wave" style="font-size: 24px; margin-right: 15px; color: #00bac7;"></i>
                            <div>
                                <div style="font-weight: 600; color: #333; margin-bottom: 3px;">Cash on Delivery</div>
                                <div style="font-size: 12px; color: #999;">Pay when you receive</div>
                            </div>
                        </label>
                    </div>

                    <!-- Card Details (shown when card is selected) -->
                    <div id="cardDetails" style="margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Card Number</label>
                            <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Expiry</label>
                                <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">CVV</label>
                                <input type="text" id="cardCVV" placeholder="123" maxlength="3" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Name</label>
                                <input type="text" id="cardName" placeholder="Cardholder Name" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                            </div>
                        </div>
                    </div>

                    <!-- UPI Details (hidden by default) -->
                    <div id="upiDetails" style="margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 8px; display: none;">
                        <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">UPI ID</label>
                        <input type="text" id="upiId" placeholder="yourname@upi" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <!-- Place Order Button -->
                <button id="placeOrderBtn" style="width: 100%; padding: 18px; background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%); color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0, 186, 199, 0.3);">
                    <i class="fas fa-lock" style="margin-right: 10px;"></i> Place Order
                </button>
            </div>
        </div>
    </div>

    <script src="auth.js"></script>
    <script src="script.js"></script>
    <script src="payment.js"></script>
</body>
</html>
