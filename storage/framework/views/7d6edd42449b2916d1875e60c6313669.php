<?php $__env->startSection('title', __('messages.contact_messages_management')); ?>

<?php $__env->startSection('content'); ?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php if(app()->getLocale() === 'ar'): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<?php endif; ?>

<style>
    /* Force RTL for all text elements */
    * {
        text-align: inherit;
    }

    /* Contacts Page Specific Styles */
    .contacts-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 28px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
        position: relative;
        overflow: hidden;
    }

    .contacts-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .contacts-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
        text-align: right !important;
        direction: rtl !important;
        justify-content: flex-end;
        width: 100%;
    }

    .contacts-header p {
        opacity: 0.95;
        font-size: 16px;
        position: relative;
        z-index: 1;
        text-align: right !important;
        direction: rtl !important;
        width: 100%;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 28px 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        transition: width 0.3s ease;
    }

    .stat-card.total::before { background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%); }
    .stat-card.pending::before { background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%); }
    .stat-card.read::before { background: linear-gradient(180deg, #10b981 0%, #059669 100%); }
    .stat-card.archived::before { background: linear-gradient(180deg, #6b7280 0%, #4b5563 100%); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card:hover::before {
        width: 100%;
        opacity: 0.05;
    }

    .stat-card h3 {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        text-align: right !important;
        direction: rtl !important;
        justify-content: flex-end;
        width: 100%;
    }

    .stat-card .stat-value {
        font-size: 38px;
        font-weight: 700;
        color: var(--dark);
        text-align: right !important;
        direction: rtl !important;
        width: 100%;
    }

    .filters-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .filters-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: right !important;
        direction: rtl !important;
        display: block;
        width: 100%;
    }

    .filter-group input,
    .filter-group select {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #f8fafc;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .table th {
        padding: 18px 20px;
        text-align: right !important;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        direction: rtl !important;
    }

    .table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table td {
        padding: 18px 20px;
        color: var(--dark);
        vertical-align: middle;
        font-size: 14px;
        text-align: right !important;
        direction: rtl !important;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
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

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
    }

    .btn-view {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .pagination {
        padding: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .empty-state {
        padding: 80px 40px;
        text-align: center;
        color: var(--secondary);
    }

    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state h3 {
        font-size: 24px;
        color: var(--dark);
        margin-bottom: 12px;
        font-weight: 700;
        text-align: center !important;
        direction: rtl !important;
    }

    .empty-state p {
        color: var(--secondary);
        font-size: 16px;
        text-align: center !important;
        direction: rtl !important;
    }

    .checkbox-cell {
        width: 50px;
        text-align: center;
    }

    .checkbox-cell input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .bulk-actions {
        display: none;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        align-items: center;
        gap: 16px;
        box-shadow: 0 8px 32px rgba(37, 99, 235, 0.3);
        flex-wrap: wrap;
    }

    .bulk-actions.active {
        display: flex;
    }

    .bulk-actions #selectedCount {
        font-size: 18px;
        font-weight: 700;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
    }

    .bulk-actions select {
        padding: 10px 16px;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: white;
        color: var(--dark);
        font-weight: 600;
        font-size: 14px;
    }

    .bulk-actions form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .truncate {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* RTL Support for Contacts Table */
    [dir="rtl"] .contacts-header h1 {
        flex-direction: row-reverse;
        text-align: right !important;
    }

    [dir="rtl"] .contacts-header p {
        text-align: right !important;
    }

    [dir="rtl"] .table th,
    [dir="rtl"] .table td {
        text-align: right !important;
    }

    [dir="rtl"] .table th:last-child,
    [dir="rtl"] .table td:last-child {
        text-align: left !important;
    }

    [dir="rtl"] .action-buttons {
        justify-content: flex-start;
    }

    [dir="rtl"] .stat-card::before {
        left: auto;
        right: 0;
    }

    [dir="rtl"] .stat-card h3 {
        flex-direction: row-reverse;
        text-align: right !important;
    }

    [dir="rtl"] .stat-card .stat-value {
        text-align: right !important;
    }

    [dir="rtl"] .btn {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .filter-actions {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .bulk-actions {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .bulk-actions form {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .filter-group label {
        text-align: right !important;
    }

    [dir="rtl"] .filter-group input,
    [dir="rtl"] .filter-group select {
        text-align: right !important;
        direction: rtl !important;
    }

    [dir="rtl"] .empty-state h3,
    [dir="rtl"] .empty-state p {
        text-align: center !important;
        direction: rtl !important;
    }

    [dir="rtl"] .truncate {
        direction: rtl;
        text-align: right !important;
    }

    /* Force RTL for Arabic content */
    [lang="ar"] .contacts-header h1,
    [lang="ar"] .contacts-header p,
    [lang="ar"] .stat-card h3,
    [lang="ar"] .stat-card .stat-value,
    [lang="ar"] .filter-group label {
        direction: rtl !important;
        text-align: right !important;
    }

    /* LTR Support for English */
    [dir="ltr"] .contacts-header h1 {
        text-align: left !important;
        flex-direction: row;
    }

    [dir="ltr"] .contacts-header p {
        text-align: left !important;
    }

    [dir="ltr"] .stat-card h3 {
        text-align: left !important;
        flex-direction: row;
    }

    [dir="ltr"] .stat-card .stat-value {
        text-align: left !important;
    }

    [dir="ltr"] .filter-group label,
    [dir="ltr"] .filter-group input,
    [dir="ltr"] .filter-group select {
        text-align: left !important;
        direction: ltr !important;
    }

    [dir="ltr"] .table th,
    [dir="ltr"] .table td {
        text-align: left !important;
        direction: ltr !important;
    }

    [dir="ltr"] .table th:last-child,
    [dir="ltr"] .table td:last-child {
        text-align: right !important;
    }

    [dir="ltr"] .action-buttons {
        justify-content: flex-end;
    }

    [dir="ltr"] .stat-card::before {
        left: 0;
        right: auto;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .filters-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .contacts-header h1 {
            font-size: 22px;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .filters-form {
            grid-template-columns: 1fr;
        }

        .table {
            font-size: 13px;
        }

        .table td,
        .table th {
            padding: 14px;
        }

        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }

        .stat-card .stat-value {
            font-size: 32px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Date Input RTL Support */
    [dir="rtl"] input[type="date"] {
        direction: rtl;
        text-align: right;
    }

    /* Force RTL layout for date picker in Arabic */
    input[type="date"][lang="ar"],
    input[type="date"][lang="he"] {
        direction: rtl;
    }

    /* Calendar icon position for RTL */
    [dir="rtl"] input[type="date"]::-webkit-calendar-picker-indicator {
        margin-left: 0;
        margin-right: auto;
    }

    /* Placeholder styling for date inputs (all languages) */
    input[type="date"].date-input-locale::placeholder {
        color: #9ca3af;
        opacity: 0.7;
        font-size: 0.9rem;
    }

    input[type="date"].date-input-locale::-webkit-input-placeholder {
        color: #9ca3af;
        opacity: 0.7;
        font-size: 0.9rem;
    }

    input[type="date"].date-input-locale::-moz-placeholder {
        color: #9ca3af;
        opacity: 0.7;
        font-size: 0.9rem;
    }

    /* Flatpickr RTL Support */
    <?php if(app()->getLocale() === 'ar' || app()->getLocale() === 'he'): ?>
    .flatpickr-calendar {
        direction: rtl;
    }

    .flatpickr-calendar .flatpickr-months {
        direction: rtl;
    }

    .flatpickr-calendar .flatpickr-weekdays {
        direction: rtl;
    }

    .flatpickr-calendar .dayContainer {
        direction: rtl;
    }

    .flatpickr-calendar .flatpickr-prev-month,
    .flatpickr-calendar .flatpickr-next-month {
        transform: scaleX(-1); /* Flip arrow icons for RTL */
    }

    .flatpickr-calendar .flatpickr-current-month {
        padding-right: 28.5px;
        padding-left: 28.5px;
    }

    /* Adjust positioning for RTL */
    .flatpickr-calendar.arrowTop:after,
    .flatpickr-calendar.arrowTop:before {
        left: auto;
        right: 22px;
    }
    <?php endif; ?>
</style>

<div class="contacts-header">
    <h1><i class="fas fa-envelope"></i> <?php echo e(__('messages.contact_messages_management')); ?></h1>
    <p><?php echo e(__('messages.view_and_manage_customer_inquiries')); ?></p>
</div>

<?php if(session('success')): ?>
<div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; padding: 18px 24px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
    <i class="fas fa-check-circle"></i> <strong><?php echo e(session('success')); ?></strong>
</div>
<?php endif; ?>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card total">
        <h3><i class="fas fa-envelope"></i> <?php echo e(__('messages.total_messages')); ?></h3>
        <div class="stat-value"><?php echo e($stats['total_messages']); ?></div>
    </div>
    <div class="stat-card pending">
        <h3><i class="fas fa-clock"></i> <?php echo e(__('messages.pending')); ?></h3>
        <div class="stat-value"><?php echo e($stats['pending_messages']); ?></div>
    </div>
    <div class="stat-card read">
        <h3><i class="fas fa-envelope-open"></i> <?php echo e(__('messages.read')); ?></h3>
        <div class="stat-value"><?php echo e($stats['read_messages']); ?></div>
    </div>
    <div class="stat-card archived">
        <h3><i class="fas fa-archive"></i> <?php echo e(__('messages.archived')); ?></h3>
        <div class="stat-value"><?php echo e($stats['archived_messages']); ?></div>
    </div>
</div>

<!-- Bulk Actions Bar -->
<div class="bulk-actions" id="bulkActions">
    <span id="selectedCount">0</span> <?php echo e(__('messages.messages_selected')); ?>

    <form action="<?php echo e(route('admin.contacts.bulk-update-status')); ?>" method="POST" style="display: flex; gap: 10px; align-items: center;">
        <?php echo csrf_field(); ?>
        <select name="status" style="padding: 10px 16px; border-radius: 8px; border: 2px solid rgba(255, 255, 255, 0.3); background: white; color: var(--dark); font-weight: 600; font-size: 14px;">
            <option value="pending"><?php echo e(__('messages.mark_as_pending')); ?></option>
            <option value="read"><?php echo e(__('messages.mark_as_read')); ?></option>
            <option value="archived"><?php echo e(__('messages.mark_as_archived')); ?></option>
        </select>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-sync-alt"></i> <?php echo e(__('messages.update_status')); ?>

        </button>
    </form>
    <form action="<?php echo e(route('admin.contacts.bulk-delete')); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('messages.are_you_sure_delete_selected_messages')); ?>')">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-delete">
            <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_selected')); ?>

        </button>
    </form>
</div>

<!-- Filters -->
<div class="filters-card">
    <form action="<?php echo e(route('admin.contacts.index')); ?>" method="GET" class="filters-form">
        <div class="filter-group">
            <label><i class="fas fa-search"></i> <?php echo e(__('messages.search')); ?></label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.search_name_email_subject')); ?>">
        </div>
        <div class="filter-group">
            <label><i class="fas fa-filter"></i> <?php echo e(__('messages.status')); ?></label>
            <select name="status">
                <option value="all" <?php echo e(request('status') == 'all' ? 'selected' : ''); ?>><?php echo e(__('messages.all_statuses')); ?></option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>><?php echo e(__('messages.pending')); ?></option>
                <option value="read" <?php echo e(request('status') == 'read' ? 'selected' : ''); ?>><?php echo e(__('messages.read')); ?></option>
                <option value="archived" <?php echo e(request('status') == 'archived' ? 'selected' : ''); ?>><?php echo e(__('messages.archived')); ?></option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> <?php echo e(__('messages.from_date')); ?></label>
            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" 
                   class="date-input-locale" 
                   data-locale="<?php echo e(app()->getLocale()); ?>"
                   lang="<?php echo e(app()->getLocale()); ?>"
                   <?php if(app()->getLocale() === 'ar'): ?>
                   placeholder="اضغط لاختيار تاريخ البداية"
                   <?php elseif(app()->getLocale() === 'he'): ?>
                   placeholder="לחץ לבחירת תאריך התחלה"
                   <?php else: ?>
                   placeholder="Click to choose start date"
                   <?php endif; ?>>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-check"></i> <?php echo e(__('messages.to_date')); ?></label>
            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" 
                   class="date-input-locale" 
                   data-locale="<?php echo e(app()->getLocale()); ?>"
                   lang="<?php echo e(app()->getLocale()); ?>"
                   <?php if(app()->getLocale() === 'ar'): ?>
                   placeholder="اضغط لاختيار تاريخ النهاية"
                   <?php elseif(app()->getLocale() === 'he'): ?>
                   placeholder="לחץ לבחירת תאריך סיום"
                   <?php else: ?>
                   placeholder="Click to choose end date"
                   <?php endif; ?>>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> <?php echo e(__('messages.filter')); ?>

            </button>
            <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-redo"></i> <?php echo e(__('messages.reset')); ?>

            </a>
        </div>
    </form>
</div>

<!-- Messages Table -->
<div class="table-container">
    <?php if($messages->count() > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th class="checkbox-cell">
                    <input type="checkbox" id="selectAll">
                </th>
                <th><?php echo e(__('messages.name')); ?></th>
                <th><?php echo e(__('messages.email')); ?></th>
                <th><?php echo e(__('messages.subject')); ?></th>
                <th><?php echo e(__('messages.message')); ?></th>
                <th><?php echo e(__('messages.status')); ?></th>
                <th><?php echo e(__('messages.date')); ?></th>
                <th><?php echo e(__('messages.actions')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="checkbox-cell">
                    <input type="checkbox" class="message-checkbox" value="<?php echo e($message->id); ?>">
                </td>
                <td><strong><?php echo e($message->name); ?></strong></td>
                <td><span style="color: var(--secondary);"><?php echo e($message->email); ?></span></td>
                <td><?php echo e(Str::limit($message->subject, 30)); ?></td>
                <td class="truncate"><?php echo e(Str::limit($message->message, 50)); ?></td>
                <td>
                    <span class="status-badge <?php echo e($message->status); ?>">
                        <?php if($message->status === 'pending'): ?>
                            <i class="fas fa-clock"></i>
                        <?php elseif($message->status === 'read'): ?>
                            <i class="fas fa-envelope-open"></i>
                        <?php else: ?>
                            <i class="fas fa-archive"></i>
                        <?php endif; ?>
                        <?php echo e(__('messages.' . $message->status)); ?>

                    </span>
                </td>
                <td style="color: var(--secondary); font-weight: 600;">
                    <i class="fas fa-calendar"></i> <?php echo e($message->created_at->format('Y-m-d')); ?><br>
                    <i class="fas fa-clock"></i> <?php echo e($message->created_at->format('H:i')); ?>

                </td>
                <td>
                    <div class="action-buttons">
                        <a href="<?php echo e(route('admin.contacts.show', $message->id)); ?>" class="btn btn-view" title="<?php echo e(__('messages.view_details')); ?>">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="<?php echo e(route('admin.contacts.destroy', $message->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('messages.are_you_sure')); ?>')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-delete" title="<?php echo e(__('messages.delete')); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php echo e($messages->links()); ?>

    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3><?php echo e(__('messages.no_messages_found')); ?></h3>
        <p><?php echo e(__('messages.no_contact_messages_to_display')); ?></p>
    </div>
    <?php endif; ?>
</div>

<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<?php if(app()->getLocale() === 'ar'): ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
<?php elseif(app()->getLocale() === 'he'): ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/he.js"></script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.message-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkIds = document.getElementById('bulkIds');
    const bulkDeleteIds = document.getElementById('bulkDeleteIds');

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
    const currentLocale = '<?php echo e(app()->getLocale()); ?>';
    const dateInputs = document.querySelectorAll('input[type="date"].date-input-locale');
    
    // Flatpickr configuration based on locale
    const flatpickrConfig = {
        dateFormat: 'Y-m-d',
        allowInput: true,
        disableMobile: true, // Force custom calendar instead of native mobile picker
    };

    // Add locale-specific configuration
    if (currentLocale === 'ar') {
        flatpickrConfig.locale = 'ar';
        flatpickrConfig.position = 'auto right'; // RTL positioning
    } else if (currentLocale === 'he') {
        flatpickrConfig.locale = 'he';
        flatpickrConfig.position = 'auto right'; // RTL positioning
    }

    // Initialize Flatpickr on each date input with custom placeholder
    dateInputs.forEach(input => {
        const config = { ...flatpickrConfig };
        
        // Handle placeholder for all languages
        const placeholderText = input.getAttribute('placeholder');
        if (placeholderText) {
            // Store original placeholder
            input.setAttribute('data-placeholder', placeholderText);
            
            // Show placeholder when field is empty
            if (!input.value) {
                input.setAttribute('placeholder', placeholderText);
            }
        }
        
        flatpickr(input, config);
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/admin/contacts/index.blade.php ENDPATH**/ ?>