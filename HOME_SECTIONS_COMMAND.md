# كيفية إدارة أقسام الصفحة الرئيسية - Home Sections Management

## الأمر الرئيسي - Main Command

```bash
php artisan home:populate-sections
```

## الخيارات المتاحة - Available Options

### 1. إعادة تعيين جميع العلامات ثم ملء البيانات
```bash
php artisan home:populate-sections --reset
```
هذا سيمسح جميع العلامات (`is_featured`, `is_new`, `is_bestseller`) ثم يملأ الأقسام من جديد.

### 2. تحديد عدد المنتجات في كل قسم
```bash
php artisan home:populate-sections --featured=15 --new=12 --bestseller=10 --sale=8
```

- `--featured=X` : عدد المنتجات المميزة (افتراضي: 10)
- `--new=X` : عدد المنتجات الجديدة (افتراضي: 10)
- `--bestseller=X` : عدد المنتجات الأكثر مبيعاً (افتراضي: 10)
- `--sale=X` : عدد المنتجات في التخفيضات (افتراضي: 10)

## أمثلة عملية - Practical Examples

### مثال 1: إعداد أولي سريع
```bash
# مسح كل شيء وملء كل قسم بـ 8 منتجات
php artisan home:populate-sections --reset --featured=8 --new=8 --bestseller=8 --sale=8
```

### مثال 2: إضافة منتجات جديدة فقط
```bash
# إضافة 5 منتجات جديدة بدون مسح البيانات الموجودة
php artisan home:populate-sections --new=5
```

### مثال 3: تحديث الأكثر مبيعاً
```bash
# إعادة حساب الأكثر مبيعاً بناءً على الطلبات
php artisan home:populate-sections --reset --bestseller=15
```

## كيف يعمل الأمر - How It Works

### 1. المنتجات المميزة (Featured)
- يختار أول X منتج نشط (`is_active = true`)
- يضع علامة `is_featured = true`

### 2. وصل حديثاً (New Arrivals)
- يختار آخر X منتج تم إنشاؤه (حسب `created_at`)
- يضع علامة `is_new = true`

### 3. الأكثر مبيعاً (Bestsellers)
- يحسب مجموع المبيعات من جدول `order_items`
- يختار المنتجات الأعلى مبيعاً
- إذا لم توجد بيانات مبيعات، يختار منتجات عشوائية
- يضع علامة `is_bestseller = true`

### 4. التخفيضات (On Sale)
- يختار X منتج بدون سعر تخفيض
- يطبق تخفيض 20% على السعر الأصلي
- يحفظ السعر في حقل `sale_price`

## الناتج المتوقع - Expected Output

```
🚀 Starting to populate home page sections...

📌 Setting 10 featured products...
   ✓ 10 products marked as featured
🆕 Setting 10 new arrival products...
   ✓ 10 products marked as new arrivals
🏆 Setting 10 bestseller products...
   ✓ 10 products marked as bestsellers
🏷️  Setting 10 products on sale...
   ✓ 10 products set on sale (20% discount)

✨ Home page sections populated successfully!

📊 Summary:

┌─────────────────────────┬───────────────┐
│ Section                 │ Product Count │
├─────────────────────────┼───────────────┤
│ 📌 Featured Products    │ 10            │
│ 🆕 New Arrivals         │ 10            │
│ 🏆 Bestsellers          │ 10            │
│ 🏷️  On Sale             │ 10            │
└─────────────────────────┴───────────────┘

💡 Tip: Visit the home page to see the changes!
```

## التحقق من النتائج - Verify Results

### عبر Tinker
```bash
php artisan tinker
```

```php
// عدد المنتجات المميزة
Product::featured()->count();

// عدد المنتجات الجديدة
Product::new()->count();

// عدد الأكثر مبيعاً
Product::bestseller()->count();

// عدد المنتجات في التخفيضات
Product::whereNotNull('sale_price')->where('sale_price', '<', DB::raw('price'))->count();
```

### عبر SQL
```sql
SELECT 
    'Featured' as section,
    COUNT(*) as count
FROM products 
WHERE is_featured = 1 AND is_active = 1
UNION ALL
SELECT 'New Arrivals', COUNT(*) FROM products WHERE is_new = 1 AND is_active = 1
UNION ALL
SELECT 'Bestsellers', COUNT(*) FROM products WHERE is_bestseller = 1 AND is_active = 1
UNION ALL
SELECT 'On Sale', COUNT(*) 
FROM products 
WHERE is_active = 1 AND sale_price IS NOT NULL AND sale_price < price;
```

## استكشاف الأخطاء - Troubleshooting

### مشكلة: الأمر لا يجد منتجات
```bash
# تأكد من وجود منتجات نشطة
php artisan tinker
>>> Product::where('is_active', 1)->count()
```

### مشكلة: لا توجد بيانات مبيعات للأكثر مبيعاً
```
⚠ No sales data found, selecting random products
```
هذا طبيعي إذا لم تكن هناك طلبات مكتملة. سيختار الأمر منتجات عشوائية.

### مشكلة: الصفحة الرئيسية لا تعرض الأقسام
1. تأكد من تشغيل الأمر بنجاح
2. امسح الـ cache:
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```
3. تأكد من وجود 8+ منتجات في كل قسم

## التكامل مع Seeder

يمكنك إضافة هذا الأمر إلى الـ Seeder:

```php
// في database/seeders/DatabaseSeeder.php
public function run()
{
    // ... seeders أخرى
    
    // ملء أقسام الصفحة الرئيسية
    $this->call(HomeProductsSeeder::class);
}
```

```php
// إنشاء database/seeders/HomeProductsSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class HomeProductsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Populating home sections...');
        
        Artisan::call('home:populate-sections', [
            '--reset' => true,
            '--featured' => 10,
            '--new' => 10,
            '--bestseller' => 10,
            '--sale' => 10,
        ]);
        
        $this->command->info(Artisan::output());
    }
}
```

## الجدولة التلقائية - Scheduled Tasks

يمكنك جدولة هذا الأمر ليعمل بشكل دوري:

```php
// في app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // تحديث الأكثر مبيعاً كل يوم
    $schedule->command('home:populate-sections --bestseller=10')
             ->daily()
             ->at('01:00');
             
    // إضافة منتجات جديدة كل أسبوع
    $schedule->command('home:populate-sections --new=10')
             ->weekly()
             ->mondays()
             ->at('02:00');
}
```

---

**نصيحة:** استخدم هذا الأمر بحذر على الـ production، وتأكد من أخذ backup قبل استخدام `--reset`.
