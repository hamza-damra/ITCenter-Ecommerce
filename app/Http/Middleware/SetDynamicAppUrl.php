<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to dynamically set APP_URL based on the incoming request.
 * 
 * This fixes the issue where images and assets don't load when accessing
 * the site through DDNS or different domains/IPs while APP_URL is set to localhost.
 * 
 * Common scenarios this fixes:
 * - Accessing site via DDNS (e.g., http://archivingalquds.ddns.net:8000)
 * - Accessing via local IP (e.g., http://192.168.1.100:8000)
 * - Accessing via localhost (http://localhost:8000)
 * - Accessing via custom domain
 */
class SetDynamicAppUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Build the URL based on the actual request
        $scheme = $request->getScheme(); // http or https
        $host = $request->getHost(); // domain or IP
        $port = $request->getPort();
        
        // Build the base URL
        $baseUrl = $scheme . '://' . $host;
        
        // Add port if it's non-standard
        if (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443)) {
            $baseUrl .= ':' . $port;
        }
        
        // Update the application URL configuration
        config(['app.url' => $baseUrl]);
        
        // Force URL generator to use the dynamic URL
        URL::forceRootUrl($baseUrl);
        
        // Also handle HTTPS if behind a proxy
        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
        
        return $next($request);
    }
}

