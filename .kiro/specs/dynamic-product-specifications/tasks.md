# Implementation Plan

- [x] 1. Create database migration and model





  - [x] 1.1 Create migration for custom_product_specs table


    - Create migration file with product_id, label_en, label_ar, label_he, value, sort_order columns
    - Add foreign key constraint with cascade delete
    - Add index on product_id and sort_order
    - _Requirements: 6.1, 6.2_
  - [x] 1.2 Create CustomProductSpec model


    - Define fillable attributes
    - Add product() relationship
    - Implement getLabelAttribute() with locale fallback
    - _Requirements: 3.2, 3.3_
  - [ ]* 1.3 Write property test for locale fallback
    - **Property 5: Locale Fallback Behavior**
    - **Validates: Requirements 3.2, 3.3**

- [x] 2. Enhance Product model





  - [x] 2.1 Add customSpecs relationship to Product model


    - Add hasMany relationship ordered by sort_order
    - _Requirements: 4.2_
  - [x] 2.2 Implement syncCustomSpecs method


    - Handle create, update, and delete of specifications
    - Filter out empty specifications
    - Preserve sort order from form submission
    - _Requirements: 1.4, 2.4, 2.5_
  - [x] 2.3 Update getFormattedSpecificationsAttribute


    - Combine template-based specs with custom specs
    - Maintain proper ordering
    - _Requirements: 4.1, 4.2_
  - [ ]* 2.4 Write property test for specification persistence round-trip
    - **Property 1: Specification Persistence Round-Trip**
    - **Validates: Requirements 1.4, 2.1, 2.4**
  - [ ]* 2.5 Write property test for empty specification removal
    - **Property 2: Empty Specification Removal**
    - **Validates: Requirements 2.5**
  - [ ]* 2.6 Write property test for display order preservation
    - **Property 6: Display Order Preservation**
    - **Validates: Requirements 4.2**
  - [ ]* 2.7 Write property test for cascade delete
    - **Property 7: Cascade Delete on Product Removal**
    - **Validates: Requirements 6.2**

- [-] 3. Update ProductRequest validation



  - [x] 3.1 Add validation rules for custom_specs array



    - Add rules for label_en (required_with, max:100)
    - Add rules for label_ar, label_he (nullable, max:100)
    - Add rules for value (required_with, max:500)
    - _Requirements: 1.3, 5.3_
  - [ ]* 3.2 Write property test for label length validation
    - **Property 3: Label Length Validation**
    - **Validates: Requirements 1.3**
  - [ ]* 3.3 Write property test for value length validation
    - **Property 4: Value Length Validation**
    - **Validates: Requirements 1.3**
  - [ ]* 3.4 Write property test for validation error on invalid data
    - **Property 8: Validation Error on Invalid Data**
    - **Validates: Requirements 5.3**

- [x] 4. Checkpoint - Ensure all tests pass





  - Ensure all tests pass, ask the user if questions arise.

- [ ] 5. Update ProductController



  - [ ] 5.1 Update create method to pass empty specs array
    - Initialize customSpecs variable for view
    - _Requirements: 1.1_
  - [ ] 5.2 Update edit method to load existing custom specs
    - Eager load customSpecs relationship
    - Pass specs to view
    - _Requirements: 2.1_
  - [ ] 5.3 Update store method to sync custom specs
    - Extract custom_specs from request
    - Call syncCustomSpecs after product creation
    - _Requirements: 1.4_
  - [ ] 5.4 Update update method to sync custom specs
    - Extract custom_specs from request
    - Call syncCustomSpecs after product update
    - _Requirements: 2.4_

- [ ] 6. Create admin UI components
  - [ ] 6.1 Create specifications card blade partial
    - Create resources/views/admin/products/_specifications-card.blade.php
    - Include section header with icon
    - Add container for specification rows
    - Add "Add Specification" button
    - _Requirements: 1.1, 5.1_
  - [ ] 6.2 Create JavaScript for dynamic row management
    - Create public/js/admin/product-specifications.js
    - Implement addRow() function
    - Implement removeRow() function
    - Implement updateIndexes() function
    - Initialize SortableJS for drag-and-drop
    - _Requirements: 1.2, 1.5, 2.3_
  - [ ] 6.3 Add CSS styles for specifications card
    - Style specification rows
    - Style drag handle
    - Style remove button
    - Add hover effects
    - _Requirements: 5.1, 5.2_

- [ ] 7. Integrate UI into product forms
  - [ ] 7.1 Include specifications card in create form
    - Add @include for specifications card partial
    - Include JavaScript file
    - _Requirements: 1.1_
  - [ ] 7.2 Include specifications card in edit form
    - Add @include for specifications card partial with existing data
    - Include JavaScript file
    - Pre-populate existing specifications
    - _Requirements: 2.1_

- [ ] 8. Add translation strings
  - [ ] 8.1 Add English translation strings
    - Add strings for specifications section title
    - Add strings for add button, placeholders, labels
    - _Requirements: 3.1_
  - [ ] 8.2 Add Arabic translation strings
    - Add Arabic translations for all specification-related strings
    - _Requirements: 3.1_
  - [ ] 8.3 Add Hebrew translation strings
    - Add Hebrew translations for all specification-related strings
    - _Requirements: 3.1_

- [ ] 9. Update product detail page display
  - [ ] 9.1 Update getFormattedSpecificationsAttribute to include custom specs
    - Merge custom specs with template specs
    - Ensure proper ordering
    - _Requirements: 4.1, 4.2, 4.3_

- [ ] 10. Final Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.
