<?php $__env->startSection('title', __('messages.brands_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Brands Page Specific Styles - Extending unified components */
    
    /* Search & Filter Box */
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
        transform: translateY(-1px);
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

    /* Brands Grid */
    .brands-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    /* Brand Card Styles */
    .brand-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        border: none;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .brand-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card-hover);
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
        background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
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

    /* Header Actions */
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

    @media (max-width: 1024px) {
        .brands-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .brands-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
        }

        .brand-card {
            padding: 1rem;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
        }

        .brand-name {
            font-size: 0.9rem;
        }

        .brand-count {
            font-size: 0.8rem;
        }

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

        .header-actions .btn,
        .header-actions .btn-add {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .brands-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.625rem;
        }

        .brand-card {
            padding: 0.875rem;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
        }

        .brand-name {
            font-size: 0.8rem;
        }

        .brand-actions .btn {
            padding: 6px 8px;
            font-size: 10px;
        }

        .search-filter-box {
            padding: 12px;
        }
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-award"></i>
            </div>
            <div>
                <h1><?php echo e(__('messages.brands_management_title')); ?></h1>
                <p><?php echo e(__('messages.manage_brands_subtitle')); ?></p>
            </div>
        </div>
        <div class="header-actions">
            <?php if($brands->count() > 0): ?>
                <button onclick="showDeleteAllModal()" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_all')); ?>

                </button>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.brands.create')); ?>" class="btn-add">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_brand')); ?>

            </a>
        </div>
    </div>
</div>

<!-- Stats Overview - Using unified admin-stats-grid component -->
<?php
    $totalBrands = $brands->total() ?? count($brands);
    $activeBrands = $brands->where('is_active', true)->count() ?? 0;
    $featuredBrands = $brands->where('is_featured', true)->count() ?? 0;
?>
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-tags"></i> <?php echo e(__('messages.total_brands_stat')); ?></h4>
        <div class="stat-value"><?php echo e($totalBrands); ?></div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active_brands')); ?></h4>
        <div class="stat-value"><?php echo e($activeBrands); ?></div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-star"></i> <?php echo e(__('messages.featured_brands')); ?></h4>
        <div class="stat-value"><?php echo e($featuredBrands); ?></div>
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
        <!-- Empty State - Using unified admin-empty-state component -->
        <div class="admin-empty-state" style="grid-column: 1 / -1;">
            <div class="admin-empty-state-icon">
                <i class="fas fa-award"></i>
            </div>
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


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/brands/index.blade.php ENDPATH**/ ?>