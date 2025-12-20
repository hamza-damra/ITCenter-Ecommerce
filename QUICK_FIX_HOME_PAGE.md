# حل سريع لمشكلة الصفحة الرئيسية

## المشكلة
البيانات موجودة في قاعدة البيانات لكن لا تظهر في الصفحة الرئيسية (Home Page) فقط.

## الحل السريع

### الطريقة 1: استخدام رابط خاص (الأسرع)
افتح الصفحة الرئيسية مع إضافة `?nocache=1` في نهاية الرابط:

```
http://your-domain/?nocache=1
```

هذا سيجبر الصفحة على إعادة تحميل البيانات من قاعدة البيانات مباشرة بدون استخدام الكاش.

### الطريقة 2: استخدام زر مسح الكاش
1. اذهب إلى: **Admin → Database Backup Management**
2. اضغط على زر **"Clear Frontend Cache"** (أزرق)
3. انتظر رسالة التأكيد
4. اذهب إلى الصفحة الرئيسية وحدّث الصفحة (F5)

### الطريقة 3: استخدام Terminal
```bash
php artisan cache:clear
php artisan view:clear
```

أو استخدم الرابط المباشر:
```
http://your-domain/clear-cache
```

## التحقق من البيانات

للتأكد من أن البيانات موجودة في قاعدة البيانات:

```bash
php artisan tinker
```

ثم:
```php
\App\Models\Product::where('is_active', true)->count();
\App\Models\Product::where('is_active', true)->where('is_featured', true)->count();
\App\Models\Category::where('is_active', true)->count();
\App\Models\Category::where('is_active', true)->whereNull('parent_id')->where('display_mode', 'carousel')->count();
```

## ملاحظات

- الكاش يتم مسحه تلقائياً بعد كل عملية استيراد/استعادة
- إذا لم تظهر البيانات، استخدم `?nocache=1` للتأكد من أن المشكلة في الكاش
- البيانات يجب أن تكون:
  - `is_active = 1` (مطلوب)
  - `is_featured = 1` (للمنتجات المميزة)
  - `display_mode = 'carousel'` (لتصنيفات الكاروسيل)

