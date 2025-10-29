<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
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
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Sanctum: treat API requests from same-origin as stateful so session cookies authenticate
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Global handler: if database is unreachable, show a friendly 503 page (web) or JSON (API)
        $exceptions->render(function (\Throwable $e, Request $request) {
            $isDbDown = false;

            // Detect PDO/Query exceptions that indicate DB connectivity issues
            if ($e instanceof \PDOException) {
                $isDbDown = true;
            }

            if (!$isDbDown && $e instanceof QueryException) {
                $message = $e->getMessage();
                $patterns = [
                    'SQLSTATE[HY000] [2002]', // Connection refused / host unreachable
                    'SQLSTATE[HY000] [1045]', // Access denied
                    'SQLSTATE[08006]',        // Connection failure
                    'No connection could be made',
                    'Connection refused',
                    'Can\'t connect to',
                    'Access denied for user',
                    'server has gone away',
                ];
                foreach ($patterns as $p) {
                    if (str_contains($message, $p)) {
                        $isDbDown = true;
                        break;
                    }
                }
            }

            if ($isDbDown) {
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
                        'message' => trans('errors.db_connection_failed'),
                        'error' => config('app.debug') ? $e->getMessage() : 'Database connection failed'
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
