/**
 * VisionKart Authentication Helper
 * Manages user authentication state across the frontend
 */

// API Base URL helper for authentication
const getAuthApiBaseUrl = () => {
    const path = window.location.pathname;
    const pathParts = path.split('/').filter(p => p);
    const projectFolder = pathParts[0] || '';
    return window.location.origin + '/' + projectFolder + '/api';
};
const AUTH_API_BASE_URL = getAuthApiBaseUrl();

// CSRF Token Helper for auth operations
const getAuthCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
};

// Helper to get pages base URL (for links to pages from root or from pages folder)
const getPagesBaseUrl = () => {
    const path = window.location.pathname;
    const pathParts = path.split('/').filter(p => p);
    // If we're in pages folder, links are relative (same folder)
    if (pathParts.length > 1 && pathParts[1] === 'pages') {
        return '';
    }
    // If we're at root level, prefix with pages/
    return 'pages/';
};

// Helper to get admin base URL (for links to admin panel)
const getAdminBaseUrl = () => {
    const path = window.location.pathname;
    const pathParts = path.split('/').filter(p => p);
    // If we're in pages folder, go up and into admin
    if (pathParts.length > 1 && pathParts[1] === 'pages') {
        return '../admin/admin-pages/';
    }
    // If we're at root level, prefix with admin/admin-pages/
    return 'admin/admin-pages/';
};

// Helper to get root base URL (for links back to index from pages folder)  
const getRootBaseUrl = () => {
    const path = window.location.pathname;
    const pathParts = path.split('/').filter(p => p);
    // If we're in pages folder, go up one level
    if (pathParts.length > 1 && pathParts[1] === 'pages') {
        return '../';
    }
    // If we're at root level, stay at current level
    return '';
};

class AuthManager {
    constructor() {
        this.user = null;
        this.isAuthenticated = false;
        this.init();
    }

    /**
     * Initialize authentication state
     */
    async init() {
        await this.checkSession();
        this.updateUI();
    }

    /**
     * Check if user session is valid
     */
    async checkSession() {
        try {
            const response = await fetch(`${AUTH_API_BASE_URL}/api_auth.php?action=check-session`, {
                credentials: 'include'
            });
            const data = await response.json();

            if (data.success && data.authenticated) {
                this.isAuthenticated = true;
                this.user = {
                    id: data.user_id,
                    email: data.user_email,
                    name: data.user_name,
                    role: data.user_role
                };
                
                // Also store in localStorage for quick access
                localStorage.setItem('visionkart_user', JSON.stringify(this.user));
                
                return true;
            } else {
                this.isAuthenticated = false;
                this.user = null;
                localStorage.removeItem('visionkart_user');
                return false;
            }
        } catch (error) {
            console.error('Session check failed:', error);
            this.isAuthenticated = false;
            this.user = null;
            return false;
        }
    }

    /**
     * Get current user details
     */
    async getCurrentUser() {
        try {
            const response = await fetch(`${AUTH_API_BASE_URL}/api_auth.php?action=current-user`, {
                credentials: 'include'
            });
            const data = await response.json();

            if (data.success) {
                this.user = data.user;
                localStorage.setItem('visionkart_user', JSON.stringify(data.user));
                return data.user;
            }
            return null;
        } catch (error) {
            console.error('Failed to get current user:', error);
            return null;
        }
    }

    /**
     * Login user
     */
    async login(email, password) {
        try {
            const headers = {
                'Content-Type': 'application/json'
            };
            // Add CSRF token if available
            const csrfToken = getAuthCsrfToken();
            if (csrfToken) {
                headers['X-CSRF-Token'] = csrfToken;
            }
            
            const response = await fetch(`${AUTH_API_BASE_URL}/api_auth.php?action=login`, {
                method: 'POST',
                headers: headers,
                credentials: 'include',
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (data.success) {
                this.isAuthenticated = true;
                this.user = data.user;
                localStorage.setItem('visionkart_user', JSON.stringify(data.user));
                this.updateUI();
                return { success: true, user: data.user };
            } else {
                return { success: false, message: data.message };
            }
        } catch (error) {
            console.error('Login failed:', error);
            return { success: false, message: 'Network error' };
        }
    }

    /**
     * Register new user
     */
    async register(userData) {
        try {
            const headers = {
                'Content-Type': 'application/json'
            };
            // Add CSRF token if available
            const csrfToken = getAuthCsrfToken();
            if (csrfToken) {
                headers['X-CSRF-Token'] = csrfToken;
            }
            
            const response = await fetch(`${AUTH_API_BASE_URL}/api_auth.php?action=register`, {
                method: 'POST',
                headers: headers,
                credentials: 'include',
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (data.success) {
                this.isAuthenticated = true;
                this.user = data.user;
                localStorage.setItem('visionkart_user', JSON.stringify(data.user));
                this.updateUI();
                return { success: true, user: data.user };
            } else {
                return { success: false, message: data.message };
            }
        } catch (error) {
            console.error('Registration failed:', error);
            return { success: false, message: 'Network error' };
        }
    }

    /**
     * Logout user
     */
    async logout() {
        try {
            const response = await fetch(`${AUTH_API_BASE_URL}/api_auth.php?action=logout`, {
                method: 'POST',
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.isAuthenticated = false;
                this.user = null;
                
                // Clear all user-related localStorage data
                localStorage.removeItem('visionkart_user');
                localStorage.removeItem('visionkart_cart');
                localStorage.removeItem('visionkart_wishlist');
                localStorage.removeItem('visionkart_session_id');
                
                // Update UI
                this.updateUI();
                
                // Update cart count to 0
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = '0';
                }
                
                // Update wishlist count to 0
                const wishlistCountElement = document.querySelector('.wishlist-count');
                if (wishlistCountElement) {
                    wishlistCountElement.textContent = '0';
                }
                
                // Redirect to home page
                const rootUrl = getRootBaseUrl() + 'index.php';
                const currentPath = window.location.pathname;
                if (!currentPath.endsWith('/index.php') && !currentPath.endsWith('/')) {
                    window.location.href = rootUrl;
                } else {
                    // If already on home page, reload to refresh the state
                    window.location.reload();
                }
                
                return { success: true };
            }
        } catch (error) {
            console.error('Logout failed:', error);
        }
        return { success: false };
    }

    /**
     * Update UI based on authentication state
     */
    updateUI() {
        const loginBtn = document.getElementById('loginBtn');
        const signupBtn = document.getElementById('signupBtn');
        const authContainer = document.getElementById('authContainer');

        if (!loginBtn) return;

        if (this.isAuthenticated && this.user) {
            // Hide signup button when logged in
            if (signupBtn) {
                signupBtn.style.display = 'none';
            }

            // Replace login button with user menu
            loginBtn.innerHTML = `
                <i class="fas fa-user-circle"></i>
                <span>${this.user.name.split(' ')[0]}</span>
            `;
            loginBtn.href = '#';
            loginBtn.title = 'My Account';
            
            // Add dropdown menu if not exists
            if (!document.getElementById('userDropdown')) {
                const pagesBase = getPagesBaseUrl();
                const adminBase = getAdminBaseUrl();
                const dropdown = document.createElement('div');
                dropdown.id = 'userDropdown';
                dropdown.className = 'user-dropdown';
                dropdown.innerHTML = `
                    <a href="${pagesBase}dashboard.php"><i class="fas fa-th-large"></i> My Dashboard</a>
                    <a href="${pagesBase}dashboard.php#profile"><i class="fas fa-user"></i> My Profile</a>
                    <a href="${pagesBase}my-wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a>
                    <a href="${pagesBase}dashboard.php#orders"><i class="fas fa-shopping-bag"></i> My Orders</a>
                    <a href="${pagesBase}dashboard.php#addresses"><i class="fas fa-map-marker-alt"></i> My Addresses</a>
                    ${this.user.role === 'admin' ? `<a href="${adminBase}admin.php"><i class="fas fa-cog"></i> Admin Panel</a>` : ''}
                    <hr>
                    <a href="#" id="logoutLink"><i class="fas fa-sign-out-alt"></i> Logout</a>
                `;
                
                // Insert dropdown after auth container
                if (authContainer) {
                    authContainer.style.position = 'relative';
                    authContainer.appendChild(dropdown);
                } else {
                    loginBtn.parentElement.appendChild(dropdown);
                }

                // Toggle dropdown
                loginBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    dropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!loginBtn.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });

                // Handle logout
                document.getElementById('logoutLink').addEventListener('click', async (e) => {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        await this.logout();
                    }
                });
            }
        } else {
            // Show login button and signup button
            loginBtn.innerHTML = '<i class="fas fa-user"></i>';
            loginBtn.href = getPagesBaseUrl() + 'login.php';
            loginBtn.title = 'Login';
            
            // Show signup button
            if (signupBtn) {
                signupBtn.style.display = 'inline-block';
            }
            
            // Remove dropdown if exists
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.remove();
            }
        }
    }

    /**
     * Require authentication (redirect if not logged in)
     */
    requireAuth(redirectUrl = null) {
        if (!this.isAuthenticated) {
            const loginUrl = redirectUrl || (getPagesBaseUrl() + 'login.php');
            const currentUrl = window.location.pathname + window.location.search;
            window.location.href = `${loginUrl}?redirect=${encodeURIComponent(currentUrl)}`;
            return false;
        }
        return true;
    }

    /**
     * Get user info
     */
    getUser() {
        return this.user;
    }

    /**
     * Check if user is admin
     */
    isAdmin() {
        return this.isAuthenticated && this.user && this.user.role === 'admin';
    }
}

// Create global auth instance
const authManager = new AuthManager();

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuthManager;
}
