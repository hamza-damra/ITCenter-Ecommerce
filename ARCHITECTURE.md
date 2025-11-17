# IT Center E-commerce Architecture

## Overview

This document defines the architectural standards, design system, and development guidelines for the IT Center e-commerce platform. All code generation and modifications must strictly adhere to these specifications to ensure consistency, maintainability, and professional quality.

## Technology Stack

### Backend
- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum/Breeze
- **Caching**: Redis (recommended)
- **Queue**: Redis/Database
- **Storage**: Local/S3 compatible

### Frontend
- **Template Engine**: Blade Templates
- **Styling**: Custom CSS with modern patterns
- **JavaScript**: Vanilla JS with modern ES6+ features
- **Icons**: Font Awesome 6.4.0
- **Typography**: Poppins font family
- **Build Tools**: Vite (Laravel Mix alternative)

### Infrastructure
- **Web Server**: Nginx/Apache
- **PHP**: PHP-FPM
- **SSL**: Let's Encrypt/Commercial certificates
- **Deployment**: Docker containers (recommended)

## Design System

### Color Palette

#### Primary Colors
```css
--primary-dark: #1f2937;      /* Main dark gray */
--primary-blue: #2563eb;      /* Primary blue */
--primary-light-blue: #3b82f6; /* Light blue variant */
--primary-gray: #111827;      /* Darker gray */
```

#### Secondary Colors
```css
--secondary-orange: #e69270;  /* Accent orange */
--secondary-red: #ef4444;     /* Error/danger */
--secondary-green: #10b981;   /* Success */
--secondary-yellow: #f59e0b;  /* Warning */
```

#### Background Colors
```css
--bg-primary: #fafafa;        /* Main background */
--bg-secondary: #f5f5f5;      /* Secondary background */
--bg-card: #ffffff;           /* Card backgrounds */
--bg-dark: #1a1a1a;          /* Dark sections */
```

#### Text Colors
```css
--text-primary: #1e293b;      /* Primary text */
--text-secondary: #64748b;    /* Secondary text */
--text-muted: #94a3b8;        /* Muted text */
--text-white: #ffffff;        /* White text */
```

### Typography

#### Font Family
- **Primary**: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif
- **Arabic**: 'Cairo', sans-serif (for RTL languages)
- **Monospace**: 'Fira Code', 'Consolas', monospace (for code)

#### Font Weights
- **Light**: 300
- **Regular**: 400
- **Medium**: 500
- **Semi-bold**: 600
- **Bold**: 700
- **Extra-bold**: 800

#### Font Sizes
```css
--text-xs: 0.75rem;    /* 12px */
--text-sm: 0.875rem;   /* 14px */
--text-base: 1rem;     /* 16px */
--text-lg: 1.125rem;   /* 18px */
--text-xl: 1.25rem;    /* 20px */
--text-2xl: 1.5rem;    /* 24px */
--text-3xl: 1.875rem;  /* 30px */
--text-4xl: 2.25rem;   /* 36px */
```

### Layout System

#### Container Widths
```css
--container-sm: 640px;
--container-md: 768px;
--container-lg: 1024px;
--container-xl: 1280px;
--container-2xl: 1400px;  /* Max container width */
```

#### Spacing Scale
```css
--space-1: 0.25rem;   /* 4px */
--space-2: 0.5rem;    /* 8px */
--space-3: 0.75rem;   /* 12px */
--space-4: 1rem;      /* 16px */
--space-5: 1.25rem;   /* 20px */
--space-6: 1.5rem;    /* 24px */
--space-8: 2rem;      /* 32px */
--space-10: 2.5rem;   /* 40px */
--space-12: 3rem;     /* 48px */
--space-16: 4rem;     /* 64px */
```

#### Border Radius
```css
--radius-sm: 8px;
--radius-md: 12px;
--radius-lg: 16px;
--radius-xl: 20px;
--radius-2xl: 24px;
--radius-full: 50px;
```

### Component Standards

#### Cards
```css
.card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
}
```

#### Buttons
```css
.btn-primary {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-gray) 100%);
    color: var(--text-white);
    padding: 0.75rem 2rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(31, 41, 55, 0.3);
}
```

#### Form Elements
```css
.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: var(--radius-md);
    font-size: var(--text-base);
    transition: all 0.3s ease;
    background: var(--bg-card);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
```

## Directory Structure

```
/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin panel controllers
│   │   │   ├── Api/             # API controllers
│   │   │   └── Web/             # Web controllers
│   │   ├── Middleware/          # Custom middleware
│   │   └── Requests/            # Form request validation
│   ├── Models/                  # Eloquent models
│   ├── Services/                # Business logic services
│   └── Helpers/                 # Helper classes
├── resources/
│   ├── views/
│   │   ├── layouts/             # Layout templates
│   │   ├── components/          # Reusable components
│   │   ├── admin/               # Admin panel views
│   │   ├── auth/                # Authentication views
│   │   └── partials/            # Partial views
│   ├── css/
│   │   ├── app.css              # Main stylesheet
│   │   ├── components.css       # Component styles
│   │   └── admin.css            # Admin styles
│   └── js/
│       ├── app.js               # Main JavaScript
│       ├── components/          # JS components
│       └── admin/               # Admin JavaScript
├── public/
│   ├── css/                     # Compiled CSS
│   ├── js/                      # Compiled JavaScript
│   └── images/                  # Static images
└── database/
    ├── migrations/              # Database migrations
    ├── seeders/                 # Database seeders
    └── factories/               # Model factories
```

## Naming Conventions

### Files and Directories
- **Controllers**: PascalCase (e.g., `ProductController.php`)
- **Models**: PascalCase, singular (e.g., `Product.php`)
- **Views**: kebab-case (e.g., `product-detail.blade.php`)
- **CSS Classes**: kebab-case (e.g., `.product-card`)
- **JavaScript**: camelCase for functions, PascalCase for classes

### Database
- **Tables**: snake_case, plural (e.g., `product_categories`)
- **Columns**: snake_case (e.g., `created_at`)
- **Foreign Keys**: `{table}_id` (e.g., `user_id`)

### CSS Classes
- **Components**: `.component-name` (e.g., `.product-card`)
- **Modifiers**: `.component-name--modifier` (e.g., `.btn--large`)
- **States**: `.component-name.is-state` (e.g., `.btn.is-loading`)

## Responsive Design

### Breakpoints
```css
/* Mobile First Approach */
@media (min-width: 640px) { /* sm */ }
@media (min-width: 768px) { /* md */ }
@media (min-width: 1024px) { /* lg */ }
@media (min-width: 1280px) { /* xl */ }
@media (min-width: 1536px) { /* 2xl */ }
```

### Grid System
- Use CSS Grid for complex layouts
- Use Flexbox for component-level layouts
- Mobile-first responsive design
- Minimum touch target: 44px × 44px

## Performance Standards

### Frontend
- **First Contentful Paint**: < 1.5s
- **Largest Contentful Paint**: < 2.5s
- **Cumulative Layout Shift**: < 0.1
- **First Input Delay**: < 100ms

### Backend
- **API Response Time**: < 200ms (average)
- **Database Queries**: < 50ms (average)
- **Page Load Time**: < 2s (complete)

### Optimization Techniques
- Image optimization (WebP format preferred)
- CSS/JS minification and compression
- Database query optimization
- Caching strategies (Redis/Memcached)
- CDN usage for static assets

## Security Guidelines

### Authentication & Authorization
- Use Laravel Sanctum for API authentication
- Implement role-based access control (RBAC)
- Password hashing with bcrypt/argon2
- Two-factor authentication (2FA) for admin accounts

### Data Protection
- Input validation and sanitization
- SQL injection prevention (use Eloquent ORM)
- XSS protection (escape output)
- CSRF protection (Laravel's built-in)
- Rate limiting for API endpoints

### HTTPS & Encryption
- Force HTTPS in production
- Use TLS 1.2+ certificates
- Encrypt sensitive data at rest
- Secure session configuration

## Testing Standards

### Unit Tests
- Test coverage: > 80%
- Use PHPUnit for backend testing
- Test all model relationships and methods
- Mock external dependencies

### Feature Tests
- Test complete user workflows
- Test API endpoints thoroughly
- Test authentication and authorization
- Test form submissions and validations

### Frontend Testing
- Test JavaScript functionality
- Test responsive design across devices
- Test accessibility compliance (WCAG 2.1)
- Cross-browser compatibility testing

## Deployment & DevOps

### Environment Configuration
- **Development**: Local development with debugging enabled
- **Staging**: Production-like environment for testing
- **Production**: Optimized for performance and security

### CI/CD Pipeline
1. Code commit triggers automated tests
2. Security scanning and code quality checks
3. Build and compile assets
4. Deploy to staging environment
5. Run integration tests
6. Deploy to production (manual approval)

### Monitoring & Logging
- Application performance monitoring (APM)
- Error tracking and alerting
- Database performance monitoring
- Server resource monitoring
- User analytics and behavior tracking

## Internationalization (i18n)

### Language Support
- **Primary**: English (en)
- **Secondary**: Arabic (ar), Hebrew (he)
- RTL (Right-to-Left) support for Arabic and Hebrew
- Unicode UTF-8 encoding throughout

### Implementation
- Use Laravel's localization features
- Store translations in `lang/` directory
- Use `__()` helper for translatable strings
- Implement language switching functionality
- Format dates, numbers, and currencies per locale

## Accessibility Standards

### WCAG 2.1 Compliance
- **Level AA** compliance minimum
- Semantic HTML structure
- Proper heading hierarchy (h1-h6)
- Alt text for all images
- Keyboard navigation support
- Screen reader compatibility

### Implementation
- Use ARIA labels and roles
- Ensure sufficient color contrast (4.5:1 minimum)
- Provide focus indicators
- Support keyboard-only navigation
- Test with screen readers

## Code Quality Standards

### PHP Standards
- Follow PSR-12 coding standards
- Use type declarations
- Write self-documenting code
- Implement proper error handling
- Use dependency injection

### JavaScript Standards
- Use ES6+ features
- Follow consistent naming conventions
- Implement proper error handling
- Use async/await for asynchronous operations
- Minimize global scope pollution

### CSS Standards
- Use consistent naming conventions (BEM methodology)
- Organize styles logically
- Use CSS custom properties (variables)
- Implement mobile-first responsive design
- Optimize for performance

## Documentation Requirements

### Code Documentation
- PHPDoc blocks for all classes and methods
- JSDoc comments for JavaScript functions
- README files for complex components
- API documentation (OpenAPI/Swagger)

### User Documentation
- Installation and setup guides
- User manuals and tutorials
- Admin panel documentation
- Troubleshooting guides

## Version Control

### Git Workflow
- Use feature branches for development
- Meaningful commit messages
- Pull request reviews required
- Semantic versioning (SemVer)

### Branch Naming
- `feature/feature-name`
- `bugfix/bug-description`
- `hotfix/critical-fix`
- `release/version-number`

## Maintenance & Updates

### Regular Maintenance
- Security updates and patches
- Dependency updates
- Performance optimization
- Database maintenance
- Backup verification

### Monitoring & Alerts
- Server uptime monitoring
- Application error tracking
- Performance degradation alerts
- Security incident notifications

---

This architecture document serves as the foundation for all development work on the IT Center e-commerce platform. All team members must familiarize themselves with these standards and ensure compliance in their contributions.

**Last Updated**: November 2024
**Version**: 1.0.0
