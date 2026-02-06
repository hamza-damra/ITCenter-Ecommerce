@extends('admin.layout')

@section('title', __('messages.Backup Settings'))

@section('content')
<div class="page-header" dir="{{ in_array(app()->getLocale(), ['ar','he']) ? 'rtl' : 'ltr' }}">
    <div class="page-header-content">
        <h1>
            @if(in_array(app()->getLocale(), ['ar','he']))
                {{ __('messages.Backup Settings') }} <i class="fas fa-cog"></i>
            @else
                <i class="fas fa-cog"></i> {{ __('messages.Backup Settings') }}
            @endif
        </h1>
        <p>{{ __('messages.Configure automatic backup retention and cleanup policies') }}</p>
    </div>
    
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <ul style="margin-inline-start: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2><i class="fas fa-sliders-h"></i> {{ __('messages.Settings') }}</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.backup.settings.update') }}" class="form-layout">
                @csrf

                <div class="form-section">
                    <div class="section-title"><i class="fas fa-magic"></i> {{ __('messages.Automatic Cleanup') }}</div>

                    <label class="checkbox-group" for="auto_cleanup_enabled">
                        <input id="auto_cleanup_enabled" type="checkbox" name="auto_cleanup_enabled" value="1" {{ ($settings['auto_cleanup_enabled'] ?? true) ? 'checked' : '' }}>
                        <span>{{ __('messages.Enable Automatic Cleanup') }}</span>
                    </label>
                    <div class="form-text">{{ __('messages.When enabled, expired backups will be automatically deleted daily') }}</div>
                </div>

                <div class="form-section">
                    <div class="section-title"><i class="fas fa-clock"></i> {{ __('messages.Auto Backup Schedule') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="auto_backup_interval" class="form-label">
                                {{ __('messages.Auto Backup Interval') }} <span class="required">*</span>
                            </label>
                            <select name="auto_backup_interval" id="auto_backup_interval" class="form-control" required>
                                <option value="disabled" {{ ($settings['auto_backup_interval'] ?? 'daily') == 'disabled' ? 'selected' : '' }}>{{ __('messages.Disabled') }}</option>
                                <option value="5_minutes" {{ ($settings['auto_backup_interval'] ?? 'daily') == '5_minutes' ? 'selected' : '' }}>{{ __('messages.Every 5 Minutes') }}</option>
                                <option value="15_minutes" {{ ($settings['auto_backup_interval'] ?? 'daily') == '15_minutes' ? 'selected' : '' }}>{{ __('messages.Every 15 Minutes') }}</option>
                                <option value="30_minutes" {{ ($settings['auto_backup_interval'] ?? 'daily') == '30_minutes' ? 'selected' : '' }}>{{ __('messages.Every 30 Minutes') }}</option>
                                <option value="hourly" {{ ($settings['auto_backup_interval'] ?? 'daily') == 'hourly' ? 'selected' : '' }}>{{ __('messages.Every Hour') }}</option>
                                <option value="6_hours" {{ ($settings['auto_backup_interval'] ?? 'daily') == '6_hours' ? 'selected' : '' }}>{{ __('messages.Every 6 Hours') }}</option>
                                <option value="12_hours" {{ ($settings['auto_backup_interval'] ?? 'daily') == '12_hours' ? 'selected' : '' }}>{{ __('messages.Every 12 Hours') }}</option>
                                <option value="daily" {{ ($settings['auto_backup_interval'] ?? 'daily') == 'daily' ? 'selected' : '' }}>{{ __('messages.Daily') }}</option>
                                <option value="weekly" {{ ($settings['auto_backup_interval'] ?? 'daily') == 'weekly' ? 'selected' : '' }}>{{ __('messages.Weekly') }}</option>
                                <option value="monthly" {{ ($settings['auto_backup_interval'] ?? 'daily') == 'monthly' ? 'selected' : '' }}>{{ __('messages.Monthly') }}</option>
                            </select>
                            @error('auto_backup_interval')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('messages.Set how often automatic backups should be created') }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title"><i class="fas fa-calendar-check"></i> {{ __('messages.Default Retention Policy') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="default_retention_days" class="form-label">
                                {{ __('messages.Default Retention Period') }} <span class="required">*</span>
                            </label>
                            <select name="default_retention_days" id="default_retention_days" class="form-control" required>
                                <option value="1" {{ ($settings['default_retention_days'] ?? 30) == 1 ? 'selected' : '' }}>{{ __('messages.1 Day') }}</option>
                                <option value="7" {{ ($settings['default_retention_days'] ?? 30) == 7 ? 'selected' : '' }}>{{ __('messages.7 Days') }}</option>
                                <option value="14" {{ ($settings['default_retention_days'] ?? 30) == 14 ? 'selected' : '' }}>{{ __('messages.14 Days') }}</option>
                                <option value="30" {{ ($settings['default_retention_days'] ?? 30) == 30 ? 'selected' : '' }}>{{ __('messages.30 Days') }}</option>
                                <option value="60" {{ ($settings['default_retention_days'] ?? 30) == 60 ? 'selected' : '' }}>{{ __('messages.60 Days') }}</option>
                                <option value="90" {{ ($settings['default_retention_days'] ?? 30) == 90 ? 'selected' : '' }}>{{ __('messages.90 Days') }}</option>
                                <option value="180" {{ ($settings['default_retention_days'] ?? 30) == 180 ? 'selected' : '' }}>{{ __('messages.180 Days') }}</option>
                                <option value="365" {{ ($settings['default_retention_days'] ?? 30) == 365 ? 'selected' : '' }}>{{ __('messages.1 Year') }}</option>
                            </select>
                            @error('default_retention_days')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('messages.This applies to automatic backups. Manual backups can have custom expiration.') }}</div>
                        </div>

                        <div class="form-group">
                            <label for="max_backups" class="form-label">{{ __('messages.Maximum Number of Backups') }}</label>
                            <input type="number" name="max_backups" id="max_backups" class="form-control" min="1" max="100" value="{{ $settings['max_backups'] ?? 10 }}">
                            @error('max_backups')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('messages.Maximum backups to keep regardless of expiration date') }}</div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <a href="{{ route('admin.backup.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('messages.Back to Backups') }}</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.Save Settings') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2><i class="fas fa-trash-alt"></i> {{ __('messages.Cleanup Expired Backups') }}</h2>
        </div>
        <div class="card-body">
            <p style="margin-bottom: 15px;">{{ __('messages.Delete all backups that have exceeded their retention period or are invalid') }}</p>
            <form method="POST" action="{{ route('admin.backup.cleanup-expired') }}" onsubmit="return confirm('{{ __('messages.Are you sure you want to delete all expired backups?') }}')">
                @csrf
                <button type="submit" class="btn btn-danger"><i class="fas fa-broom"></i> {{ __('messages.Delete Expired Backups Now') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
