# Quick Start Guide - Multi-Image Products

## Admin Panel Usage

### Step 1: Create a New Product
1. Navigate to: **Admin Panel** → **Products** → **+ Add New Product**

### Step 2: Fill Product Details
- Product Name ✓
- Category ✓
- Brand ✓
- Price ✓
- Stock Quantity ✓

### Step 3: Add Images

#### Main Image (Required)
```
Field: Main Image URL
Example: https://picsum.photos/800/800
```

#### Additional Images (Optional)
```
Field: Additional Images
Format: One URL per line

Example:
https://picsum.photos/800/801
https://picsum.photos/800/802
https://picsum.photos/800/803
https://picsum.photos/800/804
```

### Step 4: Save
Click **"Create Product"** button

---

## Example: Creating a Laptop Product

### Product Info
- **Name:** Gaming Laptop Pro
- **Category:** Laptops
- **Brand:** Dell
- **Price:** 1299.99
- **Stock:** 50

### Images
**Main Image:**
```
https://picsum.photos/seed/laptop-main/800/800
```

**Additional Images:**
```
https://picsum.photos/seed/laptop-2/800/800
https://picsum.photos/seed/laptop-3/800/800
https://picsum.photos/seed/laptop-4/800/800
https://picsum.photos/seed/laptop-5/800/800
```

**Result:** Product will have 5 total images (1 main + 4 additional)

---

## Editing Existing Products

### When you edit a product:
1. Current main image is displayed with preview
2. Current additional images are shown as thumbnail grid
3. Additional Images textarea is pre-filled with existing URLs
4. You can:
   - Change the main image URL
   - Add new additional image URLs (add new lines)
   - Remove images (delete lines)
   - Reorder images (rearrange lines)

### Important Notes:
- ⚠️ When you save, all old additional images are replaced with new ones
- ✓ Always keep URLs you want to preserve in the textarea
- ✓ URLs must be valid (start with http:// or https://)
- ✓ Each URL must be on its own line

---

## Free Image Services

Use these services for testing:

### Picsum Photos (Recommended)
```
https://picsum.photos/800/800
https://picsum.photos/seed/YOUR_SEED/800/800
```

### Placeholder.co
```
https://placehold.co/800x800
https://placehold.co/800x800/blue/white
```

### Lorem Picsum with Specific IDs
```
https://picsum.photos/id/1/800/800
https://picsum.photos/id/2/800/800
```

---

## Common Patterns

### Desktop Computer (4 images)
```
Main: https://picsum.photos/seed/pc-front/800/800

Additional:
https://picsum.photos/seed/pc-side/800/800
https://picsum.photos/seed/pc-back/800/800
https://picsum.photos/seed/pc-inside/800/800
```

### Monitor (3 images)
```
Main: https://picsum.photos/seed/monitor-1/800/800

Additional:
https://picsum.photos/seed/monitor-2/800/800
https://picsum.photos/seed/monitor-3/800/800
```

### Keyboard (5 images)
```
Main: https://picsum.photos/seed/kb-top/800/800

Additional:
https://picsum.photos/seed/kb-angle/800/800
https://picsum.photos/seed/kb-side/800/800
https://picsum.photos/seed/kb-backlight/800/800
https://picsum.photos/seed/kb-detail/800/800
```

---

## Troubleshooting

### Problem: Images not saving
**Solution:** Ensure each URL:
- Starts with `http://` or `https://`
- Is on its own line (press Enter after each URL)
- Is accessible (try opening in browser)

### Problem: Can't see images in edit mode
**Solution:** 
- Refresh the page
- Check if images were saved (look at products list)
- Clear browser cache

### Problem: Old images still showing
**Solution:**
- Clear application cache: `php artisan cache:clear`
- Clear views cache: `php artisan view:clear`

---

## Quick Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Reset database with sample products (85 products, ~4 images each)
php artisan migrate:fresh --seed

# Count total images
php artisan tinker --execute="echo App\Models\ProductImage::count();"
```

---

## Success Indicators

✅ Product saved successfully
✅ Image count badge appears in products list
✅ Edit page shows all images
✅ Thumbnail grid displays properly

**You're all set! 🎉**
