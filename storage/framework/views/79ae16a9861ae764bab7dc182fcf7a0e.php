<?php $__env->startSection('title', __('messages.tags_management')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-tags"></i> <?php echo e(__('messages.tags_management')); ?></h1>
        <p><?php echo e(__('messages.manage_product_tags')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_tag')); ?>

        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if($tags->count() > 0): ?>
            <table class="data-table">
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
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <?php if($tag->icon): ?>
                                        <i class="<?php echo e($tag->icon); ?>" style="color: <?php echo e($tag->color); ?>"></i>
                                    <?php endif; ?>
                                    <span><?php echo e($tag->name_en); ?></span>
                                    <span style="color: #64748b; font-size: 12px;">(<?php echo e($tag->name_ar); ?>)</span>
                                </div>
                            </td>
                            <td><code><?php echo e($tag->slug); ?></code></td>
                            <td>
                                <span style="display: inline-block; width: 24px; height: 24px; background: <?php echo e($tag->color); ?>; border-radius: 4px;"></span>
                            </td>
                            <td><?php echo e($tag->products_count); ?></td>
                            <td>
                                <?php if($tag->is_active): ?>
                                    <span class="status-badge status-active"><?php echo e(__('messages.active')); ?></span>
                                <?php else: ?>
                                    <span class="status-badge status-inactive"><?php echo e(__('messages.inactive')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('admin.tags.edit', $tag)); ?>" class="btn btn-sm btn-secondary" title="<?php echo e(__('messages.edit')); ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.tags.destroy', $tag)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_tag')); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="<?php echo e(__('messages.delete')); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <?php echo e($tags->links()); ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <h3><?php echo e(__('messages.no_tags_found')); ?></h3>
                <p><?php echo e(__('messages.no_tags_description')); ?></p>
                <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?php echo e(__('messages.create_first_tag')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/tags/index.blade.php ENDPATH**/ ?>