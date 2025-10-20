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
            
            <!-- Session Success Message -->
            @if(session('success'))
            <div style="padding: 1rem; margin-top: 1rem; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;">
                {{ session('success') }}
            </div>
            @endif
            
            <!-- Session Error Message -->
            @if(session('error'))
            <div style="padding: 1rem; margin-top: 1rem; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">
                {{ session('error') }}
            </div>
            @endif
            
            <!-- Validation Errors -->
            @if($errors->any())
            <div style="padding: 1rem; margin-top: 1rem; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- Success Message -->
            <div id="success-message" style="display: none; padding: 1rem; margin-top: 1rem; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;"></div>
            
            <!-- Error Message -->
            <div id="error-message" style="display: none; padding: 1rem; margin-top: 1rem; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;"></div>
            
            <form id="contact-form" action="{{ route('contact.store') }}" method="POST" style="margin-top: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_name') }} <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <span id="name-error" class="error-text" style="color: red; font-size: 0.875rem; display: none;"></span>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_email') }} <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <span id="email-error" class="error-text" style="color: red; font-size: 0.875rem; display: none;"></span>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.subject') }} <span style="color: red;">*</span></label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <span id="subject-error" class="error-text" style="color: red; font-size: 0.875rem; display: none;"></span>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">{{ __t('messages.your_message') }} <span style="color: red;">*</span></label>
                    <textarea name="message" id="message" rows="5" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">{{ old('message') }}</textarea>
                    <span id="message-error" class="error-text" style="color: red; font-size: 0.875rem; display: none;"></span>
                </div>
                <button type="submit" id="submit-btn" style="background: #4CAF50; color: white; padding: 0.75rem 2rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.error-text').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = '{{ __t("messages.sending") ?? "Sending..." }}';

        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            subject: formData.get('subject'),
            message: formData.get('message')
        };

        try {
            const response = await fetch('{{ route("api.contact.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Accept-Language': '{{ app()->getLocale() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                // Success
                successMessage.textContent = result.message;
                successMessage.style.display = 'block';
                form.reset();
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // Validation errors
                if (result.error && typeof result.error === 'object') {
                    Object.keys(result.error).forEach(key => {
                        const errorEl = document.getElementById(`${key}-error`);
                        if (errorEl && result.error[key][0]) {
                            errorEl.textContent = result.error[key][0];
                            errorEl.style.display = 'block';
                        }
                    });
                } else {
                    errorMessage.textContent = result.message || '{{ __t("messages.error_occurred") ?? "An error occurred" }}';
                    errorMessage.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorMessage.textContent = '{{ __t("messages.error_occurred") ?? "An error occurred. Please try again." }}';
            errorMessage.style.display = 'block';
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = '{{ __t("messages.send_message") }}';
        }
    });
});
</script>
@endpush

@endsection
