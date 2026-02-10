<?php $__env->startSection('title', __('messages.employee_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .search-filter-box {
        display: flex;
        gap: 16px;
        margin-bottom: 28px;
        background: white;
        padding: 24px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        border: none;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-filter-box input,
    .search-filter-box select {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        min-width: 200px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #f8fafc;
    }

    .search-filter-box input:focus,
    .search-filter-box select:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .search-filter-box input::placeholder {
        color: #94a3b8;
    }

    .filter-reset-btn {
        padding: 12px 20px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border: 2px solid #cbd5e1;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        color: var(--dark);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-reset-btn:hover {
        background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
        border-color: #64748b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent-indigo) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-info-text {
        display: flex;
        flex-direction: column;
    }

    .employee-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 14px;
    }

    .employee-email {
        font-size: 12px;
        color: var(--secondary);
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f5f3ff;
        color: #6d28d9;
    }

    .status-toggle {
        padding: 0;
        background: none;
        border: none;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .search-filter-box {
            flex-direction: column;
            padding: 16px;
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
            width: 100%;
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
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <h1><?php echo e(__('messages.employee_management')); ?></h1>
                <p><?php echo e(__('messages.employee_management_subtitle')); ?></p>
            </div>
        </div>
        <div class="header-actions">
            <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-shield-alt"></i> <?php echo e(__('messages.manage_roles')); ?>

            </a>
            <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn-add">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_employee')); ?>

            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<?php
    $totalEmployees = $employees->total();
    $activeEmployees = $employees->where('status', 'active')->count();
    $inactiveEmployees = $employees->where('status', 'inactive')->count();
?>
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-users"></i> <?php echo e(__('messages.total_employees')); ?></h4>
        <div class="stat-value"><?php echo e($totalEmployees); ?></div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_employees')); ?></h4>
        <div class="stat-value"><?php echo e($activeEmployees); ?></div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-times-circle"></i> <?php echo e(__('messages.inactive_employees')); ?></h4>
        <div class="stat-value"><?php echo e($inactiveEmployees); ?></div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="<?php echo e(__('messages.search_employees')); ?>" onkeyup="filterTable()">
    <select id="statusFilter" onchange="filterTable()">
        <option value=""><?php echo e(__('messages.all_status')); ?></option>
        <option value="active"><?php echo e(__('messages.active')); ?></option>
        <option value="inactive"><?php echo e(__('messages.inactive')); ?></option>
    </select>
    <select id="roleFilter" onchange="filterTable()">
        <option value=""><?php echo e(__('messages.all_roles')); ?></option>
        <?php $availableRoles = \App\Models\EmployeeRole::where('is_active', true)->get(); ?>
        <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> <?php echo e(__('messages.reset')); ?>

    </button>
</div>

<!-- Employees Table -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-users"></i> <?php echo e(__('messages.employees_list')); ?></h3>
    </div>
    <div class="admin-table-body">
        <table class="admin-table" id="employeesTable">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.employee')); ?></th>
                    <th><?php echo e(__('messages.phone')); ?></th>
                    <th><?php echo e(__('messages.role')); ?></th>
                    <th><?php echo e(__('messages.status')); ?></th>
                    <th><?php echo e(__('messages.created_at')); ?></th>
                    <th><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-name="<?php echo e(strtolower($employee->name)); ?>" data-email="<?php echo e(strtolower($employee->email)); ?>" data-status="<?php echo e($employee->status); ?>" data-role="<?php echo e($employee->employee_role_id); ?>">
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    <?php echo e(strtoupper(substr($employee->name, 0, 1))); ?>

                                </div>
                                <div class="employee-info-text">
                                    <span class="employee-name"><?php echo e($employee->name); ?></span>
                                    <span class="employee-email"><?php echo e($employee->email); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($employee->phone ?? '—'); ?></td>
                        <td>
                            <?php if($employee->employeeRole): ?>
                                <span class="role-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    <?php echo e($employee->employeeRole->name); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?php echo e(__('messages.no_role')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?php echo e(route('admin.employees.toggle-status', $employee)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="status-toggle" title="<?php echo e(__('messages.toggle_status')); ?>">
                                    <span class="badge <?php echo e($employee->status === 'active' ? 'badge-success' : 'badge-danger'); ?>">
                                        <i class="fas <?php echo e($employee->status === 'active' ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                                        <?php echo e($employee->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                    </span>
                                </button>
                            </form>
                        </td>
                        <td><?php echo e($employee->created_at->format('M d, Y')); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo e(route('admin.employees.edit', $employee)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                                </a>
                                <form action="<?php echo e(route('admin.employees.destroy', $employee)); ?>" method="POST"
                                      onsubmit="handleFormConfirm(event, {
                                          message: '<?php echo e(__('messages.confirm_delete_employee')); ?>',
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6">
                            <div class="admin-empty-state">
                                <div class="admin-empty-state-icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <h3><?php echo e(__('messages.no_employees_found')); ?></h3>
                                <p><?php echo e(__('messages.no_employees_description')); ?></p>
                                <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_first_employee')); ?>

                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($employees->hasPages()): ?>
    <div style="margin-top: 24px;">
        <?php echo e($employees->links()); ?>

    </div>
<?php endif; ?>

<script>
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const roleFilter = document.getElementById('roleFilter').value;
        const rows = document.querySelectorAll('#employeesTable tbody tr[data-name]');

        rows.forEach(row => {
            let matches = true;

            if (searchTerm) {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                matches = matches && (name.includes(searchTerm) || email.includes(searchTerm));
            }

            if (statusFilter) {
                matches = matches && row.getAttribute('data-status') === statusFilter;
            }

            if (roleFilter) {
                matches = matches && row.getAttribute('data-role') === roleFilter;
            }

            row.style.display = matches ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('roleFilter').value = '';
        filterTable();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>