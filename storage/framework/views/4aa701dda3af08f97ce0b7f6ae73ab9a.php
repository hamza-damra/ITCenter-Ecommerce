<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['categories' => []]));

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

foreach (array_filter((['categories' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
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
?>

<nav class="category-nav" dir="<?php echo e(locale_direction()); ?>">
    <div class="category-nav-container">
        <ul class="category-nav-list">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="category-nav-item" data-category-id="<?php echo e($category->id); ?>">
                    <a href="<?php echo e(route('category.show', $category->slug)); ?>" 
                       class="category-nav-link">
                        <?php if($category->icon): ?>
                            <span class="category-icon">
                                <i class="<?php echo e($category->icon); ?>"></i>
                            </span>
                        <?php endif; ?>
                        <span class="category-name"><?php echo e($category->name); ?></span>
                        <?php if($category->children->where('is_active', true)->count() > 0): ?>
                            <i class="fas fa-chevron-<?php echo e($isRtl ? 'left' : 'right'); ?> category-arrow"></i>
                        <?php endif; ?>
                    </a>
                    
                    <?php if($category->children->where('is_active', true)->count() > 0): ?>
                        <div class="category-submenu">
                            <div class="submenu-header">
                                <h3><?php echo e($category->name); ?></h3>
                            </div>
                            <ul class="submenu-list">
                                <?php $__currentLoopData = $category->children->where('is_active', true)->sortBy('position'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="submenu-item">
                                        <a href="<?php echo e(route('category.show', [$category->slug, $subCategory->slug])); ?>" 
                                           class="submenu-link">
                                            <?php echo e($subCategory->name); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</nav>

<style>
    .category-nav {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 100;
    }

    .category-nav-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .category-nav-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0;
        flex-wrap: wrap;
    }

    .category-nav-item {
        position: relative;
    }

    .category-nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.25rem;
        color: #374151;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s ease;
        white-space: nowrap;
        position: relative;
    }

    .category-nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        <?php echo e($isRtl ? 'right' : 'left'); ?>: 0;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #2563eb, #3b82f6);
        transition: width 0.3s ease;
    }

    .category-nav-link:hover {
        color: #2563eb;
        background: rgba(37, 99, 235, 0.05);
    }

    .category-nav-link:hover::after,
    .category-nav-item:hover .category-nav-link::after {
        width: 100%;
    }

    .category-icon {
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        color: #2563eb;
    }

    .category-name {
        flex: 1;
    }

    .category-arrow {
        font-size: 0.75rem;
        color: #9ca3af;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .category-nav-item:hover .category-arrow {
        color: #2563eb;
        transform: translateX(<?php echo e($isRtl ? '-3px' : '3px'); ?>);
    }

    /* Submenu Styles */
    .category-submenu {
        position: absolute;
        top: 100%;
        <?php echo e($isRtl ? 'right' : 'left'); ?>: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        min-width: 250px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
        margin-top: 0;
    }

    .category-nav-item:hover .category-submenu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .submenu-header {
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e5e7eb;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }

    .submenu-header h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
    }

    .submenu-list {
        list-style: none;
        margin: 0;
        padding: 0.5rem 0;
        max-height: 400px;
        overflow-y: auto;
    }

    .submenu-item {
        margin: 0;
    }

    .submenu-link {
        display: block;
        padding: 0.75rem 1.25rem;
        color: #4b5563;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .submenu-link::before {
        content: '';
        position: absolute;
        <?php echo e($isRtl ? 'right' : 'left'); ?>: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background: linear-gradient(90deg, #2563eb, #3b82f6);
        transition: width 0.3s ease;
    }

    .submenu-link:hover {
        color: #2563eb;
        background: rgba(37, 99, 235, 0.05);
        padding-<?php echo e($isRtl ? 'right' : 'left'); ?>: 1.5rem;
    }

    .submenu-link:hover::before {
        width: 3px;
    }

    /* Scrollbar Styles for Submenu */
    .submenu-list::-webkit-scrollbar {
        width: 6px;
    }

    .submenu-list::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    .submenu-list::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .submenu-list::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Mobile Responsive */
    @media (max-width: 968px) {
        .category-nav-container {
            padding: 0 1rem;
        }

        .category-nav-link {
            padding: 0.875rem 1rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 768px) {
        .category-nav {
            display: none; /* Hide on mobile, can be shown in mobile menu */
        }

        /* Alternative: Show as horizontal scroll on mobile */
        .category-nav.mobile-visible {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .category-nav.mobile-visible .category-nav-list {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .category-nav.mobile-visible .category-nav-list::-webkit-scrollbar {
            display: none;
        }

        .category-nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .category-submenu {
            position: fixed;
            top: auto;
            <?php echo e($isRtl ? 'right' : 'left'); ?>: 0;
            bottom: 0;
            width: 100%;
            max-height: 70vh;
            border-radius: 16px 16px 0 0;
            transform: translateY(100%);
        }

        .category-nav-item:hover .category-submenu {
            transform: translateY(0);
        }

        .submenu-list {
            max-height: calc(70vh - 60px);
        }
    }

    @media (max-width: 480px) {
        .category-nav-container {
            padding: 0 0.5rem;
        }

        .category-nav-link {
            padding: 0.625rem 0.75rem;
            font-size: 0.85rem;
            gap: 0.375rem;
        }

        .category-icon {
            width: 20px;
            height: 20px;
            font-size: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle mobile touch interactions for submenu
        if (window.innerWidth <= 768) {
            const categoryItems = document.querySelectorAll('.category-nav-item');
            
            categoryItems.forEach(item => {
                const link = item.querySelector('.category-nav-link');
                const submenu = item.querySelector('.category-submenu');
                
                if (submenu) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Close other open submenus
                        categoryItems.forEach(otherItem => {
                            if (otherItem !== item) {
                                const otherSubmenu = otherItem.querySelector('.category-submenu');
                                if (otherSubmenu) {
                                    otherSubmenu.style.opacity = '0';
                                    otherSubmenu.style.visibility = 'hidden';
                                    otherSubmenu.style.transform = 'translateY(100%)';
                                }
                            }
                        });
                        
                        // Toggle current submenu
                        const isVisible = submenu.style.visibility === 'visible';
                        if (isVisible) {
                            submenu.style.opacity = '0';
                            submenu.style.visibility = 'hidden';
                            submenu.style.transform = 'translateY(100%)';
                        } else {
                            submenu.style.opacity = '1';
                            submenu.style.visibility = 'visible';
                            submenu.style.transform = 'translateY(0)';
                        }
                    });
                }
            });
            
            // Close submenu when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.category-nav-item')) {
                    categoryItems.forEach(item => {
                        const submenu = item.querySelector('.category-submenu');
                        if (submenu) {
                            submenu.style.opacity = '0';
                            submenu.style.visibility = 'hidden';
                            submenu.style.transform = 'translateY(100%)';
                        }
                    });
                }
            });
        }
    });
</script>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/components/category-nav.blade.php ENDPATH**/ ?>