@extends('admin.layout')

@section('title', __('messages.create_category'))

@section('content')
<style>
    /* Category Create Page Specific Styles */
    .category-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    .image-preview {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
        margin-top: 8px;
        display: none;
    }

    .image-preview.visible {
        display: block;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1>{{ __('messages.create_new_category') }}</h1>
        <p>{{ __('messages.add_category_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_categories') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" class="category-form-grid">
    @csrf

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-folder-plus"></i> {{ __('messages.category_information') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            {{ __('messages.category_name_english') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="e.g., Electronics, Clothing, Food"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            {{ __('messages.category_name_arabic') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="أدخل اسم الفئة بالعربية"
                            required 
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="parent_id" class="form-label">
                        {{ __('messages.parent_category_optional') }}
                        <span style="color: #64748b; font-size: 12px;">{{ __('messages.for_subcategories') }}</span>
                    </label>
                    <select id="parent_id" name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">{{ __('messages.root_category_no_parent') }}</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name_en ?? $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.parent_category_help') }}
                    </p>
                    @error('parent_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order" class="form-label">
                        {{ __('messages.display_order') }}
                        <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                    </label>
                    <input 
                        type="number" 
                        id="order" 
                        name="order" 
                        class="form-control @error('order') is-invalid @enderror" 
                        value="{{ old('order', 0) }}" 
                        placeholder="0"
                        min="0">
                    <p class="form-text">
                        <i class="fas fa-sort-numeric-down"></i> {{ __('messages.lower_numbers_first') }}
                    </p>
                    @error('order')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Category Image Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> {{ __('messages.category_image') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="image" class="form-label">
                        {{ __('messages.category_image_url') }}
                        <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                    </label>
                    <input 
                        type="url" 
                        id="image" 
                        name="image" 
                        class="form-control @error('image') is-invalid @enderror" 
                        value="{{ old('image') }}" 
                        placeholder="{{ __('messages.image_url_placeholder') }}"
                        oninput="previewImage(this.value)">
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> {!! __('messages.image_services_tip') !!}
                    </p>
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <img id="imagePreview" class="image-preview" alt="Category preview">
                </div>
            </div>
        </div>

        <!-- Descriptions Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> {{ __('messages.descriptions') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            {{ __('messages.description_english') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="{{ __('messages.description_placeholder') }}"
                            style="min-height: 100px;">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            {{ __('messages.description_arabic') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="{{ __('messages.description_placeholder_ar') }}"
                            style="min-height: 100px;">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> {{ __('messages.seo_settings') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">
                            {{ __('messages.meta_title') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                        </label>
                        <input 
                            type="text" 
                            id="meta_title" 
                            name="meta_title" 
                            class="form-control @error('meta_title') is-invalid @enderror" 
                            value="{{ old('meta_title') }}" 
                            placeholder="{{ __('messages.meta_title_placeholder') }}"
                            maxlength="60">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.meta_title_tip') }}
                        </p>
                        @error('meta_title')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords" class="form-label">
                            {{ __('messages.meta_keywords') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                        </label>
                        <input 
                            type="text" 
                            id="meta_keywords" 
                            name="meta_keywords" 
                            class="form-control @error('meta_keywords') is-invalid @enderror" 
                            value="{{ old('meta_keywords') }}" 
                            placeholder="{{ __('messages.meta_keywords_placeholder') }}">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.meta_keywords_tip') }}
                        </p>
                        @error('meta_keywords')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="meta_description" class="form-label">
                        {{ __('messages.meta_description') }}
                        <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                    </label>
                    <textarea 
                        id="meta_description" 
                        name="meta_description" 
                        class="form-control @error('meta_description') is-invalid @enderror"
                        placeholder="{{ __('messages.meta_description_placeholder') }}"
                        style="min-height: 80px;">{{ old('meta_description') }}</textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.meta_description_tip') }}
                    </p>
                    @error('meta_description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Category Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> {{ __('messages.category_settings') }}</h2>
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
                            <strong><i class="fas fa-eye"></i> {{ __('messages.active_label') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.display_category_in_store') }}</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> {{ __('messages.create_category_button') }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </a>
        </div>
    </div>


</form>

<script>
    function previewImage(url) {
        const preview = document.getElementById('imagePreview');
        if (url) {
            preview.src = url;
            preview.classList.add('visible');
            preview.onerror = function() {
                preview.classList.remove('visible');
            };
        } else {
            preview.classList.remove('visible');
        }
    }

    // Preview on load if image exists
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        if (imageInput.value) {
            previewImage(imageInput.value);
        }
    });
</script>

@endsection
