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
     * Handles /category/{parentSlug}/{childSlug?}/{subChildSlug?} routes
     *
     * @param Request $request
     * @param string $parentSlug
     * @param string|null $childSlug
     * @param string|null $subChildSlug
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $parentSlug, ?string $childSlug = null, ?string $subChildSlug = null)
    {
        // Load category (parent, child, or sub-child)
        $category = $this->loadCategory($parentSlug, $childSlug, $subChildSlug);

        // Get category IDs including all descendants for product fetching
        $categoryIds = $this->getCategoryWithDescendantIds($category);

        // Build product query with category IDs already applied
        $query = Product::with(['category', 'brand', 'images'])
            ->active();

        // Apply filters using ProductFilterService (pass category IDs array for multi-category filtering)
        $query = $this->filterService->applyFilters($query, $request, $categoryIds);

        // Sorting — whitelist columns to prevent SQL injection
        $allowedSorts = ['created_at', 'price', 'sale_price', 'name_en', 'name_ar', 'name_he', 'sales_count', 'views_count', 'stock_quantity'];
        $sortBy = in_array($request->get('sort'), $allowedSorts, true) ? $request->get('sort') : 'created_at';
        $sortOrder = $request->get('order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginate (whitelist per_page values)
        $perPage = in_array((int) $request->get('per_page'), [12, 24, 36, 48]) ? (int) $request->get('per_page') : 12;
        $products = $query->paginate($perPage);
        $products->appends($request->except('page'));

        // Get available filters (pass Category object for proper tree highlighting + descendant IDs for counts)
        $availableFilters = $this->filterService->getAvailableFilters($categoryIds, $category);

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
     * Load category based on parent, child, and sub-child slugs
     * Validates the complete hierarchy chain at each level
     *
     * @param string $parentSlug
     * @param string|null $childSlug
     * @param string|null $subChildSlug
     * @return Category
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function loadCategory(string $parentSlug, ?string $childSlug, ?string $subChildSlug = null): Category
    {
        // Always load and validate the parent category first
        $parent = Category::where('slug', $parentSlug)
            ->whereNull('parent_id')
            ->active()
            ->firstOrFail();

        // If no child slug, return the parent category
        if (!$childSlug) {
            return $parent;
        }

        // Load and validate the child category
        $child = Category::where('slug', $childSlug)
            ->where('parent_id', $parent->id)
            ->active()
            ->firstOrFail();

        // If no sub-child slug, return the child category
        if (!$subChildSlug) {
            return $child;
        }

        // Load and validate the sub-child category
        return Category::where('slug', $subChildSlug)
            ->where('parent_id', $child->id)
            ->active()
            ->firstOrFail();
    }

    /**
     * Get all descendant category IDs for product fetching
     * Returns array of category IDs including the category itself and all descendants
     *
     * @param Category $category
     * @return array<int> Array of category IDs
     */
    protected function getCategoryWithDescendantIds(Category $category): array
    {
        $ids = [$category->id];
        
        foreach ($category->descendants() as $descendant) {
            $ids[] = $descendant->id;
        }
        
        return $ids;
    }

    /**
     * Build breadcrumb navigation array for 3-level hierarchy
     * Uses the category's ancestors() method to build the complete trail
     *
     * @param Category $category
     * @return array<array{name: string, url: string|null}>
     */
    protected function buildBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [
            ['name' => __('messages.home'), 'url' => route('home')],
        ];

        // Get all ancestors (ordered from root to immediate parent)
        $ancestors = $category->ancestors();
        $slugPath = [];

        // Add each ancestor to breadcrumbs with proper URL
        foreach ($ancestors as $ancestor) {
            $slugPath[] = $ancestor->slug;
            $breadcrumbs[] = [
                'name' => $ancestor->name,
                'url' => route('category.show', $slugPath),
            ];
        }

        // Add current category (no URL since it's the current page)
        $breadcrumbs[] = [
            'name' => $category->name,
            'url' => null,
        ];

        return $breadcrumbs;
    }
}
