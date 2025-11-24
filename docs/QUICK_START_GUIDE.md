# Quick Start Guide - Advanced Catalog Filtering

## For Administrators

### 5-Minute Setup

1. **Create a Category**
   ```
   Admin Panel > Categories > Create New
   - Name: "Monitors"
   - Slug: "monitors"
   - Active: ✓
   ```

2. **Create Attributes**
   ```
   Admin Panel > Attributes > Create New
   - Name: "Refresh Rate"
   - Slug: "refresh_rate"
   - Type: select
   - Filterable: ✓
   ```

3. **Add Attribute Values**
   ```
   Admin Panel > Attributes > Manage Values
   - Value: "60Hz", Slug: "60hz"
   - Value: "144Hz", Slug: "144hz"
   - Value: "240Hz", Slug: "240hz"
   ```

4. **Assign to Category**
   ```
   Admin Panel > Categories > Manage Attributes
   - Select "Refresh Rate"
   - Save
   ```

5. **Configure Products**
   ```
   Admin Panel > Products > Edit
   - Category: Monitors
   - Refresh Rate: 144Hz
   - Strong Offer: ✓ (optional)
   - Save
   ```

Done! Filters will now appear on the category page.

---

## For Developers

### Quick Integration

#### 1. Apply Filters in Controller

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

#### 2. Display Filters in View

```blade
<x-filter-sidebar 
    :filters="$availableFilters"
    :current-filters="request()->all()"
/>
```

#### 3. URL Format Examples

```
# Strong offers
/products?strong_offers=1

# Category with filters
/category/monitors/gaming?attr[refresh_rate][]=144hz&brand[]=asus

# Multiple filters
/products?strong_offers=1&brand[]=nvidia&stock=in&min_price=300&max_price=1000
```

---

## Common Tasks

### Add a New Filter Type

1. Create attribute in admin
2. Add values
3. Assign to category
4. ProductFilterService handles it automatically

### Change Filter Order

1. Edit attribute
2. Update "Order" field
3. Lower numbers appear first

### Hide a Filter

1. Edit attribute
2. Uncheck "Filterable"
3. Or remove from category via "Manage Attributes"

### Mark Products as Strong Offers

1. Edit product
2. Check "Strong Offer"
3. Enter discount percentage (optional)
4. Product appears in `/products?strong_offers=1`

---

## Testing Checklist

- [ ] Filters appear in sidebar
- [ ] Filter counts are accurate
- [ ] Applying filters updates products
- [ ] URL parameters are correct
- [ ] Pagination maintains filters
- [ ] Mobile drawer works
- [ ] RTL layout for Arabic/Hebrew
- [ ] Multi-language labels display correctly

---

## Need Help?

See full documentation: `docs/CATALOG_FILTERING_SYSTEM.md`
