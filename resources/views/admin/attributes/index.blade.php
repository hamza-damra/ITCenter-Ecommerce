@extends('admin.layout')

@section('title', __('messages.attributes_management'))

@section('content')
<style>
    /* Attributes Page Specific Styles - Extending unified components */

    /* Action Cell */
    .action-cell {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: nowrap;
    }

    .action-cell form {
        display: inline-flex;
        margin: 0;
    }

    .action-cell .btn {
        padding: 7px 14px;
        font-size: 12px;
        flex-shrink: 0;
        white-space: nowrap;
    }

    [dir="rtl"] .action-cell {
        justify-content: flex-start;
    }

    /* Attribute Type Badges */
    .attribute-type-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [dir="rtl"] .attribute-type-badge {
        text-transform: none;
        letter-spacing: normal;
    }

    .type-select {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .type-multi_select {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #4338ca;
    }

    .type-range {
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        color: #9f1239;
    }

    .type-color {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    /* Filterable Badge */
    .filterable-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .filterable-yes {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .filterable-no {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Values Count Link */
    .values-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .values-count:hover {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
    }

    /* Attribute Name Cell */
    .attribute-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 14px;
    }

    .attribute-slug {
        font-size: 12px;
        color: var(--secondary);
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 600;
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

    /* RTL Support */
    [dir="rtl"] .admin-table th,
    [dir="rtl"] .admin-table td {
        text-align: right;
    }

    [dir="rtl"] .admin-table th:last-child,
    [dir="rtl"] .admin-table td:last-child {
        text-align: left;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .header-actions .btn,
        .header-actions .btn-add {
            width: 100%;
            justify-content: center;
        }

        .action-cell {
            gap: 4px;
        }

        .action-cell .btn {
            padding: 5px 8px;
            font-size: 11px;
        }
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-sliders-h"></i>
            </div>
            <div>
                <h1>{{ __('messages.attributes_management') }}</h1>
                <p>{{ __('messages.manage_attributes_subtitle') }}</p>
            </div>
        </div>
        <div class="header-actions">
            @if($attributes->count() > 0)
                <button onclick="showDeleteAllModal()" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> {{ __('messages.delete_all') }}
                </button>
            @endif
            <a href="{{ route('admin.attributes.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_attribute') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview - Using unified admin-stats-grid component -->
@php
    $totalAttributes = $attributes->total() ?? count($attributes);
    $activeAttributes = $attributes->where('is_active', true)->count() ?? 0;
    $filterableAttributes = $attributes->where('is_filterable', true)->count() ?? 0;
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-sliders-h"></i> {{ __('messages.total_attributes') }}</h4>
        <div class="stat-value">{{ $totalAttributes }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_attributes') }}</h4>
        <div class="stat-value">{{ $activeAttributes }}</div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-filter"></i> {{ __('messages.filterable_attributes') }}</h4>
        <div class="stat-value">{{ $filterableAttributes }}</div>
    </div>
</div>

<!-- Attributes Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.attribute_list') }}</h3>
    </div>
    
    @if($attributes->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.attribute_name') }}</th>
                    <th>{{ __('messages.attribute_slug') }}</th>
                    <th>{{ __('messages.attribute_type') }}</th>
                    <th>{{ __('messages.attribute_unit') }}</th>
                    <th>{{ __('messages.attribute_values') }}</th>
                    <th>{{ __('messages.attribute_filterable') }}</th>
                    <th>{{ __('messages.attribute_order') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th style="text-align: right;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attributes as $attribute)
                    <tr>
                        <td>
                            <div class="attribute-name">{{ $attribute->name_en }}</div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                AR: {{ $attribute->name_ar }} | HE: {{ $attribute->name_he }}
                            </div>
                        </td>
                        <td>
                            <span class="attribute-slug">{{ $attribute->slug }}</span>
                        </td>
                        <td>
                            <span class="attribute-type-badge type-{{ $attribute->type }}">
                                {{ str_replace('_', ' ', $attribute->type) }}
                            </span>
                        </td>
                        <td>
                            @if($attribute->unit)
                                <span style="font-family: monospace; color: var(--secondary); font-weight: 600;">{{ $attribute->unit }}</span>
                            @else
                                <span style="color: #cbd5e1;">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.attribute-values.index', $attribute) }}" class="values-count">
                                <i class="fas fa-list"></i>
                                {{ __('messages.values_count', ['count' => $attribute->values->count()]) }}
                            </a>
                        </td>
                        <td>
                            <span class="filterable-badge {{ $attribute->is_filterable ? 'filterable-yes' : 'filterable-no' }}">
                                <i class="fas {{ $attribute->is_filterable ? 'fa-check' : 'fa-times' }}"></i>
                                {{ $attribute->is_filterable ? __('messages.yes') : __('messages.no') }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: var(--secondary);">{{ $attribute->order }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $attribute->is_active ? 'status-active' : 'status-inactive' }}">
                                <i class="fas {{ $attribute->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $attribute->is_active ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </td>
                        <td class="action-cell" style="text-align: right;">
                            <a href="{{ route('admin.attribute-values.index', $attribute) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-list"></i> {{ __('messages.attribute_values') }}
                            </a>
                            <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" 
                                  onsubmit="handleFormConfirm(event, {
                                      message: '{{ __('messages.delete_attribute_confirm') }}',
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
    @if($attributes->hasPages())
        <div class="pagination-wrapper" style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $attributes->links() }}
        </div>
    @endif
    @else
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-sliders-h"></i>
        </div>
        <h3>{{ __('messages.no_attributes_found') }}</h3>
        <p>{{ __('messages.no_attributes_description') }}</p>
        <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_attribute') }}
        </a>
    </div>
    @endif
</div>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 15px 0; color: #dc2626; font-size: 24px;">
            <i class="fas fa-exclamation-triangle"></i> {{ __('messages.delete_all_attributes') }}
        </h3>
        <p style="margin: 0 0 25px 0; font-size: 16px; color: #4b5563;">
            {{ __('messages.confirm_delete_all_attributes') }}
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
    function showDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'flex';
    }

    function hideDeleteAllModal() {
        document.getElementById('deleteAllModal').style.display = 'none';
    }

    function deleteAllRecords() {
        event.target.disabled = true;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.deleting") }}...';

        fetch('{{ route("admin.attributes.delete-all") }}', {
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
</script>

@endsection
