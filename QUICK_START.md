# Quick Start Guide - دليل البداية السريع

## الخطوة 1️⃣: ملء البيانات

قم بتشغيل هذا الأمر لملء أقسام الصفحة الرئيسية:

```bash
php artisan home:populate-sections --reset --featured=8 --new=8 --bestseller=8 --sale=8
```

## الخطوة 2️⃣: تشغيل السيرفر

```bash
php artisan serve
```

## الخطوة 3️⃣: زيارة الصفحة الرئيسية

افتح المتصفح وانتقل إلى:
```
http://localhost:8000
```

---

## الأقسام التي ستراها:

✅ **منتجات مميزة** (Featured Products) - 8 منتجات  
✅ **وصل حديثاً** (New Arrivals) - 8 منتجات  
✅ **الأكثر مبيعاً** (Bestsellers) - 8 منتجات ⭐ جديد  
✅ **تخفيضات الآن** (On Sale) - منتجات مع خصومات  

---

## إدارة الأقسام:

### لإضافة المزيد من المنتجات:
```bash
php artisan home:populate-sections --featured=15 --new=12
```

### لإعادة تعيين كل شيء:
```bash
php artisan home:populate-sections --reset
```

### للمساعدة:
```bash
php artisan home:populate-sections --help
```

---

## التحقق من البيانات:

```bash
php artisan tinker
```

```php
// عدد المنتجات في كل قسم
Product::featured()->count();
Product::new()->count();
Product::bestseller()->count();
```

---

## ملاحظات:

⚠️ **مهم:** إذا كان قسم ما فارغاً، لن يظهر في الصفحة الرئيسية (هذا مقصود!)

✨ **نصيحة:** يمكنك تشغيل الأمر في أي وقت لتحديث الأقسام

---

**للمزيد من التفاصيل، راجع:**
- `HOME_UPDATES_SUMMARY.md` - الملخص الكامل
- `HOME_SECTIONS_COMMAND.md` - دليل الأمر المفصل
- `HOME_PAGE_IMPROVEMENTS.md` - شرح التحديثات
