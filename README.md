# ITCenter E-Commerce

A full-featured, multilingual e-commerce platform built with **Laravel 12**, supporting **Arabic (RTL)**, **Hebrew (RTL)**, and **English (LTR)** with a modern, responsive UI.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-4.0-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## Features

### Storefront
- **Product Catalog** — Browse, search, and filter products with real-time AJAX filtering
- **Advanced Filtering** — Filter by price range, category, brand, stock status, tags, and custom attributes
- **Dual-Thumb Price Slider** — Custom-built pure CSS/JS range slider (no external library), RTL-safe
- **Shopping Cart** — Add/remove items, quantity management, real-time totals
- **Favorites/Wishlist** — Save products for later
- **Product Reviews** — Customer ratings and review system
- **Responsive Design** — Mobile-first layout with touch-friendly controls

### Multilingual & RTL Support
- **3 Languages** — English, Arabic (العربية), Hebrew (עברית)
- **Full RTL Support** — Automatic layout mirroring for Arabic and Hebrew
- **Dynamic Font Loading** — Cairo font for Arabic, Poppins for English
- **Localized Content** — Translated UI, labels, error messages, and database content

### Admin Dashboard
- **Product Management** — CRUD operations with image upload
- **Order Management** — Track and manage customer orders
- **Brand & Category Management** — Organize product catalog
- **Banner System** — Customizable homepage banners with color themes
- **User Management** — Admin user controls
- **RTL Admin UI** — Full Arabic support in the admin panel

### Technical
- **AJAX Filtering** — No page reloads when applying filters
- **Docker Support** — Dockerfile and Docker Hub deployment ready
- **Railway/Fly.io Deployment** — Cloud deployment configurations included
- **Database Backup System** — Built-in backup and restore functionality
- **Security Hardened** — CSRF protection, input sanitization, secure authentication

---

## Tech Stack

| Layer        | Technology                          |
|-------------|-------------------------------------|
| **Backend**  | Laravel 12, PHP 8.2+               |
| **Frontend** | Blade Templates, Tailwind CSS 4.0  |
| **Database** | MySQL                               |
| **Build**    | Vite 7, Laravel Vite Plugin         |
| **Auth**     | Laravel Sanctum                     |
| **Deploy**   | Docker, Railway, Fly.io             |

---

## Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+

### Installation

```bash
# Clone the repository
git clone https://github.com/hamza-damra/ITCenter-Ecommerce.git
cd ITCenter-Ecommerce

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure your database in .env
# DB_DATABASE=itcenter
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations and seed
php artisan migrate --seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### Docker

```bash
# Build and run with Docker
docker build -t itcenter-ecommerce .
docker run -p 8000:8000 itcenter-ecommerce
```

---

## Project Structure

```
ITCenter-Ecommerce/
├── app/
│   ├── Auth/              # Custom authentication (Bootstrap user)
│   ├── Console/           # Artisan commands
│   ├── Exceptions/        # Custom exception handlers
│   ├── Helpers/           # Locale & image helpers
│   ├── Http/Controllers/  # Route controllers
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic (filtering, etc.)
├── config/                # App configuration
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Data seeders
├── lang/
│   ├── ar/                # Arabic translations
│   ├── en/                # English translations
│   └── he/                # Hebrew translations
├── public/
│   ├── css/               # Compiled CSS
│   ├── js/                # Client-side JavaScript
│   └── images/            # Static assets
├── resources/
│   ├── css/               # Source CSS
│   ├── js/                # Source JavaScript
│   └── views/             # Blade templates
│       ├── admin/         # Admin panel views
│       ├── auth/          # Authentication views
│       ├── components/    # Reusable components
│       └── layouts/       # Layout templates
├── routes/
│   ├── api.php            # API routes
│   └── web.php            # Web routes
└── tests/                 # Feature & unit tests
```

---

## Screenshots

> **English (LTR)**
> Products page with advanced filtering sidebar, price range slider, and category filters.

> **Arabic (RTL)**
> Full RTL layout with mirrored UI, Arabic translations, and right-to-left text flow.

---

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature`)
3. Commit your changes (`git commit -m 'feat: add your feature'`)
4. Push to the branch (`git push origin feature/your-feature`)
5. Open a Pull Request

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

**Built with passion by [Hamza Damra](https://github.com/hamza-damra)**
