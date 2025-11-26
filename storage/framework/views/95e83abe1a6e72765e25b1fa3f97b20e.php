

<?php $__env->startSection('title', __('messages.banners_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Banners Page Specific Styles */
    .banners-header {
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

    .banner-thumbnail {
        width: 120px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .banner-thumbnail-placeholder {
        width: 120px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: #94a3b8;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .banner-title-cell {
        max-width: 200px;
    }

    .banner-title-text {
        font-weight: 600;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .banner-subtitle-text {
        font-size: 12px;
        color: var(--secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        margin-top: 4px;
    }

    .order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        border-radius: 8px;
        font-weight: 700;
        color: var(--dark);
        font-size: 14px;
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
        <h1><i class="fas fa-images"></i> <?php echo e(__('messages.banners_management')); ?></h1>
        <p><?php echo e(__('messages.manage_banners_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.banners.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_banner')); ?>

        </a>
    </div>
</div>

<!-- Stats Overview -->
<?php
    $totalBanners = $banners->total() ?? count($banners);
    $activeBanners = \App\Models\Banner::where('is_active', true)->count();
    $inactiveBanners = \App\Models\Banner::where('is_active', false)->count();
?>
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-images"></i> <?php echo e(__('messages.total_banners')); ?></h4>
        <div class="number"><?php echo e($totalBanners); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_banners')); ?></h4>
        <div class="number" style="color: var(--success);"><?php echo e($activeBanners); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--secondary);">
        <h4><i class="fas fa-eye-slash"></i> <?php echo e(__('messages.inactive_banners')); ?></h4>
        <div class="number" style="color: var(--secondary);"><?php echo e($inactiveBanners); ?></div>
    </div>
</div>

<!-- Banners Table -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> <?php echo e(__('messages.banner_list')); ?></h2>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if($banners->count() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.image')); ?></th>
                        <th><?php echo e(__('messages.title')); ?></th>
                        <th><?php echo e(__('messages.link')); ?></th>
                        <th><?php echo e(__('messages.display_order')); ?></th>
                        <th><?php echo e(__('messages.status')); ?></th>
                        <th><?php echo e(__('messages.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php if($banner->image_path): ?>
                                    <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title_en ?? 'Banner'); ?>" class="banner-thumbnail">
                                <?php else: ?>
                                    <div class="banner-thumbnail-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="banner-title-cell">
                                <span class="banner-title-text" title="<?php echo e($banner->title_en ?? $banner->title_ar ?? $banner->title_he ?? __('messages.no_title')); ?>">
                                    <?php echo e($banner->title_en ?? $banner->title_ar ?? $banner->title_he ?? __('messages.no_title')); ?>

                                </span>
                                <?php if($banner->subtitle_en || $banner->subtitle_ar || $banner->subtitle_he): ?>
                                    <span class="banner-subtitle-text" title="<?php echo e($banner->subtitle_en ?? $banner->subtitle_ar ?? $banner->subtitle_he); ?>">
                                        <?php echo e($banner->subtitle_en ?? $banner->subtitle_ar ?? $banner->subtitle_he); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($banner->link): ?>
                                    <a href="<?php echo e($banner->link); ?>" target="_blank" class="link-badge" title="<?php echo e($banner->link); ?>">
                                        <i class="fas fa-external-link-alt"></i>
                                        <?php echo e(parse_url($banner->link, PHP_URL_HOST) ?? $banner->link); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="no-link"><?php echo e(__('messages.no_link')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="order-badge"><?php echo e($banner->display_order); ?></span>
                            </td>
                            <td>
                                <span class="badge <?php echo e($banner->is_active ? 'badge-success' : 'badge-danger'); ?>">
                                    <?php echo e($banner->is_active ? __('messages.active') : __('messages.inactive')); ?>

                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('admin.banners.edit', $banner)); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                                    </a>
                                    <form action="<?php echo e(route('admin.banners.destroy', $banner)); ?>" method="POST" style="display: inline;"
                                          onsubmit="handleFormConfirm(event, {
                                              message: '<?php echo e(__('messages.delete_banner_confirm')); ?>',
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
                <i class="fas fa-images"></i>
                <h3><?php echo e(__('messages.no_banners_found')); ?></h3>
                <p><?php echo e(__('messages.no_banners_description')); ?></p>
                <a href="<?php echo e(route('admin.banners.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_first_banner')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination -->
<?php if($banners->hasPages()): ?>
    <div style="margin-top: 24px;">
        <?php echo e($banners->links()); ?>

    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\banners\index.blade.php ENDPATH**/ ?>