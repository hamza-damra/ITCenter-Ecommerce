# دليل النشر على Railway

## ✅ ما تم إعداده

تم إعداد المشروع للنشر على Railway مع الاتصال بقاعدة البيانات MySQL الخاصة بك.

### الملفات التي تم إنشاؤها:

1. **Dockerfile** - لإعداد Docker container
2. **.dockerignore** - لتجاهل الملفات غير الضرورية
3. **nixpacks.toml** - إعدادات Railway (الطريقة المفضلة)
4. **railway.json** - إعدادات إضافية للنشر
5. **RAILWAY_SETUP.md** - دليل مفصل بالإنجليزية

## 🚀 خطوات النشر

### الخطوة 1: رفع الكود إلى GitHub

```bash
git add .
git commit -m "إضافة إعدادات Railway للنشر"
git push origin main
```

### الخطوة 2: ربط المشروع مع Railway

1. اذهب إلى [Railway Dashboard](https://railway.app)
2. اضغط على **"New Project"**
3. اختر **"Deploy from GitHub repo"**
4. اختر المستودع الخاص بك
5. Railway سيكتشف تلقائياً الملفات المطلوبة

### الخطوة 3: إضافة متغيرات البيئة

في Railway Dashboard، اذهب إلى **Variables** وأضف:

```
DB_CONNECTION=mysql
DB_URL=mysql://root:kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB@caboose.proxy.rlwy.net:57758/railway
```

أو يمكنك إضافة كل متغير على حدة:

```
DB_HOST=caboose.proxy.rlwy.net
DB_PORT=57758
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB
```

### الخطوة 4: إضافة متغيرات Laravel المطلوبة

```
APP_NAME="ITCenter Ecommerce"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
```

**ملاحظة مهمة**: يجب إنشاء `APP_KEY` بتشغيل:
```bash
php artisan key:generate
```
ثم نسخ المفتاح إلى متغير `APP_KEY` في Railway.

### الخطوة 5: تشغيل Migrations

بعد النشر، قم بتشغيل migrations:

1. في Railway Dashboard، اذهب إلى **Deployments**
2. اضغط على **"..."** بجانب آخر deployment
3. اختر **"Open Shell"**
4. شغل الأمر:
```bash
php artisan migrate --force
```

## 🔧 إعدادات قاعدة البيانات

تم تحديث `config/database.php` ليدعم:
- الاتصال بقاعدة البيانات عبر `DB_URL` (تلقائياً)
- دعم متغيرات Railway البيئية
- الاتصال الافتراضي بـ MySQL بدلاً من SQLite

## 📝 ملاحظات مهمة

1. **Railway يستخدم Nixpacks افتراضياً**: الملف `nixpacks.toml` سيتم استخدامه تلقائياً
2. **Port**: Railway يحدد المنفذ تلقائياً عبر متغير `$PORT`
3. **Storage**: تأكد من أن مجلد `storage` لديه صلاحيات الكتابة (تم إعداده في Dockerfile)
4. **Database Connection**: تأكد من أن قاعدة البيانات متصلة قبل تشغيل migrations

## 🐛 استكشاف الأخطاء

إذا واجهت مشاكل:

1. **تحقق من Logs**: في Railway Dashboard → Deployments → View Logs
2. **تحقق من متغيرات البيئة**: تأكد من وجود جميع المتغيرات المطلوبة
3. **اختبر الاتصال بقاعدة البيانات**:
   ```bash
   railway run php artisan tinker
   DB::connection()->getPdo();
   ```

## 📚 الملفات المرجعية

- `RAILWAY_SETUP.md` - دليل مفصل بالإنجليزية
- `nixpacks.toml` - إعدادات Railway
- `Dockerfile` - إعدادات Docker (بديل)

## ✨ الميزات المضافة

- ✅ إعداد Docker كامل
- ✅ إعداد Nixpacks لـ Railway
- ✅ دعم اتصال MySQL من Railway
- ✅ بناء الأصول (Assets) تلقائياً
- ✅ تحسين الأداء (Caching)

