# Implementation Plan

- [x] 1. Database Migration and Model Updates



  - [x] 1.1 Create migration to add display_mode column to categories table

    - Add display_mode VARCHAR column with default 'carousel'
    - _Requirements: 1.4_

  - [x] 1.2 Update Category model with display_mode field and scopes

    - Add display_mode to fillable array
    - Add scopeCarousel() and scopeNav() query scopes
    - Add getDisplayModeBadgeAttribute() accessor
    - _Requirements: 1.2, 1.3_
  - [x] 1.3 Write property test for default display mode


    - **Property 5: Default Display Mode**
    - **Validates: Requirements 1.4**

- [x] 2. Admin Category Form Updates


  - [x] 2.1 Update category create form with display_mode field


    - Add dropdown select with 'carousel' and 'nav' options
    - Add helpful description text
    - _Requirements: 1.1_
  - [x] 2.2 Update category edit form with display_mode field


    - Add dropdown select with current value selected
    - _Requirements: 1.1_
  - [x] 2.3 Update CategoryController store/update methods


    - Handle display_mode in validation and storage
    - _Requirements: 1.1_


- [x] 3. Admin Categories List Updates

  - [x] 3.1 Add display_mode badge to categories index table


    - Show "Nav Bar" badge for nav mode
    - Show "Carousel" badge for carousel mode
    - _Requirements: 5.1, 5.2, 5.3_
  - [x] 3.2 Add display_mode filter to categories list


    - Add filter dropdown option
    - Update JavaScript filter function
    - _Requirements: 5.4_

- [x] 4. Frontend Component Updates


  - [x] 4.1 Update category-carousel component to filter by display_mode


    - Only show categories with display_mode='carousel'
    - _Requirements: 4.1_
  - [x] 4.2 Update category-nav component to filter by display_mode

    - Only show parent categories with display_mode='nav'
    - Show children as dropdown items
    - _Requirements: 4.2, 2.1, 2.2_
  - [x] 4.3 Write property test for display mode segregation

    - **Property 1: Display Mode Segregation**
    - **Validates: Requirements 1.2, 1.3, 4.1, 4.2**
  - [x] 4.4 Write property test for nav children rendering

    - **Property 2: Nav Children Rendering**
    - **Validates: Requirements 1.5, 2.1**

- [x] 5. HomeController and Data Flow Updates


  - [x] 5.1 Update HomeController to pass filtered categories

    - Pass carousel categories separately
    - Pass nav categories with eager-loaded children
    - _Requirements: 4.1, 4.2_
  - [x] 5.2 Update home view to use filtered category data

    - Pass correct data to each component
    - _Requirements: 4.1, 4.2_
  - [x] 5.3 Write property test for position-based ordering

    - **Property 3: Position-Based Ordering**
    - **Validates: Requirements 2.4, 3.2, 3.3**

- [x] 6. Nav Category Behavior



  - [x] 6.1 Handle childless nav categories as direct links

    - Render without dropdown when no children
    - _Requirements: 2.3_

  - [ ] 6.2 Write property test for childless nav direct link
    - **Property 4: Childless Nav Direct Link**
    - **Validates: Requirements 2.3**


- [x] 7. Translations and Localization


  - [x] 7.1 Add translation keys for display_mode labels

    - Add English, Arabic, Hebrew translations
    - _Requirements: 1.1, 5.1, 5.2, 5.3_


- [x] 8. Checkpoint - Ensure all tests pass

  - Ensure all tests pass, ask the user if questions arise.
