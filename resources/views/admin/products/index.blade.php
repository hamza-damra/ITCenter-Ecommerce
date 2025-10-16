@extends('admin.layout')

@section('title', 'Products Management')

@section('content')
<style>
    /* Products Page Specific Styles */
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
        min-width: 180px;
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

    .products-table-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .products-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid var(--border);
    }

    .products-table th {
        padding: 16px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .products-table tbody tr:hover {
        background: #f8fafc;
    }

    .products-table tbody tr:last-child {
        border-bottom: none;
    }

    .products-table td {
        padding: 16px;
        color: var(--dark);
        vertical-align: middle;
    }

    .product-image-cell {
        display: flex;
        align-items: center;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .product-image-placeholder {
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

    .product-name-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .product-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 15px;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-sku {
        font-size: 12px;
        color: var(--secondary);
        font-family: 'Courier New', monospace;
    }

    .product-category {
        font-size: 13px;
        color: var(--secondary);
    }

    .product-category-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #3730a3;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .product-brand-cell {
        font-size: 13px;
        color: var(--secondary);
    }

    .product-brand-badge {
        display: inline-block;
        background: #f3e8ff;
        color: #6b21a8;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .product-price-cell {
        font-weight: 700;
        color: var(--success);
        font-size: 15px;
    }

    .product-sale-price {
        font-size: 12px;
        color: var(--danger);
        text-decoration: line-through;
    }

    .stock-badge {
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

    .stock-high {
        background: #d1fae5;
        color: #065f46;
    }

    .stock-medium {
        background: #fef3c7;
        color: #92400e;
    }

    .stock-low {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .images-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        background: #dbeafe;
        color: #0c4a6e;
        font-size: 12px;
        font-weight: 700;
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

    .pagination-wrapper {
        margin-top: 24px;
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
            padding: 12px;
        }

        .product-image {
            width: 50px;
            height: 50px;
        }

        .product-image-placeholder {
            width: 50px;
            height: 50px;
        }

        .product-name {
            max-width: 150px;
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
        <h1>Products Management</h1>
        <p>Manage your product catalog with ease</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Add New Product
        </a>
    </div>
</div>

<!-- Stats Overview -->
@php
    $totalProducts = $products->total() ?? count($products);
    $activeProducts = $products->where('is_active', true)->count() ?? 0;
    $featuredProducts = $products->where('is_featured', true)->count() ?? 0;
    $lowStockProducts = $products->where('stock_quantity', '<', 5)->count() ?? 0;
@endphp
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-boxes"></i> Total Products</h4>
        <div class="number">{{ $totalProducts }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> Active</h4>
        <div class="number" style="color: var(--success);">{{ $activeProducts }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--warning);">
        <h4><i class="fas fa-star"></i> Featured</h4>
        <div class="number" style="color: var(--warning);">{{ $featuredProducts }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--danger);">
        <h4><i class="fas fa-exclamation-triangle"></i> Low Stock</h4>
        <div class="number" style="color: var(--danger);">{{ $lowStockProducts }}</div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="🔍 Search by name or SKU..." onkeyup="filterProducts()">
    <select id="statusFilter" onchange="filterProducts()">
        <option value="">All Status</option>
        <option value="active">Active Only</option>
        <option value="inactive">Inactive Only</option>
    </select>
    <select id="stockFilter" onchange="filterProducts()">
        <option value="">All Stock</option>
        <option value="low">Low Stock</option>
        <option value="out">Out of Stock</option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> Reset
    </button>
</div>

<!-- Products Table -->
@if($products->count() > 0)
    <div class="products-table-wrapper">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Images</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr data-name="{{ $product->name_en ?? $product->name }}{{ $product->sku ?? '' }}"
                    data-category="{{ $product->category->name ?? '' }}"
                    data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
                    data-stock="{{ $product->stock_quantity }}">
                    
                    <td class="product-image-cell">
                        @if($product->main_image)
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <div class="product-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>

                    <td>
                        <div class="product-name-cell">
                            <div class="product-name" title="{{ $product->name_en ?? $product->name }}">
                                {{ $product->name_en ?? $product->name }}
                            </div>
                            @if($product->sku)
                                <div class="product-sku">{{ $product->sku }}</div>
                            @endif
                        </div>
                    </td>

                    <td>
                        @if($product->category)
                            <span class="product-category-badge">
                                {{ $product->category->name_en ?? $product->category->name }}
                            </span>
                        @else
                            <span style="color: #94a3b8;">Uncategorized</span>
                        @endif
                    </td>

                    <td>
                        @if($product->brand)
                            <span class="product-brand-badge">
                                {{ $product->brand->name_en ?? $product->brand->name }}
                            </span>
                        @else
                            <span style="color: #94a3b8;">—</span>
                        @endif
                    </td>

                    <td>
                        <div class="product-price-cell">
                            ${{ number_format($product->price, 2) }}
                        </div>
                        @if($product->sale_price)
                            <div class="product-sale-price">
                                ${{ number_format($product->sale_price, 2) }}
                            </div>
                        @endif
                    </td>

                    <td>
                        @php
                            $stock = $product->stock_quantity;
                            $stockClass = $stock > 20 ? 'stock-high' : ($stock > 5 ? 'stock-medium' : 'stock-low');
                            $stockLabel = $stock > 0 ? $stock . ' units' : 'Out of Stock';
                        @endphp
                        <span class="stock-badge {{ $stockClass }}">
                            <i class="fas {{ $stock > 0 ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $stockLabel }}
                        </span>
                    </td>

                    <td>
                        <span class="images-badge">
                            <i class="fas fa-image"></i>
                            {{ $product->images->count() + 1 }} {{ $product->images->count() + 1 === 1 ? 'image' : 'images' }}
                        </span>
                    </td>

                    <td>
                        <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                            <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="action-cell" style="text-align: right;">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>No Products Found</h3>
        <p>You haven't added any products yet. Start by creating your first product!</p>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Create First Product
        </a>
    </div>
@endif

<script>
    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const stockFilter = document.getElementById('stockFilter').value;
        const rows = document.querySelectorAll('.products-table tbody tr');

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

            // Stock filter
            if (stockFilter) {
                const stock = parseInt(row.getAttribute('data-stock'));
                if (stockFilter === 'low') {
                    matches = matches && stock > 0 && stock <= 5;
                } else if (stockFilter === 'out') {
                    matches = matches && stock === 0;
                }
            }

            row.style.display = matches ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('stockFilter').value = '';
        filterProducts();
    }
</script>

@endsection
