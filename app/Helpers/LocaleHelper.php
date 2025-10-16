<?php

if (!function_exists('__t')) {
    /**
     * Translate the given message with optional parameters.
     * Shortcut for trans() function.
     *
     * @param string|null $key
     * @param array $replace
     * @param string|null $locale
     * @return string|array|null
     */
    function __t(?string $key = null, array $replace = [], ?string $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }
        return trans($key, $replace, $locale);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get the current locale.
     *
     * @return string
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL (Right-to-Left).
     *
     * @return bool
     */
    function is_rtl(): bool
    {
        return in_array(current_locale(), ['ar', 'he', 'fa', 'ur']);
    }
}

if (!function_exists('locale_direction')) {
    /**
     * Get the text direction for current locale.
     *
     * @return string
     */
    function locale_direction(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('available_locales')) {
    /**
     * Get all available locales.
     *
     * @return array
     */
    function available_locales(): array
    {
        return config('app.available_locales', ['en', 'ar']);
    }
}

if (!function_exists('locale_name')) {
    /**
     * Get the display name of a locale.
     *
     * @param string|null $locale
     * @return string
     */
    function locale_name(?string $locale = null): string
    {
        $locale = $locale ?? current_locale();
        $names = config('app.locale_names', [
            'en' => 'English',
            'ar' => 'العربية',
        ]);
        
        return $names[$locale] ?? $locale;
    }
}

if (!function_exists('switch_locale_url')) {
    /**
     * Generate URL for switching locale.
     *
     * @param string $locale
     * @return string
     */
    function switch_locale_url(string $locale): string
    {
        return route('lang.switch', ['locale' => $locale]);
    }
}

if (!function_exists('trans_choice_count')) {
    /**
     * Enhanced pluralization with count display.
     *
     * @param string $key
     * @param int $count
     * @param array $replace
     * @return string
     */
    function trans_choice_count(string $key, int $count, array $replace = []): string
    {
        $replace['count'] = $count;
        return trans_choice($key, $count, $replace);
    }
}
