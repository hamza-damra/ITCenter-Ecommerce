# Favorites Feature Documentation

## Overview
A complete favorites/wishlist system that allows users (both guests and authenticated) to save products they're interested in. The favorites page matches the home page UI/UX design with the same coloring and styling.

## Features

### 1. **Guest Support**
- Guests can add/remove favorites stored in session
- Favorites persist during the browsing session
- Session-based favorites can be migrated to database upon login (optional future enhancement)

### 2. **Authenticated Users**
- Favorites stored permanently in database
- Accessible across devices and sessions
- Linked to user account

### 3. **Heart Button Functionality**
- Click heart icon on any product to add/remove from favorites
- Visual feedback with filled/outlined heart icon
- Works on: Home page, Products page, Product Detail page, Favorites page
- Real-time updates to favorites count in header

### 4. **Favorites Page**
- Dedicated page showing all favorited products
- Matches home page design with same grid layout
- Empty state with call-to-action when no favorites
- Remove items directly from favorites page
- Click product card to view details

## Database Structure

### favorites table
```sql
- id (primary key)
- user_id (foreign key to users)
- product_id (foreign key to products)
- created_at
- updated_at
- unique constraint on (user_id, product_id)
```

## Files Created/Modified

### New Files
1. **Migration**: `database/migrations/2025_10_16_101605_create_favorites_table.php`
2. **Model**: `app/Models/Favorite.php`
3. **Controller**: `app/Http/Controllers/FavoriteController.php`
4. **View**: `resources/views/favorites.blade.php`

### Modified Files
1. **Routes**: `routes/web.php`
   - GET `/favorites` - View favorites page
   - POST `/favorites/toggle/{product}` - Toggle favorite status
   - GET `/favorites/check/{product}` - Check if product is favorited
   - GET `/favorites/ids` - Get all favorite product IDs

2. **Models**:
   - `app/Models/User.php` - Added favorites relationships
   - `app/Models/Product.php` - Added favorites relationships

3. **Views**:
   - `resources/views/layouts/app.blade.php` - Added CSRF token, favorites link, global JavaScript
   - `resources/views/home.blade.php` - Added data-product-id to wishlist buttons
   - `resources/views/products.blade.php` - Added data-product-id to wishlist buttons
   - `resources/views/product-detail.blade.php` - Added data-product-id and updated wishlist button

4. **Language Files**:
   - `lang/en/messages.php` - Added favorites translations
   - `lang/ar/messages.php` - Added favorites translations (Arabic)

## API Endpoints

### Toggle Favorite
```javascript
POST /favorites/toggle/{productId}
Headers: X-CSRF-TOKEN, Content-Type: application/json
Response: {
    success: boolean,
    action: 'added' | 'removed',
    message: string
}
```

### Check Favorite Status
```javascript
GET /favorites/check/{productId}
Response: {
    isFavorite: boolean
}
```

### Get All Favorite IDs
```javascript
GET /favorites/ids
Response: {
    favoriteIds: [1, 5, 10, ...]
}
```

## JavaScript Functions

### Global Functions (in layouts/app.blade.php)

1. **updateFavoritesCount()** - Updates the favorites count badge in header
2. **updateWishlistButtonStates()** - Updates all heart button states on the page
3. **initializeWishlistButtons()** - Adds click handlers to all wishlist buttons
4. **toggleFavorite(productId, button)** - Toggles favorite status for a product
5. **showNotification(message)** - Shows a temporary notification

## Usage

### In Blade Templates
Add the wishlist button to any product card:
```blade
<div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
    <i class="far fa-heart"></i>
</div>
```

### In Controllers
Access user's favorites:
```php
// Get all favorite products
$favorites = Auth::user()->favoriteProducts;

// Check if product is favorited
$isFavorited = Auth::user()->favoriteProducts()->where('product_id', $productId)->exists();
```

## Styling
The favorites page uses the same CSS classes and styling as the home page:
- `.product-grid` - Grid layout for products
- `.product-card` - Individual product cards
- `.wishlist-btn` - Heart button styling
- `.product-badge` - NEW/SALE/HOT badges
- Same color scheme: #e69270ff (primary), #d07e5eff (hover)

## Translations

### English
- favorites: "Favorites"
- my_favorites: "My Favorites"
- no_favorites: "Your favorites list is empty"
- no_favorites_description: "Start adding products you love to easily find them later!"
- start_shopping: "Start Shopping"

### Arabic
- favorites: "المفضلة"
- my_favorites: "مفضلاتي"
- no_favorites: "قائمة المفضلة فارغة"
- no_favorites_description: "ابدأ بإضافة المنتجات التي تحبها لتجدها بسهولة لاحقاً!"
- start_shopping: "ابدأ التسوق"

## Future Enhancements
1. Migrate session favorites to database upon user login
2. Email notifications for price drops on favorited items
3. Share favorites list with others
4. Add favorites to collections/categories
5. Export favorites list
6. Favorites analytics (most favorited products)

## Testing
Run the migration:
```bash
php artisan migrate
```

Visit the favorites page:
```
http://localhost/favorites
```

## Notes
- Heart button click prevents card click event propagation
- Favorites count updates automatically after each action
- Smooth animations for adding/removing favorites
- RTL support included for Arabic interface
- Works seamlessly for both guest and authenticated users
