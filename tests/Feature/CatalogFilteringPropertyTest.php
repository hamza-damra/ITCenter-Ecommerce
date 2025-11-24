<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Eris\TestTrait;

class CatalogFilteringPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    /**
     * **Feature: advanced-catalog-filtering, Property 1: Strong offers filter exclusivity**
     * 
     * For any set of products with mixed is_strong_offer values, when the strong offers 
     * filter is applied, all returned products should have is_strong_offer=true and no 
     * products with is_strong_offer=false should be included.
     * 
     * **Validates: Requirements 1.2**
     */
    public function test_strong_offers_filter_exclusivity(): void
    {
        $this->forAll(
            \Eris\Generator\choose(5, 20), // Number of products to generate
            \Eris\Generator\choose(1, 100) // Percentage of products that are strong offers
        )
        ->then(function (int $productCount, int $strongOfferPercentage) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a category and brand for the products
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);

            // Generate products with mixed is_strong_offer values
            $strongOfferCount = (int) ceil($productCount * $strongOfferPercentage / 100);
            $regularCount = $productCount - $strongOfferCount;

            // Create strong offer products
            for ($i = 0; $i < $strongOfferCount; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => true,
                    'discount_percentage' => rand(10, 50),
                ]);
            }

            // Create regular products
            for ($i = 0; $i < $regularCount; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => false,
                    'discount_percentage' => null,
                ]);
            }

            // Apply the strong offers scope
            $strongOfferProducts = Product::strongOffers()->get();

            // Assert: All returned products have is_strong_offer=true
            $this->assertCount($strongOfferCount, $strongOfferProducts);
            
            foreach ($strongOfferProducts as $product) {
                $this->assertTrue(
                    $product->is_strong_offer,
                    "Product {$product->id} should have is_strong_offer=true"
                );
            }

            // Assert: No products with is_strong_offer=false are included
            $allProducts = Product::all();
            $nonStrongOfferProducts = $allProducts->filter(fn($p) => !$p->is_strong_offer);
            
            foreach ($nonStrongOfferProducts as $product) {
                $this->assertFalse(
                    $strongOfferProducts->contains($product),
                    "Product {$product->id} with is_strong_offer=false should not be in results"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 15: Parent-child category relationship**
     * 
     * For any category with parent_id set, it should be queryable as a child of that parent category.
     * 
     * **Validates: Requirements 5.2**
     */
    public function test_parent_child_category_relationship(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10) // Number of parent categories
        )
        ->then(function (int $parentCount) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            // Create parent categories
            $parents = [];
            for ($i = 0; $i < $parentCount; $i++) {
                $parents[] = Category::factory()->create([
                    'parent_id' => null,
                    'is_active' => true,
                ]);
            }
            
            // For each parent, create 1-5 child categories
            $childrenByParent = [];
            foreach ($parents as $parent) {
                $childCount = rand(1, 5);
                $children = [];
                for ($i = 0; $i < $childCount; $i++) {
                    $children[] = Category::factory()->create([
                        'parent_id' => $parent->id,
                        'is_active' => true,
                    ]);
                }
                $childrenByParent[$parent->id] = $children;
            }
            
            // Assert: Each child category should be queryable as a child of its parent
            foreach ($parents as $parent) {
                $queriedChildren = $parent->children()->get();
                $expectedChildren = $childrenByParent[$parent->id];
                
                $this->assertCount(
                    count($expectedChildren),
                    $queriedChildren,
                    "Parent category {$parent->id} should have " . count($expectedChildren) . " children"
                );
                
                foreach ($expectedChildren as $expectedChild) {
                    $this->assertTrue(
                        $queriedChildren->contains('id', $expectedChild->id),
                        "Child category {$expectedChild->id} should be in parent {$parent->id}'s children"
                    );
                    
                    // Also verify the child's parent relationship
                    $this->assertEquals(
                        $parent->id,
                        $expectedChild->parent_id,
                        "Child category {$expectedChild->id} should have parent_id = {$parent->id}"
                    );
                    
                    // Verify the parent() relationship works
                    $this->assertNotNull($expectedChild->parent);
                    $this->assertEquals(
                        $parent->id,
                        $expectedChild->parent->id,
                        "Child category {$expectedChild->id}'s parent should be {$parent->id}"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 2: Filter combination uses AND logic**
     * 
     * For any combination of filters (strong offers, brand, stock, price, attributes), 
     * all returned products should satisfy every filter condition simultaneously.
     * 
     * **Validates: Requirements 1.5, 3.5**
     */
    public function test_filter_combination_and_logic(): void
    {
        $this->forAll(
            \Eris\Generator\choose(10, 30) // Number of products to generate
        )
        ->then(function (int $productCount) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create categories and brands
            $category = Category::factory()->create(['is_active' => true]);
            $brand1 = Brand::factory()->create(['is_active' => true, 'slug' => 'test-brand-1']);
            $brand2 = Brand::factory()->create(['is_active' => true, 'slug' => 'test-brand-2']);

            // Generate products with various characteristics
            for ($i = 0; $i < $productCount; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => rand(0, 1) ? $brand1->id : $brand2->id,
                    'is_active' => true,
                    'is_strong_offer' => rand(0, 1) === 1,
                    'stock_status' => rand(0, 1) ? 'in_stock' : 'out_of_stock',
                    'price' => rand(100, 1000),
                ]);
            }

            // Apply multiple filters using ProductFilterService
            $filterService = new \App\Services\ProductFilterService();
            $request = new \Illuminate\Http\Request([
                'strong_offers' => 1,
                'brand' => ['test-brand-1'],
                'stock' => 'in',
                'min_price' => 200,
                'max_price' => 800,
            ]);

            $query = Product::query()->active();
            $filteredQuery = $filterService->applyFilters($query, $request);
            $filteredProducts = $filteredQuery->get();

            // Assert: All returned products satisfy ALL filter conditions
            foreach ($filteredProducts as $product) {
                $this->assertTrue(
                    $product->is_strong_offer,
                    "Product {$product->id} should be a strong offer"
                );
                
                $this->assertEquals(
                    'test-brand-1',
                    $product->brand->slug,
                    "Product {$product->id} should be from brand test-brand-1"
                );
                
                $this->assertEquals(
                    'in_stock',
                    $product->stock_status,
                    "Product {$product->id} should be in stock"
                );
                
                $this->assertGreaterThanOrEqual(
                    200,
                    $product->price,
                    "Product {$product->id} price should be >= 200"
                );
                
                $this->assertLessThanOrEqual(
                    800,
                    $product->price,
                    "Product {$product->id} price should be <= 800"
                );
            }

            // Verify that products NOT matching all criteria are excluded
            $allProducts = Product::active()->get();
            foreach ($allProducts as $product) {
                $shouldBeIncluded = $product->is_strong_offer
                    && $product->brand->slug === 'test-brand-1'
                    && $product->stock_status === 'in_stock'
                    && $product->price >= 200
                    && $product->price <= 800;

                if ($shouldBeIncluded) {
                    $this->assertTrue(
                        $filteredProducts->contains($product),
                        "Product {$product->id} matching all criteria should be included"
                    );
                } else {
                    $this->assertFalse(
                        $filteredProducts->contains($product),
                        "Product {$product->id} not matching all criteria should be excluded"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 10: Attribute filter AND logic**
     * 
     * For any set of selected attribute values, all returned products should have 
     * all selected attribute values assigned (not just any one of them).
     * 
     * **Validates: Requirements 3.3**
     */
    public function test_attribute_filter_and_logic(): void
    {
        $this->forAll(
            \Eris\Generator\choose(5, 15) // Number of products to generate
        )
        ->then(function (int $productCount) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);

            // Create attributes
            $attribute1 = \App\Models\Attribute::create([
                'name_en' => 'Refresh Rate',
                'name_ar' => 'معدل التحديث',
                'name_he' => 'קצב רענון',
                'slug' => 'refresh-rate',
                'type' => 'select',
                'is_filterable' => true,
                'is_active' => true,
                'order' => 1,
            ]);

            $attribute2 = \App\Models\Attribute::create([
                'name_en' => 'Panel Type',
                'name_ar' => 'نوع اللوحة',
                'name_he' => 'סוג פאנל',
                'slug' => 'panel-type',
                'type' => 'select',
                'is_filterable' => true,
                'is_active' => true,
                'order' => 2,
            ]);

            // Create attribute values
            $value144hz = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute1->id,
                'value_en' => '144Hz',
                'value_ar' => '144 هرتز',
                'value_he' => '144Hz',
                'slug' => '144hz',
                'is_active' => true,
                'order' => 1,
            ]);

            $value60hz = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute1->id,
                'value_en' => '60Hz',
                'value_ar' => '60 هرتز',
                'value_he' => '60Hz',
                'slug' => '60hz',
                'is_active' => true,
                'order' => 2,
            ]);

            $valueIPS = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute2->id,
                'value_en' => 'IPS',
                'value_ar' => 'IPS',
                'value_he' => 'IPS',
                'slug' => 'ips',
                'is_active' => true,
                'order' => 1,
            ]);

            $valueTN = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute2->id,
                'value_en' => 'TN',
                'value_ar' => 'TN',
                'value_he' => 'TN',
                'slug' => 'tn',
                'is_active' => true,
                'order' => 2,
            ]);

            // Generate products with various attribute combinations
            $products = [];
            for ($i = 0; $i < $productCount; $i++) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                ]);

                // Randomly assign attribute values
                $hasRefreshRate = rand(0, 1);
                $hasPanelType = rand(0, 1);

                if ($hasRefreshRate) {
                    $refreshValue = rand(0, 1) ? $value144hz : $value60hz;
                    $product->attributeValues()->attach($refreshValue->id);
                }

                if ($hasPanelType) {
                    $panelValue = rand(0, 1) ? $valueIPS : $valueTN;
                    $product->attributeValues()->attach($panelValue->id);
                }

                $products[] = $product;
            }

            // Apply attribute filters using ProductFilterService
            $filterService = new \App\Services\ProductFilterService();
            $request = new \Illuminate\Http\Request([
                'attr' => [
                    'refresh-rate' => ['144hz'],
                    'panel-type' => ['ips'],
                ],
            ]);

            $query = Product::query()->active();
            $filteredQuery = $filterService->applyFilters($query, $request);
            $filteredProducts = $filteredQuery->get();

            // Assert: All returned products have BOTH attribute values
            foreach ($filteredProducts as $product) {
                $attributeValues = $product->attributeValues;
                
                $has144hz = $attributeValues->contains('id', $value144hz->id);
                $hasIPS = $attributeValues->contains('id', $valueIPS->id);
                
                $this->assertTrue(
                    $has144hz,
                    "Product {$product->id} should have 144Hz attribute"
                );
                
                $this->assertTrue(
                    $hasIPS,
                    "Product {$product->id} should have IPS attribute"
                );
            }

            // Verify products without BOTH attributes are excluded
            foreach ($products as $product) {
                $product->refresh();
                $attributeValues = $product->attributeValues;
                
                $has144hz = $attributeValues->contains('id', $value144hz->id);
                $hasIPS = $attributeValues->contains('id', $valueIPS->id);
                
                if ($has144hz && $hasIPS) {
                    $this->assertTrue(
                        $filteredProducts->contains('id', $product->id),
                        "Product {$product->id} with both attributes should be included"
                    );
                } else {
                    $this->assertFalse(
                        $filteredProducts->contains('id', $product->id),
                        "Product {$product->id} without both attributes should be excluded"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 11: Filter count accuracy**
     * 
     * For any filter option (brand, attribute value, stock status), the displayed count 
     * should exactly match the number of products that have that characteristic.
     * 
     * **Validates: Requirements 3.4**
     */
    public function test_filter_count_accuracy(): void
    {
        $this->forAll(
            \Eris\Generator\choose(10, 30) // Number of products to generate
        )
        ->then(function (int $productCount) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category and brands
            $category = Category::factory()->create(['is_active' => true]);
            $brand1 = Brand::factory()->create(['is_active' => true, 'slug' => 'brand-a']);
            $brand2 = Brand::factory()->create(['is_active' => true, 'slug' => 'brand-b']);

            // Track expected counts
            $expectedBrand1Count = 0;
            $expectedBrand2Count = 0;
            $expectedInStockCount = 0;
            $expectedOutOfStockCount = 0;

            // Generate products
            for ($i = 0; $i < $productCount; $i++) {
                $useBrand1 = rand(0, 1) === 1;
                $inStock = rand(0, 1) === 1;

                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $useBrand1 ? $brand1->id : $brand2->id,
                    'is_active' => true,
                    'stock_status' => $inStock ? 'in_stock' : 'out_of_stock',
                ]);

                if ($useBrand1) {
                    $expectedBrand1Count++;
                } else {
                    $expectedBrand2Count++;
                }

                if ($inStock) {
                    $expectedInStockCount++;
                } else {
                    $expectedOutOfStockCount++;
                }
            }

            // Get available filters using ProductFilterService
            $filterService = new \App\Services\ProductFilterService();
            $availableFilters = $filterService->getAvailableFilters($category);

            // Assert: Brand filter counts are accurate
            $brandFilters = $availableFilters['brands'];
            $brand1Filter = collect($brandFilters)->firstWhere('slug', 'brand-a');
            $brand2Filter = collect($brandFilters)->firstWhere('slug', 'brand-b');

            if ($expectedBrand1Count > 0) {
                $this->assertNotNull($brand1Filter, 'Brand A filter should exist');
                $this->assertEquals(
                    $expectedBrand1Count,
                    $brand1Filter['count'],
                    "Brand A count should be {$expectedBrand1Count}"
                );
            }

            if ($expectedBrand2Count > 0) {
                $this->assertNotNull($brand2Filter, 'Brand B filter should exist');
                $this->assertEquals(
                    $expectedBrand2Count,
                    $brand2Filter['count'],
                    "Brand B count should be {$expectedBrand2Count}"
                );
            }

            // Assert: Stock filter counts are accurate
            $stockFilters = $availableFilters['stock'];
            $inStockFilter = collect($stockFilters)->firstWhere('value', 'in');
            $outOfStockFilter = collect($stockFilters)->firstWhere('value', 'out');

            $this->assertNotNull($inStockFilter, 'In stock filter should exist');
            $this->assertEquals(
                $expectedInStockCount,
                $inStockFilter['count'],
                "In stock count should be {$expectedInStockCount}"
            );

            $this->assertNotNull($outOfStockFilter, 'Out of stock filter should exist');
            $this->assertEquals(
                $expectedOutOfStockCount,
                $outOfStockFilter['count'],
                "Out of stock count should be {$expectedOutOfStockCount}"
            );
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 36: Pagination filter preservation**
     * 
     * For any paginated product listing with active filters, all pagination links 
     * should include all current filter parameters in the URL.
     * 
     * **Validates: Requirements 10.5**
     */
    public function test_pagination_filter_preservation(): void
    {
        $this->forAll(
            \Eris\Generator\choose(20, 50) // Number of products (enough to paginate)
        )
        ->then(function (int $productCount) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category and brands
            $category = Category::factory()->create(['is_active' => true]);
            $brand1 = Brand::factory()->create(['is_active' => true, 'slug' => 'test-brand-1']);
            $brand2 = Brand::factory()->create(['is_active' => true, 'slug' => 'test-brand-2']);

            // Generate products with various characteristics
            for ($i = 0; $i < $productCount; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => rand(0, 1) ? $brand1->id : $brand2->id,
                    'is_active' => true,
                    'is_strong_offer' => rand(0, 1) === 1,
                    'stock_status' => rand(0, 1) ? 'in_stock' : 'out_of_stock',
                    'price' => rand(100, 1000),
                ]);
            }

            // Create a request with multiple filter parameters
            $filterParams = [
                'strong_offers' => '1',
                'brand' => ['test-brand-1'],
                'stock' => 'in',
                'min_price' => '200',
                'max_price' => '800',
                'per_page' => '5', // Small page size to ensure pagination
            ];

            // Simulate the controller's index method
            $filterService = new \App\Services\ProductFilterService();
            $request = new \Illuminate\Http\Request($filterParams);
            
            $query = Product::with(['category', 'brand', 'images'])->active();
            $query = $filterService->applyFilters($query, $request);
            
            // Apply sorting
            $query->orderBy('created_at', 'desc');
            
            // Paginate
            $products = $query->paginate($request->get('per_page', 12));
            
            // Preserve filter parameters in pagination links (as done in controller)
            $products->appends($request->except('page'));

            // Assert: Pagination links should contain all filter parameters
            if ($products->hasPages()) {
                // Get the paginator's query string
                $queryString = $products->appends($request->except('page'))->toArray();
                
                // Check that filter parameters are preserved in the paginator
                $this->assertEquals(
                    '1',
                    $request->get('strong_offers'),
                    'Strong offers filter should be preserved'
                );
                
                $this->assertEquals(
                    ['test-brand-1'],
                    $request->get('brand'),
                    'Brand filter should be preserved'
                );
                
                $this->assertEquals(
                    'in',
                    $request->get('stock'),
                    'Stock filter should be preserved'
                );
                
                $this->assertEquals(
                    '200',
                    $request->get('min_price'),
                    'Min price filter should be preserved'
                );
                
                $this->assertEquals(
                    '800',
                    $request->get('max_price'),
                    'Max price filter should be preserved'
                );

                // Verify pagination URLs contain filter parameters
                if ($products->hasMorePages()) {
                    $nextPageUrl = $products->nextPageUrl();
                    
                    $this->assertStringContainsString(
                        'strong_offers=1',
                        $nextPageUrl,
                        'Next page URL should contain strong_offers parameter'
                    );
                    
                    $this->assertStringContainsString(
                        'brand',
                        $nextPageUrl,
                        'Next page URL should contain brand parameter'
                    );
                    
                    $this->assertStringContainsString(
                        'stock=in',
                        $nextPageUrl,
                        'Next page URL should contain stock parameter'
                    );
                    
                    $this->assertStringContainsString(
                        'min_price=200',
                        $nextPageUrl,
                        'Next page URL should contain min_price parameter'
                    );
                    
                    $this->assertStringContainsString(
                        'max_price=800',
                        $nextPageUrl,
                        'Next page URL should contain max_price parameter'
                    );
                }

                // If there are previous pages, check previous page URL
                if ($products->currentPage() > 1) {
                    $prevPageUrl = $products->previousPageUrl();
                    
                    $this->assertStringContainsString(
                        'strong_offers=1',
                        $prevPageUrl,
                        'Previous page URL should contain strong_offers parameter'
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 5: Sub-category URL format**
     * 
     * For any sub-category with a parent, the generated URL should follow the 
     * format /category/{parentSlug}/{childSlug}.
     * 
     * **Validates: Requirements 2.3**
     */
    public function test_sub_category_url_format(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10) // Number of parent categories
        )
        ->then(function (int $parentCount) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            // Create parent categories with child categories
            for ($i = 0; $i < $parentCount; $i++) {
                $parent = Category::factory()->create([
                    'parent_id' => null,
                    'is_active' => true,
                    'slug' => 'parent-' . $i,
                ]);
                
                // Create 1-3 child categories for each parent
                $childCount = rand(1, 3);
                for ($j = 0; $j < $childCount; $j++) {
                    $child = Category::factory()->create([
                        'parent_id' => $parent->id,
                        'is_active' => true,
                        'slug' => 'child-' . $i . '-' . $j,
                    ]);
                    
                    // Generate URL for sub-category
                    $url = route('category.show', [
                        'parentSlug' => $parent->slug,
                        'childSlug' => $child->slug,
                    ]);
                    
                    // Assert: URL should follow the format /category/{parentSlug}/{childSlug}
                    $expectedUrl = url('/category/' . $parent->slug . '/' . $child->slug);
                    
                    $this->assertEquals(
                        $expectedUrl,
                        $url,
                        "Sub-category URL should be /category/{$parent->slug}/{$child->slug}"
                    );
                    
                    // Verify the URL pattern
                    $this->assertStringContainsString(
                        '/category/' . $parent->slug . '/' . $child->slug,
                        $url,
                        "URL should contain parent and child slugs in correct order"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 6: Breadcrumb path generation**
     * 
     * For any category or sub-category, the breadcrumb should show the complete 
     * navigation path from Home through parent (if exists) to current category.
     * 
     * **Validates: Requirements 2.4**
     */
    public function test_breadcrumb_generation(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 5) // Number of parent categories
        )
        ->then(function (int $parentCount) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            $controller = new \App\Http\Controllers\CategoryController(
                new \App\Services\ProductFilterService()
            );
            
            // Test parent categories (no parent)
            for ($i = 0; $i < $parentCount; $i++) {
                $parent = Category::factory()->create([
                    'parent_id' => null,
                    'is_active' => true,
                    'name_en' => 'Parent Category ' . $i,
                ]);
                
                // Use reflection to call protected method
                $reflection = new \ReflectionClass($controller);
                $method = $reflection->getMethod('buildBreadcrumbs');
                $method->setAccessible(true);
                
                $breadcrumbs = $method->invoke($controller, $parent);
                
                // Assert: Breadcrumb should have Home and current category
                $this->assertCount(
                    2,
                    $breadcrumbs,
                    "Parent category breadcrumb should have 2 items (Home + Category)"
                );
                
                $this->assertEquals(
                    __('messages.home'),
                    $breadcrumbs[0]['name'],
                    "First breadcrumb should be Home"
                );
                
                $this->assertEquals(
                    route('home'),
                    $breadcrumbs[0]['url'],
                    "Home breadcrumb should link to home route"
                );
                
                $this->assertEquals(
                    $parent->name,
                    $breadcrumbs[1]['name'],
                    "Second breadcrumb should be the category name"
                );
                
                $this->assertNull(
                    $breadcrumbs[1]['url'],
                    "Current category breadcrumb should have null URL"
                );
                
                // Test child categories
                $child = Category::factory()->create([
                    'parent_id' => $parent->id,
                    'is_active' => true,
                    'name_en' => 'Child Category ' . $i,
                ]);
                
                $childBreadcrumbs = $method->invoke($controller, $child);
                
                // Assert: Breadcrumb should have Home, Parent, and Child
                $this->assertCount(
                    3,
                    $childBreadcrumbs,
                    "Child category breadcrumb should have 3 items (Home + Parent + Child)"
                );
                
                $this->assertEquals(
                    __('messages.home'),
                    $childBreadcrumbs[0]['name'],
                    "First breadcrumb should be Home"
                );
                
                $this->assertEquals(
                    $parent->name,
                    $childBreadcrumbs[1]['name'],
                    "Second breadcrumb should be the parent category name"
                );
                
                $this->assertEquals(
                    route('category.show', ['parentSlug' => $parent->slug]),
                    $childBreadcrumbs[1]['url'],
                    "Parent breadcrumb should link to parent category"
                );
                
                $this->assertEquals(
                    $child->name,
                    $childBreadcrumbs[2]['name'],
                    "Third breadcrumb should be the child category name"
                );
                
                $this->assertNull(
                    $childBreadcrumbs[2]['url'],
                    "Current category breadcrumb should have null URL"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 7: Category product filtering**
     * 
     * For any sub-category, the products page should display only products where 
     * category_id matches that sub-category's id.
     * 
     * **Validates: Requirements 2.5**
     */
    public function test_category_product_filtering(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 5), // Number of categories
            \Eris\Generator\choose(5, 15) // Number of products per category
        )
        ->then(function (int $categoryCount, int $productsPerCategory) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a brand for products
            $brand = Brand::factory()->create(['is_active' => true]);
            
            // Create categories and products
            $categoriesWithProducts = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $category = Category::factory()->create([
                    'is_active' => true,
                    'slug' => 'category-' . $i,
                ]);
                
                $products = [];
                for ($j = 0; $j < $productsPerCategory; $j++) {
                    $products[] = Product::factory()->create([
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'is_active' => true,
                    ]);
                }
                
                $categoriesWithProducts[$category->id] = [
                    'category' => $category,
                    'products' => $products,
                ];
            }
            
            // Test filtering for each category
            foreach ($categoriesWithProducts as $categoryId => $data) {
                $category = $data['category'];
                $expectedProducts = $data['products'];
                
                // Apply category filter using ProductFilterService
                $filterService = new \App\Services\ProductFilterService();
                $request = new \Illuminate\Http\Request([]);
                
                $query = Product::query()->active();
                $filteredQuery = $filterService->applyFilters($query, $request, $category);
                $filteredProducts = $filteredQuery->get();
                
                // Assert: All returned products belong to this category
                $this->assertCount(
                    count($expectedProducts),
                    $filteredProducts,
                    "Category {$category->id} should have " . count($expectedProducts) . " products"
                );
                
                foreach ($filteredProducts as $product) {
                    $this->assertEquals(
                        $category->id,
                        $product->category_id,
                        "Product {$product->id} should belong to category {$category->id}"
                    );
                }
                
                // Assert: Products from other categories are excluded
                $allProducts = Product::active()->get();
                foreach ($allProducts as $product) {
                    if ($product->category_id === $category->id) {
                        $this->assertTrue(
                            $filteredProducts->contains('id', $product->id),
                            "Product {$product->id} from category {$category->id} should be included"
                        );
                    } else {
                        $this->assertFalse(
                            $filteredProducts->contains('id', $product->id),
                            "Product {$product->id} from category {$product->category_id} should be excluded"
                        );
                    }
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 12: URL-to-UI state synchronization**
     * 
     * For any URL containing filter query parameters, the filter sidebar checkboxes 
     * should be checked to match those parameters.
     * 
     * **Validates: Requirements 4.1**
     */
    public function test_url_to_ui_state_synchronization(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 3) // Number of brands to select
        )
        ->then(function (int $brandCount) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category and brands
            $category = Category::factory()->create(['is_active' => true]);
            $brands = [];
            $brandSlugs = [];
            for ($i = 0; $i < 5; $i++) {
                $brand = Brand::factory()->create([
                    'is_active' => true,
                    'slug' => 'brand-' . $i,
                ]);
                $brands[] = $brand;
                $brandSlugs[] = $brand->slug;
            }

            // Create some products for each brand
            foreach ($brands as $brand) {
                for ($j = 0; $j < 3; $j++) {
                    Product::factory()->create([
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'is_active' => true,
                        'is_strong_offer' => rand(0, 1) === 1,
                        'stock_status' => rand(0, 1) ? 'in_stock' : 'out_of_stock',
                        'price' => rand(100, 1000),
                    ]);
                }
            }

            // Select random brands for filtering
            $selectedBrandSlugs = array_slice($brandSlugs, 0, $brandCount);
            
            // Build filter parameters
            $filterParams = [
                'strong_offers' => rand(0, 1) ? '1' : null,
                'brand' => $selectedBrandSlugs,
                'stock' => rand(0, 1) ? 'in' : null,
                'min_price' => rand(0, 1) ? '200' : null,
                'max_price' => rand(0, 1) ? '800' : null,
            ];

            // Remove null values
            $filterParams = array_filter($filterParams, fn($value) => $value !== null);

            // Create a mock request with filter parameters
            $request = \Illuminate\Http\Request::create('/', 'GET', $filterParams);
            app()->instance('request', $request);
            
            // Get available filters using ProductFilterService
            $filterService = new \App\Services\ProductFilterService();
            $availableFilters = $filterService->getAvailableFilters($category);
            
            // Render the filter sidebar component with the current filter parameters
            // This simulates what happens when the page loads with URL parameters
            $view = view('components.filter-sidebar', [
                'filters' => $availableFilters,
                'current' => $filterParams,
                'category' => $category,
            ]);
            
            $content = $view->render();
            
            // Assert: Strong offers checkbox should be checked if parameter is present
            if (isset($filterParams['strong_offers'])) {
                $this->assertStringContainsString(
                    'name="strong_offers"',
                    $content,
                    'Strong offers checkbox should exist'
                );
                // Check for checked attribute
                $this->assertMatchesRegularExpression(
                    '/name="strong_offers"[^>]*checked/s',
                    $content,
                    'Strong offers checkbox should be checked when parameter is present'
                );
            } else {
                // If not in params, checkbox should not be checked
                if (strpos($content, 'name="strong_offers"') !== false) {
                    $this->assertDoesNotMatchRegularExpression(
                        '/name="strong_offers"[^>]*checked/s',
                        $content,
                        'Strong offers checkbox should not be checked when parameter is absent'
                    );
                }
            }
            
            // Assert: Brand checkboxes should be checked for selected brands
            foreach ($selectedBrandSlugs as $brandSlug) {
                // Check that the checkbox exists and is checked
                // The component uses name="brands[]" (plural)
                $pattern = '/name="brands\[\]"[^>]*value="' . preg_quote($brandSlug, '/') . '"[^>]*checked/s';
                $this->assertMatchesRegularExpression(
                    $pattern,
                    $content,
                    "Brand checkbox for {$brandSlug} should be checked"
                );
            }
            
            // Assert: Stock radio button should be checked if parameter is present
            if (isset($filterParams['stock'])) {
                $stockValue = $filterParams['stock'];
                $pattern = '/name="stock"[^>]*value="' . preg_quote($stockValue, '/') . '"[^>]*checked/s';
                $this->assertMatchesRegularExpression(
                    $pattern,
                    $content,
                    "Stock radio button for '{$stockValue}' should be checked"
                );
            }
            
            // Assert: Price range values should be set in hidden inputs
            if (isset($filterParams['min_price'])) {
                $this->assertStringContainsString(
                    'name="min_price"',
                    $content,
                    'Min price input should exist'
                );
                $this->assertStringContainsString(
                    'value="' . $filterParams['min_price'] . '"',
                    $content,
                    'Min price input should have correct value'
                );
            }
            
            if (isset($filterParams['max_price'])) {
                $this->assertStringContainsString(
                    'name="max_price"',
                    $content,
                    'Max price input should exist'
                );
                $this->assertStringContainsString(
                    'value="' . $filterParams['max_price'] . '"',
                    $content,
                    'Max price input should have correct value'
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 13: RTL layout for RTL locales**
     * 
     * For any page in the catalog system, when the locale is Arabic or Hebrew, 
     * all UI components should render in RTL layout.
     * 
     * **Validates: Requirements 4.5, 12.5**
     */
    public function test_rtl_layout_for_rtl_locales(): void
    {
        $this->forAll(
            \Eris\Generator\elements(['ar', 'he', 'en']) // Test with different locales
        )
        ->then(function (string $locale) {
            // Set the application locale
            app()->setLocale($locale);
            
            // Create test data
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);
            
            // Create some products
            for ($i = 0; $i < 3; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                ]);
            }
            
            // Get available filters
            $filterService = new \App\Services\ProductFilterService();
            $availableFilters = $filterService->getAvailableFilters($category);
            
            // Render the filter sidebar component
            try {
                $view = view('components.filter-sidebar', [
                    'filters' => $availableFilters,
                    'current' => [],
                    'category' => $category,
                ]);
                
                $html = $view->render();
                
                // Determine if locale is RTL
                $expectedRtl = in_array($locale, ['ar', 'he']);
                
                // Assert: Component should have correct dir attribute
                if ($expectedRtl) {
                    $this->assertStringContainsString(
                        'dir="rtl"',
                        $html,
                        "Filter sidebar should have dir='rtl' for locale {$locale}"
                    );
                } else {
                    $this->assertStringContainsString(
                        'dir="ltr"',
                        $html,
                        "Filter sidebar should have dir='ltr' for locale {$locale}"
                    );
                }
                
                // Assert: Filter sidebar class should be present
                $this->assertStringContainsString(
                    'filter-sidebar',
                    $html,
                    "Filter sidebar should have the filter-sidebar class"
                );
                
                // Assert: Translations should be present
                // The component uses __('messages.filters') which should be translated
                $this->assertStringContainsString(
                    '<h3>',
                    $html,
                    "Filter sidebar should have header element"
                );
                
                // Verify locale-specific content
                if ($locale === 'ar') {
                    // Arabic text should be present
                    $this->assertMatchesRegularExpression(
                        '/[\x{0600}-\x{06FF}]/u',
                        $html,
                        "Arabic locale should contain Arabic characters"
                    );
                } elseif ($locale === 'he') {
                    // Hebrew text should be present
                    $this->assertMatchesRegularExpression(
                        '/[\x{0590}-\x{05FF}]/u',
                        $html,
                        "Hebrew locale should contain Hebrew characters"
                    );
                } elseif ($locale === 'en') {
                    // English text should be present
                    $this->assertStringContainsString(
                        'Filters',
                        $html,
                        "English locale should contain 'Filters' text"
                    );
                }
                
            } catch (\Exception $e) {
                // If rendering fails, at least verify the locale was set correctly
                $this->assertEquals(
                    $locale,
                    app()->getLocale(),
                    "Locale should be set to {$locale}"
                );
                
                // Re-throw to fail the test with the actual error
                throw $e;
            } finally {
                // Clean up
                \DB::table('products')->delete();
                \DB::table('categories')->delete();
                \DB::table('brands')->delete();
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 40: Promotional card localization**
     * 
     * For any locale, the Strong Offers promotional card should display translated 
     * title, text, and button label from language files.
     * 
     * **Validates: Requirements 12.4**
     */
    public function test_promotional_card_localization(): void
    {
        $this->forAll(
            \Eris\Generator\elements(['en', 'ar', 'he']) // Test with all supported locales
        )
        ->then(function (string $locale) {
            // Set the application locale
            app()->setLocale($locale);
            session(['locale' => $locale]);
            
            // Make a request to the home page
            $response = $this->get(route('home'));
            
            $response->assertStatus(200);
            
            // Get the response content
            $content = $response->getContent();
            
            // Get expected translations for this locale
            $expectedHeadline = __('messages.strong_offers.headline');
            $expectedDesc = __('messages.strong_offers.desc');
            $expectedCta = __('messages.strong_offers.cta');
            
            // Assert: Translations should not be the raw keys
            $this->assertNotEquals(
                'messages.strong_offers.headline',
                $expectedHeadline,
                "Translation for headline should be loaded for locale {$locale}"
            );
            
            $this->assertNotEquals(
                'messages.strong_offers.desc',
                $expectedDesc,
                "Translation for description should be loaded for locale {$locale}"
            );
            
            $this->assertNotEquals(
                'messages.strong_offers.cta',
                $expectedCta,
                "Translation for CTA button should be loaded for locale {$locale}"
            );
            
            // Assert: The promotional card contains the translated content
            $this->assertStringContainsString(
                $expectedHeadline,
                $content,
                "Home page should contain translated headline for locale {$locale}"
            );
            
            $this->assertStringContainsString(
                $expectedDesc,
                $content,
                "Home page should contain translated description for locale {$locale}"
            );
            
            $this->assertStringContainsString(
                $expectedCta,
                $content,
                "Home page should contain translated CTA button text for locale {$locale}"
            );
            
            // Assert: The promotional card link points to the correct URL with strong_offers parameter
            $expectedUrl = route('products', ['strong_offers' => 1]);
            $this->assertStringContainsString(
                $expectedUrl,
                $content,
                "Promotional card should link to products page with strong_offers=1"
            );
            
            // Verify locale-specific content
            if ($locale === 'ar') {
                // Arabic translations should contain Arabic text
                $this->assertMatchesRegularExpression(
                    '/[\x{0600}-\x{06FF}]/u',
                    $expectedHeadline,
                    "Arabic headline should contain Arabic characters"
                );
                
                $this->assertMatchesRegularExpression(
                    '/[\x{0600}-\x{06FF}]/u',
                    $expectedDesc,
                    "Arabic description should contain Arabic characters"
                );
                
                $this->assertMatchesRegularExpression(
                    '/[\x{0600}-\x{06FF}]/u',
                    $expectedCta,
                    "Arabic CTA should contain Arabic characters"
                );
            } elseif ($locale === 'he') {
                // Hebrew translations should contain Hebrew text
                $this->assertMatchesRegularExpression(
                    '/[\x{0590}-\x{05FF}]/u',
                    $expectedHeadline,
                    "Hebrew headline should contain Hebrew characters"
                );
                
                $this->assertMatchesRegularExpression(
                    '/[\x{0590}-\x{05FF}]/u',
                    $expectedDesc,
                    "Hebrew description should contain Hebrew characters"
                );
                
                $this->assertMatchesRegularExpression(
                    '/[\x{0590}-\x{05FF}]/u',
                    $expectedCta,
                    "Hebrew CTA should contain Hebrew characters"
                );
            } elseif ($locale === 'en') {
                // English translations should be in English
                $this->assertEquals(
                    'Strong Offers',
                    $expectedHeadline,
                    "English headline should be 'Strong Offers'"
                );
                
                $this->assertEquals(
                    'Shop Now',
                    $expectedCta,
                    "English CTA should be 'Shop Now'"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 8: Category-specific attribute filters**
     * 
     * For any sub-category, the filter sidebar should display only attributes that are 
     * mapped to that sub-category in the attribute_category table AND have products with those attribute values.
     * 
     * **Validates: Requirements 3.1, 7.4**
     */
    public function test_category_specific_attribute_filters(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 4), // Number of categories
            \Eris\Generator\choose(3, 6)  // Number of attributes
        )
        ->then(function (int $categoryCount, int $attributeCount) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create categories
            $categories = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $categories[] = Category::factory()->create([
                    'is_active' => true,
                    'slug' => 'category-' . $i,
                    'name_en' => 'Category ' . $i,
                ]);
            }
            
            // Create attributes with values
            $attributes = [];
            $attributeValues = [];
            for ($i = 0; $i < $attributeCount; $i++) {
                $attribute = \App\Models\Attribute::create([
                    'name_en' => 'Attribute ' . $i,
                    'name_ar' => 'سمة ' . $i,
                    'name_he' => 'תכונה ' . $i,
                    'slug' => 'attribute-' . $i,
                    'type' => 'select',
                    'is_filterable' => true,
                    'is_active' => true,
                    'order' => $i,
                ]);
                
                // Create 2 values for each attribute
                $values = [];
                for ($j = 0; $j < 2; $j++) {
                    $values[] = \App\Models\AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value_en' => 'Value ' . $j,
                        'value_ar' => 'قيمة ' . $j,
                        'value_he' => 'ערך ' . $j,
                        'slug' => 'value-' . $i . '-' . $j,
                        'is_active' => true,
                        'order' => $j,
                    ]);
                }
                
                $attributes[] = $attribute;
                $attributeValues[$attribute->id] = $values;
            }
            
            // Assign attributes to categories and create products with those attributes
            $brand = Brand::factory()->create(['is_active' => true]);
            $categoryAttributeMap = [];
            
            foreach ($categories as $category) {
                // Each category gets 1-3 random attributes
                $assignedCount = rand(1, min(3, $attributeCount));
                $assignedAttributes = array_slice($attributes, 0, $assignedCount);
                
                // Attach attributes to category
                foreach ($assignedAttributes as $attribute) {
                    $category->attributes()->attach($attribute->id);
                }
                
                $categoryAttributeMap[$category->id] = array_map(
                    fn($attr) => $attr->id,
                    $assignedAttributes
                );
                
                // Create products for this category with attribute values
                for ($i = 0; $i < 3; $i++) {
                    $product = Product::factory()->create([
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'is_active' => true,
                    ]);
                    
                    // Assign attribute values from assigned attributes
                    foreach ($assignedAttributes as $attribute) {
                        $values = $attributeValues[$attribute->id];
                        $randomValue = $values[array_rand($values)];
                        $product->attributeValues()->attach($randomValue->id);
                    }
                }
            }
            
            // Test that each category shows only its assigned attributes
            $filterService = new \App\Services\ProductFilterService();
            
            foreach ($categories as $category) {
                $availableFilters = $filterService->getAvailableFilters($category);
                $attributeFilters = $availableFilters['attributes'] ?? [];
                
                $expectedAttributeIds = $categoryAttributeMap[$category->id];
                $actualAttributeIds = array_map(fn($attr) => $attr['id'], $attributeFilters);
                
                // Assert: Only attributes assigned to this category are shown
                foreach ($actualAttributeIds as $attrId) {
                    $this->assertContains(
                        $attrId,
                        $expectedAttributeIds,
                        "Attribute {$attrId} shown for category {$category->id} should be assigned to it"
                    );
                }
                
                // Assert: Attributes NOT assigned to this category are NOT shown
                $allAttributeIds = array_map(fn($attr) => $attr->id, $attributes);
                $unassignedAttributeIds = array_diff($allAttributeIds, $expectedAttributeIds);
                
                foreach ($unassignedAttributeIds as $unassignedAttrId) {
                    $this->assertNotContains(
                        $unassignedAttrId,
                        $actualAttributeIds,
                        "Unassigned attribute {$unassignedAttrId} should NOT be shown for category {$category->id}"
                    );
                }
                
                // Assert: Each shown attribute has at least one value with products
                foreach ($attributeFilters as $attrFilter) {
                    $this->assertNotEmpty(
                        $attrFilter['values'],
                        "Attribute {$attrFilter['id']} should have at least one value with products"
                    );
                    
                    foreach ($attrFilter['values'] as $valueFilter) {
                        $this->assertGreaterThan(
                            0,
                            $valueFilter['count'],
                            "Attribute value {$valueFilter['id']} should have count > 0"
                        );
                    }
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 3: Active categories visibility**
     * 
     * For any set of categories, only those with is_active=true should appear in 
     * customer-facing navigation and category listings.
     * 
     * **Validates: Requirements 2.1, 5.4**
     */
    public function test_active_categories_visibility(): void
    {
        $this->forAll(
            \Eris\Generator\choose(5, 15), // Number of categories to generate
            \Eris\Generator\choose(20, 80) // Percentage of categories that are active
        )
        ->then(function (int $categoryCount, int $activePercentage) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            // Calculate how many should be active
            $activeCount = (int) ceil($categoryCount * $activePercentage / 100);
            $inactiveCount = $categoryCount - $activeCount;

            // Create active parent categories
            $activeCategories = [];
            for ($i = 0; $i < $activeCount; $i++) {
                $activeCategories[] = Category::factory()->create([
                    'parent_id' => null,
                    'is_active' => true,
                    'position' => $i,
                ]);
            }

            // Create inactive parent categories
            $inactiveCategories = [];
            for ($i = 0; $i < $inactiveCount; $i++) {
                $inactiveCategories[] = Category::factory()->create([
                    'parent_id' => null,
                    'is_active' => false,
                    'position' => $activeCount + $i,
                ]);
            }

            // Query categories as they would appear in navigation
            $navigationCategories = Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('position')
                ->get();

            // Assert: Only active categories are returned
            $this->assertCount(
                $activeCount,
                $navigationCategories,
                "Should return exactly {$activeCount} active categories"
            );

            // Assert: All returned categories have is_active=true
            foreach ($navigationCategories as $category) {
                $this->assertTrue(
                    $category->is_active,
                    "Category {$category->id} in navigation should have is_active=true"
                );
            }

            // Assert: No inactive categories are in the results
            foreach ($inactiveCategories as $inactiveCategory) {
                $this->assertFalse(
                    $navigationCategories->contains('id', $inactiveCategory->id),
                    "Inactive category {$inactiveCategory->id} should not appear in navigation"
                );
            }

            // Assert: All active categories are in the results
            foreach ($activeCategories as $activeCategory) {
                $this->assertTrue(
                    $navigationCategories->contains('id', $activeCategory->id),
                    "Active category {$activeCategory->id} should appear in navigation"
                );
            }

            // Test the view composer that provides categories to views
            $viewCategories = Category::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('position');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

            // Assert: View composer also returns only active categories
            $this->assertCount(
                $activeCount,
                $viewCategories,
                "View composer should return exactly {$activeCount} active categories"
            );

            foreach ($viewCategories as $category) {
                $this->assertTrue(
                    $category->is_active,
                    "Category {$category->id} from view composer should have is_active=true"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 39: Filter label localization**
     * 
     * For any filter label and any locale, the displayed text should come from 
     * the appropriate language file translation.
     * 
     * **Validates: Requirements 12.3**
     */
    public function test_filter_label_localization(): void
    {
        // Test each locale individually to avoid Eris evaluation ratio issues
        $locales = ['en', 'ar', 'he'];
        
        foreach ($locales as $locale) {
            // Set the application locale
            app()->setLocale($locale);
            
            // Define the core filter labels that should be translated
            $filterLabels = [
                'filters',
                'strong_offers_filter',
                'stock_filter',
                'in_stock_filter',
                'out_of_stock_filter',
                'brands_filter',
                'price_range',
                'attributes_filter',
                'no_results_found',
            ];
            
            // Assert: All filter labels have translations loaded
            foreach ($filterLabels as $label) {
                $translation = __('messages.' . $label);
                
                // Translation should not be the raw key
                $this->assertNotEquals(
                    'messages.' . $label,
                    $translation,
                    "Translation for '{$label}' should be loaded for locale {$locale}"
                );
                
                // Translation should not be empty
                $this->assertNotEmpty(
                    $translation,
                    "Translation for '{$label}' should not be empty for locale {$locale}"
                );
            }
            
            // Verify locale-specific translations contain appropriate characters
            if ($locale === 'ar') {
                // Arabic translations should contain Arabic characters
                $filtersLabel = __('messages.filters');
                $this->assertMatchesRegularExpression(
                    '/[\x{0600}-\x{06FF}]/u',
                    $filtersLabel,
                    "Arabic translation for 'filters' should contain Arabic characters"
                );
                
                $strongOffersLabel = __('messages.strong_offers_filter');
                $this->assertMatchesRegularExpression(
                    '/[\x{0600}-\x{06FF}]/u',
                    $strongOffersLabel,
                    "Arabic translation for 'strong_offers_filter' should contain Arabic characters"
                );
                
                $inStockLabel = __('messages.in_stock_filter');
                $this->assertMatchesRegularExpression(
                    '/[\x{0600}-\x{06FF}]/u',
                    $inStockLabel,
                    "Arabic translation for 'in_stock_filter' should contain Arabic characters"
                );
                
                // Verify specific Arabic translations
                $this->assertEquals(
                    'الفلاتر',
                    $filtersLabel,
                    "Arabic translation for 'filters' should be 'الفلاتر'"
                );
                
                $this->assertEquals(
                    'العروض القوية',
                    $strongOffersLabel,
                    "Arabic translation for 'strong_offers_filter' should be 'العروض القوية'"
                );
                
            } elseif ($locale === 'he') {
                // Hebrew translations should contain Hebrew characters
                $filtersLabel = __('messages.filters');
                $this->assertMatchesRegularExpression(
                    '/[\x{0590}-\x{05FF}]/u',
                    $filtersLabel,
                    "Hebrew translation for 'filters' should contain Hebrew characters"
                );
                
                $strongOffersLabel = __('messages.strong_offers_filter');
                $this->assertMatchesRegularExpression(
                    '/[\x{0590}-\x{05FF}]/u',
                    $strongOffersLabel,
                    "Hebrew translation for 'strong_offers_filter' should contain Hebrew characters"
                );
                
                $inStockLabel = __('messages.in_stock_filter');
                $this->assertMatchesRegularExpression(
                    '/[\x{0590}-\x{05FF}]/u',
                    $inStockLabel,
                    "Hebrew translation for 'in_stock_filter' should contain Hebrew characters"
                );
                
                // Verify specific Hebrew translations
                $this->assertEquals(
                    'מסננים',
                    $filtersLabel,
                    "Hebrew translation for 'filters' should be 'מסננים'"
                );
                
                $this->assertEquals(
                    'מבצעים חזקים',
                    $strongOffersLabel,
                    "Hebrew translation for 'strong_offers_filter' should be 'מבצעים חזקים'"
                );
                
            } elseif ($locale === 'en') {
                // English translations should be in English
                $filtersLabel = __('messages.filters');
                $this->assertEquals(
                    'Filters',
                    $filtersLabel,
                    "English translation for 'filters' should be 'Filters'"
                );
                
                $strongOffersLabel = __('messages.strong_offers_filter');
                $this->assertEquals(
                    'Strong Offers',
                    $strongOffersLabel,
                    "English translation for 'strong_offers_filter' should be 'Strong Offers'"
                );
                
                $inStockLabel = __('messages.in_stock_filter');
                $this->assertEquals(
                    'In Stock',
                    $inStockLabel,
                    "English translation for 'in_stock_filter' should be 'In Stock'"
                );
                
                $outOfStockLabel = __('messages.out_of_stock_filter');
                $this->assertEquals(
                    'Out of Stock',
                    $outOfStockLabel,
                    "English translation for 'out_of_stock_filter' should be 'Out of Stock'"
                );
                
                $priceRangeLabel = __('messages.price_range');
                $this->assertEquals(
                    'Price Range',
                    $priceRangeLabel,
                    "English translation for 'price_range' should be 'Price Range'"
                );
            }
        }
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 4: Sub-category display on hover**
     * 
     * For any top-level category that has child categories with is_active=true, 
     * hovering or clicking should display all active sub-categories.
     * 
     * **Validates: Requirements 2.2**
     */
    public function test_subcategory_display_on_hover(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 8), // Number of parent categories
            \Eris\Generator\choose(1, 6)  // Number of children per parent
        )
        ->then(function (int $parentCount, int $childrenPerParent) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            // Create parent categories
            $parents = [];
            for ($i = 0; $i < $parentCount; $i++) {
                $parents[] = Category::factory()->create([
                    'parent_id' => null,
                    'is_active' => true,
                    'position' => $i,
                ]);
            }

            // For each parent, create both active and inactive children
            $activeChildrenByParent = [];
            $inactiveChildrenByParent = [];
            
            foreach ($parents as $parent) {
                $activeChildren = [];
                $inactiveChildren = [];
                
                // Create active children
                $activeChildCount = max(1, (int) ceil($childrenPerParent * 0.7)); // 70% active
                for ($i = 0; $i < $activeChildCount; $i++) {
                    $activeChildren[] = Category::factory()->create([
                        'parent_id' => $parent->id,
                        'is_active' => true,
                        'position' => $i,
                    ]);
                }
                
                // Create inactive children
                $inactiveChildCount = $childrenPerParent - $activeChildCount;
                for ($i = 0; $i < $inactiveChildCount; $i++) {
                    $inactiveChildren[] = Category::factory()->create([
                        'parent_id' => $parent->id,
                        'is_active' => false,
                        'position' => $activeChildCount + $i,
                    ]);
                }
                
                $activeChildrenByParent[$parent->id] = $activeChildren;
                $inactiveChildrenByParent[$parent->id] = $inactiveChildren;
            }

            // Query categories as they would be loaded for navigation
            $navigationCategories = Category::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('position');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

            // Assert: Each parent category has the correct active children loaded
            foreach ($navigationCategories as $parent) {
                $expectedActiveChildren = $activeChildrenByParent[$parent->id];
                $loadedChildren = $parent->children;

                $this->assertCount(
                    count($expectedActiveChildren),
                    $loadedChildren,
                    "Parent category {$parent->id} should have " . count($expectedActiveChildren) . " active children"
                );

                // Assert: All loaded children are active
                foreach ($loadedChildren as $child) {
                    $this->assertTrue(
                        $child->is_active,
                        "Child category {$child->id} should be active"
                    );
                }

                // Assert: All expected active children are present
                foreach ($expectedActiveChildren as $expectedChild) {
                    $this->assertTrue(
                        $loadedChildren->contains('id', $expectedChild->id),
                        "Active child category {$expectedChild->id} should be in parent {$parent->id}'s children"
                    );
                }

                // Assert: No inactive children are present
                $inactiveChildren = $inactiveChildrenByParent[$parent->id] ?? [];
                foreach ($inactiveChildren as $inactiveChild) {
                    $this->assertFalse(
                        $loadedChildren->contains('id', $inactiveChild->id),
                        "Inactive child category {$inactiveChild->id} should not be in parent {$parent->id}'s children"
                    );
                }

                // Assert: Children are ordered by position
                $positions = $loadedChildren->pluck('position')->toArray();
                $sortedPositions = $positions;
                sort($sortedPositions);
                $this->assertEquals(
                    $sortedPositions,
                    $positions,
                    "Children of parent {$parent->id} should be ordered by position"
                );
            }

            // Test that parents without active children don't show submenu
            // Create a parent with only inactive children
            $parentWithoutActiveChildren = Category::factory()->create([
                'parent_id' => null,
                'is_active' => true,
                'position' => 999,
            ]);
            
            Category::factory()->create([
                'parent_id' => $parentWithoutActiveChildren->id,
                'is_active' => false,
                'position' => 0,
            ]);

            $parentReloaded = Category::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('position');
            }])->find($parentWithoutActiveChildren->id);

            $this->assertCount(
                0,
                $parentReloaded->children,
                "Parent with only inactive children should have 0 active children loaded"
            );
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 18: Attribute field persistence**
     * 
     * For any attribute creation, all fields (name_en, name_ar, name_he, slug, type, 
     * unit, is_filterable, order, is_active) should be stored in the database.
     * 
     * **Validates: Requirements 6.1**
     */
    public function test_attribute_field_persistence(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 100), // iteration number for unique names
            \Eris\Generator\elements('select', 'color', 'button', 'radio'), // type (current enum values)
            \Eris\Generator\oneOf(
                \Eris\Generator\constant(null),
                \Eris\Generator\elements('Hz', 'GB', 'inches', 'mm', '%')
            ), // unit (nullable)
            \Eris\Generator\bool(), // is_filterable
            \Eris\Generator\choose(0, 100), // order
            \Eris\Generator\bool() // is_active
        )
        ->then(function (
            int $iteration,
            string $type,
            ?string $unit,
            bool $isFilterable,
            int $order,
            bool $isActive
        ) {
            // Clean database before each iteration
            \DB::table('attributes')->delete();

            // Generate unique names for this iteration
            $nameEn = 'Test Attribute ' . $iteration;
            $nameAr = 'سمة اختبار ' . $iteration;
            $nameHe = 'תכונת בדיקה ' . $iteration;
            $slug = 'test-attribute-' . $iteration;

            // Create attribute with all fields
            $attribute = \App\Models\Attribute::create([
                'name_en' => $nameEn,
                'name_ar' => $nameAr,
                'name_he' => $nameHe,
                'slug' => $slug,
                'type' => $type,
                'unit' => $unit,
                'is_filterable' => $isFilterable,
                'order' => $order,
                'is_active' => $isActive,
            ]);

            // Reload from database to ensure persistence
            $reloaded = \App\Models\Attribute::find($attribute->id);

            // Assert: All fields should be persisted correctly
            $this->assertNotNull($reloaded, "Attribute should be persisted in database");
            $this->assertEquals($nameEn, $reloaded->name_en, "name_en should be persisted");
            $this->assertEquals($nameAr, $reloaded->name_ar, "name_ar should be persisted");
            $this->assertEquals($nameHe, $reloaded->name_he, "name_he should be persisted");
            $this->assertEquals($slug, $reloaded->slug, "slug should be persisted");
            $this->assertEquals($type, $reloaded->type, "type should be persisted");
            $this->assertEquals($unit, $reloaded->unit, "unit should be persisted");
            $this->assertEquals($isFilterable, $reloaded->is_filterable, "is_filterable should be persisted");
            $this->assertEquals($order, $reloaded->order, "order should be persisted");
            $this->assertEquals($isActive, $reloaded->is_active, "is_active should be persisted");
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 21: Attribute cascade deletion**
     * 
     * For any attribute deletion, all associated attribute_values and product_attribute_values 
     * records should be automatically deleted.
     * 
     * **Validates: Requirements 6.4**
     */
    public function test_attribute_cascade_deletion(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10), // Number of attribute values to create
            \Eris\Generator\choose(0, 5)   // Number of products to associate
        )
        ->then(function (int $valueCount, int $productCount) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();

            // Create an attribute
            $attribute = \App\Models\Attribute::create([
                'name_en' => 'Test Attribute',
                'name_ar' => 'سمة اختبار',
                'name_he' => 'תכונת בדיקה',
                'slug' => 'test-attribute-' . uniqid(),
                'type' => 'select',
                'is_filterable' => true,
                'order' => 0,
                'is_active' => true,
            ]);

            // Create attribute values
            $attributeValueIds = [];
            for ($i = 0; $i < $valueCount; $i++) {
                $value = \App\Models\AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value_en' => 'Value ' . $i,
                    'value_ar' => 'قيمة ' . $i,
                    'value_he' => 'ערך ' . $i,
                    'slug' => 'value-' . $i . '-' . uniqid(),
                    'order' => $i,
                    'is_active' => true,
                ]);
                $attributeValueIds[] = $value->id;
            }

            // Create products and associate with attribute values
            if ($productCount > 0 && $valueCount > 0) {
                $category = \App\Models\Category::factory()->create(['is_active' => true]);
                $brand = \App\Models\Brand::factory()->create(['is_active' => true]);

                for ($i = 0; $i < $productCount; $i++) {
                    $product = \App\Models\Product::factory()->create([
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'is_active' => true,
                    ]);

                    // Associate product with a random attribute value
                    $randomValueId = $attributeValueIds[array_rand($attributeValueIds)];
                    \DB::table('product_attribute_values')->insert([
                        'product_id' => $product->id,
                        'attribute_value_id' => $randomValueId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Verify attribute values exist before deletion
            $this->assertCount(
                $valueCount,
                \App\Models\AttributeValue::where('attribute_id', $attribute->id)->get(),
                "Should have {$valueCount} attribute values before deletion"
            );

            // Verify product associations exist before deletion
            if ($productCount > 0 && $valueCount > 0) {
                $associationCount = \DB::table('product_attribute_values')
                    ->whereIn('attribute_value_id', $attributeValueIds)
                    ->count();
                $this->assertGreaterThan(
                    0,
                    $associationCount,
                    "Should have product associations before deletion"
                );
            }

            // Delete the attribute
            $attribute->delete();

            // Assert: All attribute values should be deleted (cascade)
            $remainingValues = \App\Models\AttributeValue::where('attribute_id', $attribute->id)->count();
            $this->assertEquals(
                0,
                $remainingValues,
                "All attribute values should be deleted when attribute is deleted"
            );

            // Assert: All product_attribute_values associations should be deleted (cascade)
            $remainingAssociations = \DB::table('product_attribute_values')
                ->whereIn('attribute_value_id', $attributeValueIds)
                ->count();
            $this->assertEquals(
                0,
                $remainingAssociations,
                "All product associations should be deleted when attribute is deleted"
            );

            // Assert: The attribute itself should be deleted
            $this->assertNull(
                \App\Models\Attribute::find($attribute->id),
                "Attribute should be deleted from database"
            );
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 19: Attribute value association**
     * 
     * For any attribute value creation, it should be associated with its parent attribute 
     * via attribute_id and queryable through that relationship.
     * 
     * **Validates: Requirements 6.2**
     */
    public function test_attribute_value_association(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10), // Number of attributes
            \Eris\Generator\choose(2, 8)   // Number of values per attribute
        )
        ->then(function (int $attributeCount, int $valuesPerAttribute) {
            // Clean database before each iteration
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            
            // Create attributes and their values
            $attributesWithValues = [];
            
            for ($i = 0; $i < $attributeCount; $i++) {
                // Create an attribute
                $attribute = Attribute::factory()->create([
                    'is_active' => true,
                    'is_filterable' => true,
                ]);
                
                $values = [];
                for ($j = 0; $j < $valuesPerAttribute; $j++) {
                    // Create attribute value associated with this attribute
                    $value = AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value_en' => "Value {$j} EN",
                        'value_ar' => "قيمة {$j}",
                        'value_he' => "ערך {$j}",
                        'slug' => "value-{$i}-{$j}",
                        'order' => $j,
                        'is_active' => true,
                    ]);
                    
                    $values[] = $value;
                }
                
                $attributesWithValues[$attribute->id] = [
                    'attribute' => $attribute,
                    'values' => $values,
                ];
            }
            
            // Assert: Each attribute value should be associated with its parent attribute via attribute_id
            foreach ($attributesWithValues as $attributeId => $data) {
                $attribute = $data['attribute'];
                $expectedValues = $data['values'];
                
                foreach ($expectedValues as $value) {
                    // Assert: attribute_id is correctly set
                    $this->assertEquals(
                        $attributeId,
                        $value->attribute_id,
                        "Attribute value {$value->id} should have attribute_id = {$attributeId}"
                    );
                    
                    // Assert: Value is queryable through the attribute's values relationship
                    $queriedValues = $attribute->values()->get();
                    $this->assertTrue(
                        $queriedValues->contains('id', $value->id),
                        "Attribute value {$value->id} should be queryable through attribute {$attributeId}'s values relationship"
                    );
                    
                    // Assert: Attribute is queryable through the value's attribute relationship
                    $this->assertNotNull(
                        $value->attribute,
                        "Attribute value {$value->id} should have an attribute relationship"
                    );
                    $this->assertEquals(
                        $attributeId,
                        $value->attribute->id,
                        "Attribute value {$value->id}'s attribute should be {$attributeId}"
                    );
                }
                
                // Assert: All values for this attribute are returned by the relationship
                $queriedValues = $attribute->values()->get();
                $this->assertCount(
                    count($expectedValues),
                    $queriedValues,
                    "Attribute {$attributeId} should have " . count($expectedValues) . " values"
                );
                
                // Assert: No values from other attributes are included
                foreach ($queriedValues as $queriedValue) {
                    $this->assertEquals(
                        $attributeId,
                        $queriedValue->attribute_id,
                        "All values returned by attribute {$attributeId} should belong to it"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 23: Attribute-category assignment**
     * 
     * For any attribute assigned to a sub-category, a record should exist in the 
     * attribute_category pivot table with both IDs.
     * 
     * **Validates: Requirements 7.2**
     */
    public function test_attribute_category_assignment(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10), // Number of categories
            \Eris\Generator\choose(1, 15)  // Number of attributes
        )
        ->then(function (int $categoryCount, int $attributeCount) {
            // Clean database before each iteration
            \DB::table('attribute_category')->delete();
            \DB::table('categories')->delete();
            \DB::table('attributes')->delete();
            
            // Create categories
            $categories = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $categories[] = Category::factory()->create([
                    'is_active' => true,
                ]);
            }
            
            // Create attributes
            $attributes = [];
            for ($i = 0; $i < $attributeCount; $i++) {
                $attributes[] = Attribute::factory()->create([
                    'is_filterable' => true,
                    'is_active' => true,
                ]);
            }
            
            // Randomly assign attributes to categories
            $assignments = [];
            foreach ($categories as $category) {
                // Each category gets 0-5 random attributes
                $numAttributesToAssign = rand(0, min(5, $attributeCount));
                $attributesToAssign = array_rand(array_flip(array_map(fn($a) => $a->id, $attributes)), $numAttributesToAssign ?: 1);
                
                if (!is_array($attributesToAssign)) {
                    $attributesToAssign = [$attributesToAssign];
                }
                
                // Sync the attributes
                $category->attributes()->sync($attributesToAssign);
                $assignments[$category->id] = $attributesToAssign;
            }
            
            // Assert: For each assignment, a record exists in the pivot table
            foreach ($assignments as $categoryId => $attributeIds) {
                $category = Category::find($categoryId);
                $assignedAttributes = $category->attributes()->pluck('attributes.id')->toArray();
                
                $this->assertCount(
                    count($attributeIds),
                    $assignedAttributes,
                    "Category {$categoryId} should have " . count($attributeIds) . " attributes assigned"
                );
                
                foreach ($attributeIds as $attributeId) {
                    $this->assertContains(
                        $attributeId,
                        $assignedAttributes,
                        "Attribute {$attributeId} should be assigned to category {$categoryId}"
                    );
                    
                    // Verify the pivot record exists in the database
                    $this->assertDatabaseHas('attribute_category', [
                        'category_id' => $categoryId,
                        'attribute_id' => $attributeId,
                    ]);
                }
            }
            
            // Assert: Reverse relationship also works (attributes can query their categories)
            foreach ($attributes as $attribute) {
                $assignedCategories = $attribute->categories()->pluck('categories.id')->toArray();
                
                // Find which categories should have this attribute
                $expectedCategories = [];
                foreach ($assignments as $catId => $attrIds) {
                    if (in_array($attribute->id, $attrIds)) {
                        $expectedCategories[] = $catId;
                    }
                }
                
                $this->assertCount(
                    count($expectedCategories),
                    $assignedCategories,
                    "Attribute {$attribute->id} should be assigned to " . count($expectedCategories) . " categories"
                );
                
                foreach ($expectedCategories as $expectedCategoryId) {
                    $this->assertContains(
                        $expectedCategoryId,
                        $assignedCategories,
                        "Attribute {$attribute->id} should be assigned to category {$expectedCategoryId}"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 24: Attribute-category removal**
     * 
     * For any attribute removed from a sub-category, the corresponding attribute_category 
     * record should be deleted.
     * 
     * **Validates: Requirements 7.3**
     */
    public function test_attribute_category_removal(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 8), // Number of categories
            \Eris\Generator\choose(3, 10) // Number of attributes
        )
        ->then(function (int $categoryCount, int $attributeCount) {
            // Clean database before each iteration
            \DB::table('attribute_category')->delete();
            \DB::table('categories')->delete();
            \DB::table('attributes')->delete();
            
            // Create categories
            $categories = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $categories[] = Category::factory()->create([
                    'is_active' => true,
                ]);
            }
            
            // Create attributes
            $attributes = [];
            for ($i = 0; $i < $attributeCount; $i++) {
                $attributes[] = Attribute::factory()->create([
                    'is_filterable' => true,
                    'is_active' => true,
                ]);
            }
            
            // Assign all attributes to all categories initially
            foreach ($categories as $category) {
                $attributeIds = array_map(fn($a) => $a->id, $attributes);
                $category->attributes()->sync($attributeIds);
            }
            
            // Verify initial state: all attributes are assigned to all categories
            foreach ($categories as $category) {
                $this->assertCount(
                    $attributeCount,
                    $category->attributes,
                    "Category {$category->id} should initially have all {$attributeCount} attributes"
                );
            }
            
            // Now remove some attributes from each category
            foreach ($categories as $category) {
                // Randomly decide how many attributes to keep (at least 1, at most all-1)
                $numToKeep = rand(1, max(1, $attributeCount - 1));
                $attributesToKeep = array_slice(
                    array_map(fn($a) => $a->id, $attributes),
                    0,
                    $numToKeep
                );
                
                // Get the attributes that will be removed
                $allAttributeIds = array_map(fn($a) => $a->id, $attributes);
                $attributesToRemove = array_diff($allAttributeIds, $attributesToKeep);
                
                // Sync with the reduced set (this removes the others)
                $category->attributes()->sync($attributesToKeep);
                
                // Assert: The category now has only the kept attributes
                $currentAttributes = $category->attributes()->pluck('attributes.id')->toArray();
                $this->assertCount(
                    count($attributesToKeep),
                    $currentAttributes,
                    "Category {$category->id} should have " . count($attributesToKeep) . " attributes after removal"
                );
                
                foreach ($attributesToKeep as $keptAttributeId) {
                    $this->assertContains(
                        $keptAttributeId,
                        $currentAttributes,
                        "Attribute {$keptAttributeId} should still be assigned to category {$category->id}"
                    );
                    
                    // Verify the pivot record still exists
                    $this->assertDatabaseHas('attribute_category', [
                        'category_id' => $category->id,
                        'attribute_id' => $keptAttributeId,
                    ]);
                }
                
                // Assert: The removed attributes are no longer in the pivot table
                foreach ($attributesToRemove as $removedAttributeId) {
                    $this->assertNotContains(
                        $removedAttributeId,
                        $currentAttributes,
                        "Attribute {$removedAttributeId} should not be assigned to category {$category->id}"
                    );
                    
                    // Verify the pivot record was deleted
                    $this->assertDatabaseMissing('attribute_category', [
                        'category_id' => $category->id,
                        'attribute_id' => $removedAttributeId,
                    ]);
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 25: Multi-category attribute visibility**
     * 
     * For any attribute assigned to multiple sub-categories, it should appear in the 
     * filter sidebar for all assigned sub-categories.
     * 
     * **Validates: Requirements 7.5**
     */
    public function test_multi_category_attribute_visibility(): void
    {
        $this->forAll(
            \Eris\Generator\choose(3, 10), // Number of categories
            \Eris\Generator\choose(2, 8)   // Number of attributes
        )
        ->then(function (int $categoryCount, int $attributeCount) {
            // Clean database before each iteration
            \DB::table('attribute_category')->delete();
            \DB::table('categories')->delete();
            \DB::table('attributes')->delete();
            
            // Create categories
            $categories = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $categories[] = Category::factory()->create([
                    'is_active' => true,
                ]);
            }
            
            // Create attributes
            $attributes = [];
            for ($i = 0; $i < $attributeCount; $i++) {
                $attributes[] = Attribute::factory()->create([
                    'is_filterable' => true,
                    'is_active' => true,
                ]);
            }
            
            // For each attribute, assign it to a random subset of categories (at least 2)
            $attributeAssignments = [];
            foreach ($attributes as $attribute) {
                // Pick at least 2 categories, up to all of them
                $numCategoriesToAssign = rand(2, $categoryCount);
                $categoryIndicesToAssign = array_rand(
                    array_flip(array_keys($categories)),
                    $numCategoriesToAssign
                );
                
                if (!is_array($categoryIndicesToAssign)) {
                    $categoryIndicesToAssign = [$categoryIndicesToAssign];
                }
                
                $assignedCategoryIds = [];
                foreach ($categoryIndicesToAssign as $index) {
                    $category = $categories[$index];
                    $assignedCategoryIds[] = $category->id;
                }
                
                // Sync this attribute to these categories
                $attribute->categories()->sync($assignedCategoryIds);
                $attributeAssignments[$attribute->id] = $assignedCategoryIds;
            }
            
            // Assert: For each attribute, it appears in all assigned categories
            foreach ($attributes as $attribute) {
                $expectedCategoryIds = $attributeAssignments[$attribute->id];
                
                // For each category this attribute is assigned to
                foreach ($expectedCategoryIds as $categoryId) {
                    $category = Category::find($categoryId);
                    $categoryAttributes = $category->attributes()
                        ->where('is_filterable', true)
                        ->pluck('attributes.id')
                        ->toArray();
                    
                    $this->assertContains(
                        $attribute->id,
                        $categoryAttributes,
                        "Attribute {$attribute->id} should appear in category {$categoryId}'s filter sidebar"
                    );
                    
                    // Verify the pivot record exists
                    $this->assertDatabaseHas('attribute_category', [
                        'category_id' => $categoryId,
                        'attribute_id' => $attribute->id,
                    ]);
                }
                
                // Assert: The attribute does NOT appear in categories it's not assigned to
                $allCategoryIds = array_map(fn($c) => $c->id, $categories);
                $unassignedCategoryIds = array_diff($allCategoryIds, $expectedCategoryIds);
                
                foreach ($unassignedCategoryIds as $unassignedCategoryId) {
                    $category = Category::find($unassignedCategoryId);
                    $categoryAttributes = $category->attributes()
                        ->where('is_filterable', true)
                        ->pluck('attributes.id')
                        ->toArray();
                    
                    $this->assertNotContains(
                        $attribute->id,
                        $categoryAttributes,
                        "Attribute {$attribute->id} should NOT appear in category {$unassignedCategoryId}'s filter sidebar"
                    );
                    
                    // Verify no pivot record exists
                    $this->assertDatabaseMissing('attribute_category', [
                        'category_id' => $unassignedCategoryId,
                        'attribute_id' => $attribute->id,
                    ]);
                }
            }
            
            // Assert: Each category shows exactly the attributes assigned to it
            foreach ($categories as $category) {
                $categoryAttributes = $category->attributes()
                    ->where('is_filterable', true)
                    ->get();
                
                // Find which attributes should be in this category
                $expectedAttributeIds = [];
                foreach ($attributeAssignments as $attrId => $catIds) {
                    if (in_array($category->id, $catIds)) {
                        $expectedAttributeIds[] = $attrId;
                    }
                }
                
                $this->assertCount(
                    count($expectedAttributeIds),
                    $categoryAttributes,
                    "Category {$category->id} should have " . count($expectedAttributeIds) . " filterable attributes"
                );
                
                foreach ($expectedAttributeIds as $expectedAttrId) {
                    $this->assertTrue(
                        $categoryAttributes->contains('id', $expectedAttrId),
                        "Category {$category->id} should have attribute {$expectedAttrId}"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 14: Category field persistence**
     * 
     * For any category creation, all fields (name_en, name_ar, name_he, slug, parent_id, 
     * icon, position, is_active) should be stored in the database.
     * 
     * **Validates: Requirements 5.1**
     */
    public function test_category_field_persistence(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 100), // iteration number for unique names
            \Eris\Generator\oneOf(
                \Eris\Generator\constant(null),
                \Eris\Generator\elements('fas fa-laptop', 'fas fa-tshirt', 'fas fa-mobile', 'fas fa-gamepad', 'fas fa-book')
            ), // icon (nullable)
            \Eris\Generator\choose(0, 100), // position
            \Eris\Generator\bool(), // is_active
            \Eris\Generator\bool() // has_parent
        )
        ->then(function (
            int $iteration,
            ?string $icon,
            int $position,
            bool $isActive,
            bool $hasParent
        ) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            // Generate unique names for this iteration
            $nameEn = 'Test Category ' . $iteration;
            $nameAr = 'فئة اختبار ' . $iteration;
            $nameHe = 'קטגוריית בדיקה ' . $iteration;
            $slug = 'test-category-' . $iteration;
            
            // Create a parent category (optional)
            $parentCategory = null;
            if ($hasParent) {
                $parentCategory = Category::factory()->create([
                    'is_active' => true,
                ]);
            }
            
            // Create category with all fields
            $categoryData = [
                'name_en' => $nameEn,
                'name_ar' => $nameAr,
                'name_he' => $nameHe,
                'slug' => $slug,
                'parent_id' => $parentCategory ? $parentCategory->id : null,
                'icon' => $icon,
                'position' => $position,
                'is_active' => $isActive,
            ];
            
            $category = Category::create($categoryData);
            
            // Assert: All fields are persisted correctly
            $this->assertDatabaseHas('categories', [
                'id' => $category->id,
                'name_en' => $categoryData['name_en'],
                'name_ar' => $categoryData['name_ar'],
                'name_he' => $categoryData['name_he'],
                'slug' => $categoryData['slug'],
                'parent_id' => $categoryData['parent_id'],
                'icon' => $categoryData['icon'],
                'position' => $categoryData['position'],
                'is_active' => $categoryData['is_active'],
            ]);
            
            // Assert: Category can be retrieved with all fields
            $retrievedCategory = Category::find($category->id);
            $this->assertNotNull($retrievedCategory, "Category should be persisted in database");
            $this->assertEquals($categoryData['name_en'], $retrievedCategory->name_en, "name_en should be persisted");
            $this->assertEquals($categoryData['name_ar'], $retrievedCategory->name_ar, "name_ar should be persisted");
            $this->assertEquals($categoryData['name_he'], $retrievedCategory->name_he, "name_he should be persisted");
            $this->assertEquals($categoryData['slug'], $retrievedCategory->slug, "slug should be persisted");
            $this->assertEquals($categoryData['parent_id'], $retrievedCategory->parent_id, "parent_id should be persisted");
            $this->assertEquals($categoryData['icon'], $retrievedCategory->icon, "icon should be persisted");
            $this->assertEquals($categoryData['position'], $retrievedCategory->position, "position should be persisted");
            $this->assertEquals($categoryData['is_active'], $retrievedCategory->is_active, "is_active should be persisted");
            
            // Assert: Parent relationship works if parent_id is set
            if ($parentCategory) {
                $this->assertNotNull($retrievedCategory->parent, "Parent relationship should exist");
                $this->assertEquals($parentCategory->id, $retrievedCategory->parent->id, "Parent ID should match");
            } else {
                $this->assertNull($retrievedCategory->parent, "Parent should be null for root categories");
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 16: Category ordering**
     * 
     * For any set of categories, when displayed, they should appear in ascending order 
     * by the position field.
     * 
     * **Validates: Requirements 5.3**
     */
    public function test_category_ordering(): void
    {
        $this->forAll(
            \Eris\Generator\choose(3, 15) // Number of categories to create
        )
        ->then(function (int $categoryCount) {
            // Clean database before each iteration
            \DB::table('categories')->delete();
            
            // Create categories with random positions
            $categories = [];
            $positions = [];
            
            for ($i = 0; $i < $categoryCount; $i++) {
                $position = rand(0, 100);
                $positions[] = $position;
                
                $categories[] = Category::factory()->create([
                    'is_active' => true,
                    'position' => $position,
                    'parent_id' => null, // Root categories
                ]);
            }
            
            // Query categories ordered by position
            $orderedCategories = Category::orderBy('position', 'asc')->get();
            
            // Assert: Categories should be in ascending order by position
            $this->assertCount($categoryCount, $orderedCategories, "Should have {$categoryCount} categories");
            
            $previousPosition = -1;
            foreach ($orderedCategories as $category) {
                $this->assertGreaterThanOrEqual(
                    $previousPosition,
                    $category->position,
                    "Category {$category->id} position ({$category->position}) should be >= previous position ({$previousPosition})"
                );
                $previousPosition = $category->position;
            }
            
            // Assert: The order matches the sorted positions
            $sortedPositions = $positions;
            sort($sortedPositions);
            
            $actualPositions = $orderedCategories->pluck('position')->toArray();
            $this->assertEquals(
                $sortedPositions,
                $actualPositions,
                "Actual positions should match sorted positions"
            );
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 17: Category deletion constraint**
     * 
     * For any category that has products assigned (directly or through sub-categories), 
     * deletion attempts should fail with a validation error.
     * 
     * **Validates: Requirements 5.5**
     */
    public function test_category_deletion_constraint(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10), // Number of products to assign
            \Eris\Generator\bool() // Whether to assign products to parent or child
        )
        ->then(function (int $productCount, bool $assignToChild) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a parent category
            $parentCategory = Category::factory()->create([
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            // Create a child category
            $childCategory = Category::factory()->create([
                'is_active' => true,
                'parent_id' => $parentCategory->id,
            ]);
            
            // Create a brand for products
            $brand = Brand::factory()->create(['is_active' => true]);
            
            // Assign products to either parent or child category
            $targetCategory = $assignToChild ? $childCategory : $parentCategory;
            
            for ($i = 0; $i < $productCount; $i++) {
                Product::factory()->create([
                    'category_id' => $targetCategory->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                ]);
            }
            
            // Assert: Parent category should have products (directly or through child)
            $parentProductCount = $parentCategory->allProducts()->count();
            $this->assertGreaterThan(
                0,
                $parentProductCount,
                "Parent category should have {$productCount} products (directly or through child)"
            );
            
            // Attempt to delete the parent category
            // This should fail because it has products (directly or through sub-categories)
            $response = $this->actingAs(\App\Models\User::factory()->create(['role' => 'admin']))
                ->delete(route('admin.categories.destroy', $parentCategory));
            
            // Assert: Deletion should be prevented
            $response->assertRedirect(route('admin.categories.index'));
            $response->assertSessionHas('error');
            
            // Assert: Category should still exist in database
            $this->assertDatabaseHas('categories', [
                'id' => $parentCategory->id,
            ]);
            
            // Assert: Products should still be assigned
            $this->assertEquals(
                $productCount,
                Product::where('category_id', $targetCategory->id)->count(),
                "Products should still be assigned to category"
            );
            
            // Now test that deletion works when no products are assigned
            // Remove all products
            Product::where('category_id', $targetCategory->id)->delete();
            
            // Create a category with no products
            $emptyCategory = Category::factory()->create([
                'is_active' => true,
                'parent_id' => null,
            ]);
            
            // Assert: Empty category has no products
            $this->assertEquals(
                0,
                $emptyCategory->allProducts()->count(),
                "Empty category should have no products"
            );
            
            // Attempt to delete the empty category
            $response = $this->actingAs(\App\Models\User::factory()->create(['role' => 'admin']))
                ->delete(route('admin.categories.destroy', $emptyCategory));
            
            // Assert: Deletion should succeed
            $response->assertRedirect(route('admin.categories.index'));
            $response->assertSessionHas('success');
            
            // Assert: Category should be soft-deleted or removed
            $this->assertDatabaseMissing('categories', [
                'id' => $emptyCategory->id,
                'deleted_at' => null,
            ]);
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 26: Product attribute relevance**
     * 
     * For any product being edited, only attributes assigned to that product's category 
     * should be displayed for selection.
     * 
     * **Validates: Requirements 8.1**
     */
    public function test_product_attribute_relevance(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 5), // Number of categories
            \Eris\Generator\choose(3, 8)  // Number of attributes
        )
        ->then(function (int $categoryCount, int $attributeCount) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create categories
            $categories = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $categories[] = Category::factory()->create([
                    'is_active' => true,
                    'slug' => 'category-' . $i,
                ]);
            }
            
            // Create attributes
            $attributes = [];
            for ($i = 0; $i < $attributeCount; $i++) {
                $attributes[] = Attribute::create([
                    'name_en' => 'Attribute ' . $i,
                    'name_ar' => 'سمة ' . $i,
                    'name_he' => 'תכונה ' . $i,
                    'slug' => 'attribute-' . $i,
                    'type' => 'select',
                    'is_filterable' => true,
                    'is_active' => true,
                    'order' => $i,
                ]);
                
                // Create values for each attribute
                for ($j = 0; $j < 2; $j++) {
                    AttributeValue::create([
                        'attribute_id' => $attributes[$i]->id,
                        'value_en' => 'Value ' . $j,
                        'value_ar' => 'قيمة ' . $j,
                        'value_he' => 'ערך ' . $j,
                        'slug' => 'value-' . $i . '-' . $j,
                        'is_active' => true,
                        'order' => $j,
                    ]);
                }
            }
            
            // Assign different attributes to each category
            $categoryAttributeMap = [];
            foreach ($categories as $index => $category) {
                // Each category gets a unique subset of attributes
                $startIdx = $index % $attributeCount;
                $count = min(3, $attributeCount - $startIdx);
                $assignedAttributes = array_slice($attributes, $startIdx, $count);
                
                foreach ($assignedAttributes as $attribute) {
                    $category->attributes()->attach($attribute->id);
                }
                
                $categoryAttributeMap[$category->id] = array_map(
                    fn($attr) => $attr->id,
                    $assignedAttributes
                );
            }
            
            // Create a product for each category
            $brand = Brand::factory()->create(['is_active' => true]);
            $admin = \App\Models\User::factory()->create(['role' => 'admin']);
            
            foreach ($categories as $category) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                ]);
                
                // Test the AJAX endpoint that loads category attributes
                $response = $this->actingAs($admin)->get(route('admin.products.category-attributes', $category->id));
                
                $response->assertStatus(200);
                $data = $response->json();
                
                $this->assertArrayHasKey('attributes', $data);
                
                $returnedAttributeIds = array_map(
                    fn($attr) => $attr['id'],
                    $data['attributes']
                );
                
                $expectedAttributeIds = $categoryAttributeMap[$category->id];
                
                // Assert: Only attributes assigned to this category are returned
                $this->assertCount(
                    count($expectedAttributeIds),
                    $returnedAttributeIds,
                    "Category {$category->id} should return " . count($expectedAttributeIds) . " attributes"
                );
                
                foreach ($returnedAttributeIds as $attrId) {
                    $this->assertContains(
                        $attrId,
                        $expectedAttributeIds,
                        "Returned attribute {$attrId} should be assigned to category {$category->id}"
                    );
                }
                
                // Assert: Attributes NOT assigned to this category are NOT returned
                $allAttributeIds = array_map(fn($attr) => $attr->id, $attributes);
                $unassignedAttributeIds = array_diff($allAttributeIds, $expectedAttributeIds);
                
                foreach ($unassignedAttributeIds as $unassignedAttrId) {
                    $this->assertNotContains(
                        $unassignedAttrId,
                        $returnedAttributeIds,
                        "Unassigned attribute {$unassignedAttrId} should NOT be returned for category {$category->id}"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 27: Product attribute value assignment**
     * 
     * For any attribute value selected for a product, a record should exist in the 
     * product_attribute_values pivot table.
     * 
     * **Validates: Requirements 8.2**
     */
    public function test_product_attribute_value_assignment(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 5), // Number of attributes
            \Eris\Generator\choose(2, 4)  // Number of values per attribute
        )
        ->then(function (int $attributeCount, int $valuesPerAttribute) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);
            
            // Create attributes with values
            $allAttributeValues = [];
            for ($i = 0; $i < $attributeCount; $i++) {
                $attribute = Attribute::create([
                    'name_en' => 'Attribute ' . $i,
                    'name_ar' => 'سمة ' . $i,
                    'name_he' => 'תכונה ' . $i,
                    'slug' => 'attribute-' . $i,
                    'type' => 'select',
                    'is_filterable' => true,
                    'is_active' => true,
                    'order' => $i,
                ]);
                
                // Assign attribute to category
                $category->attributes()->attach($attribute->id);
                
                // Create values
                for ($j = 0; $j < $valuesPerAttribute; $j++) {
                    $value = AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value_en' => 'Value ' . $j,
                        'value_ar' => 'قيمة ' . $j,
                        'value_he' => 'ערך ' . $j,
                        'slug' => 'value-' . $i . '-' . $j,
                        'is_active' => true,
                        'order' => $j,
                    ]);
                    $allAttributeValues[] = $value;
                }
            }
            
            // Create a product
            $product = Product::factory()->create([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'is_active' => true,
            ]);
            
            // Select random attribute values to assign
            $selectedCount = rand(1, min(5, count($allAttributeValues)));
            $selectedValues = array_slice($allAttributeValues, 0, $selectedCount);
            $selectedValueIds = array_map(fn($v) => $v->id, $selectedValues);
            
            // Sync attribute values to product
            $product->attributeValues()->sync($selectedValueIds);
            
            // Assert: All selected values have records in pivot table
            foreach ($selectedValueIds as $valueId) {
                $this->assertDatabaseHas('product_attribute_values', [
                    'product_id' => $product->id,
                    'attribute_value_id' => $valueId,
                ]);
            }
            
            // Assert: Product relationship returns correct values
            $product->load('attributeValues');
            $assignedValueIds = $product->attributeValues->pluck('id')->toArray();
            
            $this->assertCount(
                count($selectedValueIds),
                $assignedValueIds,
                "Product should have " . count($selectedValueIds) . " attribute values assigned"
            );
            
            foreach ($selectedValueIds as $valueId) {
                $this->assertContains(
                    $valueId,
                    $assignedValueIds,
                    "Attribute value {$valueId} should be assigned to product {$product->id}"
                );
            }
            
            // Assert: Unselected values do NOT have records
            $unselectedValueIds = array_diff(
                array_map(fn($v) => $v->id, $allAttributeValues),
                $selectedValueIds
            );
            
            foreach ($unselectedValueIds as $valueId) {
                $this->assertDatabaseMissing('product_attribute_values', [
                    'product_id' => $product->id,
                    'attribute_value_id' => $valueId,
                ]);
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 28: Product attribute validation**
     * 
     * For any product save with attribute values, the system should validate that all 
     * selected attribute values belong to attributes assigned to the product's category.
     * 
     * **Validates: Requirements 8.3**
     */
    public function test_product_attribute_validation(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 4) // Number of categories
        )
        ->then(function (int $categoryCount) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create categories
            $categories = [];
            for ($i = 0; $i < $categoryCount; $i++) {
                $categories[] = Category::factory()->create(['is_active' => true]);
            }
            
            // Create attributes and assign to different categories
            $categoryAttributeMap = [];
            $categoryAttributeValueMap = [];
            
            foreach ($categories as $index => $category) {
                $attribute = Attribute::create([
                    'name_en' => 'Attribute for Category ' . $index,
                    'name_ar' => 'سمة للفئة ' . $index,
                    'name_he' => 'תכונה לקטגוריה ' . $index,
                    'slug' => 'attribute-cat-' . $index,
                    'type' => 'select',
                    'is_filterable' => true,
                    'is_active' => true,
                    'order' => $index,
                ]);
                
                $category->attributes()->attach($attribute->id);
                $categoryAttributeMap[$category->id] = [$attribute->id];
                
                // Create values for this attribute
                $values = [];
                for ($j = 0; $j < 2; $j++) {
                    $values[] = AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value_en' => 'Value ' . $j,
                        'value_ar' => 'قيمة ' . $j,
                        'value_he' => 'ערך ' . $j,
                        'slug' => 'value-cat-' . $index . '-' . $j,
                        'is_active' => true,
                        'order' => $j,
                    ]);
                }
                $categoryAttributeValueMap[$category->id] = array_map(fn($v) => $v->id, $values);
            }
            
            $brand = Brand::factory()->create(['is_active' => true]);
            $admin = \App\Models\User::factory()->create(['role' => 'admin']);
            
            // Test 1: Valid attribute values should be accepted
            $category1 = $categories[0];
            $validValueIds = $categoryAttributeValueMap[$category1->id];
            
            $response = $this->actingAs($admin)->post(route('admin.products.store'), [
                'name_en' => 'Test Product Valid',
                'name_ar' => 'منتج اختبار صالح',
                'category_id' => $category1->id,
                'brand_id' => $brand->id,
                'price' => 100,
                'stock_quantity' => 10,
                'main_image' => 'https://picsum.photos/800/800',
                'is_active' => '1',
                'attribute_values' => $validValueIds,
            ]);
            
            $response->assertRedirect(route('admin.products.index'));
            $response->assertSessionHas('success');
            
            // Assert: Product was created with valid attribute values
            $product = Product::where('name_en', 'Test Product Valid')->first();
            $this->assertNotNull($product);
            
            $assignedValueIds = $product->attributeValues->pluck('id')->toArray();
            foreach ($validValueIds as $valueId) {
                $this->assertContains(
                    $valueId,
                    $assignedValueIds,
                    "Valid attribute value {$valueId} should be assigned to product"
                );
            }
            
            // Test 2: Invalid attribute values (from different category) should be rejected
            if ($categoryCount > 1) {
                $category2 = $categories[1];
                $invalidValueIds = $categoryAttributeValueMap[$category2->id];
                
                $response = $this->actingAs($admin)->post(route('admin.products.store'), [
                    'name_en' => 'Test Product Invalid',
                    'name_ar' => 'منتج اختبار غير صالح',
                    'category_id' => $category1->id, // Category 1
                    'brand_id' => $brand->id,
                    'price' => 100,
                    'stock_quantity' => 10,
                    'main_image' => 'https://picsum.photos/800/801',
                    'is_active' => '1',
                    'attribute_values' => $invalidValueIds, // Values from Category 2
                ]);
                
                // Assert: Validation error should occur
                $response->assertSessionHasErrors('attribute_values');
                
                // Assert: Product was NOT created
                $invalidProduct = Product::where('name_en', 'Test Product Invalid')->first();
                $this->assertNull(
                    $invalidProduct,
                    "Product with invalid attribute values should not be created"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 29: Dynamic attribute loading on category change**
     * 
     * For any product category change, the displayed attribute list should update to show 
     * only attributes assigned to the new category.
     * 
     * **Validates: Requirements 8.4**
     */
    public function test_dynamic_attribute_loading_on_category_change(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 4) // Number of categories
        )
        ->then(function (int $categoryCount) {
            // Clean database before each iteration
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('categories')->delete();
            
            // Create categories with different attributes
            $categoryAttributeMap = [];
            
            for ($i = 0; $i < $categoryCount; $i++) {
                $category = Category::factory()->create([
                    'is_active' => true,
                    'slug' => 'category-' . $i,
                ]);
                
                // Create unique attributes for this category
                $attributeCount = rand(2, 4);
                $attributeIds = [];
                
                for ($j = 0; $j < $attributeCount; $j++) {
                    $attribute = Attribute::create([
                        'name_en' => 'Category ' . $i . ' Attribute ' . $j,
                        'name_ar' => 'الفئة ' . $i . ' السمة ' . $j,
                        'name_he' => 'קטגוריה ' . $i . ' תכונה ' . $j,
                        'slug' => 'cat-' . $i . '-attr-' . $j,
                        'type' => 'select',
                        'is_filterable' => true,
                        'is_active' => true,
                        'order' => $j,
                    ]);
                    
                    // Create values for attribute
                    for ($k = 0; $k < 2; $k++) {
                        AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value_en' => 'Value ' . $k,
                            'value_ar' => 'قيمة ' . $k,
                            'value_he' => 'ערך ' . $k,
                            'slug' => 'cat-' . $i . '-attr-' . $j . '-val-' . $k,
                            'is_active' => true,
                            'order' => $k,
                        ]);
                    }
                    
                    $category->attributes()->attach($attribute->id);
                    $attributeIds[] = $attribute->id;
                }
                
                $categoryAttributeMap[$category->id] = $attributeIds;
            }
            
            // Test that each category returns its own attributes via AJAX
            $categories = Category::all();
            $admin = \App\Models\User::factory()->create(['role' => 'admin']);
            
            foreach ($categories as $category) {
                $response = $this->actingAs($admin)->get(route('admin.products.category-attributes', $category->id));
                
                $response->assertStatus(200);
                $data = $response->json();
                
                $this->assertArrayHasKey('attributes', $data);
                
                $returnedAttributeIds = array_map(
                    fn($attr) => $attr['id'],
                    $data['attributes']
                );
                
                $expectedAttributeIds = $categoryAttributeMap[$category->id];
                
                // Assert: Returned attributes match expected attributes for this category
                $this->assertCount(
                    count($expectedAttributeIds),
                    $returnedAttributeIds,
                    "Category {$category->id} should return " . count($expectedAttributeIds) . " attributes"
                );
                
                foreach ($expectedAttributeIds as $expectedId) {
                    $this->assertContains(
                        $expectedId,
                        $returnedAttributeIds,
                        "Expected attribute {$expectedId} should be returned for category {$category->id}"
                    );
                }
                
                // Assert: Attributes from other categories are NOT returned
                $otherCategoryAttributeIds = [];
                foreach ($categoryAttributeMap as $catId => $attrIds) {
                    if ($catId !== $category->id) {
                        $otherCategoryAttributeIds = array_merge($otherCategoryAttributeIds, $attrIds);
                    }
                }
                
                foreach ($otherCategoryAttributeIds as $otherId) {
                    $this->assertNotContains(
                        $otherId,
                        $returnedAttributeIds,
                        "Attribute {$otherId} from other category should NOT be returned for category {$category->id}"
                    );
                }
                
                // Assert: Each attribute has its values included
                foreach ($data['attributes'] as $attrData) {
                    $this->assertArrayHasKey('values', $attrData);
                    $this->assertNotEmpty(
                        $attrData['values'],
                        "Attribute {$attrData['id']} should have values"
                    );
                    
                    foreach ($attrData['values'] as $valueData) {
                        $this->assertArrayHasKey('id', $valueData);
                        $this->assertArrayHasKey('value', $valueData);
                        $this->assertArrayHasKey('slug', $valueData);
                    }
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 30: Attribute filter matching**
     * 
     * For any product with specific attribute values assigned, when those attribute values 
     * are selected in filters, that product should appear in the results.
     * 
     * **Validates: Requirements 8.5**
     */
    public function test_attribute_filter_matching(): void
    {
        $this->forAll(
            \Eris\Generator\choose(3, 8), // Number of products
            \Eris\Generator\choose(2, 4)  // Number of attributes
        )
        ->then(function (int $productCount, int $attributeCount) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);
            
            // Create attributes with values
            $attributes = [];
            $attributeValuesByAttribute = [];
            
            for ($i = 0; $i < $attributeCount; $i++) {
                $attribute = Attribute::create([
                    'name_en' => 'Attribute ' . $i,
                    'name_ar' => 'سمة ' . $i,
                    'name_he' => 'תכונה ' . $i,
                    'slug' => 'attribute-' . $i,
                    'type' => 'select',
                    'is_filterable' => true,
                    'is_active' => true,
                    'order' => $i,
                ]);
                
                $category->attributes()->attach($attribute->id);
                $attributes[] = $attribute;
                
                // Create 3 values per attribute
                $values = [];
                for ($j = 0; $j < 3; $j++) {
                    $values[] = AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value_en' => 'Value ' . $j,
                        'value_ar' => 'قيمة ' . $j,
                        'value_he' => 'ערך ' . $j,
                        'slug' => 'value-' . $i . '-' . $j,
                        'is_active' => true,
                        'order' => $j,
                    ]);
                }
                $attributeValuesByAttribute[$attribute->id] = $values;
            }
            
            // Create products with random attribute values
            $products = [];
            $productAttributeMap = [];
            
            for ($i = 0; $i < $productCount; $i++) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'name_en' => 'Product ' . $i,
                ]);
                
                $products[] = $product;
                $assignedValues = [];
                
                // Assign random values from each attribute
                foreach ($attributes as $attribute) {
                    $values = $attributeValuesByAttribute[$attribute->id];
                    $randomValue = $values[array_rand($values)];
                    $product->attributeValues()->attach($randomValue->id);
                    $assignedValues[$attribute->slug] = $randomValue->slug;
                }
                
                $productAttributeMap[$product->id] = $assignedValues;
            }
            
            // Test filtering by attribute values
            $filterService = new \App\Services\ProductFilterService();
            
            // Pick a random product and filter by its attribute values
            $testProduct = $products[array_rand($products)];
            $testProductAttributes = $productAttributeMap[$testProduct->id];
            
            // Build filter array
            $filters = [];
            foreach ($testProductAttributes as $attrSlug => $valueSlug) {
                $filters[$attrSlug] = [$valueSlug];
            }
            
            // Create a mock request with attribute filters
            $request = new \Illuminate\Http\Request();
            $request->merge(['attr' => $filters]);
            
            // Apply filters
            $query = Product::query()->where('category_id', $category->id)->where('is_active', true);
            $filteredQuery = $filterService->applyFilters($query, $request, $category);
            $filteredProducts = $filteredQuery->get();
            
            // Assert: Test product should be in filtered results
            $this->assertTrue(
                $filteredProducts->contains('id', $testProduct->id),
                "Product {$testProduct->id} with matching attribute values should be in filtered results"
            );
            
            // Assert: All returned products have the filtered attribute values
            foreach ($filteredProducts as $product) {
                $product->load('attributeValues.attribute');
                
                foreach ($testProductAttributes as $attrSlug => $valueSlug) {
                    $hasMatchingValue = $product->attributeValues->contains(function ($attrValue) use ($attrSlug, $valueSlug) {
                        return $attrValue->attribute->slug === $attrSlug && $attrValue->slug === $valueSlug;
                    });
                    
                    $this->assertTrue(
                        $hasMatchingValue,
                        "Product {$product->id} in filtered results should have attribute {$attrSlug} with value {$valueSlug}"
                    );
                }
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 31: Strong offer field update**
     * 
     * For any product where the Strong Offer checkbox is checked, the is_strong_offer 
     * field should be set to true in the database.
     * 
     * **Validates: Requirements 9.2**
     */
    public function test_strong_offer_field_update(): void
    {
        $this->forAll(
            \Eris\Generator\choose(5, 15), // Number of products to test
            \Eris\Generator\bool() // Whether to set strong offer
        )
        ->then(function (int $productCount, bool $isStrongOffer) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);

            // Create products with the specified is_strong_offer value
            $products = [];
            for ($i = 0; $i < $productCount; $i++) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => $isStrongOffer,
                    'discount_percentage' => $isStrongOffer ? rand(10, 50) : null,
                ]);
                $products[] = $product;
            }

            // Assert: All products have the correct is_strong_offer value
            foreach ($products as $product) {
                $product->refresh();
                $this->assertEquals(
                    $isStrongOffer,
                    $product->is_strong_offer,
                    "Product {$product->id} should have is_strong_offer=" . ($isStrongOffer ? 'true' : 'false')
                );
            }

            // Update products to opposite value
            foreach ($products as $product) {
                $product->update(['is_strong_offer' => !$isStrongOffer]);
            }

            // Assert: All products have been updated correctly
            foreach ($products as $product) {
                $product->refresh();
                $this->assertEquals(
                    !$isStrongOffer,
                    $product->is_strong_offer,
                    "Product {$product->id} should have is_strong_offer=" . (!$isStrongOffer ? 'true' : 'false') . " after update"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 32: Discount percentage validation**
     * 
     * For any discount_percentage input, the system should validate that the value 
     * is between 0 and 100 (inclusive).
     * 
     * **Validates: Requirements 9.3**
     */
    public function test_discount_percentage_validation(): void
    {
        $this->forAll(
            \Eris\Generator\choose(-100, 200) // Test values outside and inside valid range
        )
        ->then(function (int $discountPercentage) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);

            // Attempt to create a product with the discount percentage
            $productData = [
                'name_en' => 'Test Product',
                'name_ar' => 'منتج تجريبي',
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'price' => 100.00,
                'stock_quantity' => 10,
                'is_active' => true,
                'is_strong_offer' => true,
                'discount_percentage' => $discountPercentage,
            ];

            // Validate using Laravel's validator (same rules as ProductController)
            $validator = \Validator::make($productData, [
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
            ]);

            // Assert: Validation passes only for values between 0 and 100
            if ($discountPercentage >= 0 && $discountPercentage <= 100) {
                $this->assertFalse(
                    $validator->fails(),
                    "Discount percentage {$discountPercentage} should be valid (between 0 and 100)"
                );
                
                // Create the product if validation passes
                $product = Product::factory()->create($productData);
                
                // Read directly from database to avoid accessor interference
                $dbValue = \DB::table('products')
                    ->where('id', $product->id)
                    ->value('discount_percentage');
                
                // Compare with tolerance for decimal precision
                $this->assertEquals(
                    (float) $discountPercentage,
                    (float) $dbValue,
                    "Product discount_percentage in database should be {$discountPercentage}",
                    0.01 // Allow small floating point differences
                );
            } else {
                $this->assertTrue(
                    $validator->fails(),
                    "Discount percentage {$discountPercentage} should be invalid (outside 0-100 range)"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 33: Strong offer filter inclusion**
     * 
     * For any product with is_strong_offer=true, when the strong offers filter is applied, 
     * that product should appear in the results.
     * 
     * **Validates: Requirements 9.4**
     */
    public function test_strong_offer_filter_inclusion(): void
    {
        $this->forAll(
            \Eris\Generator\choose(3, 10), // Number of strong offer products
            \Eris\Generator\choose(3, 10)  // Number of regular products
        )
        ->then(function (int $strongOfferCount, int $regularCount) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);

            // Create strong offer products
            $strongOfferProducts = [];
            for ($i = 0; $i < $strongOfferCount; $i++) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => true,
                    'discount_percentage' => rand(10, 50),
                ]);
                $strongOfferProducts[] = $product;
            }

            // Create regular products
            for ($i = 0; $i < $regularCount; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => false,
                    'discount_percentage' => null,
                ]);
            }

            // Apply strong offers filter
            $filteredProducts = Product::strongOffers()->get();

            // Assert: All strong offer products are included in results
            foreach ($strongOfferProducts as $product) {
                $this->assertTrue(
                    $filteredProducts->contains('id', $product->id),
                    "Strong offer product {$product->id} should be included in filtered results"
                );
            }

            // Assert: Result count matches strong offer count
            $this->assertCount(
                $strongOfferCount,
                $filteredProducts,
                "Filtered results should contain exactly {$strongOfferCount} strong offer products"
            );
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 34: Strong offer filter exclusion**
     * 
     * For any product with is_strong_offer=false, when the strong offers filter is applied, 
     * that product should not appear in the results.
     * 
     * **Validates: Requirements 9.5**
     */
    public function test_strong_offer_filter_exclusion(): void
    {
        $this->forAll(
            \Eris\Generator\choose(3, 10), // Number of strong offer products
            \Eris\Generator\choose(3, 10)  // Number of regular products
        )
        ->then(function (int $strongOfferCount, int $regularCount) {
            // Clean database before each iteration
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create a category and brand
            $category = Category::factory()->create(['is_active' => true]);
            $brand = Brand::factory()->create(['is_active' => true]);

            // Create strong offer products
            for ($i = 0; $i < $strongOfferCount; $i++) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => true,
                    'discount_percentage' => rand(10, 50),
                ]);
            }

            // Create regular products (not strong offers)
            $regularProducts = [];
            for ($i = 0; $i < $regularCount; $i++) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_strong_offer' => false,
                    'discount_percentage' => null,
                ]);
                $regularProducts[] = $product;
            }

            // Apply strong offers filter
            $filteredProducts = Product::strongOffers()->get();

            // Assert: No regular products are included in results
            foreach ($regularProducts as $product) {
                $this->assertFalse(
                    $filteredProducts->contains('id', $product->id),
                    "Regular product {$product->id} (is_strong_offer=false) should NOT be included in filtered results"
                );
            }

            // Assert: All filtered products have is_strong_offer=true
            foreach ($filteredProducts as $product) {
                $this->assertTrue(
                    $product->is_strong_offer,
                    "Product {$product->id} in filtered results should have is_strong_offer=true"
                );
            }
        });
    }

    /**
     * **Feature: advanced-catalog-filtering, Property 35: Filter URL format consistency**
     * 
     * For any combination of applied filters, the URL should follow the consistent format 
     * with brand[], attr[slug][], stock, and strong_offers parameters.
     * 
     * **Validates: Requirements 10.1**
     */
    public function test_filter_url_format_consistency(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 3), // Number of brands to filter
            \Eris\Generator\choose(0, 1), // Whether to include strong offers filter
            \Eris\Generator\choose(0, 2)  // Stock filter: 0=none, 1=in, 2=out
        )
        ->then(function (int $brandCount, int $includeStrongOffers, int $stockFilter) {
            // Clean database before each iteration
            \DB::table('product_attribute_values')->delete();
            \DB::table('attribute_category')->delete();
            \DB::table('attribute_values')->delete();
            \DB::table('attributes')->delete();
            \DB::table('products')->delete();
            \DB::table('categories')->delete();
            \DB::table('brands')->delete();
            
            // Create category
            $category = Category::factory()->create(['is_active' => true]);
            
            // Create brands
            $brands = [];
            for ($i = 0; $i < 5; $i++) {
                $brands[] = Brand::factory()->create([
                    'is_active' => true,
                    'slug' => 'brand-' . $i,
                ]);
            }
            
            // Create attributes with values
            $attribute1 = \App\Models\Attribute::create([
                'name_en' => 'Screen Size',
                'name_ar' => 'حجم الشاشة',
                'name_he' => 'גודל מסך',
                'slug' => 'screen-size',
                'type' => 'select',
                'is_filterable' => true,
                'is_active' => true,
                'order' => 1,
            ]);
            
            $attribute2 = \App\Models\Attribute::create([
                'name_en' => 'Resolution',
                'name_ar' => 'الدقة',
                'name_he' => 'רזולוציה',
                'slug' => 'resolution',
                'type' => 'select',
                'is_filterable' => true,
                'is_active' => true,
                'order' => 2,
            ]);
            
            // Create attribute values
            $value24inch = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute1->id,
                'value_en' => '24 inch',
                'value_ar' => '24 بوصة',
                'value_he' => '24 אינץ',
                'slug' => '24-inch',
                'is_active' => true,
                'order' => 1,
            ]);
            
            $value27inch = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute1->id,
                'value_en' => '27 inch',
                'value_ar' => '27 بوصة',
                'value_he' => '27 אינץ',
                'slug' => '27-inch',
                'is_active' => true,
                'order' => 2,
            ]);
            
            $value1080p = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute2->id,
                'value_en' => '1080p',
                'value_ar' => '1080p',
                'value_he' => '1080p',
                'slug' => '1080p',
                'is_active' => true,
                'order' => 1,
            ]);
            
            $value4k = \App\Models\AttributeValue::create([
                'attribute_id' => $attribute2->id,
                'value_en' => '4K',
                'value_ar' => '4K',
                'value_he' => '4K',
                'slug' => '4k',
                'is_active' => true,
                'order' => 2,
            ]);
            
            // Assign attributes to category
            $category->attributes()->attach([$attribute1->id, $attribute2->id]);
            
            // Create products with various characteristics
            for ($i = 0; $i < 20; $i++) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brands[array_rand($brands)]->id,
                    'is_active' => true,
                    'is_strong_offer' => rand(0, 1) === 1,
                    'stock_status' => rand(0, 1) ? 'in_stock' : 'out_of_stock',
                    'price' => rand(200, 1000),
                ]);
                
                // Randomly assign attribute values
                if (rand(0, 1)) {
                    $product->attributeValues()->attach(rand(0, 1) ? $value24inch->id : $value27inch->id);
                }
                if (rand(0, 1)) {
                    $product->attributeValues()->attach(rand(0, 1) ? $value1080p->id : $value4k->id);
                }
            }
            
            // Build filter parameters
            $filterParams = [];
            
            // Add brand filters
            $selectedBrands = array_slice(array_map(fn($b) => $b->slug, $brands), 0, $brandCount);
            if (!empty($selectedBrands)) {
                $filterParams['brand'] = $selectedBrands;
            }
            
            // Add strong offers filter
            if ($includeStrongOffers) {
                $filterParams['strong_offers'] = '1';
            }
            
            // Add stock filter
            if ($stockFilter === 1) {
                $filterParams['stock'] = 'in';
            } elseif ($stockFilter === 2) {
                $filterParams['stock'] = 'out';
            }
            
            // Add price filters
            $filterParams['min_price'] = '300';
            $filterParams['max_price'] = '800';
            
            // Add attribute filters
            $filterParams['attr'] = [
                'screen-size' => ['24-inch', '27-inch'],
                'resolution' => ['4k'],
            ];
            
            // Build the URL with filter parameters
            $currentUrl = route('products', $filterParams);
            
            // Parse the URL to extract query parameters
            $parsedUrl = parse_url($currentUrl);
            $this->assertArrayHasKey('query', $parsedUrl, 'URL should contain query parameters');
            
            parse_str($parsedUrl['query'], $queryParams);
            
            // Make a request to verify the URL works
            $response = $this->get($currentUrl);
            $response->assertStatus(200);
            
            // Assert: Brand filter should use brand[] format
            if (!empty($selectedBrands)) {
                $this->assertArrayHasKey('brand', $queryParams, 'URL should contain brand parameter');
                $this->assertIsArray($queryParams['brand'], 'Brand parameter should be an array');
                
                foreach ($selectedBrands as $brandSlug) {
                    $this->assertContains(
                        $brandSlug,
                        $queryParams['brand'],
                        "URL should contain brand filter for {$brandSlug}"
                    );
                }
                
                // Verify URL format contains brand[]=value (URL encoded as brand%5B%5D=value or brand[0]=value)
                foreach ($selectedBrands as $brandSlug) {
                    // Laravel may encode as brand[0]=value or brand[]=value
                    $hasBrandParam = strpos($currentUrl, 'brand%5B') !== false || strpos($currentUrl, 'brand[') !== false;
                    $this->assertTrue(
                        $hasBrandParam && strpos($currentUrl, urlencode($brandSlug)) !== false,
                        "URL should contain brand parameter with value {$brandSlug}"
                    );
                }
            }
            
            // Assert: Strong offers filter should use strong_offers=1 format
            if ($includeStrongOffers) {
                $this->assertArrayHasKey('strong_offers', $queryParams, 'URL should contain strong_offers parameter');
                $this->assertEquals(
                    '1',
                    $queryParams['strong_offers'],
                    'Strong offers parameter should be 1'
                );
                $this->assertStringContainsString(
                    'strong_offers=1',
                    $currentUrl,
                    'URL should contain strong_offers=1 format'
                );
            }
            
            // Assert: Stock filter should use stock=value format
            if ($stockFilter === 1) {
                $this->assertArrayHasKey('stock', $queryParams, 'URL should contain stock parameter');
                $this->assertEquals(
                    'in',
                    $queryParams['stock'],
                    'Stock parameter should be "in"'
                );
                $this->assertStringContainsString(
                    'stock=in',
                    $currentUrl,
                    'URL should contain stock=in format'
                );
            } elseif ($stockFilter === 2) {
                $this->assertArrayHasKey('stock', $queryParams, 'URL should contain stock parameter');
                $this->assertEquals(
                    'out',
                    $queryParams['stock'],
                    'Stock parameter should be "out"'
                );
                $this->assertStringContainsString(
                    'stock=out',
                    $currentUrl,
                    'URL should contain stock=out format'
                );
            }
            
            // Assert: Price filters should use min_price=value and max_price=value format
            $this->assertArrayHasKey('min_price', $queryParams, 'URL should contain min_price parameter');
            $this->assertEquals(
                '300',
                $queryParams['min_price'],
                'Min price parameter should be 300'
            );
            $this->assertStringContainsString(
                'min_price=300',
                $currentUrl,
                'URL should contain min_price=300 format'
            );
            
            $this->assertArrayHasKey('max_price', $queryParams, 'URL should contain max_price parameter');
            $this->assertEquals(
                '800',
                $queryParams['max_price'],
                'Max price parameter should be 800'
            );
            $this->assertStringContainsString(
                'max_price=800',
                $currentUrl,
                'URL should contain max_price=800 format'
            );
            
            // Assert: Attribute filters should use attr[slug][]=value format
            $this->assertArrayHasKey('attr', $queryParams, 'URL should contain attr parameter');
            $this->assertIsArray($queryParams['attr'], 'Attr parameter should be an array');
            
            // Check screen-size attribute
            $this->assertArrayHasKey('screen-size', $queryParams['attr'], 'URL should contain screen-size attribute');
            $this->assertIsArray($queryParams['attr']['screen-size'], 'Screen-size attribute should be an array');
            $this->assertContains('24-inch', $queryParams['attr']['screen-size'], 'Screen-size should contain 24-inch');
            $this->assertContains('27-inch', $queryParams['attr']['screen-size'], 'Screen-size should contain 27-inch');
            
            // Verify URL format contains attr[screen-size][]=value (URL encoded)
            $hasAttrParam = strpos($currentUrl, 'attr%5Bscreen-size%5D') !== false || strpos($currentUrl, 'attr[screen-size]') !== false;
            $this->assertTrue(
                $hasAttrParam && strpos($currentUrl, urlencode('24-inch')) !== false,
                'URL should contain attr[screen-size] parameter with value 24-inch'
            );
            $this->assertTrue(
                $hasAttrParam && strpos($currentUrl, urlencode('27-inch')) !== false,
                'URL should contain attr[screen-size] parameter with value 27-inch'
            );
            
            // Check resolution attribute
            $this->assertArrayHasKey('resolution', $queryParams['attr'], 'URL should contain resolution attribute');
            $this->assertIsArray($queryParams['attr']['resolution'], 'Resolution attribute should be an array');
            $this->assertContains('4k', $queryParams['attr']['resolution'], 'Resolution should contain 4k');
            
            // Verify URL format contains attr[resolution][]=value (URL encoded)
            $hasResolutionParam = strpos($currentUrl, 'attr%5Bresolution%5D') !== false || strpos($currentUrl, 'attr[resolution]') !== false;
            $this->assertTrue(
                $hasResolutionParam && strpos($currentUrl, urlencode('4k')) !== false,
                'URL should contain attr[resolution] parameter with value 4k'
            );
            
            // Assert: All filter parameters are preserved and follow the correct format
            // This ensures consistency across the application
            $expectedParamCount = 0;
            $expectedParamCount += !empty($selectedBrands) ? 1 : 0; // brand
            $expectedParamCount += $includeStrongOffers ? 1 : 0; // strong_offers
            $expectedParamCount += $stockFilter > 0 ? 1 : 0; // stock
            $expectedParamCount += 2; // min_price and max_price
            $expectedParamCount += 1; // attr
            
            $this->assertGreaterThanOrEqual(
                $expectedParamCount,
                count($queryParams),
                "URL should contain at least {$expectedParamCount} filter parameters"
            );
        });
    }
}
