@extends('admin.layout')

@section('title', __('messages.contact_messages_management'))

@section('content')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@if(app()->getLocale() === 'ar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endif

<style>
    /* Page-specific styles that extend unified components */
    
    /* Filters Card */
    .filters-card {
        background: var(--bg-primary, #ffffff);
        border-radius: var(--radius-lg, 16px);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-card, 0 4px 20px rgba(0, 0, 0, 0.08));
    }

    .filters-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--dark, #0f172a);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    [dir="rtl"] .filter-group label {
        text-transform: none;
        letter-spacing: normal;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border, #e2e8f0);
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        background: var(--bg-secondary, #f8fafc);
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary, #2563eb);
        background: var(--bg-primary, #ffffff);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.625rem;
        flex-wrap: wrap;
    }

    .filter-actions .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .filter-actions .btn-primary {
        background: linear-gradient(135deg, var(--primary, #2563eb) 0%, var(--primary-dark, #1e40af) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .filter-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .filter-actions .btn-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .filter-actions .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
    }

    /* Bulk Actions Bar */
    .bulk-actions {
        display: none;
        background: linear-gradient(135deg, var(--primary, #2563eb) 0%, var(--primary-dark, #1e40af) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: var(--radius-lg, 16px);
        margin-bottom: 1.25rem;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 8px 32px rgba(37, 99, 235, 0.3);
        flex-wrap: wrap;
    }

    .bulk-actions.active {
        display: flex;
    }

    .bulk-actions #selectedCount {
        font-size: 1.125rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
    }

    .bulk-actions select {
        padding: 0.625rem 1rem;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: white;
        color: var(--dark, #0f172a);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .bulk-actions form {
        display: flex;
        gap: 0.625rem;
        align-items: center;
    }

    .bulk-actions .btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .bulk-actions .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .bulk-actions .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .bulk-actions .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [dir="rtl"] .status-badge {
        text-transform: none;
        letter-spacing: normal;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .status-badge.read {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-badge.archived {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 0.85rem;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--primary, #2563eb) 0%, var(--primary-dark, #1e40af) 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    /* Checkbox Cell */
    .checkbox-cell {
        width: 50px;
        text-align: center;
    }

    .checkbox-cell input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary, #2563eb);
    }

    /* Truncate Text */
    .truncate {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border, #e2e8f0);
        background: linear-gradient(135deg, var(--bg-secondary, #f8fafc) 0%, var(--bg-tertiary, #f1f5f9) 100%);
        display: flex;
        justify-content: center;
    }

    /* RTL Support */
    [dir="rtl"] .filter-actions {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .bulk-actions {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .bulk-actions form {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .truncate {
        direction: rtl;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .filters-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .filters-form {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.35rem;
        }

        .action-buttons .btn-action {
            width: 100%;
            justify-content: center;
        }
    }

    /* Flatpickr RTL Support */
    @if(app()->getLocale() === 'ar' || app()->getLocale() === 'he')
    .flatpickr-calendar {
        direction: rtl;
    }

    .flatpickr-calendar .flatpickr-months,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .dayContainer {
        direction: rtl;
    }

    .flatpickr-calendar .flatpickr-prev-month,
    .flatpickr-calendar .flatpickr-next-month {
        transform: scaleX(-1);
    }
    @endif
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h1>{{ __('messages.contact_messages_management') }}</h1>
                <p>{{ __('messages.view_and_manage_customer_inquiries') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics - Using unified admin-stats-grid and admin-stat-card components -->
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
        <h4>{{ __('messages.total_messages') }}</h4>
        <div class="stat-value">{{ $stats['total_messages'] }}</div>
    </div>
    <div class="admin-stat-card stat-warning">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <h4>{{ __('messages.pending') }}</h4>
        <div class="stat-value">{{ $stats['pending_messages'] }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <div class="stat-icon"><i class="fas fa-envelope-open"></i></div>
        <h4>{{ __('messages.read') }}</h4>
        <div class="stat-value">{{ $stats['read_messages'] }}</div>
    </div>
    <div class="admin-stat-card stat-violet">
        <div class="stat-icon"><i class="fas fa-archive"></i></div>
        <h4>{{ __('messages.archived') }}</h4>
        <div class="stat-value">{{ $stats['archived_messages'] }}</div>
    </div>
</div>

<!-- Bulk Actions Bar -->
<div class="bulk-actions" id="bulkActions">
    <span id="selectedCount">0</span> {{ __('messages.messages_selected') }}
    <form action="{{ route('admin.contacts.bulk-update-status') }}" method="POST" style="display: flex; gap: 0.625rem; align-items: center;">
        @csrf
        <select name="status">
            <option value="pending">{{ __('messages.mark_as_pending') }}</option>
            <option value="read">{{ __('messages.mark_as_read') }}</option>
            <option value="archived">{{ __('messages.mark_as_archived') }}</option>
        </select>
        <button type="submit" class="btn-action btn-secondary">
            <i class="fas fa-sync-alt"></i> {{ __('messages.update_status') }}
        </button>
    </form>
    <form action="{{ route('admin.contacts.bulk-delete') }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure_delete_selected_messages') }}')">
        @csrf
        <button type="submit" class="btn-action btn-delete">
            <i class="fas fa-trash-alt"></i> {{ __('messages.delete_selected') }}
        </button>
    </form>
</div>

<!-- Filters -->
<div class="filters-card">
    <form action="{{ route('admin.contacts.index') }}" method="GET" class="filters-form">
        <div class="filter-group">
            <label><i class="fas fa-search"></i> {{ __('messages.search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_name_email_subject') }}">
        </div>
        <div class="filter-group">
            <label><i class="fas fa-filter"></i> {{ __('messages.status') }}</label>
            <select name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('messages.all_statuses') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>{{ __('messages.read') }}</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>{{ __('messages.archived') }}</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> {{ __('messages.from_date') }}</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="date-input-locale" 
                   data-locale="{{ app()->getLocale() }}"
                   lang="{{ app()->getLocale() }}"
                   @if(app()->getLocale() === 'ar')
                   placeholder="اضغط لاختيار تاريخ البداية"
                   @elseif(app()->getLocale() === 'he')
                   placeholder="לחץ לבחירת תאריך התחלה"
                   @else
                   placeholder="Click to choose start date"
                   @endif>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-check"></i> {{ __('messages.to_date') }}</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="date-input-locale" 
                   data-locale="{{ app()->getLocale() }}"
                   lang="{{ app()->getLocale() }}"
                   @if(app()->getLocale() === 'ar')
                   placeholder="اضغط لاختيار تاريخ النهاية"
                   @elseif(app()->getLocale() === 'he')
                   placeholder="לחץ לבחירת תאריך סיום"
                   @else
                   placeholder="Click to choose end date"
                   @endif>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> {{ __('messages.filter') }}
            </button>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> {{ __('messages.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Messages Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-inbox"></i> {{ __('messages.contact_messages') ?? __('messages.messages') }}</h3>
    </div>
    
    @if($messages->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.email') }}</th>
                    <th>{{ __('messages.subject') }}</th>
                    <th>{{ __('messages.message') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="message-checkbox" value="{{ $message->id }}">
                    </td>
                    <td><strong>{{ $message->name }}</strong></td>
                    <td><span style="color: var(--secondary);">{{ $message->email }}</span></td>
                    <td>{{ Str::limit($message->subject, 30) }}</td>
                    <td class="truncate">{{ Str::limit($message->message, 50) }}</td>
                    <td>
                        <span class="status-badge {{ $message->status }}">
                            @if($message->status === 'pending')
                                <i class="fas fa-clock"></i>
                            @elseif($message->status === 'read')
                                <i class="fas fa-envelope-open"></i>
                            @else
                                <i class="fas fa-archive"></i>
                            @endif
                            {{ __('messages.' . $message->status) }}
                        </span>
                    </td>
                    <td style="color: var(--secondary); font-weight: 600;">
                        <i class="fas fa-calendar"></i> {{ $message->created_at->format('Y-m-d') }}<br>
                        <i class="fas fa-clock"></i> {{ $message->created_at->format('H:i') }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn-action btn-view" title="{{ __('messages.view_details') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.are_you_sure') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="{{ __('messages.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
    <div class="pagination-wrapper">
        {{ $messages->links() }}
    </div>
    @endif
    @else
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3>{{ __('messages.no_messages_found') }}</h3>
        <p>{{ __('messages.no_contact_messages_to_display') }}</p>
    </div>
    @endif
</div>

<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@if(app()->getLocale() === 'ar')
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
@elseif(app()->getLocale() === 'he')
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/he.js"></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.message-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkActions() {
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        const ids = checked.map(cb => cb.value);
        
        if (checked.length > 0) {
            bulkActions.classList.add('active');
            selectedCount.textContent = checked.length;
            
            // Remove existing hidden inputs
            document.querySelectorAll('.bulk-ids-input').forEach(el => el.remove());
            
            // Add hidden inputs for each ID in both forms
            const statusForm = document.querySelector('form[action*="bulk-update-status"]');
            const deleteForm = document.querySelector('form[action*="bulk-delete"]');
            
            ids.forEach(id => {
                // For status update form
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'ids[]';
                statusInput.value = id;
                statusInput.className = 'bulk-ids-input';
                statusForm.appendChild(statusInput);
                
                // For delete form
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'ids[]';
                deleteInput.value = id;
                deleteInput.className = 'bulk-ids-input';
                deleteForm.appendChild(deleteInput);
            });
        } else {
            bulkActions.classList.remove('active');
            document.querySelectorAll('.bulk-ids-input').forEach(el => el.remove());
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    // Initialize Flatpickr for date inputs with localization
    const currentLocale = '{{ app()->getLocale() }}';
    const dateInputs = document.querySelectorAll('input[type="date"].date-input-locale');
    
    // Flatpickr configuration based on locale
    const flatpickrConfig = {
        dateFormat: 'Y-m-d',
        allowInput: true,
        disableMobile: true,
    };

    // Add locale-specific configuration
    if (currentLocale === 'ar') {
        flatpickrConfig.locale = 'ar';
        flatpickrConfig.position = 'auto right';
    } else if (currentLocale === 'he') {
        flatpickrConfig.locale = 'he';
        flatpickrConfig.position = 'auto right';
    }

    // Initialize Flatpickr on each date input
    dateInputs.forEach(input => {
        const config = { ...flatpickrConfig };
        const placeholderText = input.getAttribute('placeholder');
        if (placeholderText) {
            input.setAttribute('data-placeholder', placeholderText);
            if (!input.value) {
                input.setAttribute('placeholder', placeholderText);
            }
        }
        flatpickr(input, config);
    });
});
</script>
@endsection
