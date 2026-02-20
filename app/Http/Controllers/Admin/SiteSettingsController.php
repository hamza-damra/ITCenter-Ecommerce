<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SiteSettingsController extends Controller
{
    /**
     * Display the site settings page.
     */
    public function index()
    {
        $imageSettings = [
            'max_image_size_kb' => SiteSetting::getValue('max_image_size_kb', 5120),
            'allowed_image_formats' => SiteSetting::getValue('allowed_image_formats', 'jpg,jpeg,png,webp'),
            'max_additional_images' => SiteSetting::getValue('max_additional_images', 10),
            'image_quality' => SiteSetting::getValue('image_quality', 80),
            'convert_to_webp' => SiteSetting::getValue('convert_to_webp', true),
            'max_image_width' => SiteSetting::getValue('max_image_width', 1920),
            'max_image_height' => SiteSetting::getValue('max_image_height', 1080),
        ];

        $privacyPolicy = [
            'en' => SiteSetting::getValue('privacy_policy_en', ''),
            'ar' => SiteSetting::getValue('privacy_policy_ar', ''),
            'he' => SiteSetting::getValue('privacy_policy_he', ''),
        ];

        $refundPolicy = [
            'en' => SiteSetting::getValue('refund_policy_en', ''),
            'ar' => SiteSetting::getValue('refund_policy_ar', ''),
            'he' => SiteSetting::getValue('refund_policy_he', ''),
        ];

        $defaultSocialLinks = [
            ['platform' => 'facebook',  'label' => 'Facebook',    'icon' => 'fab fa-facebook-f', 'url' => 'https://facebook.com',  'visible' => true],
            ['platform' => 'instagram', 'label' => 'Instagram',   'icon' => 'fab fa-instagram',  'url' => 'https://instagram.com', 'visible' => true],
            ['platform' => 'whatsapp',  'label' => 'WhatsApp',    'icon' => 'fab fa-whatsapp',   'url' => 'https://wa.me/',        'visible' => true],
            ['platform' => 'twitter',   'label' => 'Twitter / X', 'icon' => 'fab fa-twitter',    'url' => 'https://twitter.com',   'visible' => false],
        ];

        $socialLinks = SiteSetting::getValue('social_links', $defaultSocialLinks);
        if (!is_array($socialLinks)) {
            $socialLinks = $defaultSocialLinks;
        }

        return view('admin.site-settings.index', compact('imageSettings', 'privacyPolicy', 'refundPolicy', 'socialLinks'));
    }

    /**
     * Update image upload settings.
     */
    public function updateImageSettings(Request $request)
    {
        $validated = $request->validate([
            'max_image_size_kb' => 'required|integer|min:256|max:20480',
            'allowed_image_formats' => 'required|string',
            'max_additional_images' => 'required|integer|min:1|max:50',
            'image_quality' => 'required|integer|min:10|max:100',
            'convert_to_webp' => 'nullable|boolean',
            'max_image_width' => 'required|integer|min:320|max:7680',
            'max_image_height' => 'required|integer|min:320|max:4320',
        ]);

        SiteSetting::setValue('max_image_size_kb', $validated['max_image_size_kb'], 'integer', 'images');
        SiteSetting::setValue('allowed_image_formats', $validated['allowed_image_formats'], 'string', 'images');
        SiteSetting::setValue('max_additional_images', $validated['max_additional_images'], 'integer', 'images');
        SiteSetting::setValue('image_quality', $validated['image_quality'], 'integer', 'images');
        SiteSetting::setValue('convert_to_webp', $request->boolean('convert_to_webp'), 'boolean', 'images');
        SiteSetting::setValue('max_image_width', $validated['max_image_width'], 'integer', 'images');
        SiteSetting::setValue('max_image_height', $validated['max_image_height'], 'integer', 'images');

        return redirect()->route('admin.site-settings.index', ['tab' => 'images'])
            ->with('success', __('messages.image_settings_updated'));
    }

    /**
     * Change admin password.
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->getAuthPassword())) {
            return back()->withErrors(['current_password' => __('messages.current_password_incorrect')])->with('tab', 'password');
        }

        // Check if this is a BootstrapUser or an Eloquent User
        if ($user instanceof \App\Auth\BootstrapUser) {
            // For bootstrap users, store the new hash in site_settings
            $newHash = Hash::make($validated['new_password']);
            SiteSetting::setValue('admin_password_hash', $newHash, 'string', 'security');

            return redirect()->route('admin.site-settings.index', ['tab' => 'password'])
                ->with('success', __('messages.password_changed_successfully'));
        }

        // For Eloquent users (admin/employees)
        if ($user instanceof \App\Models\User) {
            $user->password = $validated['new_password'];
            $user->save();

            return redirect()->route('admin.site-settings.index', ['tab' => 'password'])
                ->with('success', __('messages.password_changed_successfully'));
        }

        return back()->withErrors(['current_password' => __('messages.password_change_failed')])->with('tab', 'password');
    }

    /**
     * Update social media links.
     */
    public function updateSocialLinks(Request $request)
    {
        $links = [];
        $platforms  = $request->input('platform', []);
        $labels     = $request->input('label', []);
        $icons      = $request->input('icon', []);
        $urls       = $request->input('url', []);
        $visibles   = $request->input('visible', []);

        foreach ($platforms as $i => $platform) {
            if (empty($platform)) continue;
            $links[] = [
                'platform' => $platform,
                'label'    => $labels[$i] ?? $platform,
                'icon'     => $icons[$i] ?? 'fab fa-link',
                'url'      => $urls[$i] ?? '',
                'visible'  => ($visibles[$i] ?? '0') === '1',
            ];
        }

        SiteSetting::setValue('social_links', $links, 'json', 'social');
        Cache::forget('site_setting.social_links');

        return redirect()->route('admin.site-settings.index', ['tab' => 'social-links'])
            ->with('success', __('messages.social_links_updated'));
    }

    /**
     * Update privacy policy content.
     */
    public function updatePrivacyPolicy(Request $request)
    {
        $validated = $request->validate([
            'privacy_policy_en' => 'nullable|string|max:65000',
            'privacy_policy_ar' => 'nullable|string|max:65000',
            'privacy_policy_he' => 'nullable|string|max:65000',
        ]);

        SiteSetting::setValue('privacy_policy_en', $validated['privacy_policy_en'] ?? '', 'text', 'policies');
        SiteSetting::setValue('privacy_policy_ar', $validated['privacy_policy_ar'] ?? '', 'text', 'policies');
        SiteSetting::setValue('privacy_policy_he', $validated['privacy_policy_he'] ?? '', 'text', 'policies');

        return redirect()->route('admin.site-settings.index', ['tab' => 'privacy-policy'])
            ->with('success', __('messages.privacy_policy_updated'));
    }

    /**
     * Update refund policy content.
     */
    public function updateRefundPolicy(Request $request)
    {
        $validated = $request->validate([
            'refund_policy_en' => 'nullable|string|max:65000',
            'refund_policy_ar' => 'nullable|string|max:65000',
            'refund_policy_he' => 'nullable|string|max:65000',
        ]);

        SiteSetting::setValue('refund_policy_en', $validated['refund_policy_en'] ?? '', 'text', 'policies');
        SiteSetting::setValue('refund_policy_ar', $validated['refund_policy_ar'] ?? '', 'text', 'policies');
        SiteSetting::setValue('refund_policy_he', $validated['refund_policy_he'] ?? '', 'text', 'policies');

        return redirect()->route('admin.site-settings.index', ['tab' => 'refund-policy'])
            ->with('success', __('messages.refund_policy_updated'));
    }
}
