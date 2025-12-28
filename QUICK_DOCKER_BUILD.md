# بناء Docker Image بسرعة

## خطوات سريعة

### 1. تسجيل الدخول إلى Docker Hub
```bash
docker login
```

### 2. تحديث اسم المستخدم
افتح `build-docker.ps1` وعدّل السطر 10:
```powershell
$DOCKER_USERNAME = "your-dockerhub-username"  # ضع اسمك هنا
```

### 3. بناء ورفع الصورة
```powershell
.\build-docker.ps1 -Push
```

### 4. استخدام الصورة في Railway
- اذهب إلى Railway → New Project
- اختر "Deploy from Docker Hub"
- أدخل: `your-dockerhub-username/itcenter-ecommerce:latest`

**للمزيد من التفاصيل، راجع `DOCKER_HUB_DEPLOY.md`**

