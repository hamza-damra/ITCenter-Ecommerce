@extends('admin.layout')

@section('title', 'Edit Brand')

@section('content')
<div class="top-bar">
    <h1>Edit Brand: {{ $brand->name }}</h1>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-primary">← Back to Brands</a>
</div>

<div class="content-box">
    <form action="{{ route('admin.brands.update', $brand) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Brand Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
        </div>

        <div class="form-group">
            <label for="logo">Logo URL</label>
            <input type="url" id="logo" name="logo" class="form-control" value="{{ old('logo', $brand->logo) }}">
            @if($brand->logo)
                <img src="{{ $brand->logo }}" alt="Current Logo" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label for="website">Website URL</label>
            <input type="url" id="website" name="website" class="form-control" value="{{ old('website', $brand->website) }}">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $brand->description) }}</textarea>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
            <label for="is_active">Active</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}>
            <label for="is_featured">Featured</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Brand</button>
            <a href="{{ route('admin.brands.index') }}" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection
