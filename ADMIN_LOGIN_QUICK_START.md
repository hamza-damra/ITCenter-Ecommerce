# Admin Login - Quick Start Guide

## 🚀 Quick Access

**URL**: `http://localhost:8000/admin/login`

**Credentials**:
```
Email: admin@itcenter.com
Password: admin123
```

## 📋 Setup Commands

```bash
# 1. Run migration
php artisan migrate

# 2. Create admin user
php artisan db:seed --class=AdminUserSeeder

# 3. Start server
php artisan serve

# 4. Visit admin login
# http://localhost:8000/admin/login
```

## 🎨 Features

✅ Modern gradient design
✅ Multi-language (EN, AR, HE)
✅ RTL support
✅ Password visibility toggle
✅ Remember me
✅ Form validation
✅ Loading animations
✅ Auto-hide alerts
✅ Responsive design

## 🔒 Security

- Role-based access (admin only)
- Session regeneration
- CSRF protection
- Password hashing
- Middleware protection

## 📁 Key Files

```
app/Http/Controllers/Admin/AuthController.php
app/Http/Middleware/IsAdmin.php
resources/views/admin/auth/login.blade.php
database/migrations/*_add_role_to_users_table.php
database/seeders/AdminUserSeeder.php
routes/web.php (admin routes)
```

## 🔑 Routes

```
GET  /admin/login      - Show login page
POST /admin/login      - Handle login
POST /admin/logout     - Handle logout
GET  /admin            - Dashboard (protected)
```

## 🛠️ Common Commands

```bash
# Create new admin user manually
php artisan tinker
>>> User::create(['name'=>'Admin', 'email'=>'admin@test.com', 'password'=>Hash::make('password'), 'role'=>'admin', 'first_name'=>'Admin', 'last_name'=>'User'])

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check routes
php artisan route:list --name=admin
```

## 📱 Test Checklist

- [ ] Login with correct credentials
- [ ] Login with wrong credentials
- [ ] Try customer account (should fail)
- [ ] Test Remember me
- [ ] Test logout
- [ ] Access protected route while logged out
- [ ] Switch languages
- [ ] Test on mobile
- [ ] Test RTL (Arabic/Hebrew)

## 🐛 Troubleshooting

**Can't login?**
- Check database has role field
- Verify admin user exists
- Clear cache

**419 Error?**
- Check CSRF token
- Clear session

**Redirected back to login?**
- Check role is 'admin'
- Check middleware registered

## 📚 Full Documentation

See `ADMIN_LOGIN_GUIDE.md` for complete documentation.
