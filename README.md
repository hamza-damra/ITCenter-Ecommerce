# IT Center E-commerce

A modern e-commerce platform for IT products built with Laravel 12 and MySQL.

## 🚀 Quick Start

### For Team Members After Pulling Changes

**If you get "Class not found" errors, run:**

```bash
composer install
php artisan config:clear
php artisan cache:clear
```

📖 **[Complete Setup Guide](SETUP.md)** - Read this for detailed instructions!

---

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for frontend assets)

---

## ⚡ Quick Setup

```bash
# Clone the repository
git clone https://github.com/hamza-damra/ITCenter-Ecommerce.git
cd ITCenter-Ecommerce

# Install dependencies
composer install

# Copy environment file
copy .env.example .env    # Windows
# cp .env.example .env    # Linux/Mac

# Generate application key
php artisan key:generate

# Configure database in .env file
# Then run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed --class=QuickSeeder

# Start development server
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---

## 🗄️ Database Configuration

### Local Development

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itcenter_ecommerce
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Remote Database (Team)

Contact the team lead for remote database credentials.

---

## 🌍 Features

- ✅ Multi-language support (English & Arabic)
- ✅ RTL/LTR layout support
- ✅ Product catalog with categories
- ✅ Shopping cart functionality
- ✅ Wishlist/Favorites
- ✅ Responsive design
- ✅ Remote database support with SSL
- ✅ Modern UI with animations

---

## 📁 Project Structure

```
ITCenter-Ecommerce/
├── app/                    # Application code
│   ├── Http/Controllers/   # Controllers
│   ├── Models/             # Eloquent models
│   └── Helpers/            # Helper functions
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Web routes
└── public/                 # Public assets
```

---

## 🛠️ Common Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Check migration status
php artisan migrate:status

# Start development server
php artisan serve
```

---

## 🐛 Troubleshooting

### Issue: "Class not found" errors

**Solution:**
```bash
composer install
php artisan config:clear
php artisan cache:clear
```

### Issue: Database connection failed

**Solution:**
- Check if MySQL is running
- Verify credentials in `.env`
- Ensure database exists

### Issue: No application key

**Solution:**
```bash
php artisan key:generate
```

---

## 📚 Documentation

- [Complete Setup Guide](SETUP.md)
- [Language Dropdown Improvements](LANGUAGE_DROPDOWN_IMPROVEMENTS.md)
- [Language Dropdown Comparison](LANGUAGE_DROPDOWN_COMPARISON.md)

---

## 🤝 Contributing

1. Pull latest changes: `git pull origin main`
2. Create feature branch: `git checkout -b feature-name`
3. Make changes and commit: `git commit -m "Description"`
4. Push changes: `git push origin feature-name`
5. Create Pull Request

---

## 👥 Team

- **Team Lead:** [Contact for database credentials]

---

## 📝 License

This project is private and for educational purposes.

---

**Need help?** Check [SETUP.md](SETUP.md) or contact the team lead.
