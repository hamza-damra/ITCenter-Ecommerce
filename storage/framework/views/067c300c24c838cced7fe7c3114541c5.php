<?php $__env->startSection('title', __('messages.create_tag')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-plus"></i> <?php echo e(__('messages.create_new_tag')); ?></h1>
        <p><?php echo e(__('messages.add_tag_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.tags.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_tags')); ?>

        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.tags.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-tag"></i> <?php echo e(__('messages.tag_information')); ?></h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name_en" class="form-label">
                        <?php echo e(__('messages.tag_name_english')); ?>

                        <span class="required">*</span>
                    </label>
                    <input type="text" id="name_en" name="name_en" class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('name_en')); ?>" placeholder="e.g., Gaming, Office, Student" required>
                    <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="name_ar" class="form-label">
                        <?php echo e(__('messages.tag_name_arabic')); ?>

                        <span class="required">*</span>
                    </label>
                    <input type="text" id="name_ar" name="name_ar" class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('name_ar')); ?>" placeholder="أدخل اسم الوسم" required dir="rtl">
                    <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="color" class="form-label">
                        <?php echo e(__('messages.tag_color')); ?>

                    </label>
                    <input type="color" id="color" name="color" class="form-control" 
                           value="<?php echo e(old('color', '#3b82f6')); ?>" style="height: 42px; padding: 4px;">
                    <p class="form-text"><?php echo e(__('messages.tag_color_help')); ?></p>
                </div>

                <div class="form-group">
                    <label for="icon" class="form-label">
                        <?php echo e(__('messages.tag_icon')); ?>

                        <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                    </label>
                    <input type="text" id="icon" name="icon" class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('icon')); ?>" placeholder="e.g., fas fa-gamepad, fas fa-briefcase">
                    <p class="form-text"><?php echo e(__('messages.icon_help_text')); ?></p>
                    <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="position" class="form-label">
                    <?php echo e(__('messages.display_position')); ?>

                    <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                </label>
                <input type="number" id="position" name="position" class="form-control <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       value="<?php echo e(old('position', 0)); ?>" min="0">
                <p class="form-text"><?php echo e(__('messages.lower_numbers_first')); ?></p>
                <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-message"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                    <span>
                        <strong><i class="fas fa-eye"></i> <?php echo e(__('messages.active_label')); ?></strong>
                        <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.display_tag_in_store')); ?></p>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 12px; padding-top: 24px;">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> <?php echo e(__('messages.create_tag_button')); ?>

        </button>
        <a href="<?php echo e(route('admin.tags.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

        </a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/tags/create.blade.php ENDPATH**/ ?>