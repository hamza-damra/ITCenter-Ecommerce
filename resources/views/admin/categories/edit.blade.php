@extends('admin.layout')

@section('title', 'Edit Category')

@section('content')
<div class="top-bar">
    <h1>Edit Category: {{ $category->name }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">← Back to Categories</a>
</div>

<div class="content-box">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="form-group">
            <label for="parent_id">Parent Category</label>
            <select id="parent_id" name="parent_id" class="form-control">
                <option value="">None (Root Category)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="url" id="image" name="image" class="form-control" value="{{ old('image', $category->image) }}">
            @if($category->image)
                <img src="{{ $category->image }}" alt="Current Image" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
            <label for="is_active">Active</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection
