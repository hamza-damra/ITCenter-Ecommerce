# Design Document: Category Navigation Fix

## Overview

This design document outlines the technical approach for fixing and standardizing the category navigation system in the IT Center e-commerce application. The primary issue is that navbar child-category links are broken while under-banner category links work correctly. The fix involves:

1. Updating the category resolution logic to properly handle hierarchical categories
2. Modifying product fetching to include descendant categories
3. Ensuring consistent link generation across all navigation components
4. Integrating tag filtering without breaking category navigation

## Architecture

The category navigation system follows a layered architecture:

```
┌─────────────────────────────────────────────────────────────────┐
│                        Presentation Layer                        │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │   Category Nav   │  │ Under-Banner    │  │   Breadcrumbs   │  │
│  │   Component      │  │ Categories      │  │   Component     │  │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘  │
│           │                    │                    │            │
│           └────────────────────┼────────────────────┘            │
│                                │                                 │
│                    route('category.show', [...])                 │
└────────────────────────────────┼─────────────────────────────────┘
                                 │
┌────────────────────────────────┼─────────────────────────────────┐
│                         Routing Layer                            │
│                                │                                 │
│    /category/{parentSlug}/{childSlug?}/{subChildSlug?}          │
│                                │                                 │
└────────────────────────────────┼─────────────────────────────────┘
                                 │
┌────────────────────────────────┼─────────────────────────────────┐
│                       Controller Layer                           │
│                                │                                 │
│  ┌─────────────────────────────┴─────────────────────────────┐  │
│  │              CategoryController::show()                    │  │
│  │  - loadCategory() → resolves hierarchy                     │  │
│  │  - getCategoryWithDescendants() → gets all category IDs    │  │
│  │  - buildBreadcrumbs() → generates navigation trail         │  │
│  └─────────────────────────────┬─────────────────────────────┘  │
└────────────────────────────────┼─────────────────────────────────┘
                                 │
┌────────────────────────────────┼─────────────────────────────────┐
│                        Service Layer                             │
│                                │                                 │
│  ┌─────────────────────────────┴─────────────────────────────┐  │
│  │              ProductFilterService                          │  │
│  │  - applyCategoryFilter() → filters by category IDs         │  │
│  │  - applyTagFilter() → filters by tag slug                  │  │
│  │  - getAvailableFilters() → returns filter options          │  │
│  └─────────────────────────────┬─────────────────────────────┘  │
└────────────────────────────────┼─────────────────────────────────┘
                                 │
┌────────────────────────────────┼─────────────────────────────────┐
│                         Model Layer                              │
│                                │                                 │
│  ┌──────────────┐  ┌──────────┴───────┐  ┌──────────────────┐   │
│  │   Category   │──│     Product      │──│       Tag        │   │
│  │  (hierarchy) │  │ (category_id)    │  │ (many-to-many)   │   │
│  └──────────────┘  └──────────────────┘  └──────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### 1. Route Definition (routes/web.php)

**Current Route:**
```php
Route::get('/category/{parentSlug}/{childSlug?}', [CategoryController::class, 'show'])->name('category.show');
```

**Updated Route (supports 3 levels):**
```php
Route::get('/category/{parentSlug}/{childSlug?}/{subChildSlug?}', [CategoryController::class, 'show'])->name('category.show');
```

### 2. CategoryController Interface

```php
class CategoryController extends Controller
{
    /**
     * Show category products with filtering
     * 
     * @param Request $request
     * @param string $parentSlug - Required parent category slug
     * @param string|null $childSlug - Optional child category slug
     * @param string|null $subChildSlug - Optional sub-child category slug
     * @return View
     */
    public function show(Request $request, string $parentSlug, ?string $childSlug = null, ?string $subChildSlug = null): View;
    
    /**
     * Load and validate category hierarchy
     * 
     * @param string $parentSlug
     * @param string|null $childSlug
     * @param string|null $subChildSlug
     * @return Category
     * @throws ModelNotFoundException
     */
    protected function loadCategory(string $parentSlug, ?string $childSlug, ?string $subChildSlug): Category;
    
    /**
     * Get all descendant category IDs for product fetching
     * 
     * @param Category $category
     * @return array<int> Array of category IDs including the category itself
     */
    protected function getCategoryWithDescendantIds(Category $category): array;
    
    /**
     * Build breadcrumb navigation array
     * 
     * @param Category $category
     * @return array<array{name: string, url: string|null}>
     */
    protected function buildBreadcrumbs(Category $category): array;
}
```

### 3. ProductFilterService Interface Update

```php
class ProductFilterService
{
    /**
     * Apply category filter supporting multiple category IDs
     * 
     * @param Builder $query
     * @param array<int> $categoryIds - Array of category IDs to include
     * @return Builder
     */
    protected function applyCategoryFilter(Builder $query, array $categoryIds): Builder;
}
```

### 4. Category Model Interface

```php
class Category extends Model
{
    /**
     * Get all descendant categories (children and sub-children)
     * 
     * @return Collection<Category>
     */
    public function descendants(): Collection;
    
    /**
     * Get all ancestor categories (parent chain)
     * 
     * @return Collection<Category>
     */
    public function ancestors(): Collection;
    
    /**
     * Get the full URL path for this category
     * 
     * @return string
     */
    public function getUrlAttribute(): string;
}
```

## Data Models

### Category Hierarchy

```
categories table:
┌────┬──────────────────┬───────────┬───────────┐
│ id │ slug             │ parent_id │ is_active │
├────┼──────────────────┼───────────┼───────────┤
│ 1  │ electronics      │ NULL      │ true      │  ← Parent
│ 2  │ computers        │ 1         │ true      │  ← Child of 1
│ 3  │ laptops          │ 2         │ true      │  ← Sub-child of 2
│ 4  │ gaming           │ NULL      │ true      │  ← Parent
│ 5  │ gaming-keyboards │ 4         │ true      │  ← Child of 4
└────┴──────────────────┴───────────┴───────────┘

URL Patterns:
- /category/electronics           → Shows products from 1, 2, 3
- /category/electronics/computers → Shows products from 2, 3
- /category/electronics/computers/laptops → Shows products from 3 only
```

### Product-Category-Tag Relationships

```
products table:
┌────┬─────────────┬─────────────┐
│ id │ category_id │ is_active   │
├────┼─────────────┼─────────────┤
│ 1  │ 3           │ true        │  ← In "laptops" sub-child
│ 2  │ 2           │ true        │  ← In "computers" child
│ 3  │ 1           │ true        │  ← In "electronics" parent
└────┴─────────────┴─────────────┘

product_tag pivot table:
┌────────────┬────────┐
│ product_id │ tag_id │
├────────────┼────────┤
│ 1          │ 1      │  ← Product 1 has tag "gaming"
│ 1          │ 2      │  ← Product 1 has tag "portable"
│ 2          │ 1      │  ← Product 2 has tag "gaming"
└────────────┴────────┘
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Category Hierarchy Resolution

*For any* valid category hierarchy (parent → child → sub-child), navigating to the URL with the correct slug path should resolve to the exact target category, and the resolved category's parent chain should match the URL path segments.

**Validates: Requirements 1.1, 1.2, 1.3**

### Property 2: Invalid Category Returns 404

*For any* URL with a non-existent slug or invalid parent-child relationship, the system should return a 404 response.

**Validates: Requirements 1.4**

### Property 3: Active Category Filter

*For any* category resolution request, only categories with `is_active = true` should be considered; inactive categories should result in 404.

**Validates: Requirements 1.5**

### Property 4: Product Aggregation by Category Level

*For any* category at any level in the hierarchy, viewing that category should return all products from that category AND all its descendant categories. The product count should equal the sum of products in the category plus products in all descendants.

**Validates: Requirements 2.1, 2.2, 2.3**

### Property 5: Active Product Filter

*For any* category page, only products with `is_active = true` should be displayed; inactive products should be excluded from results.

**Validates: Requirements 2.5**

### Property 6: Category-Tag Intersection Filter

*For any* combination of category and tag filter, the returned products should be the intersection: products that belong to the category scope AND have the specified tag.

**Validates: Requirements 3.1**

### Property 7: Tag Filter Pagination Preservation

*For any* paginated results with a tag filter applied, all pagination links should preserve the tag query parameter.

**Validates: Requirements 3.4**

### Property 8: Multiple Tags AND Logic

*For any* set of multiple tag filters, the returned products should have ALL specified tags (AND logic), not just any one of them.

**Validates: Requirements 3.5**

### Property 9: Navbar Link Generation

*For any* category displayed in the navbar, the generated link should use the correct URL pattern: `/category/{parentSlug}` for parents, `/category/{parentSlug}/{childSlug}` for children, with the correct parent slug included.

**Validates: Requirements 4.1, 4.2**

### Property 10: Category Name Localization

*For any* category displayed in the navbar or breadcrumbs, the name should match the current locale (name_en, name_ar, or name_he).

**Validates: Requirements 4.4, 5.5**

### Property 11: Breadcrumb Structure

*For any* category page, the breadcrumb should contain exactly N+1 items where N is the depth of the category (0 for parent, 1 for child, 2 for sub-child), starting with Home and ending with the current category.

**Validates: Requirements 5.1, 5.2, 5.3**

### Property 12: Breadcrumb Link Correctness

*For any* breadcrumb item (except the current page), clicking the link should navigate to the correct category page using the proper URL pattern.

**Validates: Requirements 5.4**

### Property 13: Category Slug Global Uniqueness

*For any* two categories in the system, their slugs should be different (globally unique).

**Validates: Requirements 6.5**

### Property 14: Child Category Parent Validation

*For any* child category, its `parent_id` should point to an existing, active parent category (where `parent_id` is null).

**Validates: Requirements 6.1**

### Property 15: Sub-child Category Parent Validation

*For any* sub-child category, its `parent_id` should point to an existing, active child category (not a parent category).

**Validates: Requirements 6.2**

## Error Handling

### Category Not Found (404)

When a category cannot be resolved:
- Invalid parent slug → 404 with "Category not found" message
- Invalid child slug → 404 with "Category not found" message
- Invalid parent-child relationship → 404 with "Category not found" message
- Inactive category → 404 with "Category not found" message

### Empty Category Results

When a category has no products:
- Display "No Products Found" message
- Show navigation options to browse other categories
- Do NOT return 404 (empty category is valid)

### Invalid Tag Filter

When a tag filter references a non-existent tag:
- Ignore the invalid tag filter
- Return products without tag filtering
- Log warning for debugging

## Testing Strategy

### Dual Testing Approach

This feature requires both unit tests and property-based tests:

1. **Unit Tests**: Verify specific examples, edge cases, and integration points
2. **Property-Based Tests**: Verify universal properties that should hold across all inputs

### Property-Based Testing Framework

**Framework**: PHPUnit with custom generators (Laravel's built-in testing + Faker)

**Configuration**: Each property test should run a minimum of 100 iterations.

**Test Annotation Format**: Each property-based test must be tagged with:
```php
/**
 * **Feature: category-nav-fix, Property {number}: {property_text}**
 */
```

### Test Categories

#### Unit Tests
1. Route resolution for each hierarchy level
2. Breadcrumb generation for each hierarchy level
3. Link generation in navbar component
4. Tag filter URL preservation
5. Empty category handling

#### Property-Based Tests
1. Category hierarchy resolution (Property 1)
2. Product aggregation by category level (Property 4)
3. Category-tag intersection filtering (Property 6)
4. Navbar link generation correctness (Property 9)
5. Breadcrumb structure validation (Property 11)
6. Category slug uniqueness (Property 13)

### Test Data Generation

```php
// Generator for category hierarchies
function generateCategoryHierarchy(): array {
    $parent = Category::factory()->create(['parent_id' => null]);
    $child = Category::factory()->create(['parent_id' => $parent->id]);
    $subChild = Category::factory()->create(['parent_id' => $child->id]);
    return [$parent, $child, $subChild];
}

// Generator for products with tags
function generateProductWithTags(Category $category, array $tags): Product {
    $product = Product::factory()->create(['category_id' => $category->id]);
    $product->tags()->attach($tags);
    return $product;
}
```

## Implementation Notes

### Key Changes Required

1. **routes/web.php**: Add optional `{subChildSlug?}` parameter
2. **CategoryController::loadCategory()**: Update to handle 3-level hierarchy
3. **CategoryController::show()**: Add `getCategoryWithDescendantIds()` method
4. **ProductFilterService::applyCategoryFilter()**: Accept array of category IDs
5. **Category model**: Add `descendants()` and `getUrlAttribute()` methods
6. **category-nav.blade.php**: Verify link generation (should already be correct)

### Backward Compatibility

- Existing URLs (`/category/{parent}` and `/category/{parent}/{child}`) continue to work
- No database migrations required
- No breaking changes to API endpoints
