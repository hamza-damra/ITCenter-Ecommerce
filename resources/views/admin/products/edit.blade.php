@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<div class="top-bar">
    <h1>Edit Product: {{ $product->name }}</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">← Back to Products</a>
</div>

<div class="content-box">
    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="brand_id">Brand</label>
            <select id="brand_id" name="brand_id" class="form-control">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label for="sale_price">Sale Price</label>
            <input type="number" id="sale_price" name="sale_price" class="form-control" step="0.01" value="{{ old('sale_price', $product->sale_price) }}">
        </div>

        <div class="form-group">
            <label for="stock_quantity">Stock Quantity *</label>
            <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
        </div>

        <div class="form-group">
            <label for="main_image">Main Image URL *</label>
            <input type="url" id="main_image" name="main_image" class="form-control" value="{{ old('main_image', $product->main_image) }}" required>
            @if($product->main_image)
                <img src="{{ $product->main_image }}" alt="Current Image" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label for="additional_images">Additional Images (One URL per line)</label>
            <textarea id="additional_images" name="additional_images" class="form-control" rows="5" placeholder="https://picsum.photos/800/801&#10;https://picsum.photos/800/802&#10;https://picsum.photos/800/803">{{ old('additional_images', $product->images->where('is_primary', false)->pluck('image_path')->implode("\n")) }}</textarea>
            @error('additional_images')<span style="color: red;">{{ $message }}</span>@enderror
            <small style="color: #7f8c8d;">Enter each image URL on a new line. These will be displayed as additional product images in the gallery.</small>
            
            @if($product->images->where('is_primary', false)->count() > 0)
                <div style="margin-top: 15px;">
                    <strong>Current Additional Images:</strong>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                        @foreach($product->images->where('is_primary', false) as $image)
                            <img src="{{ $image->image_path }}" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 2px solid #ddd;">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea id="short_description" name="short_description" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="description">Full Description</label>
            <textarea id="description" name="description" class="form-control" style="min-height: 150px;">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
            <label for="is_active">Active</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
            <label for="is_featured">Featured</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
            <label for="is_new">New Product</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_bestseller" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
            <label for="is_bestseller">Bestseller</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection
