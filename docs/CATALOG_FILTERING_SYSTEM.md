# Advanced Catalog Filtering System Documentation

## Table of Contents

1. [Overview](#overview)
2. [Filter URL Parameter Format](#filter-url-parameter-format)
3. [Database Schema](#database-schema)
4. [ProductFilterService API](#productfilterservice-api)
5. [Admin Configuration Guide](#admin-configuration-guide)
6. [Frontend Integration](#frontend-integration)
7. [Multi-language Support](#multi-language-support)

---

## Overview

The Advanced Catalog Filtering System provides a comprehensive solution for organizing and filtering products in an e-commerce platform. It includes:

- **Strong Offers System**: Promotional filtering for special deals
- **Hierarchical Categories**: Parent/child category structure with SEO-friendly URLs
- **Dynamic Attribute Filtering**: Category-specific filters managed through admin panel
- **Multi-language Support**: Full support for English, Arabic, and Hebrew with RTL layouts

---

## Filter URL Parameter Format

### Query Parameter Structure

All filters use clean, RESTful query parameters that maintain state across page navigation and pagination.

### Parameter Types

#### 1. Strong Offers Filter
```
?strong_offers=1
```
- **Type**: Boolean (0 or 1)
- **Purpose**: Filter products marked as strong offers
- **Example**: `/products?strong_offers=1`

#### 2. Brand Filter
```
?brand[]=value1&brand[]=value2
```
- **Type**: Array of brand IDs or slugs
- **Purpose**: Filter by one or more brands
- **Example**: `/products?brand[]=asus&brand[]=msi`

#### 3. Stock Filter
```
?stock=in
?stock=out
```
- **Type**: String enum (`in`, `out`)
- **Purpose**: Filter by stock availability
- **Example**: `/products?stock=in`

#### 4. Price Range Filter
```
?min_price=100&max_price=500
```
- **Type**: Numeric values
- **Purpose**: Filter by price range
- **Example**: `/products?min_price=100&max_price=500`

#### 5. Attribute Filters
```
?attr[attribute_slug][]=value_slug1&attr[attribute_slug][]=value_slug2
```
- **Type**: Nested array structure
- **Purpose**: Filter by product attributes
- **Example**: `/category/monitors/gaming?attr[refresh_rate][]=144hz&attr[panel_type][]=ips`

#### 6. Sorting
```
?sort=price&order=asc
```
- **Type**: String values
- **Purpose**: Sort products
- **Available sort fields**: `price`, `name`, `created_at`, `rating`
- **Order**: `asc` or `desc`

#### 7. Pagination
```
?page=2&per_page=24
```
- **Type**: Numeric values
- **Purpose**: Paginate results
- **Default per_page**: 12

### Complete Example

```
/category/pc-components/graphics-cards?
  strong_offers=1&
  brand[]=nvidia&brand[]=amd&
  attr[memory][]=8gb&attr[memory][]=16gb&
  attr[interface][]=pcie4&
  min_price=300&
  max_price=1000&
  stock=in&
  sort=price&
  order=asc&
  page=1&
  per_page=24
```

### Filter Logic

- **Multiple values within same filter**: OR logic (e.g., brand[]=asus OR brand[]=msi)
- **Different filter types**: AND logic (e.g., brand AND attributes AND price)
- **Multiple attributes**: AND logic (e.g., must have refresh_rate=144hz AND panel_type=ips)

---

## Database Schema

### New Tables

#### 1. `attribute_category` (Pivot Table)
Maps which attributes are available for filtering in each category.

```sql
CREATE TABLE attribute_category (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    attribute_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    UNIQUE KEY unique_attribute_category (attribute_id, category_id),
    INDEX idx_category_id (category_id),
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

#### 2. `product_attribute_values` (Pivot Table)
Maps which attribute values are assigned to each product.

```sql
CREATE TABLE product_attribute_values (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    attribute_value_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    UNIQUE KEY unique_product_attribute_value (product_id, attribute_value_id),
    INDEX idx_attribute_value_id (attribute_value_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE
);
```

### Modified Tables

#### 1. `products` Table
Added fields for strong offers functionality.

```sql
ALTER TABLE products ADD COLUMN (
    is_strong_offer BOOLEAN DEFAULT FALSE,
    discount_percentage DECIMAL(5,2) NULL,
    
    INDEX idx_is_strong_offer (is_strong_offer)
);
```

#### 2. `categories` Table
Added fields for icons and positioning.

```sql
ALTER TABLE categories ADD COLUMN (
    icon VARCHAR(255) NULL,
    position INT DEFAULT 0,
    
    INDEX idx_position (position),
    INDEX idx_parent_active (parent_id, is_active)
);
```

#### 3. `attributes` Table
Added multi-language support and filtering configuration.

```sql
ALTER TABLE attributes (
    -- Removed: name VARCHAR(255)
    -- Added:
    name_en VARCHAR(255) NOT NULL,
    name_ar VARCHAR(255) NOT NULL,
    name_he VARCHAR(255) NOT NULL,
    unit VARCHAR(50) NULL,
    is_filterable BOOLEAN DEFAULT TRUE,
    
    INDEX idx_is_filterable (is_filterable)
);
```

#### 4. `attribute_values` Table
Added multi-language support and slugs for URL filtering.

```sql
ALTER TABLE attribute_values (
    -- Removed: value VARCHAR(255)
    -- Added:
    value_en VARCHAR(255) NOT NULL,
    value_ar VARCHAR(255) NOT NULL,
    value_he VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    
    INDEX idx_slug (slug)
);
```

### Performance Indexes

The following composite indexes were added for optimal query performance:

```sql
-- Products table
CREATE INDEX idx_products_category_active ON products(category_id, is_active);
CREATE INDEX idx_products_strong_offer ON products(is_strong_offer);

-- Categories table
CREATE INDEX idx_categories_parent_active_position ON categories(parent_id, is_active, position);

-- Pivot tables
CREATE INDEX idx_attribute_category_category ON attribute_category(category_id);
CREATE INDEX idx_product_attribute_values_value ON product_attribute_values(attribute_value_id);
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

---

## ProductFilterService API

### Class: `App\Services\ProductFilterService`

The ProductFilterService centralizes all filtering logic and provides methods for applying filters and retrieving filter metadata.

### Public Methods

#### `applyFilters(Builder $query, Request $request, ?Category $category = null): Builder`

Applies all filters from the request to the product query.

**Parameters:**
- `$query` (Builder): The Eloquent query builder instance
- `$request` (Request): The HTTP request containing filter parameters
- `$category` (Category|null): Optional category for category-specific filtering

**Returns:** Builder - The modified query builder with filters applied

**Example:**
```php
$query = Product::query()->active();
$filterService = new ProductFilterService();
$query = $filterService->applyFilters($query, $request, $category);
$products = $query->paginate(12);
```

#### `getAvailableFilters(?Category $category = null): array`

Retrieves all available filters with product counts for the current context.

**Parameters:**
- `$category` (Category|null): Optional category for category-specific filters

**Returns:** Array with structure:
```php
[
    'brands' => [
        ['id' => 1, 'name' => 'ASUS', 'slug' => 'asus', 'count' => 45],
        ['id' => 2, 'name' => 'MSI', 'slug' => 'msi', 'count' => 32],
    ],
    'stock' => [
        ['value' => 'in', 'label' => 'In Stock', 'count' => 120],
        ['value' => 'out', 'label' => 'Out of Stock', 'count' => 8],
    ],
    'attributes' => [
        [
            'id' => 1,
            'slug' => 'refresh_rate',
            'name' => 'Refresh Rate',
            'type' => 'select',
            'unit' => 'Hz',
            'values' => [
                ['id' => 1, 'slug' => '60hz', 'value' => '60Hz', 'count' => 25],
                ['id' => 2, 'slug' => '144hz', 'value' => '144Hz', 'count' => 18],
            ],
        ],
    ],
    'price_range' => [
        'min' => 99.99,
        'max' => 2499.99,
    ],
]
```

**Example:**
```php
$filterService = new ProductFilterService();
$availableFilters = $filterService->getAvailableFilters($category);
return view('products', compact('availableFilters'));
```

### Protected Methods

#### `applyCategoryFilter(Builder $query, Category $category): Builder`

Filters products by category, including sub-category products if applicable.

#### `applyStrongOffersFilter(Builder $query): Builder`

Filters products where `is_strong_offer = true`.

#### `applyStockFilter(Builder $query, string $stock): Builder`

Filters products by stock status ('in' or 'out').

#### `applyBrandFilter(Builder $query, array $brands): Builder`

Filters products by one or more brand IDs or slugs.

#### `applyPriceFilter(Builder $query, ?float $minPrice, ?float $maxPrice): Builder`

Filters products within the specified price range.

#### `applyAttributeFilters(Builder $query, array $attributes): Builder`

Filters products by attribute values using AND logic.

**Attribute Filter Structure:**
```php
$attributes = [
    'refresh_rate' => ['144hz', '240hz'],
    'panel_type' => ['ips'],
];
```

#### `getBrandFilters(?Category $category): array`

Retrieves available brands with product counts.

#### `getStockFilters(?Category $category): array`

Retrieves stock options with product counts.

#### `getAttributeFilters(Category $category): array`

Retrieves category-specific attributes with values and counts.

#### `getPriceRange(?Category $category): array`

Retrieves the minimum and maximum product prices.

### Usage in Controllers

```php
use App\Services\ProductFilterService;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])->active();
        
        $filterService = new ProductFilterService();
        $query = $filterService->applyFilters($query, $request);
        
        $products = $query->paginate(12);
        $products->appends($request->except('page'));
        
        $availableFilters = $filterService->getAvailableFilters();
        
        return view('products', compact('products', 'availableFilters'));
    }
}
```

---

## Admin Configuration Guide

### Setting Up the Catalog System

Follow these steps to configure categories, attributes, and products for filtering.

### Step 1: Create Categories

1. Navigate to **Admin Panel > Categories**
2. Click **"Create New Category"**
3. Fill in the form:
   - **Name (English)**: e.g., "PC Components"
   - **Name (Arabic)**: e.g., "مكونات الكمبيوتر"
   - **Name (Hebrew)**: e.g., "רכיבי מחשב"
   - **Slug**: Auto-generated or custom (e.g., "pc-components")
   - **Parent Category**: Leave empty for top-level category
   - **Icon**: Upload category icon (optional)
   - **Position**: Numeric value for ordering (lower = first)
   - **Active**: Check to make visible to customers
4. Click **"Save"**

### Step 2: Create Sub-Categories

1. Navigate to **Admin Panel > Categories**
2. Click **"Create New Category"**
3. Fill in the form:
   - **Name (English)**: e.g., "Graphics Cards"
   - **Name (Arabic)**: e.g., "بطاقات الرسومات"
   - **Name (Hebrew)**: e.g., "כרטיסי מסך"
   - **Slug**: e.g., "graphics-cards"
   - **Parent Category**: Select "PC Components"
   - **Position**: Set ordering within parent
   - **Active**: Check to enable
4. Click **"Save"**

### Step 3: Create Attributes

1. Navigate to **Admin Panel > Attributes**
2. Click **"Create New Attribute"**
3. Fill in the form:
   - **Name (English)**: e.g., "Memory Size"
   - **Name (Arabic)**: e.g., "حجم الذاكرة"
   - **Name (Hebrew)**: e.g., "גודל זיכרון"
   - **Slug**: e.g., "memory"
   - **Type**: Select from:
     - `select`: Single selection
     - `multi_select`: Multiple selections
     - `range`: Numeric range
     - `color`: Color picker
   - **Unit**: e.g., "GB" (optional)
   - **Filterable**: Check to show in filter sidebar
   - **Order**: Numeric value for ordering
   - **Active**: Check to enable
4. Click **"Save"**

### Step 4: Create Attribute Values

1. Navigate to **Admin Panel > Attributes**
2. Click **"Manage Values"** next to an attribute
3. Click **"Create New Value"**
4. Fill in the form:
   - **Value (English)**: e.g., "8GB"
   - **Value (Arabic)**: e.g., "8 جيجابايت"
   - **Value (Hebrew)**: e.g., "8GB"
   - **Slug**: e.g., "8gb"
   - **Color Code**: For color-type attributes (optional)
   - **Order**: Numeric value for ordering
   - **Active**: Check to enable
5. Click **"Save"**
6. Repeat for all values (e.g., "16GB", "24GB")

### Step 5: Assign Attributes to Categories

1. Navigate to **Admin Panel > Categories**
2. Click **"Manage Attributes"** next to a sub-category
3. Check the attributes that should be filterable for this category
   - Example: For "Graphics Cards", select:
     - Memory Size
     - Interface Type
     - Cooling Type
4. Click **"Save"**

**Important**: Only attributes assigned to a category will appear in the filter sidebar for that category's products.

### Step 6: Configure Products

#### Create/Edit Product

1. Navigate to **Admin Panel > Products**
2. Click **"Create New Product"** or edit existing
3. Fill in basic product information
4. Select the **Category** (sub-category)
5. The attribute section will dynamically load based on selected category

#### Assign Attribute Values

1. In the **Attributes** section, you'll see all attributes assigned to the selected category
2. For each attribute, select the appropriate value(s):
   - **Select type**: Choose one value
   - **Multi-select type**: Choose multiple values
   - **Range type**: Enter numeric value
   - **Color type**: Select color
3. Example for a graphics card:
   - Memory Size: 16GB
   - Interface Type: PCIe 4.0
   - Cooling Type: Triple Fan

#### Mark as Strong Offer

1. Check the **"Strong Offer"** checkbox
2. Optionally enter **Discount Percentage** (0-100)
3. Products marked as strong offers will appear when the strong offers filter is applied

#### Save Product

1. Click **"Save"**
2. The product will now be filterable by its assigned attributes

### Step 7: Test the System

1. Visit the customer-facing site
2. Navigate to a category (e.g., /category/pc-components/graphics-cards)
3. Verify that:
   - Category-specific filters appear in the sidebar
   - Filter counts are accurate
   - Applying filters updates the product list
   - URL parameters reflect selected filters
   - Pagination maintains filter state

### Common Configuration Scenarios

#### Scenario 1: Adding a New Filter to Existing Category

1. Create the attribute (if it doesn't exist)
2. Create attribute values
3. Go to **Categories > Manage Attributes**
4. Assign the new attribute to the category
5. Edit products to assign the new attribute values

#### Scenario 2: Removing a Filter

1. Go to **Categories > Manage Attributes**
2. Uncheck the attribute you want to remove
3. Click **"Save"**
4. The filter will no longer appear for that category

#### Scenario 3: Reordering Filters

1. Edit attributes and update their **Order** field
2. Lower numbers appear first in the filter sidebar

#### Scenario 4: Multi-Category Attributes

If an attribute applies to multiple categories (e.g., "Brand"):
1. Create the attribute once
2. Assign it to multiple categories via **Manage Attributes**
3. The same attribute will appear in all assigned categories

### Best Practices

1. **Use Descriptive Slugs**: Slugs appear in URLs, make them SEO-friendly
2. **Consistent Naming**: Use consistent naming across languages
3. **Logical Grouping**: Group related attributes together using the Order field
4. **Test Filters**: Always test filters after configuration changes
5. **Performance**: Limit the number of filterable attributes per category (5-10 recommended)
6. **Attribute Values**: Keep attribute value lists manageable (< 20 values per attribute)

---

## Frontend Integration

### Filter Sidebar Component

The filter sidebar is implemented as a Blade component: `components/filter-sidebar.blade.php`

#### Usage

```blade
<x-filter-sidebar 
    :filters="$availableFilters"
    :current-filters="request()->all()"
    :category="$category ?? null"
/>
```

#### Props

- `filters`: Array of available filters from ProductFilterService
- `current-filters`: Current request parameters
- `category`: Optional category object for context

### JavaScript Integration

The filter sidebar uses `public/js/filter-sidebar.js` for interactive functionality:

- Checkbox state management
- URL parameter updates
- Form submission
- Price range slider
- Filter preservation

### Mobile Filter Drawer

For mobile devices, filters are displayed in a slide-over drawer:

```blade
<x-mobile-filter-toggle :active-count="$activeFilterCount" />
```

The drawer automatically opens/closes and maintains filter state.

### Category Navigation

Display category navigation with:

```blade
<x-category-nav :categories="$categories" />
```

This component:
- Shows top-level categories with icons
- Displays sub-categories on hover
- Generates proper URLs
- Supports RTL/LTR layouts

---

## Multi-language Support

### Language Files

Filter labels and UI text are stored in language files:

```
lang/
├── en/messages.php
├── ar/messages.php
└── he/messages.php
```

### Adding Translations

Add new filter-related translations to each language file:

```php
// lang/en/messages.php
return [
    'filters' => 'Filters',
    'strong_offers' => 'Strong Offers',
    'in_stock' => 'In Stock',
    'out_of_stock' => 'Out of Stock',
    'price_range' => 'Price Range',
    'apply_filters' => 'Apply Filters',
    'clear_filters' => 'Clear Filters',
    // ...
];
```

### RTL Layout

The system automatically applies RTL layout for Arabic and Hebrew:

```blade
<div class="filter-sidebar" dir="{{ in_array(app()->getLocale(), ['ar', 'he']) ? 'rtl' : 'ltr' }}">
    <!-- Filter content -->
</div>
```

### Database Multi-language Fields

Categories, attributes, and attribute values store translations in dedicated columns:

- `name_en`, `name_ar`, `name_he`
- `value_en`, `value_ar`, `value_he`

Access localized values using model accessors:

```php
$category->name; // Returns name in current locale
$attribute->name; // Returns name in current locale
$attributeValue->value; // Returns value in current locale
```

---

## Troubleshooting

### Filters Not Appearing

**Problem**: Filters don't show in the sidebar

**Solutions**:
1. Verify attributes are assigned to the category via **Manage Attributes**
2. Check that `is_filterable = true` for the attribute
3. Ensure attribute values exist and are active
4. Verify products have attribute values assigned

### Incorrect Filter Counts

**Problem**: Filter counts don't match actual products

**Solutions**:
1. Clear application cache: `php artisan cache:clear`
2. Verify database indexes are created
3. Check that products are active (`is_active = true`)
4. Ensure product-attribute relationships are correct

### URL Parameters Not Working

**Problem**: Filters don't apply when URL parameters are present

**Solutions**:
1. Verify ProductFilterService is being used in the controller
2. Check that filter parameters match the expected format
3. Ensure JavaScript is properly updating URLs
4. Check browser console for JavaScript errors

### Performance Issues

**Problem**: Filtering is slow with many products

**Solutions**:
1. Verify all indexes are created (see Database Schema section)
2. Enable query caching for filter counts
3. Consider pagination with smaller page sizes
4. Use eager loading for relationships

---

## API Reference Quick Links

- **ProductFilterService**: `app/Services/ProductFilterService.php`
- **Product Model**: `app/Models/Product.php`
- **Category Model**: `app/Models/Category.php`
- **Attribute Model**: `app/Models/Attribute.php`
- **AttributeValue Model**: `app/Models/AttributeValue.php`
- **ProductController**: `app/Http/Controllers/ProductController.php`
- **CategoryController**: `app/Http/Controllers/CategoryController.php`
- **Admin Controllers**: `app/Http/Controllers/Admin/`

---

## Support

For additional help or questions about the catalog filtering system, please refer to:

- Design Document: `.kiro/specs/advanced-catalog-filtering/design.md`
- Requirements Document: `.kiro/specs/advanced-catalog-filtering/requirements.md`
- Implementation Tasks: `.kiro/specs/advanced-catalog-filtering/tasks.md`
