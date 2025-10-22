@extends('admin.layout')

@section('title', __('messages.create_new_user'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-user-plus"></i> {{ __('messages.create_new_user') }}</h1>
        <p>{{ __('messages.add_new_user_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_users') }}
        </a>
    </div>
</div>

<!-- Create User Form -->
<div class="card">
    <div class="card-header">
        <h2>{{ __('messages.user_information') }}</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" class="form-layout">
            @csrf

            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.basic_information') }}</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="first_name">
                            {{ __('messages.first_name') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               class="form-control @error('first_name') is-invalid @enderror" 
                               placeholder="{{ __('messages.first_name_placeholder') }}"
                               value="{{ old('first_name') }}"
                               required>
                        @error('first_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="last_name">
                            {{ __('messages.last_name') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               class="form-control @error('last_name') is-invalid @enderror" 
                               placeholder="{{ __('messages.last_name_placeholder') }}"
                               value="{{ old('last_name') }}"
                               required>
                        @error('last_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.contact_information') }}</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="email">
                            {{ __('messages.email') }} <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="{{ __('messages.email_placeholder') }}"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('messages.email_must_be_unique') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">
                            {{ __('messages.phone') }}
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="{{ __('messages.phone_placeholder') }}"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.account_settings') }}</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="role">
                            {{ __('messages.user_role') }} <span class="required">*</span>
                        </label>
                        <select name="role" 
                                id="role" 
                                class="form-control @error('role') is-invalid @enderror"
                                required>
                            <option value="">{{ __('messages.select_role') }}</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>
                                {{ __('messages.customer') }}
                            </option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                {{ __('messages.admin') }}
                            </option>
                        </select>
                        @error('role')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('messages.role_help_text') }}</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">
                            {{ __('messages.password') }} <span class="required">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="{{ __('messages.password_placeholder') }}"
                               required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('messages.password_min_8_chars') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            {{ __('messages.confirm_password') }} <span class="required">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-control" 
                               placeholder="{{ __('messages.confirm_password_placeholder') }}"
                               required>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 24px; border-top: 2px solid var(--border);">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> {{ __('messages.create_user') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Help Section -->
<div class="card" style="margin-top: 24px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #93c5fd;">
    <div class="card-header" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
        <h2 style="color: #1e40af;">
            <i class="fas fa-info-circle"></i> {{ __('messages.important_notes') }}
        </h2>
    </div>
    <div class="card-body">
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 12px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                <i class="fas fa-check-circle" style="color: #3b82f6; margin-top: 4px;"></i>
                <span>{{ __('messages.user_create_note_1') }}</span>
            </li>
            <li style="padding: 12px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                <i class="fas fa-check-circle" style="color: #3b82f6; margin-top: 4px;"></i>
                <span>{{ __('messages.user_create_note_2') }}</span>
            </li>
            <li style="padding: 12px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                <i class="fas fa-check-circle" style="color: #3b82f6; margin-top: 4px;"></i>
                <span>{{ __('messages.user_create_note_3') }}</span>
            </li>
            <li style="padding: 12px 0; display: flex; align-items: start; gap: 12px;">
                <i class="fas fa-check-circle" style="color: #3b82f6; margin-top: 4px;"></i>
                <span>{{ __('messages.user_create_note_4') }}</span>
            </li>
        </ul>
    </div>
</div>
@endsection

