/**
 * Admin API Client
 * Handles all API calls for the admin panel with authentication
 */

class AdminAPIClient {
    constructor() {
        this.baseURL = '/api/v1/admin';
        this.token = this.getAuthToken();
        this.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        if (this.token) {
            this.headers['Authorization'] = `Bearer ${this.token}`;
        }

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            this.headers['X-CSRF-TOKEN'] = csrfToken.content;
        }
    }

    /**
     * Get authentication token from localStorage
     */
    getAuthToken() {
        return localStorage.getItem('admin_api_token');
    }

    /**
     * Set authentication token
     */
    setAuthToken(token) {
        localStorage.setItem('admin_api_token', token);
        this.token = token;
        this.headers['Authorization'] = `Bearer ${token}`;
    }

    /**
     * Remove authentication token
     */
    removeAuthToken() {
        localStorage.removeItem('admin_api_token');
        this.token = null;
        delete this.headers['Authorization'];
    }

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            ...options,
            headers: {
                ...this.headers,
                ...options.headers
            }
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    /**
     * GET request
     */
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        
        return this.request(url, {
            method: 'GET'
        });
    }

    /**
     * POST request
     */
    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    /**
     * PUT request
     */
    async put(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    /**
     * DELETE request
     */
    async delete(endpoint) {
        return this.request(endpoint, {
            method: 'DELETE'
        });
    }

    // ==================== DASHBOARD ====================

    /**
     * Get dashboard statistics
     */
    async getDashboardStats() {
        return this.get('/dashboard/stats');
    }

    // ==================== PRODUCTS ====================

    /**
     * Get all products with optional filters
     */
    async getProducts(params = {}) {
        return this.get('/products', params);
    }

    /**
     * Get a single product
     */
    async getProduct(id) {
        return this.get(`/products/${id}`);
    }

    /**
     * Create a new product
     */
    async createProduct(data) {
        return this.post('/products', data);
    }

    /**
     * Update a product
     */
    async updateProduct(id, data) {
        return this.put(`/products/${id}`, data);
    }

    /**
     * Delete a product
     */
    async deleteProduct(id) {
        return this.delete(`/products/${id}`);
    }

    /**
     * Toggle product status (active/inactive)
     */
    async toggleProductStatus(id) {
        return this.post(`/products/${id}/toggle-status`);
    }

    /**
     * Toggle product featured status
     */
    async toggleProductFeatured(id) {
        return this.post(`/products/${id}/toggle-featured`);
    }

    // ==================== CATEGORIES ====================

    /**
     * Get all categories with optional filters
     */
    async getCategories(params = {}) {
        return this.get('/categories', params);
    }

    /**
     * Get a single category
     */
    async getCategory(id) {
        return this.get(`/categories/${id}`);
    }

    /**
     * Create a new category
     */
    async createCategory(data) {
        return this.post('/categories', data);
    }

    /**
     * Update a category
     */
    async updateCategory(id, data) {
        return this.put(`/categories/${id}`, data);
    }

    /**
     * Delete a category
     */
    async deleteCategory(id) {
        return this.delete(`/categories/${id}`);
    }

    /**
     * Toggle category status (active/inactive)
     */
    async toggleCategoryStatus(id) {
        return this.post(`/categories/${id}/toggle-status`);
    }

    // ==================== BRANDS ====================

    /**
     * Get all brands with optional filters
     */
    async getBrands(params = {}) {
        return this.get('/brands', params);
    }

    /**
     * Get a single brand
     */
    async getBrand(id) {
        return this.get(`/brands/${id}`);
    }

    /**
     * Create a new brand
     */
    async createBrand(data) {
        return this.post('/brands', data);
    }

    /**
     * Update a brand
     */
    async updateBrand(id, data) {
        return this.put(`/brands/${id}`, data);
    }

    /**
     * Delete a brand
     */
    async deleteBrand(id) {
        return this.delete(`/brands/${id}`);
    }

    /**
     * Toggle brand status (active/inactive)
     */
    async toggleBrandStatus(id) {
        return this.post(`/brands/${id}/toggle-status`);
    }

    /**
     * Toggle brand featured status
     */
    async toggleBrandFeatured(id) {
        return this.post(`/brands/${id}/toggle-featured`);
    }
}

// Create global instance
window.adminAPI = new AdminAPIClient();

// ==================== HELPER FUNCTIONS ====================

/**
 * Show loading indicator
 */
function showLoading(element) {
    if (element) {
        element.classList.add('loading');
        element.style.opacity = '0.6';
        element.style.pointerEvents = 'none';
    }
}

/**
 * Hide loading indicator
 */
function hideLoading(element) {
    if (element) {
        element.classList.remove('loading');
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    }
}

/**
 * Show success message
 */
function showSuccess(message) {
    // You can customize this to use your preferred notification library
    alert('Success: ' + message);
}

/**
 * Show error message
 */
function showError(message) {
    // You can customize this to use your preferred notification library
    alert('Error: ' + message);
}

/**
 * Confirm action with user
 */
function confirmAction(message) {
    return confirm(message);
}

/**
 * Handle API errors
 */
function handleAPIError(error) {
    console.error('API Error:', error);
    
    if (error.message) {
        showError(error.message);
    } else {
        showError('An unexpected error occurred');
    }

    // If unauthorized, redirect to login
    if (error.status === 401) {
        window.location.href = '/admin/login';
    }
}

/**
 * Format date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

/**
 * Debounce function for search inputs
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        AdminAPIClient,
        showLoading,
        hideLoading,
        showSuccess,
        showError,
        confirmAction,
        handleAPIError,
        formatDate,
        formatCurrency,
        debounce
    };
}
