# Migration Guide - Advanced Catalog Filtering System

## Overview

This guide helps you migrate from the basic product listing to the advanced catalog filtering system.

---

## Pre-Migration Checklist

- [ ] Backup your database
- [ ] Review existing categories and products
- [ ] Plan your attribute structure
- [ ] Test on staging environment first
- [ ] Notify users of potential downtime

---

## Step 1: Run Database Migrations

### Execute Migrations

```bash
php artisan migrate
```

This will create:
- `attribute_category` pivot table
- `product_attribute_values` pivot table
- New columns in `products`, `categories`, `attributes`, `attribute_values`
- Performance indexes

### Verify Migration

```bash
php artisan migrate:status
```

All migrations should show "Ran".

---

## Step 2: Update Existing Data

### 2.1 Update Categories

Add icons and positions to existing categories:

```sql
-- Set default positions
UPDATE categories 
SET position = id * 10 
WHERE position = 0;

-- Add icons (optional, can be done via admin panel)
UPDATE categories 
SET icon = 'default-icon.png' 
WHERE icon IS NULL AND parent_id IS NULL;
```

### 2.2 Update Attributes

Migrate single-language attributes to multi-language:

```sql
-- Copy existing name to all language fields
UPDATE attributes 
SET 
    name_en = name,
    name_ar = name,
    name_he = name
WHERE name_en IS NULL;

-- Set default filterable status
UPDATE attributes 
SET is_filterable = TRUE 
WHERE is_filterable IS NULL;
```

### 2.3 Update Attribute Values

Migrate single-language values to multi-language:

```sql
-- Copy existing value to all language fields
UPDATE attribute_values 
SET 
    value_en = value,
    value_ar = value,
    value_he = value
WHERE value_en IS NULL;

-- Generate slugs from values (basic approach)
UPDATE attribute_values 
SET slug = LOWER(REPLACE(value, ' ', '-'))
WHERE slug IS NULL OR slug = '';
```

**Note**: You should manually review and update translations for better user experience.

---

## Step 3: Configure Attribute-Category Mappings

### Option A: Via Admin Panel (Recommended)

1. Go to **Admin Panel > Categories**
2. For each sub-category, click **"Manage Attributes"**
3. Select relevant attributes
4. Save

### Option B: Via Database (Bulk)

If you want to assign attributes to all categories initially:

```sql
-- Example: Assign "Brand" attribute to all categories
INSERT INTO attribute_category (attribute_id, category_id, created_at, updated_at)
SELECT 
    (SELECT id FROM attributes WHERE slug = 'brand'),
    id,
    NOW(),
    NOW()
FROM categories
WHERE parent_id IS NOT NULL; -- Only sub-categories
```

---

## Step 4: Assign Attribute Values to Products

### Option A: Via Admin Panel

1. Edit each product
2. Select category (attributes will load automatically)
3. Assign attribute values
4. Save

### Option B: Via Seeder (Bulk)

Create a migration seeder:

```php
// database/seeders/MigrateProductAttributesSeeder.php

public function run()
{
    $products = Product::all();
    
    foreach ($products as $product) {
        // Example: Assign brand as an attribute
        if ($product->brand_id) {
            $brandAttribute = Attribute::where('slug', 'brand')->first();
            $brandValue = AttributeValue::where('attribute_id', $brandAttribute->id)
                ->where('value_en', $product->brand->name)
                ->first();
            
            if ($brandValue) {
                $product->attributeValues()->attach($brandValue->id);
            }
        }
        
        // Add more attribute assignments based on your data structure
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=MigrateProductAttributesSeeder
```

---

## Step 5: Update Controllers

### Replace Old Filtering Logic

**Before:**
```php
public function index(Request $request)
{
    $query = Product::query();
    
    if ($request->has('brand')) {
        $query->whereIn('brand_id', $request->brand);
    }
    
    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    
    // ... more inline filtering
    
    $products = $query->paginate(12);
    return view('products', compact('products'));
}
```

**After:**
```php
use App\Services\ProductFilterService;

public function index(Request $request)
{
    $query = Product::query()->active();
    
    $filterService = new ProductFilterService();
    $query = $filterService->applyFilters($query, $request);
    
    $products = $query->paginate(12);
    $availableFilters = $filterService->getAvailableFilters();
    
    return view('products', compact('products', 'availableFilters'));
}
```

---

## Step 6: Update Views

### Add Filter Sidebar

**Before:**
```blade
<div class="products-page">
    <div class="product-grid">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</div>
```

**After:**
```blade
<div class="products-page">
    <aside class="filter-sidebar">
        <x-filter-sidebar 
            :filters="$availableFilters"
            :current-filters="request()->all()"
        />
    </aside>
    
    <main class="product-grid">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </main>
</div>
```

### Update Category Navigation

Replace old category links with the new component:

```blade
<x-category-nav :categories="$categories" />
```

---

## Step 7: Update Routes

### Add New Routes

```php
// Category routes with sub-category support
Route::get('/category/{parentSlug}/{childSlug?}', [CategoryController::class, 'show'])
    ->name('category.show');

// Strong offers route (optional, uses existing products route)
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');
```

### Update Existing Links

**Before:**
```blade
<a href="{{ route('category', $category->slug) }}">
```

**After:**
```blade
<a href="{{ route('category.show', [$category->parent->slug ?? $category->slug, $category->parent ? $category->slug : null]) }}">
```

Or use the helper method if available:
```blade
<a href="{{ $category->url }}">
```

---

## Step 8: Configure Strong Offers

### Update Products

1. Go to **Admin Panel > Products**
2. Edit products you want to mark as strong offers
3. Check **"Strong Offer"**
4. Enter **Discount Percentage** (optional)
5. Save

### Update Home Page Promotional Card

Ensure the "Shop Now" button links to:

```blade
<a href="{{ route('products.index', ['strong_offers' => 1]) }}" class="btn btn-primary">
    {{ __('messages.shop_now') }}
</a>
```

---

## Step 9: Test the Migration

### Functional Testing

1. **Category Navigation**
   - [ ] Top-level categories display
   - [ ] Sub-categories appear on hover
   - [ ] URLs follow correct format

2. **Filtering**
   - [ ] Filters appear in sidebar
   - [ ] Filter counts are accurate
   - [ ] Applying filters updates products
   - [ ] Multiple filters work together (AND logic)

3. **Strong Offers**
   - [ ] Promotional card links to filtered page
   - [ ] Only strong offer products appear
   - [ ] Filter checkbox reflects URL state

4. **Multi-language**
   - [ ] Category names display in correct language
   - [ ] Attribute names display in correct language
   - [ ] Filter labels are translated
   - [ ] RTL layout works for Arabic/Hebrew

5. **Performance**
   - [ ] Page load times are acceptable
   - [ ] Filter counts load quickly
   - [ ] Pagination works smoothly

### Database Integrity

```sql
-- Check for products without categories
SELECT COUNT(*) FROM products WHERE category_id IS NULL;

-- Check for orphaned attribute values
SELECT COUNT(*) FROM product_attribute_values pav
LEFT JOIN products p ON pav.product_id = p.id
WHERE p.id IS NULL;

-- Check for categories without positions
SELECT COUNT(*) FROM categories WHERE position = 0;

-- Check for attributes without translations
SELECT COUNT(*) FROM attributes 
WHERE name_en IS NULL OR name_ar IS NULL OR name_he IS NULL;
```

---

## Step 10: Optimize Performance

### Clear Caches

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

### Optimize Autoloader

```bash
composer dump-autoload -o
```

### Cache Configuration (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Rollback Plan

If you need to rollback:

### 1. Restore Database Backup

```bash
# Restore from backup
mysql -u username -p database_name < backup.sql
```

### 2. Rollback Migrations

```bash
php artisan migrate:rollback --step=5
```

### 3. Revert Code Changes

```bash
git checkout previous-version
composer install
```

---

## Post-Migration Tasks

### 1. Update Documentation

- [ ] Update internal wiki
- [ ] Train admin users on new features
- [ ] Document custom attribute configurations

### 2. Monitor Performance

- [ ] Check server logs for errors
- [ ] Monitor database query performance
- [ ] Track page load times

### 3. Gather Feedback

- [ ] Survey admin users
- [ ] Monitor customer behavior
- [ ] Track filter usage analytics

---

## Common Migration Issues

### Issue 1: Missing Translations

**Problem**: Attributes show in English only

**Solution**: Update translations via admin panel or SQL:

```sql
UPDATE attributes 
SET 
    name_ar = 'الترجمة العربية',
    name_he = 'תרגום עברי'
WHERE slug = 'attribute-slug';
```

### Issue 2: Filters Not Appearing

**Problem**: No filters show in sidebar

**Solution**: Assign attributes to categories:

```sql
-- Check assignments
SELECT c.name, a.name_en 
FROM attribute_category ac
JOIN categories c ON ac.category_id = c.id
JOIN attributes a ON ac.attribute_id = a.id;

-- If empty, assign via admin panel
```

### Issue 3: Incorrect Filter Counts

**Problem**: Filter counts don't match products

**Solution**: Rebuild indexes and clear cache:

```bash
php artisan cache:clear
php artisan db:seed --class=UpdateFilterCountsSeeder
```

### Issue 4: Slow Performance

**Problem**: Filtering is slow

**Solution**: Verify indexes exist:

```sql
SHOW INDEX FROM products;
SHOW INDEX FROM attribute_category;
SHOW INDEX FROM product_attribute_values;
```

If missing, run migrations again or create manually.

---

## Support

For issues during migration:

1. Check logs: `storage/logs/laravel.log`
2. Review migration files: `database/migrations/`
3. Consult full documentation: `docs/CATALOG_FILTERING_SYSTEM.md`
4. Test on staging before production

---

## Migration Timeline Estimate

- **Small site** (< 100 products): 2-4 hours
- **Medium site** (100-1000 products): 4-8 hours
- **Large site** (> 1000 products): 1-2 days

*Includes planning, execution, testing, and optimization.*
