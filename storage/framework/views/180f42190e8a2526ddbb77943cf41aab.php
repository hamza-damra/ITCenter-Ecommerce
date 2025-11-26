

<?php $__env->startSection('title', 'Attributes Management'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .attributes-table {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .attributes-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .attributes-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid var(--border);
    }

    .attributes-table th {
        padding: 16px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .attributes-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        color: var(--secondary);
    }

    .attributes-table tbody tr:hover {
        background: #f8fafc;
    }

    .attribute-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .attribute-slug {
        font-size: 12px;
        color: var(--secondary);
        font-family: monospace;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .attribute-type-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .type-select {
        background: #dbeafe;
        color: #1e40af;
    }

    .type-multi_select {
        background: #e0e7ff;
        color: #4338ca;
    }

    .type-range {
        background: #fce7f3;
        color: #9f1239;
    }

    .type-color {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .filterable-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .filterable-yes {
        background: #d1fae5;
        color: #065f46;
    }

    .filterable-no {
        background: #f3f4f6;
        color: #6b7280;
    }

    .values-count {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: #f1f5f9;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-buttons .btn {
        padding: 6px 12px;
        font-size: 12px;
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
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1>Attributes Management</h1>
        <p>Manage product attributes and their values for filtering</p>
    </div>
    <div class="page-actions">
        <?php if($attributes->count() > 0): ?>
            <button onclick="showDeleteAllModal()" class="btn btn-danger" style="margin-right: 10px;">
                <i class="fas fa-trash-alt"></i> Delete All
            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('admin.attributes.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Add New Attribute
        </a>
    </div>
</div>

<!-- Attributes Table -->
<?php if($attributes->count() > 0): ?>
    <div class="attributes-table">
        <table>
            <thead>
                <tr>
                    <th>Attribute Name</th>
                    <th>Slug</th>
                    <th>Type</th>
                    <th>Unit</th>
                    <th>Values</th>
                    <th>Filterable</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="attribute-name"><?php echo e($attribute->name_en); ?></div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                AR: <?php echo e($attribute->name_ar); ?> | HE: <?php echo e($attribute->name_he); ?>

                            </div>
                        </td>
                        <td>
                            <span class="attribute-slug"><?php echo e($attribute->slug); ?></span>
                        </td>
                        <td>
                            <span class="attribute-type-badge type-<?php echo e($attribute->type); ?>">
                                <?php echo e(str_replace('_', ' ', $attribute->type)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($attribute->unit): ?>
                                <span style="font-family: monospace; color: var(--secondary);"><?php echo e($attribute->unit); ?></span>
                            <?php else: ?>
                                <span style="color: #cbd5e1;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.attribute-values.index', $attribute)); ?>" class="values-count" style="text-decoration: none; cursor: pointer;">
                                <i class="fas fa-list"></i>
                                <?php echo e($attribute->values->count()); ?> values
                            </a>
                        </td>
                        <td>
                            <span class="filterable-badge <?php echo e($attribute->is_filterable ? 'filterable-yes' : 'filterable-no'); ?>">
                                <i class="fas <?php echo e($attribute->is_filterable ? 'fa-check' : 'fa-times'); ?>"></i>
                                <?php echo e($attribute->is_filterable ? 'Yes' : 'No'); ?>

                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: var(--secondary);"><?php echo e($attribute->order); ?></span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo e($attribute->is_active ? 'status-active' : 'status-inactive'); ?>">
                                <i class="fas fa-circle" style="font-size: 6px;"></i>
                                <?php echo e($attribute->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo e(route('admin.attribute-values.index', $attribute)); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-list"></i> Values
                                </a>
                                <a href="<?php echo e(route('admin.attributes.edit', $attribute)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo e(route('admin.attributes.destroy', $attribute)); ?>" method="POST" 
                                      onsubmit="handleFormConfirm(event, {
                                          message: 'Are you sure you want to delete this attribute? This will also delete all its values and associations.',
                                          confirmText: 'Yes, Delete',
                                          type: 'danger',
                                          confirmButtonType: 'danger'
                                      })">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($attributes->hasPages()): ?>
        <div style="margin-top: 24px;">
            <?php echo e($attributes->links()); ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-tags"></i>
        <h3>No Attributes Found</h3>
        <p>Start by creating your first product attribute for filtering</p>
        <a href="<?php echo e(route('admin.attributes.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Create First Attribute
        </a>
    </div>
<?php endif; ?>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> Delete All Attributes
        </h3>
        <p style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            Are you sure you want to delete all attributes? This action cannot be undone and will remove all attribute values and associations.
        </p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideDeleteAllModal()" class="btn" style="background: #e5e7eb; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button onclick="deleteAllRecords()" class="btn btn-danger" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-trash-alt"></i> Yes, Delete All
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #10b981; font-size: 24px;">
            <i class="fas fa-check-circle"></i> Success
        </h3>
        <p id="successMessage" style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            All records deleted successfully
        </p>
        <div style="display: flex; justify-content: flex-end;">
            <button onclick="window.location.reload()" class="btn btn-success" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-check"></i> OK
            </button>
        </div>
    </div>
</div>

<script>
    function showDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'flex';
    }

    function hideDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'none';
    }

    function deleteAllRecords() {
        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

        fetch('<?php echo e(route("admin.attributes.delete-all")); ?>', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideDeleteAllModal();
            if (data.success) {
                document.getElementById('successMessage').textContent = data.message;
                document.getElementById('successModal').style.display = 'flex';
            } else {
                alert('Error: ' + data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            hideDeleteAllModal();
            alert('Error: ' + error.message);
            window.location.reload();
        });
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\attributes\index.blade.php ENDPATH**/ ?>