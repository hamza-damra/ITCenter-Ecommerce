@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<style>
    /* Product Edit Page Specific Styles */
    .product-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    .section-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
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

    .additional-images-preview {
        margin-top: 15px;
    }

    .additional-images-preview strong {
        font-size: 12px;
        color: var(--secondary);
        margin-bottom: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .images-grid img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--border);
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
        <h1><i class="fas fa-edit"></i> Edit Product</h1>
        <p>Update product information: <strong>{{ $product->name }}</strong></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" class="product-form-grid">
    @csrf
    @method('PUT')

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
                            value="{{ old('name_en', $product->name_en) }}" 
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
                            value="{{ old('name_ar', $product->name_ar) }}" 
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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                            <option value="">Select a Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                                value="{{ old('price', $product->price) }}" 
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
                                value="{{ old('sale_price', $product->sale_price) }}"
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
                            value="{{ old('stock_quantity', $product->stock_quantity) }}" 
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
                        value="{{ old('main_image', $product->main_image) }}" 
                        placeholder="https://picsum.photos/800/800"
                        required>
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> Recommended: Use services like <strong>picsum.photos</strong> or <strong>placehold.co</strong>
                    </p>
                    @error('main_image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    
                    @if($product->main_image)
                        <div class="current-image-container">
                            <div class="current-image-label">
                                <i class="fas fa-image"></i>
                                Current Main Image
                            </div>
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                        </div>
                    @endif
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
                        placeholder="https://picsum.photos/800/801&#10;https://picsum.photos/800/802&#10;https://picsum.photos/800/803">{{ old('additional_images', $product->images->where('is_primary', false)->pluck('image_path')->implode("\n")) }}</textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> Enter each image URL on a new line for the product gallery
                    </p>
                    @error('additional_images')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    
                    @if($product->images->where('is_primary', false)->count() > 0)
                        <div class="additional-images-preview">
                            <strong>
                                <i class="fas fa-images"></i>
                                Current Additional Images ({{ $product->images->where('is_primary', false)->count() }})
                            </strong>
                            <div class="images-grid">
                                @foreach($product->images->where('is_primary', false) as $image)
                                    <img src="{{ $image->image_path }}" alt="Product Image">
                                @endforeach
                            </div>
                        </div>
                    @endif
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
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <textarea 
                            id="short_description_en" 
                            name="short_description_en" 
                            class="form-control @error('short_description_en') is-invalid @enderror"
                            placeholder="Brief description for product listings"
                            style="min-height: 80px;">{{ old('short_description_en', $product->short_description_en) }}</textarea>
                        @error('short_description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short_description_ar" class="form-label">
                            وصف قصير (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
                        </label>
                        <textarea 
                            id="short_description_ar" 
                            name="short_description_ar" 
                            class="form-control @error('short_description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف قصير للمنتج"
                            style="min-height: 80px;">{{ old('short_description_ar', $product->short_description_ar) }}</textarea>
                        @error('short_description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            Full Description (English)
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="Complete product description with details"
                            style="min-height: 150px;">{{ old('description_en', $product->description_en) }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            وصف كامل (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف المنتج الكامل بالتفاصيل"
                            style="min-height: 150px;">{{ old('description_ar', $product->description_ar) }}</textarea>
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
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
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
                            {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
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
                            {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
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
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>

        <!-- Danger Zone -->
        <div class="delete-section">
            <div class="danger-zone">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    Danger Zone
                </h3>
                <p>Deleting this product will permanently remove it from your store. This action cannot be undone.</p>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> Delete Product
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete "{{ $product->name }}"?\n\nThis action cannot be undone and will permanently remove this product from your store.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>

@endsection
