# Advanced Catalog Filtering System - Documentation

Welcome to the documentation for the Advanced Catalog Filtering System. This comprehensive filtering solution provides hierarchical categories, dynamic attribute-based filtering, and promotional filtering capabilities for e-commerce platforms.

---

## Documentation Index

### 📚 Core Documentation

1. **[Catalog Filtering System Guide](CATALOG_FILTERING_SYSTEM.md)**
   - Complete system overview
   - Filter URL parameter format
   - Database schema reference
   - ProductFilterService API
   - Admin configuration guide
   - Frontend integration
   - Multi-language support
   - Troubleshooting

2. **[API Reference](API_REFERENCE.md)**
   - ProductFilterService methods
   - Model methods and relationships
   - Blade components
   - JavaScript API
   - Helper functions
   - Events and exceptions

3. **[Quick Start Guide](QUICK_START_GUIDE.md)**
   - 5-minute setup for administrators
   - Quick integration for developers
   - Common tasks
   - Testing checklist

4. **[Migration Guide](MIGRATION_GUIDE.md)**
   - Pre-migration checklist
   - Step-by-step migration process
   - Database updates
   - Code updates
   - Testing procedures
   - Rollback plan

---

## Quick Links

### For Administrators

- **Getting Started**: [Quick Start Guide - For Administrators](QUICK_START_GUIDE.md#for-administrators)
- **Category Setup**: [Admin Configuration Guide - Step 1](CATALOG_FILTERING_SYSTEM.md#step-1-create-categories)
- **Attribute Setup**: [Admin Configuration Guide - Step 3](CATALOG_FILTERING_SYSTEM.md#step-3-create-attributes)
- **Product Configuration**: [Admin Configuration Guide - Step 6](CATALOG_FILTERING_SYSTEM.md#step-6-configure-products)

### For Developers

- **Integration**: [Quick Start Guide - For Developers](QUICK_START_GUIDE.md#for-developers)
- **ProductFilterService**: [API Reference - ProductFilterService](API_REFERENCE.md#productfilterservice)
- **Model Methods**: [API Reference - Model Methods](API_REFERENCE.md#model-methods)
- **Blade Components**: [API Reference - Blade Components](API_REFERENCE.md#blade-components)

### For Project Managers

- **System Overview**: [Catalog Filtering System - Overview](CATALOG_FILTERING_SYSTEM.md#overview)
- **Migration Timeline**: [Migration Guide - Timeline](MIGRATION_GUIDE.md#migration-timeline-estimate)
- **Feature List**: [Requirements Document](../.kiro/specs/advanced-catalog-filtering/requirements.md)

---

## System Features

### ✨ Core Features

- **Strong Offers System**: Promotional filtering for special deals
- **Hierarchical Categories**: Parent/child category structure with SEO-friendly URLs
- **Dynamic Attribute Filtering**: Category-specific filters managed through admin panel
- **Multi-language Support**: Full support for English, Arabic, and Hebrew
- **RTL Layout**: Automatic RTL layout for Arabic and Hebrew
- **Mobile Responsive**: Filter drawer for mobile devices
- **Performance Optimized**: Database indexes and query optimization

### 🎯 Filter Types

1. **Strong Offers**: Filter products marked as special promotions
2. **Categories**: Browse by hierarchical category structure
3. **Brands**: Filter by product brands
4. **Stock Status**: Filter by in-stock or out-of-stock
5. **Price Range**: Filter by minimum and maximum price
6. **Attributes**: Dynamic category-specific filters (e.g., refresh rate, memory size)

### 🔧 Admin Features

- Complete CRUD for categories, attributes, and attribute values
- Assign attributes to specific categories
- Assign attribute values to products
- Mark products as strong offers
- Multi-language content management
- Category icons and positioning
- Bulk operations support

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ Products     │  │ Categories   │  │ Admin Panel  │      │
│  │ Listing      │  │ Navigation   │  │ (CRUD)       │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────────┐
│                     Application Layer                        │
│  ┌──────────────────────────────────────────────────┐       │
│  │         ProductFilterService                      │       │
│  │  - applyFilters()                                 │       │
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
└─────────────────────────────────────────────────────────────┘
```

---

## Technology Stack

- **Backend**: Laravel 10+
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates, JavaScript
- **Styling**: CSS (with RTL support)
- **Testing**: PHPUnit, Property-Based Testing

---

## Installation

### Prerequisites

- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js & NPM

### Setup

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Seed Database** (Optional)
   ```bash
   php artisan db:seed --class=CategorySeeder
   php artisan db:seed --class=AttributeSeeder
   ```

3. **Clear Caches**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

4. **Configure Admin Panel**
   - Follow the [Admin Configuration Guide](CATALOG_FILTERING_SYSTEM.md#admin-configuration-guide)

---

## Usage Examples

### Basic Product Filtering

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

### Category-Specific Filtering

```php
public function show(Request $request, string $parentSlug, ?string $childSlug = null)
{
    $category = $this->loadCategory($parentSlug, $childSlug);
    
    $query = Product::where('category_id', $category->id)->active();
    
    $filterService = new ProductFilterService();
    $query = $filterService->applyFilters($query, $request, $category);
    
    $products = $query->paginate(12);
    $availableFilters = $filterService->getAvailableFilters($category);
    
    return view('category-products', compact('category', 'products', 'availableFilters'));
}
```

### Display Filters in View

```blade
<x-filter-sidebar 
    :filters="$availableFilters"
    :current-filters="request()->all()"
    :category="$category ?? null"
/>
```

---

## URL Examples

### Strong Offers
```
/products?strong_offers=1
```

### Category with Filters
```
/category/pc-components/graphics-cards?
  attr[memory][]=8gb&
  attr[interface][]=pcie4&
  brand[]=nvidia&
  stock=in&
  min_price=300&
  max_price=1000
```

### Multiple Filters
```
/products?
  strong_offers=1&
  brand[]=asus&brand[]=msi&
  stock=in&
  sort=price&
  order=asc
```

---

## Testing

### Run Tests

```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/CatalogFilteringPropertyTest.php

# With coverage
php artisan test --coverage
```

### Property-Based Tests

The system includes comprehensive property-based tests that verify correctness properties across all valid inputs. See [Design Document - Correctness Properties](../.kiro/specs/advanced-catalog-filtering/design.md#correctness-properties) for details.

---

## Performance

### Database Indexes

The system includes optimized indexes for:
- Product filtering by category and status
- Attribute-category relationships
- Product-attribute value relationships
- Category hierarchy queries

### Caching Recommendations

```php
// Cache filter counts
$filters = Cache::remember('filters_' . $category->id, 3600, function () use ($category) {
    return $filterService->getAvailableFilters($category);
});

// Cache category tree
$categories = Cache::remember('category_tree', 3600, function () {
    return Category::with('children')->whereNull('parent_id')->get();
});
```

---

## Troubleshooting

### Common Issues

1. **Filters not appearing**: Check attribute-category assignments
2. **Incorrect counts**: Clear cache and verify indexes
3. **Slow performance**: Review query optimization and indexes
4. **Translation issues**: Verify multi-language fields are populated

See [Troubleshooting Guide](CATALOG_FILTERING_SYSTEM.md#troubleshooting) for detailed solutions.

---

## Contributing

### Code Style

Follow Laravel coding standards and PSR-12.

### Testing

All new features must include:
- Unit tests for core logic
- Property-based tests for correctness properties
- Integration tests for user flows

### Documentation

Update relevant documentation when adding features:
- API Reference for new methods
- User Guide for new admin features
- Migration Guide for breaking changes

---

## Support

### Documentation

- **Full System Guide**: [CATALOG_FILTERING_SYSTEM.md](CATALOG_FILTERING_SYSTEM.md)
- **API Reference**: [API_REFERENCE.md](API_REFERENCE.md)
- **Quick Start**: [QUICK_START_GUIDE.md](QUICK_START_GUIDE.md)
- **Migration**: [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)

### Specification Documents

- **Requirements**: [requirements.md](../.kiro/specs/advanced-catalog-filtering/requirements.md)
- **Design**: [design.md](../.kiro/specs/advanced-catalog-filtering/design.md)
- **Tasks**: [tasks.md](../.kiro/specs/advanced-catalog-filtering/tasks.md)

---

## License

This system is part of the IT Center E-commerce platform.

---

## Version

**Current Version**: 1.0.0

**Last Updated**: November 2025

---

## Changelog

### Version 1.0.0 (November 2025)
- Initial release
- Strong offers system
- Hierarchical categories
- Dynamic attribute filtering
- Multi-language support (EN, AR, HE)
- RTL layout support
- Mobile responsive design
- Admin CRUD interfaces
- Performance optimizations

---

## Roadmap

### Planned Features

- [ ] Advanced search with filters
- [ ] Filter presets/saved searches
- [ ] Analytics dashboard for filter usage
- [ ] A/B testing for filter layouts
- [ ] API endpoints for headless commerce
- [ ] GraphQL support
- [ ] Elasticsearch integration for advanced filtering

---

## Credits

Developed as part of the Advanced Catalog Filtering specification.

**Key Components**:
- ProductFilterService: Centralized filtering logic
- Hierarchical Category System: SEO-friendly URLs
- Dynamic Attribute System: Admin-configurable filters
- Multi-language Support: EN, AR, HE with RTL

---

**Need Help?** Start with the [Quick Start Guide](QUICK_START_GUIDE.md) or consult the [Full System Guide](CATALOG_FILTERING_SYSTEM.md).
