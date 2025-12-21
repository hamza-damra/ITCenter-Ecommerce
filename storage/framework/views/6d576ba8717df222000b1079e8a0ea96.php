<?php $__env->startSection('title', __('messages.products_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Products Page Specific Styles - Extending unified components */
    
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

    /* Product Image Styles */
    .product-image-cell {
        display: flex;
        align-items: center;
    }

    .product-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .product-image-placeholder {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
        color: #94a3b8;
        border-radius: 12px;
        font-size: 28px;
    }

    /* Product Name Cell */
    .product-name-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .product-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 16px;
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-sku {
        font-size: 13px;
        color: var(--secondary);
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        width: fit-content;
    }

    /* Category & Brand Badges */
    .product-category-badge {
        display: inline-block;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
    }

    .product-brand-badge {
        display: inline-block;
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        color: #6b21a8;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
    }

    /* Price Cell */
    .product-price-cell {
        font-weight: 700;
        color: var(--success);
        font-size: 17px;
    }

    .product-sale-price {
        font-size: 13px;
        color: var(--danger);
        text-decoration: line-through;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    [dir="rtl"] .stock-badge {
        text-transform: none;
        letter-spacing: normal;
    }

    .stock-high {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .stock-medium {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .stock-low {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #7f1d1d;
    }

    /* Images Badge */
    .images-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 8px;
        background: transparent;
        color: var(--dark);
        font-size: 12px;
        font-weight: 700;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    [dir="rtl"] .status-badge {
        text-transform: none;
        letter-spacing: normal;
    }

    .status-active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-inactive {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #7f1d1d;
    }

    /* Action Cell */
    .action-cell {
        display: flex;
        gap: 10px;
    }

    .action-cell .btn {
        padding: 8px 16px;
        font-size: 13px;
        flex-shrink: 0;
    }

    /* Hero Add Button */
    .admin-hero .btn-add {
        background: white;
        color: var(--accent-blue);
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .admin-hero .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 28px;
        display: flex;
        justify-content: center;
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
        .search-filter-box {
            flex-direction: column;
            padding: 20px;
            gap: 12px;
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
            width: 100%;
        }

        .admin-stat-card .stat-value {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 768px) {
        .search-filter-box {
            padding: 16px;
            margin-bottom: 20px;
        }

        .search-filter-box input,
        .search-filter-box select {
            padding: 10px 14px;
            font-size: 13px;
        }

        .filter-reset-btn {
            width: 100%;
            justify-content: center;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -16px;
            padding: 0 16px;
        }

        .admin-table {
            font-size: 12px;
            min-width: 800px;
        }

        .admin-table td,
        .admin-table th {
            padding: 10px 8px;
            white-space: nowrap;
        }

        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
        }

        .product-image-placeholder {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .product-name {
            max-width: 150px;
            font-size: 13px;
        }

        .product-sku {
            font-size: 11px;
        }

        .product-category-badge,
        .product-brand-badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .action-cell {
            flex-direction: row;
            gap: 4px;
        }

        .action-cell .btn {
            padding: 6px 8px;
            font-size: 11px;
        }

        .admin-stat-card .stat-value {
            font-size: 1.5rem;
        }

        .admin-stat-card .stat-label {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .search-filter-box {
            padding: 12px;
        }

        .search-filter-box input,
        .search-filter-box select {
            padding: 10px 12px;
            font-size: 12px;
        }

        .admin-table {
            font-size: 11px;
        }

        .admin-table td,
        .admin-table th {
            padding: 8px 6px;
        }

        .product-image {
            width: 40px;
            height: 40px;
        }

        .product-image-placeholder {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .product-name {
            max-width: 120px;
            font-size: 12px;
        }

        .action-cell .btn {
            padding: 5px 6px;
            font-size: 10px;
        }

        .action-cell .btn i {
            font-size: 10px;
        }
    }

    /* RTL Support */
    [dir="rtl"] .admin-table th,
    [dir="rtl"] .admin-table td {
        text-align: right;
    }

    [dir="rtl"] .admin-table th:last-child,
    [dir="rtl"] .admin-table td:last-child {
        text-align: left;
    }

    [dir="rtl"] .action-cell {
        justify-content: flex-start;
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <div>
                <h1>
                    <?php echo e(__('messages.products_management')); ?>

                    <?php if(request('filter')): ?>
                        <?php if(request('filter') == 'recent'): ?>
                            <span style="font-size: 0.6em; color: var(--accent-blue); font-weight: 600; margin-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 12px;">
                                (<?php echo e(__('messages.recent_products')); ?>)
                            </span>
                        <?php elseif(request('filter') == 'top_rated'): ?>
                            <span style="font-size: 0.6em; color: var(--accent-amber); font-weight: 600; margin-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 12px;">
                                (<?php echo e(__('messages.top_rated_products')); ?>)
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </h1>
                <p><?php echo e(__('messages.manage_product_catalog')); ?></p>
            </div>
        </div>
        <div class="header-actions">
            <?php if($products->count() > 0): ?>
                <button id="bulkDeleteBtn" onclick="showBulkDeleteModal()" class="btn btn-danger" style="display: none;">
                    <i class="fas fa-trash-alt"></i> <span id="bulkDeleteText"><?php echo e(__('messages.delete_selected')); ?></span>
                </button>
                <button onclick="showDeleteAllModal()" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_all')); ?>

                </button>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn-add">
                <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_product')); ?>

            </a>
        </div>
    </div>
</div>

<!-- Stats Overview - Using unified admin-stats-grid component -->
<?php
    $totalProducts = $products->total() ?? count($products);
    $activeProducts = $products->where('is_active', true)->count() ?? 0;
    $featuredProducts = $products->where('is_featured', true)->count() ?? 0;
    $lowStockProducts = $products->where('stock_quantity', '<', 5)->count() ?? 0;
?>
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-boxes"></i> <?php echo e(__('messages.total_products')); ?></h4>
        <div class="stat-value"><?php echo e($totalProducts); ?></div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active')); ?></h4>
        <div class="stat-value"><?php echo e($activeProducts); ?></div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-star"></i> <?php echo e(__('messages.featured_products_count')); ?></h4>
        <div class="stat-value"><?php echo e($featuredProducts); ?></div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.low_stock')); ?></h4>
        <div class="stat-value"><?php echo e($lowStockProducts); ?></div>
    </div>
</div>

<!-- Search & Filter -->
<form method="GET" action="<?php echo e(route('admin.products.index')); ?>" id="searchFilterForm">
    <div class="search-filter-box">
        <input type="text" name="search" id="searchInput" placeholder="🔍 <?php echo e(__('messages.search_by_name_sku')); ?>" 
               value="<?php echo e(request('search')); ?>" oninput="debounceSearch()">
        <select name="status" id="statusFilter" onchange="filterProducts()">
            <option value=""><?php echo e(__('messages.all_status')); ?></option>
            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>><?php echo e(__('messages.active_only')); ?></option>
            <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>><?php echo e(__('messages.inactive_only')); ?></option>
        </select>
        <select name="stock" id="stockFilter" onchange="filterProducts()">
            <option value=""><?php echo e(__('messages.all_stock')); ?></option>
            <option value="low" <?php echo e(request('stock') === 'low' ? 'selected' : ''); ?>><?php echo e(__('messages.low_stock')); ?></option>
            <option value="out" <?php echo e(request('stock') === 'out' ? 'selected' : ''); ?>><?php echo e(__('messages.out_of_stock')); ?></option>
        </select>
        <select name="featured" id="featuredFilter" onchange="filterProducts()">
            <option value=""><?php echo e(__('messages.all_featured')); ?></option>
            <option value="1" <?php echo e(request('featured') === '1' ? 'selected' : ''); ?>>⭐ <?php echo e(__('messages.featured_only')); ?></option>
            <option value="0" <?php echo e(request('featured') === '0' ? 'selected' : ''); ?>><?php echo e(__('messages.not_featured')); ?></option>
        </select>
        <select name="new" id="newFilter" onchange="filterProducts()">
            <option value=""><?php echo e(__('messages.all_new')); ?></option>
            <option value="1" <?php echo e(request('new') === '1' ? 'selected' : ''); ?>>🆕 <?php echo e(__('messages.new_only')); ?></option>
            <option value="0" <?php echo e(request('new') === '0' ? 'selected' : ''); ?>><?php echo e(__('messages.not_new')); ?></option>
        </select>
        <select name="bestseller" id="bestsellerFilter" onchange="filterProducts()">
            <option value=""><?php echo e(__('messages.all_bestseller')); ?></option>
            <option value="1" <?php echo e(request('bestseller') === '1' ? 'selected' : ''); ?>>🔥 <?php echo e(__('messages.bestseller_only')); ?></option>
            <option value="0" <?php echo e(request('bestseller') === '0' ? 'selected' : ''); ?>><?php echo e(__('messages.not_bestseller')); ?></option>
        </select>
        <select name="special_offer" id="specialOfferFilter" onchange="filterProducts()">
            <option value=""><?php echo e(__('messages.all_special_offers')); ?></option>
            <option value="1" <?php echo e(request('special_offer') === '1' ? 'selected' : ''); ?>>🎁 <?php echo e(__('messages.special_offer_only')); ?></option>
            <option value="0" <?php echo e(request('special_offer') === '0' ? 'selected' : ''); ?>><?php echo e(__('messages.not_special_offer')); ?></option>
        </select>
        <button type="button" class="filter-reset-btn" onclick="resetFilters()">
            <i class="fas fa-redo"></i> <?php echo e(__('messages.reset')); ?>

        </button>
    </div>
</form>

<!-- Products Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> <?php echo e(__('messages.product_list')); ?></h3>
    </div>
    
    <?php if($products->count() > 0): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="cursor: pointer;">
                    </th>
                    <th><?php echo e(__('messages.image')); ?></th>
                    <th><?php echo e(__('messages.product_name')); ?></th>
                    <th><?php echo e(__('messages.category')); ?></th>
                    <th><?php echo e(__('messages.brand')); ?></th>
                    <th><?php echo e(__('messages.price')); ?></th>
                    <th><?php echo e(__('messages.stock')); ?></th>
                    <th><?php echo e(__('messages.images')); ?></th>
                    <th><?php echo e(__('messages.status')); ?></th>
                    <th style="text-align: right;"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-name="<?php echo e($product->name_en ?? $product->name); ?><?php echo e($product->sku ?? ''); ?>"
                    data-category="<?php echo e($product->category->name ?? ''); ?>"
                    data-status="<?php echo e($product->is_active ? 'active' : 'inactive'); ?>"
                    data-stock="<?php echo e($product->stock_quantity); ?>">

                    <td style="text-align: center;">
                        <input type="checkbox" class="product-checkbox" value="<?php echo e($product->id); ?>" onchange="updateBulkDeleteButton()" style="cursor: pointer;">
                    </td>

                    <td class="product-image-cell">
                        <?php if($product->main_image): ?>
                            <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
                        <?php else: ?>
                            <div class="product-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div class="product-name-cell">
                            <div class="product-name" title="<?php echo e($product->name_en ?? $product->name); ?>">
                                <?php echo e($product->name_en ?? $product->name); ?>

                            </div>
                            <?php if($product->sku): ?>
                                <div class="product-sku"><?php echo e($product->sku); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>

                    <td>
                        <?php if($product->category): ?>
                            <span class="product-category-badge">
                                <?php echo e($product->category->name_en ?? $product->category->name); ?>

                            </span>
                        <?php else: ?>
                            <span style="color: #94a3b8;"><?php echo e(__('messages.uncategorized')); ?></span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($product->brand): ?>
                            <span class="product-brand-badge">
                                <?php echo e($product->brand->name_en ?? $product->brand->name); ?>

                            </span>
                        <?php else: ?>
                            <span style="color: #94a3b8;">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div class="product-price-cell">
                            $<?php echo e(number_format($product->price, 2)); ?>

                        </div>
                        <?php if($product->sale_price): ?>
                            <div class="product-sale-price">
                                $<?php echo e(number_format($product->sale_price, 2)); ?>

                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php
                            $stock = $product->stock_quantity;
                            $stockClass = $stock > 20 ? 'stock-high' : ($stock > 5 ? 'stock-medium' : 'stock-low');
                            $stockLabel = $stock > 0 ? $stock . ' ' . __('messages.units') : __('messages.out_of_stock');
                        ?>
                        <span class="stock-badge <?php echo e($stockClass); ?>">
                            <i class="fas <?php echo e($stock > 0 ? 'fa-check' : 'fa-times'); ?>"></i>
                            <?php echo e($stockLabel); ?>

                        </span>
                    </td>

                    <td>
                        <span class="images-badge">
                            <i class="fas fa-image"></i>
                            <?php echo e($product->images->count() + 1); ?> <?php echo e($product->images->count() + 1 === 1 ? __('messages.image') : __('messages.images')); ?>

                        </span>
                    </td>

                    <td>
                        <span class="status-badge <?php echo e($product->is_active ? 'status-active' : 'status-inactive'); ?>">
                            <i class="fas <?php echo e($product->is_active ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                            <?php echo e($product->is_active ? __('messages.active') : __('messages.inactive')); ?>

                        </span>
                    </td>

                    <td class="action-cell" style="text-align: right;">
                        <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" style="display: inline;" 
                              onsubmit="handleFormConfirm(event, {
                                  message: '<?php echo e(__('messages.delete_product_confirm')); ?>',
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
    <?php if($products->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($products->links()); ?>

        </div>
    <?php endif; ?>
    <?php else: ?>
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-box-open"></i>
        </div>
        <h3><?php echo e(__('messages.no_products_available')); ?></h3>
        <p><?php echo e(__('messages.start_adding_products')); ?></p>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_product')); ?> (N)
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.delete_all_products')); ?>

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
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.delete_selected_products')); ?>

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
    function showDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'flex';
    }

    function hideDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'none';
    }

    function deleteAllRecords() {
        // Disable the delete button to prevent multiple clicks
        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.deleting")); ?>...';
        
        fetch('<?php echo e(route("admin.products.delete-all")); ?>', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideDeleteAllModal();
            if (data.success) {
                document.getElementById('successMessage').textContent = data.message || '<?php echo e(__("messages.all_records_deleted_successfully")); ?>';
                document.getElementById('successModal').style.display = 'flex';
            } else {
                alert(data.message || '<?php echo e(__("messages.error_occurred")); ?>');
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php echo e(__("messages.error_occurred")); ?>');
            window.location.reload();
        });
    }

    function showBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').style.display = 'flex';
    }

    function hideBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').style.display = 'none';
    }

    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        updateBulkDeleteButton();
    }

    function updateBulkDeleteButton() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDeleteText = document.getElementById('bulkDeleteText');
        
        if (checkboxes.length > 0) {
            bulkDeleteBtn.style.display = 'inline-flex';
            bulkDeleteText.textContent = '<?php echo e(__("messages.delete_selected")); ?> (' + checkboxes.length + ')';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    function bulkDeleteRecords() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('<?php echo e(__("messages.no_products_selected")); ?>');
            return;
        }

        // Disable the delete button to prevent multiple clicks
        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.deleting")); ?>...';
        
        fetch('<?php echo e(route("admin.products.bulk-delete")); ?>', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            hideBulkDeleteModal();
            if (data.success) {
                document.getElementById('successMessage').textContent = data.message || '<?php echo e(__("messages.selected_records_deleted_successfully")); ?>';
                document.getElementById('successModal').style.display = 'flex';
            } else {
                alert(data.message || '<?php echo e(__("messages.error_occurred")); ?>');
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php echo e(__("messages.error_occurred")); ?>');
            window.location.reload();
        });
    }

    let searchTimeout;
    
    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterProducts();
        }, 500);
    }

    function filterProducts() {
        document.getElementById('searchFilterForm').submit();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('stockFilter').value = '';
        document.getElementById('featuredFilter').value = '';
        document.getElementById('newFilter').value = '';
        document.getElementById('bestsellerFilter').value = '';
        document.getElementById('specialOfferFilter').value = '';
        window.location.href = '<?php echo e(route("admin.products.index")); ?>';
    }

    // Keyboard shortcut for creating new product
    document.addEventListener('keydown', function(e) {
        // Check if 'N' is pressed and no input/textarea is focused
        if (e.key === 'n' || e.key === 'N') {
            const activeElement = document.activeElement;
            const isInputFocused = activeElement.tagName === 'INPUT' || 
                                   activeElement.tagName === 'TEXTAREA' || 
                                   activeElement.isContentEditable;
            
            if (!isInputFocused) {
                e.preventDefault();
                window.location.href = '<?php echo e(route("admin.products.create")); ?>';
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/products/index.blade.php ENDPATH**/ ?>