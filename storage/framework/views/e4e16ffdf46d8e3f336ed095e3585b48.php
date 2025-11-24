<?php $__env->startSection('title', __('messages.view_contact_message')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Force RTL for all text elements */
    * {
        text-align: inherit;
    }

    /* RTL Support */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?> !important;
    }

    .back-link:hover {
        gap: 0.75rem;
        color: #5568d3;
    }

    .back-link i {
        <?php echo e(is_rtl() ? 'margin-left: 0.5rem; transform: rotate(180deg);' : 'margin-right: 0.5rem;'); ?>

    }

    .message-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?> !important;
    }

    .message-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        <?php echo e(is_rtl() ? 'flex-direction: row-reverse; justify-content: flex-end;' : ''); ?>

    }

    .message-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
    }

    .meta-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?> !important;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        <?php echo e(is_rtl() ? 'flex-direction: row-reverse; justify-content: flex-end;' : ''); ?>

    }

    .info-row {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.25rem;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
    }

    .info-value {
        font-size: 1rem;
        color: #111827;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
    }

    .message-content {
        line-height: 1.8;
        color: #374151;
        white-space: pre-wrap;
        word-wrap: break-word;
        text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?> !important;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?> !important;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.read {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.archived {
        background: #e5e7eb;
        color: #374151;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        <?php echo e(is_rtl() ? 'flex-direction: row-reverse;' : ''); ?>

    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 4px solid #10b981;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        <?php echo e(is_rtl() ? 'flex-direction: row-reverse; text-align: right;' : ''); ?>

    }
</style>

<a href="<?php echo e(route('admin.contacts.index')); ?>" class="back-link">
    <i class="fas fa-arrow-<?php echo e(is_rtl() ? 'right' : 'left'); ?>"></i>
    <?php echo e(__('messages.back_to_messages')); ?>

</a>

<?php if(session('success')): ?>
<div class="alert-success">
    <i class="fas fa-check-circle"></i>
    <span><?php echo e(session('success')); ?></span>
</div>
<?php endif; ?>

<div class="message-header">
    <h1 class="message-title">
        <i class="fas fa-envelope"></i>
        <span><?php echo e(__('messages.contact_message_details')); ?></span>
    </h1>
    <div class="message-meta">
        <div class="meta-item">
            <span class="meta-label"><?php echo e(__('messages.received')); ?></span>
            <span class="meta-value"><?php echo e($message->created_at->locale(app()->getLocale())->isoFormat('DD MMMM YYYY, HH:mm')); ?></span>
        </div>
        <div class="meta-item">
            <span class="meta-label"><?php echo e(__('messages.status')); ?></span>
            <span class="meta-value">
                <span class="status-badge <?php echo e($message->status); ?>">
                    <?php echo e(__('messages.' . $message->status)); ?>

                </span>
            </span>
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Message Details -->
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <h2 class="card-title">
                <i class="fas fa-user"></i>
                <span><?php echo e(__('messages.sender_information')); ?></span>
            </h2>
            <div class="info-row">
                <div class="info-label"><?php echo e(__('messages.name')); ?></div>
                <div class="info-value"><?php echo e($message->name); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><?php echo e(__('messages.email')); ?></div>
                <div class="info-value">
                    <a href="mailto:<?php echo e($message->email); ?>" style="color: #3b82f6;"><?php echo e($message->email); ?></a>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label"><?php echo e(__('messages.subject')); ?></div>
                <div class="info-value"><?php echo e($message->subject); ?></div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-comment"></i>
                <span><?php echo e(__('messages.message_content')); ?></span>
            </h2>
            <div class="message-content"><?php echo e($message->message); ?></div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div>
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-tools"></i>
                <span><?php echo e(__('messages.actions')); ?></span>
            </h2>
            <div class="action-buttons">
                <!-- Reply via Email -->
                <a href="mailto:<?php echo e($message->email); ?>?subject=Re: <?php echo e(urlencode($message->subject)); ?>" class="btn btn-primary">
                    <i class="fas fa-reply"></i>
                    <span><?php echo e(__('messages.reply_via_email')); ?></span>
                </a>

                <!-- Update Status Buttons -->
                <?php if($message->status !== 'read'): ?>
                <button type="button" onclick="updateMessageStatus('read')" class="btn btn-success" style="width: 100%;">
                    <i class="fas fa-check"></i>
                    <span><?php echo e(__('messages.mark_as_read')); ?></span>
                </button>
                <?php endif; ?>

                <?php if($message->status !== 'archived'): ?>
                <button type="button" onclick="updateMessageStatus('archived')" class="btn btn-secondary" style="width: 100%;">
                    <i class="fas fa-archive"></i>
                    <span><?php echo e(__('messages.archive_message')); ?></span>
                </button>
                <?php endif; ?>

                <?php if($message->status === 'archived'): ?>
                <button type="button" onclick="updateMessageStatus('read')" class="btn btn-success" style="width: 100%;">
                    <i class="fas fa-undo"></i>
                    <span><?php echo e(__('messages.unarchive')); ?></span>
                </button>
                <?php endif; ?>

                <!-- Delete -->
                <button type="button" onclick="confirmDeleteMessage()" class="btn btn-danger" style="width: 100%;">
                    <i class="fas fa-trash"></i>
                    <span><?php echo e(__('messages.delete_message')); ?></span>
                </button>
            </div>
        </div>

        <!-- Message Info Card -->
        <div class="card" style="margin-top: 1.5rem;">
            <h2 class="card-title">
                <i class="fas fa-info-circle"></i>
                <span><?php echo e(__('messages.message_info')); ?></span>
            </h2>
            <div class="info-row">
                <div class="info-label"><?php echo e(__('messages.received_date')); ?></div>
                <div class="info-value"><?php echo e($message->created_at->locale(app()->getLocale())->isoFormat('DD MMMM YYYY')); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><?php echo e(__('messages.received_time')); ?></div>
                <div class="info-value"><?php echo e($message->created_at->locale(app()->getLocale())->isoFormat('HH:mm')); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><?php echo e(__('messages.last_updated')); ?></div>
                <div class="info-value"><?php echo e($message->updated_at->locale(app()->getLocale())->diffForHumans()); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<!-- Update Status Form -->
<form id="updateStatusForm" action="<?php echo e(route('admin.contacts.update-status', $message->id)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>
    <input type="hidden" name="status" id="statusInput" value="">
</form>

<!-- Delete Form -->
<form id="deleteMessageForm" action="<?php echo e(route('admin.contacts.destroy', $message->id)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
function updateMessageStatus(status) {
    document.getElementById('statusInput').value = status;
    document.getElementById('updateStatusForm').submit();
}

function confirmDeleteMessage() {
    if (confirm('<?php echo e(__("messages.are_you_sure_delete_message")); ?>')) {
        document.getElementById('deleteMessageForm').submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\contacts\show.blade.php ENDPATH**/ ?>