<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and add security headers to the response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Frame-Options: Prevent clickjacking by disabling iframe embedding
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options: Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: Enable browser XSS filter (legacy but still useful)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Control referrer information sent with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: Restrict browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Strict-Transport-Security (HSTS): Force HTTPS for 1 year, include subdomains
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Cross-Origin-Opener-Policy: Isolate top-level window from cross-origin documents
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Content-Security-Policy: Mitigate XSS by restricting allowed sources
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com",
            "img-src 'self' data: blob: https: http:",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
