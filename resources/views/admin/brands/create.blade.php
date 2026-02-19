@extends('admin.layout')

@section('title', __('messages.create_brand'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>{{ __('messages.create_new_brand') }}</h1>
        <p>{{ __('messages.add_brand_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_brands') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 900px; margin: 0 auto;">
    @csrf

    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> {{ __('messages.basic_information') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            {{ __('messages.brand_name_english') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="{{ __('messages.brand_name_placeholder_en') }}"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            {{ __('messages.brand_name_arabic') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="{{ __('messages.brand_name_placeholder_ar') }}"
                            required 
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="website" class="form-label">
                        {{ __('messages.website_url') }}
                        <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') ?? 'Optional' }}</span>
                    </label>
                    <input 
                        type="url" 
                        id="website" 
                        name="website" 
                        class="form-control @error('website') is-invalid @enderror" 
                        value="{{ old('website') }}"
                        placeholder="{{ __('messages.website_placeholder') }}">
                    @error('website')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Brand Logo Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> {{ __('messages.logo_url') ?? 'Brand Logo' }}</h2>
            </div>
            <div class="card-body">
                <!-- Image Source Toggle -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">{{ __('messages.image_source') ?? 'Image Source' }}</label>
                    <div style="display: flex; gap: 12px;">
                        <label class="checkbox-group" style="margin: 0; flex: 1; padding: 12px; border: 2px solid var(--primary); border-radius: 8px; background: #eff6ff; cursor: pointer;" id="source-file-label">
                            <input type="radio" name="image_source_type" value="file" {{ old('image_source_type', 'file') === 'file' ? 'checked' : '' }} onchange="toggleImageSource('file')">
                            <span>
                                <strong><i class="fas fa-upload"></i> {{ __('messages.upload_files') ?? 'Upload Files' }}</strong>
                                <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.upload_from_device') ?? 'Upload images from your device (stored on server)' }}</p>
                            </span>
                        </label>
                        <label class="checkbox-group" style="margin: 0; flex: 1; padding: 12px; border: 2px solid var(--border); border-radius: 8px; cursor: pointer;" id="source-url-label">
                            <input type="radio" name="image_source_type" value="url" {{ old('image_source_type', 'file') === 'url' ? 'checked' : '' }} onchange="toggleImageSource('url')">
                            <span>
                                <strong><i class="fas fa-link"></i> {{ __('messages.image_url') ?? 'Image URL' }}</strong>
                                <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.paste_external_url') ?? 'Paste external image URLs' }}</p>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- FILE UPLOAD MODE -->
                <div id="image-source-file" style="{{ old('image_source_type', 'file') === 'file' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label class="form-label">
                            {{ __('messages.logo_url') ?? 'Brand Logo' }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') ?? 'Optional' }}</span>
                        </label>
                        <div class="dropzone-area @error('logo_file') dropzone-error @enderror" id="main-dropzone" onclick="document.getElementById('logo_file').click()">
                            <input type="file" id="logo_file" name="logo_file" accept="image/jpeg,image/png,image/webp" style="display:none;">
                            <div class="dropzone-content" id="main-dropzone-content">
                                <div class="dropzone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <p class="dropzone-title">{{ __('messages.drag_drop_image') }}</p>
                                <p class="dropzone-subtitle">{{ __('messages.or_click_to_browse') }}</p>
                                <span class="dropzone-formats">{{ __('messages.accepted_formats') }}</span>
                            </div>
                            <div class="dropzone-preview" id="main-dropzone-preview" style="display:none;">
                                <img id="main-image-preview-img" src="" alt="Preview">
                                <button type="button" class="dropzone-remove" onclick="event.stopPropagation(); removeMainImage();" title="{{ __('messages.remove') }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @error('logo_file')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- URL MODE -->
                <div id="image-source-url" style="{{ old('image_source_type', 'file') === 'url' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label for="logo" class="form-label">
                            {{ __('messages.logo_url') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') ?? 'Optional' }}</span>
                        </label>
                        <input 
                            type="url" 
                            id="logo" 
                            name="logo" 
                            class="form-control @error('logo') is-invalid @enderror" 
                            value="{{ old('logo') }}" 
                            placeholder="{{ __('messages.logo_placeholder') }}">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.logo_tip') }}
                        </p>
                        @error('logo')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <style>
            .dropzone-area {
                border: 2px dashed var(--border);
                border-radius: 12px;
                padding: 32px 20px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                position: relative;
            }
            .dropzone-area:hover, .dropzone-area.dragover {
                border-color: var(--primary);
                background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
            }
            .dropzone-area.dropzone-error {
                border-color: var(--danger);
                background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            }
            .dropzone-icon { font-size: 36px; color: var(--primary); margin-bottom: 12px; opacity: 0.7; }
            .dropzone-area:hover .dropzone-icon { opacity: 1; }
            .dropzone-title { font-size: 15px; font-weight: 600; color: var(--dark); margin-bottom: 4px; }
            .dropzone-subtitle { font-size: 13px; color: var(--secondary); margin-bottom: 8px; }
            .dropzone-formats { display: inline-block; font-size: 12px; color: var(--secondary); background: white; padding: 4px 12px; border-radius: 20px; border: 1px solid var(--border); }
            .dropzone-preview { position: relative; display: inline-block; }
            .dropzone-preview img { max-width: 100%; max-height: 220px; border-radius: 10px; object-fit: contain; }
            .dropzone-remove { position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; border-radius: 50%; background: var(--danger); color: white; border: 2px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; box-shadow: 0 2px 8px rgba(239,68,68,0.4); transition: transform 0.2s; }
            .dropzone-remove:hover { transform: scale(1.15); }
            [dir="rtl"] .dropzone-remove { right: auto; left: -8px; }
        </style>

        <!-- Descriptions Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> {{ __('messages.descriptions') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            {{ __('messages.brand_description_english') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') ?? 'Optional' }}</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="{{ __('messages.brand_description_placeholder_en') }}"
                            style="min-height: 100px;">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            {{ __('messages.brand_description_arabic') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') ?? 'Optional' }}</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="{{ __('messages.brand_description_placeholder_ar') }}"
                            style="min-height: 100px;">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> {{ __('messages.settings') }}</h2>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-eye"></i> {{ __('messages.active_brand') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.make_brand_visible') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_featured" 
                            name="is_featured" 
                            value="1" 
                            {{ old('is_featured') ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-star"></i> {{ __('messages.featured_brand') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.display_featured_section') }}</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> {{ __('messages.create_brand_button') }}
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </a>
        </div>
    </div>
</form>

<script>
    function toggleImageSource(type) {
        const fileSection = document.getElementById('image-source-file');
        const urlSection = document.getElementById('image-source-url');
        const fileLabel = document.getElementById('source-file-label');
        const urlLabel = document.getElementById('source-url-label');
        if (type === 'file') {
            fileSection.style.display = '';
            urlSection.style.display = 'none';
            fileLabel.style.borderColor = 'var(--primary)';
            fileLabel.style.background = '#eff6ff';
            urlLabel.style.borderColor = 'var(--border)';
            urlLabel.style.background = '';
        } else {
            fileSection.style.display = 'none';
            urlSection.style.display = '';
            urlLabel.style.borderColor = 'var(--primary)';
            urlLabel.style.background = '#eff6ff';
            fileLabel.style.borderColor = 'var(--border)';
            fileLabel.style.background = '';
        }
    }

    function removeMainImage() {
        const input = document.getElementById('logo_file');
        input.value = '';
        document.getElementById('main-dropzone-preview').style.display = 'none';
        document.getElementById('main-dropzone-content').style.display = '';
    }

    function setupDropzone(dropzoneId, inputId) {
        const zone = document.getElementById(dropzoneId);
        if (!zone) return;
        ['dragenter', 'dragover'].forEach(e => {
            zone.addEventListener(e, function(ev) { ev.preventDefault(); zone.classList.add('dragover'); });
        });
        ['dragleave', 'drop'].forEach(e => {
            zone.addEventListener(e, function(ev) { ev.preventDefault(); zone.classList.remove('dragover'); });
        });
        zone.addEventListener('drop', function(ev) {
            const input = document.getElementById(inputId);
            if (ev.dataTransfer.files.length) {
                input.files = ev.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupDropzone('main-dropzone', 'logo_file');

        const mainInput = document.getElementById('logo_file');
        if (mainInput) {
            mainInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('main-image-preview-img').src = e.target.result;
                        document.getElementById('main-dropzone-preview').style.display = '';
                        document.getElementById('main-dropzone-content').style.display = 'none';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection
