@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Dashboard Specific Styles */
    .dashboard-header {
        margin-bottom: 32px;
    }

    .greeting {
        font-size: 16px;
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .stats-grid-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .stat-card-large {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card-large:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card-large.success {
        border-left-color: var(--success);
    }

    .stat-card-large.warning {
        border-left-color: var(--warning);
    }

    .stat-card-large.danger {
        border-left-color: var(--danger);
    }

    .stat-card-large.info {
        border-left-color: #3b82f6;
    }

    .stat-card-icon {
        position: absolute;
        top: -10px;
        right: -10px;
        font-size: 80px;
        opacity: 0.05;
    }

    .stat-card-content {
        position: relative;
    }

    .stat-card-label {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .stat-card-value {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .stat-card-large.success .stat-card-value {
        color: var(--success);
    }

    .stat-card-large.warning .stat-card-value {
        color: var(--warning);
    }

    .stat-card-large.danger .stat-card-value {
        color: var(--danger);
    }

    .stat-card-large.info .stat-card-value {
        color: #3b82f6;
    }

    .stat-card-footer {
        font-size: 12px;
        color: var(--secondary);
    }

    .stat-card-footer i {
        margin-right: 4px;
    }

    .dashboard-sections {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .recent-products-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .recent-products-card .card-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-products-card .card-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
    }

    .view-all-link {
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .view-all-link:hover {
        gap: 10px;
    }

    .recent-products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-products-table thead {
        background: #f8fafc;
    }

    .recent-products-table th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border);
    }

    .recent-products-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        color: var(--dark);
    }

    .recent-products-table tbody tr:hover {
        background: #f8fafc;
    }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-cell img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--border);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .product-name {
        font-weight: 600;
        font-size: 14px;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-sku {
        font-size: 12px;
        color: var(--secondary);
    }

    .price-cell {
        font-weight: 700;
        color: var(--success);
    }

    .stock-cell {
        font-weight: 600;
    }

    .stock-cell.low {
        color: var(--danger);
    }

    .stock-cell.good {
        color: var(--success);
    }

    .status-pill {
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

    .quick-actions-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .quick-actions-card .card-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border);
    }

    .quick-actions-card .card-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
    }

    .quick-actions-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .quick-actions-list li {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .quick-actions-list li:last-child {
        border-bottom: none;
    }

    .quick-actions-list li:hover {
        background: #f8fafc;
    }

    .quick-action-link {
        text-decoration: none;
        color: var(--primary);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
    }

    .quick-action-link:hover {
        gap: 16px;
    }

    .quick-action-link i {
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .empty-state-message {
        padding: 40px 24px;
        text-align: center;
        color: var(--secondary);
    }

    .empty-state-message i {
        font-size: 32px;
        margin-bottom: 12px;
        display: block;
        color: #cbd5e1;
    }

    @media (max-width: 1024px) {
        .dashboard-sections {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid-dashboard {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        }

        .stat-card-value {
            font-size: 28px;
        }

        .recent-products-table {
            font-size: 13px;
        }

        .recent-products-table td,
        .recent-products-table th {
            padding: 10px 12px;
        }

        .product-cell img {
            width: 40px;
            height: 40px;
        }

        .product-name {
            max-width: 100px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header" style="border-left-color: var(--primary);">
    <div class="page-header-content">
        <h1>Dashboard</h1>
        <p>Welcome back! Here's your catalog overview</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid-dashboard">
    <!-- Total Products -->
    <div class="stat-card-large">
        <div class="stat-card-icon"><i class="fas fa-box"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-cubes"></i> Total Products</div>
            <div class="stat-card-value">{{ $stats['total_products'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-chart-line"></i> Complete inventory
            </div>
        </div>
    </div>

    <!-- Active Products -->
    <div class="stat-card-large success">
        <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-eye"></i> Active Products</div>
            <div class="stat-card-value">{{ $stats['active_products'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-info-circle"></i> Currently visible
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="stat-card-large info">
        <div class="stat-card-icon"><i class="fas fa-folder"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-folder-open"></i> Categories</div>
            <div class="stat-card-value">{{ $stats['total_categories'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-sitemap"></i> Organize products
            </div>
        </div>
    </div>

    <!-- Brands -->
    <div class="stat-card-large info">
        <div class="stat-card-icon"><i class="fas fa-tag"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-tags"></i> Brands</div>
            <div class="stat-card-value">{{ $stats['total_brands'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-industry"></i> In your store
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="stat-card-large warning">
        <div class="stat-card-icon"><i class="fas fa-star"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-crown"></i> Featured</div>
            <div class="stat-card-value">{{ $stats['featured_products'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-highlight"></i> Promoted items
            </div>
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="stat-card-large danger">
        <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-times-circle"></i> Out of Stock</div>
            <div class="stat-card-value">{{ $stats['out_of_stock'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-alert"></i> Need attention
            </div>
        </div>
    </div>

    <!-- Total Reviews -->
    <div class="stat-card-large">
        <div class="stat-card-icon"><i class="fas fa-comments"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-star"></i> Total Reviews</div>
            <div class="stat-card-value">{{ $stats['total_reviews'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-users"></i> Customer feedback
            </div>
        </div>
    </div>

    <!-- Active Offers -->
    <div class="stat-card-large success">
        <div class="stat-card-icon"><i class="fas fa-gift"></i></div>
        <div class="stat-card-content">
            <div class="stat-card-label"><i class="fas fa-percentage"></i> Active Offers</div>
            <div class="stat-card-value">{{ $stats['active_offers'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-fire"></i> Running campaigns
            </div>
        </div>
    </div>
</div>

<!-- Main Content Sections -->
<div class="dashboard-sections">
    <!-- Recent Products -->
    <div class="recent-products-card">
        <div class="card-header">
            <h2><i class="fas fa-clock"></i> Recent Products</h2>
            <a href="{{ route('admin.products.index') }}" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($recent_products->count() > 0)
                <table class="recent-products-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_products as $product)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name_en ?? $product->name }}</div>
                                        <div class="product-sku">{{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px;">
                                    {{ $product->category->name_en ?? $product->category->name }}
                                </span>
                            </td>
                            <td>
                                <div class="price-cell">${{ number_format($product->price, 2) }}</div>
                            </td>
                            <td>
                                <span class="stock-cell {{ $product->stock_quantity > 10 ? 'good' : 'low' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>
                                <span class="status-pill {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                    <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state-message">
                    <i class="fas fa-inbox"></i>
                    <p>No products yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-card">
        <div class="card-header">
            <h2><i class="fas fa-lightning-bolt"></i> Quick Actions</h2>
        </div>
        <ul class="quick-actions-list">
            <li>
                <a href="{{ route('admin.products.create') }}" class="quick-action-link">
                    <i class="fas fa-plus"></i>
                    <span>Add New Product</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.create') }}" class="quick-action-link">
                    <i class="fas fa-folder-plus"></i>
                    <span>Create Category</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.brands.create') }}" class="quick-action-link">
                    <i class="fas fa-tag"></i>
                    <span>Add New Brand</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="quick-action-link">
                    <i class="fas fa-list"></i>
                    <span>Manage Products</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="quick-action-link">
                    <i class="fas fa-th"></i>
                    <span>Manage Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.brands.index') }}" class="quick-action-link">
                    <i class="fas fa-bars"></i>
                    <span>Manage Brands</span>
                </a>
            </li>
        </ul>
    </div>
</div>

@endsection
