// Enhanced Payment Module for VisionKart

// Payment Gateway Configuration
const PAYMENT_CONFIG = {
    razorpay: {
        enabled: true,
        key: 'rzp_test_XXXXXXXXXXXXX', // Replace with your Razorpay key
        name: 'VisionKart',
        description: 'Premium Eyewear Purchase',
        image: 'https://cdn-icons-png.flaticon.com/512/2089/2089215.png',
        theme: {
            color: '#00bac7'
        }
    },
    stripe: {
        enabled: false,
        key: 'pk_test_XXXXXXXXXXXXX' // Replace with your Stripe key
    }
};

// Payment Methods Data
const paymentMethods = {
    card: {
        id: 'card',
        name: 'Credit / Debit Card',
        icon: 'fas fa-credit-card',
        description: 'Visa, Mastercard, RuPay, Amex',
        processingFee: 0,
        badges: ['💳', '🔒 Secure']
    },
    upi: {
        id: 'upi',
        name: 'UPI',
        icon: 'fab fa-google-pay',
        description: 'Google Pay, PhonePe, Paytm',
        processingFee: 0,
        badges: ['⚡ Instant', '🎁 Cashback']
    },
    netbanking: {
        id: 'netbanking',
        name: 'Net Banking',
        icon: 'fas fa-university',
        description: 'All major banks',
        processingFee: 0,
        badges: ['🏦']
    },
    wallets: {
        id: 'wallets',
        name: 'Wallets',
        icon: 'fas fa-wallet',
        description: 'Paytm, PhonePe, Amazon Pay',
        processingFee: 0,
        badges: ['🎁 Cashback']
    },
    cod: {
        id: 'cod',
        name: 'Cash on Delivery',
        icon: 'fas fa-money-bill-wave',
        description: 'Pay when you receive',
        processingFee: 0,
        badges: ['🚚']
    },
    emi: {
        id: 'emi',
        name: 'EMI (Easy Installments)',
        icon: 'fas fa-calendar-alt',
        description: 'No cost EMI available',
        processingFee: 0,
        badges: ['📅 3-12 months']
    }
};

// Bank list for Net Banking
const banks = [
    'State Bank of India',
    'HDFC Bank',
    'ICICI Bank',
    'Axis Bank',
    'Kotak Mahindra Bank',
    'Punjab National Bank',
    'Bank of Baroda',
    'Canara Bank',
    'Union Bank of India',
    'IDBI Bank'
];

// Wallet providers
const walletProviders = [
    { id: 'paytm', name: 'Paytm', icon: '💰' },
    { id: 'phonepe', name: 'PhonePe', icon: '📱' },
    { id: 'amazonpay', name: 'Amazon Pay', icon: '🛒' },
    { id: 'mobikwik', name: 'MobiKwik', icon: '💳' }
];

// EMI options
const emiPlans = [
    { months: 3, interest: 0, description: 'No cost EMI' },
    { months: 6, interest: 0, description: 'No cost EMI' },
    { months: 9, interest: 2, description: '2% interest' },
    { months: 12, interest: 2, description: '2% interest' }
];

// Initialize Enhanced Payment UI
function initializeEnhancedPayment() {
    const checkoutModal = document.getElementById('checkoutModal');
    if (!checkoutModal) return;

    // Add enhanced payment section after existing payment section
    const paymentSection = checkoutModal.querySelector('h3:contains("Payment Method")');
    if (!paymentSection) return;

    console.log('🎨 Initializing enhanced payment UI...');
}

// Process Payment with Razorpay
function processRazorpayPayment(orderData) {
    if (!PAYMENT_CONFIG.razorpay.enabled) {
        console.warn('Razorpay is not enabled');
        return processStandardPayment(orderData);
    }

    const options = {
        key: PAYMENT_CONFIG.razorpay.key,
        amount: orderData.total * 100, // Convert to paise
        currency: 'INR',
        name: PAYMENT_CONFIG.razorpay.name,
        description: PAYMENT_CONFIG.razorpay.description,
        image: PAYMENT_CONFIG.razorpay.image,
        order_id: 'order_' + orderData.id,
        handler: function (response) {
            handlePaymentSuccess(response, orderData);
        },
        prefill: {
            name: orderData.deliveryAddress.name,
            email: orderData.customer,
            contact: orderData.deliveryAddress.phone
        },
        notes: {
            order_id: orderData.id,
            items: orderData.items.length
        },
        theme: PAYMENT_CONFIG.razorpay.theme,
        modal: {
            ondismiss: function() {
                showNotification('Payment cancelled', 'info');
            }
        }
    };

    const razorpay = new Razorpay(options);
    razorpay.open();
}

// Handle Payment Success
function handlePaymentSuccess(paymentResponse, orderData) {
    console.log('✅ Payment successful:', paymentResponse);
    
    // Update order with payment details
    orderData.paymentId = paymentResponse.razorpay_payment_id;
    orderData.paymentSignature = paymentResponse.razorpay_signature;
    orderData.status = 'Paid';
    
    // Save order
    const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
    orders.push(orderData);
    localStorage.setItem('visionkart_orders', JSON.stringify(orders));
    
    // Clear cart
    cart = [];
    saveCart();
    updateCartCount();
    
    // Show success animation
    showPaymentSuccessAnimation(orderData);
}

// Show Payment Success Animation
function showPaymentSuccessAnimation(orderData) {
    const modal = document.getElementById('checkoutModal');
    if (!modal) return;

    const successHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10004; display: flex; align-items: center; justify-content: center;" id="paymentSuccessOverlay">
            <div style="background: white; border-radius: 20px; padding: 40px; text-align: center; max-width: 500px; animation: successPop 0.5s ease-out;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #00bac7, #008c9a); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: successCheck 0.8s ease-out;">
                    <i class="fas fa-check" style="color: white; font-size: 50px;"></i>
                </div>
                <h2 style="color: #00bac7; margin-bottom: 10px; font-size: 28px;">Payment Successful! 🎉</h2>
                <p style="color: #666; font-size: 16px; margin-bottom: 20px;">Your order has been confirmed</p>
                
                <div style="background: #f9f9f9; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #666;">Order ID:</span>
                        <span style="font-weight: 700; color: #333;">#${orderData.id}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #666;">Total Amount:</span>
                        <span style="font-weight: 700; color: #00bac7; font-size: 20px;">₹${orderData.total.toLocaleString()}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666;">Payment Method:</span>
                        <span style="font-weight: 700; color: #333;">${paymentMethods[orderData.paymentMethod]?.name || orderData.paymentMethod}</span>
                    </div>
                </div>
                
                <p style="color: #999; font-size: 14px; margin-bottom: 20px;">
                    <i class="fas fa-truck"></i> Estimated delivery: 3-5 business days
                </p>
                
                <button onclick="closePaymentSuccess()" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #00bac7, #008c9a); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer;">
                    Continue Shopping
                </button>
            </div>
        </div>
        
        <style>
            @keyframes successPop {
                0% { transform: scale(0.8); opacity: 0; }
                100% { transform: scale(1); opacity: 1; }
            }
            
            @keyframes successCheck {
                0% { transform: scale(0); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
        </style>
    `;
    
    document.body.insertAdjacentHTML('beforeend', successHTML);
}

// Close Payment Success
function closePaymentSuccess() {
    const overlay = document.getElementById('paymentSuccessOverlay');
    if (overlay) overlay.remove();
    
    const modal = document.getElementById('checkoutModal');
    if (modal) modal.style.display = 'none';
}

// Process Standard Payment (without payment gateway)
function processStandardPayment(orderData) {
    const paymentMethod = orderData.paymentMethod;
    
    // Simulate payment processing
    showNotification('Processing payment...', 'info');
    
    setTimeout(() => {
        // Update order status based on payment method
        if (paymentMethod === 'cod') {
            orderData.status = 'Pending';
        } else {
            orderData.status = 'Paid';
        }
        
        // Save order
        const orders = JSON.parse(localStorage.getItem('visionkart_orders') || '[]');
        orders.push(orderData);
        localStorage.setItem('visionkart_orders', JSON.stringify(orders));
        
        // Clear cart
        cart = [];
        saveCart();
        updateCartCount();
        
        // Show success
        showPaymentSuccessAnimation(orderData);
    }, 1500);
}

// Calculate EMI amount
function calculateEMI(principal, months, interestRate) {
    if (interestRate === 0) {
        return Math.ceil(principal / months);
    }
    
    const monthlyRate = interestRate / 100 / 12;
    const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / 
                (Math.pow(1 + monthlyRate, months) - 1);
    return Math.ceil(emi);
}

// Show EMI Calculator
function showEMICalculator(totalAmount) {
    const emiHTML = `
        <div style="margin-top: 15px; padding: 15px; background: #f0fbfc; border-radius: 8px; border: 2px solid #00bac7;">
            <h4 style="margin: 0 0 15px 0; color: #00bac7;">
                <i class="fas fa-calculator"></i> EMI Options
            </h4>
            ${emiPlans.map(plan => {
                const emiAmount = calculateEMI(totalAmount, plan.months, plan.interest);
                return `
                    <label style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 6px; margin-bottom: 10px; cursor: pointer; border: 2px solid #e0e0e0; transition: all 0.3s;" class="emi-option">
                        <input type="radio" name="emiPlan" value="${plan.months}" style="margin-right: 10px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #333;">${plan.months} Months</div>
                            <div style="font-size: 12px; color: #999;">${plan.description}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: #00bac7; font-size: 18px;">₹${emiAmount.toLocaleString()}/mo</div>
                            <div style="font-size: 11px; color: #666;">Total: ₹${(emiAmount * plan.months).toLocaleString()}</div>
                        </div>
                    </label>
                `;
            }).join('')}
        </div>
    `;
    
    return emiHTML;
}

// Show Wallet Options
function showWalletOptions() {
    return `
        <div style="margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
            <label style="display: block; margin-bottom: 10px; color: #333; font-weight: 600;">Select Wallet</label>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                ${walletProviders.map(wallet => `
                    <label style="display: flex; align-items: center; padding: 12px; background: white; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; transition: all 0.3s;" class="wallet-option">
                        <input type="radio" name="walletProvider" value="${wallet.id}" style="margin-right: 10px;">
                        <span style="font-size: 24px; margin-right: 8px;">${wallet.icon}</span>
                        <span style="font-weight: 600;">${wallet.name}</span>
                    </label>
                `).join('')}
            </div>
        </div>
    `;
}

// Show Bank Selection for Net Banking
function showBankSelection() {
    return `
        <div style="margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
            <label style="display: block; margin-bottom: 10px; color: #333; font-weight: 600;">Select Your Bank</label>
            <select id="bankSelect" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; cursor: pointer;">
                <option value="">-- Choose Bank --</option>
                ${banks.map(bank => `<option value="${bank}">${bank}</option>`).join('')}
            </select>
        </div>
    `;
}

// Payment Security Badge
function showSecurityBadge() {
    return `
        <div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #00bac7;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-shield-alt" style="color: #00bac7; font-size: 24px;"></i>
                <div>
                    <div style="font-weight: 600; color: #333; margin-bottom: 3px;">
                        🔒 100% Secure Payment
                    </div>
                    <div style="font-size: 12px; color: #666;">
                        Your payment information is encrypted and secure
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                <span style="background: white; padding: 5px 10px; border-radius: 5px; font-size: 11px; font-weight: 600; color: #666;">
                    <i class="fas fa-lock"></i> SSL Encrypted
                </span>
                <span style="background: white; padding: 5px 10px; border-radius: 5px; font-size: 11px; font-weight: 600; color: #666;">
                    <i class="fas fa-check-circle"></i> PCI DSS Compliant
                </span>
            </div>
        </div>
    `;
}

// Offer Banner
function showOfferBanner() {
    return `
        <div style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #ff6b6b, #ee5a6f); border-radius: 10px; color: white; text-align: center;">
            <div style="font-size: 16px; font-weight: 700; margin-bottom: 5px;">
                🎁 Special Offer!
            </div>
            <div style="font-size: 13px; opacity: 0.95;">
                Use code <strong style="background: rgba(255,255,255,0.3); padding: 3px 8px; border-radius: 4px;">FIRST10</strong> for 10% extra cashback on UPI payments
            </div>
        </div>
    `;
}

// Export functions for global use
window.processRazorpayPayment = processRazorpayPayment;
window.processStandardPayment = processStandardPayment;
window.closePaymentSuccess = closePaymentSuccess;
window.showEMICalculator = showEMICalculator;
window.showWalletOptions = showWalletOptions;
window.showBankSelection = showBankSelection;
window.calculateEMI = calculateEMI;

console.log('💳 Enhanced Payment Module Loaded');
