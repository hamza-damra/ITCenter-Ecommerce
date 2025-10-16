import { cartApi } from '../api/index';

/**
 * CartManager Component
 * Handles cart operations and state management
 */
export class CartManager {
    constructor() {
        this.items = [];
        this.total = 0;
        this.count = 0;
        this.listeners = [];
        this.init();
    }

    async init() {
        await this.fetchCart();
        this.attachGlobalEvents();
    }

    async fetchCart() {
        try {
            const response = await cartApi.getItems();
            if (response.success) {
                this.items = response.data.items;
                this.total = response.data.total;
                this.count = response.data.items_count;
                this.notifyListeners('cart:updated');
            }
        } catch (error) {
            console.error('Failed to fetch cart:', error);
        }
    }

    async addItem(productId, quantity = 1) {
        try {
            const response = await cartApi.addItem(productId, quantity);
            if (response.success) {
                await this.fetchCart();
                this.showNotification(response.message, 'success');
                return response;
            }
        } catch (error) {
            console.error('Failed to add item:', error);
            this.showNotification('Failed to add item to cart', 'error');
            throw error;
        }
    }

    async updateItem(productId, quantity) {
        try {
            const response = await cartApi.updateItem(productId, quantity);
            if (response.success) {
                await this.fetchCart();
                this.showNotification(response.message, 'success');
                return response;
            }
        } catch (error) {
            console.error('Failed to update item:', error);
            this.showNotification('Failed to update cart', 'error');
            throw error;
        }
    }

    async removeItem(productId) {
        try {
            const response = await cartApi.removeItem(productId);
            if (response.success) {
                await this.fetchCart();
                this.showNotification(response.message, 'success');
                return response;
            }
        } catch (error) {
            console.error('Failed to remove item:', error);
            this.showNotification('Failed to remove item', 'error');
            throw error;
        }
    }

    async checkItem(productId) {
        try {
            const response = await cartApi.checkItem(productId);
            return response.in_cart;
        } catch (error) {
            console.error('Failed to check item:', error);
            return false;
        }
    }

    // Event management
    attachGlobalEvents() {
        document.addEventListener('product:addToCart', async (e) => {
            await this.addItem(e.detail.productId, e.detail.quantity || 1);
        });
    }

    subscribe(callback) {
        this.listeners.push(callback);
        return () => {
            this.listeners = this.listeners.filter(cb => cb !== callback);
        };
    }

    notifyListeners(eventType) {
        this.listeners.forEach(callback => {
            callback({
                type: eventType,
                items: this.items,
                total: this.total,
                count: this.count,
            });
        });
    }

    showNotification(message, type = 'info') {
        // Dispatch custom event for notifications
        const event = new CustomEvent('cart:notification', {
            detail: { message, type },
        });
        document.dispatchEvent(event);
    }

    // Getters
    getItems() {
        return this.items;
    }

    getTotal() {
        return this.total;
    }

    getCount() {
        return this.count;
    }

    isEmpty() {
        return this.count === 0;
    }
}

// Export singleton instance
export const cartManager = new CartManager();
