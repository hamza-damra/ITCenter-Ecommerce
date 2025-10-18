<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
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

            // Create API token with appropriate abilities
            $token = $user->createToken('auth-token', ['*'])->plainTextToken;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Registration successful', 201);

        } catch (\Exception $e) {
            return $this->errorResponse('Registration failed. Please try again.', 500);
        }
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('The provided credentials are incorrect.', 401);
        }

        // Generate token with device name
        $deviceName = $request->device_name ?? 'web-browser';
        $token = $user->createToken($deviceName, ['*'])->plainTextToken;

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'email_verified_at' => $user->email_verified_at,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
            ],
        ], 'User data retrieved successfully');
    }

    /**
     * Logout user (Revoke current token)
     */
    public function logout(Request $request)
    {
        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout successful');
    }

    /**
     * Logout from all devices (Revoke all tokens)
     */
    public function logoutAll(Request $request)
    {
        // Delete all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return $this->successResponse(null, 'Logged out from all devices');
    }

    /**
     * Refresh token (Create new token and revoke old one)
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Delete current token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $deviceName = $request->device_name ?? 'web-browser';
        $token = $user->createToken($deviceName, ['*'])->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Token refreshed successfully');
    }

    /**
     * Get all active sessions (tokens)
     */
    public function sessions(Request $request)
    {
        $tokens = $request->user()->tokens;

        $sessions = $tokens->map(function ($token) use ($request) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'is_current' => $token->id === $request->user()->currentAccessToken()->id,
            ];
        });

        return $this->successResponse([
            'sessions' => $sessions,
        ], 'Active sessions retrieved successfully');
    }

    /**
     * Revoke a specific session (token)
     */
    public function revokeSession(Request $request, $tokenId)
    {
        $user = $request->user();
        $token = $user->tokens()->find($tokenId);

        if (!$token) {
            return $this->notFoundResponse('Session not found');
        }

        $token->delete();

        return $this->successResponse(null, 'Session revoked successfully');
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse(null, 'Password reset link sent to your email');
        }

        return $this->errorResponse('Unable to send reset link. Please try again.', 500);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                // Revoke all tokens after password reset
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse(null, 'Password reset successfully');
        }

        return $this->errorResponse('Unable to reset password. Please try again.', 500);
    }

    /**
     * Change password (authenticated user)
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('Current password is incorrect', 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Optionally revoke all tokens except current
        $currentTokenId = $request->user()->currentAccessToken()->id;
        $user->tokens()->where('id', '!=', $currentTokenId)->delete();

        return $this->successResponse(null, 'Password changed successfully');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'sometimes|required|email|unique:users,email,' . $request->user()->id,
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->toArray());
        }

        $user = $request->user();
        
        $updateData = $request->only(['first_name', 'last_name', 'phone', 'email']);
        
        // Update name if first_name or last_name changed
        if (isset($updateData['first_name']) || isset($updateData['last_name'])) {
            $firstName = $updateData['first_name'] ?? $user->first_name;
            $lastName = $updateData['last_name'] ?? $user->last_name;
            $updateData['name'] = $firstName . ' ' . $lastName;
        }

        $user->update($updateData);

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ], 'Profile updated successfully');
    }
}
