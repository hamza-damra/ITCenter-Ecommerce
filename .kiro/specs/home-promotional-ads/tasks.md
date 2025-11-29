# Implementation Plan

- [x] 1. Create database migration and model





  - [x] 1.1 Create migration for promotional_ads table


    - Create migration file with fields: image_path, position (enum: left, right), link, is_active, timestamps
    - Add index on position and is_active columns
    - _Requirements: 8.1_
  - [x] 1.2 Create PromotionalAd model with fillable fields and casts


    - Define fillable array with all fields
    - Add boolean cast for is_active
    - _Requirements: 8.1_
  - [x] 1.3 Add accessor methods and scopes to PromotionalAd model


    - Implement getImageUrlAttribute() for full URL generation
    - Implement scopeActive() for filtering active ads
    - Implement scopeForPosition($position) for position filtering
    - _Requirements: 5.1, 5.2_
  - [x] 1.4 Write property test for active status filtering


    - **Property 4: Active Status Filtering**
    - **Validates: Requirements 3.4, 5.1**
  - [x] 1.5 Write property test for position assignment


    - **Property 5: Position Assignment**
    - **Validates: Requirements 2.4, 8.4**
-

- [x] 2. Implement admin promotional ad controller with CRUD operations


  - [x] 2.1 Create PromotionalAdController with index method


    - List all promotional ads ordered by position and updated_at
    - _Requirements: 6.2_

  - [x] 2.2 Implement create and store methods

    - Show create form with image upload, position select, link input, active checkbox
    - Validate file type (jpg, jpeg, png, gif, webp)
    - Validate position is 'left' or 'right'
    - Generate unique filename and store in public/storage/promotional-ads/
    - _Requirements: 1.1, 1.2, 7.1, 7.2, 7.3, 8.2, 8.3_

  - [x] 2.3 Write property test for file type validation

    - **Property 1: File Type Validation**
    - **Validates: Requirements 1.2, 7.1**

  - [x] 2.4 Write property test for secure file storage

    - **Property 2: Secure File Storage**
    - **Validates: Requirements 7.2, 7.3, 7.4**
  - [x] 2.5 Write property test for position validation

    - **Property 9: Position Validation**
    - **Validates: Requirements 8.3**

  - [x] 2.6 Write property test for image required validation
    - **Property 10: Image Required for New Ads**
    - **Validates: Requirements 8.2**
  - [x] 2.7 Implement edit and update methods


    - Show edit form with current ad data
    - Handle image replacement when new file uploaded
    - Preserve existing image when no new file provided
    - _Requirements: 3.1, 3.2, 3.3_
  - [x] 2.8 Write property test for image update invariant


    - **Property 3: Image Update Invariant**
    - **Validates: Requirements 3.2, 3.3**
  - [x] 2.9 Implement destroy method

    - Delete database record
    - Remove associated image file from storage
    - _Requirements: 4.2_

  - [x] 2.10 Write property test for deletion cleanup

    - **Property 8: Deletion Cleanup**
    - **Validates: Requirements 4.2**


- [x] 3. Create admin views for promotional ad management




  - [x] 3.1 Create promotional ads index view


    - Display ad list in table format with image thumbnail, position, link, status
    - Add action buttons for edit and delete
    - Include confirmation modal for delete action
    - Follow existing admin layout patterns
    - _Requirements: 4.1_

  - [x] 3.2 Create promotional ad create view

    - Form with file upload for image
    - Select dropdown for position (left/right)
    - Input for link URL
    - Checkbox for active status
    - _Requirements: 2.1, 2.2_

  - [x] 3.3 Create promotional ad edit view

    - Pre-populate form with existing ad data
    - Show current image with option to replace
    - Same fields as create view
    - _Requirements: 3.1_
-

- [x] 4. Configure routes and navigation


  - [x] 4.1 Add promotional ad resource routes to admin route group


    - Register routes under admin middleware group
    - Use existing auth and admin middleware
    - _Requirements: 6.3_

  - [x] 4.2 Add promotional ads management link to admin sidebar
    - Add menu item in admin layout sidebar
    - Use appropriate icon (fa-ad or fa-bullhorn)
    - _Requirements: 6.2_

  - [x] 4.3 Write property test for authorization enforcement


    - **Property 7: Authorization Enforcement**
    - **Validates: Requirements 6.1, 6.2**


- [x] 5. Checkpoint - Ensure all tests pass




  - Ensure all tests pass, ask the user if questions arise.
-

- [x] 6. Integrate promotional ads with home page




  - [x] 6.1 Update HomeController to fetch active promotional ads


    - Query active promotional ads grouped by position
    - Pass promotional ads collection to home view
    - _Requirements: 5.1, 5.2_
  - [x] 6.2 Update home view to use dynamic promotional ads


    - Replace static Strong Offers banner with dynamic ad from database
    - Replace static International Gift Store banner with dynamic ad from database
    - Maintain same dimensions and styling (class: product-item-section gift-idea-banner)
    - Handle missing ads gracefully (hide section if no active ad for position)
    - Make ads clickable with configured link
    - _Requirements: 2.3, 5.1, 5.2, 5.3, 5.4, 5.5_
  - [x] 6.3 Write property test for clickable link rendering


    - **Property 6: Clickable Link Rendering**
    - **Validates: Requirements 2.3, 5.3**

- [x] 7. Add translation strings






  - [x] 7.1 Add promotional ad related translations to language files

    - Add strings to lang/en/messages.php, lang/ar/messages.php, lang/he/messages.php
    - Include: promotional_ads, add_promotional_ad, edit_promotional_ad, position, left, right, etc.
    - _Requirements: 2.1_


- [x] 8. Final Checkpoint - Ensure all tests pass




  - Ensure all tests pass, ask the user if questions arise.
