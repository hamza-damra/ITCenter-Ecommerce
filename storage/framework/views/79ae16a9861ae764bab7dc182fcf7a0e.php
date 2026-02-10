<?php $__env->startSection('title', __('messages.tags_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Page-specific styles that extend unified components */
    
    /* Tag Name Cell */
    .tag-name-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tag-name-cell .tag-icon {
        font-size: 1rem;
    }

    .tag-name-cell .tag-name-secondary {
        color: #64748b;
        font-size: 0.75rem;
    }

    /* Color Swatch */
    .color-swatch {
        display: inline-block;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 2px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Slug Code */
    .slug-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.8rem;
        background: #f1f5f9;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        color: #475569;
    }

    /* Products Count Badge */
    .products-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 0.35rem 0.6rem;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e40af;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 8px;
    }

    /* Header Actions */
    .header-actions .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-indigo) 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3);
    }

    .header-actions .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 0.85rem;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-edit {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        transform: translateY(-1px);
    }

    .btn-delete {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        transform: translateY(-1px);
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border, #e2e8f0);
        background: linear-gradient(135deg, var(--bg-secondary, #f8fafc) 0%, var(--bg-tertiary, #f1f5f9) 100%);
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h1><?php echo e(__('messages.tags_management')); ?></h1>
                <p><?php echo e(__('messages.manage_product_tags')); ?></p>
            </div>
        </div>
        <div class="header-actions">
            <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn-add">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_tag')); ?>

            </a>
        </div>
    </div>
</div>

<!-- Tags Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> <?php echo e(__('messages.tags_list') ?? __('messages.tags')); ?></h3>
    </div>
    
    <?php if($tags->count() > 0): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.tag_name')); ?></th>
                    <th><?php echo e(__('messages.slug')); ?></th>
                    <th><?php echo e(__('messages.color')); ?></th>
                    <th><?php echo e(__('messages.products_count')); ?></th>
                    <th><?php echo e(__('messages.status')); ?></th>
                    <th><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="tag-name-cell">
                            <?php if($tag->icon): ?>
                                <i class="<?php echo e($tag->icon); ?> tag-icon" style="color: <?php echo e($tag->color); ?>"></i>
                            <?php endif; ?>
                            <span><?php echo e($tag->name_en); ?></span>
                            <span class="tag-name-secondary">(<?php echo e($tag->name_ar); ?>)</span>
                        </div>
                    </td>
                    <td><code class="slug-code"><?php echo e($tag->slug); ?></code></td>
                    <td>
                        <span class="color-swatch" style="background: <?php echo e($tag->color); ?>;"></span>
                    </td>
                    <td>
                        <span class="products-count-badge"><?php echo e($tag->products_count); ?></span>
                    </td>
                    <td>
                        <?php if($tag->is_active): ?>
                            <span class="status-badge status-active"><?php echo e(__('messages.active')); ?></span>
                        <?php else: ?>
                            <span class="status-badge status-inactive"><?php echo e(__('messages.inactive')); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('admin.tags.edit', $tag)); ?>" class="btn-action btn-edit" title="<?php echo e(__('messages.edit')); ?>">
                                <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                            </a>
                            <form action="<?php echo e(route('admin.tags.destroy', $tag)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_tag')); ?>')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-action btn-delete" title="<?php echo e(__('messages.delete')); ?>">
                                    <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <?php if($tags->hasPages()): ?>
    <div class="pagination-wrapper">
        <?php echo e($tags->links()); ?>

    </div>
    <?php endif; ?>
    <?php else: ?>
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-tags"></i>
        </div>
        <h3><?php echo e(__('messages.no_tags_found')); ?></h3>
        <p><?php echo e(__('messages.no_tags_description')); ?></p>
        <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_first_tag')); ?>

        </a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/tags/index.blade.php ENDPATH**/ ?>