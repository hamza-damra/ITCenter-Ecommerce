<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductFilterService;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class CategoryController extends Controller
{
    protected ProductFilterService $filterService;

    public function __construct(ProductFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name_en')
            ->get();
            
        return view('categories', compact('categories'));
    }

    /**
     * Show category or sub-category products with filtering
     * Handles both /category/{slug} and /category/{parent}/{child} routes
     *
     * @param Request $request
     * @param string $parentSlug
     * @param string|null $childSlug
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $parentSlug, ?string $childSlug = null)
    {
        // Load category (parent or child)
        $category = $this->loadCategory($parentSlug, $childSlug);

        // Build product query
        $query = Product::with(['category', 'brand', 'images'])
            ->active()
            ->where('category_id', $category->id);

        // Apply filters using ProductFilterService
        $query = $this->filterService->applyFilters($query, $request, $category);

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $products = $query->paginate($request->get('per_page', 12));
        $products->appends($request->except('page'));

        // Get available filters for this category
        $availableFilters = $this->filterService->getAvailableFilters($category);

        // Build breadcrumb data
        $breadcrumbs = $this->buildBreadcrumbs($category);

        return view('category-products', compact(
            'category',
            'products',
            'availableFilters',
            'breadcrumbs'
        ));
    }

    /**
     * Load category based on parent and child slugs
     *
     * @param string $parentSlug
     * @param string|null $childSlug
     * @return Category
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function loadCategory(string $parentSlug, ?string $childSlug): Category
    {
        if ($childSlug) {
            // Load sub-category
            $parent = Category::where('slug', $parentSlug)
                ->active()
                ->firstOrFail();
            
            return Category::where('slug', $childSlug)
                ->where('parent_id', $parent->id)
                ->active()
                ->firstOrFail();
        }

        // Load parent category
        return Category::where('slug', $parentSlug)
            ->whereNull('parent_id')
            ->active()
            ->firstOrFail();
    }

    /**
     * Build breadcrumb navigation array
     *
     * @param Category $category
     * @return array
     */
    protected function buildBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [
            ['name' => __('messages.home'), 'url' => route('home')],
        ];

        if ($category->parent) {
            $breadcrumbs[] = [
                'name' => $category->parent->name,
                'url' => route('category.show', ['parentSlug' => $category->parent->slug]),
            ];
        }

        $breadcrumbs[] = [
            'name' => $category->name,
            'url' => null, // Current page
        ];

        return $breadcrumbs;
    }
}
