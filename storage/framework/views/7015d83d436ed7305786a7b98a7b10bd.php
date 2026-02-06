<?php $__env->startSection('title', __('messages.specification_templates')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .template-stats {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }
    .template-stat {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .field-count-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .category-badge {
        background: #f0fdf4;
        color: #166534;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-clipboard-list"></i> <?php echo e(__('messages.specification_templates')); ?></h1>
        <p><?php echo e(__('messages.manage_category_spec_templates')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.spec-templates.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.create_template')); ?>

        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <?php if($templates->count() > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.template_name')); ?></th>
                        <th><?php echo e(__('messages.category')); ?></th>
                        <th><?php echo e(__('messages.fields')); ?></th>
                        <th><?php echo e(__('messages.status')); ?></th>
                        <th><?php echo e(__('messages.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo e($template->name_en); ?></strong>
                                    <?php if($template->name_ar): ?>
                                        <div style="color: #64748b; font-size: 12px;"><?php echo e($template->name_ar); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">
                                    <i class="fas fa-folder"></i>
                                    <?php echo e($template->category?->name ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td>
                                <span class="field-count-badge">
                                    <i class="fas fa-list"></i>
                                    <?php echo e($template->fields->count()); ?> <?php echo e(__('messages.fields')); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($template->is_active): ?>
                                    <span class="status-badge" style="background: #dcfce7; color: #166534;">
                                        <i class="fas fa-check-circle"></i> <?php echo e(__('messages.active')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="status-badge" style="background: #fee2e2; color: #991b1b;">
                                        <i class="fas fa-times-circle"></i> <?php echo e(__('messages.inactive')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('admin.spec-templates.edit', $template)); ?>" 
                                       class="btn btn-sm btn-secondary" 
                                       title="<?php echo e(__('messages.edit')); ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.spec-templates.destroy', $template)); ?>" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_template')); ?>')">
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
                <?php echo e($templates->links()); ?>

            </div>
        <?php else: ?>
            <div class="admin-empty-state">
                <div class="admin-empty-state-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3><?php echo e(__('messages.no_templates_found')); ?></h3>
                <p><?php echo e(__('messages.create_first_template')); ?></p>
                <a href="<?php echo e(route('admin.spec-templates.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?php echo e(__('messages.create_template')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/spec-templates/index.blade.php ENDPATH**/ ?>