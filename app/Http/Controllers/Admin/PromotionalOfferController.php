<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalOffer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PromotionalOfferController extends Controller
{
    public function index()
    {
        $offers = PromotionalOffer::with('product')->latest()->paginate(10);
        return view('admin.promotional-offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::active()->get();
        return view('admin.promotional-offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'title_he' => 'nullable|string|max:255',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'features_en' => 'nullable|string',
            'features_ar' => 'nullable|string',
            'features_he' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ]);

        $validated['discount_amount'] = $validated['original_price'] - $validated['sale_price'];
        $validated['discount_percentage'] = round(($validated['discount_amount'] / $validated['original_price']) * 100);

        if ($request->features_en) {
            $validated['features_en'] = json_encode(array_filter(explode("\n", $request->features_en)));
        }
        if ($request->features_ar) {
            $validated['features_ar'] = json_encode(array_filter(explode("\n", $request->features_ar)));
        }
        if ($request->features_he) {
            $validated['features_he'] = json_encode(array_filter(explode("\n", $request->features_he)));
        }

        PromotionalOffer::create($validated);

        // Clear home page cache
        $this->clearHomeCache();

        return redirect()->route('admin.promotional-offers.index')
            ->with('success', __('messages.offer_created_successfully'));
    }

    public function edit(PromotionalOffer $promotionalOffer)
    {
        $products = Product::active()->get();
        return view('admin.promotional-offers.edit', compact('promotionalOffer', 'products'));
    }

    public function update(Request $request, PromotionalOffer $promotionalOffer)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'title_he' => 'nullable|string|max:255',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'features_en' => 'nullable|string',
            'features_ar' => 'nullable|string',
            'features_he' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ]);

        $validated['discount_amount'] = $validated['original_price'] - $validated['sale_price'];
        $validated['discount_percentage'] = round(($validated['discount_amount'] / $validated['original_price']) * 100);

        if ($request->features_en) {
            $validated['features_en'] = json_encode(array_filter(explode("\n", $request->features_en)));
        }
        if ($request->features_ar) {
            $validated['features_ar'] = json_encode(array_filter(explode("\n", $request->features_ar)));
        }
        if ($request->features_he) {
            $validated['features_he'] = json_encode(array_filter(explode("\n", $request->features_he)));
        }

        $promotionalOffer->update($validated);

        // Clear home page cache
        $this->clearHomeCache();

        return redirect()->route('admin.promotional-offers.index')
            ->with('success', __('messages.offer_updated_successfully'));
    }

    public function destroy(PromotionalOffer $promotionalOffer)
    {
        $promotionalOffer->delete();

        // Clear home page cache
        $this->clearHomeCache();

        return redirect()->route('admin.promotional-offers.index')
            ->with('success', __('messages.offer_deleted_successfully'));
    }

    public function toggleActive(PromotionalOffer $promotionalOffer)
    {
        $promotionalOffer->update(['is_active' => !$promotionalOffer->is_active]);

        // Clear home page cache
        $this->clearHomeCache();

        return response()->json([
            'success' => true,
            'message' => __('messages.offer_status_toggled'),
            'is_active' => $promotionalOffer->is_active
        ]);
    }

    /**
     * Clear home page cache for all locales
     */
    private function clearHomeCache()
    {
        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_he');
    }
}
