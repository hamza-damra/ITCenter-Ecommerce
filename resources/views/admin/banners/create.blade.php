@extends('admin.layout')

@section('title', __('messages.create_banner'))

@section('content')
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
        <h1><i class="fas fa-plus-circle"></i> {{ __('messages.create_banner') }}</h1>
        <p>{{ __('messages.add_new_banner_to_slider') ?? 'Add a new banner to the homepage slider' }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_banners') ?? 'Back to Banners' }}
        </a>
    </div>
</div>

<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="banner-form-grid">
    @csrf

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Banner Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> {{ __('messages.banner_image') }}</h2>
            </div>
            <div class="card-body">
                
                <!-- Image Source Selector -->
                <div class="form-group">
                    <label class="form-label">
                        {{ __('messages.image_source') ?? 'Image Source' }}
                        <span class="required">*</span>
                    </label>
                    
                    <div class="image-source-selector">
                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_database" value="database" 
                                   {{ old('image_source', 'database') === 'database' ? 'checked' : '' }}
                                   onchange="toggleImageSource('database')">
                            <label for="source_database" class="source-option-label">
                                <i class="fas fa-database"></i>
                                <span class="source-title">{{ __('messages.store_in_database') ?? 'Store in Database' }}</span>
                                <span class="source-desc">{{ __('messages.store_in_database_desc') ?? 'Upload and store image directly in database' }}</span>
                            </label>
                        </div>
                        
                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_url" value="url"
                                   {{ old('image_source') === 'url' ? 'checked' : '' }}
                                   onchange="toggleImageSource('url')">
                            <label for="source_url" class="source-option-label">
                                <i class="fas fa-link"></i>
                                <span class="source-title">{{ __('messages.external_url') ?? 'External URL' }}</span>
                                <span class="source-desc">{{ __('messages.external_url_desc') ?? 'Use image URL from the internet' }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Database/File Upload Section -->
                <div id="upload-section" class="image-input-section {{ old('image_source', 'database') !== 'url' ? 'active' : '' }}">
                    <div class="form-group">
                        <label for="image" class="form-label">
                            {{ __('messages.upload_image') ?? 'Upload Image' }}
                            <span class="required">*</span>
                        </label>
                        <div class="image-upload-box" onclick="document.getElementById('image').click()">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>{{ __('messages.click_to_upload') ?? 'Click to upload image' }}</p>
                                <p class="upload-hint">{{ __('messages.banner_image_help') }}</p>
                            </div>
                            <img id="imagePreview" class="image-preview" alt="Preview">
                        </div>
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            class="form-control @error('image') is-invalid @enderror" 
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            style="display: none;"
                            onchange="previewUploadedImage(this)">
                        
                        <div class="storage-info info-database">
                            <i class="fas fa-info-circle"></i>
                            {{ __('messages.database_storage_info') }}
                        </div>
                        
                        @error('image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- External URL Section -->
                <div id="url-section" class="image-input-section {{ old('image_source') === 'url' ? 'active' : '' }}">
                    <div class="form-group">
                        <label for="image_url" class="form-label">
                            {{ __('messages.image_url') ?? 'Image URL' }}
                            <span class="required">*</span>
                        </label>
                        <div class="url-input-wrapper">
                            <i class="fas fa-globe url-icon"></i>
                            <input 
                                type="url" 
                                id="image_url" 
                                name="image_url" 
                                class="form-control @error('image_url') is-invalid @enderror" 
                                value="{{ old('image_url') }}"
                                placeholder="{{ __('messages.enter_image_url') ?? 'https://example.com/image.jpg' }}"
                                oninput="previewUrlImage(this.value)">
                        </div>
                        
                        <div class="storage-info info-url">
                            <i class="fas fa-info-circle"></i>
                            {{ __('messages.url_storage_info') ?? 'Image will be loaded from external URL. Make sure the URL is accessible.' }}
                        </div>
                        
                        <!-- URL Preview -->
                        <div id="urlPreviewContainer" class="url-preview-container">
                            <img id="urlPreviewImage" class="url-preview-image" alt="URL Preview" onerror="showUrlError()" onload="hideUrlError()">
                            <div id="urlPreviewError" class="url-preview-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>{{ __('messages.image_load_failed') ?? 'Failed to load image. Please check the URL.' }}</span>
                            </div>
                        </div>
                        
                        @error('image_url')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <!-- Title Fields Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-heading"></i> {{ __('messages.banner_title') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_en" class="form-label">
                            {{ __('messages.title_english') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.at_least_one_required') ?? 'At least one required' }})</span>
                        </label>
                        <input 
                            type="text" 
                            id="title_en" 
                            name="title_en" 
                            class="form-control @error('title_en') is-invalid @enderror" 
                            value="{{ old('title_en') }}" 
                            placeholder="{{ __('messages.enter_title_english') ?? 'Enter title in English' }}">
                        @error('title_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_ar" class="form-label">
                            {{ __('messages.title_arabic') }}
                        </label>
                        <input 
                            type="text" 
                            id="title_ar" 
                            name="title_ar" 
                            class="form-control @error('title_ar') is-invalid @enderror" 
                            value="{{ old('title_ar') }}" 
                            placeholder="{{ __('messages.enter_title_arabic') ?? 'Enter title in Arabic' }}"
                            dir="rtl">
                        @error('title_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_he" class="form-label">
                            {{ __('messages.title_hebrew') }}
                        </label>
                        <input 
                            type="text" 
                            id="title_he" 
                            name="title_he" 
                            class="form-control @error('title_he') is-invalid @enderror" 
                            value="{{ old('title_he') }}" 
                            placeholder="{{ __('messages.enter_title_hebrew') ?? 'Enter title in Hebrew' }}"
                            dir="rtl">
                        @error('title_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtitle Fields Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> {{ __('messages.banner_subtitle') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="subtitle_en" class="form-label">
                            {{ __('messages.subtitle_english') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <textarea 
                            id="subtitle_en" 
                            name="subtitle_en" 
                            class="form-control @error('subtitle_en') is-invalid @enderror" 
                            placeholder="{{ __('messages.enter_subtitle_english') ?? 'Enter subtitle in English' }}"
                            style="min-height: 80px;">{{ old('subtitle_en') }}</textarea>
                        @error('subtitle_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subtitle_ar" class="form-label">
                            {{ __('messages.subtitle_arabic') }}
                        </label>
                        <textarea 
                            id="subtitle_ar" 
                            name="subtitle_ar" 
                            class="form-control @error('subtitle_ar') is-invalid @enderror" 
                            placeholder="{{ __('messages.enter_subtitle_arabic') ?? 'Enter subtitle in Arabic' }}"
                            dir="rtl"
                            style="min-height: 80px;">{{ old('subtitle_ar') }}</textarea>
                        @error('subtitle_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subtitle_he" class="form-label">
                            {{ __('messages.subtitle_hebrew') }}
                        </label>
                        <textarea 
                            id="subtitle_he" 
                            name="subtitle_he" 
                            class="form-control @error('subtitle_he') is-invalid @enderror" 
                            placeholder="{{ __('messages.enter_subtitle_hebrew') ?? 'Enter subtitle in Hebrew' }}"
                            dir="rtl"
                            style="min-height: 80px;">{{ old('subtitle_he') }}</textarea>
                        @error('subtitle_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Button Text Fields Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-mouse-pointer"></i> {{ __('messages.banner_button_text') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="button_text_en" class="form-label">
                            {{ __('messages.button_text_english') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <input 
                            type="text" 
                            id="button_text_en" 
                            name="button_text_en" 
                            class="form-control @error('button_text_en') is-invalid @enderror" 
                            value="{{ old('button_text_en') }}" 
                            placeholder="{{ __('messages.enter_button_text') ?? 'e.g., Shop Now' }}">
                        @error('button_text_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="button_text_ar" class="form-label">
                            {{ __('messages.button_text_arabic') }}
                        </label>
                        <input 
                            type="text" 
                            id="button_text_ar" 
                            name="button_text_ar" 
                            class="form-control @error('button_text_ar') is-invalid @enderror" 
                            value="{{ old('button_text_ar') }}" 
                            placeholder="{{ __('messages.enter_button_text_arabic') ?? 'e.g., تسوق الآن' }}"
                            dir="rtl">
                        @error('button_text_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="button_text_he" class="form-label">
                            {{ __('messages.button_text_hebrew') }}
                        </label>
                        <input 
                            type="text" 
                            id="button_text_he" 
                            name="button_text_he" 
                            class="form-control @error('button_text_he') is-invalid @enderror" 
                            value="{{ old('button_text_he') }}" 
                            placeholder="{{ __('messages.enter_button_text_hebrew') ?? 'e.g., קנה עכשיו' }}"
                            dir="rtl">
                        @error('button_text_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Color Customization Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-palette"></i> {{ __('messages.color_customization') ?? 'Color Customization' }}</h2>
            </div>
            <div class="card-body">
                <p class="form-text" style="margin-bottom: 16px;">
                    <i class="fas fa-info-circle"></i> {{ __('messages.color_customization_help') ?? 'Customize the colors of the banner text and button. Leave empty to use default colors.' }}
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_color" class="form-label">
                            {{ __('messages.title_color') ?? 'Title Color' }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="title_color_picker" 
                                value="{{ old('title_color', '#ffffff') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('title_color').value = this.value">
                            <input 
                                type="text" 
                                id="title_color" 
                                name="title_color" 
                                class="form-control @error('title_color') is-invalid @enderror" 
                                value="{{ old('title_color') }}" 
                                placeholder="#ffffff"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('title_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('title_color').value = ''; document.getElementById('title_color_picker').value = '#ffffff';" title="{{ __('messages.clear') ?? 'Clear' }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @error('title_color')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subtitle_color" class="form-label">
                            {{ __('messages.subtitle_color') ?? 'Subtitle Color' }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="subtitle_color_picker" 
                                value="{{ old('subtitle_color', '#e2e8f0') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('subtitle_color').value = this.value">
                            <input 
                                type="text" 
                                id="subtitle_color" 
                                name="subtitle_color" 
                                class="form-control @error('subtitle_color') is-invalid @enderror" 
                                value="{{ old('subtitle_color') }}" 
                                placeholder="#e2e8f0"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('subtitle_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('subtitle_color').value = ''; document.getElementById('subtitle_color_picker').value = '#e2e8f0';" title="{{ __('messages.clear') ?? 'Clear' }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @error('subtitle_color')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row" style="margin-top: 16px;">
                    <div class="form-group">
                        <label for="button_bg_color" class="form-label">
                            {{ __('messages.button_bg_color') ?? 'Button Background Color' }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="button_bg_color_picker" 
                                value="{{ old('button_bg_color', '#3b82f6') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('button_bg_color').value = this.value">
                            <input 
                                type="text" 
                                id="button_bg_color" 
                                name="button_bg_color" 
                                class="form-control @error('button_bg_color') is-invalid @enderror" 
                                value="{{ old('button_bg_color') }}" 
                                placeholder="#3b82f6"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('button_bg_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('button_bg_color').value = ''; document.getElementById('button_bg_color_picker').value = '#3b82f6';" title="{{ __('messages.clear') ?? 'Clear' }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @error('button_bg_color')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="button_text_color" class="form-label">
                            {{ __('messages.button_text_color') ?? 'Button Text Color' }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="color" 
                                id="button_text_color_picker" 
                                value="{{ old('button_text_color', '#ffffff') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('button_text_color').value = this.value">
                            <input 
                                type="text" 
                                id="button_text_color" 
                                name="button_text_color" 
                                class="form-control @error('button_text_color') is-invalid @enderror" 
                                value="{{ old('button_text_color') }}" 
                                placeholder="#ffffff"
                                pattern="^#[0-9A-Fa-f]{6}$"
                                style="flex: 1;"
                                oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('button_text_color_picker').value = this.value">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('button_text_color').value = ''; document.getElementById('button_text_color_picker').value = '#ffffff';" title="{{ __('messages.clear') ?? 'Clear' }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @error('button_text_color')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Link & Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> {{ __('messages.link_settings') ?? 'Link & Settings' }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="link" class="form-label">
                            {{ __('messages.banner_link_url') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <input 
                            type="url" 
                            id="link" 
                            name="link" 
                            class="form-control @error('link') is-invalid @enderror" 
                            value="{{ old('link') }}" 
                            placeholder="{{ __('messages.banner_link_placeholder') }}">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.banner_link_help') }}
                        </p>
                        @error('link')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="display_order" class="form-label">
                            {{ __('messages.display_order') }}
                        </label>
                        <input 
                            type="number" 
                            id="display_order" 
                            name="display_order" 
                            class="form-control @error('display_order') is-invalid @enderror" 
                            value="{{ old('display_order', 0) }}" 
                            min="0"
                            placeholder="0">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.banner_display_order_help') }}
                        </p>
                        @error('display_order')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
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
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-eye"></i> {{ __('messages.banner_active') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.banner_active_help') }}</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> {{ __('messages.create_banner') }}
            </button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
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

@endsection
