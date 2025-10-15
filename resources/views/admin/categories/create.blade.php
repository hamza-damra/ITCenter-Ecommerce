@extends('admin.layout')

@section('title', 'Create Category')

@section('content')
<div class="top-bar">
    <h1>Create New Category</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">← Back to Categories</a>
</div>

<div class="content-box">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="parent_id">Parent Category</label>
            <select id="parent_id" name="parent_id" class="form-control">
                <option value="">None (Root Category)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="url" id="image" name="image" class="form-control" value="{{ old('image') }}" placeholder="https://images.unsplash.com/photo...">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active">Active</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Create Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection
