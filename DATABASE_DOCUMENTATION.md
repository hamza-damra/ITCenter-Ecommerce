# IT Center E-commerce - Database Documentation

## Overview

This document provides comprehensive information about the database structure for the IT Center E-commerce platform. The database is designed to handle a full-featured e-commerce system with products, categories, brands, attributes, reviews, and offers.

## Database Tables

### 1. Categories Table
Stores product categories with support for nested subcategories.

**Fields:**
- `id` - Primary key
- `name` - Category name
- `slug` - URL-friendly identifier (unique)
- `description` - Category description (nullable)
- `image` - Category image path (nullable)
- `parent_id` - Foreign key to parent category (nullable, for subcategories)
- `is_active` - Active status (boolean)
- `order` - Display order (integer)
- `meta_title` - SEO meta title (nullable)
- `meta_description` - SEO meta description (nullable)
- `meta_keywords` - SEO keywords (nullable)
- `created_at`, `updated_at` - Timestamps
- `deleted_at` - Soft delete timestamp

**Relationships:**
- `parent` - belongsTo Category (self-referencing)
- `children` - hasMany Category (subcategories)
- `products` - hasMany Product

---

### 2. Brands Table
Stores brand information for products.

**Fields:**
- `id` - Primary key
- `name` - Brand name
- `slug` - URL-friendly identifier (unique)
- `description` - Brand description (nullable)
- `logo` - Brand logo path (nullable)
- `website` - Brand website URL (nullable)
- `email` - Brand contact email (nullable)
- `phone` - Brand contact phone (nullable)
- `is_active` - Active status (boolean)
- `is_featured` - Featured brand flag (boolean)
- `order` - Display order (integer)
- `meta_title` - SEO meta title (nullable)
- `meta_description` - SEO meta description (nullable)
- `meta_keywords` - SEO keywords (nullable)
- `created_at`, `updated_at` - Timestamps
- `deleted_at` - Soft delete timestamp

**Relationships:**
- `products` - hasMany Product

---

### 3. Products Table
Main products table with comprehensive product information.

**Fields:**
- `id` - Primary key
- `name` - Product name
- `slug` - URL-friendly identifier (unique)
- `sku` - Stock Keeping Unit (unique)
- `short_description` - Brief product description (nullable)
- `description` - Full product description (nullable)
- `price` - Regular price (decimal 10,2)
- `sale_price` - Sale/discounted price (nullable, decimal 10,2)
- `cost_price` - Cost price (nullable, decimal 10,2)
- `stock_quantity` - Available stock (integer)
- `min_stock_quantity` - Minimum stock level (integer)
- `main_image` - Main product image path (nullable)
- `category_id` - Foreign key to categories
- `brand_id` - Foreign key to brands (nullable)
- `is_active` - Active status (boolean)
- `is_featured` - Featured product flag (boolean)
- `is_new` - New product flag (boolean)
- `is_bestseller` - Bestseller flag (boolean)
- `track_stock` - Enable stock tracking (boolean)
- `stock_status` - Stock status enum (in_stock, out_of_stock, on_backorder)
- `weight` - Product weight (decimal 8,2, nullable)
- `length` - Product length (decimal 8,2, nullable)
- `width` - Product width (decimal 8,2, nullable)
- `height` - Product height (decimal 8,2, nullable)
- `warranty` - Warranty information (nullable)
- `views_count` - Number of views (integer)
- `sales_count` - Number of sales (integer)
- `avg_rating` - Average rating (decimal 2,1)
- `reviews_count` - Number of reviews (integer)
- `meta_title` - SEO meta title (nullable)
- `meta_description` - SEO meta description (nullable)
- `meta_keywords` - SEO keywords (nullable)
- `specifications` - JSON field for product specifications
- `created_at`, `updated_at` - Timestamps
- `deleted_at` - Soft delete timestamp

**Relationships:**
- `category` - belongsTo Category
- `brand` - belongsTo Brand
- `images` - hasMany ProductImage
- `reviews` - hasMany Review
- `attributes` - belongsToMany AttributeValue (through product_attributes)
- `offers` - belongsToMany Offer (through product_offers)

**Model Methods:**
- `getFinalPriceAttribute()` - Returns sale price if available, otherwise regular price
- `getDiscountPercentageAttribute()` - Calculates discount percentage
- `getIsOnSaleAttribute()` - Checks if product is on sale
- `getIsLowStockAttribute()` - Checks if stock is low
- `incrementViews()` - Increment view count
- `incrementSales($quantity)` - Increment sales and update stock
- `updateStockStatus()` - Update stock status based on quantity
- `updateRating()` - Recalculate average rating

---

### 4. Product Images Table
Stores multiple images for each product.

**Fields:**
- `id` - Primary key
- `product_id` - Foreign key to products
- `image_path` - Image file path
- `thumbnail_path` - Thumbnail path (nullable)
- `order` - Display order (integer)
- `is_primary` - Primary image flag (boolean)
- `alt_text` - Image alt text for SEO (nullable)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `product` - belongsTo Product

---

### 5. Attributes Table
Stores product attribute types (e.g., Color, Size, Storage).

**Fields:**
- `id` - Primary key
- `name` - Attribute name
- `slug` - URL-friendly identifier (unique)
- `type` - Display type enum (select, color, button, radio)
- `order` - Display order (integer)
- `is_active` - Active status (boolean)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `values` - hasMany AttributeValue

---

### 6. Attribute Values Table
Stores specific values for attributes.

**Fields:**
- `id` - Primary key
- `attribute_id` - Foreign key to attributes
- `value` - Attribute value (e.g., "Red", "Large", "128GB")
- `color_code` - Hex color code for color attributes (nullable)
- `order` - Display order (integer)
- `is_active` - Active status (boolean)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `attribute` - belongsTo Attribute
- `products` - belongsToMany Product (through product_attributes)

---

### 7. Product Attributes Table (Pivot)
Links products with their attribute values.

**Fields:**
- `id` - Primary key
- `product_id` - Foreign key to products
- `attribute_value_id` - Foreign key to attribute_values
- `price_adjustment` - Price adjustment for this variant (decimal 10,2)
- `stock_quantity` - Stock for this variant (integer)
- `created_at`, `updated_at` - Timestamps

---

### 8. Reviews Table
Stores customer reviews for products.

**Fields:**
- `id` - Primary key
- `product_id` - Foreign key to products
- `user_id` - Foreign key to users
- `rating` - Rating (1-5 stars, integer)
- `title` - Review title (nullable)
- `comment` - Review comment (nullable)
- `is_verified_purchase` - Verified purchase flag (boolean)
- `is_approved` - Approval status (boolean)
- `helpful_count` - Number of helpful votes (integer)
- `unhelpful_count` - Number of unhelpful votes (integer)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `product` - belongsTo Product
- `user` - belongsTo User

**Model Methods:**
- `markAsHelpful()` - Increment helpful count
- `markAsUnhelpful()` - Increment unhelpful count

---

### 9. Offers Table
Stores promotional offers and discounts.

**Fields:**
- `id` - Primary key
- `name` - Offer name
- `slug` - URL-friendly identifier (unique)
- `description` - Offer description (nullable)
- `discount_type` - Discount type enum (percentage, fixed)
- `discount_value` - Discount value (decimal 10,2)
- `min_purchase_amount` - Minimum purchase required (nullable, decimal 10,2)
- `max_uses` - Maximum number of uses (nullable, integer)
- `uses_count` - Current number of uses (integer)
- `start_date` - Offer start date (datetime)
- `end_date` - Offer end date (datetime)
- `is_active` - Active status (boolean)
- `banner_image` - Offer banner image (nullable)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- `products` - belongsToMany Product (through product_offers)

**Model Methods:**
- `isValid()` - Check if offer is currently valid
- `calculateDiscount($amount)` - Calculate discount for given amount
- `incrementUses()` - Increment uses count

---

### 10. Product Offers Table (Pivot)
Links products with offers.

**Fields:**
- `id` - Primary key
- `product_id` - Foreign key to products
- `offer_id` - Foreign key to offers
- `created_at`, `updated_at` - Timestamps

---

## Model Scopes

### Product Scopes
- `active()` - Active products only
- `featured()` - Featured products
- `new()` - New products
- `bestseller()` - Bestseller products
- `inStock()` - In stock products
- `priceRange($min, $max)` - Filter by price range

### Category Scopes
- `active()` - Active categories
- `parent()` - Parent categories only (no subcategories)

### Brand Scopes
- `active()` - Active brands
- `featured()` - Featured brands

### Review Scopes
- `approved()` - Approved reviews
- `verifiedPurchase()` - Verified purchase reviews
- `rating($rating)` - Filter by rating

### Offer Scopes
- `active()` - Active and valid offers
- `upcoming()` - Upcoming offers
- `expired()` - Expired offers

---

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This will create all the necessary database tables.

### 2. Seed the Database

```bash
php artisan db:seed
```

This will populate the database with:
- 6 main categories with subcategories
- 14 popular brands (Dell, HP, Lenovo, ASUS, etc.)
- Product attributes (Color, Storage, RAM, Screen Size, Processor)
- 85+ products (featured, on sale, bestsellers, new)
- Multiple images per product
- Customer reviews
- Active promotional offers

### 3. Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for storing product images.

---

## Database Configuration

Make sure your `.env` file has the correct database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

For SQLite (default):
```env
DB_CONNECTION=sqlite
```

---

## Controllers

All controllers have been updated to use the database:

### ProductController
- `index()` - List products with filtering, sorting, search
- `show($slug)` - Display single product with related products

### CategoryController
- `index()` - List all categories
- `show($slug)` - Display category with products

### BrandController
- `index()` - List all brands
- `show($slug)` - Display brand with products

### OfferController
- `index()` - List active and upcoming offers
- `show($slug)` - Display offer details

### HomeController
- `index()` - Display homepage with featured, new, bestseller products

---

## API Routes

All routes use slugs for better SEO:

```php
GET /                           - Home page
GET /products                   - All products (with filters)
GET /product/{slug}             - Product details
GET /categories                 - All categories
GET /category/{slug}            - Category products
GET /brands                     - All brands
GET /brand/{slug}               - Brand products
GET /offers                     - All offers
GET /offer/{slug}               - Offer details
```

---

## Features

✅ Complete product management system
✅ Hierarchical categories (with subcategories)
✅ Brand management
✅ Product attributes and variations
✅ Multiple images per product
✅ Product reviews and ratings
✅ Promotional offers and discounts
✅ Stock management
✅ SEO-friendly URLs (slugs)
✅ Soft deletes for safe data removal
✅ View and sales tracking
✅ Advanced filtering and search
✅ Factories for testing data
✅ Comprehensive seeders

---

## Future Enhancements

Consider adding:
- Shopping cart functionality
- Order management system
- Payment gateway integration
- Wishlist feature
- Customer accounts
- Admin dashboard
- Product comparison
- Advanced search with filters
- Email notifications
- Product recommendations

---

## Notes

- All models use soft deletes to prevent accidental data loss
- Slugs are automatically generated from names
- SKUs are auto-generated if not provided
- Stock status updates automatically based on quantity
- Product ratings recalculate when reviews change
- Factories generate realistic test data
- All relationships are properly indexed for performance

---

## Support

For issues or questions, please check the Laravel documentation at https://laravel.com/docs
