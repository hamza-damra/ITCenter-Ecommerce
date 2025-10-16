import { productsApi } from '../api/index';

/**
 * ProductGrid Component
 * Handles fetching and displaying products in a grid
 */
export class ProductGrid {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            filters: {},
            onProductClick: null,
            ...options,
        };
        this.products = [];
        this.loading = false;
        this.init();
    }

    async init() {
        await this.fetchProducts();
        this.render();
        this.attachEvents();
    }

    async fetchProducts() {
        this.setLoading(true);
        try {
            const response = await productsApi.getAll(this.options.filters);
            if (response.success) {
                this.products = response.data.products;
            }
        } catch (error) {
            console.error('Failed to fetch products:', error);
            this.showError('Failed to load products');
        } finally {
            this.setLoading(false);
        }
    }

    render() {
        if (this.loading) {
            this.element.innerHTML = this.getLoadingTemplate();
            return;
        }

        if (this.products.length === 0) {
            this.element.innerHTML = this.getEmptyTemplate();
            return;
        }

        this.element.innerHTML = this.products.map(product => this.getProductTemplate(product)).join('');
    }

    getProductTemplate(product) {
        return `
            <article class="product-card" data-product-id="${product.id}">
                <a href="/product/${product.slug}" class="product-link">
                    <div class="product-image-wrapper">
                        ${product.main_image ? `
                            <img src="${product.main_image}" 
                                 alt="${product.name}" 
                                 class="product-image"
                                 loading="lazy">
                        ` : ''}
                        ${this.getBadgesTemplate(product)}
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        ${product.category ? `<p class="product-category">${product.category.name}</p>` : ''}
                        ${this.getPricingTemplate(product)}
                        ${this.getRatingTemplate(product)}
                    </div>
                </a>
                <div class="product-actions">
                    <button type="button" 
                            class="btn-add-to-cart" 
                            data-product-id="${product.id}"
                            ${!product.stock.is_in_stock ? 'disabled' : ''}>
                        Add to Cart
                    </button>
                    <button type="button" 
                            class="btn-favorite" 
                            data-product-id="${product.id}"
                            aria-label="Add to favorites">
                        ♡
                    </button>
                </div>
            </article>
        `;
    }

    getBadgesTemplate(product) {
        const badges = [];
        if (product.is_new) badges.push('<span class="badge badge-new">New</span>');
        if (product.price.sale) badges.push('<span class="badge badge-sale">Sale</span>');
        if (!product.stock.is_in_stock) badges.push('<span class="badge badge-out-of-stock">Out of Stock</span>');
        
        return badges.length > 0 ? `<div class="product-badges">${badges.join('')}</div>` : '';
    }

    getPricingTemplate(product) {
        if (product.price.sale) {
            return `
                <div class="product-pricing">
                    <span class="price-sale">${product.price.sale} USD</span>
                    <span class="price-regular">${product.price.regular} USD</span>
                </div>
            `;
        }
        return `<div class="product-pricing"><span class="price">${product.price.regular} USD</span></div>`;
    }

    getRatingTemplate(product) {
        if (product.stats.avg_rating > 0) {
            const stars = '★'.repeat(Math.floor(product.stats.avg_rating)) + 
                          '☆'.repeat(5 - Math.floor(product.stats.avg_rating));
            return `
                <div class="product-rating" data-rating="${product.stats.avg_rating}">
                    <span class="rating-stars">${stars}</span>
                    <span class="rating-count">(${product.stats.reviews_count})</span>
                </div>
            `;
        }
        return '';
    }

    getLoadingTemplate() {
        return '<div class="loading">Loading products...</div>';
    }

    getEmptyTemplate() {
        return '<div class="empty-state"><p>No products found</p></div>';
    }

    setLoading(loading) {
        this.loading = loading;
        this.element.classList.toggle('loading', loading);
    }

    showError(message) {
        this.element.innerHTML = `<div class="error-state"><p>${message}</p></div>`;
    }

    attachEvents() {
        // Add event delegation for product actions
        this.element.addEventListener('click', (e) => {
            const addToCartBtn = e.target.closest('.btn-add-to-cart');
            if (addToCartBtn) {
                e.preventDefault();
                this.handleAddToCart(addToCartBtn.dataset.productId);
            }

            const favoriteBtn = e.target.closest('.btn-favorite');
            if (favoriteBtn) {
                e.preventDefault();
                this.handleToggleFavorite(favoriteBtn.dataset.productId);
            }
        });
    }

    async handleAddToCart(productId) {
        // Emit custom event for cart addition
        const event = new CustomEvent('product:addToCart', {
            detail: { productId },
            bubbles: true,
        });
        this.element.dispatchEvent(event);
    }

    async handleToggleFavorite(productId) {
        // Emit custom event for favorite toggle
        const event = new CustomEvent('product:toggleFavorite', {
            detail: { productId },
            bubbles: true,
        });
        this.element.dispatchEvent(event);
    }

    // Public methods
    async updateFilters(filters) {
        this.options.filters = { ...this.options.filters, ...filters };
        await this.fetchProducts();
        this.render();
    }

    async refresh() {
        await this.fetchProducts();
        this.render();
    }
}
