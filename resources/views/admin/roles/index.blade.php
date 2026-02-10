@extends('admin.layout')

@section('title', __('messages.role_management'))

@section('content')
<style>
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .role-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        border: none;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .role-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card-hover);
    }

    .role-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px;
        border-bottom: 1px solid var(--border);
    }

    .role-card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .role-card-header h3 i {
        color: var(--primary);
    }

    .role-card-header p {
        font-size: 13px;
        color: var(--secondary);
        margin: 0;
        line-height: 1.5;
    }

    .role-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .role-meta {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .role-meta-badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .role-status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .role-status-inactive {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .role-employees-badge {
        background: #eff6ff;
        color: #1e40af;
    }

    .role-permissions-count {
        background: #f5f3ff;
        color: #6d28d9;
    }

    .role-permissions-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .perm-tag {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 500;
    }

    .perm-tag-more {
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
    }

    .role-card-footer {
        padding: 12px 20px;
        background: #f8fafc;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .role-card-footer .btn {
        flex: 1;
        min-width: 80px;
        padding: 8px 12px;
        font-size: 13px;
    }

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

    @media (max-width: 768px) {
        .roles-grid {
            grid-template-columns: 1fr;
        }

        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .header-actions .btn-add {
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
                <h1>{{ __('messages.role_management') }}</h1>
                <p>{{ __('messages.role_management_subtitle') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.roles.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.create_role') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
@php
    $totalRoles = $roles->total();
    $activeRoles = $roles->where('is_active', true)->count();
    $totalEmployees = \App\Models\User::where('role', 'employee')->count();
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-shield-alt"></i> {{ __('messages.total_roles') }}</h4>
        <div class="stat-value">{{ $totalRoles }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_roles') }}</h4>
        <div class="stat-value">{{ $activeRoles }}</div>
    </div>
    <div class="admin-stat-card stat-violet">
        <h4><i class="fas fa-users"></i> {{ __('messages.total_employees') }}</h4>
        <div class="stat-value">{{ $totalEmployees }}</div>
    </div>
</div>

<!-- Roles Grid -->
<div class="roles-grid">
    @forelse($roles as $role)
        <div class="role-card">
            <div class="role-card-header">
                <h3>
                    <i class="fas fa-shield-alt"></i>
                    {{ $role->name }}
                </h3>
                @if($role->description)
                    <p>{{ \Illuminate\Support\Str::limit($role->description, 80) }}</p>
                @endif
            </div>

            <div class="role-card-body">
                <div class="role-meta">
                    <span class="role-meta-badge {{ $role->is_active ? 'role-status-active' : 'role-status-inactive' }}">
                        <i class="fas {{ $role->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $role->is_active ? __('messages.active') : __('messages.inactive') }}
                    </span>
                    <span class="role-meta-badge role-employees-badge">
                        <i class="fas fa-users"></i>
                        {{ $role->employees_count }} {{ __('messages.employees_label') }}
                    </span>
                    <span class="role-meta-badge role-permissions-count">
                        <i class="fas fa-key"></i>
                        {{ count($role->permissions ?? []) }} {{ __('messages.permissions_label') }}
                    </span>
                </div>

                <div class="role-permissions-preview">
                    @php
                        $perms = $role->permissions ?? [];
                        $shown = array_slice($perms, 0, 5);
                        $remaining = count($perms) - count($shown);
                    @endphp
                    @foreach($shown as $perm)
                        <span class="perm-tag">{{ $perm }}</span>
                    @endforeach
                    @if($remaining > 0)
                        <span class="perm-tag perm-tag-more">+{{ $remaining }} {{ __('messages.more') }}</span>
                    @endif
                </div>
            </div>

            <div class="role-card-footer">
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                </a>
                @if($role->employees_count === 0)
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="flex: 1;"
                          onsubmit="handleFormConfirm(event, {
                              message: '{{ __('messages.confirm_delete_role') }}',
                              confirmText: '{{ __('messages.yes_delete') }}',
                              type: 'danger',
                              confirmButtonType: 'danger'
                          })">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                            <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm" disabled title="{{ __('messages.role_has_employees') }}" style="flex: 1; opacity: 0.6; cursor: not-allowed;">
                        <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="admin-empty-state" style="grid-column: 1 / -1;">
            <div class="admin-empty-state-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3>{{ __('messages.no_roles_found') }}</h3>
            <p>{{ __('messages.no_roles_description') }}</p>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_role') }}
            </a>
        </div>
    @endforelse
</div>

@if($roles->hasPages())
    <div style="margin-top: 24px;">
        {{ $roles->links() }}
    </div>
@endif
@endsection
