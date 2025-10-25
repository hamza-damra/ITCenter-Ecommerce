<?php

namespace App\Http\Controllers;

use App\Mail\SendResetCodeMail;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Maximum attempts allowed for verifying a code
    const MAX_ATTEMPTS = 5;
    
    /**
     * Show the forgot password form (Step 1)
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the forgot password request (Step 1 - POST)
     * Generate and send OTP code
     */
    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Delete any existing unused codes for this email to prevent clutter
        PasswordResetCode::forEmail($email)
            ->where('used', false)
            ->delete();

        // Generate a 4-digit code
        $code = PasswordResetCode::generateCode();

        // Store the code in the database
        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'used' => false,
            'attempts' => 0,
        ]);

        // Send the code via email (only if the user exists, but don't reveal this)
        $user = User::where('email', $email)->first();
        if ($user) {
            try {
                Mail::to($email)->send(new SendResetCodeMail($code, $email));
            } catch (\Exception $e) {
                // Log the error but don't reveal to the user
                \Log::error('Failed to send reset code email: ' . $e->getMessage());
            }
        }

        // Generic success message (don't reveal if email exists or not)
        return redirect()
            ->route('password.verify.form')
            ->with('email', $email)
            ->with('success', __t('password_reset.code_sent'));
    }

    /**
     * Show the verify code form (Step 2)
     */
    public function showVerifyCodeForm()
    {
        $email = session('email');
        
        if (!$email) {
            return redirect()
                ->route('password.request')
                ->with('error', __t('password_reset.session_expired'));
        }

        return view('auth.verify-code', compact('email'));
    }

    /**
     * Handle the code verification (Step 2 - POST)
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:4',
        ]);

        $email = $request->email;
        $code = $request->code;

        // Find the most recent code for this email
        $resetCode = PasswordResetCode::forEmail($email)
            ->where('code', $code)
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if code exists
        if (!$resetCode) {
            return back()
                ->withInput()
                ->with('error', __t('password_reset.invalid_code'));
        }

        // Check if too many attempts
        if ($resetCode->attempts >= self::MAX_ATTEMPTS) {
            return back()
                ->withInput()
                ->with('error', __t('password_reset.too_many_attempts'));
        }

        // Check if code is expired
        if ($resetCode->isExpired()) {
            return back()
                ->withInput()
                ->with('error', __t('password_reset.code_expired'));
        }

        // Check if code is already used
        if ($resetCode->used) {
            return back()
                ->withInput()
                ->with('error', __t('password_reset.code_already_used'));
        }

        // Verify the code matches
        if ($resetCode->code !== $code) {
            $resetCode->incrementAttempts();
            return back()
                ->withInput()
                ->with('error', __t('password_reset.invalid_code'));
        }

        // Code is valid - create a temporary verification token
        $verificationToken = base64_encode(json_encode([
            'email' => $email,
            'code_id' => $resetCode->id,
            'timestamp' => time(),
        ]));

        // Store the verification token in session
        Session::put('reset_verified', $verificationToken);
        Session::put('reset_email', $email);

        return redirect()
            ->route('password.reset.form')
            ->with('success', __t('password_reset.code_verified'));
    }

    /**
     * Show the reset password form (Step 3)
     */
    public function showResetPasswordForm()
    {
        // Ensure the user has verified their code
        if (!Session::has('reset_verified')) {
            return redirect()
                ->route('password.request')
                ->with('error', __t('password_reset.verification_required'));
        }

        $email = Session::get('reset_email');

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Handle the password reset (Step 3 - POST)
     */
    public function resetPassword(Request $request)
    {
        // Ensure the user has verified their code
        if (!Session::has('reset_verified')) {
            return redirect()
                ->route('password.request')
                ->with('error', __t('password_reset.verification_required'));
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->email;
        $verifiedEmail = Session::get('reset_email');

        // Ensure the email matches the verified email
        if ($email !== $verifiedEmail) {
            return back()
                ->with('error', __t('password_reset.email_mismatch'));
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()
                ->with('error', __t('password_reset.user_not_found'));
        }

        // Get the verification token
        $verificationToken = Session::get('reset_verified');
        $tokenData = json_decode(base64_decode($verificationToken), true);

        // Mark the code as used
        $resetCode = PasswordResetCode::find($tokenData['code_id']);
        if ($resetCode) {
            $resetCode->markAsUsed();
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear the verification session data
        Session::forget('reset_verified');
        Session::forget('reset_email');
        Session::forget('email');

        // Log the user in automatically
        Auth::login($user);

        return redirect()
            ->route('home')
            ->with('success', __t('password_reset.password_updated'));
    }
}
