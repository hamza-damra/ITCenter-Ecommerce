


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'count' => 0,
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
    'count' => 0,
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
?>

<button class="mobile-filter-toggle" onclick="toggleMobileFilters()" aria-label="<?php echo e($isRtl ? 'فتح التصفية' : 'Open filters'); ?>">
    <span class="mobile-filter-toggle-content">
        <i class="fas fa-filter"></i>
        <span class="mobile-filter-toggle-text"><?php echo e($isRtl ? 'تصفية المنتجات' : 'Filter Products'); ?></span>
        <?php if($count > 0): ?>
            <span class="mobile-filter-badge" aria-label="<?php echo e($isRtl ? "$count مرشحات نشطة" : "$count active filters"); ?>">
                <?php echo e($count); ?>

            </span>
        <?php endif; ?>
    </span>
</button>

<style>
    .mobile-filter-toggle {
        display: none;
        width: 100%;
        padding: 1rem 1.5rem;
        background: white;
        border: 2px solid #2762f3;
        color: #2762f3;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.1);
    }

    .mobile-filter-toggle:hover {
        background: #2762f3;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.2);
    }

    .mobile-filter-toggle:active {
        transform: translateY(0);
    }

    .mobile-filter-toggle-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        position: relative;
    }

    .mobile-filter-toggle i {
        font-size: 1.1rem;
    }

    .mobile-filter-toggle-text {
        font-weight: 600;
    }

    .mobile-filter-badge {
        position: absolute;
        top: -8px;
        <?php if($isRtl): ?>
        left: -8px;
        <?php else: ?>
        right: -8px;
        <?php endif; ?>
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 22px;
        height: 22px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.4rem;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        animation: badgePulse 2s ease-in-out infinite;
    }

    @keyframes badgePulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .mobile-filter-toggle:hover .mobile-filter-badge {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    @media (max-width: 1024px) {
        .mobile-filter-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .mobile-filter-toggle {
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
        }

        .mobile-filter-toggle i {
            font-size: 1rem;
        }

        .mobile-filter-badge {
            min-width: 20px;
            height: 20px;
            font-size: 0.7rem;
        }
    }
</style>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/components/mobile-filter-toggle.blade.php ENDPATH**/ ?>