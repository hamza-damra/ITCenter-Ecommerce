@extends('admin.layout')

@section('title', __('messages.site_settings'))

@section('content')
<style>
    .settings-tabs {
        display: flex;
        gap: 0;
        background: white;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        border-bottom: 2px solid var(--border);
    }

    .settings-tab {
        padding: 16px 28px;
        font-size: 15px;
        font-weight: 600;
        color: var(--secondary);
        cursor: pointer;
        border: none;
        background: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        position: relative;
        font-family: inherit;
    }

    .settings-tab:hover {
        color: var(--primary);
        background: #f8fafc;
    }

    .settings-tab.active {
        color: var(--primary);
        background: #eff6ff;
    }

    .settings-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary);
        border-radius: 3px 3px 0 0;
    }

    .settings-tab i {
        font-size: 16px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .settings-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .setting-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .setting-item .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
    }

    .setting-item .form-hint {
        font-size: 12px;
        color: var(--secondary);
        margin-top: 2px;
    }

    .setting-item .input-with-suffix {
        display: flex;
        align-items: stretch;
    }

    .setting-item .input-with-suffix .form-control {
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

    [dir="rtl"] .setting-item .input-with-suffix .form-control {
        border-radius: 0 8px 8px 0;
        border-right: 2px solid var(--border);
        border-left: none;
    }

    .setting-item .input-suffix {
        display: flex;
        align-items: center;
        padding: 0 14px;
        background: #f1f5f9;
        border: 2px solid var(--border);
        border-left: none;
        border-radius: 0 8px 8px 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--secondary);
        white-space: nowrap;
    }

    [dir="rtl"] .setting-item .input-suffix {
        border-radius: 8px 0 0 8px;
        border-left: 2px solid var(--border);
        border-right: none;
    }

    .password-requirements {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        margin-top: 8px;
    }

    .password-requirements h4 {
        font-size: 13px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .password-requirements ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .password-requirements ul li {
        font-size: 13px;
        color: var(--secondary);
        padding: 3px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .password-requirements ul li i {
        font-size: 11px;
        color: var(--primary);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 26px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider {
        background-color: var(--primary);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(22px);
    }

    [dir="rtl"] .toggle-slider:before {
        left: auto;
        right: 3px;
    }

    [dir="rtl"] .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(-22px);
    }

    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .settings-info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .settings-info-box i {
        color: var(--primary);
        font-size: 18px;
        margin-top: 2px;
    }

    .settings-info-box p {
        font-size: 14px;
        color: #1e40af;
        line-height: 1.5;
    }

    .toggle-password-btn {
        position: absolute;
        top: 50%;
        right: 12px;
        left: auto;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--secondary);
        padding: 4px;
    }

    [dir="rtl"] .toggle-password-btn {
        right: auto;
        left: 12px;
    }

    .policy-language-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        width: fit-content;
    }

    .policy-lang-tab {
        padding: 10px 22px;
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary);
        background: #f8fafc;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        border-right: 1px solid var(--border);
    }

    .policy-lang-tab:last-child {
        border-right: none;
    }

    [dir="rtl"] .policy-lang-tab {
        border-right: none;
        border-left: 1px solid var(--border);
    }

    [dir="rtl"] .policy-lang-tab:last-child {
        border-left: none;
    }

    .policy-lang-tab:hover {
        background: #eff6ff;
        color: var(--primary);
    }

    .policy-lang-tab.active {
        background: var(--primary);
        color: white;
    }

    .policy-lang-content {
        display: none;
    }

    .policy-lang-content.active {
        display: block;
    }

    .policy-lang-content .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        display: block;
    }

    .policy-textarea {
        font-family: 'Courier New', Consolas, monospace;
        font-size: 13px;
        line-height: 1.6;
        min-height: 300px;
        resize: vertical;
        white-space: pre-wrap;
    }

    .policy-hint {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        padding: 10px 14px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 8px;
        font-size: 13px;
        color: #92400e;
    }

    .policy-hint i {
        color: #d97706;
        font-size: 14px;
    }

    .social-links-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }

    .social-link-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 12px;
        transition: border-color 0.2s;
    }

    .social-link-row:hover { border-color: var(--primary); }

    .social-link-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 15px; flex-shrink: 0;
    }

    .social-link-name {
        font-weight: 600; font-size: 14px; color: var(--dark);
        min-width: 110px; flex-shrink: 0;
    }

    .social-link-url { flex: 1; }

    .social-toggle-btn, .social-delete-btn {
        width: 36px; height: 36px;
        border-radius: 8px; border: 1px solid var(--border);
        background: white; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0; transition: all 0.2s;
    }

    .social-toggle-btn.active  { background: #dcfce7; border-color: #86efac; color: #16a34a; }
    .social-toggle-btn:not(.active) { background: #fef2f2; border-color: #fca5a5; color: #dc2626; }
    .social-delete-btn { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }
    .social-delete-btn:hover { background: #dc2626; color: white; }

    .add-social-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 12px 20px; background: #eff6ff;
        border: 2px dashed var(--primary); border-radius: 12px;
        color: var(--primary); font-weight: 600; font-size: 14px;
        cursor: pointer; width: 100%; justify-content: center;
        transition: all 0.2s; margin-bottom: 20px; font-family: inherit;
    }
    .add-social-btn:hover { background: var(--primary); color: white; }

    .platform-picker-modal {
        display: none; position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .platform-picker-modal.show { display: flex; }

    .platform-picker-content {
        background: white; border-radius: 16px; padding: 24px;
        width: 520px; max-width: 95vw; max-height: 80vh;
        overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .platform-picker-title {
        font-size: 18px; font-weight: 700; color: var(--dark);
        margin-bottom: 20px;
        display: flex; align-items: center; justify-content: space-between;
    }

    .platform-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 10px; margin-bottom: 20px;
    }

    .platform-option {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        padding: 14px 10px; border: 2px solid var(--border);
        border-radius: 12px; cursor: pointer; transition: all 0.2s;
        background: white; font-family: inherit;
    }
    .platform-option:hover { border-color: var(--primary); background: #eff6ff; }
    .platform-option.already-added { opacity: 0.45; cursor: not-allowed; }

    .platform-option-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 20px;
    }
    .platform-option-name { font-size: 12px; font-weight: 600; color: var(--dark); text-align: center; }

    .custom-platform-form { border-top: 1px solid var(--border); padding-top: 16px; margin-top: 4px; }

    @media (max-width: 768px) {
        .settings-tabs { flex-wrap: wrap; }
        .settings-tab { padding: 12px 16px; font-size: 13px; }
        .policy-language-tabs { width: 100%; }
        .policy-lang-tab { flex: 1; text-align: center; padding: 10px 12px; }
        .social-link-name { min-width: 80px; }
    }

    /* Favicon / Site Icon Tab */
    .favicon-preview-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        padding: 24px;
        background: #f8fafc;
        border: 2px dashed var(--border);
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .favicon-preview-container.has-favicon {
        border-style: solid;
        border-color: var(--primary);
        background: #eff6ff;
    }

    .favicon-preview-sizes {
        display: flex;
        gap: 20px;
        align-items: flex-end;
    }

    .favicon-preview-sizes .size-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .favicon-preview-sizes .size-item img {
        object-fit: contain;
        background: white;
        border-radius: 4px;
        border: 1px solid var(--border);
        padding: 2px;
    }

    .favicon-preview-sizes .size-label {
        font-size: 11px;
        color: var(--secondary);
        font-weight: 600;
    }

    .favicon-status {
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .favicon-status.custom { color: var(--success, #16a34a); }
    .favicon-status.default { color: var(--secondary); }

    .favicon-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .favicon-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .favicon-upload-label:hover { opacity: 0.9; }

    .favicon-delete-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        color: var(--danger, #dc2626);
        border: 2px solid var(--danger, #dc2626);
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .favicon-delete-btn:hover {
        background: var(--danger, #dc2626);
        color: white;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-cog"></i> {{ __('messages.site_settings') }}</h1>
        <p>{{ __('messages.manage_site_configuration') }}</p>
    </div>
</div>

<div class="card" style="overflow: visible;">
    <!-- Tabs -->
    <div class="settings-tabs">
        <button class="settings-tab {{ request('tab', 'images') === 'images' ? 'active' : '' }}" onclick="switchTab('images')" type="button">
            <i class="fas fa-image"></i> {{ __('messages.image_settings') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'site-icon' ? 'active' : '' }}" onclick="switchTab('site-icon')" type="button">
            <i class="fas fa-globe"></i> {{ __('messages.site_icon') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'site-logo' ? 'active' : '' }}" onclick="switchTab('site-logo')" type="button">
            <i class="fas fa-pen-nib"></i> {{ __('messages.site_logo_tab') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'password' || session('tab') === 'password' ? 'active' : '' }}" onclick="switchTab('password')" type="button">
            <i class="fas fa-lock"></i> {{ __('messages.change_password') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'privacy-policy' ? 'active' : '' }}" onclick="switchTab('privacy-policy')" type="button">
            <i class="fas fa-shield-alt"></i> {{ __('messages.privacy_policy') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'refund-policy' ? 'active' : '' }}" onclick="switchTab('refund-policy')" type="button">
            <i class="fas fa-undo-alt"></i> {{ __('messages.refund_policy_tab') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'social-links' ? 'active' : '' }}" onclick="switchTab('social-links')" type="button">
            <i class="fas fa-share-alt"></i> {{ __('messages.social_links') }}
        </button>
    </div>

    <!-- Image Settings Tab -->
    <div class="tab-content {{ request('tab', 'images') === 'images' && session('tab') !== 'password' ? 'active' : '' }}" id="tab-images">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-info-circle"></i>
                <p>{{ __('messages.image_settings_description') }}</p>
            </div>

            <form action="{{ route('admin.site-settings.update-images') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="settings-form-grid">
                    <!-- Max Image Size -->
                    <div class="setting-item">
                        <label for="max_image_size_kb" class="form-label">
                            {{ __('messages.max_image_size') }}
                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_image_size_kb"
                                   name="max_image_size_kb"
                                   class="form-control @error('max_image_size_kb') is-invalid @enderror"
                                   value="{{ old('max_image_size_kb', $imageSettings['max_image_size_kb']) }}"
                                   min="256" max="20480" step="256">
                            <span class="input-suffix">KB</span>
                        </div>
                        <span class="form-hint">
                            {{ __('messages.current_value') }}: {{ round($imageSettings['max_image_size_kb'] / 1024, 1) }} MB
                            ({{ __('messages.range') }}: 256 KB - 20 MB)
                        </span>
                        @error('max_image_size_kb')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Allowed Formats -->
                    <div class="setting-item">
                        <label for="allowed_image_formats" class="form-label">
                            {{ __('messages.allowed_formats') }}
                        </label>
                        <input type="text"
                               id="allowed_image_formats"
                               name="allowed_image_formats"
                               class="form-control @error('allowed_image_formats') is-invalid @enderror"
                               value="{{ old('allowed_image_formats', $imageSettings['allowed_image_formats']) }}"
                               placeholder="jpg,jpeg,png,webp">
                        <span class="form-hint">{{ __('messages.comma_separated_formats') }}</span>
                        @error('allowed_image_formats')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Max Additional Images -->
                    <div class="setting-item">
                        <label for="max_additional_images" class="form-label">
                            {{ __('messages.max_additional_images_setting') }}
                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_additional_images"
                                   name="max_additional_images"
                                   class="form-control @error('max_additional_images') is-invalid @enderror"
                                   value="{{ old('max_additional_images', $imageSettings['max_additional_images']) }}"
                                   min="1" max="50">
                            <span class="input-suffix">{{ __('messages.files') }}</span>
                        </div>
                        @error('max_additional_images')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image Quality -->
                    <div class="setting-item">
                        <label for="image_quality" class="form-label">
                            {{ __('messages.image_quality') }}
                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="image_quality"
                                   name="image_quality"
                                   class="form-control @error('image_quality') is-invalid @enderror"
                                   value="{{ old('image_quality', $imageSettings['image_quality']) }}"
                                   min="10" max="100">
                            <span class="input-suffix">%</span>
                        </div>
                        <span class="form-hint">{{ __('messages.quality_hint') }}</span>
                        @error('image_quality')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Max Width -->
                    <div class="setting-item">
                        <label for="max_image_width" class="form-label">
                            {{ __('messages.max_image_width') }}
                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_image_width"
                                   name="max_image_width"
                                   class="form-control @error('max_image_width') is-invalid @enderror"
                                   value="{{ old('max_image_width', $imageSettings['max_image_width']) }}"
                                   min="320" max="7680">
                            <span class="input-suffix">px</span>
                        </div>
                        @error('max_image_width')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Max Height -->
                    <div class="setting-item">
                        <label for="max_image_height" class="form-label">
                            {{ __('messages.max_image_height') }}
                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_image_height"
                                   name="max_image_height"
                                   class="form-control @error('max_image_height') is-invalid @enderror"
                                   value="{{ old('max_image_height', $imageSettings['max_image_height']) }}"
                                   min="320" max="4320">
                            <span class="input-suffix">px</span>
                        </div>
                        @error('max_image_height')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Convert to WebP Toggle -->
                <div style="margin-top: 20px;">
                    <div class="toggle-row">
                        <div>
                            <strong style="font-size: 14px;">{{ __('messages.convert_to_webp') }}</strong>
                            <p style="font-size: 12px; color: var(--secondary); margin-top: 2px;">{{ __('messages.webp_description') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="convert_to_webp" value="0">
                            <input type="checkbox" name="convert_to_webp" value="1" {{ old('convert_to_webp', $imageSettings['convert_to_webp']) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.save_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Site Icon Tab -->
    <div class="tab-content {{ request('tab') === 'site-icon' ? 'active' : '' }}" id="tab-site-icon">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-info-circle"></i>
                <p>{{ __('messages.site_icon_description') }}</p>
            </div>

            <!-- Current Favicon Preview -->
            <div class="favicon-preview-container {{ $hasFavicon ? 'has-favicon' : '' }}">
                <div class="favicon-preview-sizes">
                    <div class="size-item">
                        <img src="{{ $faviconUrl }}" alt="Favicon 16x16" width="16" height="16">
                        <span class="size-label">16×16</span>
                    </div>
                    <div class="size-item">
                        <img src="{{ $faviconUrl }}" alt="Favicon 32x32" width="32" height="32">
                        <span class="size-label">32×32</span>
                    </div>
                    <div class="size-item">
                        <img src="{{ $faviconUrl }}" alt="Favicon 48x48" width="48" height="48">
                        <span class="size-label">48×48</span>
                    </div>
                    <div class="size-item">
                        <img src="{{ $faviconUrl }}" alt="Favicon 64x64" width="64" height="64">
                        <span class="size-label">64×64</span>
                    </div>
                </div>

                @if($hasFavicon)
                    <span class="favicon-status custom">
                        <i class="fas fa-check-circle"></i> {{ __('messages.favicon_custom_active') }}
                    </span>
                @else
                    <span class="favicon-status default">
                        <i class="fas fa-info-circle"></i> {{ __('messages.favicon_using_default') }}
                    </span>
                @endif
            </div>

            <!-- Upload Form -->
            <form action="{{ route('admin.site-settings.update-favicon') }}" method="POST" enctype="multipart/form-data" id="favicon-upload-form">
                @csrf
                @method('PUT')

                <div class="favicon-actions">
                    <label class="favicon-upload-label" for="favicon-input">
                        <i class="fas fa-upload"></i>
                        {{ $hasFavicon ? __('messages.favicon_change') : __('messages.favicon_upload') }}
                    </label>
                    <input type="file"
                           id="favicon-input"
                           name="favicon"
                           accept=".jpg,.jpeg,.png,.webp,.ico"
                           style="display: none;"
                           onchange="previewFavicon(this)">

                    <span style="font-size: 13px; color: var(--secondary);">
                        {{ __('messages.favicon_allowed_formats') }}
                    </span>
                </div>

                @error('favicon')
                    <div style="margin-top: 12px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; color: #dc2626; font-size: 13px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

                <!-- Confirmation area (shown after file selection) -->
                <div id="favicon-confirm-area" style="display: none; margin-top: 16px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <img id="favicon-new-preview" src="" alt="New favicon preview" width="48" height="48" style="object-fit: contain; border-radius: 6px; border: 1px solid var(--border);">
                        <div>
                            <span id="favicon-filename" style="font-weight: 600; font-size: 14px; color: var(--dark);"></span>
                            <span id="favicon-filesize" style="display: block; font-size: 12px; color: var(--secondary);"></span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-family: inherit;">
                            <i class="fas fa-save"></i> {{ __('messages.favicon_save') }}
                        </button>
                        <button type="button" style="padding: 8px 20px; font-size: 13px; background: #f1f5f9; color: var(--secondary); border: 1px solid var(--border); border-radius: 8px; cursor: pointer; font-family: inherit;" onclick="cancelFaviconUpload()">
                            {{ __('messages.cancel') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form (only visible when custom favicon exists) -->
            @if($hasFavicon)
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <form action="{{ route('admin.site-settings.delete-favicon') }}" method="POST"
                          onsubmit="return confirm('{{ __('messages.favicon_delete_confirm') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="favicon-delete-btn">
                            <i class="fas fa-trash"></i> {{ __('messages.favicon_delete') }}
                        </button>
                        <span style="font-size: 13px; color: var(--secondary); margin-inline-start: 12px;">
                            {{ __('messages.favicon_delete_hint') }}
                        </span>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Site Logo Tab -->
    <div class="tab-content {{ request('tab') === 'site-logo' ? 'active' : '' }}" id="tab-site-logo">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-info-circle"></i>
                <p>{{ __('messages.site_logo_description') }}</p>
            </div>

            <!-- Current Logo Preview -->
            <div class="favicon-preview-container {{ $hasLogo ? 'has-favicon' : '' }}">
                <div style="display: flex; align-items: center; justify-content: center; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed var(--border); min-height: 80px;">
                    <img src="{{ $logoUrl }}" alt="Current Logo" style="max-height: 60px; max-width: 300px; object-fit: contain;">
                </div>

                @if($hasLogo)
                    <span class="favicon-status custom" style="margin-top: 12px;">
                        <i class="fas fa-check-circle"></i> {{ __('messages.logo_custom_active') }}
                    </span>
                @else
                    <span class="favicon-status default" style="margin-top: 12px;">
                        <i class="fas fa-info-circle"></i> {{ __('messages.logo_using_default') }}
                    </span>
                @endif
            </div>

            <!-- Upload Form -->
            <form action="{{ route('admin.site-settings.update-logo') }}" method="POST" enctype="multipart/form-data" id="logo-upload-form">
                @csrf
                @method('PUT')

                <div class="favicon-actions">
                    <label class="favicon-upload-label" for="logo-input">
                        <i class="fas fa-upload"></i>
                        {{ $hasLogo ? __('messages.logo_change') : __('messages.logo_upload') }}
                    </label>
                    <input type="file"
                           id="logo-input"
                           name="logo"
                           accept=".jpg,.jpeg,.png,.webp,.svg"
                           style="display: none;"
                           onchange="previewLogo(this)">

                    <span style="font-size: 13px; color: var(--secondary);">
                        {{ __('messages.logo_allowed_formats') }}
                    </span>
                </div>

                @error('logo')
                    <div style="margin-top: 12px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; color: #dc2626; font-size: 13px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

                <!-- Confirmation area (shown after file selection) -->
                <div id="logo-confirm-area" style="display: none; margin-top: 16px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <img id="logo-new-preview" src="" alt="New logo preview" style="max-height: 50px; max-width: 200px; object-fit: contain; border-radius: 6px; border: 1px solid var(--border);">
                        <div>
                            <span id="logo-filename" style="font-weight: 600; font-size: 14px; color: var(--dark);"></span>
                            <span id="logo-filesize" style="display: block; font-size: 12px; color: var(--secondary);"></span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-family: inherit;">
                            <i class="fas fa-save"></i> {{ __('messages.logo_save') }}
                        </button>
                        <button type="button" style="padding: 8px 20px; font-size: 13px; background: #f1f5f9; color: var(--secondary); border: 1px solid var(--border); border-radius: 8px; cursor: pointer; font-family: inherit;" onclick="cancelLogoUpload()">
                            {{ __('messages.cancel') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form (only visible when custom logo exists) -->
            @if($hasLogo)
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <form action="{{ route('admin.site-settings.delete-logo') }}" method="POST"
                          onsubmit="return confirm('{{ __('messages.logo_delete_confirm') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="favicon-delete-btn">
                            <i class="fas fa-trash"></i> {{ __('messages.logo_delete') }}
                        </button>
                        <span style="font-size: 13px; color: var(--secondary); margin-inline-start: 12px;">
                            {{ __('messages.logo_delete_hint') }}
                        </span>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Change Password Tab -->
    <div class="tab-content {{ request('tab') === 'password' || session('tab') === 'password' ? 'active' : '' }}" id="tab-password">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-shield-alt"></i>
                <p>{{ __('messages.password_change_description') }}</p>
            </div>

            <form action="{{ route('admin.site-settings.change-password') }}" method="POST" style="max-width: 500px;">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="current_password" class="form-label">
                        {{ __('messages.current_password') }}
                        <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               required
                               autocomplete="off">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('current_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="new_password" class="form-label">
                        {{ __('messages.new_password') }}
                        <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               id="new_password"
                               name="new_password"
                               class="form-control @error('new_password') is-invalid @enderror"
                               required
                               autocomplete="off">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="new_password_confirmation" class="form-label">
                        {{ __('messages.confirm_new_password') }}
                        <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               id="new_password_confirmation"
                               name="new_password_confirmation"
                               class="form-control"
                               required
                               autocomplete="off">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="password-requirements">
                    <h4><i class="fas fa-info-circle"></i> {{ __('messages.password_requirements') }}</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> {{ __('messages.password_min_8') }}</li>
                        <li><i class="fas fa-check-circle"></i> {{ __('messages.password_mixed_case') }}</li>
                        <li><i class="fas fa-check-circle"></i> {{ __('messages.password_numbers') }}</li>
                    </ul>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> {{ __('messages.update_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Privacy Policy Tab -->
    <div class="tab-content {{ request('tab') === 'privacy-policy' ? 'active' : '' }}" id="tab-privacy-policy">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-shield-alt"></i>
                <p>{{ __('messages.privacy_policy_admin_description') }}</p>
            </div>

            <form action="{{ route('admin.site-settings.update-privacy-policy') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="policy-language-tabs">
                    <button type="button" class="policy-lang-tab active" onclick="switchPolicyLang(this, 'privacy', 'en')">English</button>
                    <button type="button" class="policy-lang-tab" onclick="switchPolicyLang(this, 'privacy', 'ar')">العربية</button>
                    <button type="button" class="policy-lang-tab" onclick="switchPolicyLang(this, 'privacy', 'he')">עברית</button>
                </div>

                <div class="policy-lang-content active" id="privacy-lang-en">
                    <label for="privacy_policy_en" class="form-label">{{ __('messages.policy_content_english') }}</label>
                    <textarea id="privacy_policy_en" name="privacy_policy_en" class="form-control policy-textarea" rows="15" dir="ltr" placeholder="{{ __('messages.policy_html_placeholder') }}">{{ old('privacy_policy_en', $privacyPolicy['en']) }}</textarea>
                </div>

                <div class="policy-lang-content" id="privacy-lang-ar">
                    <label for="privacy_policy_ar" class="form-label">{{ __('messages.policy_content_arabic') }}</label>
                    <textarea id="privacy_policy_ar" name="privacy_policy_ar" class="form-control policy-textarea" rows="15" dir="rtl" placeholder="{{ __('messages.policy_html_placeholder') }}">{{ old('privacy_policy_ar', $privacyPolicy['ar']) }}</textarea>
                </div>

                <div class="policy-lang-content" id="privacy-lang-he">
                    <label for="privacy_policy_he" class="form-label">{{ __('messages.policy_content_hebrew') }}</label>
                    <textarea id="privacy_policy_he" name="privacy_policy_he" class="form-control policy-textarea" rows="15" dir="rtl" placeholder="{{ __('messages.policy_html_placeholder') }}">{{ old('privacy_policy_he', $privacyPolicy['he']) }}</textarea>
                </div>

                <div class="policy-hint">
                    <i class="fas fa-code"></i>
                    <span>{{ __('messages.policy_html_hint') }}</span>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.save_privacy_policy') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Refund Policy Tab -->
    <div class="tab-content {{ request('tab') === 'refund-policy' ? 'active' : '' }}" id="tab-refund-policy">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-undo-alt"></i>
                <p>{{ __('messages.refund_policy_admin_description') }}</p>
            </div>

            <form action="{{ route('admin.site-settings.update-refund-policy') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="policy-language-tabs">
                    <button type="button" class="policy-lang-tab active" onclick="switchPolicyLang(this, 'refund', 'en')">English</button>
                    <button type="button" class="policy-lang-tab" onclick="switchPolicyLang(this, 'refund', 'ar')">العربية</button>
                    <button type="button" class="policy-lang-tab" onclick="switchPolicyLang(this, 'refund', 'he')">עברית</button>
                </div>

                <div class="policy-lang-content active" id="refund-lang-en">
                    <label for="refund_policy_en" class="form-label">{{ __('messages.policy_content_english') }}</label>
                    <textarea id="refund_policy_en" name="refund_policy_en" class="form-control policy-textarea" rows="15" dir="ltr" placeholder="{{ __('messages.policy_html_placeholder') }}">{{ old('refund_policy_en', $refundPolicy['en']) }}</textarea>
                </div>

                <div class="policy-lang-content" id="refund-lang-ar">
                    <label for="refund_policy_ar" class="form-label">{{ __('messages.policy_content_arabic') }}</label>
                    <textarea id="refund_policy_ar" name="refund_policy_ar" class="form-control policy-textarea" rows="15" dir="rtl" placeholder="{{ __('messages.policy_html_placeholder') }}">{{ old('refund_policy_ar', $refundPolicy['ar']) }}</textarea>
                </div>

                <div class="policy-lang-content" id="refund-lang-he">
                    <label for="refund_policy_he" class="form-label">{{ __('messages.policy_content_hebrew') }}</label>
                    <textarea id="refund_policy_he" name="refund_policy_he" class="form-control policy-textarea" rows="15" dir="rtl" placeholder="{{ __('messages.policy_html_placeholder') }}">{{ old('refund_policy_he', $refundPolicy['he']) }}</textarea>
                </div>

                <div class="policy-hint">
                    <i class="fas fa-code"></i>
                    <span>{{ __('messages.policy_html_hint') }}</span>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.save_refund_policy') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @php
    $platformColors = [
        'facebook'  => '#1877F2', 'instagram' => '#E1306C', 'whatsapp'  => '#25D366',
        'twitter'   => '#1DA1F2', 'youtube'   => '#FF0000', 'tiktok'    => '#010101',
        'linkedin'  => '#0A66C2', 'snapchat'  => '#FFFC00', 'telegram'  => '#0088CC',
        'pinterest' => '#E60023', 'x'         => '#000000',
    ];
    @endphp

    <!-- Social Links Tab -->
    <div class="tab-content {{ request('tab') === 'social-links' ? 'active' : '' }}" id="tab-social-links">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-share-alt"></i>
                <p>{{ __('messages.social_links_admin_description') }}</p>
            </div>

            <form action="{{ route('admin.site-settings.update-social-links') }}" method="POST" id="social-links-form">
                @csrf
                @method('PUT')

                <div class="social-links-list" id="social-links-list">
                    @foreach($socialLinks as $index => $link)
                    <div class="social-link-row" data-index="{{ $index }}">
                        <input type="hidden" name="platform[{{ $index }}]" value="{{ $link['platform'] }}">
                        <input type="hidden" name="label[{{ $index }}]" value="{{ $link['label'] }}">
                        <input type="hidden" name="icon[{{ $index }}]" value="{{ $link['icon'] }}">
                        <input type="hidden" name="visible[{{ $index }}]" class="visibility-input" value="{{ !empty($link['visible']) ? '1' : '0' }}">
                        <div class="social-link-icon" style="background: {{ $platformColors[$link['platform']] ?? '#6b7280' }};">
                            <i class="{{ $link['icon'] }}"></i>
                        </div>
                        <span class="social-link-name">{{ $link['label'] }}</span>
                        <input type="text" name="url[{{ $index }}]" class="form-control social-link-url" value="{{ $link['url'] }}" placeholder="https://...">
                        <button type="button" class="social-toggle-btn {{ !empty($link['visible']) ? 'active' : '' }}" onclick="toggleSocialVisible(this)" title="{{ !empty($link['visible']) ? __('messages.hide') : __('messages.show') }}">
                            <i class="fas fa-{{ !empty($link['visible']) ? 'eye' : 'eye-slash' }}"></i>
                        </button>
                        <button type="button" class="social-delete-btn" onclick="removeSocialRow(this)" title="{{ __('messages.delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="add-social-btn" onclick="openPlatformPicker()">
                    <i class="fas fa-plus-circle"></i> {{ __('messages.add_social_platform') }}
                </button>

                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.save_social_links') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Platform Picker Modal -->
<div class="platform-picker-modal" id="platform-picker-modal">
    <div class="platform-picker-content">
        <div class="platform-picker-title">
            <span><i class="fas fa-share-alt"></i> {{ __('messages.choose_platform') }}</span>
            <button type="button" onclick="closePlatformPicker()" style="background:none;border:none;cursor:pointer;font-size:20px;color:var(--secondary);"><i class="fas fa-times"></i></button>
        </div>
        <div class="platform-grid" id="platform-grid"></div>
        <div class="custom-platform-form">
            <p style="font-size:13px;font-weight:600;color:var(--dark);margin-bottom:12px;">{{ __('messages.or_custom_platform') }}</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label class="form-label" style="font-size:13px;">{{ __('messages.platform_name') }}</label>
                    <input type="text" id="custom-platform-name" class="form-control" placeholder="e.g. Pinterest">
                </div>
                <div>
                    <label class="form-label" style="font-size:13px;">{{ __('messages.platform_icon') }}</label>
                    <input type="text" id="custom-platform-icon" class="form-control" placeholder="fab fa-pinterest">
                </div>
            </div>
            <button type="button" class="btn btn-primary" style="margin-top:12px;font-size:13px;" onclick="addCustomPlatform()">
                <i class="fas fa-plus"></i> {{ __('messages.add_custom') }}
            </button>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.settings-tab').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');

    // Update URL without reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.replaceState({}, '', url);
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function previewFavicon(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const confirmArea = document.getElementById('favicon-confirm-area');
        const preview = document.getElementById('favicon-new-preview');
        const filename = document.getElementById('favicon-filename');
        const filesize = document.getElementById('favicon-filesize');

        filename.textContent = file.name;
        filesize.textContent = (file.size / 1024).toFixed(1) + ' KB';

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
        }

        confirmArea.style.display = 'block';
    }
}

function cancelFaviconUpload() {
    document.getElementById('favicon-input').value = '';
    document.getElementById('favicon-confirm-area').style.display = 'none';
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const confirmArea = document.getElementById('logo-confirm-area');
        const preview = document.getElementById('logo-new-preview');
        const filename = document.getElementById('logo-filename');
        const filesize = document.getElementById('logo-filesize');

        filename.textContent = file.name;
        filesize.textContent = (file.size / 1024).toFixed(1) + ' KB';

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
        }

        confirmArea.style.display = 'block';
    }
}

function cancelLogoUpload() {
    document.getElementById('logo-input').value = '';
    document.getElementById('logo-confirm-area').style.display = 'none';
}

let socialLinkCounter = {{ count($socialLinks) }};
const SOCIAL_PLATFORMS = [
    { platform: 'facebook',  label: 'Facebook',    icon: 'fab fa-facebook-f',  color: '#1877F2' },
    { platform: 'instagram', label: 'Instagram',   icon: 'fab fa-instagram',   color: '#E1306C' },
    { platform: 'whatsapp',  label: 'WhatsApp',    icon: 'fab fa-whatsapp',    color: '#25D366' },
    { platform: 'twitter',   label: 'Twitter / X', icon: 'fab fa-twitter',     color: '#1DA1F2' },
    { platform: 'youtube',   label: 'YouTube',     icon: 'fab fa-youtube',     color: '#FF0000' },
    { platform: 'tiktok',    label: 'TikTok',      icon: 'fab fa-tiktok',      color: '#010101' },
    { platform: 'linkedin',  label: 'LinkedIn',    icon: 'fab fa-linkedin-in', color: '#0A66C2' },
    { platform: 'snapchat',  label: 'Snapchat',    icon: 'fab fa-snapchat',    color: '#FFFC00' },
    { platform: 'telegram',  label: 'Telegram',    icon: 'fab fa-telegram',    color: '#0088CC' },
    { platform: 'pinterest', label: 'Pinterest',   icon: 'fab fa-pinterest',   color: '#E60023' },
];

function openPlatformPicker() {
    const modal = document.getElementById('platform-picker-modal');
    const grid  = document.getElementById('platform-grid');
    grid.innerHTML = '';
    const existing = Array.from(document.querySelectorAll('[name^="platform["]')).map(i => i.value);
    SOCIAL_PLATFORMS.forEach(p => {
        const added = existing.includes(p.platform);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'platform-option' + (added ? ' already-added' : '');
        if (!added) btn.onclick = () => { addPlatformRow(p); closePlatformPicker(); };
        btn.innerHTML = `<div class="platform-option-icon" style="background:${p.color}"><i class="${p.icon}"></i></div><span class="platform-option-name">${p.label}</span>${added ? '<span style="font-size:10px;color:#9ca3af">✓ Added</span>' : ''}`;
        grid.appendChild(btn);
    });
    modal.classList.add('show');
}

function closePlatformPicker() {
    document.getElementById('platform-picker-modal').classList.remove('show');
}

function addPlatformRow(p) {
    const idx  = socialLinkCounter++;
    const list = document.getElementById('social-links-list');
    const row  = document.createElement('div');
    row.className = 'social-link-row';
    row.dataset.index = idx;
    row.innerHTML = `
        <input type="hidden" name="platform[${idx}]" value="${p.platform}">
        <input type="hidden" name="label[${idx}]" value="${p.label}">
        <input type="hidden" name="icon[${idx}]" value="${p.icon}">
        <input type="hidden" name="visible[${idx}]" class="visibility-input" value="1">
        <div class="social-link-icon" style="background:${p.color}"><i class="${p.icon}"></i></div>
        <span class="social-link-name">${p.label}</span>
        <input type="text" name="url[${idx}]" class="form-control social-link-url" value="" placeholder="https://...">
        <button type="button" class="social-toggle-btn active" onclick="toggleSocialVisible(this)"><i class="fas fa-eye"></i></button>
        <button type="button" class="social-delete-btn" onclick="removeSocialRow(this)"><i class="fas fa-trash"></i></button>
    `;
    list.appendChild(row);
}

function addCustomPlatform() {
    const name = document.getElementById('custom-platform-name').value.trim();
    const icon = document.getElementById('custom-platform-icon').value.trim();
    if (!name) { alert('{{ __('messages.platform_name_required') }}'); return; }
    addPlatformRow({ platform: name.toLowerCase().replace(/\s+/g,'_'), label: name, icon: icon || 'fas fa-link', color: '#6b7280' });
    closePlatformPicker();
}

function toggleSocialVisible(btn) {
    const input = btn.closest('.social-link-row').querySelector('.visibility-input');
    const nowVisible = input.value !== '1';
    input.value = nowVisible ? '1' : '0';
    btn.classList.toggle('active', nowVisible);
    btn.querySelector('i').className = nowVisible ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function removeSocialRow(btn) {
    if (confirm('{{ __('messages.confirm_delete_social') }}')) {
        btn.closest('.social-link-row').remove();
    }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePlatformPicker(); });

function switchPolicyLang(btn, policy, lang) {
    // Update language tab buttons within this policy
    const tabsContainer = btn.parentElement;
    tabsContainer.querySelectorAll('.policy-lang-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    // Update language content panels
    const parent = tabsContainer.parentElement;
    parent.querySelectorAll('.policy-lang-content').forEach(c => c.classList.remove('active'));
    document.getElementById(policy + '-lang-' + lang).classList.add('active');
}

// Restore active tab from URL or session
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || '{{ session("tab", "images") }}';
    if (tab && tab !== 'images') {
        const tabNames = ['images', 'site-icon', 'site-logo', 'password', 'privacy-policy', 'refund-policy', 'social-links'];
        const idx = tabNames.indexOf(tab);
        if (idx > 0) {
            const tabBtn = document.querySelector(`.settings-tab:nth-child(${idx + 1})`);
            if (tabBtn) tabBtn.click();
        }
    }
});
</script>
@endsection
