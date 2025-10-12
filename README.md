# IT Center E-commerce Website

Modern Arabic e-commerce website for technology and electronics products.

## Features

### Design
- ✅ Clean, professional tech look with white backgrounds and modern shadows
- ✅ Full RTL (Right-to-Left) support for Arabic content
- ✅ Cairo Arabic font family
- ✅ Responsive design (desktop, tablet, mobile)
- ✅ Smooth animations and hover effects

### Sections
1. **Fixed Header**
   - Logo (right side)
   - Centered search bar
   - Account, wishlist, and cart icons (left side)
   - Navigation menu (Home, Categories, Brands, Offers, Products, About, Contact)

2. **Hero Section**
   - Large banner with featured tech products
   - Call-to-action button
   - Gradient background

3. **Main Categories**
   - Grid layout with 8 categories
   - Icon-based design
   - Hover effects with elevation
   - Categories: Computers, Laptops, Keyboards, Mice, Headphones, Processors, RAM, Printers

4. **Featured Products**
   - Horizontal carousel/slider
   - Product cards with images, names, descriptions, and prices
   - Add to cart and wishlist buttons
   - Auto-slide with manual controls

5. **New Arrivals**
   - Grid layout (3-4 products per row)
   - Product cards with hover zoom effect
   - Clean white background with shadows

6. **Category Blocks**
   - Section for keyboards (expandable to other categories)
   - Compact product cards in row layout

7. **Dark Footer**
   - About section with logo and social media links
   - Quick links (About, Terms, Privacy, Returns)
   - Customer service links
   - Payment methods section
   - Copyright notice

### Interactive Features
- 🛒 Add to cart functionality with badge counter
- ❤️ Wishlist toggle (filled/unfilled heart icon)
- 🔍 Search functionality
- 📱 Mobile-responsive hamburger menu
- 🎠 Auto-sliding carousel
- 🔔 Toast notifications for actions
- 🖱️ Smooth scrolling
- 🎨 Hover effects on all interactive elements

## File Structure

```
ITCenter-Ecommerce/
├── index.html          # Main HTML file
├── styles.css          # All styling (RTL, responsive, animations)
├── script.js           # JavaScript for interactions
└── README.md           # This file
```

## How to Run

1. Simply open `index.html` in any modern web browser
2. No build process or dependencies required
3. Works offline (except for external resources like fonts and icons)

## External Resources

The site uses CDN links for:
- **Google Fonts**: Cairo (Arabic font)
- **Font Awesome**: Icons for UI elements
- **Unsplash**: Placeholder product images

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers

## Customization

### Colors
Edit the CSS variables in `styles.css`:
```css
:root {
    --primary-color: #0ea5e9;
    --primary-dark: #0369a1;
    --secondary-color: #1e293b;
    --text-color: #1e293b;
    --text-light: #64748b;
}
```

### Products
Add/edit products in `index.html` by copying the product card structure:
```html
<div class="product-card">
    <div class="product-image">
        <img src="your-image.jpg" alt="Product">
    </div>
    <div class="product-info">
        <h3>Product Name</h3>
        <p class="product-desc">Description</p>
        <div class="product-price">
            <span class="price">Price</span>
        </div>
        <button class="btn btn-cart">Add to Cart</button>
    </div>
</div>
```

## Future Enhancements

- Backend integration for real product data
- Shopping cart page
- Checkout process
- User authentication
- Product filtering and sorting
- Product detail pages
- Order tracking
- Customer reviews

## Credits

Built with pure HTML, CSS, and JavaScript following modern web standards and best practices.

---

**Version**: 1.0.0
**Last Updated**: 2024
