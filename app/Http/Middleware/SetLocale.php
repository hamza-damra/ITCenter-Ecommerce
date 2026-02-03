<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get available locales from config
        $availableLocales = config('app.available_locales', ['en', 'ar']);
        
        // Priority order for locale detection:
        // 1. URL parameter (?lang=ar)
        // 2. Session (user's explicit choice)
        // 3. Accept-Language header (browser preference)
        // 4. Default from config
        
        $locale = null;
        
        // Check URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $availableLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        }
        
        // Check session (user's explicit choice takes priority over browser headers)
        if (!$locale && Session::has('locale') && in_array(Session::get('locale'), $availableLocales)) {
            $locale = Session::get('locale');
        }
        
        // Check Accept-Language header (browser preference)
        if (!$locale && $request->header('Accept-Language')) {
            $acceptLanguage = $request->header('Accept-Language');
            // Extract first locale from header (e.g., "ar" from "ar,en;q=0.9")
            $headerLocale = strtok($acceptLanguage, ',;');
            if ($headerLocale && in_array($headerLocale, $availableLocales)) {
                $locale = $headerLocale;
            }
        }
        
        // Fallback: Check browser preferred language
        if (!$locale) {
            $browserLocale = $request->getPreferredLanguage($availableLocales);
            if ($browserLocale && in_array($browserLocale, $availableLocales)) {
                $locale = $browserLocale;
            }
        }
        
        // Fallback to default
        if (!$locale) {
            $locale = config('app.locale', 'en');
        }
        
        // Set the locale
        App::setLocale($locale);
        
        // Share locale with all views
        view()->share('currentLocale', $locale);
        view()->share('availableLocales', $availableLocales);
        
        return $next($request);
    }
}
