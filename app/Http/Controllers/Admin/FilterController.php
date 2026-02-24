<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Category;
use App\Models\Filter;
use App\Models\FilterAssignment;
use App\Models\FilterOption;
use App\Models\FilterSectionSetting;
use App\Services\FilterResolutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilterController extends Controller
{
    protected FilterResolutionService $resolutionService;

    public function __construct(FilterResolutionService $resolutionService)
    {
        $this->resolutionService = $resolutionService;
    }

    /**
     * List all filters with search and stats.
     */
    public function index(Request $request)
    {
        $query = Filter::query()
            ->withCount(['options', 'assignments']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_he', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Stats
        $totalFilters = Filter::count();
        $activeFilters = Filter::where('is_active', true)->count();
        $inactiveFilters = $totalFilters - $activeFilters;

        $filters = $query->ordered()->paginate(20)->appends($request->except('page'));

        // Section settings for built-in filters (Status, Brand, Strong Offers, Price)
        $sectionSettings = FilterSectionSetting::orderBy('sort_order')->get()->keyBy('section_key');
        if ($sectionSettings->isEmpty()) {
            $defaults = [['status', 0], ['strong_offers', 1], ['brand', 2], ['price', 3]];
            foreach ($defaults as $idx => $item) {
                FilterSectionSetting::create([
                    'section_key' => $item[0],
                    'is_enabled' => true,
                    'sort_order' => $item[1],
                ]);
            }
            $sectionSettings = FilterSectionSetting::orderBy('sort_order')->get()->keyBy('section_key');
        }

        return view('admin.filters.index', compact(
            'filters', 'totalFilters', 'activeFilters', 'inactiveFilters', 'sectionSettings'
        ));
    }

    /**
     * Update built-in filter section settings (Status, Brand, Strong Offers, Price).
     */
    public function updateSectionSettings(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.key' => 'required|string|in:status,brand,strong_offers,price',
            'sections.*.enabled' => 'required|boolean',
            'sections.*.sort_order' => 'required|integer|min:0|max:999',
        ]);

        foreach ($validated['sections'] as $idx => $section) {
            FilterSectionSetting::updateOrCreate(
                ['section_key' => $section['key']],
                ['is_enabled' => $section['enabled'], 'sort_order' => $section['sort_order']]
            );
        }

        FilterSectionSetting::clearCache();

        return redirect()->route('admin.filters.index')
            ->with('success', __('messages.filter_section_settings_updated'));
    }

    /**
     * Show create filter form.
     */
    public function create()
    {
        $categories = $this->getCategoryTree();
        return view('admin.filters.create', compact('categories'));
    }

    /**
     * Store a new filter with options and assignments.
     */
    public function store(FilterRequest $request)
    {
        $validated = $request->validated();

        // Generate slug
        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title_en']);

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (Filter::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $filter = Filter::create([
            'title_en'       => $validated['title_en'],
            'title_ar'       => $validated['title_ar'] ?? null,
            'title_he'       => $validated['title_he'] ?? null,
            'slug'           => $slug,
            'description_en' => $validated['description_en'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_he' => $validated['description_he'] ?? null,
            'type'           => $validated['type'],
            'sort_order'     => $validated['sort_order'] ?? 0,
            'is_active'      => $request->input('is_active') == '1',
        ]);

        // Save options (for option-based types)
        $this->syncOptions($filter, $validated['options'] ?? []);

        // Save category assignments
        $this->syncAssignments($filter, $validated['categories'] ?? []);

        // Invalidate filter cache
        $this->resolutionService->invalidateAllCaches();

        return redirect()->route('admin.filters.index')
            ->with('success', __('messages.filter_created'));
    }

    /**
     * Show edit filter form.
     */
    public function edit(Filter $filter)
    {
        $filter->load(['options' => fn($q) => $q->orderBy('sort_order'), 'assignments.category']);
        $categories = $this->getCategoryTree();
        return view('admin.filters.edit', compact('filter', 'categories'));
    }

    /**
     * Update an existing filter.
     */
    public function update(FilterRequest $request, Filter $filter)
    {
        $validated = $request->validated();

        // Handle slug
        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title_en']);

        $originalSlug = $slug;
        $counter = 1;
        while (Filter::where('slug', $slug)->where('id', '!=', $filter->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $filter->update([
            'title_en'       => $validated['title_en'],
            'title_ar'       => $validated['title_ar'] ?? null,
            'title_he'       => $validated['title_he'] ?? null,
            'slug'           => $slug,
            'description_en' => $validated['description_en'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_he' => $validated['description_he'] ?? null,
            'type'           => $validated['type'],
            'sort_order'     => $validated['sort_order'] ?? 0,
            'is_active'      => $request->input('is_active') == '1',
        ]);

        // Sync options
        $this->syncOptions($filter, $validated['options'] ?? []);

        // Sync assignments
        $this->syncAssignments($filter, $validated['categories'] ?? []);

        // Invalidate cache
        $this->resolutionService->invalidateAllCaches();

        return redirect()->route('admin.filters.index')
            ->with('success', __('messages.filter_updated'));
    }

    /**
     * Delete a filter.
     */
    public function destroy(Filter $filter)
    {
        $filter->delete();

        $this->resolutionService->invalidateAllCaches();

        return redirect()->route('admin.filters.index')
            ->with('success', __('messages.filter_deleted'));
    }

    /**
     * Toggle filter status (AJAX).
     */
    public function toggleStatus(Filter $filter)
    {
        $filter->update(['is_active' => !$filter->is_active]);

        $this->resolutionService->invalidateAllCaches();

        return response()->json([
            'success' => true,
            'is_active' => $filter->is_active,
        ]);
    }

    /**
     * Get filters for a category (AJAX, used in product forms).
     */
    public function getCategoryFilters(int $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $filters = $this->resolutionService->getFiltersForCategory($category);

        $result = $filters->map(function ($filter) {
            return [
                'id'      => $filter->id,
                'title'   => $filter->title,
                'slug'    => $filter->slug,
                'type'    => $filter->type,
                'options' => $filter->activeOptions->map(function ($opt) {
                    return [
                        'id'         => $opt->id,
                        'label'      => $opt->label,
                        'value_slug' => $opt->value_slug,
                        'color_code' => $opt->color_code,
                    ];
                }),
            ];
        });

        return response()->json($result);
    }

    // ── Private Helpers ──────────────────────────────────

    /**
     * Sync filter options (create/update/delete).
     */
    protected function syncOptions(Filter $filter, array $options): void
    {
        $existingIds = $filter->options()->pluck('id')->toArray();
        $incomingIds = [];

        foreach ($options as $index => $optionData) {
            $optionId = $optionData['id'] ?? null;

            $data = [
                'label_en'   => $optionData['label_en'],
                'label_ar'   => $optionData['label_ar'] ?? null,
                'label_he'   => $optionData['label_he'] ?? null,
                'value_slug' => Str::slug($optionData['value_slug'] ?: $optionData['label_en']),
                'color_code' => $optionData['color_code'] ?? null,
                'icon'        => $optionData['icon'] ?? null,
                'sort_order' => $optionData['sort_order'] ?? $index,
                'is_active'  => isset($optionData['is_active']) ? $optionData['is_active'] == '1' : true,
            ];

            if ($optionId && in_array($optionId, $existingIds)) {
                // Update existing
                FilterOption::where('id', $optionId)->update($data);
                $incomingIds[] = (int) $optionId;
            } else {
                // Create new
                $newOption = $filter->options()->create($data);
                $incomingIds[] = $newOption->id;
            }
        }

        // Delete removed options
        $toDelete = array_diff($existingIds, $incomingIds);
        if (!empty($toDelete)) {
            FilterOption::whereIn('id', $toDelete)->delete();
        }
    }

    /**
     * Sync category assignments.
     */
    protected function syncAssignments(Filter $filter, array $categories): void
    {
        // Delete all existing
        $filter->assignments()->delete();

        foreach ($categories as $catData) {
            if (empty($catData['category_id'])) continue;

            FilterAssignment::create([
                'filter_id'           => $filter->id,
                'category_id'         => $catData['category_id'],
                'inherit_to_children' => isset($catData['inherit_to_children']) && $catData['inherit_to_children'] == '1',
            ]);
        }
    }

    /**
     * Build a flat category tree for dropdown selectors.
     */
    protected function getCategoryTree(): array
    {
        $locale = app()->getLocale();
        $nameCol = "name_{$locale}";

        $categories = Category::active()
            ->withTrashed()
            ->whereNull('deleted_at')
            ->orderBy($nameCol)
            ->get();

        $tree = [];
        $roots = $categories->whereNull('parent_id');

        foreach ($roots as $root) {
            $tree[] = ['id' => $root->id, 'name' => $root->name, 'depth' => 0];
            $this->addChildrenToTree($tree, $categories, $root->id, 1);
        }

        return $tree;
    }

    protected function addChildrenToTree(array &$tree, $categories, int $parentId, int $depth): void
    {
        $children = $categories->where('parent_id', $parentId);
        foreach ($children as $child) {
            $tree[] = ['id' => $child->id, 'name' => str_repeat('— ', $depth) . $child->name, 'depth' => $depth];
            $this->addChildrenToTree($tree, $categories, $child->id, $depth + 1);
        }
    }
}
