# دليل بناء ورفع Docker Image إلى Docker Hub

## المتطلبات

1. تثبيت Docker Desktop على جهازك
2. حساب على Docker Hub ([سجل هنا](https://hub.docker.com/signup))
3. تسجيل الدخول إلى Docker Hub من Terminal

## الخطوات

### 1. تسجيل الدخول إلى Docker Hub

```bash
docker login
```

أدخل اسم المستخدم وكلمة المرور الخاصة بك.

### 2. تحديث اسم المستخدم في Script

افتح ملف `build-docker.ps1` (لـ Windows) أو `build-docker.sh` (لـ Linux/Mac) وعدّل:

```powershell
# في build-docker.ps1
$DOCKER_USERNAME = "your-dockerhub-username"  # غيّر هذا
```

أو

```bash
# في build-docker.sh
DOCKER_USERNAME="your-dockerhub-username"  # غيّر هذا
```

### 3. بناء الصورة

#### على Windows (PowerShell):
```powershell
# بناء الصورة فقط
.\build-docker.ps1

# بناء ورفع مباشرة
.\build-docker.ps1 -Push

# بناء إصدار محدد
.\build-docker.ps1 -Version "v1.0.0" -Push
```

#### على Linux/Mac:
```bash
# إعطاء صلاحيات التنفيذ
chmod +x build-docker.sh

# بناء الصورة فقط
./build-docker.sh

# بناء ورفع مباشرة
./build-docker.sh latest --push

# بناء إصدار محدد
./build-docker.sh v1.0.0 --push
```

### 4. بناء يدوي (بدون Script)

```bash
# 1. بناء الصورة
docker build -t your-dockerhub-username/itcenter-ecommerce:latest .

# 2. اختبار الصورة محلياً (اختياري)
docker run -p 8080:80 your-dockerhub-username/itcenter-ecommerce:latest

# 3. رفع الصورة إلى Docker Hub
docker push your-dockerhub-username/itcenter-ecommerce:latest
```

## استخدام الصورة على Railway

بعد رفع الصورة إلى Docker Hub:

1. اذهب إلى Railway Dashboard
2. أنشئ مشروع جديد
3. اختر **"Deploy from Docker Hub"**
4. أدخل اسم الصورة: `your-dockerhub-username/itcenter-ecommerce:latest`
5. أضف متغيرات البيئة:
   ```
   DB_URL=mysql://root:kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB@caboose.proxy.rlwy.net:57758/railway
   APP_KEY=base64:YOUR_APP_KEY_HERE
   APP_ENV=production
   ```

## متغيرات البيئة المطلوبة

في Railway، أضف هذه المتغيرات:

```
DB_CONNECTION=mysql
DB_URL=mysql://root:kCrtIOEFlUJIJOAJKSXovFBNiYKaIRxB@caboose.proxy.rlwy.net:57758/railway
APP_NAME="ITCenter Ecommerce"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
```

## تحديث الصورة

عندما تقوم بتحديثات على الكود:

1. ارفع الكود إلى GitHub
2. شغّل script البناء مرة أخرى:
   ```powershell
   .\build-docker.ps1 -Version "v1.0.1" -Push
   ```
3. Railway سيكتشف التحديث تلقائياً إذا كنت تستخدم `latest` tag

## نصائح

- استخدم version tags بدلاً من `latest` للإنتاج (مثل: `v1.0.0`, `v1.0.1`)
- اختبر الصورة محلياً قبل الرفع
- استخدم multi-stage builds لتقليل حجم الصورة (اختياري)
- راجع logs في Railway بعد النشر

## استكشاف الأخطاء

### خطأ في البناء
- تأكد من أن Docker Desktop يعمل
- تحقق من أن جميع الملفات المطلوبة موجودة
- راجع `.dockerignore` للتأكد من عدم استبعاد ملفات مهمة

### خطأ في الرفع
- تأكد من تسجيل الدخول: `docker login`
- تحقق من اسم المستخدم في script
- تأكد من وجود اتصال بالإنترنت

### خطأ في Railway
- تحقق من متغيرات البيئة
- راجع logs في Railway Dashboard
- تأكد من أن الصورة موجودة في Docker Hub

