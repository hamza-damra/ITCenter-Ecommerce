@extends('admin.layout')

@section('title', __('messages.edit_banner'))

@section('content')
<style>
    .banner-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Image Source Selector */
    .image-source-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: stretch;
    }

    .source-option {
        flex: 1;
        min-width: 140px;
        position: relative;
        display: flex;
        flex-direction: column;
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
        padding: 14px 10px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        flex: 1;
    }

    .source-option-label i {
        font-size: 22px;
        margin-bottom: 6px;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .source-option-label .source-title {
        font-weight: 600;
        color: #334155;
        font-size: 12px;
        margin-bottom: 2px;
    }

    .source-option-label .source-desc {
        font-size: 10px;
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

    .current-image-container {
        margin-bottom: 20px;
        position: relative;
    }

    .current-image-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .current-source-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .current-source-badge.badge-file {
        background: #d1fae5;
        color: #065f46;
    }

    .current-source-badge.badge-url {
        background: #dbeafe;
        color: #1e40af;
    }

    .current-image {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        border: 2px solid var(--border);
        box-shadow: var(--shadow);
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

    /* URL Input */
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
        border-radius: 6px;
        font-size: 12px;
        margin-top: 12px;
    }

    .storage-info.info-file {
        background: #d1fae5;
        color: #065f46;
    }

    .storage-info.info-url {
        background: #dbeafe;
        color: #1e40af;
    }

    .storage-info.info-download {
        background: #fef3c7;
        color: #92400e;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-edit"></i> {{ __('messages.edit_banner') }}</h1>
        <p>{{ __('messages.update_banner_details') ?? 'Update banner details and settings' }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_banners') ?? 'Back to Banners' }}
        </a>
    </div>
</div>

<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="banner-form-grid">
    @csrf
    @method('PUT')
    <input type="hidden" name="image_url" id="image_url_hidden" value="">

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Banner Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> {{ __('messages.banner_image') }}</h2>
            </div>
            <div class="card-body">
                
                <!-- Current Image Display -->
                <div class="current-image-container">
                    <span class="current-image-label">
                        {{ __('messages.current_banner_image') }}
                        @if($banner->isImageFromUrl())
                            <span class="current-source-badge badge-url"><i class="fas fa-link"></i> {{ __('messages.external_url') ?? 'External URL' }}</span>
                        @elseif($banner->isImageInFile())
                            <span class="current-source-badge badge-file"><i class="fas fa-hdd"></i> {{ __('messages.stored_on_server') ?? 'Stored on Server' }}</span>
                        @endif
                    </span>
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title_en ?? 'Banner' }}" class="current-image"
                         onerror="this.onerror=null; this.src='{{ asset('images/assets/Banner.jpg') }}';">
                </div>

                <!-- Image Source Selector -->
                <div class="form-group">
                    <label class="form-label">
                        {{ __('messages.change_image') ?? 'Change Image' }}
                    </label>
                    
                    <div class="image-source-selector">
                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_keep" value="keep" 
                                   {{ old('image_source', 'keep') === 'keep' ? 'checked' : '' }}
                                   onchange="toggleImageSource('keep')">
                            <label for="source_keep" class="source-option-label">
                                <i class="fas fa-check-circle"></i>
                                <span class="source-title">{{ __('messages.keep_current') ?? 'Keep Current' }}</span>
                                <span class="source-desc">{{ __('messages.keep_current_desc') ?? 'No changes to image' }}</span>
                            </label>
                        </div>

                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_file" value="file" 
                                   {{ old('image_source') === 'file' ? 'checked' : '' }}
                                   onchange="toggleImageSource('file')">
                            <label for="source_file" class="source-option-label">
                                <i class="fas fa-upload"></i>
                                <span class="source-title">{{ __('messages.banner_upload_from_device') ?? 'Upload from Device' }}</span>
                                <span class="source-desc">{{ __('messages.banner_upload_from_device_desc') ?? 'Upload and store on server' }}</span>
                            </label>
                        </div>
                        
                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_url" value="url"
                                   {{ old('image_source') === 'url' ? 'checked' : '' }}
                                   onchange="toggleImageSource('url')">
                            <label for="source_url" class="source-option-label">
                                <i class="fas fa-link"></i>
                                <span class="source-title">{{ __('messages.external_url') ?? 'External URL' }}</span>
                                <span class="source-desc">{{ __('messages.external_url_desc') ?? 'Display from external URL' }}</span>
                            </label>
                        </div>

                        <div class="source-option">
                            <input type="radio" name="image_source" id="source_download_url" value="download_url"
                                   {{ old('image_source') === 'download_url' ? 'checked' : '' }}
                                   onchange="toggleImageSource('download_url')">
                            <label for="source_download_url" class="source-option-label">
                                <i class="fas fa-cloud-download-alt"></i>
                                <span class="source-title">{{ __('messages.download_from_url') ?? 'Download from URL' }}</span>
                                <span class="source-desc">{{ __('messages.download_from_url_desc') ?? 'Download and store on server' }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Keep Current Section (empty, just info) -->
                <div id="section-keep" class="image-input-section {{ old('image_source', 'keep') === 'keep' ? 'active' : '' }}">
                    <div class="storage-info info-file">
                        <i class="fas fa-info-circle"></i>
                        {{ __('messages.keep_current_info') ?? 'The current image will be kept. Select another option above to change it.' }}
                    </div>
                </div>

                <!-- File Upload Section -->
                <div id="section-file" class="image-input-section {{ old('image_source') === 'file' ? 'active' : '' }}">
                    <div class="form-group">
                        <label for="image" class="form-label">
                            {{ __('messages.upload_image') ?? 'Upload Image' }}
                            <span class="required">*</span>
                        </label>
                        <div class="image-upload-box" onclick="document.getElementById('image').click()">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>{{ __('messages.click_to_upload_new') ?? 'Click to upload new image' }}</p>
                                <p class="upload-hint">{{ __('messages.banner_image_help') }}</p>
                            </div>
                            <img id="imagePreview" class="image-preview" alt="Preview">
                        </div>
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            class="form-control @error('image') is-invalid @enderror" 
                            accept="image/jpeg,image/png,image/webp"
                            style="display: none;"
                            onchange="previewUploadedImage(this)">
                        
                        <div class="storage-info info-file">
                            <i class="fas fa-hdd"></i>
                            {{ __('messages.file_storage_info') ?? 'Image will be stored on the server filesystem for optimal performance.' }}
                        </div>
                        
                        @error('image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- External URL Section -->
                <div id="section-url" class="image-input-section {{ old('image_source') === 'url' ? 'active' : '' }}">
                    <div class="form-group">
                        <label for="image_url_external" class="form-label">
                            {{ __('messages.image_url') ?? 'Image URL' }}
                            <span class="required">*</span>
                        </label>
                        <div class="url-input-wrapper">
                            <i class="fas fa-globe url-icon"></i>
                            <input 
                                type="url" 
                                id="image_url_external" 
                                class="form-control @error('image_url') is-invalid @enderror" 
                                value="{{ old('image_url', $banner->isImageFromUrl() ? $banner->image_path : '') }}"
                                placeholder="{{ __('messages.enter_image_url') ?? 'https://example.com/image.jpg' }}"
                                oninput="previewUrlImage(this.value, 'urlPreviewExternal')">
                        </div>
                        
                        <div class="storage-info info-url">
                            <i class="fas fa-info-circle"></i>
                            {{ __('messages.url_storage_info') ?? 'Image will be loaded directly from external URL. Make sure the URL is always accessible.' }}
                        </div>
                        
                        <div id="urlPreviewExternal" class="url-preview-container">
                            <img class="url-preview-image" alt="URL Preview" onerror="showUrlError(this)" onload="hideUrlError(this)">
                            <div class="url-preview-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>{{ __('messages.image_load_failed') ?? 'Failed to load image. Please check the URL.' }}</span>
                            </div>
                        </div>
                        
                        @error('image_url')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Download from URL Section -->
                <div id="section-download_url" class="image-input-section {{ old('image_source') === 'download_url' ? 'active' : '' }}">
                    <div class="form-group">
                        <label for="image_url_download" class="form-label">
                            {{ __('messages.image_url') ?? 'Image URL' }}
                            <span class="required">*</span>
                        </label>
                        <div class="url-input-wrapper">
                            <i class="fas fa-cloud-download-alt url-icon"></i>
                            <input 
                                type="url" 
                                id="image_url_download" 
                                class="form-control @error('image_url') is-invalid @enderror" 
                                value="{{ old('image_url') }}"
                                placeholder="{{ __('messages.enter_image_url') ?? 'https://example.com/image.jpg' }}"
                                oninput="previewUrlImage(this.value, 'urlPreviewDownload')">
                        </div>
                        
                        <div class="storage-info info-download">
                            <i class="fas fa-download"></i>
                            {{ __('messages.download_url_storage_info') ?? 'Image will be downloaded from the URL and stored on the server for optimal performance.' }}
                        </div>
                        
                        <div id="urlPreviewDownload" class="url-preview-container">
                            <img class="url-preview-image" alt="URL Preview" onerror="showUrlError(this)" onload="hideUrlError(this)">
                            <div class="url-preview-error">
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
                            value="{{ old('title_en', $banner->title_en) }}" 
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
                            value="{{ old('title_ar', $banner->title_ar) }}" 
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
                            value="{{ old('title_he', $banner->title_he) }}" 
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
                            style="min-height: 80px;">{{ old('subtitle_en', $banner->subtitle_en) }}</textarea>
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
                            style="min-height: 80px;">{{ old('subtitle_ar', $banner->subtitle_ar) }}</textarea>
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
                            style="min-height: 80px;">{{ old('subtitle_he', $banner->subtitle_he) }}</textarea>
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
                            value="{{ old('button_text_en', $banner->button_text_en) }}" 
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
                            value="{{ old('button_text_ar', $banner->button_text_ar) }}" 
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
                            value="{{ old('button_text_he', $banner->button_text_he) }}" 
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
                                value="{{ old('title_color', $banner->title_color ?? '#ffffff') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('title_color').value = this.value">
                            <input 
                                type="text" 
                                id="title_color" 
                                name="title_color" 
                                class="form-control @error('title_color') is-invalid @enderror" 
                                value="{{ old('title_color', $banner->title_color) }}" 
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
                                value="{{ old('subtitle_color', $banner->subtitle_color ?? '#e2e8f0') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('subtitle_color').value = this.value">
                            <input 
                                type="text" 
                                id="subtitle_color" 
                                name="subtitle_color" 
                                class="form-control @error('subtitle_color') is-invalid @enderror" 
                                value="{{ old('subtitle_color', $banner->subtitle_color) }}" 
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
                                value="{{ old('button_bg_color', $banner->button_bg_color ?? '#3b82f6') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('button_bg_color').value = this.value">
                            <input 
                                type="text" 
                                id="button_bg_color" 
                                name="button_bg_color" 
                                class="form-control @error('button_bg_color') is-invalid @enderror" 
                                value="{{ old('button_bg_color', $banner->button_bg_color) }}" 
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
                                value="{{ old('button_text_color', $banner->button_text_color ?? '#ffffff') }}"
                                style="width: 50px; height: 40px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; padding: 2px;"
                                onchange="document.getElementById('button_text_color').value = this.value">
                            <input 
                                type="text" 
                                id="button_text_color" 
                                name="button_text_color" 
                                class="form-control @error('button_text_color') is-invalid @enderror" 
                                value="{{ old('button_text_color', $banner->button_text_color) }}" 
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
                            value="{{ old('link', $banner->link) }}" 
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
                            value="{{ old('display_order', $banner->display_order) }}" 
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
                            {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
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
                <i class="fas fa-save"></i> {{ __('messages.update_banner') }}
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
    document.querySelectorAll('.image-input-section').forEach(el => el.classList.remove('active'));
    const section = document.getElementById('section-' + source);
    if (section) section.classList.add('active');
    syncUrlInput(source);
}

// Sync the correct URL input into the hidden image_url field
function syncUrlInput(source) {
    const hiddenUrl = document.getElementById('image_url_hidden');
    if (!hiddenUrl) return;

    if (source === 'url') {
        hiddenUrl.value = document.getElementById('image_url_external')?.value || '';
    } else if (source === 'download_url') {
        hiddenUrl.value = document.getElementById('image_url_download')?.value || '';
    } else {
        hiddenUrl.value = '';
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
function previewUrlImage(url, containerId) {
    clearTimeout(urlPreviewTimeout);
    const container = document.getElementById(containerId);
    const image = container?.querySelector('.url-preview-image');
    const error = container?.querySelector('.url-preview-error');
    if (!container || !image) return;

    const hiddenUrl = document.getElementById('image_url_hidden');
    if (hiddenUrl) hiddenUrl.value = url;
    
    if (!url || url.trim() === '') {
        container.classList.remove('has-preview');
        return;
    }
    
    urlPreviewTimeout = setTimeout(() => {
        container.classList.add('has-preview');
        if (error) error.classList.remove('show');
        image.style.display = 'block';
        image.src = url;
    }, 500);
}

function showUrlError(img) {
    const container = img.closest('.url-preview-container');
    const error = container?.querySelector('.url-preview-error');
    img.style.display = 'none';
    if (error) error.classList.add('show');
}

function hideUrlError(img) {
    const container = img.closest('.url-preview-container');
    const error = container?.querySelector('.url-preview-error');
    if (error) error.classList.remove('show');
}

// On form submit, sync the URL input
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.banner-form-grid');
    if (form) {
        form.addEventListener('submit', function() {
            const source = document.querySelector('input[name="image_source"]:checked')?.value;
            syncUrlInput(source);
        });
    }

    // Restore URL preview on validation error
    const checkedSource = document.querySelector('input[name="image_source"]:checked')?.value;
    if (checkedSource === 'url') {
        const val = document.getElementById('image_url_external')?.value;
        if (val) previewUrlImage(val, 'urlPreviewExternal');
    } else if (checkedSource === 'download_url') {
        const val = document.getElementById('image_url_download')?.value;
        if (val) previewUrlImage(val, 'urlPreviewDownload');
    }
});
</script>

@endsection
