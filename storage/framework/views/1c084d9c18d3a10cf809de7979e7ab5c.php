<?php $__env->startSection('title', __('messages.categories_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Categories Page Specific Styles */
    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .search-filter-box {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        background: white;
        padding: 16px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .search-filter-box input,
    .search-filter-box select {
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        min-width: 200px;
    }

    .search-filter-box input:focus,
    .search-filter-box select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .filter-reset-btn {
        padding: 10px 16px;
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary);
        transition: all 0.3s ease;
    }

    .filter-reset-btn:hover {
        background: var(--light);
        border-color: var(--secondary);
    }

    .categories-table-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .categories-table {
        width: 100%;
        border-collapse: collapse;
    }

    .categories-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid var(--border);
    }

    .categories-table th {
        padding: 16px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .categories-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .categories-table tbody tr:hover {
        background: #f8fafc;
    }

    .categories-table tbody tr:last-child {
        border-bottom: none;
    }

    .categories-table td {
        padding: 16px;
        color: var(--dark);
    }

    .category-image-cell {
        display: flex;
        align-items: center;
    }

    .category-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .category-image-placeholder {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: #94a3b8;
        border-radius: 8px;
        font-size: 24px;
    }

    .category-name-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .category-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 15px;
    }

    .category-slug {
        font-size: 13px;
        color: var(--secondary);
        font-family: 'Courier New', monospace;
    }

    .category-parent {
        font-size: 13px;
        color: var(--secondary);
    }

    .category-parent-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #3730a3;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .category-parent-root {
        display: inline-block;
        background: #f3f4f6;
        color: #6b7280;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .action-cell {
        display: flex;
        gap: 8px;
    }

    .action-cell .btn {
        padding: 6px 12px;
        font-size: 12px;
        flex-shrink: 0;
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

    .stat-mini-card .number {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
    }

    .breadcrumb-hierarchy {
        display: flex;
        gap: 8px;
        font-size: 13px;
        color: var(--secondary);
        align-items: center;
    }

    .breadcrumb-hierarchy .icon {
        color: #cbd5e1;
    }

    .pagination-wrapper {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .search-filter-box {
            flex-direction: column;
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
            width: 100%;
        }

        .categories-table {
            font-size: 13px;
        }

        .categories-table td,
        .categories-table th {
            padding: 12px;
        }

        .category-image {
            width: 50px;
            height: 50px;
        }

        .category-image-placeholder {
            width: 50px;
            height: 50px;
        }

        .action-cell {
            flex-direction: column;
        }

        .action-cell .btn {
            width: 100%;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><?php echo e(__('messages.categories_management_title')); ?></h1>
        <p><?php echo e(__('messages.organize_categories_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <?php if($categories->count() > 0): ?>
            <button id="bulkDeleteBtn" onclick="showBulkDeleteModal()" class="btn btn-danger" style="margin-right: 10px; display: none;">
                <i class="fas fa-trash-alt"></i> <span id="bulkDeleteText"><?php echo e(__('messages.delete_selected')); ?></span>
            </button>
            <button onclick="showDeleteAllModal()" class="btn btn-danger" style="margin-right: 10px;">
                <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_all')); ?>

            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_category')); ?>

        </a>
    </div>
</div>

<!-- Stats Overview -->
<?php
    $totalCategories = $categories->total() ?? count($categories);
    $activeCategories = $categories->where('is_active', true)->count() ?? 0;
    $rootCategories = $categories->where('parent_id', null)->count() ?? 0;
?>
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-folder"></i> <?php echo e(__('messages.total_categories_stat')); ?></h4>
        <div class="number"><?php echo e($totalCategories); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_categories')); ?></h4>
        <div class="number" style="color: var(--success);"><?php echo e($activeCategories); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--primary-light);">
        <h4><i class="fas fa-sitemap"></i> <?php echo e(__('messages.root_categories')); ?></h4>
        <div class="number" style="color: var(--primary-light);"><?php echo e($rootCategories); ?></div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="<?php echo e(__('messages.search_categories')); ?>" onkeyup="filterCategories()">
    <select id="statusFilter" onchange="filterCategories()">
        <option value=""><?php echo e(__('messages.all_status')); ?></option>
        <option value="active"><?php echo e(__('messages.active_only')); ?></option>
        <option value="inactive"><?php echo e(__('messages.inactive_only')); ?></option>
    </select>
    <select id="parentFilter" onchange="filterCategories()">
        <option value=""><?php echo e(__('messages.all_categories_filter')); ?></option>
        <option value="root"><?php echo e(__('messages.root_only')); ?></option>
        <option value="subcategory"><?php echo e(__('messages.subcategories_only')); ?></option>
    </select>
    <select id="displayModeFilter" onchange="filterCategories()">
        <option value=""><?php echo e(__('messages.all_display_modes')); ?></option>
        <option value="carousel"><?php echo e(__('messages.carousel')); ?></option>
        <option value="nav"><?php echo e(__('messages.nav_bar')); ?></option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> <?php echo e(__('messages.reset_filters')); ?>

    </button>
</div>

<!-- Categories Table -->
<?php if($categories->count() > 0): ?>
    <div class="categories-table-wrapper">
        <table class="categories-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="cursor: pointer;">
                    </th>
                    <th><?php echo e(__('messages.image')); ?></th>
                    <th><?php echo e(__('messages.category_name')); ?></th>
                    <th><?php echo e(__('messages.parent_category')); ?></th>
                    <th><?php echo e(__('messages.display_mode')); ?></th>
                    <th><?php echo e(__('messages.status')); ?></th>
                    <th style="text-align: right;"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-status="<?php echo e($category->is_active ? 'active' : 'inactive'); ?>"
                    data-parent="<?php echo e($category->parent_id ? 'subcategory' : 'root'); ?>"
                    data-display-mode="<?php echo e($category->display_mode ?? 'carousel'); ?>"
                    data-name="<?php echo e($category->name_en ?? $category->name); ?><?php echo e($category->slug ?? ''); ?>">

                    <td style="text-align: center;">
                        <input type="checkbox" class="category-checkbox" value="<?php echo e($category->id); ?>" onchange="updateBulkDeleteButton()" style="cursor: pointer;">
                    </td>

                    <td class="category-image-cell">
                        <?php if($category->icon): ?>
                            <div class="category-image-placeholder" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <i class="<?php echo e($category->icon); ?>"></i>
                            </div>
                        <?php elseif($category->image): ?>
                            <img src="<?php echo e($category->image); ?>" alt="<?php echo e($category->name); ?>" class="category-image">
                        <?php else: ?>
                            <div class="category-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div class="category-name-cell">
                            <div class="category-name"><?php echo e($category->name_en ?? $category->name); ?></div>
                            <?php if($category->slug): ?>
                                <div class="category-slug"><?php echo e($category->slug); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>

                    <td>
                        <?php if($category->parent): ?>
                            <span class="category-parent-badge">
                                <i class="fas fa-arrow-right"></i> <?php echo e($category->parent->name_en ?? $category->parent->name); ?>

                            </span>
                        <?php else: ?>
                            <span class="category-parent-root">
                                <i class="fas fa-folder-open"></i> <?php echo e(__('messages.root_category')); ?>

                            </span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if(!$category->parent_id): ?>
                            <?php if($category->display_mode === 'nav'): ?>
                                <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
                                    <i class="fas fa-bars"></i> <?php echo e(__('messages.nav_bar')); ?>

                                </span>
                            <?php else: ?>
                                <span class="status-badge" style="background: #fef3c7; color: #92400e;">
                                    <i class="fas fa-images"></i> <?php echo e(__('messages.carousel')); ?>

                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="status-badge" style="background: #f3f4f6; color: #6b7280;">
                                <i class="fas fa-level-down-alt"></i> <?php echo e(__('messages.inherits_parent')); ?>

                            </span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="status-badge <?php echo e($category->is_active ? 'status-active' : 'status-inactive'); ?>">
                            <i class="fas <?php echo e($category->is_active ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                            <?php echo e($category->is_active ? __('messages.active') : __('messages.inactive')); ?>

                        </span>
                    </td>

                    <td class="action-cell" style="text-align: right;">
                        <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <a href="<?php echo e(route('admin.categories.attributes.edit', $category)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-filter"></i> <?php echo e(__('messages.attributes')); ?>

                        </a>
                        <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" style="display: inline;" 
                              onsubmit="handleFormConfirm(event, {
                                  message: '<?php echo e(__('messages.delete_category_confirm')); ?>',
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
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($categories->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($categories->links()); ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h3><?php echo e(__('messages.no_categories_found')); ?></h3>
        <p><?php echo e(__('messages.no_categories_description')); ?></p>
        <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_first_category')); ?>

        </a>
    </div>
<?php endif; ?>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.delete_all_categories')); ?>

        </h3>
        <p style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            <?php echo e(__('messages.confirm_delete_all')); ?>

        </p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideDeleteAllModal()" class="btn" style="background: #e5e7eb; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

            </button>
            <button onclick="deleteAllRecords()" class="btn btn-danger" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.yes_delete')); ?>

            </button>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.delete_selected_categories')); ?>

        </h3>
        <p style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            <?php echo e(__('messages.confirm_delete_selected')); ?>

        </p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideBulkDeleteModal()" class="btn" style="background: #e5e7eb; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

            </button>
            <button onclick="bulkDeleteRecords()" class="btn btn-danger" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.yes_delete')); ?>

            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #10b981; font-size: 24px;">
            <i class="fas fa-check-circle"></i> <?php echo e(__('messages.success')); ?>

        </h3>
        <p id="successMessage" style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            <?php echo e(__('messages.all_records_deleted_successfully')); ?>

        </p>
        <div style="display: flex; justify-content: flex-end;">
            <button onclick="window.location.reload()" class="btn btn-success" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-check"></i> <?php echo e(__('messages.OK')); ?>

            </button>
        </div>
    </div>
</div>

<script>
    function filterCategories() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const parentFilter = document.getElementById('parentFilter').value;
        const displayModeFilter = document.getElementById('displayModeFilter').value;
        const rows = document.querySelectorAll('.categories-table tbody tr');

        rows.forEach(row => {
            let matches = true;

            // Search filter
            if (searchTerm) {
                const name = row.getAttribute('data-name').toLowerCase();
                matches = matches && name.includes(searchTerm);
            }

            // Status filter
            if (statusFilter) {
                const status = row.getAttribute('data-status');
                matches = matches && status === statusFilter;
            }

            // Parent filter
            if (parentFilter) {
                const parent = row.getAttribute('data-parent');
                matches = matches && parent === parentFilter;
            }

            // Display mode filter
            if (displayModeFilter) {
                const displayMode = row.getAttribute('data-display-mode');
                matches = matches && displayMode === displayModeFilter;
            }

            row.style.display = matches ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('parentFilter').value = '';
        document.getElementById('displayModeFilter').value = '';
        filterCategories();
    }

    function showDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'flex';
    }

    function hideDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'none';
    }

    function deleteAllRecords() {
        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.deleting_all_records")); ?>';

        fetch('<?php echo e(route("admin.categories.delete-all")); ?>', {
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

    // Bulk selection functions
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.category-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });

        updateBulkDeleteButton();
    }

    function updateBulkDeleteButton() {
        const checkboxes = document.querySelectorAll('.category-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDeleteText = document.getElementById('bulkDeleteText');
        const selectAllCheckbox = document.getElementById('selectAll');

        if (checkboxes.length > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
            bulkDeleteText.textContent = '<?php echo e(__("messages.delete_selected")); ?> (' + checkboxes.length + ')';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }

        // Update "Select All" checkbox state
        const allCheckboxes = document.querySelectorAll('.category-checkbox');
        const allChecked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
        const someChecked = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;

        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked;
    }

    function showBulkDeleteModal() {
        const checkboxes = document.querySelectorAll('.category-checkbox:checked');
        if (checkboxes.length === 0) {
            alert('<?php echo e(__("messages.please_select_items")); ?>');
            return;
        }
        document.getElementById('bulkDeleteModal').style.display = 'flex';
    }

    function hideBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').style.display = 'none';
    }

    function bulkDeleteRecords() {
        const checkboxes = document.querySelectorAll('.category-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length === 0) {
            alert('<?php echo e(__("messages.please_select_items")); ?>');
            return;
        }

        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.deleting")); ?>...';

        fetch('<?php echo e(route("admin.categories.bulk-delete")); ?>', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            hideBulkDeleteModal();
            if (data.success) {
                document.getElementById('successMessage').textContent = data.message;
                document.getElementById('successModal').style.display = 'flex';
            } else {
                alert('Error: ' + data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            hideBulkDeleteModal();
            alert('Error: ' + error.message);
            window.location.reload();
        });
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>