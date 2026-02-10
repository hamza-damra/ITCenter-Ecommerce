<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Checks if the authenticated user has the required permission.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  The permission key to check (e.g. 'products.view')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login')
                ->with('error', __('messages.admin_login_required'));
        }

        // Admins bypass all permission checks
        if ($user->isAdmin()) {
            return $next($request);
        }

        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.access_denied'),
                ], 403);
            }

            return response()->view('admin.access-denied', [
                'permission' => $permission,
            ], 403);
        }

        return $next($request);
    }
}
