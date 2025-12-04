<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Category Display Mode Management
 * Feature: category-nav-management
 */
class CategoryDisplayModePropertyTest extends TestCase
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
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'is_active' => true,
            'position' => rand(0, 100),
        ], $overrides);
    }

    /**
     * **Feature: category-nav-management, Property 5: Default Display Mode**
     * *For any* newly created category without explicit display_mode, 
     * the system assigns 'carousel' as the default value.
     * **Validates: Requirements 1.4**
     * 
     * @test
     */
    public function property_default_display_mode_is_carousel(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            $categoryData = $this->generateRandomCategoryData();
            // Explicitly NOT setting display_mode
            unset($categoryData['display_mode']);
            
            $category = Category::create($categoryData);
            
            $this->assertEquals(
                'carousel',
                $category->display_mode,
                "Category '{$category->name_en}' should default to 'carousel' display mode"
            );
            
            // Clean up for next iteration
            $category->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-management, Property 1: Display Mode Segregation**
     * *For any* active parent category, if its display_mode is 'carousel' then it 
     * appears only in carousel scope, and if its display_mode is 'nav' then it 
     * appears only in nav scope.
     * **Validates: Requirements 1.2, 1.3, 4.1, 4.2**
     * 
     * @test
     */
    public function property_display_mode_segregation(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            $displayMode = rand(0, 1) ? 'carousel' : 'nav';
            $categoryData = $this->generateRandomCategoryData(['display_mode' => $displayMode]);
            
            $category = Category::create($categoryData);
            
            if ($displayMode === 'carousel') {
                $this->assertTrue(
                    Category::carousel()->where('id', $category->id)->exists(),
                    "Category with display_mode 'carousel' should appear in carousel scope"
                );
                $this->assertFalse(
                    Category::nav()->where('id', $category->id)->exists(),
                    "Category with display_mode 'carousel' should NOT appear in nav scope"
                );
            } else {
                $this->assertTrue(
                    Category::nav()->where('id', $category->id)->exists(),
                    "Category with display_mode 'nav' should appear in nav scope"
                );
                $this->assertFalse(
                    Category::carousel()->where('id', $category->id)->exists(),
                    "Category with display_mode 'nav' should NOT appear in carousel scope"
                );
            }
            
            // Clean up for next iteration
            $category->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-management, Property 2: Nav Children Rendering**
     * *For any* parent category with display_mode 'nav' and active children, 
     * all those children are associated with that parent.
     * **Validates: Requirements 1.5, 2.1**
     * 
     * @test
     */
    public function property_nav_children_rendering(): void
    {
        // Run 50 iterations (fewer due to more complex setup)
        for ($i = 0; $i < 50; $i++) {
            // Create parent with nav mode
            $parentData = $this->generateRandomCategoryData(['display_mode' => 'nav']);
            $parent = Category::create($parentData);
            
            // Create random number of children (1-5)
            $childCount = rand(1, 5);
            $children = [];
            
            for ($j = 0; $j < $childCount; $j++) {
                $childData = $this->generateRandomCategoryData([
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
                $children[] = Category::create($childData);
            }
            
            // Verify all children are associated with parent
            $parentChildren = $parent->children()->where('is_active', true)->get();
            
            $this->assertCount(
                $childCount,
                $parentChildren,
                "Parent should have exactly {$childCount} active children"
            );
            
            foreach ($children as $child) {
                $this->assertTrue(
                    $parentChildren->contains('id', $child->id),
                    "Child category should be in parent's children collection"
                );
            }
            
            // Clean up
            foreach ($children as $child) {
                $child->forceDelete();
            }
            $parent->forceDelete();
        }
    }

    /**
     * **Feature: category-nav-management, Property 3: Position-Based Ordering**
     * *For any* set of categories, they are ordered by position ascending, 
     * then alphabetically by name for equal positions.
     * **Validates: Requirements 2.4, 3.2, 3.3**
     * 
     * @test
     */
    public function property_position_based_ordering(): void
    {
        // Run 50 iterations
        for ($i = 0; $i < 50; $i++) {
            $categories = [];
            $categoryCount = rand(3, 8);
            
            // Create categories with various positions
            for ($j = 0; $j < $categoryCount; $j++) {
                $position = rand(0, 5); // Limited range to ensure some duplicates
                $categoryData = $this->generateRandomCategoryData([
                    'position' => $position,
                    'display_mode' => 'nav',
                ]);
                $categories[] = Category::create($categoryData);
            }
            
            // Get ordered categories
            $orderedCategories = Category::nav()
                ->orderBy('position', 'asc')
                ->orderBy('name_en', 'asc')
                ->get();
            
            // Verify ordering
            $previousPosition = -1;
            $previousName = '';
            
            foreach ($orderedCategories as $category) {
                if ($category->position === $previousPosition) {
                    // Same position - should be alphabetically ordered
                    $this->assertGreaterThanOrEqual(
                        0,
                        strcmp($category->name_en, $previousName),
                        "Categories with same position should be ordered alphabetically"
                    );
                } else {
                    // Different position - should be ascending
                    $this->assertGreaterThanOrEqual(
                        $previousPosition,
                        $category->position,
                        "Categories should be ordered by position ascending"
                    );
                }
                
                $previousPosition = $category->position;
                $previousName = $category->name_en;
            }
            
            // Clean up
            foreach ($categories as $category) {
                $category->forceDelete();
            }
        }
    }

    /**
     * **Feature: category-nav-management, Property 4: Childless Nav Direct Link**
     * *For any* parent category with display_mode 'nav' and no active children, 
     * it should have zero children count.
     * **Validates: Requirements 2.3**
     * 
     * @test
     */
    public function property_childless_nav_categories(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            $categoryData = $this->generateRandomCategoryData(['display_mode' => 'nav']);
            $category = Category::create($categoryData);
            
            // Verify no children
            $activeChildrenCount = $category->children()->where('is_active', true)->count();
            
            $this->assertEquals(
                0,
                $activeChildrenCount,
                "Newly created nav category should have no children"
            );
            
            // Clean up
            $category->forceDelete();
        }
    }
}
