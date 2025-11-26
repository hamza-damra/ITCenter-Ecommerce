@extends('admin.layout')

@section('title', __('messages.edit_banner'))

@section('content')
<style>
    .banner-form-grid {
        max-width: 900px;
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

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Banner Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> {{ __('messages.banner_image') }}</h2>
            </div>
            <div class="card-body">
                <!-- Current Image -->
                @if($banner->image_path)
                <div class="current-image-container">
                    <span class="current-image-label">{{ __('messages.current_banner_image') }}</span>
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title_en ?? 'Banner' }}" class="current-image">
                </div>
                @endif

                <div class="form-group">
                    <label for="image" class="form-label">
                        {{ __('messages.new_image') ?? 'New Image' }}
                        <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional_replace') ?? 'Optional - leave empty to keep current' }})</span>
                    </label>
                    <div class="image-upload-box" onclick="document.getElementById('image').click()">
                        <div class="upload-placeholder">
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
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        style="display: none;"
                        onchange="previewImage(this)">
                    <p class="change-image-hint">
                        <i class="fas fa-info-circle"></i> {{ __('messages.image_replace_hint') ?? 'Upload a new image to replace the current one' }}
                    </p>
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
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
