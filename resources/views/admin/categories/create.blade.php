@extends('admin.layout')

@section('title', 'Create Category')

@section('content')
<style>
    /* Category Create Page Specific Styles */
    .category-form-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
        margin-bottom: 24px;
    }

    .form-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-left: 4px solid var(--primary);
        padding: 16px;
        border-radius: 8px;
        font-size: 14px;
        color: #0c4a6e;
    }

    .info-card i {
        margin-right: 8px;
        color: var(--primary);
    }

    .info-card strong {
        display: block;
        margin-bottom: 4px;
        color: var(--dark);
    }

    .info-card ul {
        margin: 8px 0 0 20px;
        padding: 0;
    }

    .info-card li {
        margin-bottom: 4px;
        font-size: 13px;
    }

    .hierarchy-indicator {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        text-align: center;
    }

    .hierarchy-indicator h3 {
        font-size: 14px;
        color: var(--secondary);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .hierarchy-level {
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 6px;
        background: #f8fafc;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .hierarchy-level:last-child {
        margin-bottom: 0;
    }

    .hierarchy-level i {
        font-size: 12px;
        color: var(--primary);
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

    @media (max-width: 1024px) {
        .category-form-grid {
            grid-template-columns: 1fr;
        }

        .form-sidebar {
            grid-column: 1;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1>Create New Category</h1>
        <p>Add a new product category to organize your catalog</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
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
                <h2><i class="fas fa-folder-plus"></i> Category Information</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Category Name (English)
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
                            اسم الفئة (عربي)
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
                        Parent Category
                        <span style="color: #64748b; font-size: 12px;">(Optional - For Subcategories)</span>
                    </label>
                    <select id="parent_id" name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">→ Root Category (No Parent)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name_en ?? $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> Leave empty to create a main category, or select a parent for a subcategory
                    </p>
                    @error('parent_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order" class="form-label">
                        Display Order
                        <span style="color: #64748b; font-size: 12px;">(Optional)</span>
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
                        <i class="fas fa-sort-numeric-down"></i> Lower numbers appear first
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
                <h2><i class="fas fa-image"></i> Category Image</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="image" class="form-label">
                        Category Image URL
                        <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                    </label>
                    <input 
                        type="url" 
                        id="image" 
                        name="image" 
                        class="form-control @error('image') is-invalid @enderror" 
                        value="{{ old('image') }}" 
                        placeholder="https://images.unsplash.com/photo-..."
                        oninput="previewImage(this.value)">
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> Use services like <strong>Unsplash</strong>, <strong>Pexels</strong>, or <strong>Pixabay</strong> for high-quality images
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
                <h2><i class="fas fa-align-left"></i> Descriptions</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            Description (English)
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="Enter a brief description of this category"
                            style="min-height: 100px;">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            الوصف (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="أدخل وصف الفئة"
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
                <h2><i class="fas fa-search"></i> SEO Settings</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">
                            Meta Title
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="meta_title" 
                            name="meta_title" 
                            class="form-control @error('meta_title') is-invalid @enderror" 
                            value="{{ old('meta_title') }}" 
                            placeholder="SEO title for search engines"
                            maxlength="60">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Recommended: 50-60 characters
                        </p>
                        @error('meta_title')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords" class="form-label">
                            Meta Keywords
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="meta_keywords" 
                            name="meta_keywords" 
                            class="form-control @error('meta_keywords') is-invalid @enderror" 
                            value="{{ old('meta_keywords') }}" 
                            placeholder="keyword1, keyword2, keyword3">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Separate keywords with commas
                        </p>
                        @error('meta_keywords')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="meta_description" class="form-label">
                        Meta Description
                        <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                    </label>
                    <textarea 
                        id="meta_description" 
                        name="meta_description" 
                        class="form-control @error('meta_description') is-invalid @enderror"
                        placeholder="Brief description for search engine results"
                        style="min-height: 80px;">{{ old('meta_description') }}</textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> Recommended: 150-160 characters
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
                <h2><i class="fas fa-cog"></i> Category Settings</h2>
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
                            <strong><i class="fas fa-eye"></i> Active</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Display this category in the store</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Create Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="form-sidebar">
        <!-- Quick Tips Card -->
        <div class="info-card">
            <strong><i class="fas fa-lightbulb"></i> Best Practices</strong>
            <ul>
                <li>Use clear, concise names</li>
                <li>Add descriptive images</li>
                <li>Optimize for SEO</li>
                <li>Organize hierarchy logically</li>
                <li>Keep names consistent</li>
            </ul>
        </div>

        <!-- Category Hierarchy Card -->
        <div class="hierarchy-indicator">
            <h3><i class="fas fa-sitemap"></i> Category Hierarchy</h3>
            <div class="hierarchy-level">
                <i class="fas fa-folder"></i>
                <span>Root Category (Main)</span>
            </div>
            <div class="hierarchy-level">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="hierarchy-level">
                <i class="fas fa-folder"></i>
                <span>Subcategory</span>
            </div>
        </div>

        <!-- Image Tips Card -->
        <div class="info-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left-color: var(--warning); color: #78350f;">
            <strong style="color: var(--dark);"><i class="fas fa-image"></i> Image Tips</strong>
            <ul style="color: #78350f;">
                <li>Use high-quality images</li>
                <li>Recommended: 800x600px</li>
                <li>Format: JPG or PNG</li>
                <li>Optimized file size</li>
            </ul>
        </div>

        <!-- Help Card -->
        <div class="info-card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left-color: var(--success); color: #065f46;">
            <strong style="color: var(--dark);"><i class="fas fa-circle-info"></i> Getting Started</strong>
            <p style="font-size: 13px; margin-top: 8px; color: #065f46;">
                Categories organize your products. Create main categories first, then add subcategories as needed.
            </p>
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
