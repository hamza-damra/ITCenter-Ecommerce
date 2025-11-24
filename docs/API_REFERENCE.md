# API Reference - Advanced Catalog Filtering System

## Table of Contents

1. [ProductFilterService](#productfilterservice)
2. [Model Methods](#model-methods)
3. [Blade Components](#blade-components)
4. [JavaScript API](#javascript-api)
5. [Helper Functions](#helper-functions)

---

## ProductFilterService

**Namespace**: `App\Services\ProductFilterService`

### Public Methods

#### `applyFilters()`

Apply all filters from request to product query.

```php
public function applyFilters(
    Builder $query, 
    Request $request, 
    ?Category $category = null
): Builder
```

**Parameters:**
- `$query` (Illuminate\Database\Eloquent\Builder) - Base query builder
- `$request` (Illuminate\Http\Request) - HTTP request with filter parameters
- `$category` (App\Models\Category|null) - Optional category context

**Returns:** `Illuminate\Database\Eloquent\Builder`

**Example:**
```php
$query = Product::query()->active();
$filterService = new ProductFilterService();
$filteredQuery = $filterService->applyFilters($query, $request, $category);
$products = $filteredQuery->paginate(12);
```

**Supported Request Parameters:**
- `strong_offers` (boolean): Filter strong offer products
- `brand[]` (array): Filter by brand IDs/slugs
- `stock` (string): Filter by stock status ('in' or 'out')
- `min_price` (numeric): Minimum price
- `max_price` (numeric): Maximum price
- `attr[slug][]` (array): Attribute filters

---

#### `getAvailableFilters()`

Get all available filters with product counts.

```php
public function getAvailableFilters(?Category $category = null): array
```

**Parameters:**
- `$category` (App\Models\Category|null) - Optional category for context-specific filters

**Returns:** `array`

**Return Structure:**
```php
[
    'brands' => [
        [
            'id' => 1,
            'name' => 'ASUS',
            'slug' => 'asus',
            'count' => 45
        ],
        // ...
    ],
    'stock' => [
        [
            'value' => 'in',
            'label' => 'In Stock',
            'count' => 120
        ],
        [
            'value' => 'out',
            'label' => 'Out of Stock',
            'count' => 8
        ]
    ],
    'attributes' => [
        [
            'id' => 1,
            'slug' => 'refresh_rate',
            'name' => 'Refresh Rate',
            'type' => 'select',
            'unit' => 'Hz',
            'values' => [
                [
                    'id' => 1,
                    'slug' => '60hz',
                    'value' => '60Hz',
                    'count' => 25
                ],
                // ...
            ]
        ],
        // ...
    ],
    'price_range' => [
        'min' => 99.99,
        'max' => 2499.99
    ]
]
```

**Example:**
```php
$filterService = new ProductFilterService();
$filters = $filterService->getAvailableFilters($category);

return view('products', [
    'products' => $products,
    'availableFilters' => $filters
]);
```

---

### Protected Methods

#### `applyCategoryFilter()`

```php
protected function applyCategoryFilter(Builder $query, Category $category): Builder
```

Filters products by category, including sub-category products if the category is a parent.

---

#### `applyStrongOffersFilter()`

```php
protected function applyStrongOffersFilter(Builder $query): Builder
```

Filters products where `is_strong_offer = true`.

---

#### `applyStockFilter()`

```php
protected function applyStockFilter(Builder $query, string $stock): Builder
```

Filters products by stock status.

**Parameters:**
- `$stock` (string): 'in' or 'out'

---

#### `applyBrandFilter()`

```php
protected function applyBrandFilter(Builder $query, array $brands): Builder
```

Filters products by brand IDs or slugs.

**Parameters:**
- `$brands` (array): Array of brand IDs or slugs

---

#### `applyPriceFilter()`

```php
protected function applyPriceFilter(
    Builder $query, 
    ?float $minPrice, 
    ?float $maxPrice
): Builder
```

Filters products within price range.

**Parameters:**
- `$minPrice` (float|null): Minimum price (inclusive)
- `$maxPrice` (float|null): Maximum price (inclusive)

---

#### `applyAttributeFilters()`

```php
protected function applyAttributeFilters(Builder $query, array $attributes): Builder
```

Filters products by attribute values using AND logic.

**Parameters:**
- `$attributes` (array): Nested array structure
  ```php
  [
      'refresh_rate' => ['144hz', '240hz'],
      'panel_type' => ['ips']
  ]
  ```

**Logic**: Products must have ALL selected attribute values (AND logic within attributes, OR logic within values of same attribute).

---

#### `getBrandFilters()`

```php
protected function getBrandFilters(?Category $category): array
```

Get available brands with product counts.

---

#### `getStockFilters()`

```php
protected function getStockFilters(?Category $category): array
```

Get stock options with product counts.

---

#### `getAttributeFilters()`

```php
protected function getAttributeFilters(Category $category): array
```

Get category-specific attributes with values and counts.

---

#### `getPriceRange()`

```php
protected function getPriceRange(?Category $category): array
```

Get minimum and maximum product prices.

---

## Model Methods

### Product Model

#### Scopes

##### `scopeStrongOffers()`

```php
public function scopeStrongOffers($query)
```

Filter products marked as strong offers.

**Example:**
```php
$strongOffers = Product::strongOffers()->get();
```

##### `scopeActive()`

```php
public function scopeActive($query)
```

Filter active products only.

**Example:**
```php
$activeProducts = Product::active()->get();
```

#### Relationships

##### `attributeValues()`

```php
public function attributeValues()
```

Get attribute values assigned to this product.

**Returns:** `BelongsToMany` relationship

**Example:**
```php
$product = Product::with('attributeValues')->find(1);
foreach ($product->attributeValues as $value) {
    echo $value->value;
}
```

#### Accessors

##### `getDiscountedPriceAttribute()`

```php
public function getDiscountedPriceAttribute(): float
```

Calculate discounted price if product is a strong offer.

**Example:**
```php
$product = Product::find(1);
echo $product->discounted_price; // Applies discount_percentage
```

---

### Category Model

#### Relationships

##### `attributes()`

```php
public function attributes()
```

Get attributes assigned to this category for filtering.

**Returns:** `BelongsToMany` relationship

**Example:**
```php
$category = Category::with('attributes.values')->find(1);
foreach ($category->attributes as $attribute) {
    echo $attribute->name;
}
```

##### `parent()`

```php
public function parent()
```

Get parent category.

**Returns:** `BelongsTo` relationship

##### `children()`

```php
public function children()
```

Get child categories (sub-categories).

**Returns:** `HasMany` relationship

**Example:**
```php
$category = Category::with('children')->find(1);
foreach ($category->children as $child) {
    echo $child->name;
}
```

#### Methods

##### `allProducts()`

```php
public function allProducts()
```

Get all products including products from sub-categories.

**Returns:** `Builder`

**Example:**
```php
$category = Category::find(1);
$allProducts = $category->allProducts()->get();
```

##### `isParent()`

```php
public function isParent(): bool
```

Check if category is a parent (has children).

**Example:**
```php
if ($category->isParent()) {
    // Show sub-categories
}
```

##### `isChild()`

```php
public function isChild(): bool
```

Check if category is a child (has parent).

**Example:**
```php
if ($category->isChild()) {
    // Show breadcrumb with parent
}
```

---

### Attribute Model

#### Relationships

##### `categories()`

```php
public function categories()
```

Get categories this attribute is assigned to.

**Returns:** `BelongsToMany` relationship

##### `values()`

```php
public function values()
```

Get all values for this attribute.

**Returns:** `HasMany` relationship

**Example:**
```php
$attribute = Attribute::with('values')->find(1);
foreach ($attribute->values as $value) {
    echo $value->value;
}
```

#### Accessors

##### `getNameAttribute()`

```php
public function getNameAttribute(): string
```

Get localized attribute name based on current locale.

**Example:**
```php
app()->setLocale('ar');
echo $attribute->name; // Returns name_ar
```

#### Scopes

##### `scopeFilterable()`

```php
public function scopeFilterable($query)
```

Filter only filterable attributes.

**Example:**
```php
$filterableAttributes = Attribute::filterable()->get();
```

---

### AttributeValue Model

#### Relationships

##### `attribute()`

```php
public function attribute()
```

Get parent attribute.

**Returns:** `BelongsTo` relationship

##### `products()`

```php
public function products()
```

Get products with this attribute value.

**Returns:** `BelongsToMany` relationship

**Example:**
```php
$value = AttributeValue::find(1);
$products = $value->products()->get();
```

#### Accessors

##### `getValueAttribute()`

```php
public function getValueAttribute(): string
```

Get localized attribute value based on current locale.

**Example:**
```php
app()->setLocale('he');
echo $attributeValue->value; // Returns value_he
```

---

## Blade Components

### Filter Sidebar Component

**Component**: `x-filter-sidebar`

**File**: `resources/views/components/filter-sidebar.blade.php`

#### Props

```php
@props([
    'filters',          // array - Available filters from ProductFilterService
    'currentFilters',   // array - Current request parameters
    'category' => null  // Category|null - Optional category context
])
```

#### Usage

```blade
<x-filter-sidebar 
    :filters="$availableFilters"
    :current-filters="request()->all()"
    :category="$category ?? null"
/>
```

#### Slots

```blade
<x-filter-sidebar :filters="$filters" :current-filters="request()->all()">
    <x-slot name="header">
        <h2>Custom Header</h2>
    </x-slot>
</x-filter-sidebar>
```

---

### Category Navigation Component

**Component**: `x-category-nav`

**File**: `resources/views/components/category-nav.blade.php`

#### Props

```php
@props([
    'categories' // Collection - Top-level categories with children
])
```

#### Usage

```blade
<x-category-nav :categories="$categories" />
```

---

### Mobile Filter Toggle Component

**Component**: `x-mobile-filter-toggle`

**File**: `resources/views/components/mobile-filter-toggle.blade.php`

#### Props

```php
@props([
    'activeCount' => 0 // int - Number of active filters
])
```

#### Usage

```blade
<x-mobile-filter-toggle :active-count="$activeFilterCount" />
```

---

### Product Card Component

**Component**: `x-product-card`

**File**: `resources/views/components/product-card.blade.php`

#### Props

```php
@props([
    'product' // Product - Product model instance
])
```

#### Usage

```blade
<x-product-card :product="$product" />
```

---

## JavaScript API

### Filter Sidebar JavaScript

**File**: `public/js/filter-sidebar.js`

#### Functions

##### `initializeFilters()`

Initialize filter sidebar functionality.

```javascript
initializeFilters();
```

Called automatically on page load.

##### `updateFilters()`

Update URL with selected filters.

```javascript
updateFilters(filterType, filterValue, isChecked);
```

**Parameters:**
- `filterType` (string): Type of filter ('brand', 'stock', 'attr', etc.)
- `filterValue` (string): Value to add/remove
- `isChecked` (boolean): Whether checkbox is checked

##### `clearFilters()`

Clear all active filters.

```javascript
clearFilters();
```

##### `getActiveFilters()`

Get currently active filters from URL.

```javascript
const activeFilters = getActiveFilters();
// Returns: { brand: ['asus'], attr: { refresh_rate: ['144hz'] } }
```

##### `buildFilterUrl()`

Build URL with filter parameters.

```javascript
const url = buildFilterUrl(filters);
```

**Parameters:**
- `filters` (object): Filter object

**Returns:** `string` - Complete URL with parameters

---

### Mobile Filter Drawer

**File**: `public/js/filter-sidebar.js` (included)

#### Functions

##### `openFilterDrawer()`

Open mobile filter drawer.

```javascript
openFilterDrawer();
```

##### `closeFilterDrawer()`

Close mobile filter drawer.

```javascript
closeFilterDrawer();
```

##### `toggleFilterDrawer()`

Toggle mobile filter drawer.

```javascript
toggleFilterDrawer();
```

---

## Helper Functions

### Route Helpers

#### `categoryUrl()`

Generate URL for category.

```php
function categoryUrl(Category $category): string
```

**Example:**
```php
$url = categoryUrl($category);
// Returns: /category/parent-slug/child-slug
```

#### `filterUrl()`

Generate URL with filters.

```php
function filterUrl(array $filters, ?string $baseUrl = null): string
```

**Example:**
```php
$url = filterUrl([
    'brand' => ['asus'],
    'attr' => ['refresh_rate' => ['144hz']]
]);
```

---

### Translation Helpers

#### `getLocalizedField()`

Get localized field value.

```php
function getLocalizedField($model, string $field): string
```

**Example:**
```php
$name = getLocalizedField($category, 'name');
// Returns name_en, name_ar, or name_he based on locale
```

---

### Filter Helpers

#### `isFilterActive()`

Check if a filter is currently active.

```php
function isFilterActive(string $filterType, $filterValue): bool
```

**Example:**
```php
@if(isFilterActive('brand', 'asus'))
    <span class="badge">Active</span>
@endif
```

#### `getActiveFilterCount()`

Get count of active filters.

```php
function getActiveFilterCount(): int
```

**Example:**
```php
$count = getActiveFilterCount();
// Returns: 3 (if 3 filters are active)
```

---

## Error Handling

### Exceptions

#### `FilterException`

Thrown when filter operations fail.

```php
try {
    $filterService->applyFilters($query, $request);
} catch (FilterException $e) {
    Log::error('Filter error: ' . $e->getMessage());
}
```

#### `CategoryNotFoundException`

Thrown when category is not found.

```php
try {
    $category = Category::findOrFail($id);
} catch (CategoryNotFoundException $e) {
    abort(404, 'Category not found');
}
```

---

## Events

### FilterApplied

Fired when filters are applied.

```php
Event::listen(FilterApplied::class, function ($event) {
    Log::info('Filters applied', $event->filters);
});
```

### ProductFiltered

Fired when products are filtered.

```php
Event::listen(ProductFiltered::class, function ($event) {
    // Track analytics
    Analytics::track('product_filtered', [
        'filters' => $event->filters,
        'result_count' => $event->resultCount
    ]);
});
```

---

## Testing

### Testing Filters

```php
use Tests\TestCase;
use App\Services\ProductFilterService;

class FilterTest extends TestCase
{
    public function test_strong_offers_filter()
    {
        $request = Request::create('/products', 'GET', ['strong_offers' => 1]);
        $query = Product::query();
        
        $filterService = new ProductFilterService();
        $query = $filterService->applyFilters($query, $request);
        
        $products = $query->get();
        
        $this->assertTrue($products->every(fn($p) => $p->is_strong_offer));
    }
}
```

---

## Performance Considerations

### Caching Filter Counts

```php
$filters = Cache::remember('filters_category_' . $category->id, 3600, function () use ($category) {
    return $filterService->getAvailableFilters($category);
});
```

### Eager Loading

```php
$products = Product::with([
    'category',
    'brand',
    'images',
    'attributeValues.attribute'
])->get();
```

### Query Optimization

```php
// Use select to limit columns
$products = Product::select(['id', 'name', 'price', 'category_id'])
    ->with('category:id,name')
    ->get();
```

---

## Version History

- **v1.0.0** - Initial release with core filtering functionality
- **v1.1.0** - Added strong offers system
- **v1.2.0** - Added multi-language support
- **v1.3.0** - Performance optimizations and caching

---

## Support

For API questions or issues:

- Review full documentation: `docs/CATALOG_FILTERING_SYSTEM.md`
- Check examples: `docs/QUICK_START_GUIDE.md`
- Migration help: `docs/MIGRATION_GUIDE.md`
