# Railway Deployment Setup

## إعداد قاعدة البيانات على Railway

### 1. إضافة متغيرات البيئة (Environment Variables)

في Railway Dashboard، أضف المتغيرات التالية في قسم Variables:

```
DB_CONNECTION=mysql
DB_URL=mysql://root:kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB@caboose.proxy.rlwy.net:57758/railway
DB_HOST=caboose.proxy.rlwy.net
DB_PORT=57758
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB
```

أو يمكنك استخدام `DB_URL` فقط (Laravel سيقوم بتحليله تلقائياً).

### 2. متغيرات إضافية مطلوبة

```
APP_NAME="ITCenter Ecommerce"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 3. إنشاء APP_KEY

قم بتشغيل الأمر التالي محلياً أو في Railway:
```bash
php artisan key:generate
```

ثم انسخ المفتاح إلى متغير `APP_KEY` في Railway.

## النشر على Railway

### الطريقة 1: النشر من GitHub (موصى به)

1. ارفع الكود إلى GitHub:
```bash
git add .
git commit -m "Add Railway deployment configuration"
git push origin main
```

2. في Railway Dashboard:
   - اضغط على "New Project"
   - اختر "Deploy from GitHub repo"
   - اختر المستودع الخاص بك
   - Railway سيكتشف تلقائياً `nixpacks.toml` أو `Dockerfile`

3. أضف متغيرات البيئة كما هو موضح أعلاه

4. Railway سيقوم تلقائياً بـ:
   - تثبيت التبعيات (Composer & NPM)
   - بناء الأصول (npm run build)
   - تشغيل التطبيق

### الطريقة 2: استخدام Railway CLI

```bash
# تثبيت Railway CLI
npm i -g @railway/cli

# تسجيل الدخول
railway login

# ربط المشروع
railway link

# رفع المتغيرات
railway variables set DB_URL="mysql://root:kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB@caboose.proxy.rlwy.net:57758/railway"

# النشر
railway up
```

## تشغيل Migrations

بعد النشر، قم بتشغيل migrations:

```bash
railway run php artisan migrate --force
```

أو من Railway Dashboard:
- اذهب إلى Deployments
- اضغط على "..." بجانب آخر deployment
- اختر "Open Shell"
- شغل: `php artisan migrate --force`

## ملاحظات مهمة

1. **Storage**: تأكد من أن مجلد `storage` لديه صلاحيات الكتابة
2. **Cache**: Railway سيستخدم `php artisan serve` افتراضياً
3. **Port**: Railway يحدد المنفذ تلقائياً عبر متغير `$PORT`
4. **Database**: تأكد من أن قاعدة البيانات متصلة قبل تشغيل migrations

## استكشاف الأخطاء

إذا واجهت مشاكل:

1. تحقق من Logs في Railway Dashboard
2. تأكد من أن جميع متغيرات البيئة موجودة
3. تحقق من اتصال قاعدة البيانات:
   ```bash
   railway run php artisan tinker
   DB::connection()->getPdo();
   ```

