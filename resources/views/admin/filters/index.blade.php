@extends('admin.layout')

@section('title', __('messages.filters_management'))

@section('content')
<style>
    .action-cell {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: nowrap;
    }
    .action-cell form { display: inline-flex; margin: 0; }
    .action-cell .btn { padding: 7px 14px; font-size: 12px; flex-shrink: 0; white-space: nowrap; }
    [dir="rtl"] .action-cell { justify-content: flex-start; }

    .filter-type-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    [dir="rtl"] .filter-type-badge { text-transform: none; letter-spacing: normal; }
    .type-checkbox { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; }
    .type-radio { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; }
    .type-range { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; }
    .type-min_max { background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); color: #5b21b6; }
    .type-boolean { background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); color: #9d174d; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 6px 12px; border-radius: 8px;
        font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    [dir="rtl"] .status-badge { text-transform: none; letter-spacing: normal; }
    .status-active { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; }
    .status-inactive { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #7f1d1d; }

    .count-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 8px; font-size: 12px; font-weight: 700; color: var(--primary);
    }

    .filter-name { font-weight: 700; color: var(--dark); font-size: 14px; }
    .filter-slug {
        font-size: 12px; color: var(--secondary); font-family: 'Courier New', monospace;
        background: #f1f5f9; padding: 3px 8px; border-radius: 4px; font-weight: 600;
    }

    .header-actions { display: flex; gap: 12px; align-items: center; }
    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--accent-emerald) 0%, #059669 100%);
        color: white; border-radius: 10px; font-weight: 700; font-size: 14px;
        text-decoration: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
        background: linear-gradient(135deg, #059669 0%, var(--accent-emerald) 100%);
    }

    [dir="rtl"] .admin-table th, [dir="rtl"] .admin-table td { text-align: right; }
    [dir="rtl"] .admin-table th:last-child, [dir="rtl"] .admin-table td:last-child { text-align: left; }

    /* Filter Display Settings */
    .filter-settings-form { padding: 1rem 0; }
    .filter-settings-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
    .filter-setting-row {
        display: grid;
        grid-template-columns: auto 1fr;
        grid-template-rows: auto auto;
        gap: 0.5rem 1rem;
        padding: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        align-items: start;
    }
    .filter-setting-order { grid-row: 1 / -1; }
    .filter-setting-toggle { display: flex; align-items: center; gap: 0.75rem; }
    .filter-setting-label { font-weight: 600; color: #1e293b; }
    .filter-setting-help { font-size: 12px; color: #64748b; grid-column: 2; }
    .toggle-switch { position: relative; display: inline-block; width: 48px; height: 26px; flex-shrink: 0; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background: #cbd5e1; border-radius: 26px; transition: 0.3s;
    }
    .toggle-slider:before {
        position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px;
        background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-switch input:checked + .toggle-slider { background: #10b981; }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(22px); }

    @media (max-width: 768px) {
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions .btn, .header-actions .btn-add { width: 100%; justify-content: center; }
        .action-cell { gap: 4px; }
        .action-cell .btn { padding: 5px 8px; font-size: 11px; }
    }
</style>

<!-- Page Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-filter"></i>
            </div>
            <div>
                <h1>{{ __('messages.filters_management') }}</h1>
                <p>{{ __('messages.all_filters') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.filters.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.create_filter') }}
            </a>
        </div>
    </div>
</div>

<!-- Filter Display Settings (built-in sections) -->
<div class="admin-table-container" style="margin-bottom: 1.5rem;">
    <div class="admin-table-header">
        <h3><i class="fas fa-sliders-h"></i> {{ __('messages.filter_display_settings') }}</h3>
    </div>
    <form action="{{ route('admin.filters.section-settings') }}" method="POST" class="filter-settings-form">
        @csrf
        <p style="color: #64748b; font-size: 13px; margin-bottom: 1rem;">{{ __('messages.filter_display_settings_help') }}</p>
        <div class="filter-settings-grid">
            @php
                $sectionLabels = [
                    'status' => ['label' => __('messages.section_status'), 'help' => __('messages.section_status_help'), 'icon' => 'fa-box'],
                    'strong_offers' => ['label' => __('messages.section_strong_offers'), 'help' => __('messages.section_strong_offers_help'), 'icon' => 'fa-fire'],
                    'brand' => ['label' => __('messages.section_brand'), 'help' => __('messages.section_brand_help'), 'icon' => 'fa-award'],
                    'price' => ['label' => __('messages.section_price'), 'help' => __('messages.section_price_help'), 'icon' => 'fa-dollar-sign'],
                ];
                $sorted = $sectionSettings->sortBy('sort_order');
            @endphp
            @foreach($sorted as $section)
                @php $meta = $sectionLabels[$section->section_key] ?? ['label' => $section->section_key, 'help' => '', 'icon' => 'fa-filter']; @endphp
                <div class="filter-setting-row">
                    <div class="filter-setting-order">
                        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">{{ __('messages.filter_sort_order') }}</label>
                        <input type="number" name="sections[{{ $loop->index }}][sort_order]" value="{{ $section->sort_order }}" min="0" max="999" class="form-control" style="width: 70px;">
                    </div>
                    <div class="filter-setting-toggle">
                        <label class="toggle-switch">
                            <input type="hidden" name="sections[{{ $loop->index }}][enabled]" value="0">
                            <input type="checkbox" name="sections[{{ $loop->index }}][enabled]" value="1" {{ $section->is_enabled ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="filter-setting-label">{{ $meta['label'] }}</span>
                    </div>
                    <input type="hidden" name="sections[{{ $loop->index }}][key]" value="{{ $section->section_key }}">
                    <div class="filter-setting-help">{{ $meta['help'] }}</div>
                </div>
            @endforeach
        </div>
        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('messages.save') }} {{ __('messages.filter_display_settings') }}
            </button>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-filter"></i> {{ __('messages.total_filters') }}</h4>
        <div class="stat-value">{{ $totalFilters }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_filters') }}</h4>
        <div class="stat-value">{{ $activeFilters }}</div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-times-circle"></i> {{ __('messages.inactive_filters') }}</h4>
        <div class="stat-value">{{ $inactiveFilters }}</div>
    </div>
</div>

<!-- Search -->
<div class="admin-table-container">
    <div class="admin-table-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <h3><i class="fas fa-list"></i> {{ __('messages.all_filters') }}</h3>
        <form method="GET" action="{{ route('admin.filters.index') }}" style="display:flex;gap:8px;align-items:center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search') }}..." class="form-control" style="max-width:250px;">
            <button type="submit" class="btn btn-primary" style="padding:8px 16px;">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    @if($filters->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.filter_title') }}</th>
                    <th>{{ __('messages.filter_slug') }}</th>
                    <th>{{ __('messages.filter_type') }}</th>
                    <th>{{ __('messages.filter_options') }}</th>
                    <th>{{ __('messages.filter_assignments') }}</th>
                    <th>{{ __('messages.filter_sort_order') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th style="text-align: right;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filters as $filter)
                    <tr>
                        <td>
                            <div class="filter-name">{{ $filter->title_en }}</div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                @if($filter->title_ar) AR: {{ $filter->title_ar }} @endif
                                @if($filter->title_he) | HE: {{ $filter->title_he }} @endif
                            </div>
                            @if($filter->description_en)
                                <div style="font-size: 11px; color: #64748b; margin-top: 4px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $filter->description_en }}
                                </div>
                            @endif
                        </td>
                        <td><span class="filter-slug">{{ $filter->slug }}</span></td>
                        <td>
                            <span class="filter-type-badge type-{{ $filter->type }}">{{ strtoupper(str_replace('_', '/', $filter->type)) }}</span>
                        </td>
                        <td>
                            <span class="count-badge">
                                <i class="fas fa-list-ul"></i> {{ __('messages.options_count', ['count' => $filter->options_count]) }}
                            </span>
                        </td>
                        <td>
                            <span class="count-badge">
                                <i class="fas fa-folder"></i> {{ __('messages.assignments_count', ['count' => $filter->assignments_count]) }}
                            </span>
                        </td>
                        <td>{{ $filter->sort_order }}</td>
                        <td>
                            @if($filter->is_active)
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle"></i> {{ __('messages.active_label') }}
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-times-circle"></i> {{ __('messages.inactive') ?? 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('admin.filters.edit', $filter) }}" class="btn btn-primary" title="{{ __('messages.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.filters.destroy', $filter) }}" method="POST"
                                      onsubmit="return confirm('{{ __('messages.confirm_delete_filter') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="{{ __('messages.delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($filters->hasPages())
        <div class="pagination-wrapper" style="padding: 20px; display: flex; justify-content: center;">
            {{ $filters->links() }}
        </div>
    @endif

    @else
    <div style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;">
            <i class="fas fa-filter"></i>
        </div>
        <h3 style="color: #64748b; margin-bottom: 8px;">{{ __('messages.no_filters') }}</h3>
        <a href="{{ route('admin.filters.create') }}" class="btn-add" style="margin-top: 16px; display: inline-flex;">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_filter') }}
        </a>
    </div>
    @endif
</div>
@endsection
