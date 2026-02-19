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

    @media (max-width: 768px) {
        .settings-tabs {
            flex-wrap: wrap;
        }

        .settings-tab {
            padding: 12px 16px;
            font-size: 13px;
        }

        .policy-language-tabs {
            width: 100%;
        }

        .policy-lang-tab {
            flex: 1;
            text-align: center;
            padding: 10px 12px;
        }
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
        <button class="settings-tab {{ request('tab') === 'password' || session('tab') === 'password' ? 'active' : '' }}" onclick="switchTab('password')" type="button">
            <i class="fas fa-lock"></i> {{ __('messages.change_password') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'privacy-policy' ? 'active' : '' }}" onclick="switchTab('privacy-policy')" type="button">
            <i class="fas fa-shield-alt"></i> {{ __('messages.privacy_policy') }}
        </button>
        <button class="settings-tab {{ request('tab') === 'refund-policy' ? 'active' : '' }}" onclick="switchTab('refund-policy')" type="button">
            <i class="fas fa-undo-alt"></i> {{ __('messages.refund_policy_tab') }}
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
        const tabNames = ['images', 'password', 'privacy-policy', 'refund-policy'];
        const idx = tabNames.indexOf(tab);
        if (idx > 0) {
            const tabBtn = document.querySelector(`.settings-tab:nth-child(${idx + 1})`);
            if (tabBtn) tabBtn.click();
        }
    }
});
</script>
@endsection
