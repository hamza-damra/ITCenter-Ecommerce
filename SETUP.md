# Setup Instructions for Team Members

## After Pulling Changes from GitHub

When you pull updates from GitHub, follow these steps to ensure everything works correctly:

### 1. **Install/Update Dependencies**

```bash
composer install
```

This will install all PHP dependencies including Laravel Sanctum and other packages.

### 2. **Copy Environment File**

If you don't have a `.env` file:

```bash
# Windows (PowerShell)
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### 3. **Configure Your Database**

Open `.env` file and update the database settings:

#### Option A: Use Local Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itcenter_ecommerce
DB_USERNAME=root
DB_PASSWORD=root
```

#### Option B: Use Remote Aiven Database (Ask team lead for credentials)
```env
DB_CONNECTION=mysql
DB_HOST=your-database-host.aivencloud.com
DB_PORT=21972
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=your_database_password_here
MYSQL_ATTR_SSL_CA=
DB_SSLMODE=REQUIRED
```

### 4. **Generate Application Key**

```bash
php artisan key:generate
```

### 5. **Run Migrations**

```bash
# Check migration status
php artisan migrate:status

# Run migrations (only if needed)
php artisan migrate
```

### 6. **Seed Database (Optional)**

If you need sample data:

```bash
# Full seeding (takes longer)
php artisan db:seed

# Quick seeding (faster, less data)
php artisan db:seed --class=QuickSeeder
```

### 7. **Install Node Dependencies (If working with frontend)**

```bash
npm install
```

### 8. **Build Assets (If needed)**

```bash
# For development
npm run dev

# For production
npm run build
```

### 9. **Start Development Server**

```bash
php artisan serve
```

The application should now be accessible at `http://127.0.0.1:8000`

---

## Common Issues and Solutions

### Issue: "Class 'Laravel\Sanctum\Sanctum' not found"

**Solution:**
```bash
composer install
php artisan config:clear
php artisan cache:clear
```

### Issue: Database connection error

**Solution:**
- Check if your database server is running
- Verify database credentials in `.env` file
- Make sure the database exists (create it if needed)

### Issue: "No application encryption key has been specified"

**Solution:**
```bash
php artisan key:generate
```

### Issue: Assets not loading

**Solution:**
```bash
npm install
npm run dev
```

### Issue: Permission errors (Linux/Mac)

**Solution:**
```bash
chmod -R 775 storage bootstrap/cache
```

---

## Quick Setup Command (All-in-One)

For a completely fresh setup, run:

```bash
composer install && php artisan key:generate && php artisan migrate && php artisan db:seed
```

---

## Database Configuration Notes

### Local Development
- Make sure MySQL/MariaDB is installed and running
- Create the database: `CREATE DATABASE itcenter_ecommerce;`
- Update `.env` with your local credentials

### Remote Database (Aiven)
- SSL is required for connection
- All team members can use the same remote database
- No need to run migrations if database is already set up
- Contact team lead for current database credentials

---

## Git Workflow

When pulling changes:

1. **Pull latest changes:**
   ```bash
   git pull origin main
   ```

2. **Update dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Check for new migrations:**
   ```bash
   php artisan migrate:status
   ```

4. **Run new migrations if any:**
   ```bash
   php artisan migrate
   ```

5. **Clear caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

---

## Environment Variables Not Tracked by Git

The following files are NOT tracked by Git (`.gitignore`):
- `.env` - You need to create/update this yourself
- `vendor/` - Run `composer install` to generate
- `node_modules/` - Run `npm install` to generate
- `public/storage` - Run `php artisan storage:link` if needed

---

## Need Help?

Contact the team lead for:
- Remote database credentials
- Environment configuration
- Any setup issues

---

**Last Updated:** October 17, 2025
