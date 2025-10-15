@extends('admin.layout')

@section('title', 'Create Product')

@section('content')
<div class="top-bar">
    <h1>Create New Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">← Back to Products</a>
</div>

<div class="content-box">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="brand_id">Brand</label>
            <select id="brand_id" name="brand_id" class="form-control">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
            @error('price')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="sale_price">Sale Price</label>
            <input type="number" id="sale_price" name="sale_price" class="form-control" step="0.01" value="{{ old('sale_price') }}">
            @error('sale_price')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="stock_quantity">Stock Quantity *</label>
            <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}" required>
            @error('stock_quantity')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="main_image">Main Image URL * (e.g., https://picsum.photos/800/800)</label>
            <input type="url" id="main_image" name="main_image" class="form-control" value="{{ old('main_image') }}" required placeholder="https://picsum.photos/800/800">
            @error('main_image')<span style="color: red;">{{ $message }}</span>@enderror
            <small style="color: #7f8c8d;">Use placeholder services like: https://picsum.photos/800/800 or https://placehold.co/800x800</small>
        </div>

        <div class="form-group">
            <label for="additional_images">Additional Images (One URL per line)</label>
            <textarea id="additional_images" name="additional_images" class="form-control" rows="5" placeholder="https://picsum.photos/800/801&#10;https://picsum.photos/800/802&#10;https://picsum.photos/800/803">{{ old('additional_images') }}</textarea>
            @error('additional_images')<span style="color: red;">{{ $message }}</span>@enderror
            <small style="color: #7f8c8d;">Enter each image URL on a new line. These will be displayed as additional product images in the gallery.</small>
        </div>

        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea id="short_description" name="short_description" class="form-control">{{ old('short_description') }}</textarea>
            @error('short_description')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="description">Full Description</label>
            <textarea id="description" name="description" class="form-control" style="min-height: 150px;">{{ old('description') }}</textarea>
            @error('description')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active">Active</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
            <label for="is_featured">Featured</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
            <label for="is_new">New Product</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_bestseller" name="is_bestseller" value="1" {{ old('is_bestseller') ? 'checked' : '' }}>
            <label for="is_bestseller">Bestseller</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Create Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection
