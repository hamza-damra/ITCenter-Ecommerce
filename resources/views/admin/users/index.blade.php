@extends('admin.layout')

@section('title', __('messages.users_management'))

@section('content')
<style>
    /* User-specific styles */
    .role-badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: linear-gradient(135deg, #fecaca 0%, #fee2e2 100%);
        color: #991b1b;
    }

    .role-customer {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .user-info h4 {
        margin: 0 0 4px 0;
        font-size: 15px;
        font-weight: 600;
        color: var(--dark);
    }

    .user-info p {
        margin: 0;
        font-size: 13px;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stats-pill {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        color: var(--dark);
    }

    .search-filters {
        background: white;
        padding: 24px 28px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
        color: var(--dark);
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
    }

    [dir="rtl"] .role-badge {
        text-transform: none;
        letter-spacing: normal;
    }

    /* Enhanced Page Header for Users */
    .users-page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 48px 32px;
        border-radius: 20px;
        margin-bottom: 32px;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
        position: relative;
        overflow: hidden;
    }

    .users-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .users-page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        z-index: 0;
    }

    .users-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
    }

    .users-header-left {
        flex: 1;
        min-width: 300px;
    }

    .users-header-left h1 {
        font-size: 42px;
        font-weight: 800;
        margin: 0 0 12px 0;
        display: flex;
        align-items: center;
        gap: 16px;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .users-header-left h1 i {
        font-size: 48px;
        opacity: 0.95;
    }

    .users-header-left p {
        font-size: 17px;
        margin: 0;
        opacity: 0.95;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .users-header-right {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .users-header-right .btn {
        background: white;
        color: #667eea;
        padding: 14px 28px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        border: none;
    }

    .users-header-right .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        background: #f8fafc;
    }

    .users-header-right .btn i {
        font-size: 16px;
    }

    /* Enhanced Stats Cards */
    .stats-grid-users {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card-users {
        background: white;
        padding: 28px 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-users:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card-users::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    /* Statistics Cards Styling - Matching Dashboard */
    .stats-grid-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card-large {
        padding: 28px 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-large:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Products Sold Card - Purple Gradient */
    .stat-card-large.products-sold {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    /* Revenue Card - Pink Gradient */
    .stat-card-large.revenue {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    /* Customers Card - Orange Gradient */
    .stat-card-large.customers {
        background: linear-gradient(135deg, #fa8e42 0%, #feb47b 100%);
        color: white;
    }

    /* Satisfaction Card - Blue Gradient */
    .stat-card-large.satisfaction {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    /* Green Gradient for success metrics */
    .stat-card-large.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .stat-card-content {
        flex: 1;
        z-index: 2;
    }

    .stat-card-label {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 8px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .stat-card-value {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 4px;
        line-height: 1;
    }

    .stat-card-footer {
        font-size: 13px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
    }

    .stat-card-icon-wrapper {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        z-index: 2;
    }

    .stat-card-icon-wrapper i {
        font-size: 32px;
        opacity: 0.9;
    }

    /* Decorative background elements */
    .stat-card-large::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .stat-card-large::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        z-index: 0;
    }

    @media (max-width: 768px) {
        .users-page-header {
            padding: 32px 24px;
        }

        .users-header-left h1 {
            font-size: 32px;
        }

        .users-header-left h1 i {
            font-size: 36px;
        }

        .users-header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .users-header-right {
            width: 100%;
        }

        .users-header-right .btn {
            width: 100%;
            justify-content: center;
        }

        .stats-grid-dashboard {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }
</style>

<!-- Enhanced Page Header -->
<div class="users-page-header">
    <div class="users-header-content">
        <div class="users-header-left">
            <h1>
                <i class="fas fa-users-cog"></i>
                {{ __('messages.users_management') }}
            </h1>
            <p>{{ __('messages.manage_all_users_accounts') }}</p>
        </div>
        <div class="users-header-right">
            <a href="{{ route('admin.users.create') }}" class="btn">
                <i class="fas fa-user-plus"></i> {{ __('messages.add_new_user') }}
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Statistics Cards -->
<div class="stats-grid-dashboard">
    <!-- Total Users Card - Purple Gradient -->
    <div class="stat-card-large products-sold">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.total_users') }}</div>
            <div class="stat-card-value">{{ $stats['total_users'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-users"></i> {{ __('messages.all_registered_users') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <!-- Administrators Card - Pink Gradient -->
    <div class="stat-card-large revenue">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.administrators') }}</div>
            <div class="stat-card-value">{{ $stats['admin_users'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-user-shield"></i> {{ __('messages.admin_accounts') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>

    <!-- Customers Card - Blue Gradient -->
    <div class="stat-card-large satisfaction">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.customers') }}</div>
            <div class="stat-card-value">{{ $stats['customer_users'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-user"></i> {{ __('messages.customer_accounts') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user"></i>
        </div>
    </div>

    <!-- Active Customers Card - Green Gradient -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.users_with_orders') }}</div>
            <div class="stat-card-value">{{ $stats['users_with_orders'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-shopping-bag"></i> {{ __('messages.active_customers') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <!-- New Users Card - Orange Gradient -->
    <div class="stat-card-large customers">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.new_users_this_month') }}</div>
            <div class="stat-card-value">{{ $stats['new_users_this_month'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar-plus"></i> {{ __('messages.joined_this_month') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<form method="GET" action="{{ route('admin.users.index') }}" class="search-filters">
    <div class="filter-group">
        <label for="search">{{ __('messages.search') }}</label>
        <input type="text" 
               id="search" 
               name="search" 
               class="form-control" 
               placeholder="{{ __('messages.search_users_placeholder') }}"
               value="{{ request('search') }}">
    </div>

    <div class="filter-group" style="max-width: 200px;">
        <label for="role">{{ __('messages.user_role') }}</label>
        <select name="role" id="role" class="form-control">
            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>{{ __('messages.all_roles') }}</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>{{ __('messages.customer') }}</option>
        </select>
    </div>

    <div class="filter-group" style="max-width: 200px;">
        <label for="sort_by">{{ __('messages.sort_by') }}</label>
        <select name="sort_by" id="sort_by" class="form-control">
            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('messages.registration_date') }}</option>
            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>{{ __('messages.name') }}</option>
            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>{{ __('messages.email') }}</option>
        </select>
    </div>

    <div class="filter-buttons">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> {{ __('messages.filter') }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i> {{ __('messages.reset') }}
        </a>
    </div>
</form>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> {{ __('messages.all_users') }}</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>{{ __('messages.user') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.phone') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.orders') }}</th>
                        <th>{{ __('messages.registration_date') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                </div>
                                <div class="user-info">
                                    <h4>{{ $user->name }}</h4>
                                    <p>
                                        <i class="fas fa-user-circle" style="color: var(--primary);"></i>
                                        {{ __('messages.user_id') }}: #{{ $user->id }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-envelope" style="color: var(--secondary);"></i>
                                {{ $user->email }}
                            </span>
                        </td>
                        <td>
                            @if($user->phone)
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-phone" style="color: var(--secondary);"></i>
                                    {{ $user->phone }}
                                </span>
                            @else
                                <span style="color: var(--secondary); font-style: italic;">{{ __('messages.not_provided') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">
                                <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }}"></i>
                                {{ $user->role === 'admin' ? __('messages.admin') : __('messages.customer') }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <span class="stats-pill">
                                    <i class="fas fa-shopping-bag" style="color: var(--primary);"></i>
                                    {{ $user->orders_count }} {{ __('messages.orders') }}
                                </span>
                                <span class="stats-pill">
                                    <i class="fas fa-heart" style="color: #ef4444;"></i>
                                    {{ $user->favorites_count }} {{ __('messages.favorites') }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <span style="font-weight: 600;">{{ $user->created_at->format('Y-m-d') }}</span>
                                <span style="font-size: 12px; color: var(--secondary);">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary" title="{{ __('messages.view_details') }}">
                                    <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-secondary" title="{{ __('messages.edit_user') }}">
                                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('{{ __('messages.delete_user_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.delete_user') }}">
                                        <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="padding: 24px 28px;">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state-message">
                <i class="fas fa-users"></i>
                <p>{{ __('messages.no_users_found') }}</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                    <i class="fas fa-plus"></i> {{ __('messages.add_first_user') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

