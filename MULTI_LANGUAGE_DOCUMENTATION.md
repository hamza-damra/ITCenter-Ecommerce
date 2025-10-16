# دليل تعدد اللغات (Multi-Language Support) - ITCenter Ecommerce

## 📋 نظرة عامة

تم تطبيق نظام تعدد اللغات الكامل في Laravel باستخدام **أفضل الممارسات (Best Practices)** لدعم اللغتين العربية والإنجليزية مع إمكانية التوسع لإضافة لغات أخرى بسهولة.

---

## 🏗️ البنية التحتية

### 1. ملفات الترجمة
```
lang/
├── ar/
│   └── messages.php    # الترجمات العربية
└── en/
    └── messages.php    # الترجمات الإنجليزية
```

### 2. الملفات المعدلة/المضافة

#### الملفات الجديدة:
- `app/Http/Middleware/SetLocale.php` - Middleware لإدارة اللغة
- `app/Helpers/LocaleHelper.php` - دوال مساعدة للترجمة
- `lang/ar/messages.php` - ملف الترجمة العربية
- `lang/en/messages.php` - ملف الترجمة الإنجليزية

#### الملفات المعدلة:
- `config/app.php` - إضافة إعدادات اللغات المدعومة
- `bootstrap/app.php` - تسجيل Middleware
- `routes/web.php` - إضافة مسار تبديل اللغة
- `composer.json` - تحميل ملف Helper
- `resources/views/layouts/app.blade.php` - دعم RTL ومبدل اللغة
- `resources/views/home.blade.php` - مثال تطبيق الترجمة

---

## ⚙️ الإعدادات

### config/app.php

```php
'locale' => env('APP_LOCALE', 'ar'), // اللغة الافتراضية: العربية
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
'available_locales' => ['en', 'ar'], // اللغات المدعومة
'locale_names' => [
    'en' => 'English',
    'ar' => 'العربية',
],
```

---

## 🔧 المكونات الرئيسية

### 1. SetLocale Middleware

**المسؤولية:** تحديد واختيار اللغة بناءً على عدة مصادر بترتيب الأولوية:

1. **Parameter من URL** (`?lang=ar`)
2. **Session** (محفوظ من الزيارة السابقة)
3. **Browser Accept-Language Header** (تلقائي من المتصفح)
4. **Default** (من config)

**المميزات:**
- ✅ حفظ اللغة في Session
- ✅ مشاركة اللغة الحالية مع جميع Views
- ✅ دعم الكشف التلقائي من المتصفح

### 2. Helper Functions

#### `__t($key, $replace = [], $locale = null)`
اختصار لدالة `trans()` لتسهيل الترجمة

```php
{{ __t('messages.home') }}
{{ __t('messages.welcome', ['name' => 'أحمد']) }}
```

#### `current_locale()`
الحصول على اللغة الحالية

```php
$currentLang = current_locale(); // 'ar' or 'en'
```

#### `is_rtl()`
التحقق إذا كانت اللغة الحالية RTL

```php
@if(is_rtl())
    <div style="text-align: right;">...</div>
@endif
```

#### `locale_direction()`
الحصول على اتجاه النص

```php
<html dir="{{ locale_direction() }}"> // 'rtl' or 'ltr'
```

#### `available_locales()`
قائمة جميع اللغات المدعومة

```php
@foreach(available_locales() as $locale)
    <option value="{{ $locale }}">{{ locale_name($locale) }}</option>
@endforeach
```

#### `locale_name($locale = null)`
الحصول على اسم اللغة

```php
{{ locale_name('ar') }} // "العربية"
{{ locale_name() }} // اسم اللغة الحالية
```

#### `switch_locale_url($locale)`
رابط تبديل اللغة

```php
<a href="{{ switch_locale_url('ar') }}">العربية</a>
```

---

## 🎨 دعم RTL (Right-to-Left)

### في Layout الرئيسي

```php
<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ locale_direction() }}">
<head>
    @if(is_rtl())
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        body {
            font-family: {{ is_rtl() ? "'Cairo', sans-serif" : "'Segoe UI', sans-serif" }};
            direction: {{ locale_direction() }};
        }
    </style>
</head>
```

### الأيقونات الديناميكية

```php
<i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
```

---

## 🌐 مبدل اللغة في Navbar

تم إضافة قائمة منسدلة أنيقة في الـ Header:

```html
<div class="header-icon dropdown">
    <i class="fas fa-globe"></i>
    <div class="dropdown-menu">
        @foreach(available_locales() as $locale)
            <a href="{{ switch_locale_url($locale) }}"
               class="{{ $locale === current_locale() ? 'active' : '' }}">
                {{ locale_name($locale) }}
            </a>
        @endforeach
    </div>
</div>
```

**المميزات:**
- ✅ تصميم أنيق مع dropdown
- ✅ تحديد اللغة النشطة
- ✅ دعم RTL في موضع القائمة

---

## 📝 استخدام الترجمة في Views

### مثال بسيط

```php
<h1>{{ __t('messages.welcome') }}</h1>
<p>{{ __t('messages.description') }}</p>
```

### مع متغيرات

```php
{{ __t('messages.welcome_user', ['name' => $user->name]) }}
```

في ملف الترجمة:
```php
'welcome_user' => 'مرحباً :name',
```

### في Attributes

```php
<input type="text" placeholder="{{ __t('messages.search') }}">
<button title="{{ __t('messages.add_to_cart') }}">...</button>
```

---

## 🔄 تبديل اللغة

### عبر URL

```
https://yoursite.com/lang/ar  → العربية
https://yoursite.com/lang/en  → English
```

### عبر JavaScript

```javascript
function switchLanguage(locale) {
    window.location.href = '/lang/' + locale;
}
```

---

## 📦 إضافة لغة جديدة

### الخطوة 1: إنشاء ملف الترجمة

```bash
mkdir lang/fr
```

إنشاء `lang/fr/messages.php`:
```php
<?php
return [
    'home' => 'Accueil',
    'products' => 'Produits',
    // ...
];
```

### الخطوة 2: تحديث config/app.php

```php
'available_locales' => ['en', 'ar', 'fr'],
'locale_names' => [
    'en' => 'English',
    'ar' => 'العربية',
    'fr' => 'Français',
],
```

### الخطوة 3: (اختياري) إضافة RTL Support

في `app/Helpers/LocaleHelper.php`:
```php
function is_rtl(): bool
{
    return in_array(current_locale(), ['ar', 'he', 'fa', 'ur']);
}
```

---

## 🎯 أمثلة عملية

### مثال 1: ترجمة Navigation

```php
<nav>
    <a href="{{ route('home') }}">{{ __t('messages.home') }}</a>
    <a href="{{ route('products') }}">{{ __t('messages.products') }}</a>
    <a href="{{ route('about') }}">{{ __t('messages.about') }}</a>
</nav>
```

### مثال 2: ترجمة مع شرط RTL

```php
<div class="product-price" style="text-align: {{ is_rtl() ? 'right' : 'left' }}">
    {{ __t('messages.price') }}: $100
</div>
```

### مثال 3: Pluralization

في `lang/ar/messages.php`:
```php
'products_count' => '{0} لا توجد منتجات|{1} منتج واحد|{2} منتجان|[3,10] :count منتجات|[11,*] :count منتج',
```

استخدام:
```php
{{ trans_choice('messages.products_count', $count) }}
```

---

## 🔍 الاختبار

### 1. اختبار تغيير اللغة

```php
// زيارة الموقع
visit('/');

// النقر على مبدل اللغة
click('.dropdown .fas.fa-globe');

// اختيار العربية
click('a[href*="lang/ar"]');

// التحقق من التغيير
assertSee('الرئيسية');
```

### 2. اختبار RTL

```php
// تغيير إلى العربية
Session::put('locale', 'ar');

// التحقق من direction
$this->assertEquals('rtl', locale_direction());
$this->assertTrue(is_rtl());
```

---

## 📊 ملفات الترجمة المتوفرة

### messages.php يحتوي على:

#### Navigation (7 مفاتيح)
- home, categories, brands, products, offers, about, contact

#### Hero Section (3 مفاتيح)
- hero_title, hero_subtitle, shop_now

#### Categories (4 مفاتيح)
- shop_by_category, view_all, view_more, no_categories

#### Products (11 مفاتيح)
- featured_products, add_to_cart, quick_view, in_stock, out_of_stock...

#### Offers (8 مفاتيح)
- special_offers, hot_deals, expires_in, days, hours...

#### Reviews (5 مفاتيح)
- customer_reviews, write_review, rating...

#### Cart (9 مفاتيح)
- shopping_cart, checkout, subtotal, total...

#### Common (25 مفاتيح)
- save, cancel, delete, edit, view, loading...

**المجموع: 100+ مفتاح ترجمة**

---

## 🚀 التوسع والتخصيص

### إضافة ملفات ترجمة إضافية

يمكنك تنظيم الترجمات في ملفات منفصلة:

```
lang/
├── ar/
│   ├── messages.php
│   ├── products.php
│   ├── auth.php
│   └── validation.php
└── en/
    ├── messages.php
    ├── products.php
    ├── auth.php
    └── validation.php
```

الاستخدام:
```php
{{ __t('products.laptop') }}
{{ __t('auth.login') }}
```

### دعم قاعدة البيانات متعددة اللغات

لدعم المحتوى الديناميكي (من قاعدة البيانات):

```php
// في Model
protected $translatable = ['name', 'description'];

// الاستخدام
$product->translate('name', 'ar'); // الاسم بالعربية
```

---

## ✅ Best Practices المطبقة

1. ✅ **استخدام Laravel Translation الأصلي** - لا حاجة لمكتبات خارجية
2. ✅ **Middleware Pattern** - فصل logic اللغة عن Business Logic
3. ✅ **Helper Functions** - تسهيل الاستخدام في Views
4. ✅ **Session Persistence** - حفظ اختيار المستخدم
5. ✅ **RTL Support** - دعم كامل للغات RTL
6. ✅ **SEO Friendly** - استخدام `lang` و `dir` في HTML
7. ✅ **Browser Detection** - اختيار اللغة تلقائياً من المتصفح
8. ✅ **Scalable Structure** - سهولة إضافة لغات جديدة
9. ✅ **Consistent Naming** - تسمية موحدة للمفاتيح
10. ✅ **Fallback Mechanism** - العودة للغة الافتراضية عند عدم وجود ترجمة

---

## 🆘 استكشاف الأخطاء

### المشكلة: الترجمة لا تعمل

**الحل:**
```bash
composer dump-autoload
php artisan config:clear
php artisan view:clear
```

### المشكلة: اللغة لا تتغير

**الحل:** تأكد من:
1. تشغيل Session في Laravel
2. الـ Middleware مسجل في `bootstrap/app.php`
3. Route `/lang/{locale}` موجود

### المشكلة: RTL لا يعمل بشكل صحيح

**الحل:**
1. تأكد من `dir="{{ locale_direction() }}"` في `<html>`
2. استخدم CSS المناسب للـ RTL
3. استخدم خط عربي مناسب (Cairo, Tajawal, etc.)

---

## 📚 الموارد الإضافية

- [Laravel Localization Documentation](https://laravel.com/docs/localization)
- [RTL CSS Best Practices](https://rtlcss.com/)
- [Arabic Web Fonts](https://fonts.google.com/?subset=arabic)

---

## 🎉 الخلاصة

تم تطبيق نظام تعدد لغات احترافي وقوي يدعم:
- ✅ العربية والإنجليزية افتراضياً
- ✅ دعم كامل لـ RTL
- ✅ تبديل سهل بين اللغات
- ✅ حفظ تفضيلات المستخدم
- ✅ سهولة الإضافة والتوسع
- ✅ Best Practices في Laravel

**الموقع الآن جاهز بالكامل لدعم اللغة العربية بشكل قوي واحترافي! 🚀**

---

تم التطبيق بتاريخ: **16 أكتوبر 2025**
