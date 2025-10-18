# Dashboard Analytics - Advanced SQL Queries

## Overview
This document describes the advanced SQL queries implemented in the admin dashboard to provide real-time data insights.

## Implemented Statistics

### 1. Product Analytics
- **Total Products**: Count of all products in the database
- **Active Products**: Products with `is_active = true`
- **Featured Products**: Products with `is_featured = true`
- **Products Added Today/Week/Month**: Time-based product additions tracking

### 2. Stock Management
```php
// Out of stock products
Product::where('stock_status', 'out_of_stock')->count()

// Low stock products
Product::where('stock_status', 'low_stock')->count()

// In stock products
Product::where('stock_status', 'in_stock')->count()

// Total stock value calculation
Product::selectRaw('SUM(price * quantity) as value')
    ->where('stock_status', '!=', 'out_of_stock')
    ->value('value')
```

### 3. Category & Brand Performance
```php
// Category distribution with product count
Category::withCount('products')
    ->orderByDesc('products_count')
    ->limit(5)
    ->get()

// Brand distribution with product count
Brand::withCount('products')
    ->orderByDesc('products_count')
    ->limit(5)
    ->get()

// Active categories and brands
Category::where('is_active', true)->count()
Brand::where('is_active', true)->count()
```

### 4. Review & Rating Analytics
```php
// Total reviews
Review::count()

// Average rating across all products
Review::avg('rating')

// Pending vs Approved reviews
Review::where('is_approved', false)->count()
Review::where('is_approved', true)->count()

// Most reviewed product
Product::withCount('reviews')
    ->having('reviews_count', '>', 0)
    ->orderByDesc('reviews_count')
    ->first()

// Reviews added this month
Review::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count()
```

### 5. Offer Analytics
```php
// Active offers
Offer::active()->count()

// Expired offers
Offer::where('end_date', '<', now())->count()

// Products currently on offer (using JOIN)
DB::table('products')
    ->join('product_offers', 'products.id', '=', 'product_offers.product_id')
    ->join('offers', 'product_offers.offer_id', '=', 'offers.id')
    ->where('offers.is_active', true)
    ->where('offers.start_date', '<=', now())
    ->where('offers.end_date', '>=', now())
    ->distinct('products.id')
    ->count('products.id')
```

### 6. Customer & User Analytics
```php
// Total users
User::count()

// Users registered this month
User::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count()
```

### 7. Shopping Cart Analytics
```php
// Total items in all carts
CartItem::sum('quantity')

// Number of active carts
CartItem::distinct('session_id')->count('session_id')

// Total cart value
CartItem::selectRaw('SUM(quantity * price) as total')
    ->value('total')
```

### 8. Favorites/Wishlist Analytics
```php
// Total favorites
Favorite::count()

// Most favorited product
Product::withCount('favorites')
    ->having('favorites_count', '>', 0)
    ->orderByDesc('favorites_count')
    ->first()
```

### 9. Price Analytics
```php
// Average product price
Product::avg('price')

// Highest priced product
Product::orderByDesc('price')->first()

// Lowest priced product
Product::where('price', '>', 0)
    ->orderBy('price')
    ->first()
```

### 10. Top Performing Products
```php
// Top rated products with relationships
Product::with(['category', 'brand'])
    ->withAvg('reviews', 'rating')
    ->withCount('reviews')
    ->having('reviews_count', '>', 0)
    ->orderByDesc('reviews_avg_rating')
    ->limit(5)
    ->get()
```

### 11. Low Stock Alerts
```php
// Products needing attention
Product::with(['category', 'brand'])
    ->where('stock_status', 'low_stock')
    ->orWhere('stock_status', 'out_of_stock')
    ->orderBy('quantity')
    ->limit(10)
    ->get()

// Count of products needing attention
Product::whereIn('stock_status', ['low_stock', 'out_of_stock'])->count()
```

## Dashboard Sections

### Main Statistics Cards (8 cards)
1. Total Products (Purple gradient)
2. Active Products (Pink/Red gradient)
3. Total Categories (Orange gradient)
4. Total Brands (Blue gradient)
5. Featured Products (Green gradient)
6. Out of Stock (Red gradient)
7. Total Reviews (Purple gradient)
8. Active Offers (Yellow/Orange gradient)

### Additional Analytics Cards (4 cards)
1. Total Cart Value
2. Average Rating
3. Total Stock Value
4. Total Favorites

### Data Tables
1. **Recent Products**: Shows the 10 most recently added products with:
   - Product image, name, and SKU
   - Category
   - Price
   - Stock quantity
   - Active status

2. **Top Rated Products**: Shows the 5 highest-rated products with:
   - Product details
   - Average rating
   - Number of reviews
   - Price

3. **Low Stock Alerts**: Shows products that need restocking:
   - Product details
   - Current stock quantity
   - Stock status (low stock or out of stock)

### Quick Actions
- Add New Product
- Create Category
- Add New Brand
- Manage Products
- Manage Categories
- Manage Brands

## Performance Considerations

### Optimizations Applied
1. **Eager Loading**: Using `with()` to prevent N+1 queries
2. **Selective Queries**: Using `select()` and `selectRaw()` for specific columns
3. **Aggregations**: Using database-level aggregations (COUNT, SUM, AVG)
4. **Indexes**: Leveraging existing indexes on foreign keys and status columns
5. **Query Limits**: Limiting results to prevent memory issues

### Query Efficiency
- All statistics are calculated in a single page load
- Complex aggregations are done at the database level
- Relationships are eagerly loaded to minimize queries
- Use of query builder for complex joins when needed

## Future Enhancements

Potential additions for even more insights:
1. Sales trends (daily, weekly, monthly)
2. Revenue analytics
3. Customer behavior tracking
4. Conversion rates
5. Product performance metrics
6. Inventory turnover rates
7. Return/refund statistics
8. Shipping analytics
9. Payment method statistics
10. Geographic sales distribution

## Technical Notes

- All queries are protected against SQL injection via Eloquent ORM
- Null coalescing operators (`??`) used for safe defaults
- Rounding applied to monetary values (2 decimals)
- Time-based queries use Laravel's Carbon for accurate date handling
- Translation support for all dashboard labels
