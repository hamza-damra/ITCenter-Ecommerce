@extends('admin.layout')

@section('title', 'Attribute Values - ' . $attribute->name_en)

@section('content')
<style>
    .values-table {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .values-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .values-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid var(--border);
    }

    .values-table th {
        padding: 16px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [dir="rtl"] .values-table th {
        text-align: right;
        text-transform: none;
        letter-spacing: normal;
    }

    .values-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        color: var(--secondary);
    }

    [dir="rtl"] .values-table td {
        text-align: right;
    }

    .values-table tbody tr:hover {
        background: #f8fafc;
    }

    .value-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .value-slug {
        font-size: 12px;
        color: var(--secondary);
        font-family: monospace;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .color-preview {
        display: inline-block;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        border: 2px solid #e5e7eb;
        vertical-align: middle;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-buttons .btn {
        padding: 6px 12px;
        font-size: 12px;
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

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: var(--secondary);
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb i {
        font-size: 10px;
    }

    .attribute-info {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid var(--border);
    }

    .attribute-info h2 {
        margin: 0 0 8px 0;
        color: var(--dark);
        font-size: 20px;
    }

    .attribute-info .meta {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: var(--secondary);
    }

    .attribute-info .meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.attributes.index') }}">
        <i class="fas fa-tags"></i> Attributes
    </a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $attribute->name_en }} Values</span>
</div>

<!-- Attribute Info -->
<div class="attribute-info">
    <h2>{{ $attribute->name_en }}</h2>
    <div class="meta">
        <span>
            <i class="fas fa-code"></i>
            <strong>Slug:</strong> {{ $attribute->slug }}
        </span>
        <span>
            <i class="fas fa-tag"></i>
            <strong>Type:</strong> {{ str_replace('_', ' ', ucfirst($attribute->type)) }}
        </span>
        @if($attribute->unit)
            <span>
                <i class="fas fa-ruler"></i>
                <strong>Unit:</strong> {{ $attribute->unit }}
            </span>
        @endif
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1>Attribute Values</h1>
        <p>Manage values for {{ $attribute->name_en }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.attribute-values.create', $attribute) }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Add New Value
        </a>
    </div>
</div>

<!-- Values Table -->
@if($values->count() > 0)
    <div class="values-table">
        <table>
            <thead>
                <tr>
                    <th>Value</th>
                    <th>Slug</th>
                    @if($attribute->type === 'color')
                        <th>Color</th>
                    @endif
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($values as $value)
                    <tr>
                        <td>
                            <div class="value-name">{{ $value->value_en }}</div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                AR: {{ $value->value_ar }} | HE: {{ $value->value_he }}
                            </div>
                        </td>
                        <td>
                            <span class="value-slug">{{ $value->slug }}</span>
                        </td>
                        @if($attribute->type === 'color')
                            <td>
                                @if($value->color_code)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span class="color-preview" style="background-color: {{ $value->color_code }};"></span>
                                        <span style="font-family: monospace; font-size: 12px;">{{ $value->color_code }}</span>
                                    </div>
                                @else
                                    <span style="color: #cbd5e1;">—</span>
                                @endif
                            </td>
                        @endif
                        <td>
                            <span style="font-weight: 600; color: var(--secondary);">{{ $value->order }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $value->is_active ? 'status-active' : 'status-inactive' }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i>
                                {{ $value->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.attribute-values.edit', [$attribute, $value]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.attribute-values.destroy', [$attribute, $value]) }}" method="POST" 
                                      onsubmit="handleFormConfirm(event, {
                                          message: 'Are you sure you want to delete this value? This will remove it from all products.',
                                          confirmText: 'Yes, Delete',
                                          type: 'danger',
                                          confirmButtonType: 'danger'
                                      })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($values->hasPages())
        <div style="margin-top: 24px;">
            {{ $values->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-list"></i>
        <h3>No Values Found</h3>
        <p>Start by creating values for this attribute</p>
        <a href="{{ route('admin.attribute-values.create', $attribute) }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Create First Value
        </a>
    </div>
@endif

@endsection
