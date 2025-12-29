<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods - VisionKart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .header p {
            font-size: 20px;
            opacity: 0.9;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .payment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #00bac7, #008c9a);
        }

        .payment-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #00bac7, #008c9a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 32px;
            color: white;
        }

        .card-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .card-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .card-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .card-features li {
            padding: 8px 0;
            color: #555;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .card-features li i {
            color: #00bac7;
            margin-right: 10px;
        }

        .badge-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .badge {
            background: #00bac7;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.orange {
            background: #ff9800;
        }

        .badge.green {
            background: #4CAF50;
        }

        .badge.purple {
            background: #9c27b0;
        }

        .cta-section {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .cta-section h2 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .cta-button {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #00bac7, #008c9a);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            box-shadow: 0 6px 25px rgba(0, 186, 199, 0.4);
            transition: all 0.3s;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 186, 199, 0.5);
        }

        .security-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            margin-top: 40px;
            color: white;
        }

        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .security-item {
            text-align: center;
        }

        .security-item i {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .security-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .security-item p {
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💳 Secure Payment Options</h1>
            <p>Choose from multiple payment methods for a seamless checkout experience</p>
        </div>

        <!-- Payment Methods Grid -->
        <div class="payment-grid">
            <!-- Credit/Debit Card -->
            <div class="payment-card">
                <div class="card-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3 class="card-title">Credit / Debit Card</h3>
                <p class="card-description">
                    Pay securely with your credit or debit card. We support all major card networks.
                </p>
                <ul class="card-features">
                    <li><i class="fas fa-check-circle"></i> Visa & Mastercard</li>
                    <li><i class="fas fa-check-circle"></i> RuPay & American Express</li>
                    <li><i class="fas fa-check-circle"></i> Instant processing</li>
                    <li><i class="fas fa-check-circle"></i> Secure CVV verification</li>
                </ul>
                <div class="badge-container">
                    <span class="badge">Instant</span>
                    <span class="badge green">Secure</span>
                </div>
            </div>

            <!-- UPI -->
            <div class="payment-card">
                <div class="card-icon">
                    <i class="fab fa-google-pay"></i>
                </div>
                <h3 class="card-title">UPI Payments</h3>
                <p class="card-description">
                    Make instant payments using UPI apps like Google Pay, PhonePe, and Paytm.
                </p>
                <ul class="card-features">
                    <li><i class="fas fa-check-circle"></i> Google Pay</li>
                    <li><i class="fas fa-check-circle"></i> PhonePe</li>
                    <li><i class="fas fa-check-circle"></i> Paytm</li>
                    <li><i class="fas fa-check-circle"></i> 0% processing fee</li>
                </ul>
                <div class="badge-container">
                    <span class="badge orange">⚡ Instant</span>
                    <span class="badge green">🎁 Cashback</span>
                </div>
            </div>

            <!-- Digital Wallets -->
            <div class="payment-card">
                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3 class="card-title">Digital Wallets</h3>
                <p class="card-description">
                    Use your favorite digital wallet for quick and easy payments.
                </p>
                <ul class="card-features">
                    <li><i class="fas fa-check-circle"></i> Paytm Wallet</li>
                    <li><i class="fas fa-check-circle"></i> PhonePe Wallet</li>
                    <li><i class="fas fa-check-circle"></i> Amazon Pay</li>
                    <li><i class="fas fa-check-circle"></i> MobiKwik</li>
                </ul>
                <div class="badge-container">
                    <span class="badge green">Cashback Offers</span>
                </div>
            </div>

            <!-- Net Banking -->
            <div class="payment-card">
                <div class="card-icon">
                    <i class="fas fa-university"></i>
                </div>
                <h3 class="card-title">Net Banking</h3>
                <p class="card-description">
                    Pay directly from your bank account using internet banking.
                </p>
                <ul class="card-features">
                    <li><i class="fas fa-check-circle"></i> All major banks</li>
                    <li><i class="fas fa-check-circle"></i> SBI, HDFC, ICICI, Axis</li>
                    <li><i class="fas fa-check-circle"></i> Kotak, PNB & more</li>
                    <li><i class="fas fa-check-circle"></i> Secure transactions</li>
                </ul>
                <div class="badge-container">
                    <span class="badge">Bank Level Security</span>
                </div>
            </div>

            <!-- EMI -->
            <div class="payment-card">
                <div class="card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="card-title">EMI Options</h3>
                <p class="card-description">
                    Split your payment into easy monthly installments with no cost EMI.
                </p>
                <ul class="card-features">
                    <li><i class="fas fa-check-circle"></i> 3 to 12 months</li>
                    <li><i class="fas fa-check-circle"></i> No cost EMI available</li>
                    <li><i class="fas fa-check-circle"></i> Flexible plans</li>
                    <li><i class="fas fa-check-circle"></i> Easy approval</li>
                </ul>
                <div class="badge-container">
                    <span class="badge purple">📅 Flexible</span>
                    <span class="badge green">0% Interest</span>
                </div>
            </div>

            <!-- Cash on Delivery -->
            <div class="payment-card">
                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3 class="card-title">Cash on Delivery</h3>
                <p class="card-description">
                    Pay with cash when your order is delivered to your doorstep.
                </p>
                <ul class="card-features">
                    <li><i class="fas fa-check-circle"></i> No advance payment</li>
                    <li><i class="fas fa-check-circle"></i> Pay at delivery</li>
                    <li><i class="fas fa-check-circle"></i> Available everywhere</li>
                    <li><i class="fas fa-check-circle"></i> Verify before paying</li>
                </ul>
                <div class="badge-container">
                    <span class="badge">🚚 Convenient</span>
                </div>
            </div>
        </div>

        <!-- Security Section -->
        <div class="security-section">
            <h2 style="text-align: center; font-size: 32px; margin-bottom: 10px;">
                🔐 100% Secure Payments
            </h2>
            <p style="text-align: center; font-size: 16px; opacity: 0.9; margin-bottom: 40px;">
                Your payment information is encrypted and protected with industry-leading security standards
            </p>

            <div class="security-grid">
                <div class="security-item">
                    <i class="fas fa-lock"></i>
                    <h3>SSL Encrypted</h3>
                    <p>All data is encrypted using 256-bit SSL encryption</p>
                </div>

                <div class="security-item">
                    <i class="fas fa-shield-alt"></i>
                    <h3>PCI DSS Compliant</h3>
                    <p>Following Payment Card Industry security standards</p>
                </div>

                <div class="security-item">
                    <i class="fas fa-check-circle"></i>
                    <h3>Verified Secure</h3>
                    <p>Verified by Visa and Mastercard SecureCode</p>
                </div>

                <div class="security-item">
                    <i class="fas fa-user-shield"></i>
                    <h3>Privacy Protected</h3>
                    <p>Your information is never shared with third parties</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2>Ready to Shop?</h2>
            <p>Choose your perfect eyewear and checkout with your preferred payment method</p>
            <a href="index.php" class="cta-button">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
            <a href="checkout.php" class="cta-button" style="background: linear-gradient(135deg, #667eea, #764ba2); margin-left: 15px;">
                <i class="fas fa-credit-card"></i> Go to Checkout
            </a>
        </div>
    </div>
</body>
</html>
