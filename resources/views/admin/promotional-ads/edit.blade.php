@extends('admin.layout')

@section('title', __('messages.edit_promotional_ad'))

@section('content')
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

    .current-image-container {
        margin-bottom: 20px;
    }

    .current-image-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 12px;
        display: block;
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

    .change-image-hint {
        font-size: 13px;
        color: var(--secondary);
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
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
        <h1><i class="fas fa-edit"></i> {{ __('messages.edit_promotional_ad') }}</h1>
        <p>{{ __('messages.update_promotional_ad_description') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.promotional-ads.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_promotional_ads') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.promotional-ads.update', $promotionalAd) }}" method="POST" enctype="multipart/form-data" class="promo-ad-form-grid">
    @csrf
    @method('PUT')

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Ad Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> {{ __('messages.ad_image') }}</h2>
            </div>
            <div class="card-body">
                <!-- Current Image -->
                @if($promotionalAd->image_path)
                <div class="current-image-container">
                    <span class="current-image-label">{{ __('messages.current_ad_image') }}</span>
                    <img src="{{ $promotionalAd->image_url }}" alt="{{ __('messages.promotional_ad') }}" class="current-image">
                </div>
                @endif

                <div class="form-group">
                    <label for="image" class="form-label">
                        {{ __('messages.new_image') }}
                        <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional_replace') }})</span>
                    </label>
                    <div class="image-upload-box" onclick="document.getElementById('image').click()">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>{{ __('messages.click_to_upload_new') }}</p>
                            <p class="upload-hint">{{ __('messages.promotional_ad_image_help') }}</p>
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
                        onchange="previewImage(this)">
                    <p class="change-image-hint">
                        <i class="fas fa-info-circle"></i> {{ __('messages.image_replace_hint') }}
                    </p>
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Position Selection Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-arrows-alt-h"></i> {{ __('messages.ad_position') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">
                        {{ __('messages.select_position') }}
                        <span class="required">*</span>
                    </label>
                    <div class="position-selector">
                        <div class="position-option">
                            <input type="radio" id="position_left" name="position" value="left" {{ old('position', $promotionalAd->position) == 'left' ? 'checked' : '' }} required>
                            <label for="position_left">
                                <i class="fas fa-arrow-left"></i>
                                <span>{{ __('messages.left') }}</span>
                            </label>
                        </div>
                        <div class="position-option">
                            <input type="radio" id="position_right" name="position" value="right" {{ old('position', $promotionalAd->position) == 'right' ? 'checked' : '' }}>
                            <label for="position_right">
                                <i class="fas fa-arrow-right"></i>
                                <span>{{ __('messages.right') }}</span>
                            </label>
                        </div>
                    </div>
                    @error('position')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.position_help') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Link & Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> {{ __('messages.link_settings') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="link" class="form-label">
                        {{ __('messages.ad_link_url') }}
                        <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                    </label>
                    <input 
                        type="url" 
                        id="link" 
                        name="link" 
                        class="form-control @error('link') is-invalid @enderror" 
                        value="{{ old('link', $promotionalAd->link) }}" 
                        placeholder="{{ __('messages.ad_link_placeholder') }}">
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.ad_link_help') }}
                    </p>
                    @error('link')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <input type="hidden" name="is_active" value="0">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', $promotionalAd->is_active) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-eye"></i> {{ __('messages.ad_active') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.ad_active_help') }}</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> {{ __('messages.update_promotional_ad') }}
            </button>
            <a href="{{ route('admin.promotional-ads.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
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

@endsection
