<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use PDOException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Check if it's a database connection error
        if ($this->isDatabaseConnectionError($e)) {
            // Temporarily switch to file sessions to avoid cascading errors
            config(['session.driver' => 'file']);
            
            // For API requests, return JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => __('errors.db_connection_failed'),
                    'error' => config('app.debug') ? $e->getMessage() : 'Database connection failed'
                ], 503);
            }

            // For web requests, show custom error page
            return response()->view('errors.db-down', [
                'exception' => $e
            ], 503);
        }

        return parent::render($request, $e);
    }

    /**
     * Determine if the exception is a database connection error.
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function isDatabaseConnectionError(Throwable $e): bool
    {
        // Check for PDO connection errors
        if ($e instanceof PDOException) {
            return true;
        }

        // Check for Laravel Query Exception with connection errors
        if ($e instanceof QueryException) {
            $message = $e->getMessage();
            
            // Common database connection error patterns
            $connectionErrors = [
                'SQLSTATE[HY000] [2002]', // Connection refused
                'SQLSTATE[HY000] [1045]', // Access denied
                'SQLSTATE[08006]',        // Connection failure
                'Connection refused',
                'No connection could be made',
                'actively refused it',
                'Can\'t connect to',
                'Access denied for user',
            ];

            foreach ($connectionErrors as $error) {
                if (str_contains($message, $error)) {
                    return true;
                }
            }
        }

        return false;
    }
}
