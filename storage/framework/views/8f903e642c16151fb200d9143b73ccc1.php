
<?php
    $notificationTypes = [
        'success' => [
            'icon' => 'fas fa-check-circle',
            'title' => __('messages.notification_success_title'),
        ],
        'error' => [
            'icon' => 'fas fa-times-circle',
            'title' => __('messages.notification_error_title'),
        ],
        'warning' => [
            'icon' => 'fas fa-exclamation-triangle',
            'title' => __('messages.notification_warning_title'),
        ],
        'info' => [
            'icon' => 'fas fa-info-circle',
            'title' => __('messages.notification_info_title'),
        ],
    ];

    $isRtl = in_array(app()->getLocale(), ['ar', 'he']);
?>

<div class="admin-notifications-container" id="adminNotifications">
    <?php $__currentLoopData = $notificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(session($type)): ?>
            <div class="admin-notification admin-notification--<?php echo e($type); ?>" dir="<?php echo e($isRtl ? 'rtl' : 'ltr'); ?>" data-notification role="alert">
                <div class="admin-notification__accent"></div>
                <div class="admin-notification__icon">
                    <i class="<?php echo e($config['icon']); ?>"></i>
                </div>
                <div class="admin-notification__body">
                    <div class="admin-notification__title" dir="auto"><?php echo e($config['title']); ?></div>
                    <div class="admin-notification__message" dir="auto"><?php echo e(session($type)); ?></div>
                </div>
                <div class="admin-notification__actions">
                    <span class="admin-notification__time" dir="auto"><?php echo e(__('messages.notification_just_now')); ?></span>
                    <button type="button" class="admin-notification__close" onclick="dismissNotification(this)" aria-label="<?php echo e(__('messages.close')); ?>">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/partials/notifications.blade.php ENDPATH**/ ?>