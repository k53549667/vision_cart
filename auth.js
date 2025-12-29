/**
 * VisionKart Authentication Helper
 * Manages user authentication state across the frontend
 */

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
            const response = await fetch('api_auth.php?action=check-session', {
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
            const response = await fetch('api_auth.php?action=current-user', {
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
            const response = await fetch('api_auth.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
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
            const response = await fetch('api_auth.php?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
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
            const response = await fetch('api_auth.php?action=logout', {
                method: 'POST',
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.isAuthenticated = false;
                this.user = null;
                localStorage.removeItem('visionkart_user');
                this.updateUI();
                
                // Redirect to home page
                if (window.location.pathname !== '/index.php' && window.location.pathname !== '/') {
                    window.location.href = 'index.php';
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
                const dropdown = document.createElement('div');
                dropdown.id = 'userDropdown';
                dropdown.className = 'user-dropdown';
                dropdown.innerHTML = `
                    <a href="dashboard.php"><i class="fas fa-th-large"></i> My Dashboard</a>
                    <a href="dashboard.php#profile"><i class="fas fa-user"></i> My Profile</a>
                    <a href="my-wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a>
                    <a href="dashboard.php#orders"><i class="fas fa-shopping-bag"></i> My Orders</a>
                    <a href="dashboard.php#addresses"><i class="fas fa-map-marker-alt"></i> My Addresses</a>
                    ${this.user.role === 'admin' ? '<a href="admin.php"><i class="fas fa-cog"></i> Admin Panel</a>' : ''}
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
            loginBtn.href = 'login.php';
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
    requireAuth(redirectUrl = 'login.php') {
        if (!this.isAuthenticated) {
            const currentUrl = window.location.pathname + window.location.search;
            window.location.href = `${redirectUrl}?redirect=${encodeURIComponent(currentUrl)}`;
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
