<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingBlockedRange;
use App\Models\ShippingCity;
use App\Models\ShippingRegion;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShippingManagementController extends Controller
{
    // ==================== INDEX (Dashboard) ====================
    public function index()
    {
        $regions = ShippingRegion::with('cities')->ordered()->get();
        $blockedRanges = ShippingBlockedRange::orderBy('postal_code_min')->get();
        $settings = ShippingSetting::all()->keyBy('key');

        $stats = [
            'total_regions' => $regions->count(),
            'active_regions' => $regions->where('is_active', true)->count(),
            'total_cities' => ShippingCity::count(),
            'active_cities' => ShippingCity::where('is_active', true)->count(),
            'blocked_ranges' => $blockedRanges->where('is_active', true)->count(),
        ];

        return view('admin.shipping.index', compact('regions', 'blockedRanges', 'settings', 'stats'));
    }

    // ==================== REGIONS ====================
    public function storeRegion(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:50|unique:shipping_regions,key|regex:/^[a-z0-9_]+$/',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ShippingRegion::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_region_created'));
    }

    public function updateRegion(Request $request, ShippingRegion $region)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('shipping_regions', 'key')->ignore($region->id)],
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $region->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_region_updated'));
    }

    public function destroyRegion(ShippingRegion $region)
    {
        if ($region->cities()->count() > 0) {
            return redirect()->route('admin.shipping.index')
                ->with('error', __('messages.shipping_region_has_cities'));
        }

        $region->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_region_deleted'));
    }

    // ==================== CITIES ====================
    public function storeCity(Request $request)
    {
        $validated = $request->validate([
            'shipping_region_id' => 'required|exists:shipping_regions,id',
            'key' => 'required|string|max:50|unique:shipping_cities,key|regex:/^[a-z0-9_]+$/',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'governorate_en' => 'required|string|max:255',
            'governorate_ar' => 'required|string|max:255',
            'governorate_he' => 'required|string|max:255',
            'postal_code_min' => 'required|integer|min:0|max:999',
            'postal_code_max' => 'required|integer|min:0|max:999|gte:postal_code_min',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Check for overlapping ranges within the same region
        $overlap = ShippingCity::where('shipping_region_id', $validated['shipping_region_id'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('postal_code_min', [$validated['postal_code_min'], $validated['postal_code_max']])
                  ->orWhereBetween('postal_code_max', [$validated['postal_code_min'], $validated['postal_code_max']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('postal_code_min', '<=', $validated['postal_code_min'])
                         ->where('postal_code_max', '>=', $validated['postal_code_max']);
                  });
            })
            ->exists();

        if ($overlap) {
            return redirect()->route('admin.shipping.index')
                ->with('error', __('messages.shipping_postal_range_overlap'))
                ->withInput();
        }

        ShippingCity::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_city_created'));
    }

    public function updateCity(Request $request, ShippingCity $city)
    {
        $validated = $request->validate([
            'shipping_region_id' => 'required|exists:shipping_regions,id',
            'key' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('shipping_cities', 'key')->ignore($city->id)],
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'governorate_en' => 'required|string|max:255',
            'governorate_ar' => 'required|string|max:255',
            'governorate_he' => 'required|string|max:255',
            'postal_code_min' => 'required|integer|min:0|max:999',
            'postal_code_max' => 'required|integer|min:0|max:999|gte:postal_code_min',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Check for overlapping ranges (excluding self)
        $overlap = ShippingCity::where('shipping_region_id', $validated['shipping_region_id'])
            ->where('id', '!=', $city->id)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('postal_code_min', [$validated['postal_code_min'], $validated['postal_code_max']])
                  ->orWhereBetween('postal_code_max', [$validated['postal_code_min'], $validated['postal_code_max']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('postal_code_min', '<=', $validated['postal_code_min'])
                         ->where('postal_code_max', '>=', $validated['postal_code_max']);
                  });
            })
            ->exists();

        if ($overlap) {
            return redirect()->route('admin.shipping.index')
                ->with('error', __('messages.shipping_postal_range_overlap'))
                ->withInput();
        }

        $city->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_city_updated'));
    }

    public function destroyCity(ShippingCity $city)
    {
        $city->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_city_deleted'));
    }

    // ==================== BLOCKED RANGES ====================
    public function storeBlockedRange(Request $request)
    {
        $validated = $request->validate([
            'postal_code_min' => 'required|integer|min:0|max:999',
            'postal_code_max' => 'required|integer|min:0|max:999|gte:postal_code_min',
            'label_en' => 'required|string|max:255',
            'label_ar' => 'required|string|max:255',
            'label_he' => 'required|string|max:255',
            'reason_en' => 'nullable|string|max:500',
            'reason_ar' => 'nullable|string|max:500',
            'reason_he' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ShippingBlockedRange::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_blocked_range_created'));
    }

    public function updateBlockedRange(Request $request, ShippingBlockedRange $blockedRange)
    {
        $validated = $request->validate([
            'postal_code_min' => 'required|integer|min:0|max:999',
            'postal_code_max' => 'required|integer|min:0|max:999|gte:postal_code_min',
            'label_en' => 'required|string|max:255',
            'label_ar' => 'required|string|max:255',
            'label_he' => 'required|string|max:255',
            'reason_en' => 'nullable|string|max:500',
            'reason_ar' => 'nullable|string|max:500',
            'reason_he' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $blockedRange->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_blocked_range_updated'));
    }

    public function destroyBlockedRange(ShippingBlockedRange $blockedRange)
    {
        $blockedRange->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_blocked_range_deleted'));
    }

    // ==================== SETTINGS ====================
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'shipping_country' => 'required|string|max:100',
            'postal_code_digits' => 'required|integer|min:1|max:10',
            'free_shipping_threshold' => 'required|integer|min:0',
            'shipping_fee' => 'required|integer|min:0',
            'shipping_enabled' => 'boolean',
        ]);

        ShippingSetting::setValue('shipping_country', $validated['shipping_country']);
        ShippingSetting::setValue('postal_code_digits', $validated['postal_code_digits'], 'integer');
        ShippingSetting::setValue('free_shipping_threshold', $validated['free_shipping_threshold'], 'integer');
        ShippingSetting::setValue('shipping_fee', $validated['shipping_fee'], 'integer');
        ShippingSetting::setValue('shipping_enabled', $request->has('shipping_enabled') ? '1' : '0', 'boolean');

        return redirect()->route('admin.shipping.index')
            ->with('success', __('messages.shipping_settings_updated'));
    }

    // ==================== TOGGLE STATUS (AJAX) ====================
    public function toggleRegionStatus(ShippingRegion $region)
    {
        $region->update(['is_active' => !$region->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $region->is_active,
            'message' => $region->is_active
                ? __('messages.shipping_region_activated')
                : __('messages.shipping_region_deactivated'),
        ]);
    }

    public function toggleCityStatus(ShippingCity $city)
    {
        $city->update(['is_active' => !$city->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $city->is_active,
            'message' => $city->is_active
                ? __('messages.shipping_city_activated')
                : __('messages.shipping_city_deactivated'),
        ]);
    }

    public function toggleBlockedRangeStatus(ShippingBlockedRange $blockedRange)
    {
        $blockedRange->update(['is_active' => !$blockedRange->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $blockedRange->is_active,
            'message' => $blockedRange->is_active
                ? __('messages.shipping_blocked_range_activated')
                : __('messages.shipping_blocked_range_deactivated'),
        ]);
    }
}
