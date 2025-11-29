

<?php $__env->startSection('title', __('messages.promotional_ads_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Promotional Ads Page Specific Styles */
    .promo-ads-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
    }

    .stat-mini-card h4 {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [dir="rtl"] .stat-mini-card h4 {
        text-transform: none;
        letter-spacing: normal;
    }

    .stat-mini-card .number {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
    }

    .ad-thumbnail {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .ad-thumbnail-placeholder {
        width: 120px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: #94a3b8;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .position-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    [dir="rtl"] .position-badge {
        text-transform: none;
    }

    .position-left {
        background: #dbeafe;
        color: #1e40af;
    }

    .position-right {
        background: #fce7f3;
        color: #9d174d;
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }

    .empty-state h3 {
        font-size: 20px;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--secondary);
        margin-bottom: 24px;
    }

    .link-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: #eff6ff;
        color: var(--primary);
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .link-badge:hover {
        background: #dbeafe;
    }

    .no-link {
        color: #94a3b8;
        font-size: 12px;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .stats-overview {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-ad"></i> <?php echo e(__('messages.promotional_ads_management')); ?></h1>
        <p><?php echo e(__('messages.manage_promotional_ads_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.promotional-ads.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_promotional_ad')); ?>

        </a>
    </div>
</div>

<!-- Stats Overview -->
<?php
    $totalAds = $promotionalAds->total() ?? count($promotionalAds);
    $activeAds = \App\Models\PromotionalAd::where('is_active', true)->count();
    $leftAds = \App\Models\PromotionalAd::where('position', 'left')->where('is_active', true)->count();
    $rightAds = \App\Models\PromotionalAd::where('position', 'right')->where('is_active', true)->count();
?>
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-ad"></i> <?php echo e(__('messages.total_ads')); ?></h4>
        <div class="number"><?php echo e($totalAds); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_ads')); ?></h4>
        <div class="number" style="color: var(--success);"><?php echo e($activeAds); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: #1e40af;">
        <h4><i class="fas fa-arrow-left"></i> <?php echo e(__('messages.left_position')); ?></h4>
        <div class="number" style="color: #1e40af;"><?php echo e($leftAds); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: #9d174d;">
        <h4><i class="fas fa-arrow-right"></i> <?php echo e(__('messages.right_position')); ?></h4>
        <div class="number" style="color: #9d174d;"><?php echo e($rightAds); ?></div>
    </div>
</div>

<!-- Promotional Ads Table -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> <?php echo e(__('messages.promotional_ad_list')); ?></h2>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if($promotionalAds->count() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.image')); ?></th>
                        <th><?php echo e(__('messages.position')); ?></th>
                        <th><?php echo e(__('messages.link')); ?></th>
                        <th><?php echo e(__('messages.status')); ?></th>
                        <th><?php echo e(__('messages.updated_at')); ?></th>
                        <th><?php echo e(__('messages.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $promotionalAds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php if($ad->image_path): ?>
                                    <img src="<?php echo e($ad->image_url); ?>" alt="<?php echo e(__('messages.promotional_ad')); ?>" class="ad-thumbnail">
                                <?php else: ?>
                                    <div class="ad-thumbnail-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="position-badge position-<?php echo e($ad->position); ?>">
                                    <i class="fas fa-arrow-<?php echo e($ad->position); ?>"></i>
                                    <?php echo e(__('messages.' . $ad->position)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($ad->link): ?>
                                    <a href="<?php echo e($ad->link); ?>" target="_blank" class="link-badge" title="<?php echo e($ad->link); ?>">
                                        <i class="fas fa-external-link-alt"></i>
                                        <?php echo e(parse_url($ad->link, PHP_URL_HOST) ?? $ad->link); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="no-link"><?php echo e(__('messages.no_link')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo e($ad->is_active ? 'badge-success' : 'badge-danger'); ?>">
                                    <?php echo e($ad->is_active ? __('messages.active') : __('messages.inactive')); ?>

                                </span>
                            </td>
                            <td>
                                <?php echo e($ad->updated_at->format('Y-m-d H:i')); ?>

                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('admin.promotional-ads.edit', $ad)); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                                    </a>
                                    <form action="<?php echo e(route('admin.promotional-ads.destroy', $ad)); ?>" method="POST" style="display: inline;"
                                          onsubmit="handleFormConfirm(event, {
                                              message: '<?php echo e(__('messages.delete_promotional_ad_confirm')); ?>',
                                              confirmText: '<?php echo e(__('messages.yes_delete')); ?>',
                                              type: 'danger',
                                              confirmButtonType: 'danger'
                                          })">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-ad"></i>
                <h3><?php echo e(__('messages.no_promotional_ads_found')); ?></h3>
                <p><?php echo e(__('messages.no_promotional_ads_description')); ?></p>
                <a href="<?php echo e(route('admin.promotional-ads.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_first_promotional_ad')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination -->
<?php if($promotionalAds->hasPages()): ?>
    <div style="margin-top: 24px;">
        <?php echo e($promotionalAds->links()); ?>

    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/admin/promotional-ads/index.blade.php ENDPATH**/ ?>