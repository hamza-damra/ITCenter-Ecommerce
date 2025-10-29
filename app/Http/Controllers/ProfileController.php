<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics
        $ordersCount = $user->orders()->count();
        $reviewsCount = $user->reviews()->count();
        
        // Check if user has any verified purchases
        $hasVerifiedPurchases = $user->orders()
            ->where('payment_status', 'paid')
            ->exists();

        return view('profile', compact('user', 'ordersCount', 'reviewsCount', 'hasVerifiedPurchases'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        
        try {
            $data = $request->validated();
            
            // Update name field based on first_name and last_name
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
            
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                // Store new avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }
            
            // Update user
            $user->update($data);
            
            return redirect()->route('profile.index')
                ->with('success', __t('messages.profile_updated_success'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __t('messages.profile_update_failed'))
                ->withInput();
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => __t('messages.current_password_required'),
            'new_password.required' => __t('messages.new_password_required'),
            'new_password.min' => __t('messages.password_min'),
            'new_password.confirmed' => __t('messages.password_confirmation_mismatch'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', __t('messages.current_password_incorrect'))
                ->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.index')
            ->with('success', __t('messages.password_updated_success'));
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->update(['avatar' => null]);
        
        return redirect()->route('profile.index')
            ->with('success', __t('messages.avatar_deleted_success'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ], [
            'password.required' => __t('messages.password_required'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', __t('messages.password_incorrect'));
        }

        try {
            // Delete avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Logout user
            Auth::logout();
            
            // Delete user account
            $user->delete();

            return redirect()->route('home')
                ->with('success', __t('messages.account_deleted_success'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __t('messages.account_delete_failed'));
        }
    }
}

