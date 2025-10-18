@extends('admin.layout')

@section('title', __('messages.categories_management'))

@section('content')
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
        <h1>{{ __('messages.categories_management_title') }}</h1>
        <p>{{ __('messages.organize_categories_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_category') }}
        </a>
    </div>
</div>

<!-- Stats Overview -->
@php
    $totalCategories = $categories->total() ?? count($categories);
    $activeCategories = $categories->where('is_active', true)->count() ?? 0;
    $rootCategories = $categories->where('parent_id', null)->count() ?? 0;
@endphp
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-folder"></i> {{ __('messages.total_categories_stat') }}</h4>
        <div class="number">{{ $totalCategories }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_categories') }}</h4>
        <div class="number" style="color: var(--success);">{{ $activeCategories }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--primary-light);">
        <h4><i class="fas fa-sitemap"></i> {{ __('messages.root_categories') }}</h4>
        <div class="number" style="color: var(--primary-light);">{{ $rootCategories }}</div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="{{ __('messages.search_categories') }}" onkeyup="filterCategories()">
    <select id="statusFilter" onchange="filterCategories()">
        <option value="">{{ __('messages.all_status') }}</option>
        <option value="active">{{ __('messages.active_only') }}</option>
        <option value="inactive">{{ __('messages.inactive_only') }}</option>
    </select>
    <select id="parentFilter" onchange="filterCategories()">
        <option value="">{{ __('messages.all_categories_filter') }}</option>
        <option value="root">{{ __('messages.root_only') }}</option>
        <option value="subcategory">{{ __('messages.subcategories_only') }}</option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> {{ __('messages.reset_filters') }}
    </button>
</div>

<!-- Categories Table -->
@if($categories->count() > 0)
    <div class="categories-table-wrapper">
        <table class="categories-table">
            <thead>
                <tr>
                    <th>{{ __('messages.image') }}</th>
                    <th>{{ __('messages.category_name') }}</th>
                    <th>{{ __('messages.parent_category') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th style="text-align: right;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr data-status="{{ $category->is_active ? 'active' : 'inactive' }}" 
                    data-parent="{{ $category->parent_id ? 'subcategory' : 'root' }}"
                    data-name="{{ $category->name_en ?? $category->name }}{{ $category->slug ?? '' }}">
                    
                    <td class="category-image-cell">
                        @if($category->image)
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="category-image">
                        @else
                            <div class="category-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>

                    <td>
                        <div class="category-name-cell">
                            <div class="category-name">{{ $category->name_en ?? $category->name }}</div>
                            @if($category->slug)
                                <div class="category-slug">{{ $category->slug }}</div>
                            @endif
                        </div>
                    </td>

                    <td>
                        @if($category->parent)
                            <span class="category-parent-badge">
                                <i class="fas fa-arrow-right"></i> {{ $category->parent->name_en ?? $category->parent->name }}
                            </span>
                        @else
                            <span class="category-parent-root">
                                <i class="fas fa-folder-open"></i> {{ __('messages.root_category') }}
                            </span>
                        @endif
                    </td>

                    <td>
                        <span class="status-badge {{ $category->is_active ? 'status-active' : 'status-inactive' }}">
                            <i class="fas {{ $category->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $category->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>

                    <td class="action-cell" style="text-align: right;">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.delete_category_confirm') }}');">
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
    @if($categories->hasPages())
        <div class="pagination-wrapper">
            {{ $categories->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h3>{{ __('messages.no_categories_found') }}</h3>
        <p>{{ __('messages.no_categories_description') }}</p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_category') }}
        </a>
    </div>
@endif

<script>
    function filterCategories() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const parentFilter = document.getElementById('parentFilter').value;
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

            row.style.display = matches ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('parentFilter').value = '';
        filterCategories();
    }
</script>

@endsection
