# Implementation Plan

- [x] 1. Database schema setup and migrations





  - Create migrations for new fields and tables
  - Add is_strong_offer and discount_percentage to products table
  - Add icon and position to categories table
  - Add multi-language fields to attributes and attribute_values tables
  - Create attribute_category pivot table
  - Create product_attribute_values pivot table
  - Add necessary indexes for performance
  - _Requirements: 1.2, 5.1, 6.1, 7.2, 8.2_
-

- [x] 2. Update Eloquent models with new relationships and scopes



  - [x] 2.1 Extend Product model


    - Add is_strong_offer and discount_percentage to fillable
    - Add strongOffers() scope
    - Add attributeValues() relationship
    - Update casts for new fields
    - _Requirements: 1.2, 8.2_

  - [x] 2.2 Extend Category model


    - Add icon and position to fillable
    - Add attributes() relationship (belongsToMany)
    - Add allProducts() method for hierarchical product queries
    - _Requirements: 5.1, 7.2_

  - [x] 2.3 Extend Attribute model


    - Add multi-language name fields (name_en, name_ar, name_he)
    - Add unit and is_filterable fields
    - Add categories() relationship (belongsToMany)
    - Add getName() accessor for localization
    - _Requirements: 6.1, 7.2, 12.2_

  - [x] 2.4 Extend AttributeValue model


    - Add multi-language value fields (value_en, value_ar, value_he)
    - Add slug field
    - Add getValue() accessor for localization
    - Update products() relationship
    - _Requirements: 6.2, 12.2_

  - [x] 2.5 Write property test for strong offers scope


  - **Property 1: Strong offers filter exclusivity**
  - **Validates: Requirements 1.2**

  - [x] 2.6 Write property test for category relationships


  - **Property 15: Parent-child category relationship**
  - **Validates: Requirements 5.2**
-

- [x] 3. Create ProductFilterService for centralized filter logic




  - [x] 3.1 Implement ProductFilterService class


    - Create applyFilters() method to handle all filter types
    - Implement applyCategoryFilter() method
    - Implement applyStrongOffersFilter() method
    - Implement applyStockFilter() method
    - Implement applyBrandFilter() method
    - Implement applyPriceFilter() method
    - Implement applyAttributeFilters() method with AND logic
    - _Requirements: 1.2, 1.5, 2.5, 3.3_

  - [x] 3.2 Implement filter metadata methods


    - Create getAvailableFilters() method
    - Implement getBrandFilters() with counts
    - Implement getStockFilters() with counts
    - Implement getAttributeFilters() with counts (category-specific)
    - Implement getPriceRange() method
    - _Requirements: 3.1, 3.4_

  - [x] 3.3 Write property test for filter combination AND logic


    - **Property 2: Filter combination uses AND logic**
    - **Validates: Requirements 1.5, 3.5**

  - [x] 3.4 Write property test for attribute filter AND logic


    - **Property 10: Attribute filter AND logic**
    - **Validates: Requirements 3.3**

  - [x] 3.5 Write property test for filter count accuracy


    - **Property 11: Filter count accuracy**
    - **Validates: Requirements 3.4**

- [x] 4. Update ProductController to use ProductFilterService





  - Refactor index() method to use ProductFilterService
  - Apply filters via service instead of inline logic
  - Get available filters with counts from service
  - Maintain pagination with filter parameters
  - Pass filter data to view
  - _Requirements: 1.1, 1.5, 3.5, 10.5_

- [x] 4.1 Write property test for pagination filter preservation


  - **Property 36: Pagination filter preservation**
  - **Validates: Requirements 10.5**
-

- [x] 5. Create CategoryController for category-based product listings




  - [x] 5.1 Implement CategoryController


    - Create show() method for category/sub-category pages
    - Implement loadCategory() helper for parent/child resolution
    - Implement buildBreadcrumbs() helper
    - Apply ProductFilterService with category context
    - Handle both /category/{slug} and /category/{parent}/{child} routes
    - _Requirements: 2.3, 2.4, 2.5, 3.1_

  - [x] 5.2 Write property test for sub-category URL format


    - **Property 5: Sub-category URL format**
    - **Validates: Requirements 2.3**

  - [x] 5.3 Write property test for breadcrumb generation


    - **Property 6: Breadcrumb path generation**
    - **Validates: Requirements 2.4**

  - [x] 5.4 Write property test for category product filtering


    - **Property 7: Category product filtering**
    - **Validates: Requirements 2.5**
-

- [x] 6. Update routes for category navigation and strong offers




  - Add route for /products with strong_offers parameter support
  - Add route for /category/{parentSlug}/{childSlug?}
  - Ensure all filter parameters are preserved in routes
  - _Requirements: 1.1, 2.3_
-

- [x] 7. Create filter sidebar Blade component



  - [x] 7.1 Create reusable filter-sidebar component


    - Create component file with props for filters and current selections
    - Implement Strong Offers checkbox
    - Implement Stock filter checkboxes
    - Implement Brand filter checkboxes with counts
    - Implement Price range slider
    - Implement dynamic attribute filter groups
    - Handle RTL/LTR layout based on locale
    - _Requirements: 1.3, 3.1, 3.4, 4.1, 4.5_

  - [x] 7.2 Add JavaScript for filter interactions


    - Handle checkbox changes and URL updates
    - Handle price slider changes
    - Preserve existing filters when adding new ones
    - Submit form or update URL on filter change
    - _Requirements: 1.4, 4.3_

  - [ ] 7.3 Write property test for URL-to-UI state synchronization



    - **Property 12: URL-to-UI state synchronization**
    - **Validates: Requirements 4.1**

  - [x] 7.4 Write property test for RTL layout


    - **Property 13: RTL layout for RTL locales**
    - **Validates: Requirements 4.5, 12.5**
-

- [x] 8. Update products listing view to use filter sidebar




  - Integrate filter-sidebar component into products.blade.php
  - Pass available filters and current selections to component
  - Display "no results" message when filters return empty set
  - Ensure responsive layout (sidebar on desktop, drawer on mobile)
  - _Requirements: 1.3, 3.4, 4.4, 11.1_

- [x] 9. Create category products view




  - Create category-products.blade.php view
  - Display breadcrumb navigation
  - Integrate filter-sidebar component with category-specific filters
  - Display category name and description
  - Show product grid with filtered results
  - _Requirements: 2.4, 3.1, 7.4_

- [x] 9.1 Write property test for category-specific attribute filters


  - **Property 8: Category-specific attribute filters**
  - **Validates: Requirements 3.1, 7.4**
-

- [x] 10. Update home page Strong Offers promotional card



  - Update Shop Now button to link to /products?strong_offers=1
  - Ensure button text is translatable
  - Maintain existing card styling
  - _Requirements: 1.1, 12.4_

- [x] 10.1 Write property test for promotional card localization




  - **Property 40: Promotional card localization**
  - **Validates: Requirements 12.4**

- [x] 11. Create category navigation menu component





  - [x] 11.1 Create category-nav component


    - Display top-level categories with icons
    - Show sub-categories on hover/click
    - Generate proper URLs for categories and sub-categories
    - Support RTL/LTR layout
    - _Requirements: 2.1, 2.2, 2.3_

  - [x] 11.2 Write property test for active categories visibility


    - **Property 3: Active categories visibility**
    - **Validates: Requirements 2.1, 5.4**

  - [x] 11.3 Write property test for sub-category display


    - **Property 4: Sub-category display on hover**
    - **Validates: Requirements 2.2**

- [x] 12. Implement mobile-responsive filter drawer





  - Create mobile filter drawer component
  - Add toggle button for mobile filter drawer
  - Implement slide-over animation
  - Display all filters in scrollable drawer
  - Close drawer after applying filters
  - Show active filter count badge on toggle button
  - _Requirements: 11.1, 11.2, 11.3, 11.4_

- [x] 13. Add translation strings for all new UI elements



  - Add Strong Offers filter label translations (en, ar, he)
  - Add Stock filter labels (In Stock, Out of Stock)
  - Add filter sidebar headings (Filters, Brand, Price, etc.)
  - Add "No results found" message translations
  - Add breadcrumb translations
  - Add mobile filter button translations
  - _Requirements: 12.3, 12.4_

- [x] 13.1 Write property test for filter label localization



  - **Property 39: Filter label localization**
  - **Validates: Requirements 12.3**
- [x] 14. Create admin CRUD for attributes




- [ ] 14. Create admin CRUD for attributes

  - [x] 14.1 Create AttributeController for admin


    - Implement index() to list all attributes
    - Implement create() and store() for new attributes
    - Implement edit() and update() for existing attributes
    - Implement destroy() with cascade deletion
    - Validate all fields including multi-language names
    - _Requirements: 6.1, 6.4_

  - [x] 14.2 Create admin views for attributes


    - Create admin.attributes.index view with attribute list
    - Create admin.attributes.create form
    - Create admin.attributes.edit form
    - Display attribute values in index view
    - _Requirements: 6.1_

  - [x] 14.3 Write property test for attribute field persistence


    - **Property 18: Attribute field persistence**
    - **Validates: Requirements 6.1**

  - [x] 14.4 Write property test for attribute cascade deletion


    - **Property 21: Attribute cascade deletion**
    - **Validates: Requirements 6.4**
- [x] 15. Create admin CRUD for attribute values




- [ ] 15. Create admin CRUD for attribute values

  - [x] 15.1 Create AttributeValueController for admin


    - Implement index() to list values for an attribute
    - Implement create() and store() for new values
    - Implement edit() and update() for existing values
    - Implement destroy() for value deletion
    - Validate multi-language value fields
    - _Requirements: 6.2_

  - [x] 15.2 Create admin views for attribute values


    - Create admin.attribute-values.index view
    - Create admin.attribute-values.create form
    - Create admin.attribute-values.edit form
    - Show values grouped by attribute
    - _Requirements: 6.2_

  - [x] 15.3 Write property test for attribute value association


    - **Property 19: Attribute value association**
    - **Validates: Requirements 6.2**
-

- [x] 16. Create admin interface for category-attribute assignment



  - [x] 16.1 Create CategoryAttributeController


    - Implement edit() to show attribute assignment form
    - Implement update() to sync category-attribute relationships
    - Load all filterable attributes for selection
    - Show currently assigned attributes
    - _Requirements: 7.1, 7.2, 7.3_

  - [x] 16.2 Create admin view for category attributes


    - Create admin.categories.attributes view
    - Display checkboxes for all available attributes
    - Pre-check currently assigned attributes
    - Add save button to update assignments
    - _Requirements: 7.1_

  - [x] 16.3 Write property test for attribute-category assignment


    - **Property 23: Attribute-category assignment**
    - **Validates: Requirements 7.2**

  - [x] 16.4 Write property test for attribute-category removal


    - **Property 24: Attribute-category removal**
    - **Validates: Requirements 7.3**

  - [x] 16.5 Write property test for multi-category attribute visibility


    - **Property 25: Multi-category attribute visibility**
    - **Validates: Requirements 7.5**
-

- [x] 17. Update admin category CRUD



  - [x] 17.1 Update CategoryController for admin


    - Add icon and position fields to create/edit forms
    - Update validation rules for new fields
    - Add link to attribute assignment page from category list
    - Implement category deletion constraint (check for products)
    - _Requirements: 5.1, 5.5_

  - [x] 17.2 Update admin category views


    - Add icon upload field to category forms
    - Add position input field
    - Display icon in category list
    - Show "Manage Attributes" link for each category
    - _Requirements: 5.1_

  - [x] 17.3 Write property test for category field persistence


    - **Property 14: Category field persistence**
    - **Validates: Requirements 5.1**

  - [x] 17.4 Write property test for category ordering


    - **Property 16: Category ordering**
    - **Validates: Requirements 5.3**

  - [x] 17.5 Write property test for category deletion constraint


    - **Property 17: Category deletion constraint**
    - **Validates: Requirements 5.5**

- [x] 18. Update admin product CRUD for attribute assignment



  - [x] 18.1 Update ProductController for admin


    - Load category-specific attributes in create/edit forms
    - Display attribute selection UI based on product category
    - Validate selected attribute values belong to category attributes
    - Sync product-attribute relationships on save
    - Update attribute list dynamically when category changes (AJAX)
    - _Requirements: 8.1, 8.2, 8.3, 8.4_

  - [x] 18.2 Update admin product views


    - Add attribute selection section to product forms
    - Group attributes by type for better UX
    - Show attribute values as checkboxes or select inputs
    - Add JavaScript for dynamic attribute loading on category change
    - _Requirements: 8.1, 8.4_

  - [x] 18.3 Write property test for product attribute relevance


    - **Property 26: Product attribute relevance**
    - **Validates: Requirements 8.1**

  - [x] 18.4 Write property test for product attribute value assignment

    - **Property 27: Product attribute value assignment**
    - **Validates: Requirements 8.2**

  - [x] 18.5 Write property test for product attribute validation

    - **Property 28: Product attribute validation**
    - **Validates: Requirements 8.3**

  - [x] 18.6 Write property test for dynamic attribute loading

    - **Property 29: Dynamic attribute loading on category change**
    - **Validates: Requirements 8.4**

  - [x] 18.7 Write property test for attribute filter matching

    - **Property 30: Attribute filter matching**
    - **Validates: Requirements 8.5**

- [x] 19. Add Strong Offers fields to admin product forms






  - [x] 19.1 Update admin product forms

    - Add "Strong Offer" checkbox to product create/edit forms
    - Add discount_percentage input field
    - Validate discount_percentage is between 0 and 100
    - Update ProductController validation rules
    - _Requirements: 9.1, 9.2, 9.3_


  - [x] 19.2 Write property test for strong offer field update

    - **Property 31: Strong offer field update**
    - **Validates: Requirements 9.2**


  - [ ] 19.3 Write property test for discount percentage validation
    - **Property 32: Discount percentage validation**
    - **Validates: Requirements 9.3**


  - [ ] 19.4 Write property test for strong offer filter inclusion
    - **Property 33: Strong offer filter inclusion**

    - **Validates: Requirements 9.4**

  - [ ] 19.5 Write property test for strong offer filter exclusion
    - **Property 34: Strong offer filter exclusion**
    - **Validates: Requirements 9.5**


- [x] 20. Add database indexes for filter performance




  - Add index on products.is_strong_offer
  - Add composite index on products (category_id, is_active)
  - Add index on attribute_category (category_id)
  - Add index on product_attribute_values (attribute_value_id)
  - Add index on categories (parent_id, is_active, position)


  - _Requirements: 10.4_

- [x] 21. Write property test for filter URL format consistency



  - **Property 35: Filter URL format consistency**
  - **Validates: Requirements 10.1**

- [ ] 22. Write property tests for localization
  - [ ] 22.1 Write property test for category name localization
    - **Property 37: Category name localization**
    - **Validates: Requirements 12.1**

  - [ ] 22.2 Write property test for attribute localization
    - **Property 38: Attribute localization**


    - **Validates: Requirements 12.2**

- [x] 23. Create database seeders for testing



  - Create CategorySeeder with parent/child categories
  - Create AttributeSeeder with various attribute types
  - Create AttributeValueSeeder with values for each attribute
  - Create ProductSeeder with strong offers and attribute assignments
  - Seed attribute_category relationships
  - Seed product_attribute_values relationships
  - _Requirements: All_



- [x] 24. Checkpoint - Ensure all tests pass





 - Ensure all tests pass, ask the user if questions arise.

- [x] 25. Create documentation



  - Document filter URL parameter format
  - Document how to configure categories and attributes from admin
  - Document database schema changes
  - Create admin user guide for category/attribute management
  - Document ProductFilterService API




 - _Requirements: All_

- [ ] 26. Final checkpoint - Ensure all tests pass

  - Ensure all tests pass, ask the user if questions arise.
