<?php $__env->startSection('title', $category->name . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<!-- Import shared components CSS -->
<link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
<!-- noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Import Google Fonts - Poppins & Cairo for Arabic */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Cairo:wght@300;400;500;600;700;800;900&display=swap');

    /* Override font - exclude Font Awesome icons */
    body,
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        <?php if(is_rtl()): ?>
        font-family: 'Cairo', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        <?php else: ?>
        font-family: 'Poppins', 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        <?php endif; ?>
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    .category-section {
        padding: var(--space-12) 0;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    .category-container {
        display: flex;
        gap: var(--space-8);
        align-items: flex-start;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 var(--space-8);
    }

    /* Breadcrumb Navigation */
    .breadcrumb-nav {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(37, 99, 235, 0.02) 100%);
        border-radius: 16px;
        padding: 1.25rem 1.75rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.08);
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        list-style: none;
        margin: 0;
        padding: 0;
        <?php if(is_rtl()): ?>
        direction: rtl;
        <?php endif; ?>
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 500;
    }

    .breadcrumb-item a {
        color: #3B82F6;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    .breadcrumb-item a:hover {
        color: #2563EB;
        background: rgba(59, 130, 246, 0.1);
    }

    .breadcrumb-item.active {
        color: #1e293b;
        font-weight: 600;
    }

    .breadcrumb-separator {
        color: #cbd5e1;
        font-size: 0.85rem;
        <?php if(is_rtl()): ?>
        transform: rotate(180deg);
        <?php endif; ?>
    }

    /* Category Header */
    .category-header {
        margin-bottom: 2.5rem;
    }

    .category-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #1e293b 0%, #3B82F6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        <?php if(is_rtl()): ?>
        text-align: right;
        <?php endif; ?>
    }

    .category-description {
        font-size: 1.05rem;
        color: #64748b;
        line-height: 1.7;
        max-width: 800px;
        <?php if(is_rtl()): ?>
        text-align: right;
        <?php endif; ?>
    }

    .products-content {
        flex: 1;
        min-width: 0;
    }



    /* Loading Indicator */
    .products-loading-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        min-height: 400px;
        border-radius: 20px;
    }

    .products-loading-container.active {
        display: flex;
    }

    .products-loading-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem;
    }

    .products-loading-spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #e2e8f0;
        border-top-color: #2762f3;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .products-loading-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #334155;
        text-align: center;
    }

    .products-loading-subtext {
        font-size: 0.9rem;
        color: #64748b;
        text-align: center;
    }

    .products-content {
        position: relative;
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
        padding: 0.5rem;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .no-results::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(78, 115, 223, 0.05) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .no-results-content {
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .no-results-icon {
        width: 160px;
        height: 160px;
        margin: 0 auto 2.5rem;
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 60px rgba(236, 72, 153, 0.3), 0 0 100px rgba(139, 92, 246, 0.2);
        animation: iconFloat 4s ease-in-out infinite;
        position: relative;
    }

    .no-results-icon::before {
        content: '';
        position: absolute;
        inset: -10px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.3) 0%, rgba(139, 92, 246, 0.3) 100%);
        filter: blur(20px);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes iconFloat {
        0%, 100% {
            transform: translateY(0) scale(1);
        }
        25% {
            transform: translateY(-15px) scale(1.02);
        }
        50% {
            transform: translateY(0) scale(1);
        }
        75% {
            transform: translateY(-8px) scale(0.98);
        }
    }

    .no-results-icon i {
        font-size: 4.5rem;
        color: white;
        margin: 0;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
    }

    .no-results h3 {
        font-size: 2.25rem;
        color: #1e293b;
        margin-bottom: 1.25rem;
        font-weight: 700;
        line-height: 1.3;
        <?php if(is_rtl()): ?>
        direction: rtl;
        <?php endif; ?>
    }

    .no-results p {
        font-size: 1.15rem;
        color: #64748b;
        margin-bottom: 3rem;
        line-height: 1.8;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        <?php if(is_rtl()): ?>
        direction: rtl;
        text-align: center;
        <?php endif; ?>
    }

    .no-results-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        <?php if(is_rtl()): ?>
        flex-direction: row-reverse;
        <?php endif; ?>
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
        padding: 1.1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35);
        font-size: 1.05rem;
        border: none;
        <?php if(is_rtl()): ?>
        flex-direction: row-reverse;
        <?php endif; ?>
    }

    .btn-primary-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.45);
        color: white;
    }

    .btn-primary-action:active {
        transform: translateY(-1px);
    }

    .btn-secondary-action {
        background: white;
        color: #3B82F6;
        padding: 1.1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #3B82F6;
        font-size: 1.05rem;
        <?php if(is_rtl()): ?>
        flex-direction: row-reverse;
        <?php endif; ?>
    }

    .btn-secondary-action:hover {
        background: #3B82F6;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary-action:active {
        transform: translateY(-1px);
    }

    /* Pagination Styling */
    .pagination {
        display: flex !important;
        gap: 0.5rem !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 1rem 0 !important;
        list-style: none !important;
        margin: 0 !important;
    }

    .pagination li {
        list-style: none !important;
        margin: 0 !important;
    }

    .pagination .page-item {
        list-style: none !important;
    }

    .pagination .page-link,
    .pagination a,
    .pagination span {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 40px !important;
        height: 40px !important;
        padding: 0.5rem 1rem !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        color: #333 !important;
        text-decoration: none !important;
        font-weight: 500 !important;
        transition: all 0.3s !important;
        font-size: 1rem !important;
        line-height: 1 !important;
    }

    .pagination .page-link:hover,
    .pagination a:hover {
        background: #4169E1 !important;
        color: #fff !important;
        border-color: #4169E1 !important;
        transform: translateY(-2px) !important;
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item.active span,
    .pagination .active span {
        background: #4169E1 !important;
        color: #fff !important;
        border-color: #4169E1 !important;
    }

    .pagination .page-item.disabled .page-link,
    .pagination .page-item.disabled span,
    .pagination .disabled span {
        background: #f5f5f5 !important;
        color: #999 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    .pagination .page-link svg,
    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }

    /* Hide default nav wrapper styles */
    .pagination nav {
        width: 100% !important;
    }

    .pagination-wrapper {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }

    .pagination-wrapper nav {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
    }

    .pagination-wrapper ul {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.5rem !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .category-container {
            flex-direction: column;
        }


    }

    @media (max-width: 768px) {
        .category-section {
            padding: 2rem 1rem;
        }

        .breadcrumb-nav {
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
        }

        .category-title {
            font-size: 1.75rem;
        }

        .category-description {
            font-size: 0.95rem;
        }

        .no-results {
            padding: 3rem 1.5rem;
        }

        .no-results-icon {
            width: 120px;
            height: 120px;
        }

        .no-results-icon i {
            font-size: 3rem;
        }

        .no-results h3 {
            font-size: 1.75rem;
        }

        .no-results p {
            font-size: 1rem;
        }

        .no-results-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-primary-action,
        .btn-secondary-action {
            width: 100%;
            justify-content: center;
        }

        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-nav {
            padding: 0.875rem 1rem;
        }

        .breadcrumb-item {
            font-size: 0.85rem;
        }

        .category-title {
            font-size: 1.5rem;
        }

        .no-results-icon {
            width: 100px;
            height: 100px;
        }

        .no-results-icon i {
            font-size: 2.5rem;
        }

        .no-results h3 {
            font-size: 1.5rem;
        }

        .btn-primary-action,
        .btn-secondary-action {
            padding: 1rem 2rem;
            font-size: 0.95rem;
        }
    }
</style>

<div class="category-section">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <ol class="breadcrumb">
                <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="breadcrumb-item <?php echo e($breadcrumb['url'] === null ? 'active' : ''); ?>">
                        <?php if($breadcrumb['url']): ?>
                            <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['name']); ?></a>
                        <?php else: ?>
                            <?php echo e($breadcrumb['name']); ?>

                        <?php endif; ?>
                    </li>
                    <?php if($index < count($breadcrumbs) - 1): ?>
                        <li class="breadcrumb-separator" aria-hidden="true">
                            <i class="fas fa-chevron-right"></i>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        </nav>

        <!-- Category Header -->
        <div class="category-header">
            <h1 class="category-title"><?php echo e($category->name); ?></h1>
            <?php if($category->description): ?>
                <p class="category-description"><?php echo e($category->description); ?></p>
            <?php endif; ?>
        </div>

        <div class="category-container">
            <!-- Filter Sidebar Component (includes mobile toggle button) -->
            <?php if (isset($component)) { $__componentOriginalbe8e3a5154d26c68e645d9e6759b4422 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbe8e3a5154d26c68e645d9e6759b4422 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.filter-sidebar','data' => ['filters' => $availableFilters,'current' => request()->all(),'category' => $category]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filter-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($availableFilters),'current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->all()),'category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbe8e3a5154d26c68e645d9e6759b4422)): ?>
<?php $attributes = $__attributesOriginalbe8e3a5154d26c68e645d9e6759b4422; ?>
<?php unset($__attributesOriginalbe8e3a5154d26c68e645d9e6759b4422); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbe8e3a5154d26c68e645d9e6759b4422)): ?>
<?php $component = $__componentOriginalbe8e3a5154d26c68e645d9e6759b4422; ?>
<?php unset($__componentOriginalbe8e3a5154d26c68e645d9e6759b4422); ?>
<?php endif; ?>

            <!-- Products Content -->
            <div class="products-content" id="productsContent">
                <!-- Loading Indicator -->
                <div class="products-loading-container" id="productsLoading">
                    <div class="products-loading-content">
                        <div class="products-loading-spinner"></div>
                        <div class="products-loading-text"><?php echo e(is_rtl() ? 'جاري التحميل...' : 'Loading...'); ?></div>
                        <div class="products-loading-subtext"><?php echo e(is_rtl() ? 'يرجى الانتظار' : 'Please wait'); ?></div>
                    </div>
                </div>

                <?php if($products->count() > 0): ?>
                <div class="product-grid">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('components.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="no-results">
                    <div class="no-results-content">
                        <div class="no-results-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>
                            <?php if(is_rtl()): ?>
                                لم يتم العثور على منتجات
                            <?php else: ?>
                                No Products Found
                            <?php endif; ?>
                        </h3>
                        <p>
                            <?php if(is_rtl()): ?>
                                لا توجد منتجات متاحة في هذه الفئة حالياً.<br>
                                جرب تصفح فئات أخرى أو عرض جميع المنتجات.
                            <?php else: ?>
                                No products are currently available in this category.<br>
                                Try browsing other categories or view all products.
                            <?php endif; ?>
                        </p>
                        <div class="no-results-actions">
                            <a href="<?php echo e(route('products')); ?>" class="btn-primary-action">
                                <?php if(is_rtl()): ?>
                                    <span>عرض جميع المنتجات</span>
                                    <i class="fas fa-th-large"></i>
                                <?php else: ?>
                                    <i class="fas fa-th-large"></i>
                                    <span>View All Products</span>
                                <?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('home')); ?>" class="btn-secondary-action">
                                <?php if(is_rtl()): ?>
                                    <span>العودة للرئيسية</span>
                                    <i class="fas fa-home"></i>
                                <?php else: ?>
                                    <i class="fas fa-home"></i>
                                    <span>Back to Home</span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages()): ?>
                <div class="pagination-wrapper" style="display: flex; justify-content: center; margin: 3rem 0 2rem 0; padding: 0 1rem; width: 100%;">
                    <?php echo e($products->links()); ?>

                </div>
                <?php endif; ?>
            </div><!-- End products-content -->
        </div><!-- End category-container -->
    </div><!-- End container -->
</div><!-- End category-section -->

<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Include filter sidebar JavaScript -->
<script src="<?php echo e(asset('js/filter-sidebar.js')); ?>"></script>

<script>
(function() {
    'use strict';
    console.log('🚀 Category Products Filter System Initialized');
    
    // Mobile filter toggle
    window.toggleMobileFilters = function() {
        const sidebar = document.getElementById('filterSidebar');
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    };
})();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/category-products.blade.php ENDPATH**/ ?>