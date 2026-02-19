<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;

class PolicyController extends Controller
{
    public function privacyPolicy()
    {
        $locale = app()->getLocale();
        $content = SiteSetting::getValue("privacy_policy_{$locale}", '');

        return view('privacy-policy', compact('content'));
    }

    public function refundPolicy()
    {
        $locale = app()->getLocale();
        $content = SiteSetting::getValue("refund_policy_{$locale}", '');

        return view('refund-policy', compact('content'));
    }
}
