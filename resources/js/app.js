import './bootstrap';

// Main application entry point
import { ProductGrid } from './components/ProductGrid';
import { cartManager } from './components/CartManager';
import { favoritesManager } from './components/FavoritesManager';

/**
 * Initialize application
 */
class App {
    constructor() {
        this.components = new Map();
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initComponents());
        } else {
            this.initComponents();
        }
    }

    initComponents() {
        // Initialize product grids
        document.querySelectorAll('[data-component="products-grid"]').forEach(element => {
            const filters = this.getDataAttributes(element, 'filter-');
            const grid = new ProductGrid(element, { filters });
            this.components.set(element, grid);
        });

        // Initialize cart UI updates
        this.initCartUI();

        // Initialize favorites UI updates
        this.initFavoritesUI();

        // Initialize notifications
        this.initNotifications();
    }

    initCartUI() {
        // Update cart count in header
        cartManager.subscribe((state) => {
            document.querySelectorAll('[data-cart-count]').forEach(element => {
                element.textContent = state.count;
                element.classList.toggle('has-items', state.count > 0);
            });

            document.querySelectorAll('[data-cart-total]').forEach(element => {
                element.textContent = `$${state.total.toFixed(2)}`;
            });
        });
    }

    initFavoritesUI() {
        // Update favorites count in header
        favoritesManager.subscribe((state) => {
            document.querySelectorAll('[data-favorites-count]').forEach(element => {
                element.textContent = state.count;
                element.classList.toggle('has-items', state.count > 0);
            });

            // Update favorite buttons
            document.querySelectorAll('.btn-favorite').forEach(button => {
                const productId = parseInt(button.dataset.productId);
                const isFavorite = state.favoriteIds.includes(productId);
                button.classList.toggle('active', isFavorite);
                button.textContent = isFavorite ? '♥' : '♡';
            });
        });
    }

    initNotifications() {
        // Listen for cart notifications
        document.addEventListener('cart:notification', (e) => {
            this.showNotification(e.detail.message, e.detail.type);
        });

        // Listen for favorites notifications
        document.addEventListener('favorites:notification', (e) => {
            this.showNotification(e.detail.message, e.detail.type);
        });
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;

        // Add to DOM
        const container = document.querySelector('.notifications-container') || this.createNotificationContainer();
        container.appendChild(notification);

        // Auto-remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    createNotificationContainer() {
        const container = document.createElement('div');
        container.className = 'notifications-container';
        document.body.appendChild(container);
        return container;
    }

    getDataAttributes(element, prefix) {
        const attributes = {};
        Array.from(element.attributes).forEach(attr => {
            if (attr.name.startsWith(`data-${prefix}`)) {
                const key = attr.name.replace(`data-${prefix}`, '');
                attributes[key] = attr.value;
            }
        });
        return attributes;
    }
}

// Initialize app
const app = new App();

// Export for global access if needed
window.App = app;
window.cartManager = cartManager;
window.favoritesManager = favoritesManager;
