# Wishlist Feature Guide

## Overview
The wishlist feature allows users to save products they're interested in for later viewing and purchasing.

## How It Works

### 1. **Quick Wishlist Sidebar** (On Homepage)
When you click the heart icon in the header:
- A **sidebar slides in from the right** showing your wishlist items
- This is NOT a separate page - it's a quick preview overlay
- You can:
  - View your saved items
  - Remove items
  - Click "View Full Wishlist" button at the bottom to see the complete page

### 2. **Full Wishlist Page** (`my-wishlist.php`)
Dedicated page for managing your entire wishlist:
- Access via:
  - Click "View Full Wishlist" button in the sidebar
  - Direct URL: `http://localhost/vini/my-wishlist.php`
  - User dropdown menu: "My Wishlist" option
  - Dashboard: Click the wishlist stat card
- Features:
  - Grid view of all wishlist products
  - Product images, prices, stock status
  - Add to cart directly from wishlist
  - Remove individual items
  - Clear entire wishlist
  - See discount badges and original prices

### 3. **Dashboard Wishlist Section**
Accessible from the dashboard menu:
- Shows all wishlist items
- Manage items without leaving the dashboard
- Add to cart functionality
- Remove items individually or clear all

## User Journey

### Adding Items to Wishlist
1. Browse products on the homepage
2. Click the heart icon on any product card
3. Heart icon fills/turns red
4. Item is saved to your wishlist
5. Wishlist count badge updates

### Viewing Wishlist
**Quick View (Sidebar):**
1. Click heart icon in header
2. Sidebar slides in from right
3. See recent wishlist items
4. Click items to view details

**Full View (Dedicated Page):**
1. Click heart icon in header → Click "View Full Wishlist" button in sidebar
   OR
2. Click user profile icon → Select "My Wishlist"
   OR
3. Go to Dashboard → Click "Wishlist" in sidebar menu
   OR
4. Visit directly: `http://localhost/vini/my-wishlist.php`

### Managing Wishlist
- **Remove Item**: Click the X button on any product card
- **Add to Cart**: Click "Add to Cart" button on product card
- **Clear All**: Click "Clear All" button (only shows when items exist)
- **View Details**: Click on product image or name

## API Endpoints

All wishlist operations use `api_wishlist.php`:

- `GET api_wishlist.php?action=list` - Get all wishlist items
- `POST api_wishlist.php?action=add` - Add product to wishlist
- `POST api_wishlist.php?action=toggle` - Toggle product in/out of wishlist
- `DELETE api_wishlist.php?action=remove&product_id=X` - Remove item
- `DELETE api_wishlist.php?action=clear` - Clear entire wishlist
- `GET api_wishlist.php?action=check&product_id=X` - Check if product is in wishlist
- `GET api_wishlist.php?action=count` - Get total wishlist item count

## Files Involved

### Backend
- `api_wishlist.php` - Main wishlist API
- `session_manager.php` - Manages session-based wishlist for anonymous users

### Frontend Pages
- `index.php` - Homepage with wishlist sidebar
- `my-wishlist.php` - Full wishlist page
- `dashboard.php` - Dashboard with wishlist section

### JavaScript
- `script.js` - Wishlist sidebar functionality
- `auth.js` - User authentication and dropdown menu
- `my-wishlist.php` (inline JS) - Full wishlist page functionality

### Database
- Table: `wishlist`
  - `id` - Primary key
  - `user_id` - Foreign key to users table
  - `session_id` - For anonymous users
  - `product_id` - Foreign key to products table
  - `added_at` - Timestamp

## Session Management

### For Logged-in Users
- Wishlist tied to `user_id`
- Persists across sessions
- Synced across devices

### For Anonymous Users
- Wishlist tied to `session_id`
- Persists during browsing session
- Lost when cookies/session cleared
- Automatically migrates to user account on login

## Testing

### Test the Sidebar
1. Open `http://localhost/vini/index.php`
2. Click the heart icon in the header
3. Sidebar should slide in from right
4. Console logs will show: "Wishlist button clicked", "Opening wishlist sidebar..."

### Test the Full Page
1. Open `http://localhost/vini/my-wishlist.php`
2. Should see full grid view of wishlist items
3. Try adding items to cart
4. Try removing items
5. Try clearing all

### Test API
1. Open `http://localhost/vini/test-wishlist.html`
2. Test all API endpoints
3. Check console for responses

## Common Issues

### Sidebar Not Opening?
**Check console logs:**
```javascript
// You should see these logs when clicking heart icon:
"Wishlist button clicked"
"Opening wishlist sidebar..."
"Sidebar element: [object HTMLDivElement]"
```

**If no logs appear:**
- JavaScript error preventing execution
- Check browser console for errors
- Ensure `script.js` is loaded

**If logs appear but sidebar doesn't show:**
- CSS transition issue
- Check if `right` property changes from `-400px` to `0`
- Verify z-index (should be 10001)

### Items Not Loading?
- Check database connection
- Verify products exist in database
- Check browser console for API errors
- Test API directly: `api_wishlist.php?action=list`

## Benefits

1. **Persistent Shopping**: Save items for later without adding to cart
2. **Comparison**: Collect products to compare before buying
3. **Gift Lists**: Create lists for special occasions
4. **Price Tracking**: Monitor products for price changes
5. **Quick Access**: Both sidebar and full page options
6. **Seamless**: Works for both logged-in and anonymous users

## Next Steps

Future enhancements could include:
- Share wishlist via link
- Multiple wishlist collections
- Email notifications for price drops
- Move items between wishlist and cart
- Wishlist analytics in dashboard
