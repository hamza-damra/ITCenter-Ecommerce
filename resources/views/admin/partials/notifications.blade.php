{{-- Admin System Notifications --}}
@php
    $notificationTypes = [
        'success' => [
            'icon' => 'fas fa-check-circle',
            'title' => __('messages.notification_success_title'),
        ],
        'error' => [
            'icon' => 'fas fa-times-circle',
            'title' => __('messages.notification_error_title'),
        ],
        'warning' => [
            'icon' => 'fas fa-exclamation-triangle',
            'title' => __('messages.notification_warning_title'),
        ],
        'info' => [
            'icon' => 'fas fa-info-circle',
            'title' => __('messages.notification_info_title'),
        ],
    ];

    $isRtl = in_array(app()->getLocale(), ['ar', 'he']);
@endphp

<div class="admin-notifications-container" id="adminNotifications">
    @foreach($notificationTypes as $type => $config)
        @if(session($type))
            <div class="admin-notification admin-notification--{{ $type }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}" data-notification role="alert">
                <div class="admin-notification__accent"></div>
                <div class="admin-notification__icon">
                    <i class="{{ $config['icon'] }}"></i>
                </div>
                <div class="admin-notification__body">
                    <div class="admin-notification__title" dir="auto">{{ $config['title'] }}</div>
                    <div class="admin-notification__message" dir="auto">{{ session($type) }}</div>
                </div>
                <div class="admin-notification__actions">
                    <span class="admin-notification__time" dir="auto">{{ __('messages.notification_just_now') }}</span>
                    <button type="button" class="admin-notification__close" onclick="dismissNotification(this)" aria-label="{{ __('messages.close') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    @endforeach
</div>
