# تقرير مشاكل الأداء - ITCenter E-commerce
# Performance Issues Report

**التاريخ:** 2026-02-09  
**الإطار:** Laravel 12 + Vite + MySQL  

---

## ملخص تنفيذي

تم تحليل الكود بالكامل وتم العثور على **18 مشكلة أداء** مصنفة حسب الخطورة:
- 🔴 **حرجة (Critical):** 5 مشاكل
- 🟠 **مهمة (Major):** 7 مشاكل  
- 🟡 **متوسطة (Medium):** 6 مشاكل

---

## 🔴 المشاكل الحرجة (Critical)

### 1. ملف Layout عملاق - 2964 سطر من CSS/JS/HTML مضمّن (Inline)
**الملف:** `resources/views/layouts/app.blade.php`  
**المشكلة:** الملف يحتوي على ~1676 سطر CSS مضمّن داخل `<style>` و ~900 سطر JavaScript مضمّن داخل `<script>`. هذا يعني:
- **لا يمكن للمتصفح تخزينها (No Browser Caching):** كل زيارة صفحة تعيد تحميل كل CSS/JS
- **حجم HTML ضخم:** كل response يحمل ~100KB+ من CSS/JS إضافي
- **يمنع Render-Blocking:** المتصفح لا يستطيع عرض الصفحة حتى يعالج كل هذا
- **لا يمكن ضغطها بشكل فعال عبر CDN**

**الحل:** نقل CSS إلى ملفات خارجية (`public/css/layout.css`) ونقل JS إلى ملفات خارجية (`public/js/layout.js`) أو دمجها مع Vite pipeline.

---

### 2. استعلامات قاعدة بيانات داخل Blade Template (View Composer + Blade)
**الملفات:** `resources/views/layouts/app.blade.php` (سطر 1707-1730, 1934-1972) + `app/Providers/AppServiceProvider.php` (سطر 65-101)

**المشكلة:** يوجد **4 استعلامات قاعدة بيانات مباشرة** داخل الـ Layout:
1. `CartItem::where('user_id', ...)->sum('quantity')` — عدد السلة (header)
2. `CartItem::where('session_id', ...)->sum('quantity')` — عدد السلة (mobile nav)  
3. `Favorite::where('user_id', ...)->count()` — عدد المفضلات (header)
4. `Favorite::where('session_id', ...)->count()` — عدد المفضلات (mobile nav)

بالإضافة إلى **View Composer** على `'*'` (كل view!) يُنفّذ استعلام categories:
```php
view()->composer('*', function ($view) {
    $navigationCategories = Category::with(['children' => ...])->...->get();
});
```
**التأثير:** هذا يُنفّذ على **كل view يتم تحميلها** بما فيها components و partials — مما يضاعف الاستعلامات بشكل كبير.

**الحل:**
- استخدام `view()->composer(['layouts.app'], ...)` بدلاً من `'*'`
- تجميع استعلامات Cart/Favorites في View Composer واحد مع caching
- استخدام `Cache::remember()` للـ navigation categories

---

### 3. N+1 Query Problem في ProductFilterService
**الملف:** `app/Services/ProductFilterService.php` (سطر 279-311, 356-393)

**المشكلة:** دوال `getTagFilters()` و `getBrandFilters()` تُنفّذ استعلام `COUNT` **لكل عنصر على حدة**:
```php
// لكل tag يتم تنفيذ استعلام منفصل!
return $query->get()->map(function ($tag) use ($category) {
    $count = Product::active()->whereHas('tags', ...)->count(); // ← استعلام لكل tag!
});

// نفس المشكلة للـ brands
$brands->map(function ($brand) use ($category) {
    $count = Product::active()->where('brand_id', $brand->id)->count(); // ← استعلام لكل brand!
});
```
**التأثير:** إذا كان لديك 20 brand و 10 tags = **30 استعلام إضافي** في كل تحميل لصفحة Products أو Category.

**الحل:** استخدام `withCount` مع eager loading أو استعلام واحد مع `GROUP BY`:
```php
// بدلاً من N+1
$brandCounts = Product::active()
    ->select('brand_id', DB::raw('count(*) as count'))
    ->groupBy('brand_id')
    ->pluck('count', 'brand_id');
```

---

### 4. استعلام `getAttributeFilters()` — N+1 مضاعف
**الملف:** `app/Services/ProductFilterService.php` (سطر 437-484)

**المشكلة:** حلقة متداخلة (nested loop) من الاستعلامات:
```php
foreach ($attributes as $attribute) {           // لكل attribute
    foreach ($attribute->values as $value) {    // لكل value
        $count = Product::active()              // ← استعلام COUNT منفصل!
            ->whereHas('attributeValues', ...)
            ->count();
    }
}
```
**التأثير:** إذا كان هناك 5 attributes × 10 values = **50 استعلام** إضافي!

**الحل:** استعلام واحد مع `JOIN` و `GROUP BY` لجلب كل الأعداد مرة واحدة.

---

### 5. `file_exists()` متعدد في كل صورة منتج — Filesystem I/O مفرط
**الملفات:** `app/Models/Product.php` (سطر 95-141) + `app/Models/Category.php` (سطر 55-95)

**المشكلة:** `getMainImageAttribute()` يُنفّذ **حتى 4 عمليات `file_exists()`** لكل منتج:
```php
if (file_exists(public_path($value))) { ... }      // check 1
if (file_exists(public_path('storage/' . $value))) { ... }  // check 2
if (file_exists(public_path($value))) { ... }       // check 3
// fallback
```
**التأثير:** في صفحة تعرض 12 منتج = **حتى 48 عملية filesystem I/O** لكل request. في الصفحة الرئيسية (8+8+8+8+8 = 40+ منتج) = **160+ عملية I/O**!

**الحل:**
- تخزين المسار الصحيح في قاعدة البيانات بتنسيق موحد
- أو استخدام cache للمسارات التي تم التحقق منها
- أو إزالة التحقق واستخدام fallback على مستوى الـ frontend (CSS/JS)

---

## 🟠 المشاكل المهمة (Major)

### 6. `Schema::hasTable()` داخل Model Accessors
**الملفات:** `app/Models/Product.php` (سطر 631-654) + `app/Models/Category.php` (سطر 226-229)

**المشكلة:** استدعاء `Schema::hasTable()` في كل مرة يتم فيها الوصول إلى الـ accessor:
```php
if (Schema::hasTable('product_spec_values') && Schema::hasTable('spec_fields')) { ... }
if (Schema::hasTable('custom_product_specs')) { ... }
```
**التأثير:** كل استدعاء يُنفّذ استعلام `INFORMATION_SCHEMA` على قاعدة البيانات.

**الحل:** Cache هذه الفحوصات أو نقلها إلى boot-time check مرة واحدة.

---

### 7. `inRandomOrder()` في Related Products
**الملف:** `app/Http/Controllers/ProductController.php` (سطر 188-194)

**المشكلة:**
```php
$relatedProducts = Product::with(['category', 'brand', 'images'])
    ->where('category_id', $product->category_id)
    ->inRandomOrder()  // ← يستخدم ORDER BY RAND()
    ->limit(4)
    ->get();
```
**التأثير:** `ORDER BY RAND()` يقوم بـ **full table scan** ويُولّد رقم عشوائي لكل صف — بطيء جداً مع جداول كبيرة.

**الحل:** استخدام offset-based random أو cache مجموعة من المنتجات المتعلقة.

---

### 8. Font Awesome كاملة محمّلة من CDN
**الملف:** `resources/views/layouts/app.blade.php` (سطر 9)

**المشكلة:**
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```
**التأثير:** يتم تحميل **كل** أيقونات Font Awesome (~100KB CSS + ~300KB fonts) بينما الموقع يستخدم فقط ~20-30 أيقونة.

**الحل:**
- استخدام Font Awesome subset (فقط الأيقونات المستخدمة)
- أو التحويل إلى Lucide/Heroicons المضمنة مع Vite
- أو استخدام `@fortawesome/fontawesome-svg-core` مع tree-shaking

---

### 9. Google Fonts `@import` داخل CSS — Render Blocking
**الملف:** `resources/views/home.blade.php` (سطر 10)

**المشكلة:**
```css
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
```
**التأثير:** `@import` داخل CSS يمنع التحميل المتوازي ويُنشئ **render-blocking chain**: HTML → CSS → @import → Font files. هذا يزيد First Contentful Paint بشكل كبير.

**الحل:** نقل تحميل الخط إلى `<link>` في `<head>` مع `rel="preload"` أو `font-display: swap`.

---

### 10. عدم استخدام Lazy Loading للصور
**المشكلة:** لا يوجد `loading="lazy"` على الصور في أي من الـ views.

**التأثير:** كل الصور (المنتجات، البانرات، الماركات) تُحمّل فوراً مع الصفحة حتى لو لم تكن مرئية.

**الحل:** إضافة `loading="lazy"` لكل الصور ما عدا above-the-fold.

---

### 11. تكرار استعلام Cart/Favorites — عدم إعادة استخدام البيانات
**المشكلة:** نفس البيانات (cart count, favorites count) يتم جلبها عدة مرات:
- مرة في الـ Layout Blade (header)
- مرة في mobile nav
- مرة في JavaScript عبر `fetch('/cart/products')` عند `DOMContentLoaded`
- مرة في JavaScript عبر `fetch('/cart/count')` و `fetch('/favorites/count')`

**التأثير:** 4-6 استعلامات إضافية لنفس البيانات في كل تحميل صفحة.

**الحل:** جلب البيانات مرة واحدة server-side وتمريرها كـ JSON في الـ HTML، ثم قراءتها في JavaScript.

---

### 12. Category `descendants()` — Recursive N+1
**الملف:** `app/Models/Category.php` (سطر 179-189)

**المشكلة:**
```php
public function descendants(): Collection
{
    $descendants = collect();
    foreach ($this->children as $child) {      // ← lazy load children
        $descendants->push($child);
        $descendants = $descendants->merge($child->descendants()); // ← recursive!
    }
    return $descendants;
}
```
**التأثير:** كل مستوى من التصنيفات يُنفّذ استعلام جديد. مع 3 مستويات = 1 + N + N*M استعلامات.

**الحل:** تحميل كل التصنيفات مرة واحدة وبناء الشجرة في الذاكرة، أو استخدام Nested Set / Closure Table pattern.

---

## 🟡 المشاكل المتوسطة (Medium)

### 13. Search بـ LIKE على 11 عمود بدون Full-Text Index
**الملف:** `app/Http/Controllers/ProductController.php` (سطر 76-103)

**المشكلة:** البحث يستخدم `LIKE '%term%'` على 11 عمود:
```php
$subQ->where('name_en', 'like', $searchTerm)
    ->orWhere('name_ar', 'like', $searchTerm)
    ->orWhere('description_en', 'like', $searchTerm)
    // ... 8 أعمدة أخرى
```
**التأثير:** `LIKE '%...'` لا يستفيد من الفهارس (indexes) ويتطلب full table scan.

**الحل:** إضافة `FULLTEXT INDEX` على أعمدة البحث واستخدام `MATCH...AGAINST` أو استخدام Laravel Scout مع Meilisearch/Algolia.

---

### 14. الصفحة الرئيسية تُنفّذ 12+ استعلام حتى مع Cache
**الملف:** `app/Http/Controllers/HomeController.php` (سطر 50-196)

**المشكلة:** عند انتهاء صلاحية الـ cache (كل 30 دقيقة) أو بعد أي تعديل (بسبب Observer)، يتم تنفيذ **12 استعلام متتابع** دفعة واحدة:
- featuredProducts, newProducts, bestsellerProducts, onSaleProducts, specialDiscounts
- categories, navCategories, featuredBrands, activeOffers, promotionalOffers
- specialOfferProducts, giftIdeas, banners, promotionalAds

**التأثير:** cold cache يسبب spike في وقت الاستجابة.

**الحل:** 
- تجزئة الـ cache لكل قسم على حدة (بدلاً من cache واحد لكل شيء)
- استخدام cache warming عبر scheduled job
- استخدام stale-while-revalidate pattern

---

### 15. Session Encryption مُفعّل — عبء إضافي
**الملف:** `config/session.php` (سطر 50)

**المشكلة:** `'encrypt' => env('SESSION_ENCRYPT', true)` — كل request يُشفّر ويفك تشفير الـ session.

**التأثير:** ~1-5ms إضافية لكل request حسب حجم الـ session.

**الحل:** تقييم ما إذا كان التشفير ضرورياً. إذا كان session driver هو file أو database فالتشفير غير ضروري في معظم الحالات.

---

### 16. عدم استخدام Redis/Memcached للـ Cache والـ Session
**الملفات:** `config/cache.php`, `config/session.php`

**المشكلة:** الـ Cache والـ Session يستخدمان `file` driver كقيمة افتراضية.

**التأثير:** File-based caching يتضمن filesystem I/O لكل عملية cache read/write وهو أبطأ بكثير من Redis.

**الحل:** استخدام Redis كـ cache و session driver في بيئة الإنتاج.

---

### 17. JavaScript Scroll Event بدون Throttle
**الملف:** `resources/views/layouts/app.blade.php` (سطر 2075-2084)

**المشكلة:**
```javascript
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    // ...
});
```
**التأثير:** يتم تنفيذ الـ handler عشرات المرات في الثانية أثناء التمرير مما يؤثر على smoothness.

**الحل:** استخدام `requestAnimationFrame` أو throttle function.

---

### 18. CSS/JS ملفات منفصلة غير مجمّعة مع Vite
**الملفات:** `public/css/horizontal-scroller.css`, `public/css/search-autocomplete.css`, `public/js/horizontal-scroller.js`, `public/js/search-autocomplete.js`

**المشكلة:** هذه الملفات محمّلة كـ static assets خارج Vite pipeline:
```html
<link rel="stylesheet" href="{{ asset('css/horizontal-scroller.css') }}">
<script src="{{ asset('js/horizontal-scroller.js') }}"></script>
```
**التأثير:** 
- لا يتم minification أو tree-shaking
- لا يوجد content hashing (مشاكل cache busting)
- 4 HTTP requests إضافية

**الحل:** دمج هذه الملفات مع Vite build pipeline.

---

## ملخص الأولويات

| الأولوية | المشكلة | التأثير المتوقع بعد الإصلاح |
|----------|---------|----------------------------|
| 🔴 1 | N+1 في ProductFilterService | تقليل 30-80 استعلام/صفحة |
| 🔴 2 | View Composer على `'*'` | تقليل استعلامات مضاعفة |
| 🔴 3 | file_exists() المتعدد | تقليل 50-160 I/O/صفحة |
| 🔴 4 | Layout عملاق inline | تحسين browser caching |
| 🔴 5 | Attribute Filters N+1 | تقليل 50+ استعلام |
| 🟠 6 | Schema::hasTable() | إزالة استعلامات INFORMATION_SCHEMA |
| 🟠 7 | ORDER BY RAND() | تحسين product detail page |
| 🟠 8 | Font Awesome كاملة | تقليل ~400KB في أول تحميل |
| 🟠 9 | @import Google Fonts | تحسين FCP بـ 200-500ms |
| 🟠 10 | Lazy Loading للصور | تقليل initial page load |
| 🟠 11 | تكرار Cart/Favorites queries | تقليل 4-6 استعلامات/صفحة |
| 🟠 12 | Recursive descendants() | تقليل N+1 في categories |
| 🟡 13 | LIKE search بدون FULLTEXT | تحسين أداء البحث 10x+ |
| 🟡 14 | 12 استعلام cold cache | تقليل cache miss impact |
| 🟡 15 | Session Encryption | تقليل 1-5ms/request |
| 🟡 16 | File-based Cache | تحسين 5-10x مع Redis |
| 🟡 17 | Scroll بدون Throttle | تحسين scroll smoothness |
| 🟡 18 | Assets خارج Vite | تقليل HTTP requests + caching |

---

## التقدير الإجمالي

**قبل الإصلاح (تقدير):**
- الصفحة الرئيسية: ~30-50 استعلام قاعدة بيانات + ~200 filesystem I/O
- صفحة المنتجات: ~50-100 استعلام قاعدة بيانات
- حجم HTML: ~150-200KB (بسبب inline CSS/JS)

**بعد الإصلاح (تقدير):**
- الصفحة الرئيسية: ~5-10 استعلامات (مع cache) + 0 filesystem I/O
- صفحة المنتجات: ~10-15 استعلام
- حجم HTML: ~30-50KB + ملفات مخزنة في المتصفح
