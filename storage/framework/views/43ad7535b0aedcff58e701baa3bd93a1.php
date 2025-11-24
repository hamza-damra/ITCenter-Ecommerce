<?php $__env->startSection('title', __('messages.create_brand')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-content">
        <h1><?php echo e(__('messages.create_new_brand')); ?></h1>
        <p><?php echo e(__('messages.add_brand_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.brands.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_brands')); ?>

        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-plus-circle"></i> <?php echo e(__('messages.brand_information')); ?></h2>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.brands.store')); ?>" method="POST" class="form-layout">
            <?php echo csrf_field(); ?>

            <!-- Basic Information Section -->
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('messages.basic_information')); ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            <?php echo e(__('messages.brand_name_english')); ?>

                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_en')); ?>" 
                            placeholder="<?php echo e(__('messages.brand_name_placeholder_en')); ?>"
                            required>
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
                            <?php echo e(__('messages.brand_name_arabic')); ?>

                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_ar')); ?>" 
                            placeholder="<?php echo e(__('messages.brand_name_placeholder_ar')); ?>"
                            required 
                            dir="rtl">
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
            </div>

            <!-- Branding Section -->
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('messages.branding_details')); ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="logo" class="form-label">
                            <?php echo e(__('messages.logo_url')); ?>

                        </label>
                        <input 
                            type="url" 
                            id="logo" 
                            name="logo" 
                            class="form-control <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('logo')); ?>" 
                            placeholder="<?php echo e(__('messages.logo_placeholder')); ?>">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.logo_tip')); ?>

                        </p>
                        <?php $__errorArgs = ['logo'];
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
                        <label for="website" class="form-label">
                            <?php echo e(__('messages.website_url')); ?>

                        </label>
                        <input 
                            type="url" 
                            id="website" 
                            name="website" 
                            class="form-control <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('website')); ?>"
                            placeholder="<?php echo e(__('messages.website_placeholder')); ?>">
                        <?php $__errorArgs = ['website'];
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
            </div>

            <!-- Description Section -->
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('messages.descriptions')); ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            <?php echo e(__('messages.brand_description_english')); ?>

                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="<?php echo e(__('messages.brand_description_placeholder_en')); ?>"><?php echo e(old('description_en')); ?></textarea>
                        <?php $__errorArgs = ['description_en'];
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
                        <label for="description_ar" class="form-label">
                            <?php echo e(__('messages.brand_description_arabic')); ?>

                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="<?php echo e(__('messages.brand_description_placeholder_ar')); ?>"><?php echo e(old('description_ar')); ?></textarea>
                        <?php $__errorArgs = ['description_ar'];
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
            </div>

            <!-- Settings Section -->
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('messages.settings')); ?></h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <span>
                            <strong><?php echo e(__('messages.active_brand')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.make_brand_visible')); ?></p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_featured" 
                            name="is_featured" 
                            value="1" 
                            <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                        <span>
                            <strong><?php echo e(__('messages.featured_brand')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.display_featured_section')); ?></p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0; margin-top: 24px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo e(__('messages.create_brand_button')); ?>

                </button>
                <a href="<?php echo e(route('admin.brands.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\brands\create.blade.php ENDPATH**/ ?>