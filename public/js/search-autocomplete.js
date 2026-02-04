/**
 * Search Autocomplete Component
 * Shows only products with name in the matching search language
 */
(function() {
    'use strict';

    function initSearchAutocomplete() {
        const searchInput = document.querySelector('.search-bar input[name="search"]') 
            || document.querySelector('.search-bar input[type="search"]')
            || document.querySelector('input[name="search"]');
        
        if (!searchInput) return;

        const searchBar = searchInput.closest('.search-bar') || searchInput.parentElement;
        
        // Create dropdown
        const dropdown = document.createElement('div');
        dropdown.className = 'search-autocomplete-dropdown';
        searchBar.appendChild(dropdown);

        let debounceTimer = null;
        let abortController = null;
        let currentRequestId = 0;
        const config = {
            minChars: 2,
            debounceMs: 300,
            maxResults: 10,
            apiEndpoint: '/api/v1/search/suggestions'
        };

        function getTranslation(key) {
            const lang = document.documentElement.lang || 'en';
            const t = {
                en: { products: 'Products', viewAll: 'View all results for', noResults: 'No results found for', searching: 'Searching...' },
                ar: { products: 'المنتجات', viewAll: 'عرض جميع النتائج لـ', noResults: 'لا توجد نتائج لـ', searching: 'جاري البحث...' },
                he: { products: 'מוצרים', viewAll: 'הצג את כל התוצאות עבור', noResults: 'לא נמצאו תוצאות עבור', searching: 'מחפש...' }
            };
            return t[lang]?.[key] || t.en[key] || key;
        }

        function highlightMatch(text, query) {
            if (!text || !query) return text || '';
            try {
                const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>');
            } catch (e) {
                return text;
            }
        }

        function openDropdown() {
            if (!dropdown.classList.contains('open')) {
                console.log('[Search] Opening dropdown');
            }
            dropdown.classList.add('open');
        }

        function closeDropdown() {
            if (dropdown.classList.contains('open')) {
                console.log('[Search] Closing dropdown');
            }
            dropdown.classList.remove('open');
        }

        function showLoading() {
            // Only show loading spinner if no results exist yet
            if (!dropdown.querySelector('.autocomplete-results-wrapper')) {
                dropdown.innerHTML = `
                    <div class="autocomplete-results-wrapper">
                        <div class="autocomplete-loading">
                            <div class="autocomplete-spinner"></div>
                            <span>${getTranslation('searching')}</span>
                        </div>
                    </div>
                `;
            }
            // Otherwise, just keep showing old results - no loading indicator
            openDropdown();
        }

        function hideLoading() {
            // No-op since we don't show loading indicator anymore
        }

        function renderResults(data, query) {
            const { products = [], total = 0 } = data;
            const isRtl = document.dir === 'rtl';
            const arrow = isRtl ? 'left' : 'right';

            hideLoading();

            if (total === 0) {
                updateDropdownContent(`
                    <div class="autocomplete-no-results">
                        <i class="fas fa-search"></i>
                        <p>${getTranslation('noResults')} "${query}"</p>
                    </div>
                    <a href="/products?search=${encodeURIComponent(query)}" class="autocomplete-view-all">
                        <i class="fas fa-search"></i>
                        <span>${getTranslation('viewAll')} "${query}"</span>
                        <i class="fas fa-arrow-${arrow}"></i>
                    </a>
                `);
                openDropdown();
                return;
            }

            let html = '';

            // Products only
            if (products.length > 0) {
                html += `<div class="autocomplete-section">
                    <div class="autocomplete-section-title">
                        <i class="fas fa-box"></i>
                        <span>${getTranslation('products')}</span>
                    </div>`;
                
                products.forEach(item => {
                    const hasDiscount = item.original_price && parseFloat(item.original_price) > parseFloat(item.price);
                    html += `<a href="${item.url}" class="autocomplete-item">
                        <div class="autocomplete-item-image">
                            <img src="${item.image || '/images/products/default.png'}" alt="${item.name}" loading="lazy">
                        </div>
                        <div class="autocomplete-item-content">
                            <div class="autocomplete-item-name">${highlightMatch(item.name, query)}</div>
                            <div class="autocomplete-item-price">
                                <span class="current-price">₪${parseFloat(item.price).toFixed(2)}</span>
                                ${hasDiscount ? `<span class="original-price">₪${parseFloat(item.original_price).toFixed(2)}</span>` : ''}
                            </div>
                        </div>
                        <i class="fas fa-chevron-${arrow} autocomplete-item-arrow"></i>
                    </a>`;
                });
                html += '</div>';
            }

            // View all link
            html += `<a href="/products?search=${encodeURIComponent(query)}" class="autocomplete-view-all">
                <i class="fas fa-search"></i>
                <span>${getTranslation('viewAll')} "${query}"</span>
                <i class="fas fa-arrow-${arrow}"></i>
            </a>`;

            updateDropdownContent(html);
            openDropdown();
        }

        function updateDropdownContent(html) {
            hideLoading();
            
            let resultsWrapper = dropdown.querySelector('.autocomplete-results-wrapper');
            
            if (!resultsWrapper) {
                dropdown.innerHTML = `<div class="autocomplete-results-wrapper"></div>`;
                resultsWrapper = dropdown.querySelector('.autocomplete-results-wrapper');
            }
            
            // Instant update - no animations
            resultsWrapper.innerHTML = html;
        }

        async function fetchSuggestions(query) {
            // Cancel previous request
            if (abortController) abortController.abort();
            abortController = new AbortController();
            
            // Track this request
            const requestId = ++currentRequestId;

            showLoading();

            try {
                const response = await fetch(
                    `${config.apiEndpoint}?q=${encodeURIComponent(query)}&limit=${config.maxResults}`,
                    {
                        signal: abortController.signal,
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }
                );

                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const data = await response.json();
                
                // Only update if this is still the current request
                if (requestId !== currentRequestId) return;
                
                if (data.success && data.data) {
                    renderResults(data.data, query);
                } else {
                    hideLoading();
                    closeDropdown();
                }
            } catch (error) {
                // Only handle if this is still the current request
                if (error.name === 'AbortError') return;
                
                if (requestId === currentRequestId) {
                    hideLoading();
                    console.error('[SearchAutocomplete] Error:', error);
                }
            }
        }

        function handleInput(e) {
            const query = e.target.value.trim();
            
            if (debounceTimer) clearTimeout(debounceTimer);

            if (query.length < config.minChars) {
                closeDropdown();
                return;
            }

            // Keep dropdown open immediately - don't wait for debounce
            if (dropdown.querySelector('.autocomplete-results-wrapper')) {
                openDropdown();
            }

            debounceTimer = setTimeout(() => fetchSuggestions(query), config.debounceMs);
        }

        // Events
        searchInput.addEventListener('input', handleInput);
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.trim().length >= config.minChars && dropdown.innerHTML) {
                openDropdown();
            }
        });
        document.addEventListener('click', (e) => {
            if (!searchBar.contains(e.target)) closeDropdown();
        });
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDropdown();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchAutocomplete);
    } else {
        initSearchAutocomplete();
    }
})();
