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
        // 2. Session
        // 3. Browser Accept-Language header
        // 4. Default from config
        
        $locale = null;
        
        // Check URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $availableLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        }
        
        // Check session
        if (!$locale && Session::has('locale') && in_array(Session::get('locale'), $availableLocales)) {
            $locale = Session::get('locale');
        }
        
        // Check browser Accept-Language header
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
