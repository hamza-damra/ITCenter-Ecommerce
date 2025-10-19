# Admin Orders Page - Arabic Language Support

## Summary
The admin orders page (`/admin/orders`) has been successfully updated to support Arabic (and Hebrew) language translations, following the project's multi-language pattern.

## Changes Made

### 1. Translation Files Updated

#### Arabic (`lang/ar/messages.php`)
Added the following translation keys:
- `orders_management` - إدارة الطلبات
- `manage_track_orders` - إدارة وتتبع طلبات العملاء
- `total_orders` - إجمالي الطلبات
- `pending` - قيد الانتظار
- `processing` - قيد المعالجة
- `shipped` - تم الشحن
- `delivered` - تم التوصيل
- `cancelled` - ملغي
- `total_revenue` - إجمالي الإيرادات
- `search_orders` - البحث
- `search_placeholder` - رقم الطلب، اسم العميل، البريد الإلكتروني...
- `all_statuses` - جميع الحالات
- `export_csv` - تصدير CSV
- `order_number` - رقم الطلب
- `customer` - العميل
- `delete_order_confirm` - هل أنت متأكد من حذف هذا الطلب؟
- `selected` - محدد
- `select_status` - اختر الحالة
- `update_selected` - تحديث المحدد
- `no_orders_found` - لا توجد طلبات
- `no_orders_match_filters` - لا توجد طلبات تطابق الفلاتر الحالية
- Status keys: `pending_status`, `processing_status`, `shipped_status`, `delivered_status`, `cancelled_status`
- Payment keys: `paid`, `failed`, `refunded`
- Filter keys: `date_from`, `date_to`, `all`

#### English (`lang/en/messages.php`)
Added corresponding English translations for all keys.

#### Hebrew (`lang/he/messages.php`)
Added corresponding Hebrew translations for all keys.

### 2. View File Updated (`resources/views/admin/orders/index.blade.php`)

All hardcoded English text has been replaced with the `__t()` translation helper:

**Before:**
```blade
<h1>Orders Management</h1>
<p>Manage and track customer orders</p>
```

**After:**
```blade
<h1>{{ __t('orders_management') }}</h1>
<p>{{ __t('manage_track_orders') }}</p>
```

#### Sections Updated:
1. **Page Title** - Uses translation in section directive
2. **Header** - Title and subtitle
3. **Statistics Cards** - All stat labels (Total Orders, Pending, Processing, etc.)
4. **Filter Form** - All labels, placeholders, and button texts
5. **Table Headers** - Column names
6. **Table Content** - Status badges, action tooltips
7. **Bulk Actions** - Selection counter and button texts
8. **Empty State** - No results message
9. **JavaScript** - Dynamic selected counter text

### 3. Status Badge Translations

Order statuses are dynamically translated using a pattern:
```blade
{{ __t($order->status . '_status') }}
```

This converts:
- `pending` → `pending_status` → "قيد الانتظار"
- `processing` → `processing_status` → "قيد المعالجة"
- `shipped` → `shipped_status` → "تم الشحن"
- `delivered` → `delivered_status` → "تم التوصيل"
- `cancelled` → `cancelled_status` → "ملغي"

Payment statuses use direct keys:
- `paid` → "مدفوع"
- `failed` → "فشل"
- `refunded` → "تم الاسترداد"
- `pending` → "قيد الانتظار"

## How It Works

The page now automatically displays in the user's selected language:

1. **Arabic (ar)** - Default locale, RTL layout
2. **English (en)** - LTR layout
3. **Hebrew (he)** - RTL layout

The locale is determined by:
1. URL parameter (`?lang=ar`)
2. Session value
3. Browser preference
4. Default (Arabic)

## Testing

To test the translations:

1. **View in Arabic** (default):
   ```
   http://127.0.0.1:8000/admin/orders
   ```

2. **Switch to English**:
   ```
   http://127.0.0.1:8000/admin/orders?lang=en
   ```

3. **Switch to Hebrew**:
   ```
   http://127.0.0.1:8000/admin/orders?lang=he
   ```

## Architecture Notes

### Translation Helper
Uses the custom `__t()` helper function defined in `app/Helpers/LocaleHelper.php`:
```php
function __t($key, $replace = [], $locale = null) {
    return __('messages.' . $key, $replace, $locale);
}
```

### Reusing Existing Keys
Several keys were already defined in the translation files and are now reused:
- `filter`, `status`, `reset`, `items`, `total`, `actions`, `delete`, `date`, `view_details`

This ensures consistency across the application.

### RTL Support
The admin layout automatically applies RTL direction for Arabic and Hebrew locales through the `is_rtl()` helper function.

## Cache Clearing

After updating translations, always clear caches:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Files Modified

1. `lang/ar/messages.php` - Added 30+ admin orders translations
2. `lang/en/messages.php` - Added corresponding English translations
3. `lang/he/messages.php` - Added corresponding Hebrew translations
4. `resources/views/admin/orders/index.blade.php` - Replaced all hardcoded text with `__t()` calls

## Future Enhancements

To add more admin pages with translations, follow this pattern:

1. Add translation keys to all language files (`ar`, `en`, `he`)
2. Replace hardcoded text with `__t('key')` in Blade views
3. Clear caches
4. Test in all supported locales

## Notes

- All translations maintain the same styling and layout
- Icon classes (`fas fa-*`) remain unchanged
- JavaScript functionality is preserved
- Status badge CSS classes remain in English for consistency
- Date formats may need additional localization in the future
