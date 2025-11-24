<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom validation rule for checking if value exists in config array
        Validator::extend('exists_in_config', function ($attribute, $value, $parameters, $validator) {
            if (count($parameters) < 1) {
                return false;
            }

            $configPath = $parameters[0];
            $configArray = config($configPath);

            if (!is_array($configArray)) {
                return false;
            }

            return array_key_exists($value, $configArray);
        });

        Validator::replacer('exists_in_config', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The selected :attribute is invalid.');
        });

        // Share categories with all views for navigation
        view()->composer('*', function ($view) {
            $categories = \App\Models\Category::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('position');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
            
            $view->with('navigationCategories', $categories);
        });
    }
}
