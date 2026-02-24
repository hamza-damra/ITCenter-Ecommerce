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
        // Handle BackupRestoreException with detailed error information
        if ($e instanceof BackupRestoreException) {
            // For API requests, return JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getDetailedMessage(),
                    'error' => $e->getMessage(),
                    'safety_backup' => $e->getSafetyBackup()
                ], 500);
            }

            // For web requests, redirect back with error message
            return back()->with('error', $e->getDetailedMessage());
        }

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
        $connectionPatterns = [
            'SQLSTATE[HY000] [2002]',  // Connection refused
            'SQLSTATE[HY000] [1045]',  // Access denied
            'SQLSTATE[HY000] [2006]',  // Server has gone away
            'SQLSTATE[08006]',         // Connection failure
            'SQLSTATE[08S01]',         // Communication link failure
            'Connection refused',
            'No connection could be made',
            'actively refused it',
            'Can\'t connect to',
            'Access denied for user',
            'server has gone away',
            'could not find driver',
        ];

        $message = $e->getMessage();

        if ($e instanceof PDOException || $e instanceof QueryException) {
            foreach ($connectionPatterns as $pattern) {
                if (str_contains($message, $pattern)) {
                    return true;
                }
            }
        }

        return false;
    }
}
