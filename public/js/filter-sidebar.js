/**
 * Filter Sidebar JavaScript
 * Handles filter interactions, URL updates, and state preservation
 */

(function() {
    'use strict';

    // Filter Manager Class
    class FilterManager {
        constructor() {
            this.form = document.getElementById('filter-form');
            this.priceSlider = document.getElementById('price-slider');
            this.init();
        }

        init() {
            if (!this.form) return;

            // Initialize price slider
            this.initPriceSlider();

            // Handle checkbox changes
            this.handleCheckboxChanges();

            // Handle radio button changes
            this.handleRadioChanges();

            // Preserve scroll position
            this.preserveScrollPosition();
        }

        /**
         * Initialize custom dual-range price slider (pure JS, no library)
         */
        initPriceSlider() {
            const rangeMin = document.getElementById('rangeMin');
            const rangeMax = document.getElementById('rangeMax');
            const highlight = document.querySelector('.dual-range-highlight');

            if (!rangeMin || !rangeMax) return;

            const self = this;

            function updateHighlight() {
                if (!highlight) return;
                const min = parseInt(rangeMin.value);
                const max = parseInt(rangeMax.value);
                const total = parseInt(rangeMin.max) - parseInt(rangeMin.min);
                if (total <= 0) return;
                const minPct = ((min - parseInt(rangeMin.min)) / total) * 100;
                const maxPct = ((max - parseInt(rangeMin.min)) / total) * 100;
                highlight.style.left = minPct + '%';
                highlight.style.width = (maxPct - minPct) + '%';
            }

            function syncInputs() {
                const minDisplay = document.getElementById('price-min-display');
                const maxDisplay = document.getElementById('price-max-display');
                const minInput = document.getElementById('min-price-input') || document.getElementById('minPriceInput');
                const maxInput = document.getElementById('max-price-input') || document.getElementById('maxPriceInput');
                const minHidden = document.getElementById('minPrice');
                const maxHidden = document.getElementById('maxPrice');

                if (minDisplay) minDisplay.textContent = rangeMin.value;
                if (maxDisplay) maxDisplay.textContent = rangeMax.value;
                if (minInput) minInput.value = rangeMin.value;
                if (maxInput) maxInput.value = rangeMax.value;
                if (minHidden) minHidden.value = rangeMin.value;
                if (maxHidden) maxHidden.value = rangeMax.value;
                updateHighlight();
            }

            rangeMin.addEventListener('input', function() {
                if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) rangeMin.value = rangeMax.value;
                syncInputs();
            });

            rangeMax.addEventListener('input', function() {
                if (parseInt(rangeMax.value) < parseInt(rangeMin.value)) rangeMax.value = rangeMin.value;
                syncInputs();
            });

            let priceChangeTimeout;
            rangeMin.addEventListener('change', () => {
                clearTimeout(priceChangeTimeout);
                priceChangeTimeout = setTimeout(() => self.submitForm(), 500);
            });
            rangeMax.addEventListener('change', () => {
                clearTimeout(priceChangeTimeout);
                priceChangeTimeout = setTimeout(() => self.submitForm(), 500);
            });

            syncInputs();
        }

        /**
         * Handle checkbox changes
         */
        handleCheckboxChanges() {
            const checkboxes = this.form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    // Prevent default form submission
                    e.preventDefault();
                    
                    // Submit form with current state
                    this.submitForm();
                });
            });
        }

        /**
         * Handle radio button changes
         */
        handleRadioChanges() {
            const radios = this.form.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    // Prevent default form submission
                    e.preventDefault();
                    
                    // Submit form with current state
                    this.submitForm();
                });
            });
        }

        /**
         * Submit form and update URL
         */
        submitForm() {
            // Close mobile drawer if open
            if (window.innerWidth <= 1024 && typeof window.closeMobileFilters === 'function') {
                window.closeMobileFilters();
            }

            // Get form data
            const formData = new FormData(this.form);
            const params = new URLSearchParams();

            // Build query parameters
            for (let [key, value] of formData.entries()) {
                if (value) {
                    // Handle array parameters (brand[], attr[slug][])
                    if (key.includes('[')) {
                        params.append(key, value);
                    } else {
                        params.set(key, value);
                    }
                }
            }

            // Preserve existing non-filter parameters (like page, sort)
            const currentParams = new URLSearchParams(window.location.search);
            const preserveParams = ['sort', 'order', 'per_page'];
            preserveParams.forEach(param => {
                if (currentParams.has(param) && !params.has(param)) {
                    params.set(param, currentParams.get(param));
                }
            });

            // Update URL and reload
            const newUrl = window.location.pathname + '?' + params.toString();
            window.location.href = newUrl;
        }

        /**
         * Preserve scroll position after page reload
         */
        preserveScrollPosition() {
            // Save scroll position before unload
            window.addEventListener('beforeunload', () => {
                sessionStorage.setItem('filterScrollPosition', window.scrollY);
            });

            // Restore scroll position after load
            const savedPosition = sessionStorage.getItem('filterScrollPosition');
            if (savedPosition) {
                window.scrollTo(0, parseInt(savedPosition));
                sessionStorage.removeItem('filterScrollPosition');
            }
        }
    }

    /**
     * Toggle accordion sections
     */
    window.toggleAccordion = function(button) {
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        const content = button.nextElementSibling;
        
        button.setAttribute('aria-expanded', !isExpanded);
        content.hidden = isExpanded;
    };

    /**
     * Toggle brand list view more/less
     */
    window.toggleBrandList = function(button) {
        const brandList = document.getElementById('brand-list');
        const allBrands = brandList.querySelectorAll('.brand-checkbox');
        const isExpanded = button.classList.contains('expanded');
        
        allBrands.forEach((brand, index) => {
            if (index >= 5) {
                brand.style.display = isExpanded ? 'none' : 'flex';
            }
        });
        
        button.classList.toggle('expanded');
        const textSpan = button.querySelector('.view-more-text');
        const viewMoreText = button.dataset.viewMore || 'View More';
        const viewLessText = button.dataset.viewLess || 'View Less';
        textSpan.textContent = isExpanded ? viewMoreText : viewLessText;
    };

    /**
     * Clear all filters
     */
    window.clearAllFilters = function() {
        // Close mobile drawer if open
        if (window.innerWidth <= 1024 && typeof window.closeMobileFilters === 'function') {
            window.closeMobileFilters();
        }

        // Get current URL without query parameters
        const baseUrl = window.location.pathname;
        
        // Preserve certain parameters if needed (like category)
        const currentParams = new URLSearchParams(window.location.search);
        const preserveParams = [];
        const newParams = new URLSearchParams();
        
        preserveParams.forEach(param => {
            if (currentParams.has(param)) {
                newParams.set(param, currentParams.get(param));
            }
        });
        
        // Redirect to clean URL
        const newUrl = baseUrl + (newParams.toString() ? '?' + newParams.toString() : '');
        window.location.href = newUrl;
    };

    /**
     * Initialize filter manager when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new FilterManager();
        });
    } else {
        new FilterManager();
    }

})();
