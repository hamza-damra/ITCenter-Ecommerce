@extends('admin.layout')

@section('title', __('messages.create_new_attribute'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>{{ __('messages.create_new_attribute') }}</h1>
        <p>{{ __('messages.manage_attributes_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' || app()->getLocale() === 'he' ? 'right' : 'left' }}"></i> {{ __('messages.back_to_attributes') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-plus-circle"></i> {{ __('messages.attribute_information') }}</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.attributes.store') }}" method="POST" class="form-layout">
            @csrf

            <!-- Multi-language Names Section -->
            <div class="form-section">
                <h3 class="section-title">{{ __('messages.attribute_names_multilang') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            {{ __('messages.name_english') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en') }}" 
                            placeholder="e.g., Refresh Rate"
                            dir="ltr"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            {{ __('messages.name_arabic') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar') }}" 
                            placeholder="مثال: معدل التحديث"
                            required 
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            {{ __('messages.name_hebrew') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_he" 
                            name="name_he" 
                            class="form-control @error('name_he') is-invalid @enderror" 
                            value="{{ old('name_he') }}" 
                            placeholder="לדוגמה: קצב רענון"
                            required 
                            dir="rtl">
                        @error('name_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">
                            {{ __('messages.slug_field') }}
                        </label>
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            class="form-control @error('slug') is-invalid @enderror" 
                            value="{{ old('slug') }}" 
                            placeholder="{{ __('messages.slug_placeholder') }}"
                            dir="ltr">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.slug_auto_generate') }}
                        </p>
                        @error('slug')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Attribute Configuration Section -->
            <div class="form-section">
                <h3 class="section-title">{{ __('messages.attribute_configuration') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="type" class="form-label">
                            {{ __('messages.type_field') }}
                            <span class="required">*</span>
                        </label>
                        <select 
                            id="type" 
                            name="type" 
                            class="form-control @error('type') is-invalid @enderror"
                            required>
                            <option value="">{{ __('messages.select_type') }}</option>
                            <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>{{ __('messages.type_select') }}</option>
                            <option value="multi_select" {{ old('type') == 'multi_select' ? 'selected' : '' }}>{{ __('messages.type_multi_select') }}</option>
                            <option value="range" {{ old('type') == 'range' ? 'selected' : '' }}>{{ __('messages.type_range') }}</option>
                            <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>{{ __('messages.type_color') }}</option>
                        </select>
                        @error('type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit" class="form-label">
                            {{ __('messages.unit_field') }}
                        </label>
                        <input 
                            type="text" 
                            id="unit" 
                            name="unit" 
                            class="form-control @error('unit') is-invalid @enderror" 
                            value="{{ old('unit') }}" 
                            placeholder="{{ __('messages.unit_placeholder') }}">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.unit_help') }}
                        </p>
                        @error('unit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="order" class="form-label">
                            {{ __('messages.display_order') }}
                        </label>
                        <input 
                            type="number" 
                            id="order" 
                            name="order" 
                            class="form-control @error('order') is-invalid @enderror" 
                            value="{{ old('order', 0) }}" 
                            min="0"
                            placeholder="0">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.order_help') }}
                        </p>
                        @error('order')
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
                            id="is_filterable" 
                            name="is_filterable" 
                            value="1" 
                            {{ old('is_filterable', true) ? 'checked' : '' }}>
                        <span>
                            <strong>{{ __('messages.filterable_label') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.filterable_help') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>
                            <strong>{{ __('messages.active_label') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.active_help') }}</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0; margin-top: 24px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.create_attribute_btn') }}
                </button>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
