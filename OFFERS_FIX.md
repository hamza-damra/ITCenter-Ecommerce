# إصلاح خطأ Offers - Database Column Fix

## ❌ المشكلة

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'title_en' in 'field list'
```

كان الـ HomeController يحاول جلب أعمدة غير موجودة في جدول `offers`:
- ❌ `title_en`, `title_ar`, `title_he`
- ❌ `discount_percentage`

## ✅ الحل

تم تصحيح الأعمدة في `HomeController` لتطابق بنية الجدول الفعلية:

### قبل الإصلاح:
```php
$activeOffers = Offer::select('id', 'title_en', 'title_ar', 'title_he', 'description_en', 'description_ar', 'description_he', 'discount_percentage', 'start_date', 'end_date')
    ->active()
    ->limit(3)
    ->get();
```

### بعد الإصلاح:
```php
$activeOffers = Offer::select('id', 'name_en', 'name_ar', 'slug', 'description_en', 'description_ar', 'discount_type', 'discount_value', 'start_date', 'end_date', 'banner_image')
    ->active()
    ->limit(3)
    ->get();
```

## 📋 البنية الفعلية لجدول Offers

### الأعمدة الموجودة:
- ✅ `name_en` - الاسم بالإنجليزية
- ✅ `name_ar` - الاسم بالعربية
- ✅ `slug` - الرابط الصديق للـ SEO
- ✅ `description_en` - الوصف بالإنجليزية
- ✅ `description_ar` - الوصف بالعربية
- ✅ `discount_type` - نوع الخصم (percentage/fixed)
- ✅ `discount_value` - قيمة الخصم
- ✅ `min_purchase_amount` - الحد الأدنى للشراء
- ✅ `max_uses` - الحد الأقصى للاستخدام
- ✅ `uses_count` - عدد مرات الاستخدام
- ✅ `start_date` - تاريخ البدء
- ✅ `end_date` - تاريخ الانتهاء
- ✅ `is_active` - حالة التفعيل
- ✅ `banner_image` - صورة البانر

### ملاحظة مهمة:
⚠️ الجدول لا يحتوي على دعم للغة العبرية (`name_he`, `description_he`)

## 🔧 الملف المعدل

**app/Http/Controllers/HomeController.php** - السطر 66

تم تحديث استعلام الـ Offers ليطابق بنية الجدول الفعلية.

## ✅ التحقق

بعد تشغيل:
```bash
php artisan optimize:clear
```

الصفحة الرئيسية تعمل الآن بدون أخطاء! ✨

---

**تاريخ الإصلاح:** 20 أكتوبر 2025
