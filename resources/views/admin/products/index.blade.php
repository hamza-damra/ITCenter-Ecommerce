@extends('admin.layout')

@section('title', __('messages.products_management'))

@section('content')
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
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #0c4a6e;
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
        border-radius: 16px;
        padding: 80px 40px;
        text-align: center;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state h3 {
        font-size: 24px;
        color: var(--dark);
        margin-bottom: 12px;
        font-weight: 700;
    }

    .empty-state p {
        color: var(--secondary);
        margin-bottom: 28px;
        font-size: 16px;
    }

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
        <h1><i class="fas fa-box-open"></i> {{ __('messages.products_management') }}</h1>
        <p>{{ __('messages.manage_product_catalog') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_product') }}
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
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-boxes"></i> {{ __('messages.total_products') }}</h4>
        <div class="number" style="color: white;">{{ $totalProducts }}</div>
    </div>
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-check-circle"></i> {{ __('messages.active') }}</h4>
        <div class="number" style="color: white;">{{ $activeProducts }}</div>
    </div>
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-star"></i> {{ __('messages.featured_products_count') }}</h4>
        <div class="number" style="color: white;">{{ $featuredProducts }}</div>
    </div>
    <div class="stat-mini-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white;">
        <h4 style="color: rgba(255,255,255,0.9);"><i class="fas fa-exclamation-triangle"></i> {{ __('messages.low_stock') }}</h4>
        <div class="number" style="color: white;">{{ $lowStockProducts }}</div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="🔍 {{ __('messages.search_by_name_sku') }}" onkeyup="filterProducts()">
    <select id="statusFilter" onchange="filterProducts()">
        <option value="">{{ __('messages.all_status') }}</option>
        <option value="active">{{ __('messages.active_only') }}</option>
        <option value="inactive">{{ __('messages.inactive_only') }}</option>
    </select>
    <select id="stockFilter" onchange="filterProducts()">
        <option value="">{{ __('messages.all_stock') }}</option>
        <option value="low">{{ __('messages.low_stock') }}</option>
        <option value="out">{{ __('messages.out_of_stock') }}</option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> {{ __('messages.reset') }}
    </button>
</div>

<!-- Products Table -->
@if($products->count() > 0)
    <div class="products-table-wrapper">
        <table class="products-table">
            <thead>
                <tr>
                    <th>{{ __('messages.image') }}</th>
                    <th>{{ __('messages.product_name') }}</th>
                    <th>{{ __('messages.category') }}</th>
                    <th>{{ __('messages.brand') }}</th>
                    <th>{{ __('messages.price') }}</th>
                    <th>{{ __('messages.stock') }}</th>
                    <th>{{ __('messages.images') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th style="text-align: right;">{{ __('messages.actions') }}</th>
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
                            <span style="color: #94a3b8;">{{ __('messages.uncategorized') }}</span>
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
                            $stockLabel = $stock > 0 ? $stock . ' ' . __('messages.units') : __('messages.out_of_stock');
                        @endphp
                        <span class="stock-badge {{ $stockClass }}">
                            <i class="fas {{ $stock > 0 ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $stockLabel }}
                        </span>
                    </td>

                    <td>
                        <span class="images-badge">
                            <i class="fas fa-image"></i>
                            {{ $product->images->count() + 1 }} {{ $product->images->count() + 1 === 1 ? __('messages.image') : __('messages.images') }}
                        </span>
                    </td>

                    <td>
                        <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                            <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $product->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>

                    <td class="action-cell" style="text-align: right;">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.delete_product_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> {{ __('messages.delete') }}
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
        <h3>{{ __('messages.no_products_available') }}</h3>
        <p>{{ __('messages.start_adding_products') }}</p>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_product') }}
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
