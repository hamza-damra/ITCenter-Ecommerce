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

                <div class="form-row" id="iconPositionRow">
                    <div class="form-group" id="iconGroup">
                        <label for="icon" class="form-label">
                            {{ __('messages.category_icon') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                        </label>
                        <input 
                            type="text" 
                            id="icon" 
                            name="icon" 
                            class="form-control @error('icon') is-invalid @enderror" 
                            value="{{ old('icon') }}" 
                            placeholder="e.g., fas fa-laptop, fas fa-tshirt">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.icon_help_text') }}
                        </p>
                        @error('icon')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position" class="form-label">
                            {{ __('messages.display_position') }}
                            <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                        </label>
                        <input 
                            type="number" 
                            id="position" 
                            name="position" 
                            class="form-control @error('position') is-invalid @enderror" 
                            value="{{ old('position', 0) }}" 
                            placeholder="0"
                            min="0">
                        <p class="form-text">
                            <i class="fas fa-sort-numeric-down"></i> {{ __('messages.lower_numbers_first') }}
                        </p>
                        @error('position')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="display_mode" class="form-label">
                        {{ __('messages.display_mode') }}
                        <span class="required">*</span>
                    </label>
                    <select id="display_mode" name="display_mode" class="form-control @error('display_mode') is-invalid @enderror">
                        <option value="carousel" {{ old('display_mode', 'carousel') == 'carousel' ? 'selected' : '' }}>
                            {{ __('messages.carousel') }} - {{ __('messages.carousel_description') }}
                        </option>
                        <option value="nav" {{ old('display_mode') == 'nav' ? 'selected' : '' }}>
                            {{ __('messages.nav_bar') }} - {{ __('messages.nav_bar_description') }}
                        </option>
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.display_mode_help') }}
                    </p>
                    @error('display_mode')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nav Type Selection (Parent/Child) - Only for Nav mode -->
                <div class="form-group" id="navTypeGroup" style="display: none;">
                    <label for="nav_type" class="form-label">
                        {{ __('messages.nav_type') }}
                        <span class="required">*</span>
                    </label>
                    <select id="nav_type" name="nav_type" class="form-control @error('nav_type') is-invalid @enderror">
                        <option value="parent" {{ old('nav_type', 'parent') == 'parent' ? 'selected' : '' }}>
                            {{ __('messages.nav_parent') }} - {{ __('messages.nav_parent_description') }}
                        </option>
                        <option value="child" {{ old('nav_type') == 'child' ? 'selected' : '' }}>
                            {{ __('messages.nav_child') }} - {{ __('messages.nav_child_description') }}
                        </option>
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.nav_type_help') }}
                    </p>
                    @error('nav_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nav Parent Selection - Only for Nav Child type -->
                <div class="form-group" id="navParentGroup" style="display: none;">
                    <label for="nav_parent_id" class="form-label">
                        {{ __('messages.select_nav_parent') }}
                        <span class="required">*</span>
                    </label>
                    <select id="nav_parent_id" name="nav_parent_id" class="form-control @error('nav_parent_id') is-invalid @enderror">
                        <option value="">{{ __('messages.choose_parent_category') }}</option>
                        @foreach($navParentCategories ?? [] as $navParent)
                            <option value="{{ $navParent->id }}" {{ old('nav_parent_id') == $navParent->id ? 'selected' : '' }}>
                                {{ $navParent->name_en ?? $navParent->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.nav_parent_select_help') }}
                    </p>
                    @error('nav_parent_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Category Image Card (Only for Carousel mode) -->
        <div class="card" id="imageCard">
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

        <!-- Descriptions Card (Only for Carousel mode) -->
        <div class="card" id="descriptionsCard">
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

        <!-- SEO Card (Only for Carousel mode) -->
        <div class="card" id="seoCard">
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

    function toggleDisplayModeFields() {
        const displayMode = document.getElementById('display_mode').value;
        const imageCard = document.getElementById('imageCard');
        const descriptionsCard = document.getElementById('descriptionsCard');
        const seoCard = document.getElementById('seoCard');
        const iconGroup = document.getElementById('iconGroup');
        const navTypeGroup = document.getElementById('navTypeGroup');
        const parentIdGroup = document.getElementById('parent_id').closest('.form-group');
        
        if (displayMode === 'nav') {
            // Hide cards not needed for nav mode
            imageCard.style.display = 'none';
            descriptionsCard.style.display = 'none';
            seoCard.style.display = 'none';
            iconGroup.style.display = 'none';
            navTypeGroup.style.display = 'block';
            parentIdGroup.style.display = 'none'; // Hide regular parent dropdown for nav mode
            
            // Toggle nav type fields
            toggleNavTypeFields();
        } else {
            // Show all cards for carousel mode
            imageCard.style.display = 'block';
            descriptionsCard.style.display = 'block';
            seoCard.style.display = 'block';
            iconGroup.style.display = 'block';
            navTypeGroup.style.display = 'none';
            parentIdGroup.style.display = 'block'; // Show regular parent dropdown for carousel mode
            
            // Hide nav parent selection
            document.getElementById('navParentGroup').style.display = 'none';
        }
    }

    function toggleNavTypeFields() {
        const navType = document.getElementById('nav_type').value;
        const navParentGroup = document.getElementById('navParentGroup');
        const navParentSelect = document.getElementById('nav_parent_id');
        
        if (navType === 'child') {
            navParentGroup.style.display = 'block';
            navParentSelect.setAttribute('required', 'required');
        } else {
            navParentGroup.style.display = 'none';
            navParentSelect.removeAttribute('required');
        }
    }

    // Preview on load if image exists
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        if (imageInput.value) {
            previewImage(imageInput.value);
        }
        
        // Toggle fields based on display mode
        toggleDisplayModeFields();
        
        // Listen for display mode changes
        document.getElementById('display_mode').addEventListener('change', toggleDisplayModeFields);
        
        // Listen for nav type changes
        document.getElementById('nav_type').addEventListener('change', toggleNavTypeFields);
    });
</script>

@endsection
