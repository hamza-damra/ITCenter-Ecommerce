@extends('admin.layout')

@section('title', 'Create Brand')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Create New Brand</h1>
        <p>Add a new brand to your product catalog</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Brands
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-plus-circle"></i> Brand Information</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST" class="form-layout">
            @csrf

            <!-- Basic Information Section -->
            <div class="form-section">
                <h3 class="section-title">Basic Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Brand Name (English) 
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="e.g., Apple Inc."
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            اسم العلامة التجارية (عربي) 
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="أدخل اسم العلامة التجارية"
                            required 
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Branding Section -->
            <div class="form-section">
                <h3 class="section-title">Branding Details</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="logo" class="form-label">
                            Logo URL
                        </label>
                        <input 
                            type="url" 
                            id="logo" 
                            name="logo" 
                            class="form-control @error('logo') is-invalid @enderror" 
                            value="{{ old('logo') }}" 
                            placeholder="https://logo.clearbit.com/apple.com">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Use Clearbit Logo API: https://logo.clearbit.com/brandname.com
                        </p>
                        @error('logo')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="website" class="form-label">
                            Website URL
                        </label>
                        <input 
                            type="url" 
                            id="website" 
                            name="website" 
                            class="form-control @error('website') is-invalid @enderror" 
                            value="{{ old('website') }}"
                            placeholder="https://www.example.com">
                        @error('website')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="form-section">
                <h3 class="section-title">Descriptions</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            Description (English)
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="Enter brand description in English">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            الوصف (عربي)
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="أدخل وصف العلامة التجارية بالعربية">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div class="form-section">
                <h3 class="section-title">Settings</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>
                            <strong>Active</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Make this brand visible in the store</p>
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
                            <strong>Featured</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Display on the featured brands section</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0; margin-top: 24px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Create Brand
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
