/**
 * Admin Products Management
 * Handles all product-related operations using the API
 */

class ProductsManager {
    constructor() {
        this.currentPage = 1;
        this.filters = {};
        this.init();
    }

    /**
     * Initialize the products manager
     */
    init() {
        this.bindEvents();
        this.loadProducts();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search input
        const searchInput = document.querySelector('#product-search');
        if (searchInput) {
            searchInput.addEventListener('input', debounce((e) => {
                this.filters.search = e.target.value;
                this.loadProducts();
            }, 500));
        }

        // Category filter
        const categoryFilter = document.querySelector('#category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                this.filters.category_id = e.target.value;
                this.loadProducts();
            });
        }

        // Brand filter
        const brandFilter = document.querySelector('#brand-filter');
        if (brandFilter) {
            brandFilter.addEventListener('change', (e) => {
                this.filters.brand_id = e.target.value;
                this.loadProducts();
            });
        }

        // Status filter
        const statusFilter = document.querySelector('#status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.is_active = e.target.value;
                this.loadProducts();
            });
        }

        // Create product form
        const createForm = document.querySelector('#create-product-form');
        if (createForm) {
            createForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createProduct(createForm);
            });
        }

        // Update product form
        const updateForm = document.querySelector('#update-product-form');
        if (updateForm) {
            updateForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const productId = updateForm.dataset.productId;
                this.updateProduct(productId, updateForm);
            });
        }
    }

    /**
     * Load products from API
     */
    async loadProducts() {
        const container = document.querySelector('#products-container');
        if (!container) return;

        showLoading(container);

        try {
            const params = {
                page: this.currentPage,
                ...this.filters
            };

            const response = await window.adminAPI.getProducts(params);
            
            if (response.success) {
                this.renderProducts(response.data.data);
                this.renderPagination(response.data);
            }
        } catch (error) {
            handleAPIError(error);
        } finally {
            hideLoading(container);
        }
    }

    /**
     * Render products table
     */
    renderProducts(products) {
        const tbody = document.querySelector('#products-tbody');
        if (!tbody) return;

        if (products.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px;">
                        No products found
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = products.map(product => `
            <tr data-product-id="${product.id}">
                <td>${product.id}</td>
                <td>
                    <img src="${product.main_image}" alt="${product.name_en}" 
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                </td>
                <td>${product.name_en}</td>
                <td>${product.category?.name_en || 'N/A'}</td>
                <td>${product.brand?.name_en || 'N/A'}</td>
                <td>${formatCurrency(product.price)}</td>
                <td>${product.stock_quantity}</td>
                <td>
                    <span class="badge ${product.is_active ? 'badge-success' : 'badge-danger'}">
                        ${product.is_active ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary" onclick="productsManager.editProduct(${product.id})">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="productsManager.toggleStatus(${product.id})">
                            ${product.is_active ? 'Deactivate' : 'Activate'}
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="productsManager.deleteProduct(${product.id})">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Render pagination
     */
    renderPagination(data) {
        const pagination = document.querySelector('#products-pagination');
        if (!pagination) return;

        const { current_page, last_page, prev_page_url, next_page_url } = data;

        let html = '<div class="pagination">';
        
        // Previous button
        html += `
            <button class="page-btn" ${!prev_page_url ? 'disabled' : ''} 
                    onclick="productsManager.goToPage(${current_page - 1})">
                Previous
            </button>
        `;

        // Page numbers
        for (let i = 1; i <= last_page; i++) {
            if (i === current_page) {
                html += `<button class="page-btn active">${i}</button>`;
            } else if (i === 1 || i === last_page || Math.abs(i - current_page) <= 2) {
                html += `<button class="page-btn" onclick="productsManager.goToPage(${i})">${i}</button>`;
            } else if (i === current_page - 3 || i === current_page + 3) {
                html += `<span class="page-dots">...</span>`;
            }
        }

        // Next button
        html += `
            <button class="page-btn" ${!next_page_url ? 'disabled' : ''} 
                    onclick="productsManager.goToPage(${current_page + 1})">
                Next
            </button>
        `;

        html += '</div>';
        pagination.innerHTML = html;
    }

    /**
     * Go to specific page
     */
    goToPage(page) {
        this.currentPage = page;
        this.loadProducts();
    }

    /**
     * Create new product
     */
    async createProduct(form) {
        const submitBtn = form.querySelector('[type="submit"]');
        showLoading(submitBtn);

        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            // Handle additional images
            if (data.additional_images) {
                data.additional_images = data.additional_images.split('\n').map(url => url.trim()).filter(url => url);
            }

            const response = await window.adminAPI.createProduct(data);

            if (response.success) {
                showSuccess(response.message);
                form.reset();
                this.loadProducts();
                
                // Close modal if exists
                const modal = form.closest('.modal');
                if (modal) modal.style.display = 'none';
            }
        } catch (error) {
            handleAPIError(error);
        } finally {
            hideLoading(submitBtn);
        }
    }

    /**
     * Edit product
     */
    async editProduct(id) {
        try {
            const response = await window.adminAPI.getProduct(id);
            
            if (response.success) {
                // Populate form with product data
                const form = document.querySelector('#update-product-form');
                if (form) {
                    form.dataset.productId = id;
                    Object.keys(response.data).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.value = response.data[key];
                        }
                    });

                    // Show modal
                    const modal = form.closest('.modal');
                    if (modal) modal.style.display = 'block';
                }
            }
        } catch (error) {
            handleAPIError(error);
        }
    }

    /**
     * Update product
     */
    async updateProduct(id, form) {
        const submitBtn = form.querySelector('[type="submit"]');
        showLoading(submitBtn);

        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            // Handle additional images
            if (data.additional_images) {
                data.additional_images = data.additional_images.split('\n').map(url => url.trim()).filter(url => url);
            }

            const response = await window.adminAPI.updateProduct(id, data);

            if (response.success) {
                showSuccess(response.message);
                this.loadProducts();
                
                // Close modal
                const modal = form.closest('.modal');
                if (modal) modal.style.display = 'none';
            }
        } catch (error) {
            handleAPIError(error);
        } finally {
            hideLoading(submitBtn);
        }
    }

    /**
     * Delete product
     */
    async deleteProduct(id) {
        if (!confirmAction('Are you sure you want to delete this product?')) {
            return;
        }

        try {
            const response = await window.adminAPI.deleteProduct(id);

            if (response.success) {
                showSuccess(response.message);
                this.loadProducts();
            }
        } catch (error) {
            handleAPIError(error);
        }
    }

    /**
     * Toggle product status
     */
    async toggleStatus(id) {
        try {
            const response = await window.adminAPI.toggleProductStatus(id);

            if (response.success) {
                showSuccess(response.message);
                this.loadProducts();
            }
        } catch (error) {
            handleAPIError(error);
        }
    }

    /**
     * Toggle product featured status
     */
    async toggleFeatured(id) {
        try {
            const response = await window.adminAPI.toggleProductFeatured(id);

            if (response.success) {
                showSuccess(response.message);
                this.loadProducts();
            }
        } catch (error) {
            handleAPIError(error);
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.productsManager = new ProductsManager();
    });
} else {
    window.productsManager = new ProductsManager();
}
