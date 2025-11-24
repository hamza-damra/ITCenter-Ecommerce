<?php $__env->startSection('title', __('messages.products_management')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Products Page Specific Styles */
    .search-filter-box {
        display: flex;
        gap: 16px;
        margin-bottom: 28px;
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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

    .products-table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .products-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .products-table th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    [dir="rtl"] .products-table th {
        text-transform: none;
        letter-spacing: normal;
    }

    .products-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .products-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .products-table tbody tr:last-child {
        border-bottom: none;
    }

    .products-table td {
        padding: 18px 20px;
        color: var(--dark);
        vertical-align: middle;
    }

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

    .product-category {
        font-size: 13px;
        color: var(--secondary);
    }

    .product-category-badge {
        display: inline-block;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
    }

    .product-brand-cell {
        font-size: 13px;
        color: var(--secondary);
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

    .action-cell {
        display: flex;
        gap: 10px;
    }

    .action-cell .btn {
        padding: 8px 16px;
        font-size: 13px;
        flex-shrink: 0;
    }

    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 60px 32px;
        text-align: center;
        border: none;
        box-shadow: 0 8px 30px rgba(2, 6, 23, 0.08);
        position: relative;
        overflow: hidden;
    }

    .empty-state::after {
        content: "";
        position: absolute;
        inset: 0; 
        background: radial-gradient(1200px 300px at 50% -200px, rgba(59,130,246,.10), transparent 60%),
                    radial-gradient(600px 200px at 80% 120%, rgba(99,102,241,.10), transparent 60%);
        pointer-events: none;
    }

    .empty-illustration {
        width: 88px;
        height: 88px;
        margin: 0 auto 18px;
        border-radius: 22px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
        box-shadow: 0 8px 20px rgba(55,48,163,.18);
    }

    .empty-illustration i { font-size: 40px; }

    .empty-state h3 {
        font-size: 26px;
        color: #0f172a;
        margin-bottom: 10px;
        font-weight: 800;
    }

    .empty-state p {
        color: #475569;
        margin-bottom: 28px;
        font-size: 15px;
    }

    .empty-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        max-width: 680px;
        margin: 0 auto 22px;
    }

    .empty-actions .btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border: none;
        font-weight: 700;
        padding: 14px 18px;
    }

    .empty-actions .btn-secondary {
        background: #eef2ff; 
        color: #3730a3;
        border: 1px solid #e0e7ff;
        font-weight: 700;
        padding: 14px 18px;
    }

    .empty-tips {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin-top: 8px;
    }

    .empty-tip {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 13px;
        color: #475569;
        display: flex; align-items: center; gap: 8px;
    }

    .empty-tip i { color: #64748b; }

    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-mini-card {
        padding: 28px 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
    }

    .stat-mini-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: var(--primary);
    }

    .stat-mini-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-mini-card h4 {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    [dir="rtl"] .stat-mini-card h4 {
        text-transform: none;
        letter-spacing: normal;
    }

    .stat-mini-card .number {
        font-size: 38px;
        font-weight: 700;
        color: var(--primary);
    }

    .pagination-wrapper {
        margin-top: 28px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 1024px) {
        .search-filter-box {
            flex-direction: column;
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .products-table {
            font-size: 13px;
        }

        .products-table td,
        .products-table th {
            padding: 14px;
        }

        .product-image {
            width: 60px;
            height: 60px;
        }

        .product-image-placeholder {
            width: 60px;
            height: 60px;
        }

        .product-name {
            max-width: 180px;
        }

        .action-cell {
            flex-direction: column;
        }

        .action-cell .btn {
            width: 100%;
        }

        .stat-mini-card .number {
            font-size: 32px;
        }
    }

    /* RTL Support for Products Table */
    [dir="rtl"] .products-table th,
    [dir="rtl"] .products-table td {
        text-align: right;
    }

    [dir="rtl"] .products-table th:last-child,
    [dir="rtl"] .products-table td:last-child {
        text-align: left;
    }

    [dir="rtl"] .action-cell {
        justify-content: flex-start;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1>
            <i class="fas fa-box-open"></i> <?php echo e(__('messages.products_management')); ?>

            <?php if(request('filter')): ?>
                <?php if(request('filter') == 'recent'): ?>
                    <span style="font-size: 0.6em; color: #3b82f6; font-weight: 600; margin-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 12px;">
                        (<?php echo e(__('messages.recent_products')); ?>)
                    </span>
                <?php elseif(request('filter') == 'top_rated'): ?>
                    <span style="font-size: 0.6em; color: #f59e0b; font-weight: 600; margin-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 12px;">
                        (<?php echo e(__('messages.top_rated_products')); ?>)
                    </span>
                <?php endif; ?>
            <?php endif; ?>
        </h1>
        <p><?php echo e(__('messages.manage_product_catalog')); ?></p>
    </div>
    <div class="page-actions">
        <?php if($products->count() > 0): ?>
            <button id="bulkDeleteBtn" onclick="showBulkDeleteModal()" class="btn btn-danger" style="margin-right: 10px; display: none;">
                <i class="fas fa-trash-alt"></i> <span id="bulkDeleteText"><?php echo e(__('messages.delete_selected')); ?></span>
            </button>
            <button onclick="showDeleteAllModal()" class="btn btn-danger" style="margin-right: 10px;">
                <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_all')); ?>

            </button>
        <?php endif; ?>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.add_new_product')); ?>

        </a>
    </div>
</div>

<!-- Stats Overview -->
<?php
    $totalProducts = $products->total() ?? count($products);
    $activeProducts = $products->where('is_active', true)->count() ?? 0;
    $featuredProducts = $products->where('is_featured', true)->count() ?? 0;
    $lowStockProducts = $products->where('stock_quantity', '<', 5)->count() ?? 0;
?>
<div class="stats-overview">
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-boxes"></i> <?php echo e(__('messages.total_products')); ?></h4>
        <div class="number" style="color: white;"><?php echo e($totalProducts); ?></div>
    </div>
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-check-circle"></i> <?php echo e(__('messages.active')); ?></h4>
        <div class="number" style="color: white;"><?php echo e($activeProducts); ?></div>
    </div>
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-star"></i> <?php echo e(__('messages.featured_products_count')); ?></h4>
        <div class="number" style="color: white;"><?php echo e($featuredProducts); ?></div>
    </div>
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.low_stock')); ?></h4>
        <div class="number" style="color: white;"><?php echo e($lowStockProducts); ?></div>
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

<!-- Products Table -->
<?php if($products->count() > 0): ?>
    <div class="products-table-wrapper">
        <table class="products-table">
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
    <div class="empty-state">
        <div class="empty-illustration">
            <i class="fas fa-box-open"></i>
        </div>
        <h3><?php echo e(__('messages.no_products_available')); ?></h3>
        <p><?php echo e(__('messages.start_adding_products')); ?></p>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_product')); ?> (N)
        </a>
    </div>
<?php endif; ?>

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
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.deleting_all_records")); ?>';

        fetch('<?php echo e(route("admin.products.delete-all")); ?>', {
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

    // Debounce timer for search
    let searchTimeout;

    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            filterProducts();
        }, 500); // Wait 500ms after user stops typing
    }

    function filterProducts() {
        // Submit the form to apply server-side filtering
        document.getElementById('searchFilterForm').submit();
    }

    function resetFilters() {
        // Redirect to the index page without any parameters
        window.location.href = '<?php echo e(route('admin.products.index')); ?>';
    }

    // Bulk selection functions
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });

        updateBulkDeleteButton();
    }

    function updateBulkDeleteButton() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
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
        const allCheckboxes = document.querySelectorAll('.product-checkbox');
        const allChecked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
        const someChecked = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;

        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked;
    }

    function showBulkDeleteModal() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
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
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length === 0) {
            alert('<?php echo e(__("messages.please_select_items")); ?>');
            return;
        }

        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.deleting")); ?>...';

        fetch('<?php echo e(route("admin.products.bulk-delete")); ?>', {
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

    // Quick create shortcut: press 'N' to open Create Product when empty state is visible
    document.addEventListener('keydown', function(e) {
        const activeTag = document.activeElement?.tagName?.toLowerCase();
        const typing = ['input','textarea','select'].includes(activeTag);
        const emptyStateVisible = document.querySelector('.empty-state');
        if (!typing && emptyStateVisible && (e.key === 'n' || e.key === 'N')) {
            window.location.href = '<?php echo e(route('admin.products.create')); ?>';
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\products\index.blade.php ENDPATH**/ ?>