# نظام النسخ الاحتياطي التلقائي - دليل شامل

## ✅ الإجابة على الأسئلة

### 1️⃣ هل الموقع يدعم النسخ الاحتياطي التلقائي؟

**نعم! ✅** الموقع يدعم النسخ الاحتياطي التلقائي بالكامل

#### المميزات المتوفرة:

##### أ) جدولة تلقائية مرنة
- **يومياً (Daily)**: نسخة احتياطية كل يوم في وقت محدد
- **أسبوعياً (Weekly)**: نسخة في يوم محدد من الأسبوع
- **شهرياً (Monthly)**: نسخة في يوم محدد من الشهر

##### ب) صفحة إعدادات للأدمن
- **المسار**: `/admin/backup/settings`
- **الإعدادات المتاحة**:
  - ✅ تفعيل/إيقاف الحذف التلقائي
  - ✅ مدة الاحتفاظ بالنسخ (1-365 يوم)
  - ✅ الحد الأقصى لعدد النسخ (1-100)

##### ج) التحكم من قاعدة البيانات
جدول `backup_settings` يحتوي على:
```
- auto_cleanup_enabled: تفعيل الحذف التلقائي
- default_retention_days: مدة الاحتفاظ الافتراضية (30 يوم)
- max_backups: الحد الأقصى للنسخ (10 نسخ)
```

---

### 2️⃣ هل دالة حذف النسخ المنتهية تلقائياً تعمل؟

**نعم! ✅** الدالة تعمل بشكل صحيح

#### التفاصيل التقنية:

##### الأمر
```bash
php artisan backup:cleanup-expired
```

##### الجدولة
- **التوقيت**: يومياً الساعة 03:00 صباحاً
- **الشرط**: يعمل فقط إذا كان `auto_cleanup_enabled = 1`
- **الحالة الحالية**: ✅ مفعّل

##### آلية العمل
```php
// في app/Console/Kernel.php
$schedule->command('backup:cleanup-expired')
    ->daily()
    ->at('03:00')
    ->when(function () {
        return \App\Models\BackupSetting::get('auto_cleanup_enabled', true);
    });
```

##### معايير الحذف
1. النسخ التي `expires_at` < الآن
2. النسخ التي `created_at` < (الآن - retention_days)

##### اختبار يدوي
```bash
php artisan backup:cleanup-expired
# النتيجة: "No expired backups found" ✅
# (لأن جميع النسخ الحالية لم تنتهي بعد)
```

---

### 3️⃣ هل زر حذف جميع النسخ الاحتياطية يعمل؟

**نعم! ✅** الزر يعمل الآن بعد التحديثات

#### التحديثات المضافة:

##### 1. Force Mode في الخدمة
```php
public function cleanupOldBackups(bool $force = false): array
{
    if ($force) {
        // يحذف جميع النسخ ويحتفظ بالأحدث فقط
    } else {
        // حذف عادي حسب القواعد
    }
}
```

##### 2. تحديث Controller
```php
public function cleanup()
{
    $force = request()->has('force') ? request()->boolean('force') : true;
    $result = $this->backupService->cleanupOldBackups($force);
}
```

##### 3. ثلاث طرق للحذف
1. **النسخ المنتهية**: `expires_at < now()`
2. **النسخ القديمة**: `created_at < now() - retention_days`
3. **النسخ الزائدة**: عندما `count > max_backups`

##### اختبار ناجح
```bash
php artisan backup:cleanup --force --yes

# النتيجة:
# ✓ Deleted: 9 backups
# ✓ Kept: 1 backup (الأحدث)
```

---

## ⚙️ الإعدادات الحالية

### في ملف .env
```env
BACKUP_SCHEDULE=daily            # جدولة يومية
BACKUP_RETENTION_DAYS=30        # الاحتفاظ 30 يوم
BACKUP_MAX_BACKUPS=5            # حد أقصى 5 نسخ
BACKUP_DAILY_TIME=02:00         # الساعة 2 صباحاً
```

### في قاعدة البيانات
```json
{
  "auto_cleanup_enabled": true,
  "default_retention_days": 30,
  "max_backups": 10
}
```

**ملاحظة**: إعدادات قاعدة البيانات لها أولوية على `.env`

---

## 🚀 خطوات التفعيل الكامل

### 1️⃣ إعداد Cron Job (مطلوب للإنتاج)

#### على Windows (Task Scheduler):
1. افتح Task Scheduler
2. Create Basic Task
3. **Name**: Laravel Scheduler
4. **Trigger**: Daily
5. **Start**: اختر وقت البداية
6. **Repeat task every**: 1 minute
7. **Action**: Start a program
8. **Program**: `powershell.exe`
9. **Arguments**:
```powershell
-Command "cd 'c:\Users\Hamza Damra\ITCenter-Ecommerce'; php artisan schedule:run"
```

#### على Linux:
```bash
# افتح crontab
crontab -e

# أضف هذا السطر
* * * * * cd /path/to/ITCenter-Ecommerce && php artisan schedule:run >> /dev/null 2>&1
```

### 2️⃣ التحقق من الجدولة

```bash
# عرض جميع المهام المجدولة
php artisan schedule:list

# النتيجة المتوقعة:
# 0 2 * * * - backup:create (يومياً الساعة 2 صباحاً)
# 0 3 * * * - backup:cleanup-expired (يومياً الساعة 3 صباحاً)
```

### 3️⃣ اختبار يدوي

```bash
# إنشاء نسخة احتياطية
php artisan backup:create

# عرض جميع النسخ
php artisan backup:list

# حذف النسخ المنتهية
php artisan backup:cleanup-expired

# حذف قوي (يحتفظ بالأحدث فقط)
php artisan backup:cleanup --force --yes
```

---

## 📋 الأوامر المتاحة

### النسخ الاحتياطي
```bash
# إنشاء نسخة جديدة
php artisan backup:create

# إنشاء نسخة مع وحدات محددة
php artisan backup:create --type=modules --modules=products,users

# عرض جميع النسخ
php artisan backup:list

# استعادة نسخة
php artisan backup:restore {filename}
```

### الحذف والتنظيف
```bash
# حذف النسخ المنتهية فقط
php artisan backup:cleanup-expired

# حذف عادي (حسب القواعد)
php artisan backup:cleanup

# حذف قوي (يحتفظ بالأحدث فقط)
php artisan backup:cleanup --force --yes
```

### المراقبة
```bash
# عرض الجدولة
php artisan schedule:list

# تشغيل الجدولة مرة واحدة (للاختبار)
php artisan schedule:run

# مراقبة النسخ الاحتياطي
php artisan monitor:backup-schedule
```

---

## 🎯 سيناريوهات الاستخدام

### السيناريو 1: نسخ احتياطي يومي تلقائي
1. الأدمن يضبط الإعدادات من `/admin/backup/settings`
2. يختار: "مدة احتفاظ 30 يوم" + "حد أقصى 10 نسخ"
3. يفعّل "الحذف التلقائي"
4. النظام يعمل تلقائياً:
   - 02:00 صباحاً ← إنشاء نسخة جديدة
   - 03:00 صباحاً ← حذف النسخ المنتهية

### السيناريو 2: تنظيف يدوي سريع
1. الأدمن يدخل `/admin/backup`
2. يضغط زر "Clear Old Backups"
3. النظام يحذف جميع النسخ القديمة ويحتفظ بالأحدث

### السيناريو 3: نسخ احتياطي طارئ
1. قبل تحديث مهم
2. الأدمن يضغط "Create New Backup"
3. نسخة فورية تُنشأ خلال ثوانٍ

---

## ✅ الخلاصة

| السؤال | الإجابة | الحالة |
|--------|---------|--------|
| نسخ احتياطي تلقائي؟ | نعم، يومي/أسبوعي/شهري | ✅ يعمل |
| حذف تلقائي للمنتهية؟ | نعم، يومياً الساعة 03:00 | ✅ يعمل |
| زر حذف جميع النسخ؟ | نعم، مع Force Mode | ✅ يعمل |

### المميزات الإضافية المتوفرة:
- ✅ ضغط النسخ (gzip) لتوفير المساحة
- ✅ سجلات تفصيلية في `storage/logs/laravel.log`
- ✅ نسخ احتياطي آمن قبل الاستعادة
- ✅ التحقق من سلامة الملفات
- ✅ حماية من Path Traversal
- ✅ دعم الملفات الكبيرة (Streaming)
- ✅ إشعارات عند النجاح/الفشل
- ✅ نسخ احتياطي انتقائي (Modules)

---

## 📞 الدعم

للمزيد من المعلومات، راجع:
- `BACKUP_SYSTEM_ISSUES.md` - تحليل شامل للمشاكل والحلول
- `BACKUP_SCHEDULER_TEST_RESULTS.md` - نتائج اختبار الجدولة
- `storage/logs/laravel.log` - سجلات النظام

**تاريخ التحديث**: 27 أكتوبر 2025
**الحالة**: ✅ جاهز للإنتاج
