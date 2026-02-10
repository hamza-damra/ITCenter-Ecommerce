<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Services\HomeCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class HomeSectionController extends Controller
{
    /**
     * Display a listing of all home sections.
     */
    public function index()
    {
        $sections = HomeSection::ordered()->get();

        $stats = [
            'total' => $sections->count(),
            'active' => $sections->where('is_active', true)->count(),
            'inactive' => $sections->where('is_active', false)->count(),
        ];

        return view('admin.home-sections.index', compact('sections', 'stats'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        $maxOrder = HomeSection::max('display_order') ?? 0;
        $nextOrder = max($maxOrder + 1, 1);

        return view('admin.home-sections.create', compact('maxOrder', 'nextOrder'));
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:120',
            'title_ar' => 'nullable|string|max:120',
            'title_he' => 'nullable|string|max:120',
            'subtitle_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'subtitle_he' => 'nullable|string|max:255',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
            'settings.max_products' => 'nullable|integer|min:1|max:50',
            'settings.auto_scroll' => 'nullable|boolean',
            'settings.auto_scroll_interval' => 'nullable|integer|min:1000|max:30000',
            'settings.cards_to_scroll' => 'nullable|integer|min:1|max:10',
            'settings.background_color' => 'nullable|string|max:20',
        ]);

        $validated['type'] = HomeSection::TYPE_CUSTOM_PRODUCT_SECTION;
        $validated['is_active'] = $request->has('is_active');

        // Clean null settings
        if (isset($validated['settings'])) {
            $validated['settings'] = array_filter($validated['settings'], fn($v) => $v !== null && $v !== '');
        }

        // Shift existing sections at this order or higher up by 1
        HomeSection::where('display_order', '>=', $validated['display_order'])
            ->orderBy('display_order', 'desc')
            ->each(function ($s) {
                $s->update(['display_order' => $s->display_order + 1]);
            });

        $section = HomeSection::create($validated);

        // Clear home page cache
        HomeCacheService::clearAll();

        Log::info('Home section created', ['id' => $section->id, 'type' => $section->type]);

        return redirect()->route('admin.home-sections.index')
            ->with('success', __('messages.home_section_created'));
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(HomeSection $home_section)
    {
        // Only built-in sections (hero_banner, category_carousel) cannot be edited
        if (in_array($home_section->type, [HomeSection::TYPE_HERO_BANNER, HomeSection::TYPE_CATEGORY_CAROUSEL])) {
            return redirect()->route('admin.home-sections.index');
        }

        return view('admin.home-sections.edit', [
            'section' => $home_section,
        ]);
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, HomeSection $home_section)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:120',
            'title_ar' => 'nullable|string|max:120',
            'title_he' => 'nullable|string|max:120',
            'subtitle_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'subtitle_he' => 'nullable|string|max:255',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
            'settings.max_products' => 'nullable|integer|min:1|max:50',
            'settings.auto_scroll' => 'nullable|boolean',
            'settings.auto_scroll_interval' => 'nullable|integer|min:1000|max:30000',
            'settings.cards_to_scroll' => 'nullable|integer|min:1|max:10',
            'settings.background_color' => 'nullable|string|max:20',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Clean null settings
        if (isset($validated['settings'])) {
            $validated['settings'] = array_filter($validated['settings'], fn($v) => $v !== null && $v !== '');
        }

        $newOrder = (int) $validated['display_order'];
        $oldOrder = (int) $home_section->display_order;

        // Shift other sections if order changed
        if ($newOrder !== $oldOrder) {
            if ($newOrder < $oldOrder) {
                // Moving up: shift sections in [newOrder, oldOrder-1] down by 1
                HomeSection::where('id', '!=', $home_section->id)
                    ->whereBetween('display_order', [$newOrder, $oldOrder - 1])
                    ->orderBy('display_order', 'desc')
                    ->each(function ($s) {
                        $s->update(['display_order' => $s->display_order + 1]);
                    });
            } else {
                // Moving down: shift sections in [oldOrder+1, newOrder] up by 1
                HomeSection::where('id', '!=', $home_section->id)
                    ->whereBetween('display_order', [$oldOrder + 1, $newOrder])
                    ->orderBy('display_order', 'asc')
                    ->each(function ($s) {
                        $s->update(['display_order' => $s->display_order - 1]);
                    });
            }
        }

        $home_section->update($validated);

        // Clear home page cache
        HomeCacheService::clearAll();

        Log::info('Home section updated', ['id' => $home_section->id, 'type' => $home_section->type]);

        return redirect()->route('admin.home-sections.index')
            ->with('success', __('messages.home_section_updated'));
    }

    /**
     * Remove the specified section.
     */
    public function destroy(HomeSection $home_section)
    {
        $type = $home_section->type;
        $deletedOrder = $home_section->display_order;
        $home_section->delete();

        // Shift sections above the deleted one down by 1
        HomeSection::where('display_order', '>', $deletedOrder)
            ->orderBy('display_order', 'asc')
            ->each(function ($s) {
                $s->update(['display_order' => $s->display_order - 1]);
            });

        // Clear home page cache
        HomeCacheService::clearAll();

        Log::info('Home section deleted', ['type' => $type]);

        return redirect()->route('admin.home-sections.index')
            ->with('success', __('messages.home_section_deleted'));
    }

    /**
     * Reorder sections via AJAX.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:home_sections,id',
        ]);

        // Hero Banner always stays at 0
        $heroBanner = HomeSection::where('type', HomeSection::TYPE_HERO_BANNER)->first();
        $heroBannerId = $heroBanner ? $heroBanner->id : null;

        foreach ($request->order as $index => $id) {
            // Don't let hero banner move from 0
            if ($id == $heroBannerId) {
                HomeSection::where('id', $id)->update(['display_order' => 0]);
            } else {
                // Ensure non-banner sections start from 1
                $order = $index < 1 ? 1 : $index;
                HomeSection::where('id', $id)->update(['display_order' => $order]);
            }
        }

        // Clear home page cache
        HomeCacheService::clearAll();

        return response()->json([
            'success' => true,
            'message' => __('messages.sections_reordered'),
        ]);
    }

    /**
     * Toggle section active status via AJAX.
     */
    public function toggleActive(HomeSection $home_section)
    {
        $home_section->update(['is_active' => !$home_section->is_active]);

        // Clear home page cache
        HomeCacheService::clearAll();

        return response()->json([
            'success' => true,
            'is_active' => $home_section->is_active,
            'message' => $home_section->is_active
                ? __('messages.section_activated')
                : __('messages.section_deactivated'),
        ]);
    }
}
