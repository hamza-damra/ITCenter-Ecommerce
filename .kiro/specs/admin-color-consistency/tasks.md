# Implementation Plan

- [x] 1. Add unified CSS component system to layout.blade.php





  - [x] 1.1 Add extended CSS variables to :root block


    - Add new color variables (--accent-blue, --accent-emerald, --accent-amber, --accent-rose, --accent-violet, --accent-indigo)
    - Add hero gradient variables (--hero-gradient-start, --hero-gradient-mid, --hero-gradient-end)
    - Add background and shadow variables
    - Add border radius variables
    - _Requirements: 1.1, 1.2, 2.1_

  - [x] 1.2 Add .admin-hero component styles


    - Create unified page header with dark slate gradient
    - Add decorative circle pseudo-elements
    - Add .admin-hero-content, .admin-hero-icon, .admin-hero-text classes
    - Add RTL support for hero component
    - _Requirements: 1.1, 1.4, 6.1, 6.2_

  - [x] 1.3 Add .admin-stats-grid and .admin-stat-card component styles


    - Create grid layout for stat cards
    - Add base stat card styling with hover effects
    - Add semantic color variants (.stat-success, .stat-warning, .stat-danger, .stat-info)
    - Add RTL support for stat cards
    - _Requirements: 1.3, 2.1, 2.2, 2.3, 2.4, 2.5_

  - [x] 1.4 Add .admin-table-container and .admin-table component styles


    - Create table container with consistent styling
    - Add table header styling
    - Add consistent row hover effects
    - Add RTL support for tables
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 6.3_

  - [x] 1.5 Add .admin-empty-state component styles


    - Create empty state container styling
    - Add icon styling with gradient background
    - Add typography and spacing
    - _Requirements: 5.1, 5.2, 5.3_


- [x] 2. Update Banners page to use unified components




  - Replace custom .banners-header with .admin-hero
  - Replace custom .banners-stats-grid with .admin-stats-grid
  - Replace custom .banner-stat-card with .admin-stat-card
  - Replace custom .banners-table-container with .admin-table-container
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 4.1, 5.1_


- [x] 3. Update Promotional Offers page to use unified components



  - Replace custom .promo-offers-header with .admin-hero
  - Replace custom .promo-stats-grid with .admin-stats-grid
  - Replace custom .promo-stat-card with .admin-stat-card
  - Replace custom .promo-table-container with .admin-table-container
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 4.1, 5.1_


- [x] 4. Update Reviews page to use unified components




  - Replace custom .reviews-header with .admin-hero
  - Replace custom .reviews-stats-grid with .admin-stats-grid
  - Replace custom .review-stat-card with .admin-stat-card
  - Replace custom .reviews-table-container with .admin-table-container
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 4.1, 5.1_


- [x] 5. Update Products page to use unified components




  - Add .admin-hero header section (currently uses default page-header)
  - Replace custom .stats-overview with .admin-stats-grid
  - Replace custom .stat-mini-card with .admin-stat-card
  - Replace custom .products-table-wrapper with .admin-table-container
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 4.1, 5.1_


- [x] 6. Update Categories page to use unified components




  - Add .admin-hero header section (currently uses default page-header)
  - Replace custom .stats-overview with .admin-stats-grid
  - Replace custom .stat-mini-card with .admin-stat-card
  - Replace custom .categories-table-wrapper with .admin-table-container
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 4.1, 5.1_

- [x] 7. Update Brands page to use unified components





  - Add .admin-hero header section (currently uses default page-header)
  - Replace custom .stats-overview with .admin-stats-grid
  - Replace custom .stat-mini-card with .admin-stat-card
  - Update brand cards grid to use consistent styling
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 5.1_

- [x] 8. Update Orders page to use unified components





  - Verify .orders-hero matches .admin-hero (already uses dark slate)
  - Update stat pipeline cards to use .admin-stat-card styling
  - Update .orders-card to use .admin-table-container
  - Update empty state to use .admin-empty-state
  - Remove redundant custom styles that duplicate unified components
  - _Requirements: 1.1, 1.3, 4.1, 5.1_


- [x] 9. Update Backup page to use unified components




  - Add .admin-hero header section
  - Replace custom .stats-grid with .admin-stats-grid
  - Replace custom .stat-card with .admin-stat-card
  - Replace custom .content-card with .admin-table-container
  - Update empty state styling
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 4.1, 5.1_


- [x] 10. Update Tags page to use unified components




  - Add .admin-hero header section (currently uses default page-header)
  - Update table styling to use .admin-table-container
  - Replace custom empty state with .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 4.1, 5.1_

- [x] 11. Update Contacts page to use unified components





  - Add .admin-hero header section
  - Update table styling to use .admin-table-container
  - Update empty state to use .admin-empty-state
  - Remove redundant custom styles
  - _Requirements: 1.1, 1.2, 4.1, 5.1_


- [x] 12. Final cleanup and verification




  - Review all pages for any remaining inconsistent styles
  - Verify RTL support works correctly on all pages
  - Test responsive behavior on all pages
  - Remove any unused CSS from individual page styles
  - _Requirements: 6.1, 6.2, 6.3_
