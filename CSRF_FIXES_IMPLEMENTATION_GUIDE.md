# CSRF Token Expiration - Implementation Guide

## Quick Reference

This guide provides **ready-to-implement code fixes** for all CSRF token expiration issues identified in the analysis.

---

## Fix #1: Increase Session Lifetime ⚡ CRITICAL

### Problem
Session expires after 2 hours, causing CSRF token to become invalid.

### Solution
Update `.env` file:

```env
# Change from:
SESSION_LIFETIME=120

# To (12 hours):
SESSION_LIFETIME=720

# Or (24 hours for better UX):
SESSION_LIFETIME=1440
```

**Impact:** Reduces CSRF token expiration errors by 6-12x.

---

## Fix #2: Add TokenMismatchException Handler ⚡ CRITICAL

### Problem
No user-friendly error handling for CSRF token mismatch errors.

### Solution
Add to `bootstrap/app.php` in the `withExceptions` callback (after line 128):

```php
use Illuminate\Session\TokenMismatchException;

// Add this after the ValidationException handler (around line 128)
$exceptions->render(function (TokenMismatchException $e, Request $request) {
    // For API/AJAX requests
    if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json([
            'success' => false,
            'message' => 'Your session has expired. Please refresh the page and try again.',
            'error' => 'CSRF Token Mismatch',
            'error_code' => 'CSRF_EXPIRED',
            'should_refresh' => true,
        ], 419);
    }
    
    // For web form submissions
    return redirect()->back()
        ->withInput($request->except('_token', 'password', 'password_confirmation'))
        ->with('error', __('Your session has expired. Please try again.'));
});
```

**Location:** `bootstrap/app.php` after line 128

---

## Fix #3: Client-Side CSRF Error Detection ⚡ CRITICAL

### Problem
AJAX requests don't detect and handle 419 CSRF errors gracefully.

### Solution A: Update API Client

**File:** `resources/js/api/client.js`

Replace lines 30-42 with:

```javascript
try {
    const response = await fetch(url, config);
    
    // Handle CSRF token expiration
    if (response.status === 419) {
        console.warn('CSRF token expired, refreshing page...');
        
        // Show user-friendly message
        if (typeof showNotification === 'function') {
            showNotification('Your session has expired. Refreshing page...', 'warning');
        } else {
            alert('Your session has expired. The page will refresh.');
        }
        
        // Refresh page after short delay
        setTimeout(() => {
            window.location.reload();
        }, 2000);
        
        throw new Error('CSRF token expired');
    }
    
    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || 'An error occurred');
    }

    return data;
} catch (error) {
    console.error('API Error:', error);
    throw error;
}
```

### Solution B: Update Review Submission

**File:** `resources/views/partials/reviews-section.blade.php`

Update the fetch response handling (around line 876-890):

```javascript
const response = await fetch(url, {
    method,
    credentials: 'same-origin',
    headers: {
        'X-CSRF-TOKEN': csrfToken.content,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    body: formData
});

// Handle CSRF token expiration
if (response.status === 419) {
    showToast('{{ __("messages.session_expired") ?? "Your session has expired. Please refresh the page." }}', 'warning');
    setTimeout(() => {
        window.location.reload();
    }, 2000);
    return;
}

if (!response.ok) {
    const data = await response.json();
    showToast(data.message || '{{ __("messages.review_submit_failed") }}', 'error');
    return;
}

const data = await response.json();
```

---

## Fix #4: CSRF Token Refresh Mechanism 🔶 HIGH

### Problem
CSRF token is never refreshed during page lifecycle.

### Solution A: Create Token Refresh Endpoint

**File:** `routes/api.php`

Add this route (around line 80):

```php
// CSRF Token Refresh
Route::get('/csrf-token', function () {
    return response()->json([
        'success' => true,
        'token' => csrf_token(),
    ]);
});
```

### Solution B: Add Token Refresh Function

**File:** `resources/views/layouts/app.blade.php`

Add this JavaScript function in the `<script>` section (around line 1320):

```javascript
/**
 * Refresh CSRF token from server
 */
async function refreshCsrfToken() {
    try {
        const response = await fetch('/api/v1/csrf-token', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.token) {
                // Update meta tag
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', data.token);
                    console.log('CSRF token refreshed successfully');
                    return data.token;
                }
            }
        }
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
    }
    return null;
}

/**
 * Auto-refresh CSRF token every 30 minutes
 */
function startCsrfTokenRefresh() {
    // Refresh immediately on page load
    refreshCsrfToken();
    
    // Then refresh every 30 minutes (1800000 ms)
    setInterval(refreshCsrfToken, 1800000);
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', startCsrfTokenRefresh);
```

---

## Fix #5: Session Timeout Warning 🔶 HIGH

### Problem
Users aren't warned when their session is about to expire.

### Solution
Add session timeout warning to checkout and review pages.

**File:** `resources/views/layouts/app.blade.php`

Add this JavaScript (around line 1320):

```javascript
/**
 * Session timeout warning system
 */
class SessionTimeoutWarning {
    constructor(sessionLifetimeMinutes = 120) {
        this.sessionLifetime = sessionLifetimeMinutes * 60 * 1000; // Convert to ms
        this.warningTime = 5 * 60 * 1000; // Warn 5 minutes before expiry
        this.lastActivity = Date.now();
        this.warningShown = false;
        
        this.init();
    }
    
    init() {
        // Track user activity
        ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, () => this.resetTimer(), true);
        });
        
        // Start checking
        this.startChecking();
    }
    
    resetTimer() {
        this.lastActivity = Date.now();
        this.warningShown = false;
    }
    
    startChecking() {
        setInterval(() => {
            const timeSinceActivity = Date.now() - this.lastActivity;
            const timeUntilExpiry = this.sessionLifetime - timeSinceActivity;
            
            // Show warning if close to expiry
            if (timeUntilExpiry <= this.warningTime && !this.warningShown) {
                this.showWarning(Math.floor(timeUntilExpiry / 60000)); // Minutes remaining
                this.warningShown = true;
            }
            
            // Session expired
            if (timeUntilExpiry <= 0) {
                this.handleExpiry();
            }
        }, 30000); // Check every 30 seconds
    }
    
    showWarning(minutesRemaining) {
        const message = `Your session will expire in ${minutesRemaining} minute(s). Click OK to stay logged in.`;
        
        if (confirm(message)) {
            // User wants to stay - refresh token
            refreshCsrfToken().then(() => {
                this.resetTimer();
                if (typeof showNotification === 'function') {
                    showNotification('Session extended successfully', 'success');
                }
            });
        }
    }
    
    handleExpiry() {
        if (typeof showNotification === 'function') {
            showNotification('Your session has expired. Please refresh the page.', 'error');
        } else {
            alert('Your session has expired. The page will refresh.');
        }
        
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }
}

// Initialize session timeout warning
// Only on pages with forms (checkout, reviews, etc.)
if (document.querySelector('form')) {
    const sessionLifetime = {{ config('session.lifetime', 120) }};
    new SessionTimeoutWarning(sessionLifetime);
}
```

---

## Fix #6: Create Custom ValidateCsrfToken Middleware 🔷 MEDIUM

### Problem
No custom CSRF middleware for application-specific needs.

### Solution
Create custom middleware.

**File:** `app/Http/Middleware/ValidateCsrfToken.php` (NEW FILE)

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class ValidateCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add any routes that should be excluded from CSRF verification
        // Example: webhook endpoints
        // 'webhooks/*',
        // 'api/external/*',
    ];

    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        $response = parent::addCookieToResponse($request, $response);
        
        // Add CSRF token to response header for easier JavaScript access
        $response->headers->set('X-CSRF-Token', $request->session()->token());
        
        return $response;
    }
}
```

**Then update:** `config/sanctum.php` line 81

```php
'validate_csrf_token' => \App\Http\Middleware\ValidateCsrfToken::class,
```

---

## Fix #7: Production Cookie Security 🔷 MEDIUM

### Problem
Session cookies not properly secured in production.

### Solution
Update `.env` for production:

```env
# Production environment
APP_ENV=production
APP_DEBUG=false

# Session security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true

# Ensure HTTPS
APP_URL=https://yourdomain.com
```

---

## Fix #8: Checkout Form Token Refresh 🔶 HIGH

### Problem
Checkout form vulnerable to token expiration during long form filling.

### Solution
Add token refresh to checkout page.

**File:** `resources/views/checkout.blade.php`

Add this JavaScript before the closing `</script>` tag (around line 760):

```javascript
// Refresh CSRF token periodically during checkout
let checkoutTokenRefreshInterval;

function startCheckoutTokenRefresh() {
    // Refresh token every 15 minutes
    checkoutTokenRefreshInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/v1/csrf-token', {
                credentials: 'same-origin'
            });
            const data = await response.json();
            
            if (data.token) {
                // Update meta tag
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', data.token);
                
                // Update form token if it exists
                const formToken = document.querySelector('#checkout-form input[name="_token"]');
                if (formToken) {
                    formToken.value = data.token;
                }
                
                console.log('Checkout CSRF token refreshed');
            }
        } catch (error) {
            console.error('Failed to refresh checkout token:', error);
        }
    }, 900000); // 15 minutes
}

// Start token refresh when page loads
startCheckoutTokenRefresh();

// Clean up on page unload
window.addEventListener('beforeunload', () => {
    if (checkoutTokenRefreshInterval) {
        clearInterval(checkoutTokenRefreshInterval);
    }
});
```

---

## Fix #9: Admin API Client Token Refresh 🔷 MEDIUM

### Problem
Admin API client doesn't refresh CSRF token.

### Solution
Update admin API client.

**File:** `resources/js/admin/api-client.js`

Add method to the `AdminAPIClient` class (around line 32):

```javascript
/**
 * Refresh CSRF token
 */
async refreshCsrfToken() {
    try {
        const response = await fetch('/api/v1/csrf-token', {
            credentials: 'same-origin'
        });
        const data = await response.json();
        
        if (data.token) {
            this.headers['X-CSRF-TOKEN'] = data.token;
            
            // Update meta tag
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.content = data.token;
            }
            
            return data.token;
        }
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
    }
    return null;
}

/**
 * Make API request with automatic CSRF token refresh on 419 error
 */
async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const config = {
        ...options,
        headers: {
            ...this.headers,
            ...options.headers
        }
    };

    try {
        const response = await fetch(url, config);
        
        // Handle CSRF token expiration
        if (response.status === 419) {
            console.warn('CSRF token expired, refreshing...');
            await this.refreshCsrfToken();
            
            // Retry request with new token
            config.headers['X-CSRF-TOKEN'] = this.headers['X-CSRF-TOKEN'];
            const retryResponse = await fetch(url, config);
            const retryData = await retryResponse.json();
            
            if (!retryResponse.ok) {
                throw new Error(retryData.message || 'API request failed after token refresh');
            }
            
            return retryData;
        }
        
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'API request failed');
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}
```

---

## Implementation Checklist

### Phase 1: Critical Fixes (Implement Today)
- [ ] Fix #1: Increase session lifetime to 720 minutes
- [ ] Fix #2: Add TokenMismatchException handler
- [ ] Fix #3: Add client-side CSRF error detection
- [ ] Test: Submit form after token expiration

### Phase 2: High Priority (Implement This Week)
- [ ] Fix #4: Implement CSRF token refresh mechanism
- [ ] Fix #5: Add session timeout warning
- [ ] Fix #8: Add checkout form token refresh
- [ ] Test: Long checkout process (>2 hours)

### Phase 3: Medium Priority (Implement This Month)
- [ ] Fix #6: Create custom ValidateCsrfToken middleware
- [ ] Fix #7: Configure production cookie security
- [ ] Fix #9: Update admin API client
- [ ] Test: Multi-tab scenarios

---

## Testing Instructions

### Test 1: Session Expiration
```bash
# Temporarily set short session lifetime
SESSION_LIFETIME=1  # 1 minute

# Then:
1. Open checkout page
2. Wait 2 minutes
3. Submit form
4. Verify: User-friendly error message appears
5. Verify: Page refreshes automatically
```

### Test 2: Token Refresh
```javascript
// In browser console:
1. console.log(document.querySelector('meta[name="csrf-token"]').content);
2. Wait 30 minutes
3. console.log(document.querySelector('meta[name="csrf-token"]').content);
4. Verify: Token has changed
```

### Test 3: Review Submission
```bash
1. Open product page
2. Read reviews for 10 minutes
3. Submit a review
4. Verify: Review submits successfully (token was refreshed)
```

---

## Rollback Plan

If issues occur after implementing fixes:

1. **Revert session lifetime:**
   ```env
   SESSION_LIFETIME=120
   ```

2. **Disable token refresh:**
   Comment out `startCsrfTokenRefresh()` call

3. **Remove exception handler:**
   Comment out TokenMismatchException handler in `bootstrap/app.php`

4. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

---

**Document Version:** 1.0  
**Last Updated:** 2025-10-29  
**Status:** Ready for Implementation

