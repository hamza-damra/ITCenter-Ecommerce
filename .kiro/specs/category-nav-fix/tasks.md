# Implementation Plan

- [x] 1. Update Route Definition for 3-Level Category Hierarchy





  - [x] 1.1 Modify the category.show route in routes/web.php to accept optional subChildSlug parameter


    - Update route pattern from `/category/{parentSlug}/{childSlug?}` to `/category/{parentSlug}/{childSlug?}/{subChildSlug?}`
    - _Requirements: 1.1, 1.2, 1.3, 7.1_

- [x] 2. Enhance Category Model with Hierarchy Methods





  - [x] 2.1 Add descendants() method to Category model


    - Implement recursive method to get all child and sub-child categories
    - Return Collection of descendant Category models
    - _Requirements: 2.1, 2.2_
  - [x] 2.2 Add getUrlAttribute() accessor to Category model


    - Generate the full URL path based on category's position in hierarchy
    - Include parent slugs for child and sub-child categories
    - _Requirements: 4.1, 4.2, 7.3_


  - [x] 2.3 Add ancestors() method to Category model

    - Return Collection of parent categories up to root
    - Used for breadcrumb generation
    - _Requirements: 5.1, 5.2, 5.3_
  - [x] 2.4 Write property test for Category hierarchy methods


    - **Property 1: Category Hierarchy Resolution**
    - **Validates: Requirements 1.1, 1.2, 1.3**

- [x] 3. Fix CategoryController loadCategory Method





  - [x] 3.1 Update loadCategory() to handle 3-level hierarchy


    - Accept parentSlug, childSlug, and subChildSlug parameters
    - Validate parent-child relationships at each level
    - Return 404 for invalid hierarchies or inactive categories
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_
  - [x] 3.2 Add getCategoryWithDescendantIds() method


    - Return array of category IDs including the category and all descendants
    - Used for product fetching across hierarchy
    - _Requirements: 2.1, 2.2, 2.3_
  - [x] 3.3 Write property test for category resolution


    - **Property 2: Invalid Category Returns 404**
    - **Validates: Requirements 1.4**
  - [x] 3.4 Write property test for active category filter


    - **Property 3: Active Category Filter**
    - **Validates: Requirements 1.5**


- [x] 4. Update ProductFilterService for Multi-Category Filtering




  - [x] 4.1 Modify applyCategoryFilter() to accept array of category IDs


    - Change from single category_id filter to whereIn for multiple IDs
    - Support both single Category object and array of IDs
    - _Requirements: 2.1, 2.2, 2.3_
  - [x] 4.2 Write property test for product aggregation


    - **Property 4: Product Aggregation by Category Level**
    - **Validates: Requirements 2.1, 2.2, 2.3**


- [x] 5. Update CategoryController show() Method




  - [x] 5.1 Integrate getCategoryWithDescendantIds() into product query


    - Fetch products from category and all descendants
    - Pass category IDs array to ProductFilterService
    - _Requirements: 2.1, 2.2, 2.3_

  - [x] 5.2 Update buildBreadcrumbs() for 3-level hierarchy
    - Generate correct breadcrumb trail for parent, child, and sub-child
    - Use proper URL patterns for each breadcrumb link
    - _Requirements: 5.1, 5.2, 5.3, 5.4_
  - [x] 5.3 Write property test for breadcrumb structure


    - **Property 11: Breadcrumb Structure**
    - **Validates: Requirements 5.1, 5.2, 5.3**


- [x] 6. Checkpoint - Ensure all tests pass




  - Ensure all tests pass, ask the user if questions arise.


- [x] 7. Verify and Fix Navbar Link Generation




  - [x] 7.1 Audit category-nav.blade.php link generation


    - Verify parent category links use route('category.show', $category->slug)
    - Verify child category links use route('category.show', [$parent->slug, $child->slug])
    - Fix any incorrect link patterns
    - _Requirements: 4.1, 4.2, 4.3_
  - [x] 7.2 Ensure localized category names are used


    - Verify $category->name accessor is used (not name_en directly)
    - Test with different locales
    - _Requirements: 4.4_
  - [x] 7.3 Write property test for navbar link generation


    - **Property 9: Navbar Link Generation**
    - **Validates: Requirements 4.1, 4.2**


- [x] 8. Ensure Tag Filter Integration




  - [x] 8.1 Verify tag filter works with category hierarchy


    - Test tag filter on parent, child, and sub-child category pages
    - Ensure intersection logic (category scope AND tag) works correctly
    - _Requirements: 3.1_
  - [x] 8.2 Verify tag parameter preservation in pagination


    - Check that pagination links include ?tag= parameter
    - Test with multiple pages of results
    - _Requirements: 3.4_

  - [x] 8.3 Write property test for category-tag intersection

    - **Property 6: Category-Tag Intersection Filter**
    - **Validates: Requirements 3.1**
  - [x] 8.4 Write property test for tag pagination preservation


    - **Property 7: Tag Filter Pagination Preservation**
    - **Validates: Requirements 3.4**


- [x] 9. Add Data Integrity Validation




  - [x] 9.1 Add validation for child category parent_id


    - Ensure parent_id points to a valid parent category (parent_id = null)
    - Add validation in Category model or admin controller
    - _Requirements: 6.1_
  - [x] 9.2 Add validation for sub-child category parent_id


    - Ensure parent_id points to a valid child category (not a parent)
    - Prevent creating 4th level categories
    - _Requirements: 6.2_
  - [x] 9.3 Ensure category slug global uniqueness


    - Add unique constraint validation
    - Handle slug conflicts during creation
    - _Requirements: 6.5_
  - [x] 9.4 Write property test for slug uniqueness


    - **Property 13: Category Slug Global Uniqueness**
    - **Validates: Requirements 6.5**

- [x] 10. Final Checkpoint - Ensure all tests pass





  - Ensure all tests pass, ask the user if questions arise.

- [x] 11. Manual Testing and Verification






  - [x] 11.1 Test navbar category navigation

    - Click parent categories and verify correct products load
    - Click child categories from dropdown and verify correct products load
    - Verify breadcrumbs display correctly
    - _Requirements: 4.1, 4.2, 4.5, 5.1, 5.2_
  - [x] 11.2 Test under-banner category navigation


    - Verify under-banner links work identically to navbar links
    - Compare URL patterns between both navigation methods
    - _Requirements: 4.3_
  - [x] 11.3 Test tag filtering with categories


    - Apply tag filter on category page
    - Verify correct intersection results
    - Test pagination with tag filter
    - _Requirements: 3.1, 3.4_
  - [x] 11.4 Test empty category handling


    - Navigate to category with no products
    - Verify "No Products Found" message displays
    - Verify navigation options are available
    - _Requirements: 2.4_
