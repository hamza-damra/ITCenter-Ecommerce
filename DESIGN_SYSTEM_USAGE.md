# IT Center Design System Usage Guide

This guide provides practical examples and best practices for using the IT Center design system components and styles.

## Quick Start

### 1. Include the Components CSS
Add this to the head of your Blade templates:
```html
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
```

### 2. Use CSS Custom Properties
The design system uses CSS custom properties (variables) for consistency:
```css
/* Use design system colors */
.my-element {
    background: var(--primary-blue);
    color: var(--text-white);
    padding: var(--space-4);
    border-radius: var(--radius-md);
}
```

## Component Usage Examples

### Product Cards
Use the product card component for consistent product display:

```blade
{{-- Basic usage --}}
<x-product-card :product="$product" />

{{-- With custom options --}}
<x-product-card 
    :product="$product" 
    :showQuickActions="false" 
    :showWishlist="true" 
/>

{{-- In a grid layout --}}
<div class="grid grid-cols-4 gap-6">
    @foreach($products as $product)
        <x-product-card :product="$product" />
    @endforeach
</div>
```

### Page Headers
Create consistent page headers with breadcrumbs:

```blade
{{-- Basic page header --}}
<x-page-header 
    title="Products" 
    subtitle="Browse our latest IT products"
    icon="fas fa-laptop"
/>

{{-- With breadcrumbs --}}
<x-page-header 
    title="Product Details" 
    :breadcrumbs="[
        ['title' => 'Home', 'url' => route('home'), 'icon' => 'fas fa-home'],
        ['title' => 'Products', 'url' => route('products')],
        ['title' => $product->name]
    ]"
    icon="fas fa-box"
>
    {{-- Additional content slot --}}
    <div class="flex gap-4 mt-4">
        <x-button variant="primary" icon="fas fa-edit">Edit Product</x-button>
        <x-button variant="danger" outline icon="fas fa-trash">Delete</x-button>
    </div>
</x-page-header>

{{-- Gradient background --}}
<x-page-header 
    title="Dashboard" 
    background="gradient"
    icon="fas fa-chart-dashboard"
/>
```

### Buttons
Use the button component for consistent styling:

```blade
{{-- Primary button --}}
<x-button variant="primary" icon="fas fa-plus">
    Add Product
</x-button>

{{-- Secondary outline button --}}
<x-button variant="secondary" outline size="sm">
    Cancel
</x-button>

{{-- Loading state --}}
<x-button variant="success" :loading="true">
    Saving...
</x-button>

{{-- Icon-only button --}}
<x-button variant="danger" icon="fas fa-trash" />

{{-- Block button --}}
<x-button variant="primary" block>
    Continue to Checkout
</x-button>

{{-- Link button --}}
<x-button href="{{ route('products') }}" variant="info">
    View Products
</x-button>
```

## Layout Patterns

### Container and Sections
```html
<div class="container">
    <section class="section">
        <h2 class="text-3xl font-bold text-primary mb-6">Section Title</h2>
        <!-- Content -->
    </section>
</div>
```

### Card Layouts
```html
{{-- Basic card --}}
<div class="card">
    <div class="card-header">
        <h3 class="text-xl font-semibold">Card Title</h3>
    </div>
    <div class="card-body">
        <p>Card content goes here.</p>
    </div>
    <div class="card-footer">
        <x-button variant="primary">Action</x-button>
    </div>
</div>

{{-- Grid of cards --}}
<div class="grid grid-cols-3 gap-6">
    <div class="card"><!-- Card 1 --></div>
    <div class="card"><!-- Card 2 --></div>
    <div class="card"><!-- Card 3 --></div>
</div>
```

### Form Layouts
```html
<form class="space-y-6">
    <div class="form-group">
        <label class="form-label">Product Name</label>
        <input type="text" class="form-input" placeholder="Enter product name">
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input form-textarea" placeholder="Product description"></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Category</label>
        <select class="form-input form-select">
            <option>Select category</option>
            <option>Laptops</option>
            <option>Desktops</option>
        </select>
    </div>
    
    <div class="flex gap-4">
        <x-button type="submit" variant="primary">Save Product</x-button>
        <x-button type="button" variant="secondary">Cancel</x-button>
    </div>
</form>
```

## Utility Classes

### Spacing
```html
<!-- Padding -->
<div class="p-4">Padding all sides</div>
<div class="px-6 py-4">Horizontal and vertical padding</div>

<!-- Margin -->
<div class="mb-8">Margin bottom</div>
<div class="mt-4 mb-6">Margin top and bottom</div>
```

### Typography
```html
<h1 class="text-4xl font-bold text-primary">Main Heading</h1>
<h2 class="text-2xl font-semibold text-secondary">Sub Heading</h2>
<p class="text-base text-muted">Body text</p>
<small class="text-sm text-secondary">Small text</small>
```

### Layout
```html
<!-- Flexbox -->
<div class="flex items-center justify-between gap-4">
    <span>Left content</span>
    <span>Right content</span>
</div>

<!-- Grid -->
<div class="grid grid-cols-4 gap-6">
    <div>Item 1</div>
    <div>Item 2</div>
    <div>Item 3</div>
    <div>Item 4</div>
</div>
```

### Visual Effects
```html
<!-- Shadows -->
<div class="shadow">Basic shadow</div>
<div class="shadow-lg">Large shadow</div>

<!-- Rounded corners -->
<div class="rounded">Basic rounded</div>
<div class="rounded-lg">Large rounded</div>
<div class="rounded-full">Fully rounded</div>

<!-- Transitions -->
<div class="transition hover:transform hover:scale-105">Hover effect</div>
```

## Color Usage

### Background Colors
```html
<div class="bg-primary text-white">Primary background</div>
<div class="bg-secondary text-white">Secondary background</div>
<div class="bg-card">Card background</div>
```

### Text Colors
```html
<p class="text-primary">Primary text</p>
<p class="text-secondary">Secondary text</p>
<p class="text-muted">Muted text</p>
```

### Border Colors
```css
.custom-border {
    border: 1px solid var(--primary-blue);
}
```

## Interactive Components

### Alerts
```html
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    Success message here
</div>

<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i>
    Error message here
</div>
```

### Badges
```html
<span class="badge badge-primary">New</span>
<span class="badge badge-success">In Stock</span>
<span class="badge badge-danger">Out of Stock</span>
```

### Loading States
```html
<!-- Spinner -->
<div class="loading-spinner"></div>

<!-- Dots -->
<div class="loading-dots">
    <span></span>
    <span></span>
    <span></span>
</div>
```

## Responsive Design

### Breakpoint Classes
```html
<!-- Mobile first approach -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
    <!-- Responsive grid -->
</div>

<div class="text-sm md:text-base lg:text-lg">
    <!-- Responsive text size -->
</div>

<div class="p-4 md:p-6 lg:p-8">
    <!-- Responsive padding -->
</div>
```

### Mobile Considerations
```html
<!-- Ensure minimum touch targets -->
<button class="btn btn-primary min-h-[44px]">Touch-friendly button</button>

<!-- Stack on mobile -->
<div class="flex flex-col md:flex-row gap-4">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

## Accessibility Best Practices

### Semantic HTML
```html
<!-- Use proper heading hierarchy -->
<h1>Page Title</h1>
<h2>Section Title</h2>
<h3>Subsection Title</h3>

<!-- Use semantic elements -->
<nav aria-label="Main navigation">
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/products">Products</a></li>
    </ul>
</nav>

<main>
    <article>
        <header>
            <h1>Article Title</h1>
        </header>
        <section>
            <h2>Section Title</h2>
            <p>Content...</p>
        </section>
    </article>
</main>
```

### ARIA Labels
```html
<!-- Button with icon only -->
<button class="btn btn-primary" aria-label="Add to cart">
    <i class="fas fa-shopping-cart"></i>
</button>

<!-- Form inputs -->
<label for="email">Email Address</label>
<input type="email" id="email" class="form-input" aria-describedby="email-help">
<small id="email-help">We'll never share your email</small>

<!-- Loading states -->
<button class="btn btn-primary" aria-busy="true">
    <span class="loading-spinner" aria-hidden="true"></span>
    Loading...
</button>
```

### Focus Management
```html
<!-- Ensure focus indicators are visible -->
<button class="btn btn-primary focus:ring">Focusable button</button>

<!-- Skip links for keyboard navigation -->
<a href="#main-content" class="sr-only focus:not-sr-only">Skip to main content</a>
```

## RTL (Right-to-Left) Support

The design system automatically handles RTL layouts. Use these patterns:

```html
<!-- Text alignment -->
<p class="text-start">Always aligns to reading direction</p>

<!-- Margins and padding -->
<div class="ms-4">Margin start (left in LTR, right in RTL)</div>
<div class="me-4">Margin end (right in LTR, left in RTL)</div>

<!-- Icons in RTL -->
<button class="btn">
    <i class="fas fa-arrow-right"></i> <!-- Will flip in RTL -->
    Next
</button>
```

## Performance Considerations

### Image Optimization
```html
<!-- Use loading="lazy" for images below the fold -->
<img src="product.jpg" alt="Product" loading="lazy" class="rounded">

<!-- Provide multiple formats -->
<picture>
    <source srcset="product.webp" type="image/webp">
    <source srcset="product.jpg" type="image/jpeg">
    <img src="product.jpg" alt="Product" loading="lazy">
</picture>
```

### CSS Loading
```html
<!-- Critical CSS inline, non-critical CSS async -->
<link rel="preload" href="{{ asset('css/components.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/components.css') }}"></noscript>
```

## Common Patterns

### Product Grid
```blade
<div class="container">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</div>
```

### Dashboard Layout
```html
<div class="container">
    <x-page-header title="Dashboard" icon="fas fa-chart-dashboard" />
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats cards -->
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users text-4xl text-primary mb-4"></i>
                <h3 class="text-2xl font-bold">1,234</h3>
                <p class="text-muted">Total Users</p>
            </div>
        </div>
        <!-- More stats... -->
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Charts and tables -->
        <div class="card">
            <div class="card-header">
                <h3>Recent Orders</h3>
            </div>
            <div class="card-body">
                <!-- Table content -->
            </div>
        </div>
    </div>
</div>
```

### Form with Validation
```blade
<form class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h2>Add New Product</h2>
        </div>
        <div class="card-body space-y-6">
            <div class="form-group">
                <label class="form-label required">Product Name</label>
                <input type="text" class="form-input @error('name') border-red-500 @enderror" 
                       name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted">$</span>
                    <input type="number" class="form-input pl-8" name="price" step="0.01">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="flex gap-4">
                <x-button type="submit" variant="primary">Save Product</x-button>
                <x-button type="button" variant="secondary" href="{{ route('products') }}">
                    Cancel
                </x-button>
            </div>
        </div>
    </div>
</form>
```

## Customization

### Extending Colors
```css
:root {
    /* Add custom colors */
    --custom-purple: #8b5cf6;
    --custom-orange: #f97316;
}

.btn-purple {
    background: var(--custom-purple);
    color: var(--text-white);
}
```

### Custom Components
```css
.feature-card {
    @apply card p-6 text-center;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-light-blue));
    color: var(--text-white);
}

.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}
```

## Testing

### Visual Regression Testing
- Test components across different screen sizes
- Verify color contrast ratios meet WCAG standards
- Test with different content lengths
- Verify RTL layout correctness

### Accessibility Testing
- Use screen readers to test navigation
- Verify keyboard-only navigation works
- Test with high contrast mode
- Validate HTML semantics

### Performance Testing
- Monitor CSS bundle size
- Test loading performance
- Verify images are optimized
- Check for unused CSS

This design system ensures consistency, accessibility, and maintainability across the entire IT Center e-commerce platform. Always refer to this guide when implementing new features or modifying existing components.
