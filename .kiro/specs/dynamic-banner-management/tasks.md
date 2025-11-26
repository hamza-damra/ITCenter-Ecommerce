# Implementation Plan

- [x] 1. Create database migration and model





  - [x] 1.1 Create migration for banners table







    - Create migration file with all fields: image_path, title_en/ar/he, subtitle_en/ar/he, link, button_text_en/ar/he, display_order, is_active, timestamps
    - Add appropriate indexes for display_order and is_active columns
    - _Requirements: 9.1_
  - [x] 1.2 Create Banner model with fillable fields and casts


    - Define fillable array with all banner fields
    - Add boolean cast for is_active, integer cast for display_order
    - _Requirements: 9.1_

  - [x] 1.3 Add locale-aware accessor methods to Banner model
    - Implement getTitleAttribute() with locale fallback to English
    - Implement getSubtitleAttribute() with locale fallback
    - Implement getButtonTextAttribute() with locale fallback
    - Implement getImageUrlAttribute() for full URL generation
    - _Requirements: 9.2_

  - [x] 1.4 Add active scope and ordering scope to Banner model
    - Implement scopeActive() for filtering active banners
    - Implement scopeOrdered() for display_order + created_at sorting
    - _Requirements: 5.2, 6.1_

  - [x] 1.5 Write property test for locale resolution

    - **Property 7: Title Locale Resolution**
    - **Validates: Requirements 2.4, 9.2**

- [x] 2. Implement admin banner controller with CRUD operations



  - [x] 2.1 Create BannerController with index method


    - List all banners with pagination
    - Order by display_order ascending
    - _Requirements: 5.2_

  - [x] 2.2 Implement create and store methods
    - Show create form with all multilingual fields
    - Validate file type (jpg, jpeg, png, gif, webp) and size (max 5MB)
    - Generate unique filename and store in public/storage/banners/
    - Validate at least one title field is filled
    - _Requirements: 1.1, 1.2, 1.3, 8.1, 8.2, 8.3, 9.3_
  - [x] 2.3 Write property test for file type validation


    - **Property 1: File Type Validation**
    - **Validates: Requirements 1.2, 8.1**
  - [x] 2.4 Write property test for unique filename generation

    - **Property 2: Unique Filename Generation**
    - **Validates: Requirements 8.2, 8.3, 8.4**
  - [x] 2.5 Write property test for title validation

    - **Property 10: Title Validation**
    - **Validates: Requirements 9.3**

  - [x] 2.6 Implement edit and update methods
    - Show edit form with current banner data
    - Handle image replacement when new file uploaded
    - Preserve existing image when no new file provided
    - _Requirements: 3.1, 3.2, 3.3_

  - [x] 2.7 Write property test for image update invariant
    - **Property 3: Image Update Invariant**
    - **Validates: Requirements 3.2, 3.3**

  - [x] 2.8 Implement destroy method
    - Delete database record
    - Remove associated image file from storage
    - _Requirements: 4.2_
  - [x] 2.9 Write property test for deletion cleanup


    - **Property 9: Deletion Cleanup**
    - **Validates: Requirements 4.2**
- [x] 3. Create admin views for banner management







- [ ] 3. Create admin views for banner management

  - [x] 3.1 Create banner index view



    - Display banner list in table format with image thumbnail, title, order, status
    - Add action buttons for edit and delete
    - Include confirmation modal for delete action
    - Follow existing admin layout patterns
    - _Requirements: 4.1_

  - [x] 3.2 Create banner create view



    - Form with file upload for image
    - Multilingual input fields for title, subtitle, button text
    - Input for link URL and display order
    - Checkbox for active status
    - _Requirements: 2.1, 2.2, 5.1_

  - [x] 3.3 Create banner edit view

    - Pre-populate form with existing banner data
    - Show current image with option to replace
    - Same fields as create view
    - _Requirements: 3.1_
-

- [x] 4. Configure routes and navigation




  - [x] 4.1 Add banner resource routes to admin route group


    - Register routes under admin middleware group
    - Use existing auth and admin middleware
    - _Requirements: 7.3_
  - [x] 4.2 Add banner management link to admin sidebar


    - Add menu item in admin layout sidebar
    - Use appropriate icon (fa-images)
    - _Requirements: 7.2_
  - [x] 4.3 Write property test for authorization enforcement


    - **Property 8: Authorization Enforcement**
    - **Validates: Requirements 7.1, 7.2**

- [x] 5. Checkpoint - Ensure all tests pass





  - Ensure all tests pass, ask the user if questions arise.

- [x] 6. Integrate banners with home page





  - [x] 6.1 Update HomeController to fetch active banners


    - Query active banners ordered by display_order and created_at
    - Pass banners collection to home view
    - _Requirements: 6.1, 6.2_
  - [x] 6.2 Write property test for active status filtering


    - **Property 4: Active Status Filtering**
    - **Validates: Requirements 3.4, 6.1**
  - [x] 6.3 Write property test for display order sorting


    - **Property 5: Display Order Sorting**
    - **Validates: Requirements 5.2, 5.3, 6.2**
  - [x] 6.4 Update home view hero slider to use dynamic banners


    - Replace static slides with @foreach loop over banners
    - Render banner image as background
    - Display localized title and subtitle
    - Render CTA button with localized text if configured
    - Wrap in anchor tag if link is configured
    - Handle empty banners gracefully (hide section or show placeholder)
    - _Requirements: 2.3, 2.4, 6.1, 6.2, 6.3, 6.4_
  - [x] 6.5 Write property test for clickable link rendering


    - **Property 6: Clickable Link Rendering**
    - **Validates: Requirements 2.3, 6.3**
  - [x] 6.6 Update slider JavaScript for dynamic dot generation


    - Generate slider dots based on banner count
    - Ensure slider functionality works with variable number of slides
    - _Requirements: 6.2_
- [x] 7. Add translation strings








- [ ] 7. Add translation strings


  - [x] 7.1 Add banner-related translations to language files

    - Add strings to lang/en/messages.php, lang/ar/messages.php, lang/he/messages.php
    - Include: banners, banner_management, add_banner, edit_banner, banner_title, banner_subtitle, banner_link, display_order, etc.
    - _Requirements: 2.1_
-


- [x] 8. Final Checkpoint - Ensure all tests pass



  - Ensure all tests pass, ask the user if questions arise.
