<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Auth\BootstrapUserProvider;

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
        // Register bootstrap user provider
        Auth::provider('bootstrap', function ($app, array $config) {
            return new BootstrapUserProvider();
        });

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

        // Share nav categories with all views for navigation bar
        // Only categories with display_mode 'nav' appear in the navigation bar
        // Exclude error views and bootstrap views to prevent cascading database errors
        view()->composer('*', function ($view) {
            // Skip for error views and bootstrap views to avoid cascading DB errors
            $viewName = $view->getName();
            if (str_starts_with($viewName, 'errors.') || 
                str_starts_with($viewName, 'errors/') ||
                str_starts_with($viewName, 'admin.bootstrap.')) {
                $view->with('navigationCategories', collect([]));
                return;
            }
            
            // Check if database is available before trying to query
            try {
                if (!\App\Services\DatabaseStateService::isDatabaseAvailable()) {
                    $view->with('navigationCategories', collect([]));
                    return;
                }
            } catch (\Exception $e) {
                $view->with('navigationCategories', collect([]));
                return;
            }
            
            try {
                $navigationCategories = \App\Models\Category::with(['children' => function ($query) {
                    $query->where('is_active', true)->orderBy('position');
                }])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->where('display_mode', 'nav')
                ->orderBy('position')
                ->get();
            } catch (\Exception $e) {
                // If database fails, provide empty collection to prevent view errors
                $navigationCategories = collect([]);
            }
            
            $view->with('navigationCategories', $navigationCategories);
        });
    }
}
