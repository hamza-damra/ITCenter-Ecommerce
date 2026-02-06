<?php $__env->startSection('title', __('messages.create_promotional_ad')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .promo-ad-form-grid {
        max-width: 800px;
        margin: 0 auto;
    }

    .image-upload-box {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border: 2px dashed var(--primary);
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: var(--secondary);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-box:hover {
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        border-color: var(--primary-dark);
    }

    .image-upload-box i {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 12px;
        opacity: 0.5;
    }

    .image-upload-box p {
        margin: 0;
        font-size: 14px;
    }

    .image-upload-box .upload-hint {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 8px;
    }

    .image-preview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        display: none;
    }

    .image-preview.has-image {
        display: block;
    }

    .upload-placeholder.hidden {
        display: none;
    }

    .position-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .position-option {
        position: relative;
    }

    .position-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .position-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 24px 16px;
        background: #f8fafc;
        border: 2px solid var(--border);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .position-option input[type="radio"]:checked + label {
        background: #eff6ff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .position-option label:hover {
        border-color: var(--primary-light);
        background: #f0f9ff;
    }

    .position-option label i {
        font-size: 32px;
        color: var(--primary);
    }

    .position-option label span {
        font-weight: 600;
        color: var(--dark);
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_promotional_ad')); ?></h1>
        <p><?php echo e(__('messages.add_new_promotional_ad_description')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.promotional-ads.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_promotional_ads')); ?>

        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.promotional-ads.store')); ?>" method="POST" enctype="multipart/form-data" class="promo-ad-form-grid">
    <?php echo csrf_field(); ?>

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Ad Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> <?php echo e(__('messages.ad_image')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="image" class="form-label">
                        <?php echo e(__('messages.promotional_ad_image')); ?>

                        <span class="required">*</span>
                    </label>
                    <div class="image-upload-box" onclick="document.getElementById('image').click()">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><?php echo e(__('messages.click_to_upload')); ?></p>
                            <p class="upload-hint"><?php echo e(__('messages.promotional_ad_image_help')); ?></p>
                        </div>
                        <img id="imagePreview" class="image-preview" alt="Preview">
                    </div>
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        style="display: none;"
                        required
                        onchange="previewImage(this)">
                    <?php $__errorArgs = ['image'];
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

        <!-- Position Selection Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-arrows-alt-h"></i> <?php echo e(__('messages.ad_position')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">
                        <?php echo e(__('messages.select_position')); ?>

                        <span class="required">*</span>
                    </label>
                    <div class="position-selector">
                        <div class="position-option">
                            <input type="radio" id="position_left" name="position" value="left" <?php echo e(old('position', 'left') == 'left' ? 'checked' : ''); ?> required>
                            <label for="position_left">
                                <i class="fas fa-arrow-left"></i>
                                <span><?php echo e(__('messages.left')); ?></span>
                            </label>
                        </div>
                        <div class="position-option">
                            <input type="radio" id="position_right" name="position" value="right" <?php echo e(old('position') == 'right' ? 'checked' : ''); ?>>
                            <label for="position_right">
                                <i class="fas fa-arrow-right"></i>
                                <span><?php echo e(__('messages.right')); ?></span>
                            </label>
                        </div>
                    </div>
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
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.position_help')); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Title Customization Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-heading"></i> <?php echo e(__('messages.ad_title')); ?> <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span></h2>
            </div>
            <div class="card-body">
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label for="title_en" class="form-label"><?php echo e(__('messages.title_english')); ?></label>
                        <input type="text" id="title_en" name="title_en" class="form-control <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title_en')); ?>" placeholder="<?php echo e(__('messages.title_english_placeholder')); ?>">
                        <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="title_ar" class="form-label"><?php echo e(__('messages.title_arabic')); ?></label>
                        <input type="text" id="title_ar" name="title_ar" class="form-control <?php $__errorArgs = ['title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title_ar')); ?>" placeholder="<?php echo e(__('messages.title_arabic_placeholder')); ?>" dir="rtl">
                        <?php $__errorArgs = ['title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="title_he" class="form-label"><?php echo e(__('messages.title_hebrew')); ?></label>
                        <input type="text" id="title_he" name="title_he" class="form-control <?php $__errorArgs = ['title_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title_he')); ?>" placeholder="<?php echo e(__('messages.title_hebrew_placeholder')); ?>" dir="rtl">
                        <?php $__errorArgs = ['title_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="title_color" class="form-label"><?php echo e(__('messages.title_color')); ?></label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="color" id="title_color_picker" value="<?php echo e(old('title_color', '#FFFFFF')); ?>" 
                                style="width: 50px; height: 38px; border: 1px solid var(--border); border-radius: 4px; cursor: pointer;"
                                onchange="document.getElementById('title_color').value = this.value">
                            <input type="text" id="title_color" name="title_color" class="form-control <?php $__errorArgs = ['title_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('title_color')); ?>" placeholder="#FFFFFF" pattern="^#[0-9A-Fa-f]{6}$"
                                onchange="document.getElementById('title_color_picker').value = this.value || '#FFFFFF'">
                        </div>
                        <?php $__errorArgs = ['title_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="title_font_size" class="form-label"><?php echo e(__('messages.title_font_size')); ?></label>
                        <select id="title_font_size" name="title_font_size" class="form-control <?php $__errorArgs = ['title_font_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value=""><?php echo e(__('messages.select_font_size')); ?></option>
                            <option value="24px" <?php echo e(old('title_font_size') == '24px' ? 'selected' : ''); ?>>24px - <?php echo e(__('messages.small')); ?></option>
                            <option value="32px" <?php echo e(old('title_font_size') == '32px' ? 'selected' : ''); ?>>32px - <?php echo e(__('messages.medium')); ?></option>
                            <option value="40px" <?php echo e(old('title_font_size') == '40px' ? 'selected' : ''); ?>>40px - <?php echo e(__('messages.large')); ?></option>
                            <option value="48px" <?php echo e(old('title_font_size') == '48px' ? 'selected' : ''); ?>>48px - <?php echo e(__('messages.extra_large')); ?></option>
                        </select>
                        <?php $__errorArgs = ['title_font_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtitle Customization Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-font"></i> <?php echo e(__('messages.ad_subtitle')); ?> <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span></h2>
            </div>
            <div class="card-body">
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label for="subtitle_en" class="form-label"><?php echo e(__('messages.subtitle_english')); ?></label>
                        <input type="text" id="subtitle_en" name="subtitle_en" class="form-control <?php $__errorArgs = ['subtitle_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('subtitle_en')); ?>" placeholder="<?php echo e(__('messages.subtitle_english_placeholder')); ?>">
                        <?php $__errorArgs = ['subtitle_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="subtitle_ar" class="form-label"><?php echo e(__('messages.subtitle_arabic')); ?></label>
                        <input type="text" id="subtitle_ar" name="subtitle_ar" class="form-control <?php $__errorArgs = ['subtitle_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('subtitle_ar')); ?>" placeholder="<?php echo e(__('messages.subtitle_arabic_placeholder')); ?>" dir="rtl">
                        <?php $__errorArgs = ['subtitle_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="subtitle_he" class="form-label"><?php echo e(__('messages.subtitle_hebrew')); ?></label>
                        <input type="text" id="subtitle_he" name="subtitle_he" class="form-control <?php $__errorArgs = ['subtitle_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('subtitle_he')); ?>" placeholder="<?php echo e(__('messages.subtitle_hebrew_placeholder')); ?>" dir="rtl">
                        <?php $__errorArgs = ['subtitle_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="subtitle_color" class="form-label"><?php echo e(__('messages.subtitle_color')); ?></label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="color" id="subtitle_color_picker" value="<?php echo e(old('subtitle_color', '#FFFFFF')); ?>" 
                                style="width: 50px; height: 38px; border: 1px solid var(--border); border-radius: 4px; cursor: pointer;"
                                onchange="document.getElementById('subtitle_color').value = this.value">
                            <input type="text" id="subtitle_color" name="subtitle_color" class="form-control <?php $__errorArgs = ['subtitle_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('subtitle_color')); ?>" placeholder="#FFFFFF" pattern="^#[0-9A-Fa-f]{6}$"
                                onchange="document.getElementById('subtitle_color_picker').value = this.value || '#FFFFFF'">
                        </div>
                        <?php $__errorArgs = ['subtitle_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="subtitle_font_size" class="form-label"><?php echo e(__('messages.subtitle_font_size')); ?></label>
                        <select id="subtitle_font_size" name="subtitle_font_size" class="form-control <?php $__errorArgs = ['subtitle_font_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value=""><?php echo e(__('messages.select_font_size')); ?></option>
                            <option value="14px" <?php echo e(old('subtitle_font_size') == '14px' ? 'selected' : ''); ?>>14px - <?php echo e(__('messages.small')); ?></option>
                            <option value="16px" <?php echo e(old('subtitle_font_size') == '16px' ? 'selected' : ''); ?>>16px - <?php echo e(__('messages.medium')); ?></option>
                            <option value="18px" <?php echo e(old('subtitle_font_size') == '18px' ? 'selected' : ''); ?>>18px - <?php echo e(__('messages.large')); ?></option>
                            <option value="20px" <?php echo e(old('subtitle_font_size') == '20px' ? 'selected' : ''); ?>>20px - <?php echo e(__('messages.extra_large')); ?></option>
                        </select>
                        <?php $__errorArgs = ['subtitle_font_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button Customization Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-mouse-pointer"></i> <?php echo e(__('messages.ad_button')); ?> <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span></h2>
            </div>
            <div class="card-body">
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label for="button_text_en" class="form-label"><?php echo e(__('messages.button_text_english')); ?></label>
                        <input type="text" id="button_text_en" name="button_text_en" class="form-control <?php $__errorArgs = ['button_text_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('button_text_en')); ?>" placeholder="<?php echo e(__('messages.button_text_english_placeholder')); ?>">
                        <?php $__errorArgs = ['button_text_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="button_text_ar" class="form-label"><?php echo e(__('messages.button_text_arabic')); ?></label>
                        <input type="text" id="button_text_ar" name="button_text_ar" class="form-control <?php $__errorArgs = ['button_text_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('button_text_ar')); ?>" placeholder="<?php echo e(__('messages.button_text_arabic_placeholder')); ?>" dir="rtl">
                        <?php $__errorArgs = ['button_text_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="button_text_he" class="form-label"><?php echo e(__('messages.button_text_hebrew')); ?></label>
                        <input type="text" id="button_text_he" name="button_text_he" class="form-control <?php $__errorArgs = ['button_text_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('button_text_he')); ?>" placeholder="<?php echo e(__('messages.button_text_hebrew_placeholder')); ?>" dir="rtl">
                        <?php $__errorArgs = ['button_text_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="button_bg_color" class="form-label"><?php echo e(__('messages.button_background_color')); ?></label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="color" id="button_bg_color_picker" value="<?php echo e(old('button_bg_color', '#2563eb')); ?>" 
                                style="width: 50px; height: 38px; border: 1px solid var(--border); border-radius: 4px; cursor: pointer;"
                                onchange="document.getElementById('button_bg_color').value = this.value">
                            <input type="text" id="button_bg_color" name="button_bg_color" class="form-control <?php $__errorArgs = ['button_bg_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('button_bg_color')); ?>" placeholder="#2563eb" pattern="^#[0-9A-Fa-f]{6}$"
                                onchange="document.getElementById('button_bg_color_picker').value = this.value || '#2563eb'">
                        </div>
                        <?php $__errorArgs = ['button_bg_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="button_text_color" class="form-label"><?php echo e(__('messages.button_text_color')); ?></label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="color" id="button_text_color_picker" value="<?php echo e(old('button_text_color', '#FFFFFF')); ?>" 
                                style="width: 50px; height: 38px; border: 1px solid var(--border); border-radius: 4px; cursor: pointer;"
                                onchange="document.getElementById('button_text_color').value = this.value">
                            <input type="text" id="button_text_color" name="button_text_color" class="form-control <?php $__errorArgs = ['button_text_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('button_text_color')); ?>" placeholder="#FFFFFF" pattern="^#[0-9A-Fa-f]{6}$"
                                onchange="document.getElementById('button_text_color_picker').value = this.value || '#FFFFFF'">
                        </div>
                        <?php $__errorArgs = ['button_text_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-message"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Link & Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> <?php echo e(__('messages.link_settings')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="link" class="form-label">
                        <?php echo e(__('messages.ad_link_url')); ?>

                        <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                    </label>
                    <input 
                        type="url" 
                        id="link" 
                        name="link" 
                        class="form-control <?php $__errorArgs = ['link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        value="<?php echo e(old('link')); ?>" 
                        placeholder="<?php echo e(__('messages.ad_link_placeholder')); ?>">
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.ad_link_help')); ?>

                    </p>
                    <?php $__errorArgs = ['link'];
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

                <div class="form-group" style="margin-top: 20px;">
                    <input type="hidden" name="is_active" value="0">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-eye"></i> <?php echo e(__('messages.ad_active')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.ad_active_help')); ?></p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?php echo e(__('messages.create_promotional_ad')); ?>

            </button>
            <a href="<?php echo e(route('admin.promotional-ads.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

            </a>
        </div>
    </div>
</form>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.querySelector('.upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.add('has-image');
            placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/promotional-ads/create.blade.php ENDPATH**/ ?>