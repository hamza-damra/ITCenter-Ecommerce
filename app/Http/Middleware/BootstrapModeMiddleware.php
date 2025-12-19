<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Services\DatabaseStateService;
use Symfony\Component\HttpFoundation\Response;

class BootstrapModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force non-DB drivers FIRST to prevent any DB queries
        // This must happen before state detection to avoid cascading failures
        $this->forceNonDbDrivers();

        try {
            $state = DatabaseStateService::detectState();
        } catch (\Exception $e) {
            // If state detection fails, assume STATE_A and let exception handler deal with it
            \Illuminate\Support\Facades\Log::warning('Bootstrap mode state detection failed', [
                'error' => $e->getMessage()
            ]);
            return $next($request);
        }

        $isBootstrapRoute = $request->is('admin/bootstrap/*');
        $isAdminRoute = $request->is('admin/*') && !$isBootstrapRoute;

        // If database is missing (STATE_B), enable bootstrap mode
        if ($state === DatabaseStateService::STATE_B) {
            // Skip redirect for bootstrap routes and API routes
            if ($isBootstrapRoute || $request->is('api/*')) {
                // Allow bootstrap routes to proceed
                return $next($request);
            }

            // Redirect ALL other routes (including home page) to bootstrap login
            if ($isAdminRoute || $request->is('/') || $request->is('/*')) {
                return redirect()->route('admin.bootstrap.login')
                    ->with('info', 'Database is missing. Please restore it using Bootstrap Mode.');
            }

            // Allow bootstrap routes to proceed
            return $next($request);
        }

        // If database is available (STATE_C), block bootstrap routes
        if ($state === DatabaseStateService::STATE_C) {
            if ($isBootstrapRoute) {
                // Database exists, bootstrap mode should not be accessible
                abort(404, 'Bootstrap mode is not available when database exists.');
            }

            // Normal mode - restore default drivers if they were changed
            $this->restoreDefaultDrivers();

            return $next($request);
        }

        // STATE_A: MySQL unreachable - show error page (handled by exception handler)
        // Don't force drivers here, let exception handler deal with it
        return $next($request);
    }

    /**
     * Force non-DB drivers for bootstrap mode
     */
    protected function forceNonDbDrivers(): void
    {
        // Always force file-based session driver to prevent DB queries
        Config::set('session.driver', 'file');
        
        // Always force file-based cache driver
        if (Config::get('cache.default') === 'database') {
            Config::set('cache.default', 'file');
        }

        // Always force sync queue connection
        if (Config::get('queue.default') === 'database') {
            Config::set('queue.default', 'sync');
        }
    }

    /**
     * Restore default drivers (if needed)
     */
    protected function restoreDefaultDrivers(): void
    {
        // Only restore if they were explicitly set in .env
        // Don't override user's configuration
        // This is mainly for cleanup after bootstrap mode
    }
}

