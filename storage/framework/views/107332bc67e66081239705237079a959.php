<?php $__env->startSection('title', __('messages.create_banner')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .banner-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Image Source Selector Styles */
    .image-source-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }

    .source-option {
        flex: 1;
        position: relative;
    }

    .source-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .source-option-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .source-option-label i {
        font-size: 28px;
        margin-bottom: 10px;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .source-option-label .source-title {
        font-weight: 600;
        color: #334155;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .source-option-label .source-desc {
        font-size: 11px;
        color: #94a3b8;
        line-height: 1.3;
    }

    .source-option input[type="radio"]:checked + .source-option-label {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .source-option input[type="radio"]:checked + .source-option-label i {
        color: var(--primary);
        transform: scale(1.1);
    }

    .source-option input[type="radio"]:checked + .source-option-label .source-title {
        color: var(--primary);
    }

    .source-option:hover .source-option-label {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    /* Image Input Sections */
    .image-input-section {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .image-input-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* URL Input Styles */
    .url-input-wrapper {
        position: relative;
    }

    .url-input-wrapper .form-control {
        padding-left: 45px;
    }

    .url-input-wrapper .url-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    .url-preview-container {
        margin-top: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: none;
    }

    .url-preview-container.has-preview {
        display: block;
    }

    .url-preview-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 6px;
        display: block;
        margin: 0 auto;
    }

    .url-preview-error {
        color: #ef4444;
        text-align: center;
        padding: 20px;
        display: none;
    }

    .url-preview-error.show {
        display: block;
    }

    .url-preview-error i {
        font-size: 32px;
        margin-bottom: 8px;
        display: block;
    }

    /* Storage Info Badge */
    .storage-info {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: #fef3c7;
        color: #92400e;
        border-radius: 6px;
        font-size: 12px;
        margin-top: 12px;
    }

    .storage-info.info-database {
        background: #dbeafe;
        color: #1e40af;
    }

    .storage-info.info-url {
        background: #d1fae5;
        color: #065f46;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_banner')); ?></h1>
        <p><?php echo e(__('messages.add_new_banner_to_slider') ?? 'Add a new banner to the homepage slider'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.banners.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_banners') ?? 'Back to Banners'); ?>

        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.banners.store')); ?>" method="POST" enctype="multipart/form-data" class="banner-form-grid">
    <?php echo csrf_field(); ?>

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Banner Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> <?php echo e(__('messages.banner_image')); ?></h2>
            </div>
            <div class="card-body">
                
                <!-- Image Source Selector -->
                <div class="form-group">
                    <label class="form-label">
                        <?php echo e(__('messages.image_source') ?? 'Image Source'); ?>

                        <span class="required">*</span>
                    </label>
                    
                    <div class="image-source-selector">
                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_database" value="database" 
                                   <?php echo e(old('image_source', 'database') === 'database' ? 'checked' : ''); ?>

                                   onchange="toggleImageSource('database')">
                            <label for="source_database" class="source-option-label">
                                <i class="fas fa-database"></i>
                                <span class="source-title"><?php echo e(__('messages.store_in_database') ?? 'Store in Database'); ?></span>
                                <span class="source-desc"><?php echo e(__('messages.store_in_database_desc') ?? 'Upload and store image directly in database'); ?></span>
                            </label>
                        </div>
                        
                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_url" value="url"
                                   <?php echo e(old('image_source') === 'url' ? 'checked' : ''); ?>

                                   onchange="toggleImageSource('url')">
                            <label for="source_url" class="source-option-label">
                                <i class="fas fa-link"></i>
                                <span class="source-title"><?php echo e(__('messages.external_url') ?? 'External URL'); ?></span>
                                <span class="source-desc"><?php echo e(__('messages.external_url_desc') ?? 'Use image URL from the internet'); ?></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Database/File Upload Section -->
                <div id="upload-section" class="image-input-section <?php echo e(old('image_source', 'database') !== 'url' ? 'active' : ''); ?>">
                    <div class="form-group">
                        <label for="image" class="form-label">
                            <?php echo e(__('messages.upload_image') ?? 'Upload Image'); ?>

                            <span class="required">*</span>
                        </label>
                        <div class="image-upload-box" onclick="document.getElementById('image').click()">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p><?php echo e(__('messages.click_to_upload') ?? 'Click to upload image'); ?></p>
                                <p class="upload-hint"><?php echo e(__('messages.banner_image_help')); ?></p>
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
                            onchange="previewUploadedImage(this)">
                        
                        <div class="storage-info info-database">
                            <i class="fas fa-info-circle"></i>
                            <?php echo e(__('messages.database_storage_info')); ?>

                        </div>
                        
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

                <!-- External URL Section -->
                <div id="url-section" class="image-input-section <?php echo e(old('image_source') === 'url' ? 'active' : ''); ?>">
                    <div class="form-group">
                        <label for="image_url" class="form-label">
                            <?php echo e(__('messages.image_url') ?? 'Image URL'); ?>

                            <span class="required">*</span>
                        </label>
                        <div class="url-input-wrapper">
                            <i class="fas fa-globe url-icon"></i>
                            <input 
                                type="url" 
                                id="image_url" 
                                name="image_url" 
                                class="form-control <?php $__errorArgs = ['image_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('image_url')); ?>"
                                placeholder="<?php echo e(__('messages.enter_image_url') ?? 'https://example.com/image.jpg'); ?>"
                                oninput="previewUrlImage(this.value)">
                        </div>
                        
                        <div class="storage-info info-url">
                            <i class="fas fa-info-circle"></i>
                            <?php echo e(__('messages.url_storage_info') ?? 'Image will be loaded from external URL. Make sure the URL is accessible.'); ?>

                        </div>
                        
                        <!-- URL Preview -->
                        <div id="urlPreviewContainer" class="url-preview-container">
                            <img id="urlPreviewImage" class="url-preview-image" alt="URL Preview" onerror="showUrlError()" onload="hideUrlError()">
                            <div id="urlPreviewError" class="url-preview-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span><?php echo e(__('messages.image_load_failed') ?? 'Failed to load image. Please check the URL.'); ?></span>
                            </div>
                        </div>
                        
                        <?php $__errorArgs = ['image_url'];
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
        </div>

        <!-- Title Fields Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-heading"></i> <?php echo e(__('messages.banner_title')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_en" class="form-label">
                            <?php echo e(__('messages.title_english')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.at_least_one_required') ?? 'At least one required'); ?>)</span>
                        </label>
                        <input 
                            type="text" 
                            id="title_en" 
                            name="title_en" 
                            class="form-control <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title_en')); ?>" 
                            placeholder="<?php echo e(__('messages.enter_title_english') ?? 'Enter title in English'); ?>">
                        <?php $__errorArgs = ['title_en'];
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
                        <label for="title_ar" class="form-label">
                            <?php echo e(__('messages.title_arabic')); ?>

                        </label>
                        <input 
                            type="text" 
                            id="title_ar" 
                            name="title_ar" 
                            class="form-control <?php $__errorArgs = ['title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title_ar')); ?>" 
                            placeholder="<?php echo e(__('messages.enter_title_arabic') ?? 'Enter title in Arabic'); ?>"
                            dir="rtl">
                        <?php $__errorArgs = ['title_ar'];
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
                        <label for="title_he" class="form-label">
                            <?php echo e(__('messages.title_hebrew')); ?>

                        </label>
                        <input 
                            type="text" 
                            id="title_he" 
                            name="title_he" 
                            class="form-control <?php $__errorArgs = ['title_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title_he')); ?>" 
                            placeholder="<?php echo e(__('messages.enter_title_hebrew') ?? 'Enter title in Hebrew'); ?>"
                            dir="rtl">
                        <?php $__errorArgs = ['title_he'];
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
        </div>

        <!-- Subtitle Fields Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> <?php echo e(__('messages.banner_subtitle')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="subtitle_en" class="form-label">
                            <?php echo e(__('messages.subtitle_english')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <textarea 
                            id="subtitle_en" 
                            name="subtitle_en" 
                            class="form-control <?php $__errorArgs = ['subtitle_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="<?php echo e(__('messages.enter_subtitle_english') ?? 'Enter subtitle in English'); ?>"
                            style="min-height: 80px;"><?php echo e(old('subtitle_en')); ?></textarea>
                        <?php $__errorArgs = ['subtitle_en'];
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
                        <label for="subtitle_ar" class="form-label">
                            <?php echo e(__('messages.subtitle_arabic')); ?>

                        </label>
                        <textarea 
                            id="subtitle_ar" 
                            name="subtitle_ar" 
                            class="form-control <?php $__errorArgs = ['subtitle_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="<?php echo e(__('messages.enter_subtitle_arabic') ?? 'Enter subtitle in Arabic'); ?>"
                            dir="rtl"
                            style="min-height: 80px;"><?php echo e(old('subtitle_ar')); ?></textarea>
                        <?php $__errorArgs = ['subtitle_ar'];
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
                        <label for="subtitle_he" class="form-label">
                            <?php echo e(__('messages.subtitle_hebrew')); ?>

                        </label>
                        <textarea 
                            id="subtitle_he" 
                            name="subtitle_he" 
                            class="form-control <?php $__errorArgs = ['subtitle_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            placeholder="<?php echo e(__('messages.enter_subtitle_hebrew') ?? 'Enter subtitle in Hebrew'); ?>"
                            dir="rtl"
                            style="min-height: 80px;"><?php echo e(old('subtitle_he')); ?></textarea>
                        <?php $__errorArgs = ['subtitle_he'];
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
        </div>

        <!-- Button Text Fields Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-mouse-pointer"></i> <?php echo e(__('messages.banner_button_text')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="button_text_en" class="form-label">
                            <?php echo e(__('messages.button_text_english')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <input 
                            type="text" 
                            id="button_text_en" 
                            name="button_text_en" 
                            class="form-control <?php $__errorArgs = ['button_text_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('button_text_en')); ?>" 
                            placeholder="<?php echo e(__('messages.enter_button_text') ?? 'e.g., Shop Now'); ?>">
                        <?php $__errorArgs = ['button_text_en'];
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
                        <label for="button_text_ar" class="form-label">
                            <?php echo e(__('messages.button_text_arabic')); ?>

                        </label>
                        <input 
                            type="text" 
                            id="button_text_ar" 
                            name="button_text_ar" 
                            class="form-control <?php $__errorArgs = ['button_text_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('button_text_ar')); ?>" 
                            placeholder="<?php echo e(__('messages.enter_button_text_arabic') ?? 'e.g., تسوق الآن'); ?>"
                            dir="rtl">
                        <?php $__errorArgs = ['button_text_ar'];
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
                        <label for="button_text_he" class="form-label">
                            <?php echo e(__('messages.button_text_hebrew')); ?>

                        </label>
                        <input 
                            type="text" 
                            id="button_text_he" 
                            name="button_text_he" 
                            class="form-control <?php $__errorArgs = ['button_text_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('button_text_he')); ?>" 
                            placeholder="<?php echo e(__('messages.enter_button_text_hebrew') ?? 'e.g., קנה עכשיו'); ?>"
                            dir="rtl">
                        <?php $__errorArgs = ['button_text_he'];
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
        </div>

        <!-- Color Customization Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-palette"></i> <?php echo e(__('messages.color_customization') ?? 'Color Customization'); ?></h2>
            </div>
            <div class="card-body">
                <p class="form-text" style="margin-bottom: 16px;">
                    <i class="fas fa-info-circle"></i> <?php echo e(__('messages.color_customization_help') ?? 'Customize the colors of the banner text and button. Leave empty to use default colors.'); ?>

                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_color" class="form-label">
                            <?php echo e(__('messages.title_color') ?? 'Title Color'); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="title_color_picker" 
                                value="<?php echo e(old('title_color', '#ffffff')); ?>"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('title_color').value = this.value">
                            <input 
                                type="text" 
                                id="title_color" 
                                name="title_color" 
                                class="form-control <?php $__errorArgs = ['title_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('title_color')); ?>" 
                                placeholder="#ffffff"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('title_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('title_color').value = ''; document.getElementById('title_color_picker').value = '#ffffff';" title="<?php echo e(__('messages.clear') ?? 'Clear'); ?>">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['title_color'];
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
                        <label for="subtitle_color" class="form-label">
                            <?php echo e(__('messages.subtitle_color') ?? 'Subtitle Color'); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="subtitle_color_picker" 
                                value="<?php echo e(old('subtitle_color', '#e2e8f0')); ?>"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('subtitle_color').value = this.value">
                            <input 
                                type="text" 
                                id="subtitle_color" 
                                name="subtitle_color" 
                                class="form-control <?php $__errorArgs = ['subtitle_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('subtitle_color')); ?>" 
                                placeholder="#e2e8f0"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('subtitle_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('subtitle_color').value = ''; document.getElementById('subtitle_color_picker').value = '#e2e8f0';" title="<?php echo e(__('messages.clear') ?? 'Clear'); ?>">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['subtitle_color'];
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

                <div class="form-row" style="margin-top: 16px;">
                    <div class="form-group">
                        <label for="button_bg_color" class="form-label">
                            <?php echo e(__('messages.button_bg_color') ?? 'Button Background Color'); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="button_bg_color_picker" 
                                value="<?php echo e(old('button_bg_color', '#3b82f6')); ?>"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('button_bg_color').value = this.value">
                            <input 
                                type="text" 
                                id="button_bg_color" 
                                name="button_bg_color" 
                                class="form-control <?php $__errorArgs = ['button_bg_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('button_bg_color')); ?>" 
                                placeholder="#3b82f6"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('button_bg_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('button_bg_color').value = ''; document.getElementById('button_bg_color_picker').value = '#3b82f6';" title="<?php echo e(__('messages.clear') ?? 'Clear'); ?>">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['button_bg_color'];
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
                        <label for="button_text_color" class="form-label">
                            <?php echo e(__('messages.button_text_color') ?? 'Button Text Color'); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="button_text_color_picker" 
                                value="<?php echo e(old('button_text_color', '#ffffff')); ?>"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('button_text_color').value = this.value">
                            <input 
                                type="text" 
                                id="button_text_color" 
                                name="button_text_color" 
                                class="form-control <?php $__errorArgs = ['button_text_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('button_text_color')); ?>" 
                                placeholder="#ffffff"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('button_text_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('button_text_color').value = ''; document.getElementById('button_text_color_picker').value = '#ffffff';" title="<?php echo e(__('messages.clear') ?? 'Clear'); ?>">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['button_text_color'];
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
        </div>

        <!-- Link & Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> <?php echo e(__('messages.link_settings') ?? 'Link & Settings'); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="link" class="form-label">
                            <?php echo e(__('messages.banner_link_url')); ?>

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
                            placeholder="<?php echo e(__('messages.banner_link_placeholder')); ?>">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.banner_link_help')); ?>

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

                    <div class="form-group">
                        <label for="display_order" class="form-label">
                            <?php echo e(__('messages.display_order')); ?>

                        </label>
                        <input 
                            type="number" 
                            id="display_order" 
                            name="display_order" 
                            class="form-control <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('display_order', 0)); ?>" 
                            min="0"
                            placeholder="0">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.banner_display_order_help')); ?>

                        </p>
                        <?php $__errorArgs = ['display_order'];
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

                <div class="form-group" style="margin-top: 16px;">
                    <input type="hidden" name="is_active" value="0">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-eye"></i> <?php echo e(__('messages.banner_active')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.banner_active_help')); ?></p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?php echo e(__('messages.create_banner')); ?>

            </button>
            <a href="<?php echo e(route('admin.banners.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

            </a>
        </div>
    </div>
</form>

<script>
// Toggle between image source sections
function toggleImageSource(source) {
    const uploadSection = document.getElementById('upload-section');
    const urlSection = document.getElementById('url-section');
    
    if (source === 'url') {
        uploadSection.classList.remove('active');
        urlSection.classList.add('active');
        document.getElementById('image').removeAttribute('required');
    } else {
        urlSection.classList.remove('active');
        uploadSection.classList.add('active');
    }
}

// Preview uploaded image
function previewUploadedImage(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    
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

// Preview URL image with debounce
let urlPreviewTimeout;
function previewUrlImage(url) {
    clearTimeout(urlPreviewTimeout);
    
    const container = document.getElementById('urlPreviewContainer');
    const image = document.getElementById('urlPreviewImage');
    const error = document.getElementById('urlPreviewError');
    
    if (!url || url.trim() === '') {
        container.classList.remove('has-preview');
        return;
    }
    
    // Debounce to avoid too many requests
    urlPreviewTimeout = setTimeout(() => {
        container.classList.add('has-preview');
        error.classList.remove('show');
        image.style.display = 'block';
        image.src = url;
    }, 500);
}

function showUrlError() {
    const image = document.getElementById('urlPreviewImage');
    const error = document.getElementById('urlPreviewError');
    image.style.display = 'none';
    error.classList.add('show');
}

function hideUrlError() {
    const error = document.getElementById('urlPreviewError');
    error.classList.remove('show');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if URL was previously selected (for form validation errors)
    const urlRadio = document.getElementById('source_url');
    if (urlRadio && urlRadio.checked) {
        toggleImageSource('url');
        const urlInput = document.getElementById('image_url');
        if (urlInput && urlInput.value) {
            previewUrlImage(urlInput.value);
        }
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/banners/create.blade.php ENDPATH**/ ?>