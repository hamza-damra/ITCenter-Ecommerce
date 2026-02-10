<?php $__env->startSection('title', __('messages.role_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .role-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        border: none;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .role-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card-hover);
    }

    .role-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px;
        border-bottom: 1px solid var(--border);
    }

    .role-card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .role-card-header h3 i {
        color: var(--primary);
    }

    .role-card-header p {
        font-size: 13px;
        color: var(--secondary);
        margin: 0;
        line-height: 1.5;
    }

    .role-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .role-meta {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .role-meta-badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .role-status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .role-status-inactive {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .role-employees-badge {
        background: #eff6ff;
        color: #1e40af;
    }

    .role-permissions-count {
        background: #f5f3ff;
        color: #6d28d9;
    }

    .role-permissions-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .perm-tag {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 500;
    }

    .perm-tag-more {
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
    }

    .role-card-footer {
        padding: 12px 20px;
        background: #f8fafc;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .role-card-footer .btn {
        flex: 1;
        min-width: 80px;
        padding: 8px 12px;
        font-size: 13px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--accent-emerald) 0%, #059669 100%);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
        background: linear-gradient(135deg, #059669 0%, var(--accent-emerald) 100%);
    }

    @media (max-width: 768px) {
        .roles-grid {
            grid-template-columns: 1fr;
        }

        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .header-actions .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h1><?php echo e(__('messages.role_management')); ?></h1>
                <p><?php echo e(__('messages.role_management_subtitle')); ?></p>
            </div>
        </div>
        <div class="header-actions">
            <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn-add">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_role')); ?>

            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<?php
    $totalRoles = $roles->total();
    $activeRoles = $roles->where('is_active', true)->count();
    $totalEmployees = \App\Models\User::where('role', 'employee')->count();
?>
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-shield-alt"></i> <?php echo e(__('messages.total_roles')); ?></h4>
        <div class="stat-value"><?php echo e($totalRoles); ?></div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_roles')); ?></h4>
        <div class="stat-value"><?php echo e($activeRoles); ?></div>
    </div>
    <div class="admin-stat-card stat-violet">
        <h4><i class="fas fa-users"></i> <?php echo e(__('messages.total_employees')); ?></h4>
        <div class="stat-value"><?php echo e($totalEmployees); ?></div>
    </div>
</div>

<!-- Roles Grid -->
<div class="roles-grid">
    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="role-card">
            <div class="role-card-header">
                <h3>
                    <i class="fas fa-shield-alt"></i>
                    <?php echo e($role->name); ?>

                </h3>
                <?php if($role->description): ?>
                    <p><?php echo e(\Illuminate\Support\Str::limit($role->description, 80)); ?></p>
                <?php endif; ?>
            </div>

            <div class="role-card-body">
                <div class="role-meta">
                    <span class="role-meta-badge <?php echo e($role->is_active ? 'role-status-active' : 'role-status-inactive'); ?>">
                        <i class="fas <?php echo e($role->is_active ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                        <?php echo e($role->is_active ? __('messages.active') : __('messages.inactive')); ?>

                    </span>
                    <span class="role-meta-badge role-employees-badge">
                        <i class="fas fa-users"></i>
                        <?php echo e($role->employees_count); ?> <?php echo e(__('messages.employees_label')); ?>

                    </span>
                    <span class="role-meta-badge role-permissions-count">
                        <i class="fas fa-key"></i>
                        <?php echo e(count($role->permissions ?? [])); ?> <?php echo e(__('messages.permissions_label')); ?>

                    </span>
                </div>

                <div class="role-permissions-preview">
                    <?php
                        $perms = $role->permissions ?? [];
                        $shown = array_slice($perms, 0, 5);
                        $remaining = count($perms) - count($shown);
                    ?>
                    <?php $__currentLoopData = $shown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="perm-tag"><?php echo e($perm); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($remaining > 0): ?>
                        <span class="perm-tag perm-tag-more">+<?php echo e($remaining); ?> <?php echo e(__('messages.more')); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="role-card-footer">
                <a href="<?php echo e(route('admin.roles.edit', $role)); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                </a>
                <?php if($role->employees_count === 0): ?>
                    <form action="<?php echo e(route('admin.roles.destroy', $role)); ?>" method="POST" style="flex: 1;"
                          onsubmit="handleFormConfirm(event, {
                              message: '<?php echo e(__('messages.confirm_delete_role')); ?>',
                              confirmText: '<?php echo e(__('messages.yes_delete')); ?>',
                              type: 'danger',
                              confirmButtonType: 'danger'
                          })">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled title="<?php echo e(__('messages.role_has_employees')); ?>" style="flex: 1; opacity: 0.6; cursor: not-allowed;">
                        <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="admin-empty-state" style="grid-column: 1 / -1;">
            <div class="admin-empty-state-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3><?php echo e(__('messages.no_roles_found')); ?></h3>
            <p><?php echo e(__('messages.no_roles_description')); ?></p>
            <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_first_role')); ?>

            </a>
        </div>
    <?php endif; ?>
</div>

<?php if($roles->hasPages()): ?>
    <div style="margin-top: 24px;">
        <?php echo e($roles->links()); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>