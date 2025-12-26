@extends('admin.layout')

@section('title', __('messages.create_template'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-plus-circle"></i> {{ __('messages.create_template') }}</h1>
        <p>{{ __('messages.create_spec_template_desc') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.spec-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-info-circle"></i> {{ __('messages.template_information') }}</h2>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <form action="{{ route('admin.spec-templates.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            {{ __('messages.category') }}
                            <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">{{ __('messages.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_en }}
                                    @if($category->name_ar) - {{ $category->name_ar }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.category_template_unique') }}
                        </p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            {{ __('messages.template_name') }} (English)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="e.g., PC/Laptop Template"
                            maxlength="100"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            {{ __('messages.template_name') }} (العربية)
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="مثال: قالب الحاسوب"
                            maxlength="100"
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            {{ __('messages.template_name') }} (עברית)
                        </label>
                        <input 
                            type="text" 
                            id="name_he" 
                            name="name_he" 
                            class="form-control @error('name_he') is-invalid @enderror" 
                            value="{{ old('name_he') }}" 
                            placeholder="דוגמה: תבנית מחשב"
                            maxlength="100"
                            dir="rtl">
                        @error('name_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="hidden" name="is_active" value="0">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-check-circle"></i> {{ __('messages.active') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.template_active_desc') }}</p>
                        </span>
                    </label>
                </div>

                <div style="display: flex; gap: 12px; padding-top: 24px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> {{ __('messages.create_and_add_fields') }}
                    </button>
                    <a href="{{ route('admin.spec-templates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        @else
            <div class="admin-empty-state">
                <div class="admin-empty-state-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>{{ __('messages.all_categories_have_templates') }}</h3>
                <p>{{ __('messages.all_categories_assigned') }}</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('messages.create_new_category') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection






