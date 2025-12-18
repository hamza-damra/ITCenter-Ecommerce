


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'filters' => [],
    'current' => [],
    'category' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'filters' => [],
    'current' => [],
    'category' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
    if ($currentMinPrice && $currentMinPrice != $priceRange['min']) $activeFilterCount++;
    if ($currentMaxPrice && $currentMaxPrice != $priceRange['max']) $activeFilterCount++;
    foreach ((array)$currentAttributes as $attrValues) {
        $activeFilterCount += count((array)$attrValues);
    }
?>


<?php if (isset($component)) { $__componentOriginald6ef9e8a134c0e9365bc8c39cc85b3cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald6ef9e8a134c0e9365bc8c39cc85b3cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mobile-filter-toggle','data' => ['count' => $activeFilterCount]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mobile-filter-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeFilterCount)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald6ef9e8a134c0e9365bc8c39cc85b3cd)): ?>
<?php $attributes = $__attributesOriginald6ef9e8a134c0e9365bc8c39cc85b3cd; ?>
<?php unset($__attributesOriginald6ef9e8a134c0e9365bc8c39cc85b3cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald6ef9e8a134c0e9365bc8c39cc85b3cd)): ?>
<?php $component = $__componentOriginald6ef9e8a134c0e9365bc8c39cc85b3cd; ?>
<?php unset($__componentOriginald6ef9e8a134c0e9365bc8c39cc85b3cd); ?>
<?php endif; ?>


<div class="mobile-filter-overlay" id="mobileFilterOverlay" onclick="closeMobileFilters()"></div>

<aside class="filter-sidebar" id="filterSidebar" dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>">
    
    <div class="filter-header">
        <h3><?php echo e($isRtl ? 'تصفية' : 'Filters'); ?></h3>
        <div class="filter-header-actions">
            <button type="button" class="clear-filters-btn" id="clearFiltersBtn" onclick="clearAllFilters()">
                <?php echo e($isRtl ? 'مسح الكل' : 'Clear All'); ?>

            </button>
            <button type="button" class="mobile-close-btn" id="mobileCloseBtn" onclick="closeMobileFilters()" aria-label="<?php echo e($isRtl ? 'إغلاق' : 'Close'); ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <form id="filterForm" method="GET" action="<?php echo e(url()->current()); ?>">
        <!-- Preserve search query if exists -->
        <?php if(request('search')): ?>
            <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
        <?php endif; ?>
        
        <?php if(isset($filters['strong_offers'])): ?>
        <div class="filter-section">
            <div class="filter-section-title">
                <i class="fas fa-fire"></i>
                <?php echo e($isRtl ? 'العروض القوية' : 'Strong Offers'); ?>

            </div>
            <div class="category-list">
                <div class="category-checkbox">
                    <input 
                        type="checkbox" 
                        name="strong_offers" 
                        value="1"
                        id="strong-offers-checkbox"
                        <?php echo e($currentStrongOffers ? 'checked' : ''); ?>

                    >
                    <label for="strong-offers-checkbox">
                        <?php echo e($isRtl ? 'عروض قوية فقط' : 'Strong Offers Only'); ?>

                    </label>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if(!empty($stockOptions)): ?>
        <div class="filter-section">
            <div class="filter-section-title">
                <i class="fas fa-box"></i>
                <?php echo e($isRtl ? 'حالة المخزون' : 'Stock Status'); ?>

            </div>
            <div class="category-list">
                <?php $__currentLoopData = $stockOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="category-checkbox">
                        <input 
                            type="radio" 
                            name="stock" 
                            value="<?php echo e($stock['value']); ?>"
                            id="stock-<?php echo e($stock['value']); ?>"
                            <?php echo e($currentStock === $stock['value'] ? 'checked' : ''); ?>

                        >
                        <label for="stock-<?php echo e($stock['value']); ?>">
                            <?php echo e($stock['label']); ?>

                            <span class="item-count"><?php echo e($stock['count']); ?></span>
                        </label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($currentStock): ?>
                    <div class="category-checkbox">
                        <input 
                            type="radio" 
                            name="stock" 
                            value=""
                            id="stock-all"
                        >
                        <label for="stock-all"><?php echo e($isRtl ? 'الكل' : 'All'); ?></label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if(!empty($filters['tags'])): ?>
        <div class="filter-accordion">
            <button type="button"
                    class="filter-accordion-button"
                    id="tagAccordionToggle"
                    aria-expanded="false"
                    aria-controls="tagAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-tags"></i>
                    <span class="filter-accordion-title"><?php echo e($isRtl ? 'الوسوم' : 'Tags'); ?></span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="tagAccordionContent"
                      aria-labelledby="tagAccordionToggle"
                      hidden>
                <legend class="sr-only"><?php echo e($isRtl ? 'تصفية حسب الوسم' : 'Filter by tag'); ?></legend>

                <div class="category-list tag-filter-list">
                    <?php $__currentLoopData = $filters['tags']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $currentTag = request('tag', '');
                        $isChecked = $currentTag === $tag['slug'];
                    ?>
                    <div class="category-checkbox tag-checkbox-item">
                        <input type="radio"
                               name="tag"
                               value="<?php echo e($tag['slug']); ?>"
                               id="tag-<?php echo e($tag['slug']); ?>"
                               <?php echo e($isChecked ? 'checked' : ''); ?>>
                        <label for="tag-<?php echo e($tag['slug']); ?>">
                            <span class="tag-label-content">
                                <?php if($tag['icon']): ?>
                                    <i class="<?php echo e($tag['icon']); ?>" style="color: <?php echo e($tag['color']); ?>;"></i>
                                <?php else: ?>
                                    <span class="tag-color-dot" style="background: <?php echo e($tag['color']); ?>;"></span>
                                <?php endif; ?>
                                <?php echo e($tag['name']); ?>

                            </span>
                            <span class="item-count"><?php echo e($tag['count']); ?></span>
                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(request('tag')): ?>
                        <div class="category-checkbox">
                            <input type="radio" name="tag" value="" id="tag-all">
                            <label for="tag-all"><?php echo e($isRtl ? 'الكل' : 'All'); ?></label>
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>
        </div>
        <?php endif; ?>

        
        <?php if(!empty($filters['categories'])): ?>
        <div class="filter-accordion">
            <button type="button"
                    class="filter-accordion-button"
                    id="categoryAccordionToggle"
                    aria-expanded="false"
                    aria-controls="categoryAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-th-large"></i>
                    <span class="filter-accordion-title"><?php echo e($isRtl ? 'الفئات' : 'Categories'); ?></span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="categoryAccordionContent"
                      aria-labelledby="categoryAccordionToggle"
                      hidden>
                <legend class="sr-only"><?php echo e($isRtl ? 'تصفية حسب الفئة' : 'Filter by category'); ?></legend>

                <div class="category-list">
                    <?php $__currentLoopData = $filters['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $selectedCategories = (array)request('categories', []);
                        if (request('category') && !in_array(request('category'), $selectedCategories)) {
                            $selectedCategories[] = request('category');
                        }
                        $isChecked = in_array($cat['slug'], $selectedCategories);
                    ?>
                    <div class="category-checkbox">
                        <input type="checkbox"
                               name="categories[]"
                               value="<?php echo e($cat['slug']); ?>"
                               id="category-<?php echo e($cat['slug']); ?>"
                               <?php echo e($isChecked ? 'checked' : ''); ?>>
                        <label for="category-<?php echo e($cat['slug']); ?>">
                            <?php echo e($cat['name']); ?>

                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </fieldset>
        </div>
        <?php endif; ?>

        
        <?php if(!empty($brands)): ?>
        <div class="filter-accordion">
            <button type="button"
                    class="filter-accordion-button"
                    id="brandAccordionToggle"
                    aria-expanded="false"
                    aria-controls="brandAccordionContent">
                <span class="filter-accordion-header">
                    <i class="fas fa-tags"></i>
                    <span class="filter-accordion-title"><?php echo e($isRtl ? 'العلامات التجارية' : 'Brands'); ?></span>
                </span>
                <span class="filter-accordion-icon">
                    <i class="fas fa-plus"></i>
                </span>
            </button>

            <fieldset class="filter-accordion-content"
                      id="brandAccordionContent"
                      aria-labelledby="brandAccordionToggle"
                      hidden>
                <legend class="sr-only"><?php echo e($isRtl ? 'تصفية حسب العلامة التجارية' : 'Filter by brand'); ?></legend>

                <div class="brand-list" id="brandList">
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isChecked = in_array($brand['slug'], (array)$currentBrands);
                        $isInitiallyVisible = $index < 10; // Show first 10
                        $hasProducts = $brand['count'] > 0;
                    ?>
                    <div class="brand-checkbox <?php echo e(!$hasProducts ? 'brand-disabled' : ''); ?>" 
                         data-brand-index="<?php echo e($index); ?>" 
                         style="<?php echo e(!$isInitiallyVisible ? 'display: none;' : ''); ?>">
                        <input type="checkbox"
                               name="brands[]"
                               value="<?php echo e($brand['slug']); ?>"
                               id="brand-<?php echo e($brand['slug']); ?>"
                               <?php echo e(!$hasProducts ? 'disabled' : ''); ?>

                               <?php echo e($isChecked ? 'checked' : ''); ?>>
                        <label for="brand-<?php echo e($brand['slug']); ?>">
                            <?php echo e($brand['name']); ?>

                            <span class="item-count <?php echo e(!$hasProducts ? 'count-zero' : ''); ?>"><?php echo e($brand['count']); ?></span>
                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if(count($brands) > 10): ?>
                <button type="button"
                        class="view-more-btn"
                        id="brandViewMoreBtn"
                        onclick="toggleBrandPagination()"
                        aria-label="<?php echo e($isRtl ? 'عرض المزيد من العلامات التجارية' : 'View more brands'); ?>">
                    <span id="brandViewMoreText"><?php echo e($isRtl ? 'عرض المزيد' : 'View more'); ?></span>
                    <i class="fas fa-chevron-down" id="brandViewMoreIcon" aria-hidden="true"></i>
                </button>
                <?php endif; ?>
            </fieldset>
        </div>
        <?php endif; ?>

        
        <div class="filter-section">
            <div class="filter-section-title" id="priceRangeLabel">
                <i class="fas fa-dollar-sign"></i>
                <?php echo e($isRtl ? 'نطاق السعر' : 'Price Range'); ?>

            </div>

            <!-- Live Price Labels Above Slider -->
            <div class="price-range-labels" aria-live="polite" aria-atomic="true">
                <span class="price-label-min" id="minPriceLabel" aria-label="<?php echo e($isRtl ? 'السعر الأدنى' : 'Minimum price'); ?>">₪ <?php echo e(number_format($currentMinPrice ?: $priceRange['min'], 0)); ?></span>
                <span class="price-label-max" id="maxPriceLabel" aria-label="<?php echo e($isRtl ? 'السعر الأقصى' : 'Maximum price'); ?>">₪ <?php echo e(number_format($currentMaxPrice ?: $priceRange['max'], 0)); ?></span>
            </div>

            <!-- Dual-Handle Range Slider -->
            <div class="price-range-slider"
                 role="group"
                 aria-labelledby="priceRangeLabel"
                 aria-describedby="priceRangeDescription">
                <div id="priceSlider"
                     data-min="<?php echo e($priceRange['min']); ?>"
                     data-max="<?php echo e($priceRange['max']); ?>"
                     data-current-min="<?php echo e($currentMinPrice ?: $priceRange['min']); ?>"
                     data-current-max="<?php echo e($currentMaxPrice ?: $priceRange['max']); ?>"></div>
            </div>
            <span id="priceRangeDescription" class="sr-only">
                <?php echo e($isRtl ? 'استخدم مفاتيح الأسهم لتعديل نطاق السعر. اضغط Shift مع السهم للتحرك بشكل أسرع.' : 'Use arrow keys to adjust price range. Hold Shift with arrow keys for faster movement.'); ?>

            </span>

            <!-- Hidden Input Fields for Form Submission -->
            <input type="hidden"
                   name="min_price"
                   id="minPrice"
                   value="<?php echo e($currentMinPrice ?: $priceRange['min']); ?>">
            <input type="hidden"
                   name="max_price"
                   id="maxPrice"
                   value="<?php echo e($currentMaxPrice ?: $priceRange['max']); ?>">
        </div>

        
        <?php if(!empty($attributes)): ?>
            <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <?php echo e($attribute['name']); ?>

                                <?php if($attribute['unit']): ?>
                                    <small>(<?php echo e($attribute['unit']); ?>)</small>
                                <?php endif; ?>
                            </span>
                        </span>
                        <span class="filter-accordion-icon">
                            <i class="fas fa-plus"></i>
                        </span>
                    </button>
                    
                    <div class="filter-accordion-content" hidden>
                        <div class="category-list">
                            <?php $__currentLoopData = $attribute['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $attrSlug = $attribute['slug'];
                                    $currentAttrValues = $currentAttributes[$attrSlug] ?? [];
                                    $isChecked = in_array($value['slug'], (array)$currentAttrValues);
                                ?>
                                <div class="category-checkbox">
                                    <input 
                                        type="checkbox" 
                                        name="attr[<?php echo e($attrSlug); ?>][]" 
                                        value="<?php echo e($value['slug']); ?>"
                                        id="attr-<?php echo e($attrSlug); ?>-<?php echo e($value['slug']); ?>"
                                        <?php echo e($isChecked ? 'checked' : ''); ?>

                                    >
                                    <label for="attr-<?php echo e($attrSlug); ?>-<?php echo e($value['slug']); ?>">
                                        <?php echo e($value['value']); ?>

                                        <span class="item-count"><?php echo e($value['count']); ?></span>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
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

    /* noUiSlider Custom Styles */
    .noUi-target {
        background: #e2e8f0;
        border: none;
        border-radius: 4px;
        height: 6px;
        box-shadow: none;
    }

    .noUi-connect {
        background: linear-gradient(90deg, #2762f3 0%, #3b82f6 100%);
        border-radius: 4px;
    }

    .noUi-horizontal .noUi-handle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #2762f3;
        box-shadow: 0 2px 6px rgba(39, 98, 243, 0.3);
        cursor: pointer;
        top: -7px;
        right: -10px;
    }

    .noUi-horizontal .noUi-handle::before,
    .noUi-horizontal .noUi-handle::after {
        display: none;
    }

    .noUi-handle:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.4);
    }

    .noUi-handle:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(39, 98, 243, 0.2), 0 2px 6px rgba(39, 98, 243, 0.3);
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
        flex-direction: row-reverse;
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
        flex-direction: row-reverse;
    }

    [dir="rtl"] .price-range-labels {
        flex-direction: row-reverse;
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
            <?php if($isRtl): ?>
            right: -100%;
            left: auto;
            <?php else: ?>
            left: -100%;
            right: auto;
            <?php endif; ?>
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
            <?php if($isRtl): ?>
            transform: translateX(-100%);
            <?php else: ?>
            transform: translateX(100%);
            <?php endif; ?>
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
                // Fallback: submit the form directly
                const form = document.getElementById('filterForm');
                if (form) {
                    const formData = new FormData(form);
                    const params = new URLSearchParams();
                    
                    for (const [key, value] of formData.entries()) {
                        if (value && String(value).trim() !== '') {
                            params.append(key, value);
                        }
                    }
                    
                    window.location.href = window.location.pathname + '?' + params.toString();
                }
            }
        }, delay);
    }
    
    // Initialize all accordion buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Setup accordion toggles for tags, categories, brands, and attributes
        const accordionButtons = document.querySelectorAll('.filter-accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
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

        // Auto-expand brand accordion if any brand is selected
        const brandAccordion = document.getElementById('brandAccordionToggle');
        if (brandAccordion && document.querySelector('input[name="brands[]"]:checked')) {
            brandAccordion.setAttribute('aria-expanded', 'true');
            document.getElementById('brandAccordionContent').hidden = false;
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
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/components/filter-sidebar.blade.php ENDPATH**/ ?>