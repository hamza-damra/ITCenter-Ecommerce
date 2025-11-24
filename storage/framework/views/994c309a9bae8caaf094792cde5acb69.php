


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'subtitle' => '',
    'breadcrumbs' => [],
    'icon' => '',
    'background' => 'default',
    'actions' => null
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
    'title' => '',
    'subtitle' => '',
    'breadcrumbs' => [],
    'icon' => '',
    'background' => 'default',
    'actions' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="page-header <?php echo e($background === 'gradient' ? 'page-header-gradient' : ''); ?>">
    <div class="container">
        <div class="page-header-content">
            
            <div class="page-header-main">
                
                <?php if(!empty($breadcrumbs)): ?>
                    <nav class="breadcrumbs" aria-label="Breadcrumb">
                        <ol class="breadcrumb-list">
                            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="breadcrumb-item <?php echo e($loop->last ? 'active' : ''); ?>">
                                    <?php if(!$loop->last && isset($breadcrumb['url'])): ?>
                                        <a href="<?php echo e($breadcrumb['url']); ?>" class="breadcrumb-link">
                                            <?php if(isset($breadcrumb['icon'])): ?>
                                                <i class="<?php echo e($breadcrumb['icon']); ?>"></i>
                                            <?php endif; ?>
                                            <?php echo e($breadcrumb['title']); ?>

                                        </a>
                                    <?php else: ?>
                                        <?php if(isset($breadcrumb['icon'])): ?>
                                            <i class="<?php echo e($breadcrumb['icon']); ?>"></i>
                                        <?php endif; ?>
                                        <?php echo e($breadcrumb['title']); ?>

                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </nav>
                <?php endif; ?>

                
                <div class="page-title-section">
                    <?php if($icon): ?>
                        <div class="page-icon">
                            <i class="<?php echo e($icon); ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="page-title-content">
                        <h1 class="page-title"><?php echo e($title); ?></h1>
                        <?php if($subtitle): ?>
                            <p class="page-subtitle"><?php echo e($subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php if($actions): ?>
                <div class="page-header-actions">
                    <?php echo e($actions); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <?php if(isset($slot) && !empty(trim($slot))): ?>
            <div class="page-header-extra">
                <?php echo e($slot); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .page-header {
        background: var(--bg-card);
        border-bottom: 1px solid #e2e8f0;
        padding: var(--space-8) 0;
        margin-bottom: var(--space-8);
    }

    .page-header-gradient {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        color: var(--text-white);
    }

    .page-header-gradient .page-title,
    .page-header-gradient .page-subtitle,
    .page-header-gradient .breadcrumb-link {
        color: var(--text-white);
    }

    .page-header-gradient .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.8);
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--space-8);
    }

    .page-header-main {
        flex: 1;
    }

    /* Breadcrumbs */
    .breadcrumbs {
        margin-bottom: var(--space-4);
    }

    .breadcrumb-list {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: var(--text-sm);
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
        color: var(--text-secondary);
    }

    .breadcrumb-item:not(:last-child)::after {
        content: '/';
        margin: 0 var(--space-2);
        color: var(--text-muted);
        font-weight: 300;
    }

    .breadcrumb-item.active {
        color: var(--text-primary);
        font-weight: 500;
    }

    .breadcrumb-link {
        color: var(--text-secondary);
        text-decoration: none;
        transition: color var(--transition-normal);
        display: flex;
        align-items: center;
        gap: var(--space-1);
    }

    .breadcrumb-link:hover {
        color: var(--primary-blue);
    }

    /* Title Section */
    .page-title-section {
        display: flex;
        align-items: center;
        gap: var(--space-4);
    }

    .page-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light-blue) 100%);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-white);
        font-size: var(--text-2xl);
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
    }

    .page-header-gradient .page-icon {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .page-title-content {
        flex: 1;
    }

    .page-title {
        font-size: var(--text-4xl);
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 var(--space-2) 0;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: var(--text-lg);
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.4;
    }

    /* Actions */
    .page-header-actions {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        flex-shrink: 0;
    }

    /* Extra Content */
    .page-header-extra {
        margin-top: var(--space-6);
        padding-top: var(--space-6);
        border-top: 1px solid #e2e8f0;
    }

    .page-header-gradient .page-header-extra {
        border-top-color: rgba(255, 255, 255, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: var(--space-6) 0;
            margin-bottom: var(--space-6);
        }

        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-4);
        }

        .page-title-section {
            gap: var(--space-3);
        }

        .page-icon {
            width: 50px;
            height: 50px;
            font-size: var(--text-xl);
        }

        .page-title {
            font-size: var(--text-3xl);
        }

        .page-subtitle {
            font-size: var(--text-base);
        }

        .breadcrumb-list {
            flex-wrap: wrap;
        }

        .page-header-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            padding: var(--space-4) 0;
        }

        .page-title-section {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-3);
        }

        .page-title {
            font-size: var(--text-2xl);
        }

        .page-icon {
            width: 45px;
            height: 45px;
            font-size: var(--text-lg);
        }
    }

    /* RTL Support */
    <?php if(is_rtl()): ?>
    .breadcrumb-item:not(:last-child)::after {
        content: '\\';
        transform: scaleX(-1);
    }

    .page-title-section {
        flex-direction: row-reverse;
    }

    .page-header-content {
        flex-direction: row-reverse;
    }
    <?php endif; ?>

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .page-header {
            background: #1f2937;
            border-bottom-color: #374151;
        }

        .page-title {
            color: #f9fafb;
        }

        .page-subtitle {
            color: #d1d5db;
        }

        .breadcrumb-item {
            color: #9ca3af;
        }

        .breadcrumb-item.active {
            color: #f3f4f6;
        }

        .breadcrumb-link {
            color: #9ca3af;
        }

        .breadcrumb-link:hover {
            color: #60a5fa;
        }
    }

    /* Print styles */
    @media print {
        .page-header {
            background: none !important;
            box-shadow: none !important;
            border-bottom: 2px solid #000 !important;
            padding: var(--space-4) 0 !important;
        }

        .page-header-actions {
            display: none !important;
        }

        .page-icon {
            display: none !important;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .page-header {
            border-bottom: 2px solid currentColor;
        }

        .page-icon {
            border: 2px solid currentColor;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .breadcrumb-link,
        .page-icon {
            transition: none;
        }
    }
</style>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\components\page-header.blade.php ENDPATH**/ ?>