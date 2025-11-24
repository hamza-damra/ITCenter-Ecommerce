# Design Document

## Overview

This design implements a comprehensive catalog and filtering system for a Laravel e-commerce application. The system consists of three major components:

1. **Strong Offers System**: A promotional filtering mechanism that allows products to be marked as "Strong Offers" and filtered accordingly
2. **Hierarchical Category System**: A multi-level category structure supporting parent categories and sub-categories with SEO-friendly URLs
3. **Dynamic Attribute-Based Filtering**: A flexible attribute system where filters are configured per sub-category and managed entirely through the admin panel

The design leverages Laravel's Eloquent ORM, query scopes, and relationship features to build an efficient, maintainable filtering system. All UI components support multi-language (English, Arabic, Hebrew) and RTL/LTR layouts.

## Architecture

### System Components

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ Home Page    │  │ Products     │  │ Admin Panel  │      │
│  │ (Promo Card) │  │ Listing      │  │ (CRUD)       │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────────┐
│                     Application Layer                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ Product      │  │ Category     │  │ Admin        │      │
│  │ Controller   │  │ Controller   │  │ Controllers  │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│                                                               │
│  ┌──────────────────────────────────────────────────┐       │
│  │         ProductFilterService                      │       │
│  │  - applyFilters()                                 │       │
│  │  - buildFilterQuery()                             │       │
│  │  - getAvailableFilters()                          │       │
│  └──────────────────────────────────────────────────┘       │
└─────────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────────┐
│                       Data Layer                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Product  │  │ Category │  │ Attribute│  │ Brand    │   │
│  │ Model    │  │ Model    │  │ Model    │  │ Model    │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────┐       │
│  │              Database Tables                      │       │
│  │  - products                                       │       │
│  │  - categories (hierarchical)                     │       │
│  │  - attributes                                     │       │
│  │  - attribute_values                               │       │
│  │  - attribute_category (pivot)                    │       │
│  │  - product_attribute_values (pivot)              │       │
│  └──────────────────────────────────────────────────┘       │
└─────────────────────────────────────────────────────────────┘
```

### Request Flow

1. **User clicks "Shop Now" on Strong Offers card**
   - Route: `/products?strong_offers=1`
   - Controller loads products with `strongOffers()` scope
   - View renders with Strong Offers checkbox checked

2. **User navigates to sub-category**
   - Route: `/category/{parentSlug}/{childSlug}`
   - Controller loads category, determines applicable attributes
   - View renders filter sidebar with category-specific filters

3. **User applies filters**
   - URL updates: `?brand[]=asus&attr[refresh_rate][]=144hz&stock=in&strong_offers=1`
   - Controller applies all filters via ProductFilterService
   - View maintains filter state in checkboxes

4. **Admin configures system**
   - Admin creates/edits categories, attributes, attribute values
   - Admin assigns attributes to sub-categories
   - Admin assigns attribute values to products
   - Changes immediately reflect on customer-facing pages

## Components and Interfaces

### Models

#### Product Model Extensions
```php
class Product extends Model
{
    // New fillable fields
    protected $fillable = [
        // ... existing fields
        'is_strong_offer',
        'discount_percentage',
    ];

    // New scope for strong offers
    public function scopeStrongOffers($query)
    {
        return $query->where('is_strong_offer', true);
    }

    // Relationship to attribute values
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
            ->withTimestamps();
    }
}
```

#### Category Model Extensions
```php
class Category extends Model
{
    // New fillable fields
    protected $fillable = [
        // ... existing fields
        'icon',
        'position',
    ];

    // Relationship to attributes (for filtering)
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_category')
            ->withTimestamps()
            ->orderBy('order');
    }

    // Get all products including sub-category products
    public function allProducts()
    {
        return Product::whereIn('category_id', 
            $this->children()->pluck('id')->push($this->id)
        );
    }
}
```

#### Attribute Model Extensions
```php
class Attribute extends Model
{
    // New fillable fields
    protected $fillable = [
        'name_en',
        'name_ar',
        'name_he',
        'slug',
        'type',
        'unit',
        'is_filterable',
        'order',
        'is_active',
    ];

    // Relationship to categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'attribute_category')
            ->withTimestamps();
    }

    // Get localized name
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_en;
    }
}
```

#### AttributeValue Model Extensions
```php
class AttributeValue extends Model
{
    // New fillable fields
    protected $fillable = [
        'attribute_id',
        'value_en',
        'value_ar',
        'value_he',
        'slug',
        'color_code',
        'order',
        'is_active',
    ];

    // Get localized value
    public function getValueAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"value_$locale"} ?? $this->value_en;
    }
}
```

### Services

#### ProductFilterService
```php
class ProductFilterService
{
    /**
     * Apply all filters to product query
     */
    public function applyFilters(Builder $query, Request $request, ?Category $category = null): Builder
    {
        // Apply category filter
        if ($category) {
            $query = $this->applyCategoryFilter($query, $category);
        }

        // Apply strong offers filter
        if ($request->has('strong_offers') && $request->strong_offers) {
            $query->strongOffers();
        }

        // Apply stock filter
        if ($request->has('stock')) {
            $query = $this->applyStockFilter($query, $request->stock);
        }

        // Apply brand filter
        if ($request->has('brand')) {
            $query = $this->applyBrandFilter($query, $request->brand);
        }

        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query = $this->applyPriceFilter($query, $request->min_price, $request->max_price);
        }

        // Apply attribute filters
        if ($request->has('attr')) {
            $query = $this->applyAttributeFilters($query, $request->attr);
        }

        return $query;
    }

    /**
     * Get available filters for a category with counts
     */
    public function getAvailableFilters(?Category $category = null): array
    {
        $filters = [];

        // Get brands with product counts
        $filters['brands'] = $this->getBrandFilters($category);

        // Get stock options with counts
        $filters['stock'] = $this->getStockFilters($category);

        // Get attribute filters with counts (category-specific)
        if ($category) {
            $filters['attributes'] = $this->getAttributeFilters($category);
        }

        // Get price range
        $filters['price_range'] = $this->getPriceRange($category);

        return $filters;
    }

    /**
     * Apply attribute filters to query
     */
    protected function applyAttributeFilters(Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attributeSlug => $valuesSlugs) {
            if (empty($valuesSlugs)) {
                continue;
            }

            $query->whereHas('attributeValues', function ($q) use ($attributeSlug, $valuesSlugs) {
                $q->whereHas('attribute', function ($attrQuery) use ($attributeSlug) {
                    $attrQuery->where('slug', $attributeSlug);
                })->whereIn('slug', (array)$valuesSlugs);
            });
        }

        return $query;
    }

    /**
     * Get attribute filters for category with product counts
     */
    protected function getAttributeFilters(Category $category): array
    {
        $attributeFilters = [];

        // Get attributes assigned to this category
        $attributes = $category->attributes()
            ->where('is_filterable', true)
            ->with(['values' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->get();

        foreach ($attributes as $attribute) {
            $values = [];
            
            foreach ($attribute->values as $value) {
                // Count products with this attribute value in this category
                $count = Product::active()
                    ->where('category_id', $category->id)
                    ->whereHas('attributeValues', function ($q) use ($value) {
                        $q->where('attribute_value_id', $value->id);
                    })
                    ->count();

                if ($count > 0) {
                    $values[] = [
                        'id' => $value->id,
                        'slug' => $value->slug,
                        'value' => $value->value,
                        'count' => $count,
                    ];
                }
            }

            if (!empty($values)) {
                $attributeFilters[] = [
                    'id' => $attribute->id,
                    'slug' => $attribute->slug,
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'unit' => $attribute->unit,
                    'values' => $values,
                ];
            }
        }

        return $attributeFilters;
    }
}
```

### Controllers

#### ProductController Extensions
```php
public function index(Request $request)
{
    $query = Product::with(['category', 'brand', 'images'])->active();

    // Apply filters using service
    $filterService = new ProductFilterService();
    $query = $filterService->applyFilters($query, $request);

    // Sorting
    $sortBy = $request->get('sort', 'created_at');
    $sortOrder = $request->get('order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    // Paginate
    $products = $query->paginate($request->get('per_page', 12));
    $products->appends($request->except('page'));

    // Get available filters with counts
    $availableFilters = $filterService->getAvailableFilters();

    return view('products', compact('products', 'availableFilters'));
}
```

#### CategoryController (New)
```php
class CategoryController extends Controller
{
    public function show(Request $request, string $parentSlug, ?string $childSlug = null)
    {
        // Load category
        $category = $this->loadCategory($parentSlug, $childSlug);

        // Build product query
        $query = Product::with(['category', 'brand', 'images'])
            ->active()
            ->where('category_id', $category->id);

        // Apply filters
        $filterService = new ProductFilterService();
        $query = $filterService->applyFilters($query, $request, $category);

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $products = $query->paginate($request->get('per_page', 12));
        $products->appends($request->except('page'));

        // Get available filters for this category
        $availableFilters = $filterService->getAvailableFilters($category);

        // Breadcrumb data
        $breadcrumbs = $this->buildBreadcrumbs($category);

        return view('category-products', compact(
            'category',
            'products',
            'availableFilters',
            'breadcrumbs'
        ));
    }

    protected function loadCategory(string $parentSlug, ?string $childSlug): Category
    {
        if ($childSlug) {
            // Load sub-category
            $parent = Category::where('slug', $parentSlug)->firstOrFail();
            return Category::where('slug', $childSlug)
                ->where('parent_id', $parent->id)
                ->firstOrFail();
        }

        // Load parent category
        return Category::where('slug', $parentSlug)
            ->whereNull('parent_id')
            ->firstOrFail();
    }

    protected function buildBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [
            ['name' => __('messages.home'), 'url' => route('home')],
        ];

        if ($category->parent) {
            $breadcrumbs[] = [
                'name' => $category->parent->name,
                'url' => route('category.show', $category->parent->slug),
            ];
        }

        $breadcrumbs[] = [
            'name' => $category->name,
            'url' => null, // Current page
        ];

        return $breadcrumbs;
    }
}
```

#### Admin Controllers (New)

**Admin\AttributeController**
```php
class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->orderBy('order')->paginate(20);
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'slug' => 'required|string|unique:attributes',
            'type' => 'required|in:select,multi_select,range,color',
            'unit' => 'nullable|string|max:50',
            'is_filterable' => 'boolean',
            'order' => 'integer',
        ]);

        Attribute::create($validated);
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully');
    }

    // ... edit, update, destroy methods
}
```

**Admin\CategoryAttributeController**
```php
class CategoryAttributeController extends Controller
{
    public function edit(Category $category)
    {
        $assignedAttributes = $category->attributes()->pluck('attributes.id')->toArray();
        $allAttributes = Attribute::where('is_filterable', true)
            ->orderBy('order')
            ->get();

        return view('admin.categories.attributes', compact(
            'category',
            'assignedAttributes',
            'allAttributes'
        ));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'attributes' => 'array',
            'attributes.*' => 'exists:attributes,id',
        ]);

        $category->attributes()->sync($validated['attributes'] ?? []);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category attributes updated successfully');
    }
}
```

## Data Models

### Database Schema Changes

#### Products Table (Migration)
```php
Schema::table('products', function (Blueprint $table) {
    $table->boolean('is_strong_offer')->default(false)->after('is_special_offer');
    $table->decimal('discount_percentage', 5, 2)->nullable()->after('is_strong_offer');
    
    $table->index('is_strong_offer');
});
```

#### Categories Table (Migration)
```php
Schema::table('categories', function (Blueprint $table) {
    $table->string('icon')->nullable()->after('image');
    $table->integer('position')->default(0)->after('order');
    
    $table->index('position');
    $table->index(['parent_id', 'is_active']);
});
```

#### Attributes Table (Migration)
```php
Schema::table('attributes', function (Blueprint $table) {
    // Add multi-language support
    $table->string('name_en')->after('name');
    $table->string('name_ar')->after('name_en');
    $table->string('name_he')->after('name_ar');
    
    // Add new fields
    $table->string('unit', 50)->nullable()->after('type');
    $table->boolean('is_filterable')->default(true)->after('unit');
    
    // Drop old name column
    $table->dropColumn('name');
    
    $table->index('is_filterable');
});
```

#### Attribute Values Table (Migration)
```php
Schema::table('attribute_values', function (Blueprint $table) {
    // Add multi-language support
    $table->string('value_en')->after('value');
    $table->string('value_ar')->after('value_en');
    $table->string('value_he')->after('value_ar');
    
    // Add slug for URL filtering
    $table->string('slug')->after('value_he');
    
    // Drop old value column
    $table->dropColumn('value');
    
    $table->index('slug');
});
```

#### Attribute Category Pivot Table (New)
```php
Schema::create('attribute_category', function (Blueprint $table) {
    $table->id();
    $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['attribute_id', 'category_id']);
    $table->index('category_id');
});
```

#### Product Attribute Values Pivot Table (New)
```php
Schema::create('product_attribute_values', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('attribute_value_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['product_id', 'attribute_value_id']);
    $table->index('attribute_value_id');
});
```

### Entity Relationships

```
Product
  ├── belongsTo: Category
  ├── belongsTo: Brand
  ├── belongsToMany: AttributeValue (through product_attribute_values)
  └── hasMany: ProductImage

Category
  ├── belongsTo: Category (parent)
  ├── hasMany: Category (children)
  ├── hasMany: Product
  └── belongsToMany: Attribute (through attribute_category)

Attribute
  ├── hasMany: AttributeValue
  └── belongsToMany: Category (through attribute_category)

AttributeValue
  ├── belongsTo: Attribute
  └── belongsToMany: Product (through product_attribute_values)
```


## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Strong offers filter exclusivity
*For any* set of products with mixed is_strong_offer values, when the strong offers filter is applied, all returned products should have is_strong_offer=true and no products with is_strong_offer=false should be included.
**Validates: Requirements 1.2**

### Property 2: Filter combination uses AND logic
*For any* combination of filters (strong offers, brand, stock, price, attributes), all returned products should satisfy every filter condition simultaneously.
**Validates: Requirements 1.5, 3.5**

### Property 3: Active categories visibility
*For any* set of categories, only those with is_active=true should appear in customer-facing navigation and category listings.
**Validates: Requirements 2.1, 5.4**

### Property 4: Sub-category display on hover
*For any* top-level category that has child categories with is_active=true, hovering or clicking should display all active sub-categories.
**Validates: Requirements 2.2**

### Property 5: Sub-category URL format
*For any* sub-category with a parent, the generated URL should follow the format /category/{parentSlug}/{childSlug}.
**Validates: Requirements 2.3**

### Property 6: Breadcrumb path generation
*For any* category or sub-category, the breadcrumb should show the complete navigation path from Home through parent (if exists) to current category.
**Validates: Requirements 2.4**

### Property 7: Category product filtering
*For any* sub-category, the products page should display only products where category_id matches that sub-category's id.
**Validates: Requirements 2.5**

### Property 8: Category-specific attribute filters
*For any* sub-category, the filter sidebar should display only attributes that are mapped to that sub-category in the attribute_category table.
**Validates: Requirements 3.1, 7.4**

### Property 9: Attribute filter URL format
*For any* attribute filter selection, the URL should contain parameters in the format ?attr[attribute_slug][]=value_slug.
**Validates: Requirements 3.2**

### Property 10: Attribute filter AND logic
*For any* set of selected attribute values, all returned products should have all selected attribute values assigned (not just any one of them).
**Validates: Requirements 3.3**

### Property 11: Filter count accuracy
*For any* filter option (brand, attribute value, stock status), the displayed count should exactly match the number of products that have that characteristic and satisfy all other currently applied filters.
**Validates: Requirements 3.4**

### Property 12: URL-to-UI state synchronization
*For any* URL containing filter query parameters, the filter sidebar checkboxes should be checked to match those parameters.
**Validates: Requirements 4.1**

### Property 13: RTL layout for RTL locales
*For any* page in the catalog system, when the locale is Arabic or Hebrew, all UI components should render in RTL layout.
**Validates: Requirements 4.5, 12.5**

### Property 14: Category field persistence
*For any* category creation, all fields (name_en, name_ar, name_he, slug, parent_id, icon, position, is_active) should be stored in the database.
**Validates: Requirements 5.1**

### Property 15: Parent-child category relationship
*For any* category with parent_id set, it should be queryable as a child of that parent category.
**Validates: Requirements 5.2**

### Property 16: Category ordering
*For any* set of categories, when displayed, they should appear in ascending order by the position field.
**Validates: Requirements 5.3**

### Property 17: Category deletion constraint
*For any* category that has products assigned (directly or through sub-categories), deletion attempts should fail with a validation error.
**Validates: Requirements 5.5**

### Property 18: Attribute field persistence
*For any* attribute creation, all fields (name_en, name_ar, name_he, slug, type, unit, is_filterable) should be stored in the database.
**Validates: Requirements 6.1**

### Property 19: Attribute value association
*For any* attribute value creation, it should be associated with its parent attribute via attribute_id and queryable through that relationship.
**Validates: Requirements 6.2**

### Property 20: Non-filterable attribute exclusion
*For any* attribute with is_filterable=false, it should not appear in any filter sidebar on customer-facing pages.
**Validates: Requirements 6.3**

### Property 21: Attribute cascade deletion
*For any* attribute deletion, all associated attribute_values and product_attribute_values records should be automatically deleted.
**Validates: Requirements 6.4**

### Property 22: Attribute slug update integrity
*For any* attribute slug update, all existing product_attribute_values associations should remain intact and functional.
**Validates: Requirements 6.5**

### Property 23: Attribute-category assignment
*For any* attribute assigned to a sub-category, a record should exist in the attribute_category pivot table with both IDs.
**Validates: Requirements 7.2**

### Property 24: Attribute-category removal
*For any* attribute removed from a sub-category, the corresponding attribute_category record should be deleted.
**Validates: Requirements 7.3**

### Property 25: Multi-category attribute visibility
*For any* attribute assigned to multiple sub-categories, it should appear in the filter sidebar for all assigned sub-categories.
**Validates: Requirements 7.5**

### Property 26: Product attribute relevance
*For any* product being edited, only attributes assigned to that product's category should be displayed for selection.
**Validates: Requirements 8.1**

### Property 27: Product attribute value assignment
*For any* attribute value selected for a product, a record should exist in the product_attribute_values pivot table.
**Validates: Requirements 8.2**

### Property 28: Product attribute validation
*For any* product save with attribute values, the system should validate that all selected attribute values belong to attributes assigned to the product's category.
**Validates: Requirements 8.3**

### Property 29: Dynamic attribute loading on category change
*For any* product category change, the displayed attribute list should update to show only attributes assigned to the new category.
**Validates: Requirements 8.4**

### Property 30: Attribute filter matching
*For any* product with specific attribute values assigned, when those attribute values are selected in filters, that product should appear in the results.
**Validates: Requirements 8.5**

### Property 31: Strong offer field update
*For any* product where the Strong Offer checkbox is checked, the is_strong_offer field should be set to true in the database.
**Validates: Requirements 9.2**

### Property 32: Discount percentage validation
*For any* discount_percentage input, the system should validate that the value is between 0 and 100 (inclusive).
**Validates: Requirements 9.3**

### Property 33: Strong offer filter inclusion
*For any* product with is_strong_offer=true, when the strong offers filter is applied, that product should appear in the results.
**Validates: Requirements 9.4**

### Property 34: Strong offer filter exclusion
*For any* product with is_strong_offer=false, when the strong offers filter is applied, that product should not appear in the results.
**Validates: Requirements 9.5**

### Property 35: Filter URL format consistency
*For any* combination of applied filters, the URL should follow the consistent format with brand[], attr[slug][], stock, and strong_offers parameters.
**Validates: Requirements 10.1**

### Property 36: Pagination filter preservation
*For any* paginated product listing with active filters, all pagination links should include all current filter parameters in the URL.
**Validates: Requirements 10.5**

### Property 37: Category name localization
*For any* category and any locale (en, ar, he), the displayed name should be the name_{locale} field value.
**Validates: Requirements 12.1**

### Property 38: Attribute localization
*For any* attribute or attribute value and any locale, the displayed name/value should be the name_{locale} or value_{locale} field value.
**Validates: Requirements 12.2**

### Property 39: Filter label localization
*For any* filter label and any locale, the displayed text should come from the appropriate language file translation.
**Validates: Requirements 12.3**

### Property 40: Promotional card localization
*For any* locale, the Strong Offers promotional card should display translated title, text, and button label from language files.
**Validates: Requirements 12.4**

## Error Handling

### Validation Errors

1. **Category Deletion with Products**
   - Error: "Cannot delete category with assigned products"
   - HTTP Status: 422 Unprocessable Entity
   - User Action: Remove products from category first or soft-delete instead

2. **Invalid Discount Percentage**
   - Error: "Discount percentage must be between 0 and 100"
   - HTTP Status: 422 Unprocessable Entity
   - User Action: Enter valid percentage value

3. **Invalid Attribute Assignment**
   - Error: "Selected attribute values do not belong to product's category attributes"
   - HTTP Status: 422 Unprocessable Entity
   - User Action: Select only valid attribute values for the category

4. **Duplicate Slug**
   - Error: "Slug already exists"
   - HTTP Status: 422 Unprocessable Entity
   - User Action: Choose a different slug or let system auto-generate

### Database Errors

1. **Foreign Key Constraint Violation**
   - Scenario: Attempting to delete referenced records
   - Handling: Catch exception, return user-friendly message
   - Logging: Log full exception details for debugging

2. **Unique Constraint Violation**
   - Scenario: Duplicate slug or SKU
   - Handling: Return validation error with specific field
   - User Action: Modify the conflicting field

### Query Errors

1. **Category Not Found**
   - HTTP Status: 404 Not Found
   - Response: "Category not found"
   - User Action: Check URL or navigate from category menu

2. **Invalid Filter Parameters**
   - Handling: Ignore invalid parameters, log warning
   - Behavior: Continue with valid filters only
   - User Experience: No error shown, invalid filters simply don't apply

### Performance Safeguards

1. **Too Many Filters**
   - Limit: Maximum 50 filter values per request
   - Handling: Return 400 Bad Request if exceeded
   - Message: "Too many filter values selected"

2. **Query Timeout**
   - Timeout: 30 seconds for complex filter queries
   - Handling: Return 503 Service Unavailable
   - Logging: Log slow queries for optimization

## Testing Strategy

### Unit Testing

Unit tests will verify specific examples and edge cases:

1. **Model Tests**
   - Test scope methods (strongOffers, active, etc.)
   - Test relationship definitions
   - Test accessor/mutator methods
   - Test slug auto-generation

2. **Service Tests**
   - Test ProductFilterService with specific filter combinations
   - Test filter count calculations
   - Test URL parameter parsing
   - Test empty result handling

3. **Controller Tests**
   - Test route responses with various filter parameters
   - Test pagination with filters
   - Test breadcrumb generation
   - Test 404 responses for invalid categories

4. **Validation Tests**
   - Test discount percentage validation (0, 50, 100, -1, 101)
   - Test required field validation
   - Test unique constraint validation

### Property-Based Testing

Property-based tests will verify universal properties across all inputs using **PHPUnit with Eris** (a property-based testing library for PHP):

**Configuration**: Each property test should run a minimum of 100 iterations to ensure thorough coverage of the input space.

**Tagging**: Each property-based test must include a comment explicitly referencing the correctness property from this design document using the format: `**Feature: advanced-catalog-filtering, Property {number}: {property_text}**`

**Property Test Examples**:

1. **Strong Offers Filter Exclusivity** (Property 1)
   - Generate: Random set of products with mixed is_strong_offer values
   - Apply: Strong offers filter
   - Assert: All returned products have is_strong_offer=true

2. **Filter Combination AND Logic** (Property 2)
   - Generate: Random products, random filter combinations
   - Apply: Multiple filters simultaneously
   - Assert: All products satisfy all filter conditions

3. **Category Product Filtering** (Property 7)
   - Generate: Random category, random products in various categories
   - Apply: Category filter
   - Assert: All returned products have matching category_id

4. **Attribute Filter AND Logic** (Property 10)
   - Generate: Random products with various attribute values
   - Apply: Multiple attribute filters
   - Assert: All products have all selected attribute values

5. **Filter Count Accuracy** (Property 11)
   - Generate: Random products with various attributes
   - Calculate: Expected counts for each filter option
   - Assert: Displayed counts match actual product counts

6. **URL-to-UI State Synchronization** (Property 12)
   - Generate: Random filter combinations as URL parameters
   - Load: Page with those parameters
   - Assert: All corresponding checkboxes are checked

7. **Category Ordering** (Property 16)
   - Generate: Random categories with various position values
   - Query: Categories ordered by position
   - Assert: Results are in ascending position order

8. **Attribute Cascade Deletion** (Property 21)
   - Generate: Random attribute with values and product associations
   - Delete: The attribute
   - Assert: All related records are deleted

9. **Pagination Filter Preservation** (Property 36)
   - Generate: Random filter combination
   - Navigate: To page 2
   - Assert: All filter parameters present in pagination URL

10. **Localization Properties** (Properties 37-40)
    - Generate: Random locale (en, ar, he)
    - Load: Various pages
    - Assert: All text uses correct locale fields

### Integration Testing

Integration tests will verify end-to-end workflows:

1. **Strong Offers Flow**
   - Click promotional card → Verify URL → Verify filtered products → Verify checkbox state

2. **Category Navigation Flow**
   - Navigate category menu → Select sub-category → Verify URL → Verify breadcrumb → Verify products

3. **Filter Application Flow**
   - Apply multiple filters → Verify URL → Verify products → Verify counts → Navigate pagination

4. **Admin Configuration Flow**
   - Create category → Assign attributes → Create product → Assign attribute values → Verify customer view

### Test Data Strategy

- Use factories for generating test data
- Create realistic product catalogs with varied attributes
- Test with all three locales (en, ar, he)
- Test with empty states (no products, no filters)
- Test with large datasets (1000+ products) for performance

