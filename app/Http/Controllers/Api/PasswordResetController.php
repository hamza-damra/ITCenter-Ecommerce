<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Notifications\SendOtpVerification;
use App\Traits\ApiResponses;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    use ApiResponses;

    /** Maximum verification attempts per OTP */
    const MAX_ATTEMPTS = 5;

    /** Maximum total attempts across all OTPs before lockout */
    const MAX_GLOBAL_ATTEMPTS = 15;

    /** Lockout duration in minutes */
    const LOCKOUT_MINUTES = 30;

    /** OTP expiry in minutes */
    const OTP_EXPIRY_MINUTES = 60;

    /**
     * Generate a 6-digit OTP and send it to the user's email.
     *
     * POST /api/v1/auth/send-otp
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $email = strtolower(trim($request->email));

        // Check global lockout: count total failed attempts in the lockout window
        $recentAttempts = PasswordResetOtp::forEmail($email)
            ->where('created_at', '>=', Carbon::now()->subMinutes(self::LOCKOUT_MINUTES))
            ->sum('attempts');

        if ($recentAttempts >= self::MAX_GLOBAL_ATTEMPTS) {
            return $this->errorResponse(
                'Too many attempts. Please try again after ' . self::LOCKOUT_MINUTES . ' minutes.',
                429
            );
        }

        // Invalidate all previous unused OTPs for this email
        PasswordResetOtp::forEmail($email)
            ->where('used', false)
            ->update(['used' => true]);

        // Generate a new 6-digit OTP
        $otp = PasswordResetOtp::generateOtp();

        // Store the OTP
        PasswordResetOtp::create([
            'email'      => $email,
            'token'      => $otp,
            'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'used'       => false,
            'attempts'   => 0,
        ]);

        // Send the notification only if the user exists (prevent email enumeration)
        $user = User::where('email', $email)->first();
        if ($user) {
            try {
                $user->notify(new SendOtpVerification($otp));
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP verification email: ' . $e->getMessage());
            }
        }

        // Always return success to prevent email enumeration
        return $this->successResponse(
            ['email' => $email],
            'If an account with that email exists, a verification code has been sent.'
        );
    }

    /**
     * Verify the 6-digit OTP and reset the password.
     *
     * POST /api/v1/auth/verify-otp-reset
     */
    public function verifyOtpAndReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'token'    => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $email = strtolower(trim($request->email));
        $token = $request->token;

        // Find the most recent OTP for this email
        $otpRecord = PasswordResetOtp::forEmail($email)
            ->where('token', $token)
            ->orderBy('created_at', 'desc')
            ->first();

        // OTP does not exist
        if (!$otpRecord) {
            return $this->errorResponse('Invalid verification code.', 422);
        }

        // Too many attempts on this OTP
        if ($otpRecord->attempts >= self::MAX_ATTEMPTS) {
            return $this->errorResponse(
                'Too many failed attempts for this code. Please request a new one.',
                429
            );
        }

        // OTP is expired
        if ($otpRecord->isExpired()) {
            return $this->errorResponse('Verification code has expired. Please request a new one.', 422);
        }

        // OTP already used
        if ($otpRecord->used) {
            return $this->errorResponse('This verification code has already been used.', 422);
        }

        // OTP mismatch (should not happen since we query by token, but defence-in-depth)
        if ($otpRecord->token !== $token) {
            $otpRecord->incrementAttempts();
            return $this->errorResponse('Invalid verification code.', 422);
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->errorResponse('No account found with this email address.', 404);
        }

        // Mark OTP as used
        $otpRecord->markAsUsed();

        // Update password
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        // Revoke all API tokens (force re-login)
        $user->tokens()->delete();

        return $this->successResponse(null, 'Password has been reset successfully. Please log in with your new password.');
    }
}
