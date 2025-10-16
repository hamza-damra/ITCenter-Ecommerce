# Testing Favorites Feature

## How to Test

1. **Open your browser and navigate to the home page**
   ```
   http://localhost/
   ```

2. **Open Browser Console** (F12 or Right-click > Inspect > Console tab)

3. **Click on a heart button** on any product
   - You should see console logs showing:
     - "Favorite IDs loaded: [...]"
     - "Updating X wishlist buttons"
     - "Product X is in favorites - marking as active"

4. **Check the visual feedback:**
   - Heart icon should change from outline (`far fa-heart`) to filled (`fas fa-heart`)
   - Heart button background should turn red (#ff4757)
   - Favorites count in header should increase

5. **Refresh the page**
   - The heart should remain filled/red for favorited products
   - Console should show the favorite IDs being loaded

6. **Visit the favorites page**
   ```
   http://localhost/favorites
   ```
   - You should see all favorited products
   - Hearts should be filled/red

## Troubleshooting

### If hearts don't fill:
1. Check browser console for errors
2. Verify the product has `data-product-id` attribute:
   - Right-click on heart button > Inspect
   - Check if `<div class="wishlist-btn" data-product-id="X">` exists

### If count doesn't update:
1. Check Network tab in browser console
2. Look for request to `/favorites/ids`
3. Check response contains array of IDs

### Clear favorites for testing:
For Guest Users (Session):
- Clear browser cookies/session
- Or use incognito mode

For Authenticated Users (Database):
- Run: `DELETE FROM favorites WHERE user_id = YOUR_USER_ID;`

## Expected Behavior

| Action | Visual Result |
|--------|---------------|
| Click empty heart | Heart fills with red, background turns red |
| Click filled heart | Heart outline appears, background turns white |
| Refresh page | Hearts maintain their state |
| Visit favorites page | All favorited products shown with filled hearts |

## Files to Check

If issues persist, check these files:

1. **Layout JavaScript**: `resources/views/layouts/app.blade.php`
   - Functions: `updateFavoritesCount()`, `updateWishlistButtonStates()`, `toggleFavorite()`

2. **Home Page Buttons**: `resources/views/home.blade.php`
   - All wishlist buttons should have: `data-product-id="{{ $product->id }}"`

3. **CSS Styling**: Check that `.wishlist-btn.active` styles are defined

4. **Routes**: Check `/favorites/ids` and `/favorites/toggle/{product}` routes work

## Console Commands

Test the API endpoints directly:

```javascript
// Get favorite IDs
fetch('/favorites/ids')
  .then(r => r.json())
  .then(d => console.log('Favorites:', d));

// Check if product 1 is favorited
fetch('/favorites/check/1')
  .then(r => r.json())
  .then(d => console.log('Is Favorite:', d));
```

## Database Check

```sql
-- See all favorites
SELECT * FROM favorites;

-- See favorites for a specific user
SELECT f.*, p.name_en 
FROM favorites f 
JOIN products p ON f.product_id = p.id 
WHERE f.user_id = 1;
```
