{{-- Filter Sidebar Component — Unified Dynamic Filtering System --}}
{{-- Usage: <x-filter-sidebar :filters="$availableFilters" :current="request()->all()" :category="$category ?? null" /> --}}

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
    $currentDynamicFilters = request('f', []);

    // Get data from filters array
    $categoryTree = $filters['category_tree'] ?? [];
    $brands = $filters['brands'] ?? [];
    $stockOptions = $filters['stock'] ?? [];
    $dynamicFilters = $filters['dynamic_filters'] ?? [];
    $priceRange = $filters['price_range'] ?? ['min' => 0, 'max' => 10000];

    // Section settings (admin-controllable visibility & order)
    $sectionSettings = collect($filters['section_settings'] ?? [])->keyBy('key');
    $sectionOrder = fn ($key) => $sectionSettings->get($key)['sort_order'] ?? 999;
    $sectionEnabled = fn ($key) => $sectionSettings->has($key) ? (bool)($sectionSettings->get($key)['enabled'] ?? true) : true;

    // Round price range for slider
    $sliderMin = (int) floor($priceRange['min']);
    $sliderMax = (int) ceil($priceRange['max']);
    if ($sliderMax <= $sliderMin) $sliderMax = $sliderMin + 100;

    // Calculate active filter count
    $activeFilterCount = 0;
    if ($currentStrongOffers) $activeFilterCount++;
    if ($currentStock) $activeFilterCount++;
    $activeFilterCount += count((array)$currentBrands);
    if ($currentMinPrice !== '' && (int)$currentMinPrice > $sliderMin) $activeFilterCount++;
    if ($currentMaxPrice !== '' && (int)$currentMaxPrice < $sliderMax) $activeFilterCount++;
    foreach ((array)$currentDynamicFilters as $fValues) {
        $activeFilterCount += count((array)$fValues);
    }

    // How many parent categories to show initially
    $catShowLimit = 8;
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

    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="filter-form-ordered">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        {{-- ══ Stock Status Filter (admin-controllable) ══ --}}
        @if($sectionEnabled('status') && !empty($stockOptions))
        <div class="filter-section nav-filter-group" style="order: {{ $sectionOrder('status') }};">
            <div class="filter-section-title">
                {{ $locale === 'ar' ? 'الحالة' : ($locale === 'he' ? 'מצב מלאי' : 'Status') }}
            </div>
            <div class="filter-list">
                @foreach($stockOptions as $stock)
                <div class="filter-check-item">
                    <input type="radio" name="stock" value="{{ $stock['value'] }}" id="stock-{{ $stock['value'] }}" {{ $currentStock === $stock['value'] ? 'checked' : '' }}>
                    <label for="stock-{{ $stock['value'] }}">
                        {{ $stock['label'] }}
                        <span class="item-count">{{ $stock['count'] }}</span>
                    </label>
                </div>
                @endforeach
                @if($currentStock)
                <div class="filter-check-item">
                    <input type="radio" name="stock" value="" id="stock-all">
                    <label for="stock-all">{{ $locale === 'ar' ? 'الكل' : ($locale === 'he' ? 'הכל' : 'All') }}</label>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ══ Strong Offers Toggle (admin-controllable) ══ --}}
        @if($sectionEnabled('strong_offers') && !empty($filters['strong_offers']))
        <div class="filter-section" style="order: {{ $sectionOrder('strong_offers') }};">
            <div class="filter-list">
                <div class="filter-check-item">
                    <input type="checkbox" name="strong_offers" value="1" id="strong-offers-checkbox" {{ $currentStrongOffers ? 'checked' : '' }}>
                    <label for="strong-offers-checkbox">
                        <span class="tag-label-content">
                            <i class="fas fa-fire" style="color:#ef4444;"></i>
                            {{ $locale === 'ar' ? 'العروض القوية فقط' : ($locale === 'he' ? 'מבצעים חזקים בלבד' : 'Strong Offers Only') }}
                        </span>
                    </label>
                </div>
            </div>
        </div>
        @endif

        {{-- ══ Brand Filter (admin-controllable) ══ --}}
        @if($sectionEnabled('brand') && !empty($brands))
        <div class="filter-accordion" style="order: {{ $sectionOrder('brand') }};">
            @php $brandExpanded = count(array_filter((array)$currentBrands)) > 0; @endphp
            <button type="button" class="filter-accordion-button" id="brandAccordionToggle" aria-expanded="{{ $brandExpanded ? 'true' : 'false' }}" aria-controls="brandAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-award"></i>
                    <span class="filter-accordion-title">{{ $locale === 'ar' ? 'العلامة التجارية' : ($locale === 'he' ? 'מותג' : 'Brand') }}</span>
                </span>
                <span class="filter-accordion-icon"><i class="fas fa-{{ $brandExpanded ? 'minus' : 'plus' }}"></i></span>
            </button>
            <fieldset class="filter-accordion-content" id="brandAccordionContent" aria-labelledby="brandAccordionToggle" {{ $brandExpanded ? '' : 'hidden' }}>
                <legend class="sr-only">{{ $locale === 'ar' ? 'تصفية حسب العلامة التجارية' : ($locale === 'he' ? 'סנן לפי מותג' : 'Filter by brand') }}</legend>
                
                <div class="brand-search-wrapper">
                    <input type="text" id="brandSearchInput" class="brand-search-input" placeholder="{{ $locale === 'ar' ? 'بحث الماركات...' : ($locale === 'he' ? 'חפש מותג...' : 'Search brand...') }}" aria-label="{{ $locale === 'ar' ? 'بحث العلامات التجارية' : 'Search brands' }}">
                    <i class="fas fa-search"></i>
                </div>
                <div class="filter-list" id="brandList">
                    @foreach($brands as $index => $brand)
                    @php
                        $isChecked = in_array($brand['slug'], (array)$currentBrands);
                        $isVisible = $index < 10 || $isChecked;
                        $hasProducts = $brand['count'] > 0;
                    @endphp
                    <div class="filter-check-item brand-filter-item {{ !$hasProducts ? 'disabled-item' : '' }}"
                         data-brand-index="{{ $index }}"
                         style="{{ !$isVisible ? 'display:none;' : '' }}">
                        <input type="checkbox" name="brands[]" value="{{ $brand['slug'] }}" id="brand-{{ $brand['slug'] }}" {{ !$hasProducts ? 'disabled' : '' }} {{ $isChecked ? 'checked' : '' }}>
                        <label for="brand-{{ $brand['slug'] }}">
                            {{ $brand['name'] }}
                            <span class="item-count {{ !$hasProducts ? 'count-zero' : '' }}">{{ $brand['count'] }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @if(count($brands) > 10)
                <button type="button" class="view-more-btn" id="brandViewMoreBtn" data-visible-count="10" data-total-count="{{ count($brands) }}">
                    <span id="brandViewMoreText">{{ $locale === 'ar' ? 'عرض المزيد' : ($locale === 'he' ? 'הצג עוד' : 'View more') }} ({{ count($brands) - 10 }})</span>
                    <i class="fas fa-chevron-down" id="brandViewMoreIcon" aria-hidden="true"></i>
                </button>
                @endif
            </fieldset>
        </div>
        @endif

        {{-- ══ Price Range Filter (admin-controllable) ══ --}}
        @if($sectionEnabled('price'))
        <div class="filter-section" style="order: {{ $sectionOrder('price') }};">
            <div class="filter-section-title" id="priceRangeLabel">
                <i class="fas fa-dollar-sign"></i>
                {{ $locale === 'ar' ? 'نطاق السعر' : ($locale === 'he' ? 'טווח מחירים' : 'Price Range') }}
            </div>
            <div class="price-input-container">
                <div class="price-input-wrapper">
                    <label for="minPriceInput" class="price-input-label">{{ $locale === 'ar' ? 'من' : ($locale === 'he' ? 'מ' : 'Min') }}</label>
                    <div class="price-input-group">
                        <span class="price-currency">{{ function_exists('current_currency_symbol') ? current_currency_symbol() : '₪' }}</span>
                        <input type="number" id="minPriceInput" class="price-input" min="{{ $sliderMin }}" max="{{ $sliderMax }}" value="{{ $currentMinPrice ?: $sliderMin }}" aria-label="{{ $locale === 'ar' ? 'السعر الأدنى' : 'Minimum price' }}">
                    </div>
                </div>
                <div class="price-input-separator">-</div>
                <div class="price-input-wrapper">
                    <label for="maxPriceInput" class="price-input-label">{{ $locale === 'ar' ? 'إلى' : ($locale === 'he' ? 'עד' : 'Max') }}</label>
                    <div class="price-input-group">
                        <span class="price-currency">{{ function_exists('current_currency_symbol') ? current_currency_symbol() : '₪' }}</span>
                        <input type="number" id="maxPriceInput" class="price-input" min="{{ $sliderMin }}" max="{{ $sliderMax }}" value="{{ $currentMaxPrice ?: $sliderMax }}" aria-label="{{ $locale === 'ar' ? 'السعر الأقصى' : 'Maximum price' }}">
                    </div>
                </div>
            </div>
            <div class="price-range-slider" role="group" aria-labelledby="priceRangeLabel">
                <div class="dual-range-wrapper">
                    <div class="dual-range-track"></div>
                    <div class="dual-range-highlight"></div>
                    <input type="range" id="rangeMin" min="{{ $sliderMin }}" max="{{ $sliderMax }}" value="{{ $currentMinPrice ?: $sliderMin }}" step="1" aria-label="{{ $locale === 'ar' ? 'السعر الأدنى' : 'Minimum price' }}">
                    <input type="range" id="rangeMax" min="{{ $sliderMin }}" max="{{ $sliderMax }}" value="{{ $currentMaxPrice ?: $sliderMax }}" step="1" aria-label="{{ $locale === 'ar' ? 'السعر الأقصى' : 'Maximum price' }}">
                </div>
            </div>
            <input type="hidden" name="min_price" id="minPrice" value="{{ $currentMinPrice ?: $sliderMin }}">
            <input type="hidden" name="max_price" id="maxPrice" value="{{ $currentMaxPrice ?: $sliderMax }}">
        </div>
        @endif

        {{-- ══ Dynamic Filters (admin-created, unified system) ══ --}}
        @if(!empty($dynamicFilters))
            @foreach($dynamicFilters as $dynFilter)
            <div class="filter-accordion" style="order: {{ 100 + $loop->index }};">
                <button type="button" class="filter-accordion-button" aria-expanded="false">
                    <span class="filter-accordion-header">
                        <i class="fas fa-filter"></i>
                        <span class="filter-accordion-title">{{ $dynFilter['title'] }}</span>
                    </span>
                    <span class="filter-accordion-icon"><i class="fas fa-plus"></i></span>
                </button>
                <div class="filter-accordion-content" hidden>
                    @if(in_array($dynFilter['type'], ['checkbox', 'radio', 'boolean']))
                    <div class="filter-list">
                        @foreach($dynFilter['options'] ?? [] as $option)
                        @php
                            $fSlug = $dynFilter['slug'];
                            $curFValues = $currentDynamicFilters[$fSlug] ?? [];
                            $fChecked = in_array($option['slug'], (array)$curFValues);
                            $fType = ($dynFilter['type'] === 'radio') ? 'radio' : 'checkbox';
                        @endphp
                        <div class="filter-check-item">
                            <input type="{{ $fType }}" name="f[{{ $fSlug }}][]" value="{{ $option['slug'] }}" id="f-{{ $fSlug }}-{{ $option['slug'] }}" {{ $fChecked ? 'checked' : '' }}>
                            <label for="f-{{ $fSlug }}-{{ $option['slug'] }}">
                                @if(!empty($option['color_code']))
                                <span style="display:inline-block;width:14px;height:14px;border-radius:3px;background:{{ $option['color_code'] }};border:1px solid #ccc;margin-inline-end:6px;vertical-align:middle;"></span>
                                @endif
                                {{ $option['label'] }}
                                <span class="item-count">{{ $option['count'] ?? 0 }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @elseif(in_array($dynFilter['type'], ['range', 'min_max']))
                    @php
                        $fSlug = $dynFilter['slug'];
                        $fRange = $dynFilter['range'] ?? ['min' => 0, 'max' => 10000];
                        $curRange = $currentDynamicFilters[$fSlug] ?? [];
                        $curFMin = $curRange['min'] ?? '';
                        $curFMax = $curRange['max'] ?? '';
                    @endphp
                    <div style="padding:12px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="price-input-group" style="flex:1;">
                                <label for="f_{{ $fSlug }}_min" style="font-size:11px;color:#64748b;">Min</label>
                                <input type="number" id="f_{{ $fSlug }}_min" name="f[{{ $fSlug }}][min]" class="price-input" step="any" value="{{ $curFMin }}" placeholder="{{ $fRange['min'] ?? 0 }}">
                            </div>
                            <span style="color:#94a3b8;">—</span>
                            <div class="price-input-group" style="flex:1;">
                                <label for="f_{{ $fSlug }}_max" style="font-size:11px;color:#64748b;">Max</label>
                                <input type="number" id="f_{{ $fSlug }}_max" name="f[{{ $fSlug }}][max]" class="price-input" step="any" value="{{ $curFMax }}" placeholder="{{ $fRange['max'] ?? 10000 }}">
                            </div>
                        </div>
                        <p style="font-size:11px;color:#94a3b8;margin-top:8px;">
                            {{ __('messages.range') ?? 'Range' }}: {{ number_format($fRange['min'] ?? 0) }} – {{ number_format($fRange['max'] ?? 10000) }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        @endif
    </form>

    {{-- Mobile Apply Filters Button --}}
    <div class="mobile-apply-filters-wrapper" id="mobileApplyFiltersWrapper">
        <button type="button" class="mobile-apply-filters-btn" id="mobileApplyFiltersBtn" onclick="applyAndCloseMobileFilters()">
            <i class="fas fa-check"></i>
            <span>{{ $locale === 'ar' ? 'عرض النتائج' : ($locale === 'he' ? 'הצג תוצאות' : 'Show Results') }}</span>
        </button>
    </div>
</aside>
