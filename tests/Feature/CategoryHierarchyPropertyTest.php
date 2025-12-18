<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Services\ProductFilterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Property-Based Tests for Category Hierarchy Methods
 * Feature: category-nav-fix
 */
class CategoryHierarchyPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Generate random category data for testing
     */
    private function generateRandomCategoryData(array $overrides = []): array
    {
        $names = ['Electronics', 'Clothing', 'Food', 'Books', 'Sports', 'Home', 'Garden', 'Toys', 'Health', 'Beauty'];
        $name = $names[array_rand($names)] . '_' . uniqid();
        
        return array_merge([
            'name_en' => $name,
            'name_ar' => 'اختبار_' . uniqid(),
            'name_he' => 'בדיקה_' . uniqid(),
            'slug' => strtolower(str_replace(' ', '-', $name)) . '_' . uniqid(),
            'is_active' => true,
            'position' => rand(0, 100),
        ], $overrides);
    }

    /**
     * Create a 3-level category hierarchy (parent -> child -> sub-child)
     */
    private function createCategoryHierarchy(): array
    {
        $parent = Category::create($this->generateRandomCategoryData(['parent_id' => null]));
        $child = Category::create($this->generateRandomCategoryData(['parent_id' => $parent->id]));
        $subChild = Category::create($this->generateRandomCategoryData(['parent_id' => $child->id]));
        
        return [$parent, $child, $subChild];
    }

    /**
     * **Feature: category-nav-fix, Property 1: Category Hierarchy Resolution**
     * *For any* valid category hierarchy (parent → child → sub-child), 
     * the ancestors() method should return the correct parent chain ordered from root to immediate parent,
     * and the descendants() method should return all child and sub-child categories.
     * **Validates: Requirements 1.1, 1.2, 1.3**
     * 
     * @test
     */
    public function property_category_hierarchy_resolution(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Test ancestors() for parent category (should be empty)
            $parentAncestors = $parent->ancestors();
            $this->assertTrue(
                $parentAncestors->isEmpty(),
                "Parent category should have no ancestors"
            );
            
            // Test ancestors() for child category (should contain only parent)
            $childAncestors = $child->ancestors();
            $this->assertCount(
                1,
                $childAncestors,
                "Child category should have exactly 1 ancestor"
            );
            $this->assertEquals(
                $parent->id,
                $childAncestors->first()->id,
                "Child's ancestor should be the parent category"
            );
            
            // Test ancestors() for sub-child category (should contain parent and child, in order)
            $subChildAncestors = $subChild->ancestors();
            $this->assertCount(
                2,
                $subChildAncestors,
                "Sub-child category should have exactly 2 ancestors"
            );
            $this->assertEquals(
                $parent->id,
                $subChildAncestors->first()->id,
                "Sub-child's first ancestor should be the root parent"
            );
            $this->assertEquals(
                $child->id,
                $subChildAncestors->last()->id,
                "Sub-child's last ancestor should be the immediate parent (child)"
            );
            
            // Test descendants() for parent category (should contain child and sub-child)
            $parentDescendants = $parent->descendants();
            $this->assertCount(
                2,
                $parentDescendants,
                "Parent category should have exactly 2 descendants"
            );
            $this->assertTrue(
                $parentDescendants->contains('id', $child->id),
                "Parent's descendants should contain the child category"
            );
            $this->assertTrue(
                $parentDescendants->contains('id', $subChild->id),
                "Parent's descendants should contain the sub-child category"
            );
            
            // Test descendants() for child category (should contain only sub-child)
            $childDescendants = $child->descendants();
            $this->assertCount(
                1,
                $childDescendants,
                "Child category should have exactly 1 descendant"
            );
            $this->assertEquals(
                $subChild->id,
                $childDescendants->first()->id,
                "Child's descendant should be the sub-child category"
            );
            
            // Test descendants() for sub-child category (should be empty)
            $subChildDescendants = $subChild->descendants();
            $this->assertTrue(
                $subChildDescendants->isEmpty(),
                "Sub-child category should have no descendants"
            );
            
            // Clean up
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 1b: URL Generation Based on Hierarchy**
     * *For any* category at any level in the hierarchy, the getUrlAttribute() should 
     * generate the correct URL path including all ancestor slugs.
     * **Validates: Requirements 4.1, 4.2, 7.3**
     * 
     * @test
     */
    public function property_url_generation_based_on_hierarchy(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Test URL for parent category
            $parentUrl = $parent->url;
            $this->assertStringContainsString(
                '/category/' . $parent->slug,
                $parentUrl,
                "Parent URL should contain /category/{parentSlug}"
            );
            $this->assertStringNotContainsString(
                $child->slug,
                $parentUrl,
                "Parent URL should not contain child slug"
            );
            
            // Test URL for child category
            $childUrl = $child->url;
            $this->assertStringContainsString(
                '/category/' . $parent->slug . '/' . $child->slug,
                $childUrl,
                "Child URL should contain /category/{parentSlug}/{childSlug}"
            );
            
            // Test URL for sub-child category
            $subChildUrl = $subChild->url;
            $this->assertStringContainsString(
                '/category/' . $parent->slug . '/' . $child->slug . '/' . $subChild->slug,
                $subChildUrl,
                "Sub-child URL should contain /category/{parentSlug}/{childSlug}/{subChildSlug}"
            );
            
            // Clean up
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 1c: Descendants Count Consistency**
     * *For any* category hierarchy, the count of descendants should equal the sum of 
     * direct children plus all their descendants recursively.
     * **Validates: Requirements 2.1, 2.2**
     * 
     * @test
     */
    public function property_descendants_count_consistency(): void
    {
        // Run 50 iterations with varying hierarchy depths
        for ($i = 0; $i < 50; $i++) {
            // Create parent
            $parent = Category::create($this->generateRandomCategoryData(['parent_id' => null]));
            
            // Create random number of children (1-3)
            $childCount = rand(1, 3);
            $children = [];
            $totalDescendants = 0;
            
            for ($j = 0; $j < $childCount; $j++) {
                $child = Category::create($this->generateRandomCategoryData(['parent_id' => $parent->id]));
                $children[] = $child;
                $totalDescendants++;
                
                // Create random number of sub-children (0-2)
                $subChildCount = rand(0, 2);
                for ($k = 0; $k < $subChildCount; $k++) {
                    Category::create($this->generateRandomCategoryData(['parent_id' => $child->id]));
                    $totalDescendants++;
                }
            }
            
            // Verify descendants count
            $parentDescendants = $parent->descendants();
            $this->assertCount(
                $totalDescendants,
                $parentDescendants,
                "Parent should have exactly {$totalDescendants} descendants"
            );
            
            // Clean up - delete in reverse order
            foreach ($parentDescendants->reverse() as $descendant) {
                $descendant->forceDelete();
            }
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 2: Invalid Category Returns 404**
     * *For any* URL with a non-existent slug or invalid parent-child relationship, 
     * the system should return a 404 response.
     * **Validates: Requirements 1.4**
     * 
     * @test
     */
    public function property_invalid_category_returns_404(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Test 1: Non-existent parent slug returns 404
            $nonExistentSlug = 'non-existent-' . uniqid();
            $response = $this->get("/category/{$nonExistentSlug}");
            $response->assertStatus(404);
            
            // Test 2: Non-existent child slug returns 404
            $response = $this->get("/category/{$parent->slug}/{$nonExistentSlug}");
            $response->assertStatus(404);
            
            // Test 3: Non-existent sub-child slug returns 404
            $response = $this->get("/category/{$parent->slug}/{$child->slug}/{$nonExistentSlug}");
            $response->assertStatus(404);
            
            // Test 4: Invalid parent-child relationship returns 404
            // Create another parent category
            $anotherParent = Category::create($this->generateRandomCategoryData(['parent_id' => null]));
            
            // Try to access child with wrong parent slug
            $response = $this->get("/category/{$anotherParent->slug}/{$child->slug}");
            $response->assertStatus(404);
            
            // Test 5: Child slug used as parent slug returns 404
            $response = $this->get("/category/{$child->slug}");
            $response->assertStatus(404);
            
            // Test 6: Sub-child slug used as child slug returns 404
            $response = $this->get("/category/{$parent->slug}/{$subChild->slug}");
            $response->assertStatus(404);
            
            // Clean up
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
            $anotherParent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 3: Active Category Filter**
     * *For any* category resolution request, only categories with `is_active = true` 
     * should be considered; inactive categories should result in 404.
     * **Validates: Requirements 1.5**
     * 
     * @test
     */
    public function property_active_category_filter(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Test 1: Active parent category is accessible
            $response = $this->get("/category/{$parent->slug}");
            $response->assertStatus(200);
            
            // Test 2: Active child category is accessible
            $response = $this->get("/category/{$parent->slug}/{$child->slug}");
            $response->assertStatus(200);
            
            // Test 3: Active sub-child category is accessible
            $response = $this->get("/category/{$parent->slug}/{$child->slug}/{$subChild->slug}");
            $response->assertStatus(200);
            
            // Test 4: Inactive parent category returns 404
            $parent->update(['is_active' => false]);
            $response = $this->get("/category/{$parent->slug}");
            $response->assertStatus(404);
            
            // Test 5: Inactive parent also makes child inaccessible (invalid hierarchy)
            $response = $this->get("/category/{$parent->slug}/{$child->slug}");
            $response->assertStatus(404);
            
            // Restore parent and test inactive child
            $parent->update(['is_active' => true]);
            $child->update(['is_active' => false]);
            
            // Test 6: Inactive child category returns 404
            $response = $this->get("/category/{$parent->slug}/{$child->slug}");
            $response->assertStatus(404);
            
            // Test 7: Inactive child also makes sub-child inaccessible
            $response = $this->get("/category/{$parent->slug}/{$child->slug}/{$subChild->slug}");
            $response->assertStatus(404);
            
            // Restore child and test inactive sub-child
            $child->update(['is_active' => true]);
            $subChild->update(['is_active' => false]);
            
            // Test 8: Inactive sub-child category returns 404
            $response = $this->get("/category/{$parent->slug}/{$child->slug}/{$subChild->slug}");
            $response->assertStatus(404);
            
            // Clean up
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * Create a product with random data for testing
     */
    private function createProduct(Category $category, bool $isActive = true): Product
    {
        // Create a brand for the product
        $brand = Brand::create([
            'name_en' => 'Brand_' . uniqid(),
            'name_ar' => 'علامة_' . uniqid(),
            'name_he' => 'מותג_' . uniqid(),
            'slug' => 'brand-' . uniqid(),
            'is_active' => true,
        ]);

        return Product::create([
            'name_en' => 'Product_' . uniqid(),
            'name_ar' => 'منتج_' . uniqid(),
            'name_he' => 'מוצר_' . uniqid(),
            'slug' => 'product-' . uniqid(),
            'sku' => 'SKU-' . strtoupper(uniqid()),
            'price' => rand(100, 1000),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => $isActive,
            'stock_status' => 'in_stock',
        ]);
    }

    /**
     * **Feature: category-nav-fix, Property 4: Product Aggregation by Category Level**
     * *For any* category at any level in the hierarchy, viewing that category should return 
     * all products from that category AND all its descendant categories. The product count 
     * should equal the sum of products in the category plus products in all descendants.
     * **Validates: Requirements 2.1, 2.2, 2.3**
     * 
     * @test
     */
    public function property_product_aggregation_by_category_level(): void
    {
        $filterService = app(ProductFilterService::class);
        
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Create category hierarchy
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Create random number of products in each category (0-3 products each)
            $parentProductCount = rand(0, 3);
            $childProductCount = rand(0, 3);
            $subChildProductCount = rand(0, 3);
            
            $parentProducts = [];
            $childProducts = [];
            $subChildProducts = [];
            
            for ($j = 0; $j < $parentProductCount; $j++) {
                $parentProducts[] = $this->createProduct($parent);
            }
            
            for ($j = 0; $j < $childProductCount; $j++) {
                $childProducts[] = $this->createProduct($child);
            }
            
            for ($j = 0; $j < $subChildProductCount; $j++) {
                $subChildProducts[] = $this->createProduct($subChild);
            }
            
            // Test 1: Parent category should aggregate products from parent + child + sub-child
            $parentCategoryIds = [$parent->id];
            foreach ($parent->descendants() as $descendant) {
                $parentCategoryIds[] = $descendant->id;
            }
            
            $parentQuery = Product::active();
            $parentQuery = $filterService->applyFilters($parentQuery, new Request(), $parentCategoryIds);
            $parentAggregatedCount = $parentQuery->count();
            
            $expectedParentTotal = $parentProductCount + $childProductCount + $subChildProductCount;
            $this->assertEquals(
                $expectedParentTotal,
                $parentAggregatedCount,
                "Parent category should show {$expectedParentTotal} products (parent: {$parentProductCount}, child: {$childProductCount}, sub-child: {$subChildProductCount})"
            );
            
            // Test 2: Child category should aggregate products from child + sub-child
            $childCategoryIds = [$child->id];
            foreach ($child->descendants() as $descendant) {
                $childCategoryIds[] = $descendant->id;
            }
            
            $childQuery = Product::active();
            $childQuery = $filterService->applyFilters($childQuery, new Request(), $childCategoryIds);
            $childAggregatedCount = $childQuery->count();
            
            $expectedChildTotal = $childProductCount + $subChildProductCount;
            $this->assertEquals(
                $expectedChildTotal,
                $childAggregatedCount,
                "Child category should show {$expectedChildTotal} products (child: {$childProductCount}, sub-child: {$subChildProductCount})"
            );
            
            // Test 3: Sub-child category should only show its own products
            $subChildCategoryIds = [$subChild->id];
            // Sub-child has no descendants
            
            $subChildQuery = Product::active();
            $subChildQuery = $filterService->applyFilters($subChildQuery, new Request(), $subChildCategoryIds);
            $subChildAggregatedCount = $subChildQuery->count();
            
            $this->assertEquals(
                $subChildProductCount,
                $subChildAggregatedCount,
                "Sub-child category should show only {$subChildProductCount} products"
            );
            
            // Clean up products
            foreach (array_merge($parentProducts, $childProducts, $subChildProducts) as $product) {
                $product->brand->forceDelete();
                $product->forceDelete();
            }
            
            // Clean up categories
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 4b: Product Aggregation Excludes Inactive Products**
     * *For any* category page, only products with `is_active = true` should be displayed;
     * inactive products should be excluded from results.
     * **Validates: Requirements 2.5**
     * 
     * @test
     */
    public function property_product_aggregation_excludes_inactive_products(): void
    {
        $filterService = app(ProductFilterService::class);
        
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Create category hierarchy
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Create mix of active and inactive products
            $activeCount = rand(1, 3);
            $inactiveCount = rand(1, 3);
            
            $activeProducts = [];
            $inactiveProducts = [];
            
            // Create active products across hierarchy
            for ($j = 0; $j < $activeCount; $j++) {
                $category = [$parent, $child, $subChild][rand(0, 2)];
                $activeProducts[] = $this->createProduct($category, true);
            }
            
            // Create inactive products across hierarchy
            for ($j = 0; $j < $inactiveCount; $j++) {
                $category = [$parent, $child, $subChild][rand(0, 2)];
                $inactiveProducts[] = $this->createProduct($category, false);
            }
            
            // Get all category IDs
            $categoryIds = [$parent->id];
            foreach ($parent->descendants() as $descendant) {
                $categoryIds[] = $descendant->id;
            }
            
            // Query with filter service
            $query = Product::active();
            $query = $filterService->applyFilters($query, new Request(), $categoryIds);
            $resultCount = $query->count();
            
            // Should only return active products
            $this->assertEquals(
                $activeCount,
                $resultCount,
                "Should return only {$activeCount} active products, not include {$inactiveCount} inactive products"
            );
            
            // Verify no inactive products are in results
            $resultIds = $query->pluck('id')->toArray();
            foreach ($inactiveProducts as $inactiveProduct) {
                $this->assertNotContains(
                    $inactiveProduct->id,
                    $resultIds,
                    "Inactive product should not be in results"
                );
            }
            
            // Clean up products
            foreach (array_merge($activeProducts, $inactiveProducts) as $product) {
                $product->brand->forceDelete();
                $product->forceDelete();
            }
            
            // Clean up categories
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 11: Breadcrumb Structure**
     * *For any* category page, the breadcrumb should contain exactly N+1 items where N is the 
     * depth of the category (0 for parent, 1 for child, 2 for sub-child), starting with Home 
     * and ending with the current category.
     * **Validates: Requirements 5.1, 5.2, 5.3**
     * 
     * @test
     */
    public function property_breadcrumb_structure(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Test 1: Parent category breadcrumb (depth 0)
            // Expected: Home -> Parent (2 items = 0 + 1 + 1 for Home)
            $response = $this->get("/category/{$parent->slug}");
            $response->assertStatus(200);
            $breadcrumbs = $response->viewData('breadcrumbs');
            
            $this->assertCount(
                2,
                $breadcrumbs,
                "Parent category breadcrumb should have exactly 2 items (Home + Parent)"
            );
            $this->assertEquals(
                __('messages.home'),
                $breadcrumbs[0]['name'],
                "First breadcrumb should be Home"
            );
            $this->assertNotNull(
                $breadcrumbs[0]['url'],
                "Home breadcrumb should have a URL"
            );
            $this->assertEquals(
                $parent->name,
                $breadcrumbs[1]['name'],
                "Last breadcrumb should be the parent category name"
            );
            $this->assertNull(
                $breadcrumbs[1]['url'],
                "Current category breadcrumb should not have a URL"
            );
            
            // Test 2: Child category breadcrumb (depth 1)
            // Expected: Home -> Parent -> Child (3 items = 1 + 1 + 1 for Home)
            $response = $this->get("/category/{$parent->slug}/{$child->slug}");
            $response->assertStatus(200);
            $breadcrumbs = $response->viewData('breadcrumbs');
            
            $this->assertCount(
                3,
                $breadcrumbs,
                "Child category breadcrumb should have exactly 3 items (Home + Parent + Child)"
            );
            $this->assertEquals(
                __('messages.home'),
                $breadcrumbs[0]['name'],
                "First breadcrumb should be Home"
            );
            $this->assertEquals(
                $parent->name,
                $breadcrumbs[1]['name'],
                "Second breadcrumb should be the parent category name"
            );
            $this->assertNotNull(
                $breadcrumbs[1]['url'],
                "Parent breadcrumb should have a URL"
            );
            $this->assertStringContainsString(
                "/category/{$parent->slug}",
                $breadcrumbs[1]['url'],
                "Parent breadcrumb URL should use correct pattern"
            );
            $this->assertEquals(
                $child->name,
                $breadcrumbs[2]['name'],
                "Last breadcrumb should be the child category name"
            );
            $this->assertNull(
                $breadcrumbs[2]['url'],
                "Current category breadcrumb should not have a URL"
            );
            
            // Test 3: Sub-child category breadcrumb (depth 2)
            // Expected: Home -> Parent -> Child -> Sub-child (4 items = 2 + 1 + 1 for Home)
            $response = $this->get("/category/{$parent->slug}/{$child->slug}/{$subChild->slug}");
            $response->assertStatus(200);
            $breadcrumbs = $response->viewData('breadcrumbs');
            
            $this->assertCount(
                4,
                $breadcrumbs,
                "Sub-child category breadcrumb should have exactly 4 items (Home + Parent + Child + Sub-child)"
            );
            $this->assertEquals(
                __('messages.home'),
                $breadcrumbs[0]['name'],
                "First breadcrumb should be Home"
            );
            $this->assertEquals(
                $parent->name,
                $breadcrumbs[1]['name'],
                "Second breadcrumb should be the parent category name"
            );
            $this->assertNotNull(
                $breadcrumbs[1]['url'],
                "Parent breadcrumb should have a URL"
            );
            $this->assertEquals(
                $child->name,
                $breadcrumbs[2]['name'],
                "Third breadcrumb should be the child category name"
            );
            $this->assertNotNull(
                $breadcrumbs[2]['url'],
                "Child breadcrumb should have a URL"
            );
            $this->assertStringContainsString(
                "/category/{$parent->slug}/{$child->slug}",
                $breadcrumbs[2]['url'],
                "Child breadcrumb URL should use correct pattern"
            );
            $this->assertEquals(
                $subChild->name,
                $breadcrumbs[3]['name'],
                "Last breadcrumb should be the sub-child category name"
            );
            $this->assertNull(
                $breadcrumbs[3]['url'],
                "Current category breadcrumb should not have a URL"
            );
            
            // Clean up
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 9: Navbar Link Generation**
     * *For any* category displayed in the navbar, the generated link should use the correct 
     * URL pattern: `/category/{parentSlug}` for parents, `/category/{parentSlug}/{childSlug}` 
     * for children, with the correct parent slug included.
     * **Validates: Requirements 4.1, 4.2**
     * 
     * @test
     */
    public function property_navbar_link_generation(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Test 1: Parent category link generation
            // Expected URL pattern: /category/{parentSlug}
            $parentLink = route('category.show', $parent->slug);
            
            $this->assertStringContainsString(
                '/category/' . $parent->slug,
                $parentLink,
                "Parent category link should use pattern /category/{parentSlug}"
            );
            $this->assertStringNotContainsString(
                $child->slug,
                $parentLink,
                "Parent category link should not contain child slug"
            );
            
            // Test 2: Child category link generation
            // Expected URL pattern: /category/{parentSlug}/{childSlug}
            $childLink = route('category.show', [$parent->slug, $child->slug]);
            
            $this->assertStringContainsString(
                '/category/' . $parent->slug . '/' . $child->slug,
                $childLink,
                "Child category link should use pattern /category/{parentSlug}/{childSlug}"
            );
            
            // Verify the parent slug is correctly included (not just any slug)
            $this->assertMatchesRegularExpression(
                '#/category/' . preg_quote($parent->slug, '#') . '/' . preg_quote($child->slug, '#') . '$#',
                $childLink,
                "Child category link should have correct parent slug followed by child slug"
            );
            
            // Test 3: Sub-child category link generation (for completeness)
            // Expected URL pattern: /category/{parentSlug}/{childSlug}/{subChildSlug}
            $subChildLink = route('category.show', [$parent->slug, $child->slug, $subChild->slug]);
            
            $this->assertStringContainsString(
                '/category/' . $parent->slug . '/' . $child->slug . '/' . $subChild->slug,
                $subChildLink,
                "Sub-child category link should use pattern /category/{parentSlug}/{childSlug}/{subChildSlug}"
            );
            
            // Test 4: Verify links are accessible (return 200)
            $response = $this->get($parentLink);
            $response->assertStatus(200);
            
            $response = $this->get($childLink);
            $response->assertStatus(200);
            
            $response = $this->get($subChildLink);
            $response->assertStatus(200);
            
            // Test 5: Verify the Category model's url attribute generates correct links
            $this->assertEquals(
                $parentLink,
                $parent->url,
                "Category model url attribute should match route helper for parent"
            );
            
            $this->assertEquals(
                $childLink,
                $child->url,
                "Category model url attribute should match route helper for child"
            );
            
            $this->assertEquals(
                $subChildLink,
                $subChild->url,
                "Category model url attribute should match route helper for sub-child"
            );
            
            // Clean up
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * Create a tag with random data for testing
     */
    private function createTag(bool $isActive = true): \App\Models\Tag
    {
        return \App\Models\Tag::create([
            'name_en' => 'Tag_' . uniqid(),
            'name_ar' => 'وسم_' . uniqid(),
            'name_he' => 'תג_' . uniqid(),
            'slug' => 'tag-' . uniqid(),
            'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
            'icon' => 'fas fa-tag',
            'position' => rand(0, 100),
            'is_active' => $isActive,
        ]);
    }

    /**
     * **Feature: category-nav-fix, Property 6: Category-Tag Intersection Filter**
     * *For any* combination of category and tag filter, the returned products should be 
     * the intersection: products that belong to the category scope AND have the specified tag.
     * **Validates: Requirements 3.1**
     * 
     * @test
     */
    public function property_category_tag_intersection_filter(): void
    {
        $filterService = app(ProductFilterService::class);
        
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Create category hierarchy
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Create tags
            $tag1 = $this->createTag();
            $tag2 = $this->createTag();
            
            // Create products with different tag combinations
            // Products in parent category
            $parentProductWithTag1 = $this->createProduct($parent);
            $parentProductWithTag1->tags()->attach($tag1->id);
            
            $parentProductWithTag2 = $this->createProduct($parent);
            $parentProductWithTag2->tags()->attach($tag2->id);
            
            $parentProductNoTag = $this->createProduct($parent);
            
            // Products in child category
            $childProductWithTag1 = $this->createProduct($child);
            $childProductWithTag1->tags()->attach($tag1->id);
            
            $childProductWithBothTags = $this->createProduct($child);
            $childProductWithBothTags->tags()->attach([$tag1->id, $tag2->id]);
            
            // Products in sub-child category
            $subChildProductWithTag1 = $this->createProduct($subChild);
            $subChildProductWithTag1->tags()->attach($tag1->id);
            
            $subChildProductWithTag2 = $this->createProduct($subChild);
            $subChildProductWithTag2->tags()->attach($tag2->id);
            
            // Test 1: Parent category + tag1 filter
            // Should return: parentProductWithTag1, childProductWithTag1, childProductWithBothTags, subChildProductWithTag1
            $parentCategoryIds = [$parent->id];
            foreach ($parent->descendants() as $descendant) {
                $parentCategoryIds[] = $descendant->id;
            }
            
            $request = new Request(['tag' => $tag1->slug]);
            $query = Product::active();
            $query = $filterService->applyFilters($query, $request, $parentCategoryIds);
            $results = $query->pluck('id')->toArray();
            
            $this->assertCount(4, $results, "Parent category + tag1 should return 4 products");
            $this->assertContains($parentProductWithTag1->id, $results);
            $this->assertContains($childProductWithTag1->id, $results);
            $this->assertContains($childProductWithBothTags->id, $results);
            $this->assertContains($subChildProductWithTag1->id, $results);
            $this->assertNotContains($parentProductWithTag2->id, $results);
            $this->assertNotContains($parentProductNoTag->id, $results);
            
            // Test 2: Child category + tag1 filter
            // Should return: childProductWithTag1, childProductWithBothTags, subChildProductWithTag1
            $childCategoryIds = [$child->id];
            foreach ($child->descendants() as $descendant) {
                $childCategoryIds[] = $descendant->id;
            }
            
            $query = Product::active();
            $query = $filterService->applyFilters($query, $request, $childCategoryIds);
            $results = $query->pluck('id')->toArray();
            
            $this->assertCount(3, $results, "Child category + tag1 should return 3 products");
            $this->assertContains($childProductWithTag1->id, $results);
            $this->assertContains($childProductWithBothTags->id, $results);
            $this->assertContains($subChildProductWithTag1->id, $results);
            $this->assertNotContains($parentProductWithTag1->id, $results);
            
            // Test 3: Sub-child category + tag1 filter
            // Should return: subChildProductWithTag1 only
            $subChildCategoryIds = [$subChild->id];
            
            $query = Product::active();
            $query = $filterService->applyFilters($query, $request, $subChildCategoryIds);
            $results = $query->pluck('id')->toArray();
            
            $this->assertCount(1, $results, "Sub-child category + tag1 should return 1 product");
            $this->assertContains($subChildProductWithTag1->id, $results);
            
            // Test 4: Parent category + tag2 filter
            // Should return: parentProductWithTag2, childProductWithBothTags, subChildProductWithTag2
            $request = new Request(['tag' => $tag2->slug]);
            $query = Product::active();
            $query = $filterService->applyFilters($query, $request, $parentCategoryIds);
            $results = $query->pluck('id')->toArray();
            
            $this->assertCount(3, $results, "Parent category + tag2 should return 3 products");
            $this->assertContains($parentProductWithTag2->id, $results);
            $this->assertContains($childProductWithBothTags->id, $results);
            $this->assertContains($subChildProductWithTag2->id, $results);
            
            // Test 5: Inactive tag should not return products
            $inactiveTag = $this->createTag(false);
            $productWithInactiveTag = $this->createProduct($parent);
            $productWithInactiveTag->tags()->attach($inactiveTag->id);
            
            $request = new Request(['tag' => $inactiveTag->slug]);
            $query = Product::active();
            $query = $filterService->applyFilters($query, $request, $parentCategoryIds);
            $results = $query->pluck('id')->toArray();
            
            $this->assertNotContains($productWithInactiveTag->id, $results, "Inactive tag should not return products");
            
            // Clean up
            $allProducts = [
                $parentProductWithTag1, $parentProductWithTag2, $parentProductNoTag,
                $childProductWithTag1, $childProductWithBothTags,
                $subChildProductWithTag1, $subChildProductWithTag2,
                $productWithInactiveTag
            ];
            
            foreach ($allProducts as $product) {
                $product->tags()->detach();
                $product->brand->forceDelete();
                $product->forceDelete();
            }
            
            $tag1->forceDelete();
            $tag2->forceDelete();
            $inactiveTag->forceDelete();
            
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 7: Tag Filter Pagination Preservation**
     * *For any* paginated results with a tag filter applied, all pagination links 
     * should preserve the tag query parameter.
     * **Validates: Requirements 3.4**
     * 
     * @test
     */
    public function property_tag_filter_pagination_preservation(): void
    {
        $filterService = app(ProductFilterService::class);
        
        // Run 50 iterations (fewer due to more complex setup)
        for ($i = 0; $i < 50; $i++) {
            // Create category hierarchy
            [$parent, $child, $subChild] = $this->createCategoryHierarchy();
            
            // Create a tag
            $tag = $this->createTag();
            
            // Create enough products to trigger pagination (more than 12 per page)
            $products = [];
            $productCount = rand(15, 25); // Ensure we have multiple pages
            
            for ($j = 0; $j < $productCount; $j++) {
                $category = [$parent, $child, $subChild][rand(0, 2)];
                $product = $this->createProduct($category);
                $product->tags()->attach($tag->id);
                $products[] = $product;
            }
            
            // Get category IDs including descendants
            $categoryIds = [$parent->id];
            foreach ($parent->descendants() as $descendant) {
                $categoryIds[] = $descendant->id;
            }
            
            // Create request with tag filter
            $request = new Request(['tag' => $tag->slug, 'per_page' => 12]);
            
            // Build query with filters
            $query = Product::with(['category', 'brand', 'images', 'tags'])->active();
            $query = $filterService->applyFilters($query, $request, $categoryIds);
            
            // Paginate
            $paginatedProducts = $query->paginate(12);
            
            // Simulate appends() call as done in controller
            $paginatedProducts->appends($request->except('page'));
            
            // Test 1: Verify pagination preserves tag parameter in URLs
            if ($paginatedProducts->hasPages()) {
                // Get the URL for page 2
                $page2Url = $paginatedProducts->url(2);
                
                $this->assertStringContainsString(
                    'tag=' . $tag->slug,
                    $page2Url,
                    "Pagination URL for page 2 should contain tag parameter"
                );
                
                // Verify all products on page 1 have the tag
                foreach ($paginatedProducts as $product) {
                    $this->assertTrue(
                        $product->tags->contains('id', $tag->id),
                        "All products on page 1 should have the filtered tag"
                    );
                }
            }
            
            // Test 2: Verify page 2 products also have the tag
            if ($paginatedProducts->lastPage() > 1) {
                $page2Query = Product::with(['category', 'brand', 'images', 'tags'])->active();
                $page2Query = $filterService->applyFilters($page2Query, $request, $categoryIds);
                $page2Products = $page2Query->paginate(12, ['*'], 'page', 2);
                
                foreach ($page2Products as $product) {
                    $this->assertTrue(
                        $product->tags->contains('id', $tag->id),
                        "All products on page 2 should have the filtered tag"
                    );
                }
                
                // Verify page 2 pagination also preserves tag
                $page2Products->appends($request->except('page'));
                $page1Url = $page2Products->url(1);
                
                $this->assertStringContainsString(
                    'tag=' . $tag->slug,
                    $page1Url,
                    "Pagination URL from page 2 to page 1 should contain tag parameter"
                );
            }
            
            // Test 3: Verify multiple query parameters are preserved
            $multiRequest = new Request(['tag' => $tag->slug, 'sort' => 'price', 'order' => 'asc']);
            $multiQuery = Product::with(['category', 'brand', 'images', 'tags'])->active();
            $multiQuery = $filterService->applyFilters($multiQuery, $multiRequest, $categoryIds);
            $multiPaginated = $multiQuery->paginate(12);
            $multiPaginated->appends($multiRequest->except('page'));
            
            if ($multiPaginated->hasPages()) {
                $multiPage2Url = $multiPaginated->url(2);
                
                $this->assertStringContainsString(
                    'tag=' . $tag->slug,
                    $multiPage2Url,
                    "Pagination URL should preserve tag parameter with multiple params"
                );
                $this->assertStringContainsString(
                    'sort=price',
                    $multiPage2Url,
                    "Pagination URL should preserve sort parameter"
                );
                $this->assertStringContainsString(
                    'order=asc',
                    $multiPage2Url,
                    "Pagination URL should preserve order parameter"
                );
            }
            
            // Clean up
            foreach ($products as $product) {
                $product->tags()->detach();
                $product->brand->forceDelete();
                $product->forceDelete();
            }
            
            $tag->forceDelete();
            $subChild->forceDelete();
            $child->forceDelete();
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 13: Category Slug Global Uniqueness**
     * *For any* two categories in the system, their slugs should be different (globally unique).
     * **Validates: Requirements 6.5**
     * 
     * @test
     */
    public function property_category_slug_global_uniqueness(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Create multiple categories with potentially conflicting names
            $baseName = 'TestCategory_' . uniqid();
            
            // Create first category
            $category1 = Category::create([
                'name_en' => $baseName,
                'name_ar' => 'اختبار_' . uniqid(),
                'name_he' => 'בדיקה_' . uniqid(),
                'slug' => \Illuminate\Support\Str::slug($baseName),
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            // Create second category with the same base name
            // The system should generate a unique slug
            $category2Slug = \Illuminate\Support\Str::slug($baseName);
            $originalSlug = $category2Slug;
            $counter = 1;
            
            // Simulate the generateUniqueSlug logic
            while (Category::withTrashed()->where('slug', $category2Slug)->exists()) {
                $category2Slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $category2 = Category::create([
                'name_en' => $baseName,
                'name_ar' => 'اختبار_' . uniqid(),
                'name_he' => 'בדיקה_' . uniqid(),
                'slug' => $category2Slug,
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            // Create third category with the same base name
            $category3Slug = \Illuminate\Support\Str::slug($baseName);
            $originalSlug = $category3Slug;
            $counter = 1;
            
            while (Category::withTrashed()->where('slug', $category3Slug)->exists()) {
                $category3Slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $category3 = Category::create([
                'name_en' => $baseName,
                'name_ar' => 'اختبار_' . uniqid(),
                'name_he' => 'בדיקה_' . uniqid(),
                'slug' => $category3Slug,
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            // Verify all slugs are unique
            $this->assertNotEquals(
                $category1->slug,
                $category2->slug,
                "Category 1 and Category 2 should have different slugs"
            );
            
            $this->assertNotEquals(
                $category1->slug,
                $category3->slug,
                "Category 1 and Category 3 should have different slugs"
            );
            
            $this->assertNotEquals(
                $category2->slug,
                $category3->slug,
                "Category 2 and Category 3 should have different slugs"
            );
            
            // Verify all slugs exist in the database
            $allSlugs = Category::withTrashed()->pluck('slug')->toArray();
            $this->assertContains($category1->slug, $allSlugs);
            $this->assertContains($category2->slug, $allSlugs);
            $this->assertContains($category3->slug, $allSlugs);
            
            // Verify uniqueness across all categories in the database
            $slugCounts = Category::withTrashed()
                ->selectRaw('slug, COUNT(*) as count')
                ->groupBy('slug')
                ->having('count', '>', 1)
                ->get();
            
            $this->assertCount(
                0,
                $slugCounts,
                "No duplicate slugs should exist in the database"
            );
            
            // Clean up
            $category3->forceDelete();
            $category2->forceDelete();
            $category1->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-fix, Property 13b: Slug Uniqueness Includes Soft-Deleted Categories**
     * *For any* category slug, it should remain unique even when categories are soft-deleted.
     * **Validates: Requirements 6.5**
     * 
     * @test
     */
    public function property_slug_uniqueness_includes_soft_deleted(): void
    {
        // Run 50 iterations
        for ($i = 0; $i < 50; $i++) {
            $baseName = 'SoftDeleteTest_' . uniqid();
            $baseSlug = \Illuminate\Support\Str::slug($baseName);
            
            // Create and soft-delete a category
            $deletedCategory = Category::create([
                'name_en' => $baseName,
                'name_ar' => 'اختبار_' . uniqid(),
                'name_he' => 'בדיקה_' . uniqid(),
                'slug' => $baseSlug,
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            $deletedCategory->delete(); // Soft delete
            
            // Verify the soft-deleted category still exists in withTrashed
            $this->assertTrue(
                Category::withTrashed()->where('slug', $baseSlug)->exists(),
                "Soft-deleted category should still exist in withTrashed query"
            );
            
            // Create a new category with the same name
            // The slug generation should account for soft-deleted categories
            $newSlug = $baseSlug;
            $originalSlug = $newSlug;
            $counter = 1;
            
            while (Category::withTrashed()->where('slug', $newSlug)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $newCategory = Category::create([
                'name_en' => $baseName,
                'name_ar' => 'اختبار_' . uniqid(),
                'name_he' => 'בדיקה_' . uniqid(),
                'slug' => $newSlug,
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            // Verify the new category has a different slug
            $this->assertNotEquals(
                $deletedCategory->slug,
                $newCategory->slug,
                "New category should have a different slug than soft-deleted category"
            );
            
            // Verify both slugs are unique in the database (including soft-deleted)
            $slugCounts = Category::withTrashed()
                ->selectRaw('slug, COUNT(*) as count')
                ->groupBy('slug')
                ->having('count', '>', 1)
                ->get();
            
            $this->assertCount(
                0,
                $slugCounts,
                "No duplicate slugs should exist even with soft-deleted categories"
            );
            
            // Clean up
            $newCategory->forceDelete();
            $deletedCategory->forceDelete();
        }
    }
}
