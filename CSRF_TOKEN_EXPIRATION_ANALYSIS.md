# CSRF Token Expiration Issues - Comprehensive Analysis

## Executive Summary

This document identifies **critical CSRF token expiration issues** in the ITCenter E-commerce application that can cause "CSRF token expired" errors for users. The analysis covers configuration problems, implementation gaps, and scenarios where users will encounter token expiration.

---

## 1. Current CSRF Implementation Overview

### 1.1 CSRF Middleware Configuration

**Location:** `config/sanctum.php` (lines 78-82)

```php
'middleware' => [
    'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
    'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
    'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
],
```

**Status:** ✅ Laravel's default `ValidateCsrfToken` middleware is configured via Sanctum.

**Issue:** ❌ **No custom `ValidateCsrfToken` middleware exists** in `app/Http/Middleware/` to configure exceptions or custom handling.

### 1.2 Session Configuration

**Location:** `config/session.php` & `.env.example`

```php
'lifetime' => (int) env('SESSION_LIFETIME', 120),  // 120 minutes = 2 hours
'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
'driver' => env('SESSION_DRIVER', 'file'),  // Default: database in .env.example
'same_site' => env('SESSION_SAME_SITE', 'lax'),
'secure' => env('SESSION_SECURE_COOKIE'),  // Not set by default
'http_only' => true,  // Default Laravel setting
```

**Key Settings:**
- **Session Lifetime:** 120 minutes (2 hours)
- **Session Driver:** Database (from `.env.example`)
- **SameSite:** `lax` (allows some cross-site requests)
- **Secure Cookie:** Not explicitly set (defaults to `false` in local development)

### 1.3 CSRF Token Generation

**Location:** `resources/views/layouts/app.blade.php` (line 6)

```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

**Status:** ✅ CSRF token is embedded in the page meta tag on every page load.

### 1.4 CSRF Token Usage in JavaScript

**Locations:**
- `resources/js/api/client.js` (lines 22-28)
- `resources/js/admin/api-client.js` (lines 20-24)
- `resources/views/layouts/app.blade.php` (lines 1319-1321)
- `resources/views/partials/reviews-section.blade.php` (lines 856-859)

**Pattern:**
```javascript
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
config.headers['X-CSRF-TOKEN'] = csrfToken;
```

**Status:** ✅ CSRF tokens are correctly extracted and sent in AJAX requests.

---

## 2. Critical Issues Identified

### 🔴 **ISSUE #1: Session Lifetime Too Short (2 Hours)**

**Location:** `config/session.php` line 35, `.env.example` line 31

**Problem:**
- Session lifetime is set to **120 minutes (2 hours)**
- CSRF tokens are tied to the session
- When the session expires, the CSRF token becomes invalid
- Users who keep a page open for more than 2 hours will get CSRF errors

**Affected Scenarios:**
1. **Review Submission:** User browses product, reads reviews for 2+ hours, then tries to submit a review
2. **Checkout Process:** User adds items to cart, gets distracted, returns 2+ hours later to checkout
3. **Admin Panel:** Admin leaves backup page open, returns hours later to perform backup
4. **Form Submissions:** Any form left open for 2+ hours

**Evidence:**
```php
// config/session.php
'lifetime' => (int) env('SESSION_LIFETIME', 120),  // ❌ Only 2 hours
```

**Recommended Fix:**
```env
SESSION_LIFETIME=720  # 12 hours (more reasonable for e-commerce)
# OR
SESSION_LIFETIME=1440  # 24 hours (full day)
```

---

### 🔴 **ISSUE #2: No CSRF Token Refresh Mechanism**

**Location:** All JavaScript files making AJAX requests

**Problem:**
- CSRF token is read **once** from the meta tag when the page loads
- Token is **never refreshed** during the page lifecycle
- If the session expires while the page is open, the cached token becomes stale
- No mechanism to detect token expiration and refresh it

**Affected Code:**
```javascript
// resources/js/api/client.js (lines 22-28)
if (options.method && options.method !== 'GET') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    }
}
// ❌ Token is read fresh each time, but meta tag is never updated
```

**Why This Is a Problem:**
- The meta tag contains the token from initial page load
- Even though the code reads it fresh each time, the meta tag itself is stale
- After session expiration, all subsequent AJAX requests will fail with 419 CSRF token mismatch

**Recommended Fix:**
Implement a token refresh mechanism:
```javascript
async function refreshCsrfToken() {
    try {
        const response = await fetch('/api/v1/csrf-token', {
            method: 'GET',
            credentials: 'same-origin'
        });
        const data = await response.json();
        if (data.token) {
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
            return data.token;
        }
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
    }
    return null;
}
```

---

### 🔴 **ISSUE #3: No CSRF Error Handling in Exception Handler**

**Location:** `bootstrap/app.php` (lines 44-206), `app/Exceptions/Handler.php`

**Problem:**
- Exception handler has specific handlers for many exception types
- **No handler for `TokenMismatchException`** (CSRF token mismatch)
- Users get generic Laravel error page instead of helpful message
- No automatic token refresh attempt on CSRF failure

**Current Exception Handlers:**
```php
// bootstrap/app.php
$exceptions->render(function (NotFoundHttpException $e, Request $request) { ... });
$exceptions->render(function (ValidationException $e, Request $request) { ... });
$exceptions->render(function (AuthenticationException $e, Request $request) { ... });
// ❌ Missing: TokenMismatchException handler
```

**Recommended Fix:**
```php
use Illuminate\Session\TokenMismatchException;

$exceptions->render(function (TokenMismatchException $e, Request $request) {
    if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json([
            'success' => false,
            'message' => 'Your session has expired. Please refresh the page and try again.',
            'error' => 'CSRF Token Mismatch',
            'should_refresh' => true,
        ], 419);
    }
    
    return redirect()->back()
        ->withInput($request->except('_token', 'password'))
        ->with('error', 'Your session has expired. Please try again.');
});
```

---

### 🟡 **ISSUE #4: No Client-Side CSRF Error Detection**

**Location:** `resources/js/api/client.js`, `resources/views/partials/reviews-section.blade.php`

**Problem:**
- AJAX error handlers don't specifically check for 419 status (CSRF token mismatch)
- No automatic page refresh or token refresh on CSRF errors
- Users see generic error messages instead of being prompted to refresh

**Current Error Handling:**
```javascript
// resources/js/api/client.js (lines 34-42)
if (!response.ok) {
    throw new Error(data.message || 'An error occurred');
}
// ❌ No specific handling for 419 status
```

**Review Submission Error Handling:**
```javascript
// resources/views/partials/reviews-section.blade.php (lines 887-939)
if (!response.ok) {
    const data = await response.json();
    showToast(data.message || '...', 'error');
    // ❌ No check for response.status === 419
}
```

**Recommended Fix:**
```javascript
if (!response.ok) {
    if (response.status === 419) {
        // CSRF token expired
        showToast('Your session has expired. Refreshing page...', 'warning');
        setTimeout(() => window.location.reload(), 2000);
        return;
    }
    throw new Error(data.message || 'An error occurred');
}
```

---

### 🟡 **ISSUE #5: Checkout Form Vulnerable to Long Idle Times**

**Location:** `resources/views/checkout.blade.php`, `app/Http/Controllers/CheckoutController.php`

**Problem:**
- Checkout form uses traditional form submission with `@csrf` token
- Users may fill out checkout form slowly or get distracted
- If session expires during form filling, submission will fail with CSRF error
- No warning to user that their session is about to expire

**Current Implementation:**
```blade
<!-- resources/views/checkout.blade.php line 479 -->
<form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
    @csrf
    <!-- Long form with many fields -->
</form>
```

**Scenario:**
1. User adds items to cart at 2:00 PM
2. User navigates to checkout at 2:05 PM (session token generated)
3. User fills out shipping info slowly, gets phone call, etc.
4. User submits form at 4:10 PM (2 hours 5 minutes later)
5. **CSRF token expired** → Order fails → User frustrated

**Recommended Fix:**
- Implement session timeout warning
- Auto-refresh CSRF token periodically
- Add JavaScript to detect idle time and warn user

---

### 🟡 **ISSUE #6: Review Submission After Long Product Browsing**

**Location:** `resources/views/partials/reviews-section.blade.php`

**Problem:**
- Users may spend significant time reading product reviews before writing their own
- Review form is loaded with the page, containing initial CSRF token
- After 2+ hours of reading, token expires
- Review submission fails with CSRF error

**Current Implementation:**
```blade
<!-- Line 696 -->
<form id="review-form" onsubmit="submitReview(event)">
    @csrf
    <!-- Review form fields -->
</form>
```

**JavaScript Submission:**
```javascript
// Lines 868-885
const response = await fetch(url, {
    method,
    credentials: 'same-origin',
    headers: {
        'X-CSRF-TOKEN': csrfToken.content,  // ❌ Token from page load
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    body: formData
});
```

**Scenario:**
1. User opens product page at 10:00 AM
2. User reads 50+ reviews, watches videos, compares specs
3. User decides to write review at 12:30 PM (2.5 hours later)
4. **CSRF token expired** → Review submission fails

---

### 🟡 **ISSUE #7: Multi-Tab Session Conflicts**

**Problem:**
- User opens multiple tabs of the site
- Each tab has its own copy of the CSRF token from when it loaded
- Laravel regenerates session ID on certain actions (login, logout)
- Old tabs have stale session IDs and CSRF tokens
- AJAX requests from old tabs fail with CSRF errors

**Scenario:**
1. User opens Product A in Tab 1 at 2:00 PM
2. User opens Product B in Tab 2 at 2:05 PM
3. User logs in via Tab 2 at 2:10 PM (session regenerated)
4. User tries to add review in Tab 1 at 2:15 PM
5. **CSRF token mismatch** → Tab 1 has old session ID

**Evidence:**
```php
// Laravel regenerates session on login
Auth::login($user);  // This regenerates session ID
```

**No Mechanism to Sync Tokens Across Tabs**

---

### 🟡 **ISSUE #8: Missing Secure Cookie Configuration**

**Location:** `config/session.php` line 172, `.env.example`

**Problem:**
- `SESSION_SECURE_COOKIE` is not set in `.env.example`
- Defaults to `false` in development
- In production with HTTPS, cookies should be marked `Secure`
- Without `Secure` flag, cookies may not be sent properly over HTTPS

**Current Configuration:**
```php
// config/session.php
'secure' => env('SESSION_SECURE_COOKIE'),  // ❌ Not set, defaults to null/false
```

**Recommended Fix:**
```env
# .env for production
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

---

## 3. Additional Observations

### 3.1 No Custom ValidateCsrfToken Middleware

**Finding:** The application uses Laravel's default `ValidateCsrfToken` middleware without customization.

**Missing Features:**
- No excluded routes (e.g., webhooks, API callbacks)
- No custom token validation logic
- No ability to add CSRF token to response headers

**Location to Create:** `app/Http/Middleware/ValidateCsrfToken.php`

### 3.2 Database Session Driver

**Configuration:** `.env.example` line 30
```env
SESSION_DRIVER=database
```

**Implications:**
- Sessions stored in database table
- Requires database connection for every request
- If database is slow/down, CSRF validation fails
- Session cleanup depends on lottery system (2/100 chance per request)

**Potential Issue:** Database performance impacts CSRF token validation speed.

---

## 4. User-Facing Scenarios Summary

### Scenario 1: Long Checkout Process
**Trigger:** User takes >2 hours to complete checkout  
**Result:** CSRF token expired error on order submission  
**Impact:** HIGH - Lost sales, cart abandonment

### Scenario 2: Review After Extended Reading
**Trigger:** User reads reviews for >2 hours before submitting own review  
**Result:** Review submission fails with CSRF error  
**Impact:** MEDIUM - Lost user-generated content, frustration

### Scenario 3: Admin Backup After Idle
**Trigger:** Admin opens backup page, leaves it open for hours  
**Result:** Backup operation fails with CSRF error  
**Impact:** MEDIUM - Admin workflow disruption

### Scenario 4: Multi-Tab Shopping
**Trigger:** User shops in multiple tabs, logs in one tab  
**Result:** Other tabs have stale CSRF tokens  
**Impact:** MEDIUM - Confusing user experience

### Scenario 5: Mobile Browser Background
**Trigger:** Mobile user backgrounds browser for >2 hours  
**Result:** All form submissions fail when returning  
**Impact:** HIGH - Mobile users disproportionately affected

---

## 5. Recommended Fixes Priority

### 🔴 **CRITICAL (Implement Immediately)**

1. **Increase Session Lifetime**
   - Change from 120 minutes to 720 minutes (12 hours)
   - File: `.env` and `config/session.php`

2. **Add TokenMismatchException Handler**
   - Provide user-friendly error messages
   - Auto-refresh page on CSRF errors
   - File: `bootstrap/app.php`

3. **Implement Client-Side CSRF Error Detection**
   - Check for 419 status in all AJAX calls
   - Auto-refresh page or token on CSRF errors
   - Files: `resources/js/api/client.js`, review submission code

### 🟡 **HIGH (Implement Soon)**

4. **Add CSRF Token Refresh Mechanism**
   - Create endpoint to get fresh token
   - Periodically refresh token in long-lived pages
   - Refresh token before critical operations

5. **Add Session Timeout Warning**
   - Warn users 5 minutes before session expires
   - Offer to extend session
   - Implement in checkout and review pages

### 🟢 **MEDIUM (Implement When Possible)**

6. **Create Custom ValidateCsrfToken Middleware**
   - Add excluded routes if needed
   - Custom error messages
   - Token refresh on validation failure

7. **Implement Cross-Tab Session Sync**
   - Use localStorage/BroadcastChannel to sync tokens
   - Detect session changes across tabs

8. **Add Production Cookie Security**
   - Set `SESSION_SECURE_COOKIE=true` in production
   - Ensure HTTPS is enforced

---

## 6. Testing Recommendations

### Test Case 1: Session Expiration
1. Set `SESSION_LIFETIME=1` (1 minute)
2. Open checkout page
3. Wait 2 minutes
4. Submit order
5. **Expected:** User-friendly error, not 419 page

### Test Case 2: AJAX CSRF Failure
1. Open product page
2. Open browser console
3. Manually change CSRF token in meta tag
4. Try to submit review
5. **Expected:** Graceful error handling, page refresh prompt

### Test Case 3: Multi-Tab Scenario
1. Open product in Tab 1
2. Open product in Tab 2
3. Logout and login in Tab 2
4. Try to add to cart in Tab 1
5. **Expected:** Token sync or clear error message

---

## 7. Code Locations Reference

| Component | File Path | Lines |
|-----------|-----------|-------|
| Session Config | `config/session.php` | 35, 172, 202 |
| CSRF Middleware | `config/sanctum.php` | 78-82 |
| Exception Handler | `bootstrap/app.php` | 44-206 |
| API Client | `resources/js/api/client.js` | 22-28 |
| Review Submission | `resources/views/partials/reviews-section.blade.php` | 856-885 |
| Checkout Form | `resources/views/checkout.blade.php` | 479, 738-744 |
| Layout Meta Tag | `resources/views/layouts/app.blade.php` | 6 |

---

**Document Version:** 1.0  
**Date:** 2025-10-29  
**Status:** Analysis Complete - Awaiting Implementation

