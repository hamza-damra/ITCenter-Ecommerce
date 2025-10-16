@extends('admin.layout')

@section('title', 'Create Brand')

@section('content')
<div class="top-bar">
    <h1>Create New Brand</h1>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-primary">← Back to Brands</a>
</div>

<div class="content-box">
    <form action="{{ route('admin.brands.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name_en">Brand Name (English) *</label>
            <input type="text" id="name_en" name="name_en" class="form-control" value="{{ old('name_en') }}" required>
            @error('name_en')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="name_ar">اسم العلامة التجارية (عربي) *</label>
            <input type="text" id="name_ar" name="name_ar" class="form-control" value="{{ old('name_ar') }}" required dir="rtl">
            @error('name_ar')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="logo">Logo URL</label>
            <input type="url" id="logo" name="logo" class="form-control" value="{{ old('logo') }}" placeholder="https://logo.clearbit.com/apple.com">
            @error('logo')<span style="color: red;">{{ $message }}</span>@enderror
            <small style="color: #7f8c8d;">Use Clearbit Logo API: https://logo.clearbit.com/brandname.com</small>
        </div>

        <div class="form-group">
            <label for="website">Website URL</label>
            <input type="url" id="website" name="website" class="form-control" value="{{ old('website') }}">
            @error('website')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="description_en">Description (English)</label>
            <textarea id="description_en" name="description_en" class="form-control">{{ old('description_en') }}</textarea>
            @error('description_en')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="description_ar">الوصف (عربي)</label>
            <textarea id="description_ar" name="description_ar" class="form-control" dir="rtl">{{ old('description_ar') }}</textarea>
            @error('description_ar')<span style="color: red;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active">Active</label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
            <label for="is_featured">Featured</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Create Brand</button>
            <a href="{{ route('admin.brands.index') }}" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection
