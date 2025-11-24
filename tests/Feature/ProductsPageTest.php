<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductsPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the products page loads successfully with filter sidebar
     */
    public function test_products_page_loads_with_filter_sidebar(): void
    {
        // Create test data
        $category = Category::factory()->create(['is_active' => true]);
        $brand = Brand::factory()->create(['is_active' => true]);
        Product::factory()->count(5)->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
        ]);

        // Visit the products page
        $response = $this->get(route('products'));

        // Assert the page loads successfully
        $response->assertStatus(200);

        // Assert the filter sidebar component is present
        $response->assertSee('Filters');
        $response->assertSee('Price Range');
        $response->assertSee('Brands');
        $response->assertSee('Categories');
    }

    /**
     * Test that the products page displays "no results" message when no products match filters
     */
    public function test_products_page_shows_no_results_message(): void
    {
        // Create test data but don't create any products
        Category::factory()->create(['is_active' => true]);
        Brand::factory()->create(['is_active' => true]);

        // Visit the products page
        $response = $this->get(route('products'));

        // Assert the page loads successfully
        $response->assertStatus(200);

        // Assert the no results message is displayed
        $response->assertSee('No Results Found');
    }

    /**
     * Test that the products page is responsive (mobile filter toggle button exists)
     */
    public function test_products_page_has_mobile_filter_toggle(): void
    {
        // Create minimal test data
        $category = Category::factory()->create(['is_active' => true]);
        $brand = Brand::factory()->create(['is_active' => true]);
        Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
        ]);

        // Visit the products page
        $response = $this->get(route('products'));

        // Assert the page loads successfully
        $response->assertStatus(200);

        // Assert the mobile filter toggle button is present
        $response->assertSee('Filter Products');
        $response->assertSee('mobile-filter-toggle');
    }
}
