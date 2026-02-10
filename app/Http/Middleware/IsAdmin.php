<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * Allows access to admins and employees with active roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', __('messages.admin_login_required'));
        }

        $user = Auth::user();

        // Admins always have full access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Employees need an active role
        if ($user->isEmployee()) {
            if ($user->employeeRole && $user->employeeRole->is_active) {
                return $next($request);
            }

            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', __('messages.employee_role_inactive'));
        }

        Auth::logout();
        return redirect()->route('admin.login')
            ->with('error', __('messages.admin_permission_denied'));
    }
}
