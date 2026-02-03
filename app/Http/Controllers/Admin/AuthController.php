<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // Check if we're in bootstrap mode (database missing)
        try {
            if (\App\Services\DatabaseStateService::shouldEnableBootstrapMode()) {
                return redirect()->route('admin.bootstrap.login')
                    ->with('info', __('messages.database_missing_bootstrap'));
            }
        } catch (\Exception $e) {
            // If state detection fails, continue with normal login
        }

        try {
            if (Auth::check() && Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        } catch (\Exception $e) {
            // If auth check fails (database issue), redirect to bootstrap
            if (\App\Services\DatabaseStateService::shouldEnableBootstrapMode()) {
                return redirect()->route('admin.bootstrap.login')
                    ->with('info', __('messages.database_missing_bootstrap'));
            }
        }
        
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => __('messages.invalid_admin_credentials')])
                    ->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', __('messages.welcome_back_name', ['name' => $user->name]));
        }

        return redirect()->back()
            ->withErrors(['email' => __('messages.invalid_credentials')])
            ->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', __('messages.logout_success'));
    }
}
