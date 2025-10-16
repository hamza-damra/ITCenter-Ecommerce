import { apiClient } from './client';

// Home API
export const homeApi = {
    /**
     * Get home page data
     * @returns {Promise}
     */
    async get() {
        return apiClient.get('/home');
    },

    /**
     * Get specific section data
     * @param {string} section - Section name (featured, new, bestsellers, sale)
     * @returns {Promise}
     */
    async getSection(section) {
        return apiClient.get(`/home/${section}`);
    },
};

// Products API
export const productsApi = {
    /**
     * Get all products with optional filters
     * @param {Object} filters - Filter options
     * @returns {Promise}
     */
    async getAll(filters = {}) {
        return apiClient.get('/products', filters);
    },

    /**
     * Get a single product by slug
     * @param {string} slug - Product slug
     * @returns {Promise}
     */
    async getBySlug(slug) {
        return apiClient.get(`/products/${slug}`);
    },

    /**
     * Search products
     * @param {string} query - Search query
     * @returns {Promise}
     */
    async search(query) {
        return apiClient.get('/products/search', { q: query });
    },

    /**
     * Get featured products
     * @returns {Promise}
     */
    async getFeatured() {
        return apiClient.get('/products/featured');
    },
};

// Brands API
export const brandsApi = {
    /**
     * Get all brands
     * @returns {Promise}
     */
    async getAll() {
        return apiClient.get('/brands');
    },

    /**
     * Get a single brand by slug with products
     * @param {string} slug - Brand slug
     * @returns {Promise}
     */
    async getBySlug(slug) {
        return apiClient.get(`/brands/${slug}`);
    },
};

// Categories API
export const categoriesApi = {
    /**
     * Get all categories
     * @returns {Promise}
     */
    async getAll() {
        return apiClient.get('/categories');
    },

    /**
     * Get a single category by slug with products
     * @param {string} slug - Category slug
     * @returns {Promise}
     */
    async getBySlug(slug) {
        return apiClient.get(`/categories/${slug}`);
    },
};

// Offers API
export const offersApi = {
    /**
     * Get all offers
     * @returns {Promise}
     */
    async getAll() {
        return apiClient.get('/offers');
    },

    /**
     * Get a single offer by slug
     * @param {string} slug - Offer slug
     * @returns {Promise}
     */
    async getBySlug(slug) {
        return apiClient.get(`/offers/${slug}`);
    },
};

// Reviews API
export const reviewsApi = {
    /**
     * Get reviews for a product
     * @param {string} productSlug - Product slug
     * @param {Object} params - Query parameters
     * @returns {Promise}
     */
    async getProductReviews(productSlug, params = {}) {
        return apiClient.get(`/products/${productSlug}/reviews`, params);
    },

    /**
     * Submit a new review
     * @param {string} productSlug - Product slug
     * @param {Object} data - Review data
     * @returns {Promise}
     */
    async submit(productSlug, data) {
        return apiClient.post(`/products/${productSlug}/reviews`, data);
    },

    /**
     * Update a review
     * @param {number} reviewId - Review ID
     * @param {Object} data - Review data
     * @returns {Promise}
     */
    async update(reviewId, data) {
        return apiClient.put(`/reviews/${reviewId}`, data);
    },

    /**
     * Delete a review
     * @param {number} reviewId - Review ID
     * @returns {Promise}
     */
    async delete(reviewId) {
        return apiClient.delete(`/reviews/${reviewId}`);
    },

    /**
     * Mark review as helpful
     * @param {number} reviewId - Review ID
     * @returns {Promise}
     */
    async markHelpful(reviewId) {
        return apiClient.post(`/reviews/${reviewId}/helpful`);
    },

    /**
     * Mark review as unhelpful
     * @param {number} reviewId - Review ID
     * @returns {Promise}
     */
    async markUnhelpful(reviewId) {
        return apiClient.post(`/reviews/${reviewId}/unhelpful`);
    },
};

// Cart API
export const cartApi = {
    /**
     * Get cart items
     * @returns {Promise}
     */
    async getItems() {
        return apiClient.get('/cart');
    },

    /**
     * Add product to cart
     * @param {number} productId - Product ID
     * @param {number} quantity - Quantity to add
     * @returns {Promise}
     */
    async addItem(productId, quantity = 1) {
        return apiClient.post(`/cart/${productId}`, { quantity });
    },

    /**
     * Update cart item quantity
     * @param {number} productId - Product ID
     * @param {number} quantity - New quantity
     * @returns {Promise}
     */
    async updateItem(productId, quantity) {
        return apiClient.put(`/cart/${productId}`, { quantity });
    },

    /**
     * Remove product from cart
     * @param {number} productId - Product ID
     * @returns {Promise}
     */
    async removeItem(productId) {
        return apiClient.delete(`/cart/${productId}`);
    },

    /**
     * Check if product is in cart
     * @param {number} productId - Product ID
     * @returns {Promise}
     */
    async checkItem(productId) {
        return apiClient.get(`/cart/check/${productId}`);
    },
};

// Favorites API
export const favoritesApi = {
    /**
     * Get all favorite products
     * @returns {Promise}
     */
    async getAll() {
        return apiClient.get('/favorites');
    },

    /**
     * Toggle product in favorites
     * @param {number} productId - Product ID
     * @returns {Promise}
     */
    async toggle(productId) {
        return apiClient.post(`/favorites/${productId}`);
    },

    /**
     * Check if product is in favorites
     * @param {number} productId - Product ID
     * @returns {Promise}
     */
    async check(productId) {
        return apiClient.get(`/favorites/check/${productId}`);
    },
};

// User API
export const userApi = {
    /**
     * Get user profile
     * @returns {Promise}
     */
    async getProfile() {
        return apiClient.get('/user/profile');
    },

    /**
     * Update user profile
     * @param {FormData} data - Profile data
     * @returns {Promise}
     */
    async updateProfile(data) {
        return apiClient.put('/user/profile', data);
    },

    /**
     * Change password
     * @param {Object} data - Password data
     * @returns {Promise}
     */
    async changePassword(data) {
        return apiClient.post('/user/change-password', data);
    },

    /**
     * Get user reviews
     * @returns {Promise}
     */
    async getReviews() {
        return apiClient.get('/user/reviews');
    },

    /**
     * Get user statistics
     * @returns {Promise}
     */
    async getStats() {
        return apiClient.get('/user/stats');
    },
};
