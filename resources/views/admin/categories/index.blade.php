@extends('admin.layout')

@section('title', __('messages.categories_management'))

@section('content')
<style>
    /* Categories Page Specific Styles - Extending unified components */
    
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

    /* Category Image Styles */
    .category-image-cell {
        display: flex;
        align-items: center;
    }

    .category-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .category-image:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .category-image-placeholder {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
        color: #94a3b8;
        border-radius: 12px;
        font-size: 24px;
    }

    /* Category Name Cell */
    .category-name-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .category-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 15px;
    }

    .category-slug {
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

    /* Parent Category Badges */
    .category-parent-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
    }

    .category-parent-root {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #6b7280;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
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
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .search-filter-box {
            padding: 16px;
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -16px;
            padding: 0 16px;
        }

        .admin-table {
            font-size: 12px;
            min-width: 600px;
        }

        .admin-table td,
        .admin-table th {
            padding: 10px 8px;
            white-space: nowrap;
        }

        .category-image {
            width: 45px;
            height: 45px;
        }

        .category-image-placeholder {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }

        .category-name {
            font-size: 13px;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
    }

    @media (max-width: 480px) {
        .search-filter-box {
            padding: 12px;
        }

        .admin-table {
            font-size: 11px;
        }

        .admin-table td,
        .admin-table th {
            padding: 8px 6px;
        }

        .category-image {
            width: 36px;
            height: 36px;
        }

        .category-image-placeholder {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .action-cell .btn {
            padding: 5px 6px;
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
                <i class="fas fa-folder-tree"></i>
            </div>
            <div>
                <h1>{{ __('messages.categories_management_title') }}</h1>
                <p>{{ __('messages.organize_categories_subtitle') }}</p>
            </div>
        </div>
        <div class="header-actions">
            @if($categories->count() > 0)
                <button id="bulkDeleteBtn" onclick="showBulkDeleteModal()" class="btn btn-danger" style="display: none;">
                    <i class="fas fa-trash-alt"></i> <span id="bulkDeleteText">{{ __('messages.delete_selected') }}</span>
                </button>
                <button onclick="showDeleteAllModal()" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> {{ __('messages.delete_all') }}
                </button>
            @endif
            <a href="{{ route('admin.categories.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_category') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview - Using unified admin-stats-grid component -->
@php
    $totalCategories = $categories->total() ?? count($categories);
    $activeCategories = $categories->where('is_active', true)->count() ?? 0;
    $rootCategories = $categories->where('parent_id', null)->count() ?? 0;
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-folder"></i> {{ __('messages.total_categories_stat') }}</h4>
        <div class="stat-value">{{ $totalCategories }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_categories') }}</h4>
        <div class="stat-value">{{ $activeCategories }}</div>
    </div>
    <div class="admin-stat-card stat-indigo">
        <h4><i class="fas fa-sitemap"></i> {{ __('messages.root_categories') }}</h4>
        <div class="stat-value">{{ $rootCategories }}</div>
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
    <select id="displayModeFilter" onchange="filterCategories()">
        <option value="">{{ __('messages.all_display_modes') }}</option>
        <option value="carousel">{{ __('messages.carousel') }}</option>
        <option value="nav">{{ __('messages.nav_bar') }}</option>
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> {{ __('messages.reset_filters') }}
    </button>
</div>

<!-- Categories Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.category_list') }}</h3>
    </div>
    
    @if($categories->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="cursor: pointer;">
                    </th>
                    <th>{{ __('messages.image') }}</th>
                    <th>{{ __('messages.category_name') }}</th>
                    <th>{{ __('messages.parent_category') }}</th>
                    <th>{{ __('messages.display_mode') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th style="text-align: right;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr data-status="{{ $category->is_active ? 'active' : 'inactive' }}"
                    data-parent="{{ $category->parent_id ? 'subcategory' : 'root' }}"
                    data-display-mode="{{ $category->display_mode ?? 'carousel' }}"
                    data-name="{{ $category->name_en ?? $category->name }}{{ $category->slug ?? '' }}">

                    <td style="text-align: center;">
                        <input type="checkbox" class="category-checkbox" value="{{ $category->id }}" onchange="updateBulkDeleteButton()" style="cursor: pointer;">
                    </td>

                    <td class="category-image-cell">
                        @if($category->icon)
                            <div class="category-image-placeholder" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                        @elseif($category->image)
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
                        @if(!$category->parent_id)
                            @if($category->display_mode === 'nav')
                                <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
                                    <i class="fas fa-bars"></i> {{ __('messages.nav_bar') }}
                                </span>
                            @else
                                <span class="status-badge" style="background: #fef3c7; color: #92400e;">
                                    <i class="fas fa-images"></i> {{ __('messages.carousel') }}
                                </span>
                            @endif
                        @else
                            <span class="status-badge" style="background: #f3f4f6; color: #6b7280;">
                                <i class="fas fa-level-down-alt"></i> {{ __('messages.inherits_parent') }}
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
                        <a href="{{ route('admin.categories.attributes.edit', $category) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-filter"></i> {{ __('messages.attributes') }}
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" 
                              onsubmit="handleFormConfirm(event, {
                                  message: '{{ __('messages.delete_category_confirm') }}',
                                  confirmText: '{{ __('messages.yes_delete') }}',
                                  type: 'danger',
                                  confirmButtonType: 'danger'
                              })">
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
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-folder-tree"></i>
        </div>
        <h3>{{ __('messages.no_categories_found') }}</h3>
        <p>{{ __('messages.no_categories_description') }}</p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_category') }}
        </a>
    </div>
    @endif
</div>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> {{ __('messages.delete_all_categories') }}
        </h3>
        <p style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            {{ __('messages.confirm_delete_all') }}
        </p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideDeleteAllModal()" class="btn" style="background: #e5e7eb; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </button>
            <button onclick="deleteAllRecords()" class="btn btn-danger" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-trash-alt"></i> {{ __('messages.yes_delete') }}
            </button>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> {{ __('messages.delete_selected_categories') }}
        </h3>
        <p style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            {{ __('messages.confirm_delete_selected') }}
        </p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideBulkDeleteModal()" class="btn" style="background: #e5e7eb; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </button>
            <button onclick="bulkDeleteRecords()" class="btn btn-danger" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-trash-alt"></i> {{ __('messages.yes_delete') }}
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #10b981; font-size: 24px;">
            <i class="fas fa-check-circle"></i> {{ __('messages.success') }}
        </h3>
        <p id="successMessage" style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            {{ __('messages.all_records_deleted_successfully') }}
        </p>
        <div style="display: flex; justify-content: flex-end;">
            <button onclick="window.location.reload()" class="btn btn-success" style="padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-check"></i> {{ __('messages.OK') }}
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
        const rows = document.querySelectorAll('.admin-table tbody tr');

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
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.deleting_all_records") }}';

        fetch('{{ route("admin.categories.delete-all") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
            bulkDeleteText.textContent = '{{ __("messages.delete_selected") }} (' + checkboxes.length + ')';
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
            alert('{{ __("messages.please_select_items") }}');
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
            alert('{{ __("messages.please_select_items") }}');
            return;
        }

        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.deleting") }}...';

        fetch('{{ route("admin.categories.bulk-delete") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

@endsection
