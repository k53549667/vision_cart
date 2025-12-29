# VisionKart - Premium Eyewear E-commerce Website

A modern, responsive e-commerce website inspired by Lenskart, featuring eyewear products including eyeglasses, sunglasses, and contact lenses.

## Features

### 🎨 Design Features
- Modern, clean UI with gradient backgrounds
- Fully responsive design for all devices
- Smooth animations and transitions
- Interactive product cards with hover effects
- Category-based navigation
- Eye-catching hero section

### 🛍️ E-commerce Features
- Product catalog with 8+ sample products
- Add to cart functionality
- Wishlist feature
- Product search and filtering
- Rating and reviews display
- Pricing with discounts
- Shopping cart counter
- Notification system
- **💳 Multiple Payment Options**
- **🛒 Complete Checkout System**
- **📦 Order Management**

### 💳 Payment Features (NEW!)
- **Credit/Debit Cards** - Visa, Mastercard, RuPay, Amex
- **UPI Payments** - Google Pay, PhonePe, Paytm
- **Digital Wallets** - Paytm, PhonePe, Amazon Pay, MobiKwik
- **Net Banking** - All major banks
- **EMI Options** - 3-12 months installments
- **Cash on Delivery** - Pay on delivery
- **Secure Payment Processing**
- **Order Confirmation System**

### 📱 Sections Included
1. **Top Banner** - Promotional offers
2. **Navigation Header** - Logo, menu, search, and cart
3. **Hero Section** - Eye-catching banner with CTA
4. **Category Icons** - Quick access to product types
5. **Featured Categories** - Trending frame styles
6. **Services Grid** - Store services and offers
7. **Products Section** - Main product catalog
8. **Brands Section** - Featured brand collections
9. **Buy Your Way** - Purchase options (home trial, WhatsApp, store visit)
10. **Footer** - Links, social media, and app download

## Technologies Used
- **HTML5** - Semantic structure
- **CSS3** - Modern styling with flexbox & grid
- **JavaScript (ES6)** - Interactive features
- **Font Awesome** - Icons library

## Product Categories
- Eyeglasses
- Sunglasses
- Contact Lenses
- Computer Glasses (Blue Light Blockers)
- Kids Glasses
- Zero Power Glasses

## Key Features

### Shopping Cart
- Add items to cart
- Track cart items with counter
- Visual feedback on additions

### Product Cards
- Product images (placeholder design)
- Product name and category
- Star ratings with review counts
- Original and discounted pricing
- Discount percentage badges
- Add to cart and wishlist buttons

### User Interactions
- Smooth scrolling navigation
- Real-time search filtering
- Animated notifications
- Hover effects on all interactive elements
- Responsive menu for mobile devices

## Color Scheme
- Primary: `#00bac7` (Cyan)
- Secondary: `#333333` (Dark Gray)
- Accent: `#ff6b6b` (Coral Red)
- Background: `#f5f5f5` (Light Gray)

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Getting Started

1. Open `index.php` in a web browser
2. Browse through different sections
3. Use the search bar to filter products
4. Click on products to add them to cart
5. Add items to wishlist

## File Structure
```
vini/
├── index.php              # Main HTML file
├── checkout.html           # Dedicated checkout page (NEW!)
├── payment-methods.html    # Payment options showcase (NEW!)
├── style.css              # Main styling
├── script.js              # JavaScript functionality
├── payment.js             # Payment processing module (NEW!)
├── PAYMENT_GUIDE.md       # Complete payment documentation (NEW!)
├── admin.html             # Admin panel
├── product-detail.html    # Product detail page
└── README.md              # This file
```

## Customization

### Adding New Products
Edit the `products` array in `script.js`:

```javascript
{
    id: 9,
    name: "Your Product Name",
    type: "Product Type",
    price: 1299,
    originalPrice: 2599,
    discount: "50% OFF",
    rating: 4.5,
    reviews: 100,
    badge: "NEW",
    image: null
}
```

### Changing Colors
Modify CSS variables in `style.css`:

```css
:root {
    --primary-color: #00bac7;
    --secondary-color: #333333;
    --accent-color: #ff6b6b;
}
```

## Future Enhancements
- Backend integration for real data
- User authentication and accounts
- ~~Payment gateway integration~~ ✅ **COMPLETED**
- Order tracking system
- Product detail pages
- Advanced filtering options
- Virtual try-on feature
- Customer reviews system
- Email notifications for orders
- SMS order confirmations
- Real payment gateway (Razorpay/Stripe) integration
- Shipping provider integration

## Credits
Design inspired by Lenskart.com

## 💳 Payment System

### How to Use the Payment System

1. **Add products to cart** from any product page
2. **Click cart icon** and select "Proceed to Checkout"
3. **You'll be redirected** to the checkout page
4. **Fill in delivery details** (name, address, phone, etc.)
5. **Select your payment method**:
   - Card Payment - Enter card details
   - UPI - Enter UPI ID
   - Wallet - Select provider
   - Net Banking - Choose bank
   - EMI - Select installment plan
   - Cash on Delivery - No payment needed
6. **Click "Place Order Securely"**
7. **Order confirmed!** Order is saved and cart is cleared

### Payment Pages

- **`checkout.html`** - Main checkout page with all payment options
- **`payment-methods.html`** - Showcase of all available payment methods
- **`PAYMENT_GUIDE.md`** - Complete documentation for payment system

### Security Features

✅ SSL Encryption  
✅ PCI DSS Compliance Ready  
✅ Secure input validation  
✅ Card number formatting  
✅ CVV verification  

### Test Mode

Currently in **test mode** - no real payments are processed:
- Test Card: 4111 1111 1111 1111
- Test UPI: test@upi
- All methods work for testing

### Going Live

To enable real payments:
1. Sign up for Razorpay or Stripe
2. Get API keys
3. Update `payment.js` with your keys
4. Enable live mode in configuration
5. Test thoroughly before launch

For detailed payment documentation, see **[PAYMENT_GUIDE.md](PAYMENT_GUIDE.md)**

## License
Free to use for educational purposes

---

**Built with ❤️ for eyewear enthusiasts**
