<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class BannerImageController extends Controller
{
    /**
     * Serve banner image.
     * Redirects to the banner's image URL (file storage or fallback).
     * Kept for backward compatibility with old database-stored image routes.
     */
    public function show(Banner $banner)
    {
        return redirect($banner->image_url);
    }
}

