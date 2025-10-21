@extends('layouts.app')

@section('title', __t('messages.contact_us') . ' - IT Center')

@section('content')
<style>
    /* Contact Page Styles */
    .contact-page {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff8e1 100%);
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 0;
        text-align: center;
        color: #fff;
        margin-bottom: 3rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .page-header p {
        font-size: 1.2rem;
        margin: 0;
        opacity: 0.95;
    }
    
    .contact-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }
    
    .contact-form-section,
    .contact-info-section {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .contact-form-section h2,
    .contact-info-section h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 3px solid #4CAF50;
    }
    
    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
    
    .form-group label .required {
        color: #ff4757;
        margin-{{ is_rtl() ? 'right' : 'left' }}: 0.25rem;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.9rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
        box-sizing: border-box;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4CAF50;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 150px;
    }
    
    .error-text {
        color: #ff4757;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }
    
    /* Alert Messages */
    .alert {
        padding: 1rem 1.2rem;
        margin-bottom: 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success {
        background: #d4edda;
        border: 2px solid #c3e6cb;
        color: #155724;
    }
    
    .alert-error {
        background: #f8d7da;
        border: 2px solid #f5c6cb;
        color: #721c24;
    }
    
    .alert i {
        font-size: 1.2rem;
    }
    
    /* Submit Button */
    .submit-btn {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: #fff;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(76, 175, 80, 0.4);
        background: linear-gradient(135deg, #45a049 0%, #388e3c 100%);
    }
    
    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .submit-btn i {
        font-size: 1.2rem;
    }
    
    /* Contact Info Cards */
    .contact-info-card {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        border-{{ is_rtl() ? 'right' : 'left' }}: 4px solid #4CAF50;
        background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
        border-radius: 10px;
        transition: all 0.3s;
    }
    
    .contact-info-card:hover {
        transform: translateX({{ is_rtl() ? '-' : '' }}5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .contact-info-card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #4CAF50;
        margin: 0 0 0.8rem 0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .contact-info-card h3 i {
        font-size: 1.5rem;
        color: #4CAF50;
    }
    
    .contact-info-card p {
        margin: 0;
        color: #666;
        line-height: 1.8;
        font-size: 1rem;
    }
    
    /* Responsive Design */
    @media (max-width: 968px) {
        .contact-container {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 0 1.5rem;
        }
        
        .page-header {
            padding: 2.5rem 1rem;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .page-header p {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 768px) {
        .contact-page {
            padding: 1.5rem 0;
        }
        
        .contact-container {
            padding: 0 1rem;
            gap: 1.5rem;
        }
        
        .page-header {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header p {
            font-size: 1rem;
        }
        
        .contact-form-section,
        .contact-info-section {
            padding: 1.5rem;
            border-radius: 15px;
        }
        
        .contact-form-section h2,
        .contact-info-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1.2rem;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.8rem;
            font-size: 0.95rem;
        }
        
        .submit-btn {
            width: 100%;
            justify-content: center;
            padding: 0.9rem 2rem;
            font-size: 1rem;
        }
        
        .contact-info-card {
            padding: 1.2rem;
            margin-bottom: 1.2rem;
        }
        
        .contact-info-card h3 {
            font-size: 1.1rem;
        }
        
        .contact-info-card p {
            font-size: 0.95rem;
        }
    }
    
    @media (max-width: 480px) {
        .contact-container {
            padding: 0 0.8rem;
        }
        
        .page-header {
            padding: 1.5rem 0.8rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .page-header p {
            font-size: 0.95rem;
        }
        
        .contact-form-section,
        .contact-info-section {
            padding: 1.2rem;
            border-radius: 12px;
        }
        
        .contact-form-section h2,
        .contact-info-section h2 {
            font-size: 1.3rem;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.7rem;
            font-size: 0.9rem;
        }
        
        .submit-btn {
            padding: 0.8rem 1.5rem;
            font-size: 0.95rem;
        }
        
        .contact-info-card {
            padding: 1rem;
        }
        
        .contact-info-card h3 {
            font-size: 1rem;
        }
        
        .contact-info-card h3 i {
            font-size: 1.2rem;
        }
        
        .contact-info-card p {
            font-size: 0.9rem;
        }
    }
</style>

<div class="contact-page">
    <div class="page-header">
        <div class="container">
            <h1>{{ __t('messages.contact_us') }}</h1>
            <p>{{ __t('messages.get_in_touch') }}</p>
        </div>
    </div>

    <div class="contact-container">
        <div class="contact-form-section">
            <h2>{{ __t('messages.send_us_message') }}</h2>
            
            <!-- Session Success Message -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            
            <!-- Session Error Message -->
            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif
            
            <!-- Validation Errors -->
            @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <ul style="margin: 0; padding-{{ is_rtl() ? 'right' : 'left' }}: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- AJAX Success Message -->
            <div id="success-message" class="alert alert-success" style="display: none;">
                <i class="fas fa-check-circle"></i>
                <span id="success-text"></span>
            </div>
            
            <!-- AJAX Error Message -->
            <div id="error-message" class="alert alert-error" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <span id="error-text"></span>
            </div>
            
            <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">
                        {{ __t('messages.your_name') }}
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    <span id="name-error" class="error-text" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        {{ __t('messages.your_email') }}
                        <span class="required">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                    <span id="email-error" class="error-text" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="subject">
                        {{ __t('messages.subject') }}
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required>
                    <span id="subject-error" class="error-text" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="message">
                        {{ __t('messages.your_message') }}
                        <span class="required">*</span>
                    </label>
                    <textarea name="message" id="message" rows="5" required>{{ old('message') }}</textarea>
                    <span id="message-error" class="error-text" style="display: none;"></span>
                </div>
                
                <button type="submit" id="submit-btn" class="submit-btn">
                    @if(is_rtl())
                        {{ __t('messages.send_message') }}
                        <i class="fas fa-paper-plane"></i>
                    @else
                        <i class="fas fa-paper-plane"></i>
                        {{ __t('messages.send_message') }}
                    @endif
                </button>
            </form>
        </div>

        <div class="contact-info-section">
            <h2>{{ __t('messages.contact_information') }}</h2>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-map-marker-alt"></i>
                    {{ __t('messages.address') }}
                </h3>
                <p>123 Tech Street<br>Silicon Valley, CA 94025<br>United States</p>
            </div>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-phone"></i>
                    {{ __t('messages.phone') }}
                </h3>
                <p>+1 (555) 123-4567</p>
            </div>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-envelope"></i>
                    {{ __t('messages.email') }}
                </h3>
                <p>info@itcenter.com<br>support@itcenter.com</p>
            </div>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-clock"></i>
                    {{ __t('messages.business_hours') }}
                </h3>
                <p>
                    {{ __t('messages.monday_friday') }}<br>
                    {{ __t('messages.saturday_hours') }}<br>
                    {{ __t('messages.sunday_closed') }}
                </p>
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
    const successText = document.getElementById('success-text');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const originalBtnContent = submitBtn.innerHTML;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.error-text').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';

        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __t("messages.sending") ?? "Sending..." }}';

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
                successText.textContent = result.message;
                successMessage.style.display = 'flex';
                form.reset();
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Hide success message after 5 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
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
                    errorText.textContent = result.message || '{{ __t("messages.error_occurred") ?? "An error occurred" }}';
                    errorMessage.style.display = 'flex';
                    
                    // Scroll to error message
                    errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorText.textContent = '{{ __t("messages.error_occurred") ?? "An error occurred. Please try again." }}';
            errorMessage.style.display = 'flex';
            
            // Scroll to error message
            errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
        }
    });
});
</script>
@endpush

@endsection
