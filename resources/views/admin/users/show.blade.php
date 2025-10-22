@extends('admin.layout')

@section('title', __('messages.user_details'))

@section('content')
<style>
    .user-profile-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.3);
    }

    .user-profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
    }

    .user-large-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: 700;
        border: 4px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .user-profile-info h2 {
        margin: 0 0 8px 0;
        font-size: 32px;
        font-weight: 700;
    }

    .user-profile-info p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-profile-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .user-detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-detail-label {
        font-size: 13px;
        opacity: 0.8;
        font-weight: 500;
    }

    .user-detail-value {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stats-grid-user {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card-user {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .stat-card-user:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-content h3 {
        margin: 0 0 8px 0;
        font-size: 13px;
        color: var(--secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-content .value {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon-purple {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        color: #6b21a8;
    }

    .stat-icon-green {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .stat-icon-blue {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .stat-icon-red {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .section-title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-title-wrapper h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    .order-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid var(--primary);
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .order-number {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
    }

    .order-items-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .order-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
    }

    .order-item-info {
        flex: 1;
    }

    .order-item-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--dark);
    }

    .order-item-meta {
        font-size: 12px;
        color: var(--secondary);
    }

    [dir="rtl"] .stat-content h3 {
        text-transform: none;
        letter-spacing: normal;
    }

    [dir="rtl"] .order-card:hover {
        transform: translateX(-4px);
    }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    /* Account Control Buttons */
    .account-control-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .account-control-buttons .btn {
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .account-control-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .account-control-buttons .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        color: white;
    }

    .account-control-buttons .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
    }

    .account-control-buttons .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
    }

    /* Page Actions Responsive */
    .page-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .page-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .account-control-buttons {
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-user"></i> {{ __('messages.user_details') }}</h1>
        <p>{{ __('messages.view_complete_user_information') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_users') }}
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> {{ __('messages.edit_user') }}
        </a>
        
        <!-- Account Control Buttons -->
        <div class="account-control-buttons">
            @if($user->id !== auth()->id())
                <button type="button" class="btn btn-danger" onclick="openAccountControlModal('delete')">
                    <i class="fas fa-trash"></i> {{ __('messages.delete_account') }}
                </button>
            @endif
        </div>
    </div>
</div>

<!-- User Profile Card -->
<div class="user-profile-card">
    <div class="user-profile-header">
        <div class="user-large-avatar">
            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
        </div>
        <div class="user-profile-info">
            <h2>{{ $user->name }}</h2>
            <p>
                <i class="fas fa-envelope"></i>
                {{ $user->email }}
            </p>
        </div>
    </div>

    <div class="user-profile-details">
        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.user_id') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-hashtag"></i>
                {{ $user->id }}
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.phone') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-phone"></i>
                {{ $user->phone ?? __('messages.not_provided') }}
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.role') }}</span>
            <span class="user-detail-value">
                <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }}"></i>
                {{ $user->role === 'admin' ? __('messages.admin') : __('messages.customer') }}
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.account_status') }}</span>
            <span class="user-detail-value">
                <span class="status-pill {{ $user->getStatusBadgeClass() }}">
                    <i class="{{ $user->getStatusIcon() }}"></i>
                    {{ ucfirst($user->status ?? 'active') }}
                </span>
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.registration_date') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-calendar-plus"></i>
                {{ $user->created_at->format('Y-m-d') }}
            </span>
        </div>

        @if($userStats['last_order_date'])
        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.last_order_date') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-shopping-bag"></i>
                {{ $userStats['last_order_date']->format('Y-m-d') }}
            </span>
        </div>
        @endif
    </div>
</div>

<!-- User Statistics -->
<div class="stats-grid-user">
    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.total_orders') }}</h3>
            <div class="value">{{ $userStats['total_orders'] }}</div>
        </div>
        <div class="stat-icon stat-icon-purple">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.total_spent') }}</h3>
            <div class="value">${{ number_format($userStats['total_spent'], 2) }}</div>
        </div>
        <div class="stat-icon stat-icon-green">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.average_order') }}</h3>
            <div class="value">${{ number_format($userStats['average_order_value'], 2) }}</div>
        </div>
        <div class="stat-icon stat-icon-blue">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.favorites') }}</h3>
            <div class="value">{{ $userStats['total_favorites'] }}</div>
        </div>
        <div class="stat-icon stat-icon-red">
            <i class="fas fa-heart"></i>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header">
        <div class="section-title-wrapper">
            <h3>
                <i class="fas fa-history"></i>
                {{ __('messages.recent_orders') }}
            </h3>
            @if($recentOrders->count() > 0)
            <a href="{{ route('admin.orders.index', ['customer_id' => $user->id]) }}" class="btn btn-sm btn-primary">
                {{ __('messages.view_all_orders') }}
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($recentOrders->count() > 0)
            @foreach($recentOrders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-number">{{ __('messages.order') }} #{{ $order->order_number }}</span>
                        <br>
                        <small style="color: var(--secondary);">{{ $order->created_at->format('Y-m-d H:i') }}</small>
                    </div>
                    <div style="text-align: right;">
                        <span class="status-pill status-{{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <br>
                        <strong style="font-size: 18px; color: var(--success); display: block; margin-top: 4px;">
                            ${{ number_format($order->total, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="order-items-list">
                    @foreach($order->items->take(3) as $item)
                        @if($item->product)
                        <div class="order-item">
                            <img src="{{ $item->product->main_image }}" alt="{{ $item->product->name }}">
                            <div class="order-item-info">
                                <div class="order-item-name">{{ $item->product->name_en ?? $item->product->name }}</div>
                                <div class="order-item-meta">
                                    {{ __('messages.quantity') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                </div>
                            </div>
                            <strong style="color: var(--primary);">${{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                        @else
                        <div class="order-item">
                            <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-box" style="color: #64748b;"></i>
                            </div>
                            <div class="order-item-info">
                                <div class="order-item-name" style="color: #64748b; font-style: italic;">{{ __('messages.product_deleted') }}</div>
                                <div class="order-item-meta">
                                    {{ __('messages.quantity') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                </div>
                            </div>
                            <strong style="color: var(--primary);">${{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                        @endif
                    @endforeach

                    @if($order->items->count() > 3)
                    <div style="text-align: center; padding: 8px; color: var(--secondary); font-size: 13px;">
                        {{ __('messages.and_more_items', ['count' => $order->items->count() - 3]) }}
                    </div>
                    @endif
                </div>

                <div style="text-align: right; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border);">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> {{ __('messages.view_order_details') }}
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state-message">
                <i class="fas fa-shopping-bag"></i>
                <p>{{ __('messages.user_has_no_orders_yet') }}</p>
            </div>
        @endif
    </div>
</div>

<!-- User Favorites (if any) -->
@if($user->favorites->count() > 0)
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>
            <i class="fas fa-heart"></i>
            {{ __('messages.user_favorites') }} ({{ $user->favorites->count() }})
        </h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
            @foreach($user->favorites->take(8) as $favorite)
                @if($favorite->product)
                <div style="background: #f8fafc; border-radius: 8px; padding: 12px; text-align: center;">
                    <img src="{{ $favorite->product->main_image }}" alt="{{ $favorite->product->name }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">
                    <div style="font-weight: 600; font-size: 13px; color: var(--dark); margin-bottom: 4px;">
                        {{ Str::limit($favorite->product->name_en ?? $favorite->product->name, 30) }}
                    </div>
                    <div style="color: var(--success); font-weight: 700;">
                        ${{ number_format($favorite->product->price, 2) }}
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        @if($user->favorites->count() > 8)
        <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border);">
            <span style="color: var(--secondary);">
                {{ __('messages.and_more_favorites', ['count' => $user->favorites->count() - 8]) }}
            </span>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Account Control Modal -->
<div id="accountControlModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">{{ __('messages.delete_confirm_title') }}</h3>
            <button type="button" class="modal-close" onclick="closeAccountControlModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-icon">
                <i id="modalIcon" class="fas fa-trash-circle"></i>
            </div>
            <p id="modalMessage">{{ __('messages.delete_confirm_message') }}</p>
            <div class="user-info-card">
                <div class="user-avatar">
                    {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                </div>
                <div class="user-details">
                    <h4>{{ $user->name }}</h4>
                    <p>{{ $user->email }}</p>
                    <span class="user-role">{{ $user->role === 'admin' ? __('messages.admin') : __('messages.customer') }}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeAccountControlModal()">
                {{ __('messages.cancel') }}
            </button>
            <button type="button" class="btn btn-danger" id="confirmButton" onclick="confirmAccountAction()">
                {{ __('messages.confirm_action') }}
            </button>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.modal-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 28px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
}

.modal-close {
    background: none;
    border: none;
    font-size: 18px;
    color: var(--secondary);
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: var(--border);
    color: var(--dark);
}

.modal-body {
    padding: 28px;
    text-align: center;
}

.modal-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
}

.modal-body p {
    font-size: 16px;
    color: var(--secondary);
    margin-bottom: 24px;
    line-height: 1.6;
}

.user-info-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #f8fafc;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 24px;
    text-align: left;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
}

.user-details h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
}

.user-details p {
    margin: 0 0 8px 0;
    font-size: 14px;
    color: var(--secondary);
}

.user-role {
    background: var(--primary);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.reason-input {
    text-align: left;
}

.reason-input label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
}

.reason-input textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    resize: vertical;
    min-height: 80px;
    transition: border-color 0.2s ease;
}

.reason-input textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding: 24px 28px;
    border-top: 1px solid var(--border);
    background: #f8fafc;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

[dir="rtl"] .user-info-card {
    text-align: right;
}

[dir="rtl"] .modal-footer {
    justify-content: flex-start;
}
</style>

<script>
let currentAction = null;
let currentUserId = {{ $user->id }};

function openAccountControlModal(action) {
    currentAction = action;
    const modal = document.getElementById('accountControlModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');
    const confirmButton = document.getElementById('confirmButton');
    const reasonInput = document.getElementById('reasonInput');
    const reasonTextarea = document.getElementById('reason');
    
    // Reset form
    reasonTextarea.value = '';
    
    switch(action) {
        case 'delete':
            modalTitle.textContent = '{{ __("messages.delete_confirm_title") }}';
            modalMessage.textContent = '{{ __("messages.delete_confirm_message") }}';
            modalIcon.className = 'fas fa-trash';
            modalIcon.parentElement.style.background = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
            modalIcon.style.color = '#dc2626';
            confirmButton.textContent = '{{ __("messages.delete_account") }}';
            confirmButton.className = 'btn btn-danger';
            reasonInput.style.display = 'none';
            break;
    }
    
    modal.style.display = 'flex';
}

function closeAccountControlModal() {
    document.getElementById('accountControlModal').style.display = 'none';
    currentAction = null;
}

function confirmAccountAction() {
    if (!currentAction) return;
    
    const reason = document.getElementById('reason').value;
    const confirmButton = document.getElementById('confirmButton');
    
    // Disable button and show loading
    confirmButton.disabled = true;
    confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    let url = '';
    let data = {};
    
    switch(currentAction) {
        case 'delete':
            url = `/admin/users/${currentUserId}`;
            data = { _method: 'DELETE' };
            break;
    }
    
    // Prepare form data with CSRF token
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            showNotification(data.message, 'success');
            // Redirect to users list after delete
            window.location.href = '/admin/users';
            closeAccountControlModal();
        } else {
            showNotification(data.message || 'Action failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        // Re-enable button
        confirmButton.disabled = false;
        confirmButton.innerHTML = '{{ __("messages.confirm_action") }}';
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Add CSS for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    .notification-content {
        display: flex;
        align-items: center;
        gap: 8px;
    }
`;
document.head.appendChild(style);
</script>

@endsection

