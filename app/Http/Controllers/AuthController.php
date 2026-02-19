<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        // Store the redirect URL if provided (for returning after login)
        if ($request->has('redirect')) {
            session()->put('url.intended', $request->get('redirect'));
        }
        
        return view('auth.login');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Handle login
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
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // CRITICAL FIX: Merge guest cart with user cart
            $this->mergeGuestCart(Auth::user());

            // Check for redirect parameter or use intended URL
            $redirectTo = $request->get('redirect') ?: session()->pull('url.intended', route('home'));
            
            // Validate the redirect URL to prevent open redirect attacks
            if (!$this->isValidRedirectUrl($redirectTo)) {
                $redirectTo = route('home');
            }

            return redirect($redirectTo)
                ->with('success', __t('messages.login_success'));
        }

        return redirect()->back()
            ->withErrors(['email' => __t('messages.invalid_credentials')])
            ->withInput($request->only('email'));
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Auto login after registration
            Auth::login($user);

            // CRITICAL FIX: Merge guest cart after registration
            $this->mergeGuestCart($user);

            return redirect()->route('home')
                ->with('success', __t('messages.register_success'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __t('messages.register_failed'))
                ->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Preserve locale before session is destroyed
        $locale = $request->session()->get('locale');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Restore locale so the user stays in their chosen language
        if ($locale) {
            $request->session()->put('locale', $locale);
        }

        return redirect()->route('home')
            ->with('success', __t('messages.logout_success'));
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return redirect()->back()
                ->with('success', __t('messages.reset_link_sent'));
        }

        return redirect()->back()
            ->with('error', __t('messages.reset_link_failed'));
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', __t('messages.password_reset_success'));
        }

        return redirect()->back()
            ->withErrors(['email' => __t('messages.password_reset_failed')])
            ->withInput($request->only('email'));
    }

    /**
     * Validate redirect URL to prevent open redirect attacks
     */
    protected function isValidRedirectUrl($url)
    {
        if (empty($url)) {
            return false;
        }
        
        // Allow relative URLs starting with /
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            return true;
        }
        
        // Parse the URL and check if it's the same host
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['host'])) {
            return true; // Relative URL without host
        }
        
        // Check if the host matches our app's host
        $appUrl = parse_url(config('app.url'));
        return isset($appUrl['host']) && $parsedUrl['host'] === $appUrl['host'];
    }

    /**
     * CRITICAL FIX: Merge guest cart with user cart on login
     */
    protected function mergeGuestCart($user)
    {
        $sessionId = Session::getId();
        
        if (!$sessionId) {
            return;
        }

        DB::transaction(function() use ($user, $sessionId) {
            $guestCartItems = CartItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();

            foreach ($guestCartItems as $guestItem) {
                $existingItem = CartItem::where('user_id', $user->id)
                    ->where('product_id', $guestItem->product_id)
                    ->first();

                if ($existingItem) {
                    // Merge quantities
                    $existingItem->increment('quantity', $guestItem->quantity);
                    $guestItem->delete();
                } else {
                    // Transfer to user cart
                    $guestItem->update([
                        'user_id' => $user->id,
                        'session_id' => null,
                    ]);
                }
            }
        });
    }
}
