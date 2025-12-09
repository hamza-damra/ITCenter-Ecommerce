@extends('admin.layout')

@section('title', __('messages.create_tag'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-plus"></i> {{ __('messages.create_new_tag') }}</h1>
        <p>{{ __('messages.add_tag_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_tags') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.tags.store') }}" method="POST" style="max-width: 800px;">
    @csrf

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-tag"></i> {{ __('messages.tag_information') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name_en" class="form-label">
                        {{ __('messages.tag_name_english') }}
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="name_en" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                           value="{{ old('name_en') }}" placeholder="e.g., Gaming, Office, Student" required>
                    @error('name_en')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name_ar" class="form-label">
                        {{ __('messages.tag_name_arabic') }}
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="name_ar" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                           value="{{ old('name_ar') }}" placeholder="أدخل اسم الوسم" required dir="rtl">
                    @error('name_ar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="color" class="form-label">
                        {{ __('messages.tag_color') }}
                    </label>
                    <input type="color" id="color" name="color" class="form-control" 
                           value="{{ old('color', '#3b82f6') }}" style="height: 42px; padding: 4px;">
                    <p class="form-text">{{ __('messages.tag_color_help') }}</p>
                </div>

                <div class="form-group">
                    <label for="icon" class="form-label">
                        {{ __('messages.tag_icon') }}
                        <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                    </label>
                    <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                           value="{{ old('icon') }}" placeholder="e.g., fas fa-gamepad, fas fa-briefcase">
                    <p class="form-text">{{ __('messages.icon_help_text') }}</p>
                    @error('icon')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="position" class="form-label">
                    {{ __('messages.display_position') }}
                    <span style="color: #64748b; font-size: 12px;">{{ __('messages.optional') }}</span>
                </label>
                <input type="number" id="position" name="position" class="form-control @error('position') is-invalid @enderror" 
                       value="{{ old('position', 0) }}" min="0">
                <p class="form-text">{{ __('messages.lower_numbers_first') }}</p>
                @error('position')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>
                        <strong><i class="fas fa-eye"></i> {{ __('messages.active_label') }}</strong>
                        <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.display_tag_in_store') }}</p>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 12px; padding-top: 24px;">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> {{ __('messages.create_tag_button') }}
        </button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
        </a>
    </div>
</form>
@endsection
