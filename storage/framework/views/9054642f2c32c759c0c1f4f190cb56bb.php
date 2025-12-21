<?php $__env->startSection('title', __('messages.about') . ' - IT Center'); ?>

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

    .page-header {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
        margin: 1.5rem 1.5rem 3rem 1.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .page-header p {
        font-size: 1.125rem;
        opacity: 0.95;
    }

    .page-container {
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?>;
        padding: 2rem 0 4rem 0;
        background: #f5f5f5;
    }

    .content-section {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 2rem;
        background: #fff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .content-section h2 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .content-section h2:first-child {
        margin-top: 0;
    }

    .content-section h2 i {
        color: #1f2937;
        font-size: 1.5rem;
    }

    .content-section p {
        margin: 1rem 0;
        line-height: 1.8;
        color: #4b5563;
        font-size: 1.0625rem;
    }

    .content-section ul {
        margin: 1rem 0;
        line-height: 1.8;
        color: #4b5563;
        list-style-position: <?php echo e(is_rtl() ? 'inside' : 'outside'); ?>;
        padding-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: <?php echo e(is_rtl() ? '0' : '2rem'); ?>;
    }

    .content-section ul li {
        margin-bottom: 0.75rem;
        padding-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 0.5rem;
    }

    .content-section ul li::marker {
        color: #1f2937;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .stat-card {
        text-align: center;
        padding: 2rem 1.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.3s ease;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .stat-card:hover {
        border-color: #1f2937;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-5px);
    }

    .stat-card h3 {
        color: #1f2937;
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 3rem 1.5rem;
            margin: 1rem 1rem 2rem 1rem;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .content-section {
            padding: 2rem 1.5rem;
        }

        .content-section h2 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>

<div class="page-header">
    <div class="container">
        <h1><?php echo e(__('messages.about_us_title')); ?></h1>
        <p><?php echo e(__('messages.about_us_subtitle')); ?></p>
    </div>
</div>

<div class="container page-container">
    <div class="content-section">
        <h2>
            <i class="fas fa-info-circle"></i>
            <?php echo e(__('messages.who_we_are')); ?>

        </h2>
        <p>
            <?php echo e(__('messages.who_we_are_text')); ?>

        </p>

        <h2>
            <i class="fas fa-bullseye"></i>
            <?php echo e(__('messages.our_mission')); ?>

        </h2>
        <p>
            <?php echo e(__('messages.our_mission_text')); ?>

        </p>

        <h2>
            <i class="fas fa-gift"></i>
            <?php echo e(__('messages.what_we_offer')); ?>

        </h2>
        <ul>
            <li><?php echo e(__('messages.offer_computers')); ?></li>
            <li><?php echo e(__('messages.offer_components')); ?></li>
            <li><?php echo e(__('messages.offer_networking')); ?></li>
            <li><?php echo e(__('messages.offer_software')); ?></li>
            <li><?php echo e(__('messages.offer_support')); ?></li>
            <li><?php echo e(__('messages.offer_custom_pc')); ?></li>
        </ul>

        <h2>
            <i class="fas fa-star"></i>
            <?php echo e(__('messages.why_choose_us')); ?>

        </h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo e(__('messages.years_experience')); ?></h3>
                <p><?php echo e(__('messages.experience')); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo e(__('messages.happy_customers_count')); ?></h3>
                <p><?php echo e(__('messages.happy_customers')); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo e(__('messages.support_24_7')); ?></h3>
                <p><?php echo e(__('messages.support')); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/about.blade.php ENDPATH**/ ?>