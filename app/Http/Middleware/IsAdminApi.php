<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminApi
{
    /**
     * Handle an incoming request.
     * Ensures the authenticated user has admin role for API routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Admin access required.',
                'error' => 'Forbidden',
            ], 403);
        }

        // Admins always have full access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Employees with active roles can access
        if ($user->isEmployee() && $user->employeeRole && $user->employeeRole->is_active) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Forbidden: Admin access required.',
            'error' => 'Forbidden',
        ], 403);
    }
}
