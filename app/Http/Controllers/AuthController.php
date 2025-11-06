<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CartItem;
use App\Services\CartCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
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
            // Capture old session ID before regeneration
            $oldSessionId = $request->session()->getId();

            $request->session()->regenerate();

            // Migrate guest cart items to authenticated user
            $this->migrateGuestCart($oldSessionId, Auth::id());

            return redirect()->intended(route('home'))
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
            // Capture session ID before creating user
            $oldSessionId = $request->session()->getId();

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

            // Migrate guest cart items to newly registered user
            $this->migrateGuestCart($oldSessionId, $user->id);

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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

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
     * Migrate guest cart items to authenticated user
     * Called after successful login or registration
     */
    private function migrateGuestCart($sessionId, $userId)
    {
        try {
            // Find cart items associated with the session
            $guestCartItems = CartItem::where('session_id', $sessionId)->get();

            if ($guestCartItems->isEmpty()) {
                return;
            }

            foreach ($guestCartItems as $guestItem) {
                // Check if user already has this product in their cart
                $existingItem = CartItem::where('user_id', $userId)
                    ->where('product_id', $guestItem->product_id)
                    ->first();

                if ($existingItem) {
                    // Merge quantities
                    $existingItem->quantity += $guestItem->quantity;
                    $existingItem->save();

                    // Delete the guest cart item
                    $guestItem->delete();
                } else {
                    // Transfer the cart item to the user
                    $guestItem->user_id = $userId;
                    $guestItem->session_id = null;
                    $guestItem->save();
                }
            }

            // Clear cache for both old session and new user
            CartCacheService::clearMultiple([
                ['session_id' => $sessionId],
                ['user_id' => $userId]
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the authentication
            \Log::error('Failed to migrate guest cart: ' . $e->getMessage());
        }
    }
}
