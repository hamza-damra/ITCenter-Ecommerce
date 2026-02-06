<?php $__env->startSection('title', __('messages.Backup Settings')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" dir="<?php echo e(in_array(app()->getLocale(), ['ar','he']) ? 'rtl' : 'ltr'); ?>">
    <div class="page-header-content">
        <h1>
            <?php if(in_array(app()->getLocale(), ['ar','he'])): ?>
                <?php echo e(__('messages.Backup Settings')); ?> <i class="fas fa-cog"></i>
            <?php else: ?>
                <i class="fas fa-cog"></i> <?php echo e(__('messages.Backup Settings')); ?>

            <?php endif; ?>
        </h1>
        <p><?php echo e(__('messages.Configure automatic backup retention and cleanup policies')); ?></p>
    </div>
    
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <ul style="margin-inline-start: 18px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2><i class="fas fa-sliders-h"></i> <?php echo e(__('messages.Settings')); ?></h2>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.backup.settings.update')); ?>" class="form-layout">
                <?php echo csrf_field(); ?>

                <div class="form-section">
                    <div class="section-title"><i class="fas fa-magic"></i> <?php echo e(__('messages.Automatic Cleanup')); ?></div>

                    <label class="checkbox-group" for="auto_cleanup_enabled">
                        <input id="auto_cleanup_enabled" type="checkbox" name="auto_cleanup_enabled" value="1" <?php echo e(($settings['auto_cleanup_enabled'] ?? true) ? 'checked' : ''); ?>>
                        <span><?php echo e(__('messages.Enable Automatic Cleanup')); ?></span>
                    </label>
                    <div class="form-text"><?php echo e(__('messages.When enabled, expired backups will be automatically deleted daily')); ?></div>
                </div>

                <div class="form-section">
                    <div class="section-title"><i class="fas fa-clock"></i> <?php echo e(__('messages.Auto Backup Schedule')); ?></div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="auto_backup_interval" class="form-label">
                                <?php echo e(__('messages.Auto Backup Interval')); ?> <span class="required">*</span>
                            </label>
                            <select name="auto_backup_interval" id="auto_backup_interval" class="form-control" required>
                                <option value="disabled" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == 'disabled' ? 'selected' : ''); ?>><?php echo e(__('messages.Disabled')); ?></option>
                                <option value="5_minutes" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == '5_minutes' ? 'selected' : ''); ?>><?php echo e(__('messages.Every 5 Minutes')); ?></option>
                                <option value="15_minutes" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == '15_minutes' ? 'selected' : ''); ?>><?php echo e(__('messages.Every 15 Minutes')); ?></option>
                                <option value="30_minutes" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == '30_minutes' ? 'selected' : ''); ?>><?php echo e(__('messages.Every 30 Minutes')); ?></option>
                                <option value="hourly" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == 'hourly' ? 'selected' : ''); ?>><?php echo e(__('messages.Every Hour')); ?></option>
                                <option value="6_hours" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == '6_hours' ? 'selected' : ''); ?>><?php echo e(__('messages.Every 6 Hours')); ?></option>
                                <option value="12_hours" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == '12_hours' ? 'selected' : ''); ?>><?php echo e(__('messages.Every 12 Hours')); ?></option>
                                <option value="daily" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == 'daily' ? 'selected' : ''); ?>><?php echo e(__('messages.Daily')); ?></option>
                                <option value="weekly" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == 'weekly' ? 'selected' : ''); ?>><?php echo e(__('messages.Weekly')); ?></option>
                                <option value="monthly" <?php echo e(($settings['auto_backup_interval'] ?? 'daily') == 'monthly' ? 'selected' : ''); ?>><?php echo e(__('messages.Monthly')); ?></option>
                            </select>
                            <?php $__errorArgs = ['auto_backup_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text"><?php echo e(__('messages.Set how often automatic backups should be created')); ?></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title"><i class="fas fa-calendar-check"></i> <?php echo e(__('messages.Default Retention Policy')); ?></div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="default_retention_days" class="form-label">
                                <?php echo e(__('messages.Default Retention Period')); ?> <span class="required">*</span>
                            </label>
                            <select name="default_retention_days" id="default_retention_days" class="form-control" required>
                                <option value="1" <?php echo e(($settings['default_retention_days'] ?? 30) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.1 Day')); ?></option>
                                <option value="7" <?php echo e(($settings['default_retention_days'] ?? 30) == 7 ? 'selected' : ''); ?>><?php echo e(__('messages.7 Days')); ?></option>
                                <option value="14" <?php echo e(($settings['default_retention_days'] ?? 30) == 14 ? 'selected' : ''); ?>><?php echo e(__('messages.14 Days')); ?></option>
                                <option value="30" <?php echo e(($settings['default_retention_days'] ?? 30) == 30 ? 'selected' : ''); ?>><?php echo e(__('messages.30 Days')); ?></option>
                                <option value="60" <?php echo e(($settings['default_retention_days'] ?? 30) == 60 ? 'selected' : ''); ?>><?php echo e(__('messages.60 Days')); ?></option>
                                <option value="90" <?php echo e(($settings['default_retention_days'] ?? 30) == 90 ? 'selected' : ''); ?>><?php echo e(__('messages.90 Days')); ?></option>
                                <option value="180" <?php echo e(($settings['default_retention_days'] ?? 30) == 180 ? 'selected' : ''); ?>><?php echo e(__('messages.180 Days')); ?></option>
                                <option value="365" <?php echo e(($settings['default_retention_days'] ?? 30) == 365 ? 'selected' : ''); ?>><?php echo e(__('messages.1 Year')); ?></option>
                            </select>
                            <?php $__errorArgs = ['default_retention_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text"><?php echo e(__('messages.This applies to automatic backups. Manual backups can have custom expiration.')); ?></div>
                        </div>

                        <div class="form-group">
                            <label for="max_backups" class="form-label"><?php echo e(__('messages.Maximum Number of Backups')); ?></label>
                            <input type="number" name="max_backups" id="max_backups" class="form-control" min="1" max="100" value="<?php echo e($settings['max_backups'] ?? 10); ?>">
                            <?php $__errorArgs = ['max_backups'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text"><?php echo e(__('messages.Maximum backups to keep regardless of expiration date')); ?></div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <a href="<?php echo e(route('admin.backup.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back to Backups')); ?></a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo e(__('messages.Save Settings')); ?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2><i class="fas fa-trash-alt"></i> <?php echo e(__('messages.Cleanup Expired Backups')); ?></h2>
        </div>
        <div class="card-body">
            <p style="margin-bottom: 15px;"><?php echo e(__('messages.Delete all backups that have exceeded their retention period or are invalid')); ?></p>
            <form method="POST" action="<?php echo e(route('admin.backup.cleanup-expired')); ?>" onsubmit="return confirm('<?php echo e(__('messages.Are you sure you want to delete all expired backups?')); ?>')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-danger"><i class="fas fa-broom"></i> <?php echo e(__('messages.Delete Expired Backups Now')); ?></button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/backup/settings.blade.php ENDPATH**/ ?>