@extends('admin.layout')

@section('title', __('messages.home_sections_management'))

@section('content')
<style>
    .section-row {
        cursor: grab;
        transition: all 0.2s ease;
    }
    .section-row:active {
        cursor: grabbing;
    }
    .section-row.sortable-ghost {
        opacity: 0.4;
        background: #eff6ff !important;
    }
    .section-row.sortable-chosen {
        background: #f0f9ff !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .drag-handle {
        cursor: grab;
        color: var(--secondary);
        font-size: 1.1rem;
        padding: 0.5rem;
        transition: color 0.2s ease;
    }
    .drag-handle:hover {
        color: var(--primary);
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    .section-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
    }
    .section-type-badge i {
        font-size: 0.85rem;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 24px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: var(--success);
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }
    .order-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .section-title-cell {
        max-width: 250px;
    }
    .section-title-text {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }
    .section-subtitle-text {
        font-size: 0.8rem;
        color: var(--secondary);
        margin-top: 2px;
    }
    .reorder-save-bar {
        display: none;
        position: fixed;
        bottom: 0;
        left: 260px;
        right: 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 1rem 2rem;
        z-index: 999;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .reorder-save-bar.show {
        display: flex;
    }
    .reorder-save-bar .btn {
        text-transform: none;
    }
    [dir="rtl"] .reorder-save-bar {
        left: 0;
        right: 260px;
    }
    @media (max-width: 768px) {
        .reorder-save-bar {
            left: 0;
            right: 0;
        }
        [dir="rtl"] .reorder-save-bar {
            left: 0;
            right: 0;
        }
    }
</style>

<!-- Hero Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <div>
                <h1>{{ __('messages.home_sections_management') }}</h1>
                <p>{{ __('messages.home_sections_subtitle') }}</p>
            </div>
        </div>
        <div class="page-actions">
            @if(auth()->user()->hasPermission('home_sections.create'))
            <a href="{{ route('admin.home-sections.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('messages.add_section') }}
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
        <h4>{{ __('messages.total_sections') }}</h4>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <div class="stat-icon"><i class="fas fa-eye"></i></div>
        <h4>{{ __('messages.active_sections') }}</h4>
        <div class="stat-value">{{ $stats['active'] }}</div>
    </div>
    <div class="admin-stat-card stat-warning">
        <div class="stat-icon"><i class="fas fa-eye-slash"></i></div>
        <h4>{{ __('messages.inactive_sections') }}</h4>
        <div class="stat-value">{{ $stats['inactive'] }}</div>
    </div>
</div>

<!-- Sections Table -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list-ol"></i> {{ __('messages.section_list') }}</h3>
        <span style="font-size: 0.85rem; color: var(--secondary);">
            <i class="fas fa-arrows-alt"></i> {{ __('messages.drag_to_reorder') }}
        </span>
    </div>
    <div class="admin-table-body">
        @if($sections->count() > 0)
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;"></th>
                    <th style="width: 50px;">#</th>
                    <th>{{ __('messages.section_type') }}</th>
                    <th>{{ __('messages.title') }}</th>
                    <th style="width: 100px;">{{ __('messages.status') }}</th>
                    <th style="width: 140px;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody id="sortableSections">
                @foreach($sections as $section)
                <tr class="section-row" data-id="{{ $section->id }}" data-type="{{ $section->type }}">
                    <td>
                        @if($section->type === \App\Models\HomeSection::TYPE_HERO_BANNER)
                            <span style="color: #cbd5e1; cursor: not-allowed;"><i class="fas fa-grip-vertical"></i></span>
                        @else
                            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                        @endif
                    </td>
                    <td>
                        <span class="order-number">{{ $section->display_order }}</span>
                    </td>
                    <td>
                        <span class="section-type-badge">
                            <i class="{{ \App\Models\HomeSection::getTypeIcon($section->type) }}"></i>
                            {{ \App\Models\HomeSection::getTypeLabel($section->type) }}
                        </span>
                    </td>
                    <td class="section-title-cell">
                        @if($section->title_en || $section->title_ar || $section->title_he)
                            <div class="section-title-text">{{ $section->title_en ?: ($section->title_ar ?: $section->title_he) }}</div>
                            @if($section->subtitle_en || $section->subtitle_ar)
                                <div class="section-subtitle-text">{{ Str::limit($section->subtitle_en ?: $section->subtitle_ar, 50) }}</div>
                            @endif
                        @else
                            <span style="color: var(--secondary); font-style: italic;">{{ __('messages.no_title') }}</span>
                        @endif
                    </td>
                    <td>
                        <label class="toggle-switch">
                            <input type="checkbox" {{ $section->is_active ? 'checked' : '' }}
                                onchange="toggleSection({{ $section->id }}, this)">
                            <span class="toggle-slider"></span>
                        </label>
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if(in_array($section->type, [\App\Models\HomeSection::TYPE_HERO_BANNER, \App\Models\HomeSection::TYPE_CATEGORY_CAROUSEL]))
                                <span style="color: var(--secondary); font-size: 0.8rem; font-style: italic;">{{ __('messages.built_in_section') }}</span>
                            @else
                                @if(auth()->user()->hasPermission('home_sections.edit'))
                                <a href="{{ route('admin.home-sections.edit', $section) }}" class="btn btn-primary btn-sm" title="{{ __('messages.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('home_sections.delete'))
                                <form action="{{ route('admin.home-sections.destroy', $section) }}" method="POST"
                                    onsubmit="return confirm('{{ __('messages.delete_section_confirm') }}');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="{{ __('messages.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <h3>{{ __('messages.no_sections_found') }}</h3>
            <p>{{ __('messages.no_sections_description') }}</p>
            @if(auth()->user()->hasPermission('home_sections.create'))
            <a href="{{ route('admin.home-sections.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('messages.add_section') }}
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Reorder Save Bar -->
<div class="reorder-save-bar" id="reorderBar">
    <span><i class="fas fa-info-circle"></i> {{ __('messages.reorder_unsaved') }}</span>
    <div style="display: flex; gap: 0.75rem;">
        <button class="btn btn-secondary" onclick="cancelReorder()">{{ __('messages.cancel') }}</button>
        <button class="btn btn-success" onclick="saveReorder()" id="saveReorderBtn">
            <i class="fas fa-save"></i> {{ __('messages.save_order') }}
        </button>
    </div>
</div>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let originalOrder = [];
    let sortable = null;

    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('sortableSections');
        if (!el) return;

        // Store original order
        originalOrder = getOrder();

        sortable = Sortable.create(el, {
            handle: '.drag-handle',
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            filter: '[data-type="hero_banner"]',
            onMove: function(evt) {
                // Prevent dropping above hero banner (index 0)
                if (evt.related && evt.related.dataset.type === 'hero_banner') return false;
            },
            onEnd: function() {
                // Ensure hero banner stays at top
                const heroBannerRow = el.querySelector('[data-type="hero_banner"]');
                if (heroBannerRow && heroBannerRow !== el.firstElementChild) {
                    el.insertBefore(heroBannerRow, el.firstElementChild);
                }
                const newOrder = getOrder();
                const changed = JSON.stringify(newOrder) !== JSON.stringify(originalOrder);
                document.getElementById('reorderBar').classList.toggle('show', changed);
                updateOrderNumbers();
            }
        });
    });

    function getOrder() {
        return Array.from(document.querySelectorAll('.section-row')).map(r => parseInt(r.dataset.id));
    }

    function updateOrderNumbers() {
        document.querySelectorAll('.section-row .order-number').forEach((el, i) => {
            el.textContent = i;
        });
    }

    function cancelReorder() {
        // Restore original DOM order
        const tbody = document.getElementById('sortableSections');
        const rows = Array.from(tbody.querySelectorAll('.section-row'));
        originalOrder.forEach(id => {
            const row = rows.find(r => parseInt(r.dataset.id) === id);
            if (row) tbody.appendChild(row);
        });
        updateOrderNumbers();
        document.getElementById('reorderBar').classList.remove('show');
    }

    function saveReorder() {
        const btn = document.getElementById('saveReorderBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.saving") }}';

        fetch('{{ route("admin.home-sections.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order: getOrder() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                originalOrder = getOrder();
                document.getElementById('reorderBar').classList.remove('show');
                // Show success via page reload to reset flash
                window.location.reload();
            } else {
                alert(data.message || 'Error saving order');
            }
        })
        .catch(() => alert('Error saving order'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> {{ __("messages.save_order") }}';
        });
    }

    function toggleSection(id, checkbox) {
        fetch('/admin/home-sections/' + id + '/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                checkbox.checked = !checkbox.checked;
                alert(data.message || 'Error');
            }
        })
        .catch(() => {
            checkbox.checked = !checkbox.checked;
        });
    }
</script>
@endsection
