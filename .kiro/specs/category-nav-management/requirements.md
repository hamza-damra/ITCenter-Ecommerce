# Requirements Document

## Introduction

This feature enhances the existing category system to support dual display modes. Categories can appear either in the image-based carousel (for quick visual browsing) or in the text-based navigation bar (for hierarchical browsing with subcategories). The system leverages the existing parent/child category relationship - parent categories with display_mode 'nav' become navigation bar items, and their children become dropdown menu items.

## Glossary

- **Category_Nav_System**: The navigation management system that controls how categories appear based on their display_mode setting
- **Display_Mode**: The visual presentation style for a category - either 'carousel' (image-based carousel) or 'nav' (text-based navigation bar)
- **Parent_Category**: A root-level category (parent_id = null) that can appear as a nav bar item when display_mode is 'nav'
- **Child_Category**: A subcategory that appears as a dropdown item under its parent in the navigation bar
- **Category_Carousel**: The image-based horizontal scrolling category display component showing categories with display_mode 'carousel'
- **Category_Nav**: The text-based navigation bar showing parent categories with display_mode 'nav' and their children as dropdowns

## Requirements

### Requirement 1

**User Story:** As an administrator, I want to choose how each parent category is displayed, so that I can control whether it appears in the image carousel or the navigation bar.

#### Acceptance Criteria

1. WHEN an administrator creates or edits a parent category THEN the Category_Nav_System SHALL display a display_mode selection field with options 'carousel' and 'nav'
2. WHEN a parent category has display_mode set to 'carousel' THEN the Category_Nav_System SHALL display that category in the category-carousel-wrapper component with its image
3. WHEN a parent category has display_mode set to 'nav' THEN the Category_Nav_System SHALL display that category in the category-nav component as a menu item
4. WHEN display_mode is not specified THEN the Category_Nav_System SHALL default to 'carousel' mode
5. WHEN a category is a child category THEN the Category_Nav_System SHALL inherit display behavior from its parent category

### Requirement 2

**User Story:** As an administrator, I want parent categories in nav mode to show their subcategories as dropdown items, so that visitors can navigate the category hierarchy.

#### Acceptance Criteria

1. WHEN a parent category has display_mode 'nav' and has active child categories THEN the Category_Nav_System SHALL display those children as dropdown menu items
2. WHEN a visitor hovers over a nav category THEN the Category_Nav_System SHALL display a dropdown containing all active child categories
3. WHEN a parent category has display_mode 'nav' and has no children THEN the Category_Nav_System SHALL display it as a direct link without dropdown
4. WHEN child categories exist THEN the Category_Nav_System SHALL order them by their position field

### Requirement 3

**User Story:** As an administrator, I want to set icons and positions for nav categories, so that I can customize the navigation bar appearance.

#### Acceptance Criteria

1. WHEN an administrator edits a category with display_mode 'nav' THEN the Category_Nav_System SHALL allow setting a FontAwesome icon class
2. WHEN an administrator sets a position value THEN the Category_Nav_System SHALL use that value to order categories in the navigation bar
3. WHEN multiple categories have the same position THEN the Category_Nav_System SHALL order them alphabetically by name

### Requirement 4

**User Story:** As a visitor, I want to see both the category carousel and navigation bar, so that I can browse categories in my preferred way.

#### Acceptance Criteria

1. WHEN a visitor views the homepage THEN the Category_Nav_System SHALL display the category-carousel with all active categories having display_mode 'carousel'
2. WHEN a visitor views the homepage THEN the Category_Nav_System SHALL display the category-nav with all active parent categories having display_mode 'nav'
3. WHEN a visitor clicks on a carousel category THEN the Category_Nav_System SHALL navigate to that category's product listing page
4. WHEN a visitor clicks on a nav dropdown item THEN the Category_Nav_System SHALL navigate to that subcategory's product listing page

### Requirement 5

**User Story:** As an administrator, I want to see a visual indicator of display mode in the categories list, so that I can quickly identify how each category is displayed.

#### Acceptance Criteria

1. WHEN an administrator views the categories list THEN the Category_Nav_System SHALL display a badge indicating the display_mode for each parent category
2. WHEN a category has display_mode 'nav' THEN the Category_Nav_System SHALL show a "Nav Bar" badge with distinct styling
3. WHEN a category has display_mode 'carousel' THEN the Category_Nav_System SHALL show a "Carousel" badge with distinct styling
4. WHEN filtering categories THEN the Category_Nav_System SHALL allow filtering by display_mode
