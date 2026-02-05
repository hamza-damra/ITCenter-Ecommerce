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
        try {
            $state = DatabaseStateService::detectState();
        } catch (\Exception $e) {
            // If state detection fails, force non-DB drivers and let exception handler deal with it
            $this->forceNonDbDrivers();
            \Illuminate\Support\Facades\Log::warning('Bootstrap mode state detection failed', [
                'error' => $e->getMessage()
            ]);
            return $next($request);
        }

        $isBootstrapRoute = $request->is('admin/bootstrap/*');
        $isAdminRoute = $request->is('admin/*') && !$isBootstrapRoute;

        // If database is missing (STATE_B), enable bootstrap mode
        if ($state === DatabaseStateService::STATE_B) {
            // Force non-DB drivers only when database is unavailable
            $this->forceNonDbDrivers();
            
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

        // If database is available (STATE_C), use normal DB-based drivers
        if ($state === DatabaseStateService::STATE_C) {
            if ($isBootstrapRoute) {
                // Database exists, bootstrap mode should not be accessible
                abort(404, 'Bootstrap mode is not available when database exists.');
            }

            // Normal mode - database is available, no need to force non-DB drivers
            return $next($request);
        }

        // STATE_A: MySQL unreachable - force non-DB drivers, show error page (handled by exception handler)
        $this->forceNonDbDrivers();
        return $next($request);
    }

    /**
     * Force non-DB drivers for bootstrap mode (only called when database is unavailable)
     */
    protected function forceNonDbDrivers(): void
    {
        // Force file-based session driver to prevent DB queries
        Config::set('session.driver', 'file');
        
        // Force file-based cache driver
        if (Config::get('cache.default') === 'database') {
            Config::set('cache.default', 'file');
        }

        // Force sync queue connection
        if (Config::get('queue.default') === 'database') {
            Config::set('queue.default', 'sync');
        }
    }
}

