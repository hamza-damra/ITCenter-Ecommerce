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
    $currentBrands = request('brands', request('brand', []));
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
    // Price: only count if different from the hardcoded slider defaults (0–5000)
    if ($currentMinPrice && (int)$currentMinPrice != 0) $activeFilterCount++;
    if ($currentMaxPrice && (int)$currentMaxPrice != 5000) $activeFilterCount++;
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
        <h3>{{ $locale === 'ar' ? 'تصفية' : ($locale === 'he' ? 'סינון' : 'Filters') }}</h3>
        <div class="filter-header-actions">
            <button type="button" class="clear-filters-btn" id="clearFiltersBtn" onclick="clearAllFilters()">
                {{ $locale === 'ar' ? 'مسح الكل' : ($locale === 'he' ? 'נקה הכל' : 'Clear All') }}
            </button>
            <button type="button" class="mobile-close-btn" id="mobileCloseBtn" onclick="closeMobileFilters()" aria-label="{{ $locale === 'ar' ? 'إغلاق' : ($locale === 'he' ? 'סגור' : 'Close') }}">
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
                {{ $locale === 'ar' ? 'العروض القوية' : ($locale === 'he' ? 'מבצעים חזקים' : 'Strong Offers') }}
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
                        {{ $locale === 'ar' ? 'عروض قوية فقط' : ($locale === 'he' ? 'מבצעים חזקים בלבד' : 'Strong Offers Only') }}
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
                {{ $locale === 'ar' ? 'حالة المخزون' : ($locale === 'he' ? 'מצב מלאי' : 'Stock Status') }}
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
                        <label for="stock-all">{{ $locale === 'ar' ? 'الكل' : ($locale === 'he' ? 'הכל' : 'All') }}</label>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Tags Filter --}}
        @if(!empty($filters['tags']))
        <div class="filter-accordion">
            <button type="button"
                    class="filter-accordion-button"
                    id="tagAccordionToggle"
                    aria-expanded="false"
                    aria-controls="tagAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-tags"></i>
                    <span class="filter-accordion-title">{{ $locale === 'ar' ? 'الوسوم' : ($locale === 'he' ? 'תגיות' : 'Tags') }}</span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="tagAccordionContent"
                      aria-labelledby="tagAccordionToggle"
                      hidden>
                <legend class="sr-only">{{ $locale === 'ar' ? 'تصفية حسب الوسم' : ($locale === 'he' ? 'סנן לפי תגית' : 'Filter by tag') }}</legend>

                <div class="category-list tag-filter-list">
                    @foreach($filters['tags'] as $tag)
                    @php
                        $currentTag = request('tag', '');
                        $isChecked = $currentTag === $tag['slug'];
                    @endphp
                    <div class="category-checkbox tag-checkbox-item">
                        <input type="radio"
                               name="tag"
                               value="{{ $tag['slug'] }}"
                               id="tag-{{ $tag['slug'] }}"
                               {{ $isChecked ? 'checked' : '' }}>
                        <label for="tag-{{ $tag['slug'] }}">
                            <span class="tag-label-content">
                                @if($tag['icon'])
                                    <i class="{{ $tag['icon'] }}" style="color: {{ $tag['color'] }};"></i>
                                @else
                                    <span class="tag-color-dot" style="background: {{ $tag['color'] }};"></span>
                                @endif
                                {{ $tag['name'] }}
                            </span>
                            <span class="item-count">{{ $tag['count'] }}</span>
                        </label>
                    </div>
                    @endforeach
                    @if(request('tag'))
                        <div class="category-checkbox">
                            <input type="radio" name="tag" value="" id="tag-all">
                            <label for="tag-all">{{ $locale === 'ar' ? 'الكل' : ($locale === 'he' ? 'הכל' : 'All') }}</label>
                        </div>
                    @endif
                </div>
            </fieldset>
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
                    <span class="filter-accordion-title">{{ $locale === 'ar' ? 'الفئات' : ($locale === 'he' ? 'קטגוריות' : 'Categories') }}</span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="categoryAccordionContent"
                      aria-labelledby="categoryAccordionToggle"
                      hidden>
                <legend class="sr-only">{{ $locale === 'ar' ? 'تصفية حسب الفئة' : ($locale === 'he' ? 'סנן לפי קטגוריה' : 'Filter by category') }}</legend>

                <div class="category-list" id="categoryList">
                    @foreach($filters['categories'] as $index => $cat)
                    @php
                        $selectedCategories = (array)request('categories', []);
                        if (request('category') && !in_array(request('category'), $selectedCategories)) {
                            $selectedCategories[] = request('category');
                        }
                        $isChecked = in_array($cat['slug'], $selectedCategories);
                        $isInitiallyVisible = $index < 10; // Show first 10
                        $hasProducts = ($cat['count'] ?? 0) > 0;
                    @endphp
                    <div class="category-checkbox category-filter-item {{ !$hasProducts ? 'category-disabled' : '' }}"
                         data-category-index="{{ $index }}"
                         style="{{ !$isInitiallyVisible ? 'display: none;' : '' }}">
                        <input type="checkbox"
                               name="categories[]"
                               value="{{ $cat['slug'] }}"
                               id="category-{{ $cat['slug'] }}"
                               {{ !$hasProducts ? 'disabled' : '' }}
                               {{ $isChecked ? 'checked' : '' }}>
                        <label for="category-{{ $cat['slug'] }}">
                            {{ $cat['name'] }}
                            <span class="item-count {{ !$hasProducts ? 'count-zero' : '' }}">{{ $cat['count'] ?? 0 }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>

                @if(count($filters['categories']) > 10)
                <button type="button"
                        class="view-more-btn"
                        id="categoryViewMoreBtn"
                        data-visible-count="10"
                        data-total-count="{{ count($filters['categories']) }}"
                        aria-label="{{ $locale === 'ar' ? 'عرض المزيد من الفئات' : ($locale === 'he' ? 'הצג עוד קטגוריות' : 'View more categories') }}">
                    <span id="categoryViewMoreText">{{ $locale === 'ar' ? 'عرض المزيد' : ($locale === 'he' ? 'הצג עוד' : 'View more') }} ({{ count($filters['categories']) - 10 }})</span>
                    <i class="fas fa-chevron-down" id="categoryViewMoreIcon" aria-hidden="true"></i>
                </button>
                @endif
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
                    <span class="filter-accordion-title">{{ $locale === 'ar' ? 'العلامات التجارية' : ($locale === 'he' ? 'מותגים' : 'Brands') }}</span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="brandAccordionContent"
                      aria-labelledby="brandAccordionToggle"
                      hidden>
                <legend class="sr-only">{{ $locale === 'ar' ? 'تصفية حسب العلامة التجارية' : ($locale === 'he' ? 'סנן לפי מותג' : 'Filter by brand') }}</legend>

                <div class="brand-list" id="brandList">
                    @foreach($brands as $index => $brand)
                    @php
                        $isChecked = in_array($brand['slug'], (array)$currentBrands);
                        $isInitiallyVisible = $index < 10; // Show first 10
                        $hasProducts = $brand['count'] > 0;
                    @endphp
                    <div class="brand-checkbox brand-filter-item {{ !$hasProducts ? 'brand-disabled' : '' }}"
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
                        data-visible-count="10"
                        data-total-count="{{ count($brands) }}"
                        aria-label="{{ $locale === 'ar' ? 'عرض المزيد من العلامات التجارية' : ($locale === 'he' ? 'הצג עוד מותגים' : 'View more brands') }}">
                    <span id="brandViewMoreText">{{ $locale === 'ar' ? 'عرض المزيد' : ($locale === 'he' ? 'הצג עוד' : 'View more') }} ({{ count($brands) - 10 }})</span>
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
                {{ $locale === 'ar' ? 'نطاق السعر' : ($locale === 'he' ? 'טווח מחירים' : 'Price Range') }}
            </div>

            <!-- Price Input Fields -->
            <div class="price-input-container">
                <div class="price-input-wrapper">
                    <label for="minPriceInput" class="price-input-label">{{ $locale === 'ar' ? 'من' : ($locale === 'he' ? 'מ' : 'Min') }}</label>
                    <div class="price-input-group">
                        <span class="price-currency">&#8362;</span>
                        <input type="number"
                               id="minPriceInput"
                               class="price-input"
                               min="0"
                               max="5000"
                               value="{{ $currentMinPrice ?: 0 }}"
                               aria-label="{{ $locale === 'ar' ? 'السعر الأدنى' : ($locale === 'he' ? 'מחיר מינימום' : 'Minimum price') }}">
                    </div>
                </div>
                <div class="price-input-separator">-</div>
                <div class="price-input-wrapper">
                    <label for="maxPriceInput" class="price-input-label">{{ $locale === 'ar' ? 'إلى' : ($locale === 'he' ? 'עד' : 'Max') }}</label>
                    <div class="price-input-group">
                        <span class="price-currency">&#8362;</span>
                        <input type="number"
                               id="maxPriceInput"
                               class="price-input"
                               min="0"
                               max="5000"
                               value="{{ $currentMaxPrice ?: 5000 }}"
                               aria-label="{{ $locale === 'ar' ? 'السعر الأقصى' : ($locale === 'he' ? 'מחיר מקסימום' : 'Maximum price') }}">
                    </div>
                </div>
            </div>

            <!-- Dual-Handle Range Slider (pure HTML/CSS/JS, no library) -->
            <div class="price-range-slider"
                 role="group"
                 aria-labelledby="priceRangeLabel">
                <div class="dual-range-wrapper">
                    <div class="dual-range-track"></div>
                    <div class="dual-range-highlight"></div>
                    <input type="range"
                           id="rangeMin"
                           min="0"
                           max="5000"
                           value="{{ $currentMinPrice ?: 0 }}"
                           step="1"
                           aria-label="{{ $locale === 'ar' ? 'السعر الأدنى' : ($locale === 'he' ? 'מחיר מינימום' : 'Minimum price') }}">
                    <input type="range"
                           id="rangeMax"
                           min="0"
                           max="5000"
                           value="{{ $currentMaxPrice ?: 5000 }}"
                           step="1"
                           aria-label="{{ $locale === 'ar' ? 'السعر الأقصى' : ($locale === 'he' ? 'מחיר מקסימום' : 'Maximum price') }}">
                </div>
            </div>

            <!-- Hidden Input Fields for Form Submission -->
            <input type="hidden"
                   name="min_price"
                   id="minPrice"
                   value="{{ $currentMinPrice ?: 0 }}">
            <input type="hidden"
                   name="max_price"
                   id="maxPrice"
                   value="{{ $currentMaxPrice ?: 5000 }}">
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

    {{-- Mobile Apply Filters Button (sticky at bottom) --}}
    <div class="mobile-apply-filters-wrapper" id="mobileApplyFiltersWrapper">
        <button type="button" class="mobile-apply-filters-btn" id="mobileApplyFiltersBtn" onclick="applyAndCloseMobileFilters()">
            <i class="fas fa-check"></i>
            <span>{{ $locale === 'ar' ? 'عرض النتائج' : ($locale === 'he' ? 'הצג תוצאות' : 'Show Results') }}</span>
        </button>
    </div>
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
        min-width: 18px;
        cursor: pointer;
        accent-color: #2762f3;
        flex-shrink: 0;
    }

    .category-checkbox label,
    .brand-checkbox label {
        flex: 1;
        cursor: pointer;
        font-size: 0.9rem;
        color: #334155;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
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

    .category-checkbox input:checked + label,
    .brand-checkbox input:checked + label {
        color: #2762f3;
        font-weight: 600;
    }

    .category-checkbox input:checked + span,
    .brand-checkbox input:checked + span {
        color: #2762f3;
        font-weight: 600;
    }

    .item-count {
        font-size: 0.8rem;
        color: #64748b;
        background: transparent;
        padding: 0;
        font-weight: 500;
        min-width: auto;
        text-align: center;
        flex-shrink: 0;
    }

    .item-count.count-zero {
        color: #94a3b8;
        background: transparent;
    }

    .category-checkbox input:checked + label .item-count,
    .brand-checkbox input:checked + label .item-count,
    .category-checkbox input:checked + span .item-count,
    .brand-checkbox input:checked + span .item-count {
        background: transparent;
        color: #2762f3;
    }

    /* Tag Filter Styles */
    .tag-filter-list {
        gap: 0.5rem;
    }

    .tag-checkbox-item label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .tag-label-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tag-color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .tag-checkbox-item input:checked + label .tag-label-content {
        font-weight: 600;
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

    /* Category Disabled State */
    .category-checkbox.category-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .category-checkbox.category-disabled:hover {
        background: transparent;
    }

    .category-checkbox.category-disabled label {
        cursor: not-allowed;
        color: #94a3b8;
    }

    .category-checkbox input:disabled {
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
    .price-input-container {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding: 0 0.25rem;
    }

    .price-input-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .price-input-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-input-group {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }

    .price-input-group:focus-within {
        border-color: #2762f3;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(39, 98, 243, 0.1);
    }

    .price-currency {
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b;
        margin-right: 0.5rem;
        flex-shrink: 0;
    }

    .price-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        outline: none;
        padding: 0;
        -moz-appearance: textfield;
    }

    .price-input::-webkit-outer-spin-button,
    .price-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .price-input-separator {
        font-size: 1.25rem;
        font-weight: 600;
        color: #94a3b8;
        padding-bottom: 0.5rem;
        flex-shrink: 0;
    }

    .price-range-slider {
        margin: 1rem 0 0.5rem;
        padding: 0.75rem 0;
    }

    /* RTL Support */
    [dir="rtl"] .filter-sidebar {
        text-align: right;
    }

    [dir="rtl"] .filter-header {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .filter-header-actions {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .filter-section-title {
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    [dir="rtl"] .filter-accordion-header {
        flex-direction: row;
    }

    [dir="rtl"] .category-checkbox,
    [dir="rtl"] .brand-checkbox {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .category-checkbox label,
    [dir="rtl"] .brand-checkbox label {
        flex-direction: row;
        text-align: right;
    }

    [dir="rtl"] .filter-accordion-button {
        flex-direction: row;
        text-align: right;
    }

    [dir="rtl"] .price-input-container {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .price-currency {
        margin-right: 0;
        margin-left: 0.5rem;
    }

    [dir="rtl"] .tag-label-content {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .item-count {
        margin-right: 0;
        margin-left: auto;
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
        width: 40px;
        height: 40px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        flex-shrink: 0;
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

    /* Mobile Responsive - Tablet */
    @media (max-width: 1024px) {
        .filter-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            width: 320px;
            max-width: 85vw;
            min-width: 280px;
            z-index: 1000;
            max-height: 100vh;
            border-radius: 0;
            padding: 1.5rem;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
            box-sizing: border-box;
            /* Default LTR positioning */
            left: 0;
            right: auto;
            transform: translateX(-100%);
        }

        .filter-sidebar.active {
            transform: translateX(0);
        }

        /* RTL positioning - using dir attribute on the sidebar itself */
        .filter-sidebar[dir="rtl"] {
            left: auto;
            right: 0;
            transform: translateX(100%);
        }

        .filter-sidebar[dir="rtl"].active {
            transform: translateX(0);
        }

        .mobile-close-btn {
            display: flex;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            position: sticky;
            top: -1.5rem;
            background: #fff;
            z-index: 10;
            margin-top: -1.5rem;
            padding-top: 1.5rem;
            width: 100%;
            box-sizing: border-box;
        }

        .filter-header h3 {
            font-size: 1.35rem;
            flex-shrink: 1;
            min-width: 0;
        }

        .filter-header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .filter-section {
            margin-bottom: 1.25rem;
        }

        /* RTL adjustments for mobile drawer */
        .filter-sidebar[dir="rtl"] .filter-header {
            flex-direction: row-reverse;
        }

        .filter-sidebar[dir="rtl"] .filter-header-actions {
            flex-direction: row-reverse;
        }

        .filter-sidebar[dir="rtl"] .filter-section-title {
            flex-direction: row-reverse;
            justify-content: flex-start;
        }

        .filter-sidebar[dir="rtl"] .filter-accordion-button {
            flex-direction: row-reverse;
        }

        .filter-sidebar[dir="rtl"] .filter-accordion-header {
            flex-direction: row-reverse;
        }

        .filter-sidebar[dir="rtl"] .category-checkbox,
        .filter-sidebar[dir="rtl"] .brand-checkbox {
            flex-direction: row-reverse;
        }

        .filter-sidebar[dir="rtl"] .category-checkbox label,
        .filter-sidebar[dir="rtl"] .brand-checkbox label {
            flex-direction: row-reverse;
            text-align: right;
        }

        .filter-sidebar[dir="rtl"] .price-input-container {
            flex-direction: row-reverse;
        }

        .filter-sidebar[dir="rtl"] .price-currency {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        .filter-accordion-button {
            padding: 0.875rem 1rem;
        }

        .filter-accordion-title {
            font-size: 0.95rem;
        }

        .category-checkbox,
        .brand-checkbox {
            padding: 0.5rem;
        }

        .category-checkbox label,
        .brand-checkbox label {
            font-size: 0.875rem;
        }

        .price-input-container {
            gap: 0.5rem;
        }

        .price-input-group {
            padding: 0.4rem 0.6rem;
        }

        .price-input {
            font-size: 0.9rem;
        }
    }

    /* Mobile Responsive - Phone */
    @media (max-width: 640px) {
        .filter-sidebar {
            width: 100%;
            max-width: 100%;
            padding: 1rem;
        }

        .filter-header {
            margin-top: -1rem;
            padding-top: 1rem;
            top: -1rem;
        }

        .filter-header h3 {
            font-size: 1.25rem;
        }

        .clear-filters-btn {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }

        .filter-section-title {
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .filter-accordion-button {
            padding: 0.75rem 0.875rem;
            border-radius: 10px;
        }

        .filter-accordion-title {
            font-size: 0.9rem;
        }

        .filter-accordion-icon {
            width: 22px;
            height: 22px;
            font-size: 0.7rem;
        }

        .category-checkbox,
        .brand-checkbox {
            padding: 0.4rem;
            border-radius: 6px;
        }

        .category-checkbox label,
        .brand-checkbox label {
            font-size: 0.85rem;
        }

        .category-checkbox input[type="checkbox"],
        .category-checkbox input[type="radio"],
        .brand-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            min-width: 16px;
        }

        .item-count {
            font-size: 0.75rem;
        }

        .price-input-container {
            flex-direction: column;
            gap: 0.75rem;
        }

        .price-input-wrapper {
            width: 100%;
        }

        .price-input-separator {
            display: none;
        }

        .price-input-group {
            padding: 0.5rem 0.75rem;
        }        .price-input {
            font-size: 0.95rem;
        }

        .price-range-slider {
            margin: 1rem 0;
            padding: 0.5rem;
        }

        .view-more-btn {
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        /* RTL price input stacks vertically on phone */
        .filter-sidebar[dir="rtl"] .price-input-container {
            flex-direction: column;
        }
    }

    /* Small phone adjustments */
    @media (max-width: 380px) {
        .filter-sidebar {
            padding: 0.875rem;
        }

        .filter-header h3 {
            font-size: 1.1rem;
        }

        .filter-accordion-button {
            padding: 0.65rem 0.75rem;
        }

        .filter-accordion-title {
            font-size: 0.85rem;
        }

        .category-checkbox label,
        .brand-checkbox label {
            font-size: 0.8rem;
        }
    }

    /* Mobile Apply Filters Button */
    .mobile-apply-filters-wrapper {
        display: none;
    }

    @media (max-width: 1024px) {
        .mobile-apply-filters-wrapper {
            display: block;
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem 0 0;
            margin: 0 -1.5rem -1.5rem;
            background: linear-gradient(to top, #ffffff 70%, rgba(255, 255, 255, 0));
            z-index: 20;
        }

        .mobile-apply-filters-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            width: calc(100% - 2rem);
            margin: 0 auto 1rem;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #2762f3, #1a4dbf);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(39, 98, 243, 0.35);
            letter-spacing: 0.3px;
            font-family: inherit;
        }

        .mobile-apply-filters-btn:hover {
            background: linear-gradient(135deg, #1a4dbf, #153fa0);
            box-shadow: 0 6px 20px rgba(39, 98, 243, 0.45);
            transform: translateY(-1px);
        }

        .mobile-apply-filters-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(39, 98, 243, 0.3);
        }

        .mobile-apply-filters-btn i {
            font-size: 1rem;
        }

        /* Add padding at the bottom of the form so content isn't hidden behind the sticky button */
        #filterForm {
            padding-bottom: 1rem;
        }
    }

    @media (max-width: 640px) {
        .mobile-apply-filters-wrapper {
            margin: 0 -1rem -1rem;
        }

        .mobile-apply-filters-btn {
            width: calc(100% - 1.5rem);
            padding: 0.9rem 1.25rem;
            font-size: 1rem;
            border-radius: 12px;
            margin-bottom: 0.75rem;
        }
    }

    @media (max-width: 380px) {
        .mobile-apply-filters-wrapper {
            margin: 0 -0.875rem -0.875rem;
        }

        .mobile-apply-filters-btn {
            width: calc(100% - 1.25rem);
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            border-radius: 10px;
        }
    }
</style>

<script>
    // Helper function for accordion toggle
    function toggleAttributeAccordion(button) {
        if (!button) return;

        // Find the content element - it's the next sibling with class 'filter-accordion-content'
        let content = button.nextElementSibling;

        // If not found directly, try to find it within the parent
        if (!content || !content.classList.contains('filter-accordion-content')) {
            const parent = button.closest('.filter-accordion');
            if (parent) {
                content = parent.querySelector('.filter-accordion-content');
            }
        }

        if (!content) {
            console.error('Accordion content not found for button:', button);
            return;
        }

        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        // Toggle the accordion
        button.setAttribute('aria-expanded', !isExpanded);
        content.hidden = isExpanded;

        console.log('Accordion toggled:', button.id || 'unknown', 'expanded:', !isExpanded);
    }

    // Debounced filter application
    let filterDebounceTimer = null;
    function debouncedFilterApply(delay = 300) {
        clearTimeout(filterDebounceTimer);
        filterDebounceTimer = setTimeout(function() {
            // Check if parent page has debouncedApplyFilters function (products.blade.php)
            if (typeof window.debouncedApplyFilters === 'function') {
                window.debouncedApplyFilters(0); // Already debounced, apply immediately
            } else if (typeof window.applyFilters === 'function') {
                window.applyFilters();
            } else {
                // Fallback: try to use AJAX directly
                console.warn('⚠️ AJAX functions not found, trying direct fetch...');
                const form = document.getElementById('filterForm');
                if (form) {
                    const formData = new FormData(form);
                    const params = new URLSearchParams();

                    for (const [key, value] of formData.entries()) {
                        if (value && String(value).trim() !== '') {
                            params.append(key, value);
                        }
                    }

                    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                    window.history.pushState({ path: url }, '', url);

                    // Try to fetch and update product grid directly
                    fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.querySelector('.product-grid');
                        const newPagination = doc.querySelector('.pagination-wrapper');
                        const currentGrid = document.querySelector('.product-grid');
                        const currentPagination = document.querySelector('.pagination-wrapper');

                        if (newGrid && currentGrid) {
                            currentGrid.innerHTML = newGrid.innerHTML;
                        }
                        if (newPagination && currentPagination) {
                            currentPagination.innerHTML = newPagination.innerHTML;
                        }
                    })
                    .catch(err => console.error('Fallback fetch failed:', err));
                }
            }
        }, delay);
    }

    // Initialize all accordion buttons
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Filter sidebar initializing...');

        // Prevent form submission - use AJAX instead
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('⛔ Filter form submission prevented - using AJAX');

                // Use AJAX filter function if available
                if (typeof window.debouncedApplyFilters === 'function') {
                    window.debouncedApplyFilters(0);
                } else if (typeof window.applyFilters === 'function') {
                    window.applyFilters();
                }

                return false;
            }, true); // Use capture phase to catch early

            // Also prevent any button clicks that might submit
            filterForm.addEventListener('click', function(e) {
                const target = e.target;
                if (target.tagName === 'BUTTON' && (target.type === 'submit' || !target.type)) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('⛔ Button click prevented in filter form');
                }
            }, true);
        }

        // Setup accordion toggles for tags, categories, brands, and attributes
        const accordionButtons = document.querySelectorAll('.filter-accordion-button');
        console.log('Found accordion buttons:', accordionButtons.length);

        accordionButtons.forEach(button => {
            // Remove any existing listeners by cloning
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleAttributeAccordion(this);
            });
        });

        // Auto-expand accordion if it has active filters
        const tagAccordion = document.getElementById('tagAccordionToggle');
        if (tagAccordion && document.querySelector('input[name="tag"]:checked')) {
            tagAccordion.setAttribute('aria-expanded', 'true');
            document.getElementById('tagAccordionContent').hidden = false;
        }

        const categoryAccordion = document.getElementById('categoryAccordionToggle');
        if (categoryAccordion && document.querySelector('input[name="categories[]"]:checked')) {
            categoryAccordion.setAttribute('aria-expanded', 'true');
            document.getElementById('categoryAccordionContent').hidden = false;
        }

        // Setup stock radio buttons
        const stockRadios = document.querySelectorAll('input[name="stock"]');
        stockRadios.forEach(function(radio) {
            radio.addEventListener('change', function(e) {
                console.log('📦 Stock filter changed:', e.target.value);
                debouncedFilterApply(300);
            });
        });

        // Setup tag radio buttons
        const tagRadios = document.querySelectorAll('input[name="tag"]');
        tagRadios.forEach(function(radio) {
            radio.addEventListener('change', function(e) {
                console.log('🏷️ Tag filter changed:', e.target.value);
                debouncedFilterApply(300);
            });
        });

        // Setup strong offers checkbox
        const strongOffersCheckbox = document.getElementById('strong-offers-checkbox');
        if (strongOffersCheckbox) {
            strongOffersCheckbox.addEventListener('change', function(e) {
                console.log('🔥 Strong offers filter changed:', e.target.checked);
                debouncedFilterApply(300);
            });
        }

        // Setup attribute checkboxes
        const attrCheckboxes = document.querySelectorAll('input[name^="attr["]');
        attrCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function(e) {
                console.log('⚙️ Attribute filter changed:', e.target.name, e.target.value);
                debouncedFilterApply(300);
            });
        });

        // Setup category checkboxes
        const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
        categoryCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function(e) {
                console.log('📂 Category filter changed:', e.target.value, e.target.checked);
                debouncedFilterApply(300);
            });
        });

        // Setup brand checkboxes
        const brandCheckboxes = document.querySelectorAll('input[name="brands[]"]');
        brandCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function(e) {
                console.log('🏷️ Brand filter changed:', e.target.value, e.target.checked);
                debouncedFilterApply(300);
            });
        });

        // Auto-expand brand accordion if any brand is selected
        const brandAccordion = document.getElementById('brandAccordionToggle');
        if (brandAccordion && document.querySelector('input[name="brands[]"]:checked')) {
            brandAccordion.setAttribute('aria-expanded', 'true');
            document.getElementById('brandAccordionContent').hidden = false;
        }

        // Setup dual-range slider highlight (fallback if products.blade.php hasn't done it)
        const rangeMin = document.getElementById('rangeMin');
        const rangeMax = document.getElementById('rangeMax');
        const highlight = document.querySelector('.dual-range-highlight');

        function updateHighlight() {
            if (!rangeMin || !rangeMax || !highlight) return;
            const min = parseInt(rangeMin.value);
            const max = parseInt(rangeMax.value);
            const total = parseInt(rangeMin.max) - parseInt(rangeMin.min);
            if (total <= 0) return;
            const minPct = ((min - parseInt(rangeMin.min)) / total) * 100;
            const maxPct = ((max - parseInt(rangeMin.min)) / total) * 100;
            highlight.style.left = minPct + '%';
            highlight.style.width = (maxPct - minPct) + '%';
        }

        if (rangeMin && rangeMax && !rangeMin._initialized) {
            rangeMin._initialized = true;
            rangeMax._initialized = true;

            rangeMin.addEventListener('input', function() {
                if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) rangeMin.value = rangeMax.value;
                const minPriceInput = document.getElementById('minPriceInput');
                const minPriceHidden = document.getElementById('minPrice');
                if (minPriceInput) minPriceInput.value = rangeMin.value;
                if (minPriceHidden) minPriceHidden.value = rangeMin.value;
                updateHighlight();
            });

            rangeMax.addEventListener('input', function() {
                if (parseInt(rangeMax.value) < parseInt(rangeMin.value)) rangeMax.value = rangeMin.value;
                const maxPriceInput = document.getElementById('maxPriceInput');
                const maxPriceHidden = document.getElementById('maxPrice');
                if (maxPriceInput) maxPriceInput.value = rangeMax.value;
                if (maxPriceHidden) maxPriceHidden.value = rangeMax.value;
                updateHighlight();
            });

            rangeMin.addEventListener('change', function() { debouncedFilterApply(300); });
            rangeMax.addEventListener('change', function() { debouncedFilterApply(300); });

            updateHighlight();
            console.log('✅ Dual-range slider initialized (filter-sidebar)');
        }
    });

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

    window.applyAndCloseMobileFilters = function() {
        // Trigger filter application
        if (typeof window.debouncedApplyFilters === 'function') {
            window.debouncedApplyFilters(0);
        } else if (typeof window.applyFilters === 'function') {
            window.applyFilters();
        } else {
            debouncedFilterApply(0);
        }
        // Close the mobile drawer
        closeMobileFilters();
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

    // Category pagination - show 20 more items per click
    document.addEventListener('DOMContentLoaded', function() {
        const categoryViewMoreBtn = document.getElementById('categoryViewMoreBtn');
        if (categoryViewMoreBtn) {
            categoryViewMoreBtn.addEventListener('click', function() {
                const isRtl = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
                let visibleCount = parseInt(this.dataset.visibleCount) || 10;
                const totalCount = parseInt(this.dataset.totalCount) || 0;
                const items = document.querySelectorAll('.category-filter-item');

                // Show next 20 items
                const newVisibleCount = Math.min(visibleCount + 20, totalCount);

                items.forEach((item, index) => {
                    if (index < newVisibleCount) {
                        item.style.display = 'flex';
                    }
                });

                this.dataset.visibleCount = newVisibleCount;

                // Update button text or hide if all shown
                const remaining = totalCount - newVisibleCount;
                const viewMoreText = document.getElementById('categoryViewMoreText');
                const viewMoreIcon = document.getElementById('categoryViewMoreIcon');

                if (remaining <= 0) {
                    // All items shown - change to "Show less"
                    viewMoreText.textContent = isRtl ? 'عرض أقل' : 'View less';
                    viewMoreIcon.style.transform = 'rotate(180deg)';
                    this.dataset.expanded = 'true';
                } else {
                    viewMoreText.textContent = (isRtl ? 'عرض المزيد' : 'View more') + ' (' + remaining + ')';
                }

                // If already expanded, collapse back to 10
                if (this.dataset.expanded === 'true' && remaining <= 0) {
                    this.addEventListener('click', function collapseHandler() {
                        items.forEach((item, index) => {
                            item.style.display = index < 10 ? 'flex' : 'none';
                        });
                        this.dataset.visibleCount = '10';
                        this.dataset.expanded = 'false';
                        viewMoreText.textContent = (isRtl ? 'عرض المزيد' : 'View more') + ' (' + (totalCount - 10) + ')';
                        viewMoreIcon.style.transform = 'rotate(0deg)';
                        this.removeEventListener('click', collapseHandler);
                    }, { once: true });
                }

                console.log('📂 Category pagination: showing ' + newVisibleCount + ' of ' + totalCount);
            });
        }

        // Brand pagination - show 20 more items per click
        const brandViewMoreBtn = document.getElementById('brandViewMoreBtn');
        if (brandViewMoreBtn) {
            brandViewMoreBtn.addEventListener('click', function() {
                const isRtl = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
                let visibleCount = parseInt(this.dataset.visibleCount) || 10;
                const totalCount = parseInt(this.dataset.totalCount) || 0;
                const items = document.querySelectorAll('.brand-filter-item');

                // Show next 20 items
                const newVisibleCount = Math.min(visibleCount + 20, totalCount);

                items.forEach((item, index) => {
                    if (index < newVisibleCount) {
                        item.style.display = 'flex';
                    }
                });

                this.dataset.visibleCount = newVisibleCount;

                // Update button text or hide if all shown
                const remaining = totalCount - newVisibleCount;
                const viewMoreText = document.getElementById('brandViewMoreText');
                const viewMoreIcon = document.getElementById('brandViewMoreIcon');

                if (remaining <= 0) {
                    // All items shown - change to "Show less"
                    viewMoreText.textContent = isRtl ? 'عرض أقل' : 'View less';
                    viewMoreIcon.style.transform = 'rotate(180deg)';
                    this.dataset.expanded = 'true';
                } else {
                    viewMoreText.textContent = (isRtl ? 'عرض المزيد' : 'View more') + ' (' + remaining + ')';
                }

                // If already expanded, collapse back to 10
                if (this.dataset.expanded === 'true' && remaining <= 0) {
                    this.addEventListener('click', function collapseHandler() {
                        items.forEach((item, index) => {
                            item.style.display = index < 10 ? 'flex' : 'none';
                        });
                        this.dataset.visibleCount = '10';
                        this.dataset.expanded = 'false';
                        viewMoreText.textContent = (isRtl ? 'عرض المزيد' : 'View more') + ' (' + (totalCount - 10) + ')';
                        viewMoreIcon.style.transform = 'rotate(0deg)';
                        this.removeEventListener('click', collapseHandler);
                    }, { once: true });
                }

                console.log('🏷️ Brand pagination: showing ' + newVisibleCount + ' of ' + totalCount);
            });
        }

        // Auto-show selected items that might be hidden
        const selectedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
        selectedCategories.forEach(function(checkbox) {
            const item = checkbox.closest('.category-filter-item');
            if (item) {
                item.style.display = 'flex';
            }
        });

        const selectedBrands = document.querySelectorAll('input[name="brands[]"]:checked');
        selectedBrands.forEach(function(checkbox) {
            const item = checkbox.closest('.brand-filter-item');
            if (item) {
                item.style.display = 'flex';
            }
        });
    });
</script>
