@extends('admin.layout')

@section('title', __('messages.create_role'))

@section('content')
<style>
    .permission-groups {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .permission-group-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .permission-group-card:hover {
        box-shadow: var(--shadow-card-hover);
    }

    .permission-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        user-select: none;
        transition: background 0.2s ease;
    }

    .permission-group-card.collapsed .permission-group-header {
        border-bottom: none;
    }

    .permission-group-header:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }

    .permission-group-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 15px;
        font-weight: 700;
        color: var(--dark);
    }

    .permission-group-title i.group-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 8px;
        font-size: 14px;
    }

    .permission-group-title .chevron-icon {
        font-size: 12px;
        color: var(--secondary);
        transition: transform 0.3s ease;
        margin-left: 4px;
    }

    .permission-group-card.collapsed .chevron-icon {
        transform: rotate(-90deg);
    }

    html[dir="rtl"] .permission-group-card.collapsed .chevron-icon {
        transform: rotate(90deg);
    }

    .perm-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        padding: 0 7px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        background: #e2e8f0;
        color: var(--secondary);
        margin-left: 8px;
    }

    .perm-count-badge.has-selected {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .select-all-group {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        cursor: pointer;
    }

    .select-all-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .permission-group-body {
        padding: 16px 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
        max-height: 500px;
        overflow: hidden;
        transition: max-height 0.35s ease, padding 0.35s ease, opacity 0.25s ease;
        opacity: 1;
    }

    .permission-group-card.collapsed .permission-group-body {
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
        opacity: 0;
    }

    .permission-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .permission-checkbox:hover {
        background: #eff6ff;
        border-color: var(--primary);
    }

    .permission-checkbox.checked {
        background: #eff6ff;
        border-color: var(--primary);
    }

    .permission-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
        cursor: pointer;
        flex-shrink: 0;
    }

    .permission-checkbox label {
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .permission-checkbox .perm-action {
        font-size: 11px;
        color: var(--secondary);
        font-weight: 500;
        display: block;
        margin-top: 2px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .form-actions .btn {
        padding: 12px 28px;
    }

    @media (max-width: 768px) {
        .permission-group-body {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h1>{{ __('messages.create_role') }}</h1>
                <p>{{ __('messages.create_role_subtitle') }}</p>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h2><i class="fas fa-info-circle" style="color: var(--primary);"></i> {{ __('messages.role_information') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.role_name') }} <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('messages.role_name_placeholder') }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.status') }}</label>
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active">{{ __('messages.role_active_description') }}</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.description') }}</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="{{ __('messages.role_description_placeholder') }}">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2><i class="fas fa-key" style="color: var(--primary);"></i> {{ __('messages.permissions') }}</h2>
            <label class="select-all-group">
                <input type="checkbox" id="selectAllPermissions" onclick="toggleAllPermissions(this)">
                {{ __('messages.select_all') }}
            </label>
        </div>
        <div class="card-body">
            @error('permissions')
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ $message }}</div>
                </div>
            @enderror

            <div class="permission-groups">
                @foreach($permissionGroups as $groupKey => $group)
                    @if(empty($group['admin_only']))
                    @php
                        $oldPerms = old('permissions', []);
                        $groupHasChecked = false;
                        foreach ($group['permissions'] as $pk => $pl) {
                            if (in_array($pk, $oldPerms)) { $groupHasChecked = true; break; }
                        }
                        $checkedCount = 0;
                        foreach ($group['permissions'] as $pk => $pl) {
                            if (in_array($pk, $oldPerms)) { $checkedCount++; }
                        }
                        $totalCount = count($group['permissions']);
                    @endphp
                    <div class="permission-group-card {{ $groupHasChecked ? '' : 'collapsed' }}" data-group-card="{{ $groupKey }}">
                        <div class="permission-group-header" onclick="toggleGroupCollapse('{{ $groupKey }}')">
                            <div class="permission-group-title">
                                <i class="{{ $group['icon'] ?? 'fas fa-cog' }} group-icon"></i>
                                {{ __($group['label']) }}
                                <span class="perm-count-badge {{ $checkedCount > 0 ? 'has-selected' : '' }}" id="badge-{{ $groupKey }}">{{ $checkedCount }}/{{ $totalCount }}</span>
                                <i class="fas fa-chevron-down chevron-icon"></i>
                            </div>
                            <label class="select-all-group" onclick="event.stopPropagation()">
                                <input type="checkbox" class="group-select-all" data-group="{{ $groupKey }}" onclick="toggleGroup(this, '{{ $groupKey }}')">
                                {{ __('messages.select_all') }}
                            </label>
                        </div>
                        <div class="permission-group-body">
                            @foreach($group['permissions'] as $permKey => $permLabel)
                                @php
                                    $action = ucfirst(explode('.', $permKey)[1] ?? $permKey);
                                    $groupLabel = __($group['label']);
                                @endphp
                                <div class="permission-checkbox {{ in_array($permKey, old('permissions', [])) ? 'checked' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $permKey }}" id="perm_{{ $permKey }}"
                                        class="perm-checkbox perm-group-{{ $groupKey }}"
                                        data-group="{{ $groupKey }}"
                                        {{ in_array($permKey, old('permissions', [])) ? 'checked' : '' }}
                                        onchange="updatePermissionState(this)">
                                    <label for="perm_{{ $permKey }}">
                                        {{ __($permLabel) }} {{ $groupLabel }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('messages.create_role') }}
        </button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
        </a>
    </div>
</form>

<script>
    function updatePermissionState(checkbox) {
        const wrapper = checkbox.closest('.permission-checkbox');
        if (checkbox.checked) {
            wrapper.classList.add('checked');
        } else {
            wrapper.classList.remove('checked');
        }
        updateGroupSelectAll(checkbox.dataset.group);
        updateCountBadge(checkbox.dataset.group);
        updateGlobalSelectAll();
    }

    function toggleGroup(selectAllCheckbox, groupKey) {
        const isChecked = selectAllCheckbox.checked;
        const checkboxes = document.querySelectorAll('.perm-group-' + groupKey);
        checkboxes.forEach(cb => {
            cb.checked = isChecked;
            const wrapper = cb.closest('.permission-checkbox');
            if (isChecked) {
                wrapper.classList.add('checked');
            } else {
                wrapper.classList.remove('checked');
            }
        });
        updateCountBadge(groupKey);
        updateGlobalSelectAll();
    }

    function toggleGroupCollapse(groupKey) {
        const card = document.querySelector('[data-group-card="' + groupKey + '"]');
        if (card) {
            card.classList.toggle('collapsed');
        }
    }

    function toggleAllPermissions(selectAllCheckbox) {
        const isChecked = selectAllCheckbox.checked;
        const allCheckboxes = document.querySelectorAll('.perm-checkbox');
        const allGroupSelects = document.querySelectorAll('.group-select-all');

        allCheckboxes.forEach(cb => {
            cb.checked = isChecked;
            const wrapper = cb.closest('.permission-checkbox');
            if (isChecked) {
                wrapper.classList.add('checked');
            } else {
                wrapper.classList.remove('checked');
            }
        });

        allGroupSelects.forEach(cb => {
            cb.checked = isChecked;
        });

        // Update all badges
        const groups = new Set();
        allCheckboxes.forEach(cb => groups.add(cb.dataset.group));
        groups.forEach(g => updateCountBadge(g));
    }

    function updateGroupSelectAll(groupKey) {
        const checkboxes = document.querySelectorAll('.perm-group-' + groupKey);
        const groupSelectAll = document.querySelector('.group-select-all[data-group="' + groupKey + '"');
        if (!groupSelectAll) return;

        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        groupSelectAll.checked = allChecked;
    }

    function updateGlobalSelectAll() {
        const allCheckboxes = document.querySelectorAll('.perm-checkbox');
        const globalSelectAll = document.getElementById('selectAllPermissions');
        if (!globalSelectAll) return;

        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        globalSelectAll.checked = allChecked;
    }

    function updateCountBadge(groupKey) {
        const badge = document.getElementById('badge-' + groupKey);
        if (!badge) return;
        const checkboxes = document.querySelectorAll('.perm-group-' + groupKey);
        const checked = Array.from(checkboxes).filter(cb => cb.checked).length;
        const total = checkboxes.length;
        badge.textContent = checked + '/' + total;
        if (checked > 0) {
            badge.classList.add('has-selected');
        } else {
            badge.classList.remove('has-selected');
        }
    }

    // Initialize states on page load
    document.addEventListener('DOMContentLoaded', function() {
        const groups = new Set();
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            groups.add(cb.dataset.group);
        });
        groups.forEach(g => {
            updateGroupSelectAll(g);
            updateCountBadge(g);
        });
        updateGlobalSelectAll();
    });
</script>
@endsection
