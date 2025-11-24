<?php $__env->startSection('title', __('messages.brands_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Brands Page Specific Styles */
    .brands-header {
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

    .brands-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .brand-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .brand-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .brand-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 140px;
    }

    .brand-logo {
        max-width: 100%;
        max-height: 120px;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .brand-logo-placeholder {
        width: 100%;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: #94a3b8;
        font-size: 12px;
        border-radius: 8px;
    }

    .brand-card-body {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .brand-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .brand-website {
        font-size: 13px;
        color: var(--primary);
        margin-bottom: 12px;
        text-decoration: none;
        word-break: break-all;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .brand-website:hover {
        text-decoration: underline;
    }

    .brand-meta {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .brand-meta-badge {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .brand-status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .brand-status-inactive {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .brand-featured-yes {
        background: #fef3c7;
        color: #92400e;
    }

    .brand-featured-no {
        background: #f3f4f6;
        color: #4b5563;
    }

    .brand-card-footer {
        padding: 12px 16px;
        background: #f8fafc;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .brand-card-footer .btn {
        flex: 1;
        min-width: 80px;
        padding: 8px 12px;
        font-size: 13px;
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

    .view-toggle {
        display: flex;
        gap: 8px;
        background: white;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .view-toggle button {
        padding: 8px 12px;
        border: none;
        background: transparent;
        cursor: pointer;
        border-radius: 6px;
        color: var(--secondary);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .view-toggle button.active {
        background: var(--primary);
        color: white;
    }

    @media (max-width: 768px) {
        .brands-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }

        .search-filter-box {
            flex-direction: column;
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
        }

        .brands-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><?php echo e(__('messages.brands_management_title')); ?></h1>
        <p><?php echo e(__('messages.manage_brands_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <?php if($brands->count() > 0): ?>
            <button onclick="showDeleteAllModal()" class="btn btn-danger" style="margin-right: 10px;">
                <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_all')); ?>

            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('admin.brands.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_brand')); ?>

        </a>
    </div>
</div>

<!-- Stats Overview -->
<?php
    $totalBrands = $brands->total() ?? count($brands);
    $activeBrands = $brands->where('is_active', true)->count() ?? 0;
    $featuredBrands = $brands->where('is_featured', true)->count() ?? 0;
?>
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-tags"></i> <?php echo e(__('messages.total_brands_stat')); ?></h4>
        <div class="number"><?php echo e($totalBrands); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_brands')); ?></h4>
        <div class="number" style="color: var(--success);"><?php echo e($activeBrands); ?></div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--warning);">
        <h4><i class="fas fa-star"></i> <?php echo e(__('messages.featured_brands')); ?></h4>
        <div class="number" style="color: var(--warning);"><?php echo e($featuredBrands); ?></div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="<?php echo e(__('messages.search_brands')); ?>" onkeyup="filterBrands()">
    <select id="statusFilter" onchange="filterBrands()">
        <option value=""><?php echo e(__('messages.all_status')); ?></option>
        <option value="active"><?php echo e(__('messages.active')); ?></option>
        <option value="inactive"><?php echo e(__('messages.inactive')); ?></option>
    </select>
    <select id="featuredFilter" onchange="filterBrands()">
        <option value=""><?php echo e(__('messages.all_featured')); ?></option>
        <option value="yes"><?php echo e(__('messages.featured_only')); ?></option>
        <option value="no"><?php echo e(__('messages.not_featured')); ?></option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> <?php echo e(__('messages.reset')); ?>

    </button>
</div>

<!-- Brands Grid -->
<div class="brands-grid" id="brandsContainer">
    <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="brand-card" data-status="<?php echo e($brand->is_active ? 'active' : 'inactive'); ?>" data-featured="<?php echo e($brand->is_featured ? 'yes' : 'no'); ?>" data-name="<?php echo e($brand->name_en ?? $brand->name); ?>">
            <!-- Card Header with Logo -->
            <div class="brand-card-header">
                <?php if($brand->logo): ?>
                    <img src="<?php echo e($brand->logo); ?>" alt="<?php echo e($brand->name); ?>" class="brand-logo">
                <?php else: ?>
                    <div class="brand-logo-placeholder">
                        <i class="fas fa-image"></i> <?php echo e(__('messages.no_logo')); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Card Body -->
            <div class="brand-card-body">
                <div class="brand-name" title="<?php echo e($brand->name_en ?? $brand->name); ?>">
                    <?php echo e($brand->name_en ?? $brand->name); ?>

                </div>
                
                <?php if($brand->website): ?>
                    <a href="<?php echo e($brand->website); ?>" target="_blank" class="brand-website" title="<?php echo e($brand->website); ?>">
                        <i class="fas fa-globe"></i> <?php echo e($brand->website); ?>

                    </a>
                <?php endif; ?>

                <!-- Meta Badges -->
                <div class="brand-meta">
                    <span class="brand-meta-badge <?php echo e($brand->is_active ? 'brand-status-active' : 'brand-status-inactive'); ?>">
                        <i class="fas <?php echo e($brand->is_active ? 'fa-circle' : 'fa-circle'); ?>"></i>
                        <?php echo e($brand->is_active ? __('messages.active') : __('messages.inactive')); ?>

                    </span>
                    <span class="brand-meta-badge <?php echo e($brand->is_featured ? 'brand-featured-yes' : 'brand-featured-no'); ?>">
                        <i class="fas <?php echo e($brand->is_featured ? 'fa-star' : 'fa-star'); ?>"></i>
                        <?php echo e($brand->is_featured ? __('messages.featured') : __('messages.regular')); ?>

                    </span>
                </div>
            </div>

            <!-- Card Footer with Actions -->
            <div class="brand-card-footer">
                <a href="<?php echo e(route('admin.brands.edit', $brand)); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                </a>
                <form action="<?php echo e(route('admin.brands.destroy', $brand)); ?>" method="POST" style="flex: 1;" 
                      onsubmit="handleFormConfirm(event, {
                          message: '<?php echo e(__('messages.delete_brand_confirm')); ?>',
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
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state" style="grid-column: 1 / -1;">
            <i class="fas fa-box-open"></i>
            <h3><?php echo e(__('messages.no_brands_found')); ?></h3>
            <p><?php echo e(__('messages.no_brands_description')); ?></p>
            <a href="<?php echo e(route('admin.brands.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_first_brand')); ?>

            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if($brands->hasPages()): ?>
    <div style="margin-top: 24px;">
        <?php echo e($brands->links()); ?>

    </div>
<?php endif; ?>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.delete_all_brands')); ?>

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
    function filterBrands() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const featuredFilter = document.getElementById('featuredFilter').value;
        const cards = document.querySelectorAll('.brand-card');

        cards.forEach(card => {
            let matches = true;

            // Search filter
            if (searchTerm) {
                const name = card.getAttribute('data-name').toLowerCase();
                matches = matches && name.includes(searchTerm);
            }

            // Status filter
            if (statusFilter) {
                const status = card.getAttribute('data-status');
                matches = matches && status === statusFilter;
            }

            // Featured filter
            if (featuredFilter) {
                const featured = card.getAttribute('data-featured');
                matches = matches && featured === featuredFilter;
            }

            card.style.display = matches ? 'flex' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('featuredFilter').value = '';
        filterBrands();
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

        fetch('<?php echo e(route("admin.brands.delete-all")); ?>', {
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


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\brands\index.blade.php ENDPATH**/ ?>