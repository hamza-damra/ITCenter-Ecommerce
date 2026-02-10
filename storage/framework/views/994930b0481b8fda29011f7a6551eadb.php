<?php $__env->startSection('title', __('messages.categories') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    .categories-section {
        padding: 3rem 2rem;
        background: #f5f5f5;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>;
        min-height: calc(100vh - 200px);
    }

    .section-header {
        max-width: 1400px;
        margin: 0 auto 3rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-header h2 {
        font-size: 2.5rem;
        color: #1f2937;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .section-header h2 i {
        color: #1f2937;
        font-size: 2rem;
    }

    .view-more {
        color: #1f2937;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .view-more:hover {
        background: #1f2937;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
        padding: 2rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .category-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .category-icon {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        background: #f8f9fa;
        border: 2px solid #e2e8f0;
    }

    .category-item:hover .category-icon {
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        transform: scale(1.05);
        background: #fff;
        border-color: #1f2937;
    }

    .category-icon i {
        font-size: 2.5rem;
    }

    .category-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 18px;
    }

    .category-name {
        font-size: 1rem;
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .category-count {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .icon-computers { color: #1f2937; }
    .icon-printer { color: #374151; }
    .icon-mobile { color: #4b5563; }
    .icon-bag { color: #1f2937; }
    .icon-laptop { color: #374151; }
    .icon-accessories { color: #4b5563; }
    .icon-monitor { color: #1f2937; }
    .icon-case { color: #374151; }
    .icon-motherboard { color: #4b5563; }
    .icon-cpu { color: #1f2937; }
    .icon-cooler { color: #374151; }
    .icon-gpu { color: #4b5563; }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
        display: block;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1.125rem;
        font-weight: 500;
    }

    /* RTL Support */
    [dir="rtl"] .section-header {
        direction: rtl;
    }

    [dir="rtl"] .view-more {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .categories-grid {
        direction: rtl;
    }

    [dir="rtl"] .category-item {
        direction: rtl;
    }

    /* Responsive - Tablet */
    @media (max-width: 992px) {
        .categories-section {
            padding: 2rem 1.5rem;
        }
        
        .section-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
        }
        
        .section-header h2 {
            font-size: 2rem;
        }
    }

    /* Responsive - Mobile */
    @media (max-width: 768px) {
        .categories-section {
            padding: 1.5rem 1rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }
        
        .section-header h2 {
            font-size: 1.5rem;
            gap: 0.75rem;
        }
        
        .section-header h2 i {
            font-size: 1.5rem;
        }
        
        .view-more {
            width: 100%;
            justify-content: center;
            padding: 0.875rem 1.25rem;
            font-size: 0.9rem;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: #fff;
            border: none;
        }
        
        .view-more:hover {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            transform: translateY(-1px);
        }
        
        .view-more i {
            transition: transform 0.3s ease;
        }
        
        .view-more:hover i {
            transform: translateX(4px);
        }
        
        [dir="rtl"] .view-more:hover i {
            transform: translateX(-4px);
        }

        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .category-item {
            padding: 1.25rem 0.75rem;
            border-radius: 12px;
        }
        
        .category-item:hover {
            transform: translateY(-4px);
        }

        .category-icon {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            margin-bottom: 1rem;
        }

        .category-icon i {
            font-size: 1.75rem;
        }
        
        .category-icon img {
            border-radius: 12px;
        }
        
        .category-name {
            font-size: 0.875rem;
            line-height: 1.3;
            margin-bottom: 0.35rem;
        }
        
        .category-count {
            font-size: 0.75rem;
        }
    }
    
    /* Responsive - Small Mobile */
    @media (max-width: 480px) {
        .categories-section {
            padding: 1rem 0.75rem;
        }
        
        .section-header h2 {
            font-size: 1.35rem;
        }
        
        .categories-grid {
            gap: 0.75rem;
        }
        
        .category-item {
            padding: 1rem 0.5rem;
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
        }
        
        .category-icon i {
            font-size: 1.5rem;
        }
        
        .category-name {
            font-size: 0.8rem;
        }
        
        .category-count {
            font-size: 0.7rem;
        }
    }
</style>

<div class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>
                <i class="fas fa-th-large"></i>
                <?php echo e(__('messages.categories')); ?>

            </h2>
            <a href="<?php echo e(route('products')); ?>" class="view-more">
                <?php echo e(__('messages.view_more')); ?> <i class="fas fa-arrow-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i>
            </a>
        </div>

        <div class="categories-grid">
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="category-item" onclick="window.location.href='<?php echo e(route('category.show', $category->slug)); ?>'">
                <div class="category-icon">
                    <?php if($category->image): ?>
                        <?php if(str_starts_with($category->image, 'http')): ?>
                            <img src="<?php echo e($category->image); ?>" alt="<?php echo e($category->{'name_' . current_locale()} ?? $category->name); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset($category->image)); ?>" alt="<?php echo e($category->{'name_' . current_locale()} ?? $category->name); ?>">
                        <?php endif; ?>
                    <?php else: ?>
                        <i class="fas fa-folder icon-computers"></i>
                    <?php endif; ?>
                </div>
                <div class="category-name"><?php echo e($category->{'name_' . current_locale()} ?? $category->name); ?></div>
                <?php if($category->products_count > 0): ?>
                    <small class="category-count">(<?php echo e($category->products_count); ?> <?php echo e($category->products_count == 1 ? __('messages.product') : __('messages.products')); ?>)</small>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p><?php echo e(__('messages.no_categories')); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/categories.blade.php ENDPATH**/ ?>