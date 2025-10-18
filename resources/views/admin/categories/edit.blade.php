@extends('admin.layout')

@section('title', __('messages.edit_category'))

@section('content')
<style>
    /* Category Edit Page Specific Styles */
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

    .current-image-container {
        margin-top: 12px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .current-image-container img {
        width: 100%;
        max-width: 300px;
        height: auto;
        border-radius: 8px;
        display: block;
    }

    .current-image-label {
        font-size: 12px;
        color: var(--secondary);
        margin-bottom: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .delete-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 2px solid #fee2e2;
    }

    .danger-zone {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #fca5a5;
        border-radius: 8px;
        padding: 20px;
    }

    .danger-zone h3 {
        color: #dc2626;
        font-size: 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .danger-zone p {
        color: #7f1d1d;
        font-size: 13px;
        margin-bottom: 16px;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-edit"></i> {{ __('messages.edit_category') }}</h1>
        <p>{{ __('messages.update_category_information') }}: <strong>{{ $category->name }}</strong></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_categories') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="category-form-grid">
    @csrf
    @method('PUT')

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-folder"></i> {{ __('messages.category_information') }}</h2>
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
                            value="{{ old('name_en', $category->name_en) }}" 
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
                            value="{{ old('name_ar', $category->name_ar) }}" 
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
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                        value="{{ old('order', $category->order ?? 0) }}" 
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
                        value="{{ old('image', $category->image) }}" 
                        placeholder="{{ __('messages.image_url_placeholder') }}"
                        oninput="previewImage(this.value)">
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> {!! __('messages.image_services_tip') !!}
                    </p>
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    
                    @if($category->image)
                        <div class="current-image-container">
                            <div class="current-image-label">
                                <i class="fas fa-image"></i>
                                {{ __('messages.current_image') }}
                            </div>
                            <img src="{{ $category->image }}" alt="{{ $category->name }}">
                        </div>
                    @endif
                    
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
                            style="min-height: 100px;">{{ old('description_en', $category->description_en) }}</textarea>
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
                            style="min-height: 100px;">{{ old('description_ar', $category->description_ar) }}</textarea>
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
                            value="{{ old('meta_title', $category->meta_title ?? '') }}" 
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
                            value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}" 
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
                        style="min-height: 80px;">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
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
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
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
                <i class="fas fa-save"></i> {{ __('messages.update_category') }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </a>
        </div>

        <!-- Danger Zone -->
        <div class="delete-section">
            <div class="danger-zone">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('messages.danger_zone') }}
                </h3>
                <p>{{ __('messages.delete_category_warning') }}</p>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> {{ __('messages.delete_category') }}
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
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

    function confirmDelete() {
        if (confirm('{{ __('messages.confirm_delete_category') }} "{{ $category->name }}"?\n\n{{ __('messages.action_cannot_be_undone') }}')) {
            document.getElementById('deleteForm').submit();
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
