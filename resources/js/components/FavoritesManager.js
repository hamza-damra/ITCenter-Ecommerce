import { favoritesApi } from '../api/index';

/**
 * FavoritesManager Component
 * Handles favorites operations and state management
 */
export class FavoritesManager {
    constructor() {
        this.favorites = [];
        this.favoriteIds = new Set();
        this.listeners = [];
        this.init();
    }

    async init() {
        await this.fetchFavorites();
        this.attachGlobalEvents();
    }

    async fetchFavorites() {
        try {
            const response = await favoritesApi.getAll();
            if (response.success) {
                this.favorites = response.data.favorites;
                this.favoriteIds = new Set(this.favorites.map(fav => fav.id));
                this.notifyListeners('favorites:updated');
            }
        } catch (error) {
            console.error('Failed to fetch favorites:', error);
        }
    }

    async toggle(productId) {
        try {
            const response = await favoritesApi.toggle(productId);
            if (response.success) {
                if (response.action === 'added') {
                    this.favoriteIds.add(productId);
                } else {
                    this.favoriteIds.delete(productId);
                }
                await this.fetchFavorites();
                this.showNotification(response.message, 'success');
                return response;
            }
        } catch (error) {
            console.error('Failed to toggle favorite:', error);
            this.showNotification('Failed to update favorites', 'error');
            throw error;
        }
    }

    async check(productId) {
        try {
            const response = await favoritesApi.check(productId);
            return response.in_favorites;
        } catch (error) {
            console.error('Failed to check favorite:', error);
            return false;
        }
    }

    isFavorite(productId) {
        return this.favoriteIds.has(productId);
    }

    // Event management
    attachGlobalEvents() {
        document.addEventListener('product:toggleFavorite', async (e) => {
            await this.toggle(e.detail.productId);
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
                favorites: this.favorites,
                favoriteIds: Array.from(this.favoriteIds),
                count: this.favorites.length,
            });
        });
    }

    showNotification(message, type = 'info') {
        // Dispatch custom event for notifications
        const event = new CustomEvent('favorites:notification', {
            detail: { message, type },
        });
        document.dispatchEvent(event);
    }

    // Getters
    getFavorites() {
        return this.favorites;
    }

    getFavoriteIds() {
        return Array.from(this.favoriteIds);
    }

    getCount() {
        return this.favorites.length;
    }
}

// Export singleton instance
export const favoritesManager = new FavoritesManager();
