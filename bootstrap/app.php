<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App as AppFacade;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude logout routes from CSRF verification to prevent 419 errors
        // when session expires or token becomes stale
        $middleware->validateCsrfTokens(except: [
            'logout',
            'admin/logout',
            'admin/bootstrap/logout',
        ]);
        
        // Bootstrap mode middleware must run early to detect DB state and force non-DB drivers
        $middleware->web(prepend: [
            \App\Http\Middleware\BootstrapModeMiddleware::class,
            \App\Http\Middleware\SetDynamicAppUrl::class,
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // Sanctum: treat API requests from same-origin as stateful so session cookies authenticate
        $middleware->api(prepend: [
            \App\Http\Middleware\SetDynamicAppUrl::class,
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'admin.api' => \App\Http\Middleware\IsAdminApi::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'bootstrap.mode' => \App\Http\Middleware\BootstrapModeMiddleware::class,
            'bootstrap.ip' => \App\Http\Middleware\BootstrapIpAllowlist::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Global handler: if database is unreachable, show a friendly 503 page (web) or JSON (API)
        $exceptions->render(function (\Throwable $e, Request $request) {
            $isDbDown = false;
            $isDbMissing = false;

            // Detect PDO/Query exceptions that indicate DB connectivity issues
            $dbMissingPatterns = ['1049', 'Unknown database'];
            $dbDownPatterns = [
                'SQLSTATE[HY000] [2002]',  // Connection refused / host unreachable
                'SQLSTATE[HY000] [1045]',  // Access denied
                'SQLSTATE[HY000] [2006]',  // Server has gone away
                'SQLSTATE[08006]',         // Connection failure
                'SQLSTATE[08S01]',         // Communication link failure
                'No connection could be made',
                'Connection refused',
                'Can\'t connect to',
                'Access denied for user',
                'server has gone away',
                'could not find driver',
            ];

            if ($e instanceof \PDOException || $e instanceof QueryException) {
                $message = $e->getMessage();

                foreach ($dbMissingPatterns as $p) {
                    if (str_contains($message, $p)) {
                        $isDbMissing = true;
                        break;
                    }
                }

                if (!$isDbMissing) {
                    foreach ($dbDownPatterns as $p) {
                        if (str_contains($message, $p)) {
                            $isDbDown = true;
                            break;
                        }
                    }
                }
            }

            // If database is missing (STATE_B), redirect ALL routes to bootstrap login
            if ($isDbMissing) {
                // Ensure session is not using database
                config(['session.driver' => 'file']);
                config(['cache.default' => 'file']);
                config(['queue.default' => 'sync']);
                
                // Skip redirect for bootstrap routes and API routes
                // Bootstrap routes should handle their own errors gracefully
                if ($request->is('admin/bootstrap/*')) {
                    // For bootstrap routes, show a friendly error page instead of redirecting
                    return response()->view('errors.db-missing', [
                        'exception' => $e,
                        'is_bootstrap_route' => true,
                    ], 503);
                }
                
                // Skip API routes - return JSON error
                if ($request->is('api/*') || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Database schema is missing. Please restore it using Bootstrap Mode.',
                        'error' => config('app.debug') ? $e->getMessage() : 'Database connection failed',
                        'bootstrap_url' => route('admin.bootstrap.login'),
                    ], 503);
                }
                
                // Redirect ALL other routes (including home page) to bootstrap login
                try {
                    return redirect()->route('admin.bootstrap.login')
                        ->with('info', 'Database is missing. Please restore it using Bootstrap Mode.');
                } catch (\Exception $redirectError) {
                    // If route doesn't exist yet, show bootstrap-friendly error
                    return response()->view('errors.db-missing', [
                        'exception' => $e,
                    ], 503);
                }
            }

            if ($isDbDown || $isDbMissing) {
                // Ensure session is not using database to avoid cascading failures during render
                config(['session.driver' => 'array']);

                // Allow language switching without session if provided as query (?lang=ar|en|he)
                $available = config('app.available_locales', ['en','ar','he']);
                $lang = $request->query('lang');
                if ($lang && in_array($lang, $available, true)) {
                    AppFacade::setLocale($lang);
                }

                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => $isDbMissing 
                            ? 'Database schema is missing. Please restore it.' 
                            : trans('errors.db_connection_failed'),
                        'error' => config('app.debug') ? $e->getMessage() : 'Database connection failed'
                    ], 503);
                }

                // For admin routes with missing DB, show bootstrap-friendly error
                if ($isDbMissing && $request->is('admin/*')) {
                    return response()->view('errors.db-missing', [
                        'exception' => $e,
                    ], 503);
                }

                return response()->view('errors.db-down', [
                    'exception' => $e,
                ], 503);
            }
        });

        // Handle API exceptions with JSON responses
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested resource was not found.',
                    'error' => 'Not Found',
                ], 404);
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'error' => 'Not Found',
                ], 404);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Handle PostTooLargeException (file upload too large)
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $maxSize = ini_get('post_max_size');
            
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.upload_too_large', ['size' => $maxSize]),
                    'error' => 'Post Too Large',
                ], 413);
            }
            
            // For web requests, redirect back with error
            return redirect()->back()
                ->withInput($request->except(['image', 'images', 'file', 'files']))
                ->with('error', trans('messages.upload_too_large', ['size' => $maxSize]));
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'error' => 'Authentication Required',
                ], 401);
            }
        });

        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.',
                    'error' => 'Unauthorized',
                ], 401);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.',
                    'error' => 'Forbidden',
                ], 403);
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'The HTTP method is not allowed for this route.',
                    'error' => 'Method Not Allowed',
                ], 405);
            }
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                // Don't expose SQL errors in production
                $message = config('app.debug')
                    ? $e->getMessage()
                    : 'A database error occurred.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error' => 'Database Error',
                ], 500);
            }
        });

        // Handle all other exceptions for API routes
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                // Get status code
                $statusCode = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : 500;

                // Get error message
                $message = config('app.debug')
                    ? $e->getMessage()
                    : 'An error occurred while processing your request.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error' => class_basename($e),
                    'trace' => config('app.debug') ? $e->getTrace() : null,
                ], $statusCode);
            }
        });
    })->create();
