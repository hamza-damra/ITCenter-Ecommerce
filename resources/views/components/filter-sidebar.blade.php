{{-- Filter Sidebar Component --}}
{{-- Usage: <x-filter-sidebar :filters="$availableFilters" :current="$currentFilters" /> --}}

@props([
    'filters' => [],
    'current' => [],
    'category' => null,
])

@php
    $isRtl = is_rtl();
    $locale = app()->getLocale();
    
    // Extract current filter values from request
    $currentStrongOffers = request('strong_offers', false);
    $currentStock = request('stock', '');
    $currentBrands = request('brand', []);
    $currentMinPrice = request('min_price', '');
    $currentMaxPrice = request('max_price', '');
    $currentAttributes = request('attr', []);
    
    // Get price range from filters
    $priceRange = $filters['price_range'] ?? ['min' => 0, 'max' => 10000];
    
    // Get brands with counts
    $brands = $filters['brands'] ?? [];
    
    // Get stock options with counts
    $stockOptions = $filters['stock'] ?? [];
    
    // Get attributes with values
    $attributes = $filters['attributes'] ?? [];
    
    // Calculate active filter count
    $activeFilterCount = 0;
    if ($currentStrongOffers) $activeFilterCount++;
    if ($currentStock) $activeFilterCount++;
    $activeFilterCount += count((array)$currentBrands);
    if ($currentMinPrice && $currentMinPrice != $priceRange['min']) $activeFilterCount++;
    if ($currentMaxPrice && $currentMaxPrice != $priceRange['max']) $activeFilterCount++;
    foreach ((array)$currentAttributes as $attrValues) {
        $activeFilterCount += count((array)$attrValues);
    }
@endphp

{{-- Mobile Filter Toggle Button --}}
<x-mobile-filter-toggle :count="$activeFilterCount" />

{{-- Mobile Filter Overlay --}}
<div class="mobile-filter-overlay" id="mobileFilterOverlay" onclick="closeMobileFilters()"></div>

<aside class="filter-sidebar" id="filterSidebar" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    {{-- Filter Header --}}
    <div class="filter-header">
        <h3>{{ $isRtl ? 'تصفية' : 'Filters' }}</h3>
        <div class="filter-header-actions">
            <button type="button" class="clear-filters-btn" id="clearFiltersBtn" onclick="clearAllFilters()">
                {{ $isRtl ? 'مسح الكل' : 'Clear All' }}
            </button>
            <button type="button" class="mobile-close-btn" id="mobileCloseBtn" onclick="closeMobileFilters()" aria-label="{{ $isRtl ? 'إغلاق' : 'Close' }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <form id="filterForm" method="GET" action="{{ url()->current() }}">
        <!-- Preserve search query if exists -->
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        {{-- Strong Offers Filter --}}
        @if(isset($filters['strong_offers']))
        <div class="filter-section">
            <div class="filter-section-title">
                <i class="fas fa-fire"></i>
                {{ $isRtl ? 'العروض القوية' : 'Strong Offers' }}
            </div>
            <div class="category-list">
                <div class="category-checkbox">
                    <input 
                        type="checkbox" 
                        name="strong_offers" 
                        value="1"
                        id="strong-offers-checkbox"
                        {{ $currentStrongOffers ? 'checked' : '' }}
                    >
                    <label for="strong-offers-checkbox">
                        {{ $isRtl ? 'عروض قوية فقط' : 'Strong Offers Only' }}
                    </label>
                </div>
            </div>
        </div>
        @endif

        {{-- Stock Filter --}}
        @if(!empty($stockOptions))
        <div class="filter-section">
            <div class="filter-section-title">
                <i class="fas fa-box"></i>
                {{ $isRtl ? 'حالة المخزون' : 'Stock Status' }}
            </div>
            <div class="category-list">
                @foreach($stockOptions as $stock)
                    <div class="category-checkbox">
                        <input 
                            type="radio" 
                            name="stock" 
                            value="{{ $stock['value'] }}"
                            id="stock-{{ $stock['value'] }}"
                            {{ $currentStock === $stock['value'] ? 'checked' : '' }}
                        >
                        <label for="stock-{{ $stock['value'] }}">
                            {{ $stock['label'] }}
                            <span class="item-count">{{ $stock['count'] }}</span>
                        </label>
                    </div>
                @endforeach
                @if($currentStock)
                    <div class="category-checkbox">
                        <input 
                            type="radio" 
                            name="stock" 
                            value=""
                            id="stock-all"
                        >
                        <label for="stock-all">{{ $isRtl ? 'الكل' : 'All' }}</label>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Categories Filter --}}
        @if(!empty($filters['categories']))
        <div class="filter-accordion">
            <button type="button"
                    class="filter-accordion-button"
                    id="categoryAccordionToggle"
                    aria-expanded="false"
                    aria-controls="categoryAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-th-large"></i>
                    <span class="filter-accordion-title">{{ $isRtl ? 'الفئات' : 'Categories' }}</span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="categoryAccordionContent"
                      aria-labelledby="categoryAccordionToggle"
                      hidden>
                <legend class="sr-only">{{ $isRtl ? 'تصفية حسب الفئة' : 'Filter by category' }}</legend>

                <div class="category-list">
                    @foreach($filters['categories'] as $cat)
                    @php
                        $selectedCategories = (array)request('categories', []);
                        if (request('category') && !in_array(request('category'), $selectedCategories)) {
                            $selectedCategories[] = request('category');
                        }
                        $isChecked = in_array($cat['slug'], $selectedCategories);
                    @endphp
                    <div class="category-checkbox">
                        <input type="checkbox"
                               name="categories[]"
                               value="{{ $cat['slug'] }}"
                               id="category-{{ $cat['slug'] }}"
                               {{ $isChecked ? 'checked' : '' }}>
                        <label for="category-{{ $cat['slug'] }}">
                            {{ $cat['name'] }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </fieldset>
        </div>
        @endif

        {{-- Brand Filter --}}
        @if(!empty($brands))
        <div class="filter-accordion">
            <button type="button"
                    class="filter-accordion-button"
                    id="brandAccordionToggle"
                    aria-expanded="false"
                    aria-controls="brandAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-tags"></i>
                    <span class="filter-accordion-title">{{ $isRtl ? 'العلامات التجارية' : 'Brands' }}</span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="brandAccordionContent"
                      aria-labelledby="brandAccordionToggle"
                      hidden>
                <legend class="sr-only">{{ $isRtl ? 'تصفية حسب العلامة التجارية' : 'Filter by brand' }}</legend>

                <div class="brand-list" id="brandList">
                    @foreach($brands as $index => $brand)
                    @php
                        $isChecked = in_array($brand['slug'], (array)$currentBrands);
                        $isInitiallyVisible = $index < 10; // Show first 10
                        $hasProducts = $brand['count'] > 0;
                    @endphp
                    <div class="brand-checkbox {{ !$hasProducts ? 'brand-disabled' : '' }}" 
                         data-brand-index="{{ $index }}" 
                         style="{{ !$isInitiallyVisible ? 'display: none;' : '' }}">
                        <input type="checkbox"
                               name="brands[]"
                               value="{{ $brand['slug'] }}"
                               id="brand-{{ $brand['slug'] }}"
                               {{ !$hasProducts ? 'disabled' : '' }}
                               {{ $isChecked ? 'checked' : '' }}>
                        <label for="brand-{{ $brand['slug'] }}">
                            {{ $brand['name'] }}
                            <span class="item-count {{ !$hasProducts ? 'count-zero' : '' }}">{{ $brand['count'] }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>

                @if(count($brands) > 10)
                <button type="button"
                        class="view-more-btn"
                        id="brandViewMoreBtn"
                        onclick="toggleBrandPagination()"
                        aria-label="{{ $isRtl ? 'عرض المزيد من العلامات التجارية' : 'View more brands' }}">
                    <span id="brandViewMoreText">{{ $isRtl ? 'عرض المزيد' : 'View more' }}</span>
                    <i class="fas fa-chevron-down" id="brandViewMoreIcon" aria-hidden="true"></i>
                </button>
                @endif
            </fieldset>
        </div>
        @endif

        {{-- Price Range Filter --}}
        <div class="filter-section">
            <div class="filter-section-title" id="priceRangeLabel">
                <i class="fas fa-dollar-sign"></i>
                {{ $isRtl ? 'نطاق السعر' : 'Price Range' }}
            </div>

            <!-- Live Price Labels Above Slider -->
            <div class="price-range-labels" aria-live="polite" aria-atomic="true">
                <span class="price-label-min" id="minPriceLabel" aria-label="{{ $isRtl ? 'السعر الأدنى' : 'Minimum price' }}">₪ {{ number_format($currentMinPrice ?: $priceRange['min'], 0) }}</span>
                <span class="price-label-max" id="maxPriceLabel" aria-label="{{ $isRtl ? 'السعر الأقصى' : 'Maximum price' }}">₪ {{ number_format($currentMaxPrice ?: $priceRange['max'], 0) }}</span>
            </div>

            <!-- Dual-Handle Range Slider -->
            <div class="price-range-slider"
                 role="group"
                 aria-labelledby="priceRangeLabel"
                 aria-describedby="priceRangeDescription">
                <div id="priceSlider"></div>
            </div>
            <span id="priceRangeDescription" class="sr-only">
                {{ $isRtl ? 'استخدم مفاتيح الأسهم لتعديل نطاق السعر. اضغط Shift مع السهم للتحرك بشكل أسرع.' : 'Use arrow keys to adjust price range. Hold Shift with arrow keys for faster movement.' }}
            </span>

            <!-- Hidden Input Fields for Form Submission -->
            <input type="hidden"
                   name="min_price"
                   id="minPrice"
                   value="{{ $currentMinPrice ?: $priceRange['min'] }}">
            <input type="hidden"
                   name="max_price"
                   id="maxPrice"
                   value="{{ $currentMaxPrice ?: $priceRange['max'] }}">
        </div>

        {{-- Attribute Filters --}}
        @if(!empty($attributes))
            @foreach($attributes as $attribute)
                <div class="filter-accordion">
                    <button 
                        type="button"
                        class="filter-accordion-button" 
                        aria-expanded="false"
                        onclick="toggleAttributeAccordion(this)"
                    >
                        <span class="filter-accordion-header">
                            <i class="fas fa-sliders-h"></i>
                            <span class="filter-accordion-title">
                                {{ $attribute['name'] }}
                                @if($attribute['unit'])
                                    <small>({{ $attribute['unit'] }})</small>
                                @endif
                            </span>
                        </span>
                        <span class="filter-accordion-icon">
                            <i class="fas fa-plus"></i>
                        </span>
                    </button>
                    
                    <div class="filter-accordion-content" hidden>
                        <div class="category-list">
                            @foreach($attribute['values'] as $value)
                                @php
                                    $attrSlug = $attribute['slug'];
                                    $currentAttrValues = $currentAttributes[$attrSlug] ?? [];
                                    $isChecked = in_array($value['slug'], (array)$currentAttrValues);
                                @endphp
                                <div class="category-checkbox">
                                    <input 
                                        type="checkbox" 
                                        name="attr[{{ $attrSlug }}][]" 
                                        value="{{ $value['slug'] }}"
                                        id="attr-{{ $attrSlug }}-{{ $value['slug'] }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                    >
                                    <label for="attr-{{ $attrSlug }}-{{ $value['slug'] }}">
                                        {{ $value['value'] }}
                                        <span class="item-count">{{ $value['count'] }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </form>
</aside>

<style>
    /* Screen Reader Only */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }

    /* Filter Sidebar Styles */
    .filter-sidebar {
        width: 280px;
        min-width: 280px;
        background: var(--bg-card, #ffffff);
        border-radius: var(--radius-xl, 16px);
        padding: var(--space-6, 1.5rem);
        box-shadow: var(--shadow-md, 0 4px 6px rgba(0, 0, 0, 0.1));
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    .filter-sidebar:hover {
        box-shadow: var(--shadow-lg, 0 10px 15px rgba(0, 0, 0, 0.1));
    }

    .filter-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .filter-sidebar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .filter-sidebar::-webkit-scrollbar-thumb {
        background: #2762f3;
        border-radius: 4px;
    }

    .filter-sidebar::-webkit-scrollbar-thumb:hover {
        background: #1a4dbf;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .filter-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .clear-filters-btn {
        background: transparent;
        color: #2762f3;
        border: 1px solid #2762f3;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .clear-filters-btn:hover {
        background: #2762f3;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(39, 98, 243, 0.2);
    }

    .filter-section {
        margin-bottom: 1.75rem;
    }

    .filter-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-section-title i {
        color: #2762f3;
        font-size: 0.9rem;
    }

    /* Category/Checkbox List */
    .category-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .category-checkbox,
    .brand-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.6rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .category-checkbox:hover,
    .brand-checkbox:hover {
        background: rgba(39, 98, 243, 0.05);
    }

    .category-checkbox input[type="checkbox"],
    .category-checkbox input[type="radio"],
    .brand-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2762f3;
    }

    .category-checkbox span,
    .brand-checkbox span {
        flex: 1;
        cursor: pointer;
        font-size: 0.9rem;
        color: #334155;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-checkbox input:checked + span,
    .brand-checkbox input:checked + span {
        color: #2762f3;
        font-weight: 600;
    }

    .item-count {
        font-size: 0.75rem;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        font-weight: 600;
        min-width: 28px;
        text-align: center;
    }

    .item-count.count-zero {
        color: #cbd5e1;
        background: #f8fafc;
    }

    .category-checkbox input:checked + span .item-count,
    .brand-checkbox input:checked + span .item-count {
        background: rgba(39, 98, 243, 0.15);
        color: #2762f3;
    }

    /* Brand Disabled State */
    .brand-checkbox.brand-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .brand-checkbox.brand-disabled:hover {
        background: transparent;
    }

    .brand-checkbox.brand-disabled span {
        cursor: not-allowed;
        color: #94a3b8;
    }

    .brand-checkbox input:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    /* Accordion Styles */
    .filter-accordion {
        margin-bottom: 0;
    }

    .filter-accordion-button {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
        font-family: inherit;
    }

    .filter-accordion-button:hover {
        background: rgba(39, 98, 243, 0.03);
        border-color: #cbd5e1;
    }

    .filter-accordion-button[aria-expanded="true"] {
        background: rgba(39, 98, 243, 0.05);
        border-color: #2762f3;
    }

    .filter-accordion-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(39, 98, 243, 0.1);
    }

    .filter-accordion-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-accordion-header i {
        color: #64748b;
        font-size: 1rem;
    }

    .filter-accordion-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .filter-accordion-button[aria-expanded="true"] .filter-accordion-title {
        color: #2762f3;
    }

    .filter-accordion-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .filter-accordion-button[aria-expanded="true"] .filter-accordion-icon {
        background: #2762f3;
        color: white;
        transform: rotate(45deg);
    }

    .filter-accordion-content {
        border: none;
        padding: 0 0.5rem 1rem 0.5rem;
        margin: 0;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-accordion-content[hidden] {
        display: none;
    }

    /* View More Button */
    .view-more-btn {
        width: 100%;
        padding: 0.6rem;
        background: transparent;
        color: #2762f3;
        border: 1px solid rgba(39, 98, 243, 0.3);
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .view-more-btn:hover {
        background: rgba(39, 98, 243, 0.1);
        border-color: #2762f3;
    }

    .view-more-btn:focus {
        outline: 2px solid #2762f3;
        outline-offset: 2px;
    }

    .view-more-btn i {
        font-size: 0.75rem;
        transition: transform 0.3s ease;
    }

    .view-more-btn.expanded i {
        transform: rotate(180deg);
    }

    /* Price Range Styles */
    .price-range-labels {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding: 0 0.25rem;
        gap: 0.75rem;
    }

    .price-label-min,
    .price-label-max {
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
    }

    .price-range-slider {
        margin: 1.5rem 0;
        padding: 0 0.5rem;
    }

    /* RTL Support */
    [dir="rtl"] .filter-sidebar {
        text-align: right;
    }

    [dir="rtl"] .filter-header,
    [dir="rtl"] .filter-section-title,
    [dir="rtl"] .filter-accordion-header {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .category-checkbox,
    [dir="rtl"] .brand-checkbox {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .filter-accordion-button {
        flex-direction: row-reverse;
    }

    /* Mobile Filter Overlay */
    .mobile-filter-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-filter-overlay.active {
        display: block;
        opacity: 1;
    }

    /* Mobile Close Button */
    .mobile-close-btn {
        display: none;
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
        transition: all 0.3s ease;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .mobile-close-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .filter-header-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Mobile Responsive */
    @media (max-width: 1024px) {
        .filter-sidebar {
            position: fixed;
            top: 0;
            @if($isRtl)
            right: -100%;
            left: auto;
            @else
            left: -100%;
            right: auto;
            @endif
            bottom: 0;
            width: 85%;
            max-width: 380px;
            z-index: 1000;
            max-height: 100vh;
            border-radius: 0;
            padding: 1.5rem;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
        }

        .filter-sidebar.active {
            @if($isRtl)
            transform: translateX(-100%);
            @else
            transform: translateX(100%);
            @endif
        }

        .mobile-close-btn {
            display: flex;
        }

        .filter-header {
            margin-bottom: 1.25rem;
        }

        .filter-header h3 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .filter-sidebar {
            width: 90%;
            max-width: 100%;
            padding: 1.25rem;
        }

        .filter-header h3 {
            font-size: 1.25rem;
        }
    }
</style>

<script>
    // Helper function for attribute accordion toggle
    function toggleAttributeAccordion(button) {
        const content = button.nextElementSibling;
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        
        button.setAttribute('aria-expanded', !isExpanded);
        content.hidden = isExpanded;
    }

    // Mobile filter drawer functions
    window.openMobileFilters = function() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('mobileFilterOverlay');
        
        if (sidebar && overlay) {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
    };

    window.closeMobileFilters = function() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('mobileFilterOverlay');
        
        if (sidebar && overlay) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }
    };

    window.toggleMobileFilters = function() {
        const sidebar = document.getElementById('filterSidebar');
        
        if (sidebar && sidebar.classList.contains('active')) {
            closeMobileFilters();
        } else {
            openMobileFilters();
        }
    };

    // Close drawer on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileFilters();
        }
    });

    // Close drawer after filter is applied (on mobile)
    if (window.innerWidth <= 1024) {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            // Store original submit handler
            const originalSubmit = filterForm.onsubmit;
            
            // Add close drawer before form submission
            filterForm.addEventListener('submit', function() {
                closeMobileFilters();
            });
        }
    }
</script>
