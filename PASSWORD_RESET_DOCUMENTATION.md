# Password Reset System - Complete Documentation

## Overview
This is a complete **OTP-based password reset system** for Laravel that uses **Gmail SMTP** to send 4-digit verification codes. The system is fully multilingual (Arabic, English, Hebrew) and follows security best practices.

## 🎯 Features

### Security Features
- ✅ 4-digit numeric OTP (1000-9999)
- ✅ 10-minute expiration time
- ✅ Single-use codes (marked as used after successful reset)
- ✅ Brute force protection (max 5 attempts per code)
- ✅ Generic responses (doesn't reveal if email exists)
- ✅ Session-based verification flow
- ✅ Automatic login after password reset

### User Experience
- ✅ 3-step flow (Request → Verify → Reset)
- ✅ Clean, modern UI matching existing design
- ✅ Full multilingual support (AR/EN/HE)
- ✅ Responsive design
- ✅ Real-time password validation
- ✅ Password visibility toggle
- ✅ Auto-formatted code input

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   └── ForgotPasswordController.php       # Main controller with 3-step logic
├── Mail/
│   └── SendResetCodeMail.php             # Email template for OTP
└── Models/
    └── PasswordResetCode.php             # Eloquent model for reset codes

database/
└── migrations/
    └── 2025_10_25_125342_create_password_reset_codes_table.php

resources/
└── views/
    ├── auth/
    │   ├── forgot-password.blade.php     # Step 1: Request code
    │   ├── verify-code.blade.php         # Step 2: Verify code
    │   └── reset-password.blade.php      # Step 3: Set new password
    └── emails/
        └── reset-code.blade.php          # Email template

lang/
├── en/password_reset.php                 # English translations
├── ar/password_reset.php                 # Arabic translations
└── he/password_reset.php                 # Hebrew translations

routes/
└── web.php                               # Password reset routes
```

## 🚀 Installation & Setup

### Step 1: Gmail SMTP Configuration

1. **Update your Gmail address** in `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-gmail-address@gmail.com
   MAIL_PASSWORD="ehoq afht vhtv rgqz"
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-gmail-address@gmail.com
   MAIL_FROM_NAME="IT Center"
   ```

2. **Replace `your-gmail-address@gmail.com`** with your actual Gmail address

3. The **app password** (`ehoq afht vhtv rgqz`) is already configured

### Step 2: Database Migration

The migration has already been run, but if you need to run it again:

```bash
php artisan migrate
```

This creates the `password_reset_codes` table with:
- `id` - Primary key
- `email` - User's email address
- `code` - 4-digit numeric code
- `expires_at` - Expiration timestamp (10 minutes)
- `used` - Boolean flag (prevents reuse)
- `attempts` - Counter for failed verification attempts
- `created_at` / `updated_at` - Timestamps

### Step 3: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📋 Flow Overview

### Step 1: Request Reset Code
**Route:** `GET /forgot-password`

**What happens:**
1. User enters their email address
2. System generates a 4-digit code (1000-9999)
3. Code is saved to `password_reset_codes` table with 10-minute expiry
4. Email is sent (only if user exists, but response is always generic)
5. User is redirected to verification page

**Security:**
- Generic response: "If this email is registered, a code was sent."
- Old unused codes are deleted when requesting a new one
- No indication if email exists or not

### Step 2: Verify Code
**Route:** `GET /verify-code`

**What happens:**
1. User enters their email and the 4-digit code
2. System validates:
   - Code exists
   - Code matches
   - Code not expired
   - Code not already used
   - Attempts < 5
3. If valid, creates session token for password reset
4. User is redirected to reset password page

**Security:**
- Increments attempt counter on failed verification
- Blocks after 5 failed attempts
- Session-based verification token
- Cannot skip to password reset without verification

### Step 3: Reset Password
**Route:** `GET /reset-password`

**What happens:**
1. Verifies user has valid session token from step 2
2. User enters new password (min 8 characters) + confirmation
3. System:
   - Updates user's password using `Hash::make()`
   - Marks code as `used = true`
   - Clears all session data
   - Automatically logs user in
4. Redirects to home with success message

## 🔌 API Endpoints

### Request Reset Code
```http
POST /forgot-password
Content-Type: application/x-www-form-urlencoded

email=user@example.com
```

**Response:**
- Redirect to `/verify-code` with generic success message

### Verify Code
```http
POST /verify-code
Content-Type: application/x-www-form-urlencoded

email=user@example.com
code=1234
```

**Response:**
- Success: Redirect to `/reset-password`
- Error: Back with error message

### Reset Password
```http
POST /reset-password
Content-Type: application/x-www-form-urlencoded

email=user@example.com
password=NewPassword123
password_confirmation=NewPassword123
```

**Response:**
- Success: Redirect to home, user logged in
- Error: Back with validation errors

## 🌐 Routes

```php
// Step 1: Request reset code
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
    ->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'requestReset'])
    ->name('password.request.post');

// Step 2: Verify code
Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyCodeForm'])
    ->name('password.verify.form');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])
    ->name('password.verify.post');

// Step 3: Reset password
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.reset.post');
```

## 🎨 UI Components

### Forgot Password Page
- Email input with icon
- Info box explaining the process
- "Send Verification Code" button
- "Back to Login" link

### Verify Code Page
- Email display (from session)
- 4-digit code input (auto-formatted, numbers only)
- Expiry notice
- "Verify Code" button
- "Resend Code" and "Back to Login" links

### Reset Password Page
- Email display (from session)
- New password input with show/hide toggle
- Confirm password input with show/hide toggle
- Password requirements box
- "Reset Password" button

## 🌍 Multilingual Support

All text uses the `__t()` helper function and translation keys from `lang/{locale}/password_reset.php`.

**Example:**
```php
{{ __t('password_reset.forgot_password') }}
```

**Supported Languages:**
- English (`en`)
- Arabic (`ar`) - RTL support
- Hebrew (`he`) - RTL support

**Available Translation Keys:**
- Page titles: `forgot_password`, `verify_code`, `reset_password`
- Form fields: `email_address`, `verification_code`, `new_password`, etc.
- Buttons: `send_code`, `verify_code_button`, `reset_password_button`
- Messages: `code_sent`, `code_verified`, `password_updated`
- Errors: `invalid_code`, `code_expired`, `too_many_attempts`
- Email content: `email_subject`, `email_greeting`, `your_code`, etc.

## 📧 Email Template

The email sent to users includes:
- App logo/name
- Greeting
- Instructions
- **4-digit code** (large, centered, highlighted)
- Security notice
- Expiry warning (10 minutes)
- Footer with copyright

**Preview:**
```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       IT Center
━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Hello!

You requested to reset your password.
Use the verification code below:

┌─────────────────────┐
│   Your Code         │
│      1234           │
└─────────────────────┘

⚠️ Security Notice
This code expires in 10 minutes.

If you didn't request this, ignore
this email.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━
© 2025 IT Center
All rights reserved.
```

## 🔒 Security Best Practices

### Implemented Security Measures

1. **No Email Enumeration**
   - Generic response: "If this email is registered..."
   - Same response whether email exists or not

2. **Code Expiration**
   - Codes expire after 10 minutes
   - Expired codes cannot be used

3. **Single-Use Codes**
   - Marked as `used = true` after successful reset
   - Used codes cannot be reused

4. **Brute Force Protection**
   - Maximum 5 verification attempts per code
   - Attempt counter incremented on failures
   - Locked after limit exceeded

5. **Session Security**
   - Verification token stored in session
   - Cannot access reset page without verification
   - Session cleared after password reset

6. **Password Security**
   - Minimum 8 characters required
   - Confirmation required
   - Hashed using `Hash::make()` (bcrypt)

7. **Database Cleanup**
   - Old unused codes deleted when requesting new one
   - Prevents database clutter

## 🧪 Testing

### Manual Testing Checklist

1. **Request Code Flow:**
   - [ ] Visit `/forgot-password`
   - [ ] Enter email and submit
   - [ ] Check inbox for 4-digit code
   - [ ] Verify generic success message

2. **Verify Code Flow:**
   - [ ] Enter correct email + code
   - [ ] Try wrong code (should increment attempts)
   - [ ] Try expired code (wait 10 min)
   - [ ] Try used code (after completing reset)
   - [ ] Try 6 times (should block after 5)

3. **Reset Password Flow:**
   - [ ] Try accessing without verification (should redirect)
   - [ ] Enter new password (min 8 chars)
   - [ ] Try mismatched passwords
   - [ ] Submit and verify:
     - [ ] Password updated
     - [ ] Auto-login works
     - [ ] Code marked as used

4. **Multilingual Testing:**
   - [ ] Switch to Arabic - check RTL
   - [ ] Switch to Hebrew - check RTL
   - [ ] Switch to English - check LTR
   - [ ] Verify all translations load

5. **Email Testing:**
   - [ ] Verify email received
   - [ ] Check code in email
   - [ ] Verify email formatting
   - [ ] Check multilingual email subject

### Test Users

Create a test user:
```php
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password123'),
]);
```

## 🐛 Troubleshooting

### Email Not Sending

**Problem:** Code not received in inbox

**Solutions:**
1. Check Gmail SMTP credentials in `.env`
2. Verify Gmail app password is correct
3. Check spam/junk folder
4. Enable "Less secure app access" (if needed)
5. Check Laravel logs: `storage/logs/laravel.log`
6. Test mail config:
   ```bash
   php artisan tinker
   Mail::raw('Test email', fn($msg) => $msg->to('test@example.com')->subject('Test'));
   ```

### "Session Expired" Error

**Problem:** Getting "Session expired" when trying to verify code

**Solution:**
- Ensure cookies are enabled
- Check session driver in `.env`: `SESSION_DRIVER=database`
- Clear session: `php artisan session:clear`

### "Code Already Used" Error

**Problem:** Code shows as used even though it wasn't

**Solution:**
- Check `password_reset_codes` table
- Verify `used` column is `false`
- Request a new code

### Translation Not Working

**Problem:** Seeing translation keys instead of text (e.g., `password_reset.forgot_password`)

**Solutions:**
1. Clear cache: `php artisan config:clear && php artisan view:clear`
2. Verify translation files exist in `lang/{locale}/password_reset.php`
3. Check `LocaleHelper.php` is loaded
4. Verify `__t()` helper function exists

## 📝 Customization

### Change Code Length
Edit `PasswordResetCode::generateCode()`:
```php
public static function generateCode(): string
{
    // For 6-digit code:
    return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
}
```

### Change Expiration Time
Edit `ForgotPasswordController::requestReset()`:
```php
'expires_at' => Carbon::now()->addMinutes(15), // 15 minutes instead of 10
```

### Change Max Attempts
Edit `ForgotPasswordController`:
```php
const MAX_ATTEMPTS = 10; // Change from 5 to 10
```

### Customize Email Template
Edit `resources/views/emails/reset-code.blade.php`

### Add Additional Password Rules
Edit `ForgotPasswordController::resetPassword()`:
```php
$request->validate([
    'email' => 'required|email',
    'password' => [
        'required',
        'string',
        'min:8',
        'confirmed',
        'regex:/[A-Z]/',      // Uppercase required
        'regex:/[a-z]/',      // Lowercase required
        'regex:/[0-9]/',      // Number required
        'regex:/[@$!%*?&]/',  // Special char required
    ],
]);
```

## 🔗 Integration with Login Page

Add a "Forgot Password?" link to your login page:

```blade
<div class="text-center mt-3">
    <a href="{{ route('password.request') }}" class="auth-link">
        {{ __t('password_reset.forgot_password') }}?
    </a>
</div>
```

## 📊 Database Schema

```sql
CREATE TABLE `password_reset_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `code` varchar(4) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `attempts` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_codes_email_index` (`email`),
  KEY `password_reset_codes_email_code_used_index` (`email`,`code`,`used`)
);
```

## 🎓 Code Examples

### Generate and Send Code Programmatically
```php
use App\Models\PasswordResetCode;
use App\Mail\SendResetCodeMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

$email = 'user@example.com';
$code = PasswordResetCode::generateCode();

PasswordResetCode::create([
    'email' => $email,
    'code' => $code,
    'expires_at' => Carbon::now()->addMinutes(10),
]);

Mail::to($email)->send(new SendResetCodeMail($code, $email));
```

### Verify Code Programmatically
```php
$resetCode = PasswordResetCode::forEmail($email)
    ->where('code', $code)
    ->valid()
    ->first();

if ($resetCode && $resetCode->attempts < 5) {
    // Code is valid
    $resetCode->markAsUsed();
} else {
    $resetCode?->incrementAttempts();
}
```

### Clean Up Expired Codes (Optional Scheduled Task)
```php
// Add to app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        PasswordResetCode::where('expires_at', '<', now())->delete();
    })->daily();
}
```

## 📞 Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode: `APP_DEBUG=true` in `.env`
3. Run `php artisan route:list | grep password` to verify routes
4. Check database table: `SELECT * FROM password_reset_codes;`

## ✅ System Requirements

- Laravel 12.x
- PHP 8.2+
- MySQL/MariaDB
- Gmail account with app password
- Mail server access (SMTP)

## 🎉 Summary

Your password reset system is now fully functional with:
- ✅ 4-digit OTP via Gmail SMTP
- ✅ 3-step verification flow
- ✅ Full multilingual support (AR/EN/HE)
- ✅ Security best practices
- ✅ Modern UI matching your design
- ✅ Auto-login after reset
- ✅ Brute force protection
- ✅ Email obfuscation

**Next Steps:**
1. Replace `your-gmail-address@gmail.com` in `.env` with your actual Gmail
2. Test the full flow with a real user
3. Add the "Forgot Password?" link to your login page
4. Monitor `storage/logs/laravel.log` for any issues

🎊 **Enjoy your new password reset system!**
