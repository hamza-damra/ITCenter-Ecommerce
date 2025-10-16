# دليل سريع - تعدد اللغات

## 🚀 البدء السريع

### 1. استخدام الترجمة في Views

```php
<!-- بسيط -->
{{ __t('messages.home') }}

<!-- مع متغيرات -->
{{ __t('messages.welcome', ['name' => 'أحمد']) }}

<!-- في Attributes -->
<input placeholder="{{ __t('messages.search') }}">
```

### 2. دوال مساعدة شائعة

```php
current_locale()         // 'ar' أو 'en'
is_rtl()                 // true إذا كانت اللغة RTL
locale_direction()       // 'rtl' أو 'ltr'
locale_name('ar')        // 'العربية'
switch_locale_url('en')  // رابط التبديل
```

### 3. دعم RTL

```php
<html dir="{{ locale_direction() }}">
    
<!-- الأيقونات الديناميكية -->
<i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>

<!-- التصميم الشرطي -->
@if(is_rtl())
    <link href="...cairo-font.css" rel="stylesheet">
@endif
```

---

## 📂 الملفات الأساسية

| الملف | الوظيفة |
|------|---------|
| `lang/ar/messages.php` | الترجمات العربية |
| `lang/en/messages.php` | الترجمات الإنجليزية |
| `app/Helpers/LocaleHelper.php` | دوال مساعدة |
| `app/Http/Middleware/SetLocale.php` | إدارة اللغة |

---

## ➕ إضافة ترجمة جديدة

### في ملف الترجمة (`lang/ar/messages.php`)

```php
return [
    'my_new_key' => 'الترجمة العربية',
];
```

### في View

```php
{{ __t('messages.my_new_key') }}
```

---

## 🌐 تغيير اللغة

### عبر URL

```
/lang/ar  → العربية
/lang/en  → الإنجليزية
```

### في Code

```php
<a href="{{ switch_locale_url('ar') }}">العربية</a>
```

---

## 🎨 أمثلة سريعة

### Navigation

```php
<a href="{{ route('home') }}">{{ __t('messages.home') }}</a>
<a href="{{ route('products') }}">{{ __t('messages.products') }}</a>
```

### Button

```php
<button>{{ __t('messages.add_to_cart') }}</button>
<button>{{ __t('messages.checkout') }}</button>
```

### Form

```php
<input placeholder="{{ __t('messages.email_address') }}">
<button>{{ __t('messages.subscribe') }}</button>
```

---

## 🔧 الإعدادات الأساسية

### تغيير اللغة الافتراضية

في `.env`:
```env
APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
```

أو في `config/app.php`:
```php
'locale' => 'ar',
```

---

## ✅ Checklist

- [x] ملفات الترجمة موجودة
- [x] Middleware مُفعّل
- [x] Helper محمّل في composer.json
- [x] مبدل اللغة في Navbar
- [x] دعم RTL في HTML
- [x] Routes تبديل اللغة

---

## 📝 مفاتيح شائعة

```php
__t('messages.home')           // الرئيسية
__t('messages.products')       // المنتجات
__t('messages.categories')     // الفئات
__t('messages.add_to_cart')    // أضف إلى السلة
__t('messages.search')         // بحث
__t('messages.shop_now')       // تسوق الآن
__t('messages.view_more')      // عرض المزيد
__t('messages.loading')        // جاري التحميل...
```

---

✨ **للمزيد من التفاصيل، راجع:** `MULTI_LANGUAGE_DOCUMENTATION.md`
