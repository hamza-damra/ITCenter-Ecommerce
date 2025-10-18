# ITCenter E-commerce - AI Coding Assistant Guide

## Architecture Overview

This is a **Laravel 12** e-commerce platform with a **hybrid architecture**:
- **Web routes** (`routes/web.php`) return Blade views for server-rendered pages
- **API routes** (`routes/api.php` under `/v1` prefix) provide JSON responses using `ApiResponses` trait
- **Dual controller pattern**: Web controllers (e.g., `CartController`) render views, while API controllers (e.g., `Api\CartController`) handle business logic and return JSON

## Multi-Language System

**Critical pattern**: All content fields use locale suffixes (`name_en`, `name_ar`, `name_he`).

### Model Accessors
Models like `Product`, `Category`, `Brand` have dynamic accessors:
```php
public function getNameAttribute() {
    $locale = app()->getLocale();
    return $this->{"name_$locale"} ?? $this->name_en;
}
```
Access via `$product->name` (auto-localized) or explicitly via `$product->name_ar`.

### Locale Helpers
Use `app/Helpers/LocaleHelper.php` functions (autoloaded):
- `is_rtl()` - Check if current locale is RTL (ar, he)
- `current_locale()` - Get active locale
- `__t('key')` - Translation shortcut

### Configuration
- Default locale: **Arabic** (`ar`) - see `config/app.php`
- Supported: `['en', 'ar', 'he']`
- Locale detection: URL param → Session → Browser → Default (see `SetLocale` middleware)

## Database & Models

### Stock Management Pattern
Products use `stock_status` enum: `'in_stock' | 'low_stock' | 'out_of_stock'`
- Auto-calculated based on `stock_quantity` vs `min_stock_quantity`
- Use scopes: `Product::active()`, `Product::featured()`

### Relationships to Remember
- Products have many-to-many with Offers through `product_offers` pivot table
- Cart uses **session OR user** identifier pattern (see `CartController::getCartIdentifier()`)
- Favorites similar pattern for guest/authenticated users

### Custom Exceptions
Use domain-specific exceptions in `app/Exceptions/`:
- `OutOfStockException` - Auto-renders JSON response
- `InvalidQuantityException`, `ProductNotAvailableException`

## Development Workflows

### After Pulling Changes
```bash
composer install
php artisan config:clear
php artisan cache:clear
```

### Database Seeding
- **Quick seeding** (remote/slow DBs): `php artisan db:seed --class=QuickSeeder`
- **Full seeding**: `php artisan db:seed`
- QuickSeeder disables foreign key checks for batch inserts

### Running Development
```bash
# Auto-setup (installs deps, builds assets)
composer setup

# Dev server with hot reload, queue, logs, Vite
composer dev

# Or manually
php artisan serve
npm run dev
```

### Testing
```bash
composer test  # Clears config and runs PHPUnit
```

## API Response Standards

All API controllers use `App\Traits\ApiResponses`:
```php
return $this->successResponse($data, 'Message', 200);
return $this->errorResponse('Error message', 400);
return $this->notFoundResponse();
return $this->validationErrorResponse($errors);
```

**Always** return JSON with `success`, `message`, `data`/`error` keys.

## Admin Dashboard

**Advanced SQL patterns** in `Admin\DashboardController`:
- Uses `withCount()`, `withAvg()` for eager-loaded aggregates
- Complex queries with joins for offer statistics
- `selectRaw()` for custom calculations (stock value, cart totals)

When adding dashboard stats, follow existing pattern:
```php
'metric_name' => Model::selectRaw('AGGREGATE() as alias')->value('alias')
```

## View Architecture

- **Public views**: Extend `layouts.app`
- **Admin views**: Extend `admin.layout`
- Located in `resources/views/` with clear separation

## Frontend Stack

- **Tailwind CSS 4.0** via Vite
- **Laravel Vite Plugin** for asset bundling
- Entry points: `resources/css/app.css`, `resources/js/app.js`

## Common Patterns

### Slug Generation
Auto-generated in model boot method:
```php
static::creating(function ($product) {
    if (empty($product->slug)) {
        $product->slug = Str::slug($product->name_en);
    }
});
```

### Route Model Binding
Routes use slugs: `/product/{slug}` automatically resolves via `Product::where('slug', $slug)->firstOrFail()`

### Soft Deletes
Models use `SoftDeletes` trait - always consider `deleted_at` in queries

## Key Files Reference

- **Locale logic**: `app/Http/Middleware/SetLocale.php`
- **Model patterns**: `app/Models/Product.php` (lines 80-150 for accessors)
- **API standards**: `app/Traits/ApiResponses.php`
- **Dashboard analytics**: `app/Http/Controllers/Admin/DashboardController.php`

## Don't

- ❌ Create single-locale fields (always use `_en`, `_ar`, `_he` suffixes)
- ❌ Hardcode locale in queries (use accessors or `app()->getLocale()`)
- ❌ Return HTML from API routes (use `ApiResponses` trait)
- ❌ Forget to run `composer install` after pulling (common "Class not found" cause)
