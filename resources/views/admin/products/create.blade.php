@extends('admin.layout')

@section('title', 'Create Product')

@section('content')
<style>
    /* Product Create Page Specific Styles */
    .product-form-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
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

    .progress-indicator {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        text-align: center;
    }

    .progress-indicator h3 {
        font-size: 14px;
        color: var(--secondary);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .progress-steps {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .progress-step {
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 6px;
        background: #f8fafc;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .progress-step i {
        font-size: 12px;
    }

    .section-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .image-preview-box {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border: 2px dashed var(--primary);
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: var(--secondary);
    }

    .image-preview-box i {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 12px;
        opacity: 0.5;
    }

    @media (max-width: 1024px) {
        .product-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1>Add New Product</h1>
        <p>Create and configure a new product for your catalog</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" class="product-form-grid">
    @csrf

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Basic Information</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Product Name (English)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="Enter product name in English"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            اسم المنتج (عربي)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="أدخل اسم المنتج بالعربية"
                            required 
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            Category
                            <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_en ?? $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="brand_id" class="form-label">
                            Brand
                        </label>
                        <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                            <option value="">Select a Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name_en ?? $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-dollar-sign"></i> Pricing & Inventory</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">
                            Regular Price
                            <span class="required">*</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--secondary); font-weight: 600;">$</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                class="form-control @error('price') is-invalid @enderror" 
                                step="0.01" 
                                value="{{ old('price') }}" 
                                placeholder="0.00"
                                style="padding-left: 28px;"
                                required>
                        </div>
                        @error('price')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_price" class="form-label">
                            Sale Price
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--secondary); font-weight: 600;">$</span>
                            <input 
                                type="number" 
                                id="sale_price" 
                                name="sale_price" 
                                class="form-control @error('sale_price') is-invalid @enderror" 
                                step="0.01" 
                                value="{{ old('sale_price') }}"
                                placeholder="0.00"
                                style="padding-left: 28px;">
                        </div>
                        @error('sale_price')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">
                            Stock Quantity
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            class="form-control @error('stock_quantity') is-invalid @enderror" 
                            value="{{ old('stock_quantity', 0) }}" 
                            placeholder="0"
                            required>
                        @error('stock_quantity')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-images"></i> Product Images</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="main_image" class="form-label">
                        Main Product Image
                        <span class="required">*</span>
                    </label>
                    <input 
                        type="url" 
                        id="main_image" 
                        name="main_image" 
                        class="form-control @error('main_image') is-invalid @enderror" 
                        value="{{ old('main_image') }}" 
                        placeholder="https://picsum.photos/800/800"
                        required>
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> Recommended: Use services like <strong>picsum.photos</strong> or <strong>placehold.co</strong>
                    </p>
                    @error('main_image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="additional_images" class="form-label">
                        Additional Images
                        <span style="color: #64748b; font-size: 12px;">(Optional - One URL per line)</span>
                    </label>
                    <textarea 
                        id="additional_images" 
                        name="additional_images" 
                        class="form-control @error('additional_images') is-invalid @enderror" 
                        rows="5" 
                        placeholder="https://picsum.photos/800/801&#10;https://picsum.photos/800/802&#10;https://picsum.photos/800/803">{{ old('additional_images') }}</textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> Enter each image URL on a new line for the product gallery
                    </p>
                    @error('additional_images')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
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
                        <label for="short_description_en" class="form-label">
                            Short Description (English)
                        </label>
                        <textarea 
                            id="short_description_en" 
                            name="short_description_en" 
                            class="form-control @error('short_description_en') is-invalid @enderror"
                            placeholder="Brief description for product listings"
                            style="min-height: 80px;">{{ old('short_description_en') }}</textarea>
                        @error('short_description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short_description_ar" class="form-label">
                            وصف قصير (عربي)
                        </label>
                        <textarea 
                            id="short_description_ar" 
                            name="short_description_ar" 
                            class="form-control @error('short_description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف قصير للمنتج"
                            style="min-height: 80px;">{{ old('short_description_ar') }}</textarea>
                        @error('short_description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            Full Description (English)
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="Complete product description with details"
                            style="min-height: 150px;">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            وصف كامل (عربي)
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف المنتج الكامل بالتفاصيل"
                            style="min-height: 150px;">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> Product Settings</h2>
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
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Display this product in the store</p>
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
                            <strong><i class="fas fa-star"></i> Featured</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show on homepage featured section</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_new" 
                            name="is_new" 
                            value="1" 
                            {{ old('is_new') ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-badge"></i> New Product</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Mark as new to highlight in store</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_bestseller" 
                            name="is_bestseller" 
                            value="1" 
                            {{ old('is_bestseller') ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-fire"></i> Bestseller</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Mark as popular/bestselling product</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Create Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="form-sidebar">
        <!-- Quick Tips Card -->
        <div class="info-card">
            <strong><i class="fas fa-lightbulb"></i> Quick Tips</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                <li>Use clear, descriptive names</li>
                <li>Fill all required fields (*)</li>
                <li>Add discount prices for sales</li>
                <li>Use high-quality images</li>
            </ul>
        </div>

        <!-- Required Fields Card -->
        <div class="info-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left-color: var(--warning); color: #78350f;">
            <strong style="color: var(--dark);"><i class="fas fa-asterisk"></i> Required Fields</strong>
            <p style="font-size: 13px; margin-top: 8px;">All fields marked with <span class="required">*</span> must be completed before creating the product.</p>
        </div>

        <!-- Progress Card -->
        <div class="progress-indicator">
            <h3><i class="fas fa-tasks"></i> Setup Progress</h3>
            <div class="progress-steps">
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i> Basic Info
                </div>
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i> Pricing
                </div>
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i> Images
                </div>
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i> Descriptions
                </div>
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i> Settings
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="info-card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left-color: var(--success); color: #065f46;">
            <strong style="color: var(--dark);"><i class="fas fa-circle-info"></i> Help</strong>
            <p style="font-size: 13px; margin-top: 8px;">Need help? All fields have helpful hints. Check them as you fill the form.</p>
        </div>
    </div>
</form>

@endsection
