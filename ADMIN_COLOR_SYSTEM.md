# Admin Design System - Shared Colors

## Color Palette

The admin panel and admin login page now share a unified color system using CSS custom properties (variables).

### Primary Colors
```css
--primary: #2563eb;        /* Blue 600 - Main brand color */
--primary-dark: #1e40af;   /* Blue 800 - Darker variant */
--primary-light: #3b82f6;  /* Blue 500 - Lighter variant */
```

**Usage:**
- Primary buttons and CTAs
- Active navigation items
- Links and interactive elements
- Login header gradient

### Semantic Colors
```css
--success: #10b981;        /* Green 500 - Success states */
--danger: #ef4444;         /* Red 500 - Error states */
--warning: #f59e0b;        /* Amber 500 - Warning states */
--secondary: #64748b;      /* Slate 500 - Secondary text */
```

**Usage:**
- Alert messages
- Form validation feedback
- Status indicators
- Secondary UI elements

### Neutral Colors
```css
--light: #f8fafc;          /* Slate 50 - Light backgrounds */
--dark: #0f172a;           /* Slate 900 - Dark text */
--border: #e2e8f0;         /* Slate 200 - Borders */
```

**Usage:**
- Background colors
- Text colors
- Borders and dividers

### Shadows
```css
--shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
          0 2px 4px -1px rgba(0, 0, 0, 0.06);
          
--shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
             0 10px 10px -5px rgba(0, 0, 0, 0.04);
```

**Usage:**
- Card shadows
- Dropdown shadows
- Button elevation

## Gradients

### Background Gradient
```css
background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
```
**Used in:**
- Admin panel main background
- Login page background

### Header Gradient
```css
background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
```
**Used in:**
- Login card header
- Sidebar (admin panel)
- Primary buttons

## Implementation

### Admin Login Page
File: `resources/views/admin/auth/login.blade.php`

```css
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --primary-light: #3b82f6;
    --secondary: #64748b;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --light: #f8fafc;
    --dark: #0f172a;
    --border: #e2e8f0;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
```

### Admin Panel Layout
File: `resources/views/admin/layout.blade.php`

```css
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --primary-light: #3b82f6;
    --secondary: #64748b;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --light: #f8fafc;
    --dark: #0f172a;
    --border: #e2e8f0;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
```

## Benefits

✅ **Consistency** - Same colors across all admin interfaces
✅ **Maintainability** - Update once, applies everywhere
✅ **Accessibility** - WCAG compliant color contrasts
✅ **Professional** - Clean, modern blue theme
✅ **Flexibility** - Easy to customize via CSS variables

## Color Usage Guidelines

### Do's ✅
- Use `var(--primary)` for primary actions
- Use `var(--danger)` for destructive actions
- Use `var(--success)` for positive feedback
- Use `var(--border)` for consistent borders
- Use CSS variables instead of hardcoded hex values

### Don'ts ❌
- Don't hardcode color hex values
- Don't use inconsistent shades of blue
- Don't mix different color systems
- Don't override CSS variables inline

## Future Enhancements

- [ ] Dark mode support (alternative color scheme)
- [ ] Theme switcher
- [ ] Custom brand colors via settings
- [ ] Color accessibility checker
- [ ] Print-friendly styles

## Reference

Based on Tailwind CSS color palette:
- Blue (Primary): Tailwind Blue 600/800/500
- Green (Success): Tailwind Green 500
- Red (Danger): Tailwind Red 500
- Amber (Warning): Tailwind Amber 500
- Slate (Neutrals): Tailwind Slate 50/500/900

## Version
- **Created**: October 19, 2025
- **Last Updated**: October 19, 2025
- **Status**: Active
