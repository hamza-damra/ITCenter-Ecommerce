<?php $__env->startSection('title', __('messages.add_section')); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div>
                <h1><?php echo e(__('messages.add_section')); ?></h1>
                <p><?php echo e(__('messages.add_section_subtitle')); ?></p>
            </div>
        </div>
        <div class="page-actions">
            <a href="<?php echo e(route('admin.home-sections.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_list')); ?>

            </a>
        </div>
    </div>
</div>

<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong><?php echo e(__('messages.please_correct_errors')); ?></strong>
        <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<form action="<?php echo e(route('admin.home-sections.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-cog"></i> <?php echo e(__('messages.section_settings')); ?></h2>
        </div>
        <div class="card-body">
            <div class="form-layout">
                <!-- Display Order -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('messages.display_order')); ?> <span class="required">*</span></label>
                        <input type="number" name="display_order" class="form-control <?php echo e($errors->has('display_order') ? 'is-invalid' : ''); ?>"
                            value="<?php echo e(old('display_order', $maxOrder + 1)); ?>" min="0" required>
                        <div class="form-text"><?php echo e(__('messages.display_order_help')); ?></div>
                        <?php if($errors->has('display_order')): ?>
                            <div class="error-message"><?php echo e($errors->first('display_order')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Active Toggle -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                            <label for="is_active">
                                <strong><?php echo e(__('messages.active')); ?></strong>
                                <br><span style="font-size: 0.85rem; color: var(--secondary);"><?php echo e(__('messages.section_active_help')); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Multilingual Content -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2><i class="fas fa-language"></i> <?php echo e(__('messages.multilingual_content')); ?></h2>
        </div>
        <div class="card-body">
            <div class="form-layout">
                <!-- English -->
                <div class="form-section">
                    <div class="section-title"><?php echo e(__('messages.english')); ?></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('messages.title_english')); ?></label>
                            <input type="text" name="title_en" class="form-control" value="<?php echo e(old('title_en')); ?>" maxlength="120">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('messages.subtitle')); ?> (<?php echo e(__('messages.english')); ?>)</label>
                            <input type="text" name="subtitle_en" class="form-control" value="<?php echo e(old('subtitle_en')); ?>" maxlength="255">
                        </div>
                    </div>
                </div>

                <!-- Arabic -->
                <div class="form-section">
                    <div class="section-title"><?php echo e(__('messages.arabic')); ?></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('messages.title_arabic')); ?></label>
                            <input type="text" name="title_ar" class="form-control" value="<?php echo e(old('title_ar')); ?>" maxlength="120" dir="rtl">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('messages.subtitle')); ?> (<?php echo e(__('messages.arabic')); ?>)</label>
                            <input type="text" name="subtitle_ar" class="form-control" value="<?php echo e(old('subtitle_ar')); ?>" maxlength="255" dir="rtl">
                        </div>
                    </div>
                </div>

                <!-- Hebrew -->
                <div class="form-section">
                    <div class="section-title"><?php echo e(__('messages.hebrew')); ?></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('messages.title_hebrew')); ?></label>
                            <input type="text" name="title_he" class="form-control" value="<?php echo e(old('title_he')); ?>" maxlength="120" dir="rtl">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('messages.subtitle')); ?> (<?php echo e(__('messages.hebrew')); ?>)</label>
                            <input type="text" name="subtitle_he" class="form-control" value="<?php echo e(old('subtitle_he')); ?>" maxlength="255" dir="rtl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section-Specific Settings -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2><i class="fas fa-sliders-h"></i> <?php echo e(__('messages.advanced_settings')); ?></h2>
        </div>
        <div class="card-body">
            <div class="form-layout">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('messages.max_products')); ?></label>
                        <input type="number" name="settings[max_products]" class="form-control"
                            value="<?php echo e(old('settings.max_products', 8)); ?>" min="1" max="50">
                        <div class="form-text"><?php echo e(__('messages.max_products_help')); ?></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('messages.cards_to_scroll')); ?></label>
                        <input type="number" name="settings[cards_to_scroll]" class="form-control"
                            value="<?php echo e(old('settings.cards_to_scroll', 1)); ?>" min="1" max="10">
                        <div class="form-text"><?php echo e(__('messages.cards_to_scroll_help')); ?></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('messages.auto_scroll_interval')); ?></label>
                        <input type="number" name="settings[auto_scroll_interval]" class="form-control"
                            value="<?php echo e(old('settings.auto_scroll_interval', 5000)); ?>" min="1000" max="30000" step="500">
                        <div class="form-text"><?php echo e(__('messages.auto_scroll_interval_help')); ?></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('messages.background_color')); ?></label>
                        <input type="text" name="settings[background_color]" class="form-control"
                            value="<?php echo e(old('settings.background_color')); ?>" placeholder="#ffffff">
                        <div class="form-text"><?php echo e(__('messages.background_color_help')); ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Submit -->
    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="<?php echo e(route('admin.home-sections.index')); ?>" class="btn btn-secondary"><?php echo e(__('messages.cancel')); ?></a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo e(__('messages.save_section')); ?>

        </button>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/home-sections/create.blade.php ENDPATH**/ ?>