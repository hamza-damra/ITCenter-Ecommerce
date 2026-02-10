@extends('admin.layout')

@section('title', __('messages.add_section'))

@section('content')
<!-- Hero Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div>
                <h1>{{ __('messages.add_section') }}</h1>
                <p>{{ __('messages.add_section_subtitle') }}</p>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.home-sections.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') }}
            </a>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>{{ __('messages.please_correct_errors') }}</strong>
        <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('admin.home-sections.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-cog"></i> {{ __('messages.section_settings') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-layout">
                <!-- Display Order -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.display_order') }} <span class="required">*</span></label>
                        <input type="number" name="display_order" class="form-control {{ $errors->has('display_order') ? 'is-invalid' : '' }}"
                            value="{{ old('display_order', $nextOrder) }}" min="1" required>
                        <div class="form-text">{{ __('messages.display_order_help') }}</div>
                        @if($errors->has('display_order'))
                            <div class="error-message">{{ $errors->first('display_order') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Active Toggle -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active">
                                <strong>{{ __('messages.active') }}</strong>
                                <br><span style="font-size: 0.85rem; color: var(--secondary);">{{ __('messages.section_active_help') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Multilingual Content -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2><i class="fas fa-language"></i> {{ __('messages.multilingual_content') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-layout">
                <!-- English -->
                <div class="form-section">
                    <div class="section-title">{{ __('messages.english') }}</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.title_english') }}</label>
                            <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}" maxlength="120">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.subtitle') }} ({{ __('messages.english') }})</label>
                            <input type="text" name="subtitle_en" class="form-control" value="{{ old('subtitle_en') }}" maxlength="255">
                        </div>
                    </div>
                </div>

                <!-- Arabic -->
                <div class="form-section">
                    <div class="section-title">{{ __('messages.arabic') }}</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.title_arabic') }}</label>
                            <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar') }}" maxlength="120" dir="rtl">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.subtitle') }} ({{ __('messages.arabic') }})</label>
                            <input type="text" name="subtitle_ar" class="form-control" value="{{ old('subtitle_ar') }}" maxlength="255" dir="rtl">
                        </div>
                    </div>
                </div>

                <!-- Hebrew -->
                <div class="form-section">
                    <div class="section-title">{{ __('messages.hebrew') }}</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.title_hebrew') }}</label>
                            <input type="text" name="title_he" class="form-control" value="{{ old('title_he') }}" maxlength="120" dir="rtl">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.subtitle') }} ({{ __('messages.hebrew') }})</label>
                            <input type="text" name="subtitle_he" class="form-control" value="{{ old('subtitle_he') }}" maxlength="255" dir="rtl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section-Specific Settings -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2><i class="fas fa-sliders-h"></i> {{ __('messages.advanced_settings') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-layout">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.max_products') }}</label>
                        <input type="number" name="settings[max_products]" class="form-control"
                            value="{{ old('settings.max_products', 8) }}" min="1" max="50">
                        <div class="form-text">{{ __('messages.max_products_help') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.cards_to_scroll') }}</label>
                        <input type="number" name="settings[cards_to_scroll]" class="form-control"
                            value="{{ old('settings.cards_to_scroll', 1) }}" min="1" max="10">
                        <div class="form-text">{{ __('messages.cards_to_scroll_help') }}</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.auto_scroll_interval') }}</label>
                        <input type="number" name="settings[auto_scroll_interval]" class="form-control"
                            value="{{ old('settings.auto_scroll_interval', 5000) }}" min="1000" max="30000" step="500">
                        <div class="form-text">{{ __('messages.auto_scroll_interval_help') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.background_color') }}</label>
                        <input type="text" name="settings[background_color]" class="form-control"
                            value="{{ old('settings.background_color') }}" placeholder="#ffffff">
                        <div class="form-text">{{ __('messages.background_color_help') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Submit -->
    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="{{ route('admin.home-sections.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('messages.save_section') }}
        </button>
    </div>
</form>

@endsection
