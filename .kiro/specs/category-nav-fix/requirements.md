# Requirements Document

## Introduction

This document specifies the requirements for fixing and standardizing the category and tag navigation system in the IT Center e-commerce application. The system supports hierarchical categories (parent → child → sub-child), a product tagging system (many-to-many), and multiple navigation UI components. Currently, the navbar category dropdown links are broken while the under-banner category links work correctly. This specification addresses the end-to-end fix for category resolution, product fetching, tag integration, and link generation consistency.

## Glossary

- **Category**: A hierarchical classification for products with parent-child relationships (up to 3 levels: parent → child → sub-child)
- **Parent Category**: A top-level category with `parent_id = null`
- **Child Category**: A category with `parent_id` pointing to a parent category
- **Sub-child Category**: A category with `parent_id` pointing to a child category (third level)
- **Tag**: A non-hierarchical label that can be applied to products across categories (many-to-many relationship)
- **Slug**: A URL-friendly identifier for categories and tags (e.g., "gaming-peripherals")
- **Category Nav**: The navigation bar component displaying categories with dropdown submenus
- **Under-banner Categories**: The category carousel/strip displayed below the hero banner on the home page
- **Breadcrumb**: Navigation trail showing the current page's position in the category hierarchy
- **ProductFilterService**: The service class responsible for applying filters to product queries
- **Category Scope**: The set of products belonging to a category and optionally its descendants

## Requirements

### Requirement 1: Route Model Binding and Category Resolution

**User Story:** As a user, I want to click on any category link (parent, child, or sub-child) and be taken to the correct category page, so that I can browse products in that category.

#### Acceptance Criteria

1. WHEN a user navigates to `/category/{parentSlug}` THEN the System SHALL resolve the category by finding a parent category (where `parent_id` is null) with the matching slug
2. WHEN a user navigates to `/category/{parentSlug}/{childSlug}` THEN the System SHALL resolve the child category by first finding the parent category, then finding a child category with the matching slug where `parent_id` equals the parent's ID
3. WHEN a user navigates to `/category/{parentSlug}/{childSlug}/{subChildSlug}` THEN the System SHALL resolve the sub-child category by validating the complete hierarchy chain (parent → child → sub-child)
4. IF a category slug does not exist or the parent-child relationship is invalid THEN the System SHALL return a 404 Not Found response
5. WHEN resolving categories THEN the System SHALL only consider active categories (where `is_active` is true)

### Requirement 2: Unified Product Fetching for Category Pages

**User Story:** As a user, I want to see all relevant products when viewing a category page, so that I can browse the complete product selection for that category.

#### Acceptance Criteria

1. WHEN viewing a parent category page THEN the System SHALL display products from the parent category AND all its descendant categories (children and sub-children)
2. WHEN viewing a child category page THEN the System SHALL display products from that child category AND all its descendant sub-child categories
3. WHEN viewing a sub-child category page THEN the System SHALL display products only from that specific sub-child category
4. WHEN no products exist in the category scope THEN the System SHALL display a "No Products Found" message with navigation options to browse other categories
5. WHEN products are fetched THEN the System SHALL only include active products (where `is_active` is true)

### Requirement 3: Tag Integration with Category Navigation

**User Story:** As a user, I want to filter products by tags while browsing a category, so that I can find products that match both my category interest and specific attributes.

#### Acceptance Criteria

1. WHEN a category is selected AND a tag filter is applied THEN the System SHALL return products that belong to the category scope AND have the specified tag
2. WHEN only a tag filter is applied (no category selected) THEN the System SHALL return products across all categories that have the specified tag
3. WHEN applying tag filters THEN the System SHALL use query string parameters (e.g., `?tag=gaming`) rather than URL path segments
4. WHEN tag filters are applied THEN the System SHALL preserve the tag parameter in pagination links
5. WHEN multiple tags are selected THEN the System SHALL apply AND logic (products must have all selected tags)

### Requirement 4: Navbar Link Generation Consistency

**User Story:** As a user, I want navbar category links to work exactly like the under-banner category links, so that I have a consistent navigation experience throughout the site.

#### Acceptance Criteria

1. WHEN generating navbar links for parent categories THEN the System SHALL use the route pattern `/category/{parentSlug}`
2. WHEN generating navbar links for child categories THEN the System SHALL use the route pattern `/category/{parentSlug}/{childSlug}` with the correct parent slug
3. WHEN generating navbar links THEN the System SHALL use the same route helper (`route('category.show', [...])`) as the under-banner category links
4. WHEN displaying category names in the navbar THEN the System SHALL use the localized name based on current locale (name_en, name_ar, or name_he)
5. WHEN a category has children THEN the System SHALL display a dropdown submenu with correctly linked child categories

### Requirement 5: Breadcrumb Navigation Accuracy

**User Story:** As a user, I want breadcrumbs to accurately reflect my current position in the category hierarchy, so that I can easily navigate back to parent categories.

#### Acceptance Criteria

1. WHEN viewing a parent category page THEN the System SHALL display breadcrumbs as: Home → [Parent Category Name]
2. WHEN viewing a child category page THEN the System SHALL display breadcrumbs as: Home → [Parent Category Name] → [Child Category Name]
3. WHEN viewing a sub-child category page THEN the System SHALL display breadcrumbs as: Home → [Parent Category Name] → [Child Category Name] → [Sub-child Category Name]
4. WHEN clicking a breadcrumb link THEN the System SHALL navigate to the correct category page using the proper URL pattern
5. WHEN displaying breadcrumb names THEN the System SHALL use the localized category name based on current locale

### Requirement 6: Data Integrity Validation

**User Story:** As a system administrator, I want the system to maintain data integrity for category hierarchies, so that navigation always works correctly.

#### Acceptance Criteria

1. WHEN a child category is created or updated THEN the System SHALL validate that `parent_id` points to an existing active parent category
2. WHEN a sub-child category is created or updated THEN the System SHALL validate that `parent_id` points to an existing active child category (not a parent category)
3. WHEN a product is assigned to a category THEN the System SHALL validate that the category exists and is active
4. WHEN a tag is assigned to a product THEN the System SHALL validate that the tag exists and is active
5. WHEN category slugs are generated THEN the System SHALL ensure global uniqueness across all categories

### Requirement 7: URL Pattern Standardization

**User Story:** As a developer, I want a single, consistent URL pattern for category navigation, so that the codebase is maintainable and predictable.

#### Acceptance Criteria

1. THE System SHALL use the URL pattern `/category/{parentSlug}/{childSlug?}/{subChildSlug?}` for all category pages
2. THE System SHALL NOT use query parameters for category selection (e.g., `/products?category=...`)
3. WHEN generating category URLs THEN the System SHALL always include the full hierarchy path (parent slug for children, parent and child slugs for sub-children)
4. THE System SHALL use globally unique slugs for categories to prevent ambiguity
5. WHEN a legacy URL pattern is accessed THEN the System SHALL redirect to the standardized URL pattern with a 301 redirect
