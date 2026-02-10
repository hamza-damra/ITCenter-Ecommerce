@extends('admin.layout')

@section('title', __('messages.employee_management'))

@section('content')
<style>
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

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent-indigo) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-info-text {
        display: flex;
        flex-direction: column;
    }

    .employee-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 14px;
    }

    .employee-email {
        font-size: 12px;
        color: var(--secondary);
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f5f3ff;
        color: #6d28d9;
    }

    .status-toggle {
        padding: 0;
        background: none;
        border: none;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .search-filter-box {
            flex-direction: column;
            padding: 16px;
        }

        .search-filter-box input,
        .search-filter-box select {
            min-width: unset;
            width: 100%;
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
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <h1>{{ __('messages.employee_management') }}</h1>
                <p>{{ __('messages.employee_management_subtitle') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-shield-alt"></i> {{ __('messages.manage_roles') }}
            </a>
            <a href="{{ route('admin.employees.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.add_employee') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
@php
    $totalEmployees = $employees->total();
    $activeEmployees = $employees->where('status', 'active')->count();
    $inactiveEmployees = $employees->where('status', 'inactive')->count();
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-users"></i> {{ __('messages.total_employees') }}</h4>
        <div class="stat-value">{{ $totalEmployees }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_employees') }}</h4>
        <div class="stat-value">{{ $activeEmployees }}</div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-times-circle"></i> {{ __('messages.inactive_employees') }}</h4>
        <div class="stat-value">{{ $inactiveEmployees }}</div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter-box">
    <input type="text" id="searchInput" placeholder="{{ __('messages.search_employees') }}" onkeyup="filterTable()">
    <select id="statusFilter" onchange="filterTable()">
        <option value="">{{ __('messages.all_status') }}</option>
        <option value="active">{{ __('messages.active') }}</option>
        <option value="inactive">{{ __('messages.inactive') }}</option>
    </select>
    <select id="roleFilter" onchange="filterTable()">
        <option value="">{{ __('messages.all_roles') }}</option>
        @php $availableRoles = \App\Models\EmployeeRole::where('is_active', true)->get(); @endphp
        @foreach($availableRoles as $r)
            <option value="{{ $r->id }}">{{ $r->name }}</option>
        @endforeach
    </select>
    <button class="filter-reset-btn" onclick="resetFilters()">
        <i class="fas fa-redo"></i> {{ __('messages.reset') }}
    </button>
</div>

<!-- Employees Table -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-users"></i> {{ __('messages.employees_list') }}</h3>
    </div>
    <div class="admin-table-body">
        <table class="admin-table" id="employeesTable">
            <thead>
                <tr>
                    <th>{{ __('messages.employee') }}</th>
                    <th>{{ __('messages.phone') }}</th>
                    <th>{{ __('messages.role') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr data-name="{{ strtolower($employee->name) }}" data-email="{{ strtolower($employee->email) }}" data-status="{{ $employee->status }}" data-role="{{ $employee->employee_role_id }}">
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                                <div class="employee-info-text">
                                    <span class="employee-name">{{ $employee->name }}</span>
                                    <span class="employee-email">{{ $employee->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $employee->phone ?? '—' }}</td>
                        <td>
                            @if($employee->employeeRole)
                                <span class="role-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    {{ $employee->employeeRole->name }}
                                </span>
                            @else
                                <span class="badge badge-warning">{{ __('messages.no_role') }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.employees.toggle-status', $employee) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="status-toggle" title="{{ __('messages.toggle_status') }}">
                                    <span class="badge {{ $employee->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                        <i class="fas {{ $employee->status === 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                        {{ $employee->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </button>
                            </form>
                        </td>
                        <td>{{ $employee->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                </a>
                                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST"
                                      onsubmit="handleFormConfirm(event, {
                                          message: '{{ __('messages.confirm_delete_employee') }}',
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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="admin-empty-state">
                                <div class="admin-empty-state-icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <h3>{{ __('messages.no_employees_found') }}</h3>
                                <p>{{ __('messages.no_employees_description') }}</p>
                                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> {{ __('messages.add_first_employee') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($employees->hasPages())
    <div style="margin-top: 24px;">
        {{ $employees->links() }}
    </div>
@endif

<script>
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const roleFilter = document.getElementById('roleFilter').value;
        const rows = document.querySelectorAll('#employeesTable tbody tr[data-name]');

        rows.forEach(row => {
            let matches = true;

            if (searchTerm) {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                matches = matches && (name.includes(searchTerm) || email.includes(searchTerm));
            }

            if (statusFilter) {
                matches = matches && row.getAttribute('data-status') === statusFilter;
            }

            if (roleFilter) {
                matches = matches && row.getAttribute('data-role') === roleFilter;
            }

            row.style.display = matches ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('roleFilter').value = '';
        filterTable();
    }
</script>
@endsection
