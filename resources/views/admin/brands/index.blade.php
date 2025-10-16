@extends('admin.layout')

@section('title', 'Brands Management')

@section('content')
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
        <h1>Brands Management</h1>
        <p>Manage your product brands and keep your catalog organized</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Add New Brand
        </a>
    </div>
</div>

<!-- Stats Overview -->
@php
    $totalBrands = $brands->total() ?? count($brands);
    $activeBrands = $brands->where('is_active', true)->count() ?? 0;
    $featuredBrands = $brands->where('is_featured', true)->count() ?? 0;
@endphp
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-tags"></i> Total Brands</h4>
        <div class="number">{{ $totalBrands }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> Active</h4>
        <div class="number" style="color: var(--success);">{{ $activeBrands }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--warning);">
        <h4><i class="fas fa-star"></i> Featured</h4>
        <div class="number" style="color: var(--warning);">{{ $featuredBrands }}</div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="🔍 Search brands..." onkeyup="filterBrands()">
    <select id="statusFilter" onchange="filterBrands()">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
    <select id="featuredFilter" onchange="filterBrands()">
        <option value="">All Featured</option>
        <option value="yes">Featured Only</option>
        <option value="no">Not Featured</option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> Reset
    </button>
</div>

<!-- Brands Grid -->
<div class="brands-grid" id="brandsContainer">
    @forelse($brands as $brand)
        <div class="brand-card" data-status="{{ $brand->is_active ? 'active' : 'inactive' }}" data-featured="{{ $brand->is_featured ? 'yes' : 'no' }}" data-name="{{ $brand->name_en ?? $brand->name }}">
            <!-- Card Header with Logo -->
            <div class="brand-card-header">
                @if($brand->logo)
                    <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="brand-logo">
                @else
                    <div class="brand-logo-placeholder">
                        <i class="fas fa-image"></i> No Logo
                    </div>
                @endif
            </div>

            <!-- Card Body -->
            <div class="brand-card-body">
                <div class="brand-name" title="{{ $brand->name_en ?? $brand->name }}">
                    {{ $brand->name_en ?? $brand->name }}
                </div>
                
                @if($brand->website)
                    <a href="{{ $brand->website }}" target="_blank" class="brand-website" title="{{ $brand->website }}">
                        <i class="fas fa-globe"></i> {{ $brand->website }}
                    </a>
                @endif

                <!-- Meta Badges -->
                <div class="brand-meta">
                    <span class="brand-meta-badge {{ $brand->is_active ? 'brand-status-active' : 'brand-status-inactive' }}">
                        <i class="fas {{ $brand->is_active ? 'fa-circle' : 'fa-circle' }}"></i>
                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="brand-meta-badge {{ $brand->is_featured ? 'brand-featured-yes' : 'brand-featured-no' }}">
                        <i class="fas {{ $brand->is_featured ? 'fa-star' : 'fa-star' }}"></i>
                        {{ $brand->is_featured ? 'Featured' : 'Regular' }}
                    </span>
                </div>
            </div>

            <!-- Card Footer with Actions -->
            <div class="brand-card-footer">
                <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this brand? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <i class="fas fa-box-open"></i>
            <h3>No Brands Found</h3>
            <p>You haven't added any brands yet. Start by creating your first brand!</p>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create First Brand
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($brands->hasPages())
    <div style="margin-top: 24px;">
        {{ $brands->links() }}
    </div>
@endif

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
</script>

@endsection
