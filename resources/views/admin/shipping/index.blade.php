@extends('admin.layout')

@section('title', __('messages.shipping_management'))

@section('content')
<style>
    /* Shipping Management Tabs */
    .shipping-tabs {
        display: flex;
        gap: 0;
        background: white;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        box-shadow: var(--shadow-card);
        overflow-x: auto;
        border-bottom: 2px solid #e2e8f0;
    }

    .shipping-tab {
        padding: 16px 28px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        border: none;
        background: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        position: relative;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
    }

    .shipping-tab:hover {
        color: var(--primary);
        background: rgba(37, 99, 235, 0.05);
    }

    .shipping-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: rgba(37, 99, 235, 0.05);
    }

    .shipping-tab .badge {
        background: #e2e8f0;
        color: #475569;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .shipping-tab.active .badge {
        background: var(--primary);
        color: white;
    }

    .tab-content {
        display: none;
        background: white;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        box-shadow: var(--shadow-card);
        padding: 28px;
    }

    .tab-content.active {
        display: block;
    }

    /* Data Table */
    .shipping-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .shipping-table th {
        background: #f8fafc;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        text-align: start;
    }

    .shipping-table td {
        padding: 14px 16px;
        font-size: 14px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .shipping-table tr:hover td {
        background: #f8fafc;
    }

    .shipping-table .actions-cell {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    /* Status Toggle */
    .status-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot.active { background: #22c55e; }
    .status-dot.inactive { background: #ef4444; }

    /* Modal */
    .shipping-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        justify-content: center;
        align-items: flex-start;
        padding-top: 5vh;
        overflow-y: auto;
    }

    .shipping-modal-overlay.show {
        display: flex;
    }

    .shipping-modal {
        background: white;
        border-radius: 16px;
        max-width: 700px;
        width: 95%;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        margin-bottom: 5vh;
    }

    .shipping-modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .shipping-modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }

    .shipping-modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        line-height: 1;
    }

    .shipping-modal-close:hover {
        color: #ef4444;
    }

    .shipping-modal-body {
        padding: 24px;
    }

    .shipping-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Form Grid for Modal */
    .modal-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .modal-form-grid .full-width {
        grid-column: 1 / -1;
    }

    .modal-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .modal-form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .modal-form-group input,
    .modal-form-group select {
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .modal-form-group input:focus,
    .modal-form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .postal-range-preview {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        color: #0369a1;
        font-weight: 600;
        text-align: center;
        font-family: monospace;
    }

    /* Quick Action Buttons */
    .btn-xs {
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-xs-primary { background: #dbeafe; color: #1d4ed8; }
    .btn-xs-primary:hover { background: #bfdbfe; }
    .btn-xs-danger { background: #fee2e2; color: #dc2626; }
    .btn-xs-danger:hover { background: #fecaca; }
    .btn-xs-success { background: #dcfce7; color: #16a34a; }
    .btn-xs-success:hover { background: #bbf7d0; }
    .btn-xs-warning { background: #fef3c7; color: #d97706; }
    .btn-xs-warning:hover { background: #fde68a; }

    /* Settings Form */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .setting-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
    }

    .setting-item label {
        display: block;
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .setting-item .setting-desc {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
    }

    .setting-item input,
    .setting-item select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }

    .setting-item input:focus,
    .setting-item select:focus {
        outline: none;
        border-color: var(--primary);
    }

    /* Checkbox toggle */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
    }

    .toggle-switch input { opacity: 0; width: 0; height: 0; }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #cbd5e1;
        border-radius: 13px;
        transition: 0.3s;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: 0.3s;
    }

    .toggle-switch input:checked + .toggle-slider { background: var(--primary); }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(22px); }

    @media (max-width: 768px) {
        .shipping-tabs { flex-direction: column; }
        .shipping-tab { border-bottom: none; border-left: 3px solid transparent; }
        .shipping-tab.active { border-left-color: var(--primary); border-bottom-color: transparent; }
        .modal-form-grid { grid-template-columns: 1fr; }
        .settings-grid { grid-template-columns: 1fr; }
        .tab-content { padding: 16px; }
        .shipping-table { font-size: 12px; }
        .shipping-table th, .shipping-table td { padding: 10px 8px; }
    }
</style>

<!-- Page Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div>
                <h1>{{ __('messages.shipping_management') }}</h1>
                <p>{{ __('messages.shipping_management_subtitle') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-globe-americas"></i> {{ __('messages.total_regions') }}</h4>
        <div class="stat-value">{{ $stats['total_regions'] }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-city"></i> {{ __('messages.total_cities') }}</h4>
        <div class="stat-value">{{ $stats['total_cities'] }}</div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_cities') }}</h4>
        <div class="stat-value">{{ $stats['active_cities'] }}</div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-ban"></i> {{ __('messages.blocked_ranges') }}</h4>
        <div class="stat-value">{{ $stats['blocked_ranges'] }}</div>
    </div>
</div>

<!-- Tabs -->
<div class="shipping-tabs">
    <button class="shipping-tab active" onclick="switchTab('regions')">
        <i class="fas fa-globe-americas"></i> {{ __('messages.regions') }}
        <span class="badge">{{ $stats['total_regions'] }}</span>
    </button>
    <button class="shipping-tab" onclick="switchTab('cities')">
        <i class="fas fa-city"></i> {{ __('messages.cities') }}
        <span class="badge">{{ $stats['total_cities'] }}</span>
    </button>
    <button class="shipping-tab" onclick="switchTab('blocked')">
        <i class="fas fa-ban"></i> {{ __('messages.blocked_ranges') }}
        <span class="badge">{{ $stats['blocked_ranges'] }}</span>
    </button>
    <button class="shipping-tab" onclick="switchTab('settings')">
        <i class="fas fa-cog"></i> {{ __('messages.settings') }}
    </button>
</div>

<!-- ==================== REGIONS TAB ==================== -->
<div class="tab-content active" id="tab-regions">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 18px; color: #1e293b;">{{ __('messages.shipping_regions') }}</h3>
        <button class="btn-add" onclick="openModal('regionModal')">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_region') }}
        </button>
    </div>

    <table class="shipping-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.key') }}</th>
                <th>{{ __('messages.name') }} (EN)</th>
                <th>{{ __('messages.name') }} (AR)</th>
                <th>{{ __('messages.name') }} (HE)</th>
                <th>{{ __('messages.cities') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.sort_order') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($regions as $region)
                <tr>
                    <td>{{ $region->id }}</td>
                    <td><code>{{ $region->key }}</code></td>
                    <td>{{ $region->name_en }}</td>
                    <td>{{ $region->name_ar }}</td>
                    <td>{{ $region->name_he }}</td>
                    <td><span class="badge">{{ $region->cities->count() }}</span></td>
                    <td>
                        <span class="status-toggle" onclick="toggleStatus('region', {{ $region->id }}, this)">
                            <span class="status-dot {{ $region->is_active ? 'active' : 'inactive' }}"></span>
                            {{ $region->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td>{{ $region->sort_order }}</td>
                    <td class="actions-cell">
                        <button class="btn-xs btn-xs-primary" onclick="editRegion({{ json_encode($region) }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.shipping.regions.destroy', $region) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-xs btn-xs-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center; color: #94a3b8; padding: 40px;">{{ __('messages.no_regions_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ==================== CITIES TAB ==================== -->
<div class="tab-content" id="tab-cities">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 18px; color: #1e293b;">{{ __('messages.shipping_cities') }}</h3>
        <button class="btn-add" onclick="openModal('cityModal')">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_city') }}
        </button>
    </div>

    @foreach($regions as $region)
        <h4 style="margin: 20px 0 12px; color: #475569; font-size: 15px;">
            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
            {{ $region->name_en }} / {{ $region->name_ar }}
            <span class="badge" style="margin-inline-start: 8px;">{{ $region->cities->count() }}</span>
        </h4>
        <table class="shipping-table" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>{{ __('messages.key') }}</th>
                    <th>{{ __('messages.city') }} (EN)</th>
                    <th>{{ __('messages.city') }} (AR)</th>
                    <th>{{ __('messages.governorate') }}</th>
                    <th>{{ __('messages.postal_range') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($region->cities as $city)
                    <tr>
                        <td><code>{{ $city->key }}</code></td>
                        <td>{{ $city->name_en }}</td>
                        <td>{{ $city->name_ar }}</td>
                        <td>{{ $city->governorate_en }}</td>
                        <td><span class="postal-range-preview" style="padding: 4px 10px; font-size: 12px;">{{ $city->postal_range }}</span></td>
                        <td>
                            <span class="status-toggle" onclick="toggleStatus('city', {{ $city->id }}, this)">
                                <span class="status-dot {{ $city->is_active ? 'active' : 'inactive' }}"></span>
                                {{ $city->is_active ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <button class="btn-xs btn-xs-primary" onclick="editCity({{ json_encode($city) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.shipping.cities.destroy', $city) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-xs btn-xs-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color: #94a3b8;">{{ __('messages.no_cities_in_region') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
</div>

<!-- ==================== BLOCKED RANGES TAB ==================== -->
<div class="tab-content" id="tab-blocked">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 18px; color: #1e293b;">{{ __('messages.shipping_blocked_ranges') }}</h3>
        <button class="btn-add" onclick="openModal('blockedModal')">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_blocked_range') }}
        </button>
    </div>

    <table class="shipping-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.postal_range') }}</th>
                <th>{{ __('messages.label') }} (EN)</th>
                <th>{{ __('messages.label') }} (AR)</th>
                <th>{{ __('messages.reason') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blockedRanges as $range)
                <tr>
                    <td>{{ $range->id }}</td>
                    <td><span class="postal-range-preview" style="padding: 4px 10px; font-size: 12px; background: #fef2f2; border-color: #fecaca; color: #dc2626;">{{ $range->range }}</span></td>
                    <td>{{ $range->label_en }}</td>
                    <td>{{ $range->label_ar }}</td>
                    <td>{{ $range->reason_en }}</td>
                    <td>
                        <span class="status-toggle" onclick="toggleStatus('blocked-range', {{ $range->id }}, this)">
                            <span class="status-dot {{ $range->is_active ? 'active' : 'inactive' }}"></span>
                            {{ $range->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td class="actions-cell">
                        <button class="btn-xs btn-xs-primary" onclick="editBlockedRange({{ json_encode($range) }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.shipping.blocked-ranges.destroy', $range) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-xs btn-xs-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center; color: #94a3b8; padding: 40px;">{{ __('messages.no_blocked_ranges') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ==================== SETTINGS TAB ==================== -->
<div class="tab-content" id="tab-settings">
    <h3 style="margin: 0 0 20px; font-size: 18px; color: #1e293b;">{{ __('messages.shipping_settings') }}</h3>
    <form action="{{ route('admin.shipping.settings.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="settings-grid">
            <div class="setting-item">
                <label>{{ __('messages.shipping_country_label') }}</label>
                <div class="setting-desc">{{ __('messages.shipping_country_desc') }}</div>
                <input type="text" name="shipping_country" value="{{ $settings['shipping_country']->value ?? 'Palestine' }}" required>
            </div>
            <div class="setting-item">
                <label>{{ __('messages.postal_digits_label') }}</label>
                <div class="setting-desc">{{ __('messages.postal_digits_desc') }}</div>
                <input type="number" name="postal_code_digits" value="{{ $settings['postal_code_digits']->value ?? 7 }}" min="1" max="10" required>
            </div>
            <div class="setting-item">
                <label>{{ __('messages.free_shipping_threshold_label') }}</label>
                <div class="setting-desc">{{ __('messages.free_shipping_threshold_desc') }}</div>
                <input type="number" name="free_shipping_threshold" value="{{ $settings['free_shipping_threshold']->value ?? 200 }}" min="0" required>
            </div>
            <div class="setting-item">
                <label>{{ __('messages.shipping_fee_label') }}</label>
                <div class="setting-desc">{{ __('messages.shipping_fee_desc') }}</div>
                <input type="number" name="shipping_fee" value="{{ $settings['shipping_fee']->value ?? 25 }}" min="0" required>
            </div>
            <div class="setting-item">
                <label>{{ __('messages.shipping_enabled_label') }}</label>
                <div class="setting-desc">{{ __('messages.shipping_enabled_desc') }}</div>
                <label class="toggle-switch" style="margin-top: 8px;">
                    <input type="checkbox" name="shipping_enabled" value="1" {{ ($settings['shipping_enabled']->value ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        <div style="margin-top: 24px; text-align: end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 32px; font-weight: 700;">
                <i class="fas fa-save"></i> {{ __('messages.save_settings') }}
            </button>
        </div>
    </form>
</div>

<!-- ==================== REGION MODAL ==================== -->
<div class="shipping-modal-overlay" id="regionModal">
    <div class="shipping-modal">
        <div class="shipping-modal-header">
            <h3 id="regionModalTitle">{{ __('messages.add_region') }}</h3>
            <button class="shipping-modal-close" onclick="closeModal('regionModal')">&times;</button>
        </div>
        <form id="regionForm" method="POST" action="{{ route('admin.shipping.regions.store') }}">
            @csrf
            <div id="regionMethodField"></div>
            <div class="shipping-modal-body">
                <div class="modal-form-grid">
                    <div class="modal-form-group">
                        <label>{{ __('messages.key') }} *</label>
                        <input type="text" name="key" id="regionKey" required pattern="[a-z0-9_]+" placeholder="e.g. west_bank">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.sort_order') }}</label>
                        <input type="number" name="sort_order" id="regionSortOrder" value="0" min="0">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.name') }} (EN) *</label>
                        <input type="text" name="name_en" id="regionNameEn" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.name') }} (AR) *</label>
                        <input type="text" name="name_ar" id="regionNameAr" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.name') }} (HE) *</label>
                        <input type="text" name="name_he" id="regionNameHe" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.active') }}</label>
                        <label class="toggle-switch" style="margin-top: 6px;">
                            <input type="checkbox" name="is_active" value="1" id="regionIsActive" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="shipping-modal-footer">
                <button type="button" class="btn" style="background: #e2e8f0; color: #475569;" onclick="closeModal('regionModal')">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== CITY MODAL ==================== -->
<div class="shipping-modal-overlay" id="cityModal">
    <div class="shipping-modal">
        <div class="shipping-modal-header">
            <h3 id="cityModalTitle">{{ __('messages.add_city') }}</h3>
            <button class="shipping-modal-close" onclick="closeModal('cityModal')">&times;</button>
        </div>
        <form id="cityForm" method="POST" action="{{ route('admin.shipping.cities.store') }}">
            @csrf
            <div id="cityMethodField"></div>
            <div class="shipping-modal-body">
                <div class="modal-form-grid">
                    <div class="modal-form-group">
                        <label>{{ __('messages.region') }} *</label>
                        <select name="shipping_region_id" id="cityRegionId" required>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name_en }} / {{ $region->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.key') }} *</label>
                        <input type="text" name="key" id="cityKey" required pattern="[a-z0-9_]+" placeholder="e.g. nablus">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.city') }} (EN) *</label>
                        <input type="text" name="name_en" id="cityNameEn" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.city') }} (AR) *</label>
                        <input type="text" name="name_ar" id="cityNameAr" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.city') }} (HE) *</label>
                        <input type="text" name="name_he" id="cityNameHe" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.governorate') }} (EN) *</label>
                        <input type="text" name="governorate_en" id="cityGovEn" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.governorate') }} (AR) *</label>
                        <input type="text" name="governorate_ar" id="cityGovAr" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.governorate') }} (HE) *</label>
                        <input type="text" name="governorate_he" id="cityGovHe" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.postal_code_min') }} *</label>
                        <input type="number" name="postal_code_min" id="cityPostalMin" min="0" max="999" required oninput="updatePostalPreview()">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.postal_code_max') }} *</label>
                        <input type="number" name="postal_code_max" id="cityPostalMax" min="0" max="999" required oninput="updatePostalPreview()">
                    </div>
                    <div class="modal-form-group full-width">
                        <label>{{ __('messages.postal_range_preview') }}</label>
                        <div class="postal-range-preview" id="postalPreview">P000 – P000</div>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.sort_order') }}</label>
                        <input type="number" name="sort_order" id="citySortOrder" value="0" min="0">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.active') }}</label>
                        <label class="toggle-switch" style="margin-top: 6px;">
                            <input type="checkbox" name="is_active" value="1" id="cityIsActive" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="shipping-modal-footer">
                <button type="button" class="btn" style="background: #e2e8f0; color: #475569;" onclick="closeModal('cityModal')">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== BLOCKED RANGE MODAL ==================== -->
<div class="shipping-modal-overlay" id="blockedModal">
    <div class="shipping-modal">
        <div class="shipping-modal-header">
            <h3 id="blockedModalTitle">{{ __('messages.add_blocked_range') }}</h3>
            <button class="shipping-modal-close" onclick="closeModal('blockedModal')">&times;</button>
        </div>
        <form id="blockedForm" method="POST" action="{{ route('admin.shipping.blocked-ranges.store') }}">
            @csrf
            <div id="blockedMethodField"></div>
            <div class="shipping-modal-body">
                <div class="modal-form-grid">
                    <div class="modal-form-group">
                        <label>{{ __('messages.postal_code_min') }} *</label>
                        <input type="number" name="postal_code_min" id="blockedPostalMin" min="0" max="999" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.postal_code_max') }} *</label>
                        <input type="number" name="postal_code_max" id="blockedPostalMax" min="0" max="999" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.label') }} (EN) *</label>
                        <input type="text" name="label_en" id="blockedLabelEn" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.label') }} (AR) *</label>
                        <input type="text" name="label_ar" id="blockedLabelAr" required>
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.label') }} (HE) *</label>
                        <input type="text" name="label_he" id="blockedLabelHe" required>
                    </div>
                    <div class="modal-form-group full-width">
                        <label>{{ __('messages.reason') }} (EN)</label>
                        <input type="text" name="reason_en" id="blockedReasonEn">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.reason') }} (AR)</label>
                        <input type="text" name="reason_ar" id="blockedReasonAr">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.reason') }} (HE)</label>
                        <input type="text" name="reason_he" id="blockedReasonHe">
                    </div>
                    <div class="modal-form-group">
                        <label>{{ __('messages.active') }}</label>
                        <label class="toggle-switch" style="margin-top: 6px;">
                            <input type="checkbox" name="is_active" value="1" id="blockedIsActive" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="shipping-modal-footer">
                <button type="button" class="btn" style="background: #e2e8f0; color: #475569;" onclick="closeModal('blockedModal')">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ==================== TAB SWITCHING ====================
    function switchTab(tab) {
        document.querySelectorAll('.shipping-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('tab-' + tab).classList.add('active');
    }

    // ==================== MODAL ====================
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // Close modal on overlay click
    document.querySelectorAll('.shipping-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });
    });

    // ==================== POSTAL RANGE PREVIEW ====================
    function updatePostalPreview() {
        const min = document.getElementById('cityPostalMin').value || '0';
        const max = document.getElementById('cityPostalMax').value || '0';
        document.getElementById('postalPreview').textContent =
            'P' + min.padStart(3, '0') + ' – P' + max.padStart(3, '0');
    }

    // ==================== EDIT REGION ====================
    function editRegion(region) {
        document.getElementById('regionModalTitle').textContent = '{{ __('messages.edit_region') }}';
        document.getElementById('regionForm').action = '{{ url("admin/shipping/regions") }}/' + region.id;
        document.getElementById('regionMethodField').innerHTML = '@method("PUT")';
        document.getElementById('regionKey').value = region.key;
        document.getElementById('regionNameEn').value = region.name_en;
        document.getElementById('regionNameAr').value = region.name_ar;
        document.getElementById('regionNameHe').value = region.name_he;
        document.getElementById('regionSortOrder').value = region.sort_order;
        document.getElementById('regionIsActive').checked = region.is_active;
        openModal('regionModal');
    }

    // ==================== EDIT CITY ====================
    function editCity(city) {
        document.getElementById('cityModalTitle').textContent = '{{ __('messages.edit_city') }}';
        document.getElementById('cityForm').action = '{{ url("admin/shipping/cities") }}/' + city.id;
        document.getElementById('cityMethodField').innerHTML = '@method("PUT")';
        document.getElementById('cityRegionId').value = city.shipping_region_id;
        document.getElementById('cityKey').value = city.key;
        document.getElementById('cityNameEn').value = city.name_en;
        document.getElementById('cityNameAr').value = city.name_ar;
        document.getElementById('cityNameHe').value = city.name_he;
        document.getElementById('cityGovEn').value = city.governorate_en;
        document.getElementById('cityGovAr').value = city.governorate_ar;
        document.getElementById('cityGovHe').value = city.governorate_he;
        document.getElementById('cityPostalMin').value = city.postal_code_min;
        document.getElementById('cityPostalMax').value = city.postal_code_max;
        document.getElementById('citySortOrder').value = city.sort_order;
        document.getElementById('cityIsActive').checked = city.is_active;
        updatePostalPreview();
        openModal('cityModal');
    }

    // ==================== EDIT BLOCKED RANGE ====================
    function editBlockedRange(range) {
        document.getElementById('blockedModalTitle').textContent = '{{ __('messages.edit_blocked_range') }}';
        document.getElementById('blockedForm').action = '{{ url("admin/shipping/blocked-ranges") }}/' + range.id;
        document.getElementById('blockedMethodField').innerHTML = '@method("PUT")';
        document.getElementById('blockedPostalMin').value = range.postal_code_min;
        document.getElementById('blockedPostalMax').value = range.postal_code_max;
        document.getElementById('blockedLabelEn').value = range.label_en;
        document.getElementById('blockedLabelAr').value = range.label_ar;
        document.getElementById('blockedLabelHe').value = range.label_he;
        document.getElementById('blockedReasonEn').value = range.reason_en || '';
        document.getElementById('blockedReasonAr').value = range.reason_ar || '';
        document.getElementById('blockedReasonHe').value = range.reason_he || '';
        document.getElementById('blockedIsActive').checked = range.is_active;
        openModal('blockedModal');
    }

    // ==================== TOGGLE STATUS (AJAX) ====================
    function toggleStatus(type, id, el) {
        const url = '{{ url("admin/shipping") }}/' + type + 's/' + id + '/toggle-status';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const dot = el.querySelector('.status-dot');
                dot.classList.toggle('active');
                dot.classList.toggle('inactive');
                el.childNodes[el.childNodes.length - 1].textContent = ' ' + (data.is_active ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}');
            }
        })
        .catch(err => console.error(err));
    }

    // ==================== RESET MODALS ON CLOSE ====================
    function resetRegionModal() {
        document.getElementById('regionModalTitle').textContent = '{{ __('messages.add_region') }}';
        document.getElementById('regionForm').action = '{{ route('admin.shipping.regions.store') }}';
        document.getElementById('regionMethodField').innerHTML = '';
        document.getElementById('regionForm').reset();
        document.getElementById('regionIsActive').checked = true;
    }

    function resetCityModal() {
        document.getElementById('cityModalTitle').textContent = '{{ __('messages.add_city') }}';
        document.getElementById('cityForm').action = '{{ route('admin.shipping.cities.store') }}';
        document.getElementById('cityMethodField').innerHTML = '';
        document.getElementById('cityForm').reset();
        document.getElementById('cityIsActive').checked = true;
        document.getElementById('postalPreview').textContent = 'P000 – P000';
    }

    function resetBlockedModal() {
        document.getElementById('blockedModalTitle').textContent = '{{ __('messages.add_blocked_range') }}';
        document.getElementById('blockedForm').action = '{{ route('admin.shipping.blocked-ranges.store') }}';
        document.getElementById('blockedMethodField').innerHTML = '';
        document.getElementById('blockedForm').reset();
        document.getElementById('blockedIsActive').checked = true;
    }

    // Reset modals when add buttons are clicked
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', function() {
            resetRegionModal();
            resetCityModal();
            resetBlockedModal();
        });
    });
</script>

@endsection
