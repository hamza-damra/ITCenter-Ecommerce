@extends('admin.layout')

@section('title', __('messages.create_brand'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>{{ __('messages.create_new_brand') }}</h1>
        <p>{{ __('messages.add_brand_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_brands') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-plus-circle"></i> {{ __('messages.brand_information') }}</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST" class="form-layout">
            @csrf

            <!-- Basic Information Section -->
            <div class="form-section">
                <h3 class="section-title">{{ __('messages.basic_information') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            {{ __('messages.brand_name_english') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="{{ __('messages.brand_name_placeholder_en') }}"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            {{ __('messages.brand_name_arabic') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="{{ __('messages.brand_name_placeholder_ar') }}"
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
                <h3 class="section-title">{{ __('messages.branding_details') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="logo" class="form-label">
                            {{ __('messages.logo_url') }}
                        </label>
                        <input 
                            type="url" 
                            id="logo" 
                            name="logo" 
                            class="form-control @error('logo') is-invalid @enderror" 
                            value="{{ old('logo') }}" 
                            placeholder="{{ __('messages.logo_placeholder') }}">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.logo_tip') }}
                        </p>
                        @error('logo')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="website" class="form-label">
                            {{ __('messages.website_url') }}
                        </label>
                        <input 
                            type="url" 
                            id="website" 
                            name="website" 
                            class="form-control @error('website') is-invalid @enderror" 
                            value="{{ old('website') }}"
                            placeholder="{{ __('messages.website_placeholder') }}">
                        @error('website')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="form-section">
                <h3 class="section-title">{{ __('messages.descriptions') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            {{ __('messages.brand_description_english') }}
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="{{ __('messages.brand_description_placeholder_en') }}">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            {{ __('messages.brand_description_arabic') }}
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="{{ __('messages.brand_description_placeholder_ar') }}">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div class="form-section">
                <h3 class="section-title">{{ __('messages.settings') }}</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>
                            <strong>{{ __('messages.active_brand') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.make_brand_visible') }}</p>
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
                            <strong>{{ __('messages.featured_brand') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.display_featured_section') }}</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0; margin-top: 24px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.create_brand_button') }}
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
