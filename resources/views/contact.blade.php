@extends('layouts.app')

@section('title', __t('messages.contact_us') . ' - IT Center')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>{{ __t('messages.contact_us') }}</h1>
        <p>{{ __t('messages.get_in_touch') }}</p>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 2rem;">
        <div>
            <h2>{{ __t('messages.send_us_message') }}</h2>
            <form style="margin-top: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_name') }}</label>
                    <input type="text" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_email') }}</label>
                    <input type="email" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_phone') }}</label>
                    <input type="tel" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_message') }}</label>
                    <textarea rows="5" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
                <button type="submit" style="background: #4CAF50; color: white; padding: 0.75rem 2rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">
                    {{ __t('messages.send_message') }}
                </button>
            </form>
        </div>

        <div>
            <h2>{{ __t('messages.contact_information') }}</h2>
            <div style="margin-top: 1.5rem;">
                <div style="margin-bottom: 1.5rem; padding: 1rem; border-{{ is_rtl() ? 'right' : 'left' }}: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>{{ __t('messages.address') }}</h3>
                    <p>123 Tech Street<br>Silicon Valley, CA 94025<br>United States</p>
                </div>
                <div style="margin-bottom: 1.5rem; padding: 1rem; border-{{ is_rtl() ? 'right' : 'left' }}: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>{{ __t('messages.phone') }}</h3>
                    <p>+1 (555) 123-4567</p>
                </div>
                <div style="margin-bottom: 1.5rem; padding: 1rem; border-{{ is_rtl() ? 'right' : 'left' }}: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>{{ __t('messages.email') }}</h3>
                    <p>info@itcenter.com<br>support@itcenter.com</p>
                </div>
                <div style="padding: 1rem; border-{{ is_rtl() ? 'right' : 'left' }}: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>{{ __t('messages.business_hours') }}</h3>
                    <p>{{ __t('messages.monday_friday') }}<br>
                    {{ __t('messages.saturday_hours') }}<br>
                    {{ __t('messages.sunday_closed') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
