<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('category')->orderBy('section')->orderBy('display_order')->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name_en')->get();
        return view('admin.banners.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'title_he' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_he' => 'nullable|string',
            'image_url' => 'required|url',
            'button_text_en' => 'nullable|string|max:255',
            'button_text_ar' => 'nullable|string|max:255',
            'button_text_he' => 'nullable|string|max:255',
            'link_type' => 'required|in:external,products,categories,category',
            'link_url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'section' => 'required|in:strong_offers,gift_ideas,hero',
        ]);

        // Handle filter options
        $filterOptions = [];
        if ($request->has('filter_featured')) {
            $filterOptions['featured'] = '1';
        }
        if ($request->has('filter_new')) {
            $filterOptions['new'] = '1';
        }
        if ($request->has('filter_bestseller')) {
            $filterOptions['bestseller'] = '1';
        }
        if ($request->has('filter_special_offer')) {
            $filterOptions['special_offer'] = '1';
        }
        
        $validated['filter_options'] = !empty($filterOptions) ? $filterOptions : null;
        $validated['is_active'] = $request->has('is_active');

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_created_successfully'));
    }

    public function edit(Banner $banner)
    {
        $categories = Category::active()->orderBy('name_en')->get();
        return view('admin.banners.edit', compact('banner', 'categories'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'title_he' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_he' => 'nullable|string',
            'image_url' => 'required|url',
            'button_text_en' => 'nullable|string|max:255',
            'button_text_ar' => 'nullable|string|max:255',
            'button_text_he' => 'nullable|string|max:255',
            'link_type' => 'required|in:external,products,categories,category',
            'link_url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'section' => 'required|in:strong_offers,gift_ideas,hero',
        ]);

        // Handle filter options
        $filterOptions = [];
        if ($request->has('filter_featured')) {
            $filterOptions['featured'] = '1';
        }
        if ($request->has('filter_new')) {
            $filterOptions['new'] = '1';
        }
        if ($request->has('filter_bestseller')) {
            $filterOptions['bestseller'] = '1';
        }
        if ($request->has('filter_special_offer')) {
            $filterOptions['special_offer'] = '1';
        }
        
        $validated['filter_options'] = !empty($filterOptions) ? $filterOptions : null;
        $validated['is_active'] = $request->has('is_active');

        $banner->update($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_updated_successfully'));
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_deleted_successfully'));
    }
}
