<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - VisionKart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo h1 {
            color: #00bac7;
            font-size: 28px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4CAF50;
            font-weight: 600;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        .checkout-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 22px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #00bac7;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #00bac7;
            box-shadow: 0 0 0 3px rgba(0, 186, 199, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .payment-method:hover {
            border-color: #00bac7;
            background: #f0fbfc;
        }

        .payment-method.active {
            border-color: #00bac7;
            background: #f0fbfc;
            box-shadow: 0 4px 15px rgba(0, 186, 199, 0.2);
        }

        .payment-method input[type="radio"] {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #00bac7;
        }

        .payment-icon {
            font-size: 28px;
            margin-right: 15px;
            color: #00bac7;
        }

        .payment-info {
            flex: 1;
        }

        .payment-name {
            font-weight: 700;
            color: #333;
            font-size: 16px;
            margin-bottom: 3px;
        }

        .payment-desc {
            font-size: 12px;
            color: #999;
        }

        .payment-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .badge {
            background: #00bac7;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.orange {
            background: #ff9800;
        }

        .badge.green {
            background: #4CAF50;
        }

        .payment-details {
            margin-top: 15px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            display: none;
        }

        .payment-details.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .order-summary {
            position: sticky;
            top: 20px;
        }

        .cart-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .item-price {
            color: #00bac7;
            font-weight: 700;
            font-size: 16px;
        }

        .item-qty {
            font-size: 13px;
            color: #666;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .summary-row.total {
            border-top: 2px solid #00bac7;
            border-bottom: none;
            padding-top: 15px;
            margin-top: 10px;
            font-size: 20px;
            font-weight: 700;
            color: #00bac7;
        }

        .place-order-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #00bac7 0%, #008c9a 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(0, 186, 199, 0.3);
            margin-top: 20px;
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 186, 199, 0.4);
        }

        .place-order-btn:active {
            transform: translateY(0);
        }

        .security-info {
            margin-top: 20px;
            padding: 15px;
            background: #f0f9ff;
            border-radius: 10px;
            border-left: 4px solid #00bac7;
        }

        .security-info h4 {
            color: #00bac7;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .security-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .security-badge-item {
            background: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #666;
        }

        .offer-banner {
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            border-radius: 12px;
            color: white;
            text-align: center;
        }

        .offer-banner h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .offer-code {
            display: inline-block;
            background: rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .wallet-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .wallet-option {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .wallet-option:hover {
            border-color: #00bac7;
            background: #f0fbfc;
        }

        .wallet-option input {
            margin-right: 10px;
        }

        .wallet-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .emi-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .emi-option:hover {
            border-color: #00bac7;
        }

        .emi-option input {
            margin-right: 12px;
        }

        @media (max-width: 968px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            display: none;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification.success {
            border-left: 4px solid #4CAF50;
        }

        .notification.error {
            border-left: 4px solid #f44336;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <h1>VisionKart</h1>
            </div>
            <div class="secure-badge">
                <i class="fas fa-shield-alt"></i>
                <span>100% Secure Checkout</span>
            </div>
        </div>

        <!-- Offer Banner -->
        <div class="offer-banner">
            <h3>🎁 Special Offer!</h3>
            <p>Use code <span class="offer-code">FIRST10</span> for 10% extra cashback on UPI payments</p>
        </div>

        <!-- Checkout Grid -->
        <div class="checkout-grid">
            <!-- Left Column - Forms -->
            <div>
                <!-- Delivery Address Section -->
                <div class="checkout-section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Delivery Address
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Full Name *</label>
                            <input type="text" id="fullName" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" placeholder="+91 98765 43210" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" placeholder="john@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="address1">Address Line 1 *</label>
                        <input type="text" id="address1" placeholder="House No., Building Name" required>
                    </div>

                    <div class="form-group">
                        <label for="address2">Address Line 2 (Optional)</label>
                        <input type="text" id="address2" placeholder="Road Name, Area">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" placeholder="Mumbai" required>
                        </div>
                        <div class="form-group">
                            <label for="pincode">Pincode *</label>
                            <input type="text" id="pincode" placeholder="400001" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="state">State *</label>
                        <select id="state" required>
                            <option value="">Select State</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>
                </div>

                <!-- Payment Method Section -->
                <div class="checkout-section" style="margin-top: 30px;">
                    <h2 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        Payment Method
                    </h2>

                    <!-- Credit/Debit Card -->
                    <div class="payment-method active" data-method="card">
                        <input type="radio" name="paymentMethod" value="card" checked>
                        <i class="fas fa-credit-card payment-icon"></i>
                        <div class="payment-info">
                            <div class="payment-name">Credit / Debit Card</div>
                            <div class="payment-desc">Visa, Mastercard, RuPay, Amex</div>
                            <div class="payment-badges">
                                <span class="badge">💳 Instant</span>
                                <span class="badge green">🔒 Secure</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-details active" id="cardDetails">
                        <div class="form-group">
                            <label for="cardNumber">Card Number</label>
                            <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cardExpiry">Expiry Date</label>
                                <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="cardCVV">CVV</label>
                                <input type="text" id="cardCVV" placeholder="123" maxlength="3">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cardName">Cardholder Name</label>
                            <input type="text" id="cardName" placeholder="John Doe">
                        </div>
                    </div>

                    <!-- UPI -->
                    <div class="payment-method" data-method="upi">
                        <input type="radio" name="paymentMethod" value="upi">
                        <i class="fab fa-google-pay payment-icon"></i>
                        <div class="payment-info">
                            <div class="payment-name">UPI</div>
                            <div class="payment-desc">Google Pay, PhonePe, Paytm</div>
                            <div class="payment-badges">
                                <span class="badge orange">⚡ Instant</span>
                                <span class="badge green">🎁 Cashback</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-details" id="upiDetails">
                        <div class="form-group">
                            <label for="upiId">UPI ID</label>
                            <input type="text" id="upiId" placeholder="yourname@upi">
                        </div>
                    </div>

                    <!-- Wallets -->
                    <div class="payment-method" data-method="wallet">
                        <input type="radio" name="paymentMethod" value="wallet">
                        <i class="fas fa-wallet payment-icon"></i>
                        <div class="payment-info">
                            <div class="payment-name">Wallets</div>
                            <div class="payment-desc">Paytm, PhonePe, Amazon Pay</div>
                            <div class="payment-badges">
                                <span class="badge green">🎁 Cashback</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-details" id="walletDetails">
                        <div class="wallet-grid">
                            <label class="wallet-option">
                                <input type="radio" name="walletProvider" value="paytm">
                                <span class="wallet-icon">💰</span>
                                <span>Paytm</span>
                            </label>
                            <label class="wallet-option">
                                <input type="radio" name="walletProvider" value="phonepe">
                                <span class="wallet-icon">📱</span>
                                <span>PhonePe</span>
                            </label>
                            <label class="wallet-option">
                                <input type="radio" name="walletProvider" value="amazonpay">
                                <span class="wallet-icon">🛒</span>
                                <span>Amazon Pay</span>
                            </label>
                            <label class="wallet-option">
                                <input type="radio" name="walletProvider" value="mobikwik">
                                <span class="wallet-icon">💳</span>
                                <span>MobiKwik</span>
                            </label>
                        </div>
                    </div>

                    <!-- Net Banking -->
                    <div class="payment-method" data-method="netbanking">
                        <input type="radio" name="paymentMethod" value="netbanking">
                        <i class="fas fa-university payment-icon"></i>
                        <div class="payment-info">
                            <div class="payment-name">Net Banking</div>
                            <div class="payment-desc">All major banks</div>
                        </div>
                    </div>

                    <div class="payment-details" id="netbankingDetails">
                        <div class="form-group">
                            <label for="bankSelect">Select Your Bank</label>
                            <select id="bankSelect">
                                <option value="">-- Choose Bank --</option>
                                <option value="sbi">State Bank of India</option>
                                <option value="hdfc">HDFC Bank</option>
                                <option value="icici">ICICI Bank</option>
                                <option value="axis">Axis Bank</option>
                                <option value="kotak">Kotak Mahindra Bank</option>
                                <option value="pnb">Punjab National Bank</option>
                            </select>
                        </div>
                    </div>

                    <!-- EMI -->
                    <div class="payment-method" data-method="emi">
                        <input type="radio" name="paymentMethod" value="emi">
                        <i class="fas fa-calendar-alt payment-icon"></i>
                        <div class="payment-info">
                            <div class="payment-name">EMI (Easy Installments)</div>
                            <div class="payment-desc">No cost EMI available</div>
                            <div class="payment-badges">
                                <span class="badge">📅 3-12 months</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-details" id="emiDetails">
                        <div id="emiOptions">
                            <!-- EMI options will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Cash on Delivery -->
                    <div class="payment-method" data-method="cod">
                        <input type="radio" name="paymentMethod" value="cod">
                        <i class="fas fa-money-bill-wave payment-icon"></i>
                        <div class="payment-info">
                            <div class="payment-name">Cash on Delivery</div>
                            <div class="payment-desc">Pay when you receive</div>
                            <div class="payment-badges">
                                <span class="badge">🚚 Delivered</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="security-info">
                        <h4>🔒 100% Secure Payment</h4>
                        <p style="font-size: 13px; color: #666; margin-bottom: 10px;">
                            Your payment information is encrypted and secure
                        </p>
                        <div class="security-badges">
                            <span class="security-badge-item">
                                <i class="fas fa-lock"></i> SSL Encrypted
                            </span>
                            <span class="security-badge-item">
                                <i class="fas fa-check-circle"></i> PCI DSS Compliant
                            </span>
                            <span class="security-badge-item">
                                <i class="fas fa-shield-alt"></i> Verified by Visa
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div>
                <div class="checkout-section order-summary">
                    <h2 class="section-title">
                        <i class="fas fa-shopping-bag"></i>
                        Order Summary
                    </h2>

                    <div id="cartItems">
                        <!-- Cart items will be populated here -->
                    </div>

                    <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="subtotal">₹0</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span style="color: #4CAF50; font-weight: 600;">FREE</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (GST 18%)</span>
                            <span id="tax">₹0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="total">₹0</span>
                        </div>
                    </div>

                    <button class="place-order-btn" id="placeOrderBtn">
                        <i class="fas fa-lock"></i> Place Order Securely
                    </button>

                    <p style="text-align: center; margin-top: 15px; font-size: 12px; color: #999;">
                        <i class="fas fa-truck"></i> Estimated delivery: 3-5 business days
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script src="script.js"></script>
    <script src="payment.js"></script>
    <script>
        // Global cart data for checkout
        let checkoutCart = [];
        
        // Load cart and display
        async function loadCheckoutData() {
            try {
                // Fetch cart from API
                const response = await fetch('api_cart.php?action=list', {
                    credentials: 'include'
                });
                const data = await response.json();
                
                if (data.success && Array.isArray(data.items)) {
                    checkoutCart = data.items;
                } else {
                    // Fallback to localStorage
                    checkoutCart = JSON.parse(localStorage.getItem('visionkart_cart') || '[]');
                }
                
                if (checkoutCart.length === 0) {
                    document.getElementById('cartItems').innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 15px;"></i>
                            <p>Your cart is empty</p>
                            <a href="index.php" style="color: #00bac7; text-decoration: none; font-weight: 600;">← Continue Shopping</a>
                        </div>
                    `;
                    return;
                }

                let subtotal = 0;
                let cartHTML = '';

                checkoutCart.forEach(item => {
                    const itemPrice = parseFloat(item.price);
                    const itemQty = parseInt(item.quantity);
                    const itemTotal = itemPrice * itemQty;
                    subtotal += itemTotal;

                    cartHTML += `
                        <div class="cart-item">
                            <img src="${item.image || item.product_image}" alt="${item.name || item.product_name}">
                            <div class="item-info">
                                <div class="item-name">${item.name || item.product_name}</div>
                                <div class="item-qty">Qty: ${itemQty}</div>
                                <div class="item-price">₹${itemTotal.toLocaleString()}</div>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('cartItems').innerHTML = cartHTML;

                const tax = Math.round(subtotal * 0.18);
                const total = subtotal + tax;

                document.getElementById('subtotal').textContent = '₹' + subtotal.toLocaleString();
                document.getElementById('tax').textContent = '₹' + tax.toLocaleString();
                document.getElementById('total').textContent = '₹' + total.toLocaleString();

                // Generate EMI options
                generateEMIOptions(total);
            } catch (error) {
                console.error('Error loading checkout data:', error);
                // Fallback to localStorage
                checkoutCart = JSON.parse(localStorage.getItem('visionkart_cart') || '[]');
                if (checkoutCart.length === 0) {
                    document.getElementById('cartItems').innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 15px;"></i>
                            <p>Your cart is empty</p>
                            <a href="index.php" style="color: #00bac7; text-decoration: none; font-weight: 600;">← Continue Shopping</a>
                        </div>
                    `;
                }
            }
        }

        // Generate EMI Options
        function generateEMIOptions(amount) {
            const emiPlans = [
                { months: 3, interest: 0 },
                { months: 6, interest: 0 },
                { months: 9, interest: 2 },
                { months: 12, interest: 2 }
            ];

            let emiHTML = '';

            emiPlans.forEach(plan => {
                const emi = calculateEMI(amount, plan.months, plan.interest);
                const total = emi * plan.months;

                emiHTML += `
                    <div class="emi-option">
                        <input type="radio" name="emiPlan" value="${plan.months}">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #333;">${plan.months} Months</div>
                            <div style="font-size: 12px; color: #999;">${plan.interest === 0 ? 'No cost EMI' : plan.interest + '% interest'}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: #00bac7; font-size: 18px;">₹${emi.toLocaleString()}/mo</div>
                            <div style="font-size: 11px; color: #666;">Total: ₹${total.toLocaleString()}</div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('emiOptions').innerHTML = emiHTML;
        }

        function calculateEMI(principal, months, interestRate) {
            if (interestRate === 0) {
                return Math.ceil(principal / months);
            }
            
            const monthlyRate = interestRate / 100 / 12;
            const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / 
                        (Math.pow(1 + monthlyRate, months) - 1);
            return Math.ceil(emi);
        }

        // Payment method toggle
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Remove active class from all
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
                document.querySelectorAll('.payment-details').forEach(d => d.classList.remove('active'));

                // Add active class to clicked
                this.classList.add('active');
                this.querySelector('input[type="radio"]').checked = true;

                // Show corresponding details
                const methodType = this.dataset.method;
                const detailsEl = document.getElementById(methodType + 'Details');
                if (detailsEl) {
                    detailsEl.classList.add('active');
                }
            });
        });

        // Place Order
        document.getElementById('placeOrderBtn').addEventListener('click', async function() {
            // Validate form
            const name = document.getElementById('fullName').value;
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;
            const address1 = document.getElementById('address1').value;
            const city = document.getElementById('city').value;
            const pincode = document.getElementById('pincode').value;
            const state = document.getElementById('state').value;

            if (!name || !phone || !email || !address1 || !city || !pincode || !state) {
                showNotification('Please fill all required fields', 'error');
                return;
            }

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            // Validate payment method specific fields
            if (paymentMethod === 'card') {
                const cardNumber = document.getElementById('cardNumber').value;
                const cardExpiry = document.getElementById('cardExpiry').value;
                const cardCVV = document.getElementById('cardCVV').value;
                
                if (!cardNumber || !cardExpiry || !cardCVV) {
                    showNotification('Please fill all card details', 'error');
                    return;
                }
            }

            // Use the globally loaded cart data
            if (checkoutCart.length === 0) {
                showNotification('Your cart is empty', 'error');
                return;
            }
            
            const subtotal = checkoutCart.reduce((sum, item) => sum + (parseFloat(item.price) * parseInt(item.quantity)), 0);
            const tax = Math.round(subtotal * 0.18);
            const total = subtotal + tax;

            // Prepare shipping address
            const shippingAddress = `${name}\n${phone}\n${address1}\n${document.getElementById('address2').value || ''}\n${city}, ${state} - ${pincode}`.trim();
            
            // Prepare order items for API
            const orderItems = checkoutCart.map(item => ({
                product_id: item.id,
                product_name: item.name,
                quantity: parseInt(item.quantity),
                price: parseFloat(item.price),
                gst: Math.round(parseFloat(item.price) * parseInt(item.quantity) * 0.18),
                total: parseFloat(item.price) * parseInt(item.quantity) + Math.round(parseFloat(item.price) * parseInt(item.quantity) * 0.18)
            }));

            // Prepare order data for API
            const orderData = {
                customer_name: name,
                // customer_id will be set to NULL in API if not numeric
                total_amount: total,
                order_date: new Date().toISOString().split('T')[0],
                status: paymentMethod === 'cod' ? 'pending' : 'paid',
                payment_method: paymentMethod.toUpperCase(),
                shipping_address: shippingAddress,
                products: checkoutCart.map(item => item.name).join(', '),
                items: orderItems
            };

            // Disable button while processing
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            try {
                // Send order to API
                const response = await fetch('api_orders.php?action=create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();

                if (result.success) {
                    // Clear cart from database
                    await fetch('api_cart.php?action=clear', {
                        method: 'DELETE',
                        credentials: 'include'
                    });
                    
                    // Also save to localStorage for backward compatibility
                    const localOrder = {
                        id: result.order_id,
                        customer: email,
                        items: checkoutCart,
                        total: total,
                        date: new Date().toISOString(),
                        status: orderData.status,
                        paymentMethod: paymentMethod,
                        deliveryAddress: {
                            name: name,
                            phone: phone,
                            email: email,
                            address1: address1,
                            address2: document.getElementById('address2').value,
                            city: city,
                            state: state,
                            pincode: pincode
                        }
                    };
                    
                    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
                    orders.push(localOrder);
                    localStorage.setItem('visionkart_orders', JSON.stringify(orders));

                    // Show success
                    showNotification('Order placed successfully!', 'success');

                    // Show success alert before clearing localStorage cart
                    setTimeout(() => {
                        alert(`✅ Order Confirmed!\n\nOrder ID: ${result.order_id}\nTotal: ₹${total.toLocaleString()}\nPayment: ${paymentMethod.toUpperCase()}\n\nThank you for shopping with VisionKart! 👓`);
                        
                        // Clear localStorage cart after showing the alert
                        localStorage.removeItem('visionkart_cart');
                        window.location.href = 'index.php';
                    }, 1000);
                } else {
                    throw new Error(result.error || 'Failed to place order');
                }
            } catch (error) {
                console.error('Order error:', error);
                showNotification('Failed to place order. Please try again.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-lock"></i> Place Order Securely';
            }
        });

        // Show notification
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = 'notification ' + type;
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Card number formatting
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Expiry formatting
        document.getElementById('cardExpiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });

        // Load data on page load
        loadCheckoutData();
    </script>
</body>
</html>
