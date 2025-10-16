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
            <label for="name_en">Category Name (English) *</label>
            <input type="text" id="name_en" name="name_en" class="form-control" value="{{ old('name_en', $category->name_en) }}" required>
            @error('name_en')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="name_ar">اسم الفئة (عربي) *</label>
            <input type="text" id="name_ar" name="name_ar" class="form-control" value="{{ old('name_ar', $category->name_ar) }}" required dir="rtl">
            @error('name_ar')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="parent_id">Parent Category</label>
            <select id="parent_id" name="parent_id" class="form-control">
                <option value="">None (Root Category)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
            @error('parent_id')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="url" id="image" name="image" class="form-control" value="{{ old('image', $category->image) }}">
            @error('image')<span style="color: red;">{{ $message }}</span>@enderror
            @if($category->image)
                <img src="{{ $category->image }}" alt="Current Image" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label for="description_en">Description (English)</label>
            <textarea id="description_en" name="description_en" class="form-control">{{ old('description_en', $category->description_en) }}</textarea>
            @error('description_en')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="description_ar">الوصف (عربي)</label>
            <textarea id="description_ar" name="description_ar" class="form-control" dir="rtl">{{ old('description_ar', $category->description_ar) }}</textarea>
            @error('description_ar')<span style="color: red;">{{ $message }}</span>@enderror
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
