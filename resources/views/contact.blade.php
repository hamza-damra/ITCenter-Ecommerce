@extends('layouts.app')

@section('title', 'Contact Us - IT Center')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with our team</p>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 2rem;">
        <div>
            <h2>Send Us a Message</h2>
            <form style="margin-top: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Name</label>
                    <input type="text" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Email</label>
                    <input type="email" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Phone</label>
                    <input type="tel" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Message</label>
                    <textarea rows="5" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
                <button type="submit" style="background: #4CAF50; color: white; padding: 0.75rem 2rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">
                    Send Message
                </button>
            </form>
        </div>

        <div>
            <h2>Contact Information</h2>
            <div style="margin-top: 1.5rem;">
                <div style="margin-bottom: 1.5rem; padding: 1rem; border-left: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>Address</h3>
                    <p>123 Tech Street<br>Silicon Valley, CA 94025<br>United States</p>
                </div>
                <div style="margin-bottom: 1.5rem; padding: 1rem; border-left: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>Phone</h3>
                    <p>+1 (555) 123-4567</p>
                </div>
                <div style="margin-bottom: 1.5rem; padding: 1rem; border-left: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>Email</h3>
                    <p>info@itcenter.com<br>support@itcenter.com</p>
                </div>
                <div style="padding: 1rem; border-left: 3px solid #4CAF50; background: #f9f9f9;">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                    Saturday: 10:00 AM - 4:00 PM<br>
                    Sunday: Closed</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
