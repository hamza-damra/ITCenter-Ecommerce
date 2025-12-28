# إصلاح مشكلة رفع Docker Image

## المشكلة
الصورة تم بناؤها بنجاح لكن الرفع فشل لأن اسم المستخدم في السكريبت كان `your-dockerhub-username`.

## الحل السريع

### الطريقة 1: استخدام السكريبت المحسّن (موصى به)

1. **شغّل السكريبت مع اسم المستخدم:**
```powershell
.\build-docker.ps1 -Version "v1.0.0" -Push -DockerUsername "YOUR_DOCKERHUB_USERNAME"
```

السكريبت الآن سيطلب اسم المستخدم تلقائياً إذا لم تحددّه.

### الطريقة 2: إعادة تسمية الصورة الموجودة

1. **استخدم السكريبت لإصلاح الـ tags:**
```powershell
.\fix-docker-tags.ps1 -DockerUsername "YOUR_DOCKERHUB_USERNAME"
```

2. **ثم ارفع الصورة:**
```powershell
docker push YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:latest
docker push YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:v1.0.0
```

### الطريقة 3: يدوياً

1. **تأكد من تسجيل الدخول:**
```powershell
docker login
```

2. **أعد تسمية الصورة:**
```powershell
docker tag your-dockerhub-username/itcenter-ecommerce:latest YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:latest
docker tag your-dockerhub-username/itcenter-ecommerce:v1.0.0 YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:v1.0.0
```

3. **ارفع الصورة:**
```powershell
docker push YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:latest
docker push YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:v1.0.0
```

## ملاحظات مهمة

1. **تأكد من إنشاء Repository على Docker Hub:**
   - اذهب إلى https://hub.docker.com
   - اضغط على "Create Repository"
   - اسم الـ repository: `itcenter-ecommerce`
   - اختر Public أو Private

2. **تأكد من تسجيل الدخول:**
   ```powershell
   docker login
   ```

3. **استخدم اسم المستخدم الصحيح** في جميع الأوامر.

## بعد الرفع الناجح

ستكون الصورة متاحة على:
```
https://hub.docker.com/r/YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce
```

يمكنك استخدامها في Railway:
```
YOUR_DOCKERHUB_USERNAME/itcenter-ecommerce:latest
```

