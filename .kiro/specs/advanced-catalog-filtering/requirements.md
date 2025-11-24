# Requirements Document

## Introduction

This feature implements a comprehensive catalog and filtering system for an e-commerce platform. It includes a "Strong Offers" promotional filtering mechanism, a hierarchical category/sub-category structure with dynamic attribute-based filters, and a complete admin interface for managing all aspects of the catalog system. The system must support multi-language content (English, Arabic, Hebrew), RTL/LTR layouts, and maintain backward compatibility with existing functionality.

## Glossary

- **Strong Offers**: Products marked with special promotional pricing that can be filtered and displayed as a collection
- **Product Catalog**: The complete system for organizing, categorizing, and filtering products
- **Category**: A top-level product grouping (e.g., "PC Components", "Peripherals")
- **Sub-category**: A second-level product grouping under a category (e.g., "GPUs", "Monitors")
- **Attribute**: A filterable product characteristic (e.g., "Refresh Rate", "Panel Type")
- **Attribute Value**: A specific option for an attribute (e.g., "144Hz", "IPS")
- **Filter Sidebar**: The UI component displaying available filters for the current product view
- **Admin Panel**: The backend interface for managing categories, attributes, and products
- **Query Parameter**: URL parameters used to maintain filter state (e.g., ?brand[]=ASUS&stock=in)

## Requirements

### Requirement 1

**User Story:** As a customer, I want to view products marked as "Strong Offers" when I click the promotional card, so that I can quickly find the best deals.

#### Acceptance Criteria

1. WHEN a customer clicks the "Shop Now" button on the Strong Offers promotional card THEN the system SHALL navigate to the products listing page with the strong offers filter applied
2. WHEN the strong offers filter is active THEN the system SHALL display only products where is_strong_offer equals true
3. WHEN the products page loads with strong offers filter THEN the system SHALL show the "Strong Offers" checkbox in the filter sidebar as checked
4. WHEN a customer unchecks the Strong Offers filter THEN the system SHALL reload the product listing without the strong offers constraint
5. WHEN strong offers filter is combined with other filters THEN the system SHALL apply all filters simultaneously using AND logic

### Requirement 2

**User Story:** As a customer, I want to browse products by category and sub-category, so that I can find specific types of products I'm interested in.

#### Acceptance Criteria

1. WHEN a customer views the category navigation THEN the system SHALL display all active top-level categories with their icons
2. WHEN a customer hovers over or clicks a top-level category THEN the system SHALL display all active sub-categories for that category
3. WHEN a customer selects a sub-category THEN the system SHALL navigate to a URL with format /category/{parentSlug}/{childSlug}
4. WHEN a customer views a category or sub-category page THEN the system SHALL display a breadcrumb showing the navigation path from Home
5. WHEN a sub-category page loads THEN the system SHALL display only products assigned to that sub-category

### Requirement 3

**User Story:** As a customer, I want to filter products by attributes specific to each sub-category, so that I can narrow down products to my exact specifications.

#### Acceptance Criteria

1. WHEN a customer views a sub-category page THEN the system SHALL display filter groups relevant to that sub-category based on attribute_category mappings
2. WHEN a customer selects attribute filter values THEN the system SHALL update the URL with query parameters in format ?attr[attribute_slug][]=value_slug
3. WHEN attribute filters are applied THEN the system SHALL display only products that have all selected attribute values
4. WHEN a customer views the filter sidebar THEN the system SHALL display product counts next to each filter option showing available products
5. WHEN a customer combines category, brand, stock, price, attribute, and strong offers filters THEN the system SHALL apply all filters correctly and maintain filter state in the URL

### Requirement 4

**User Story:** As a customer, I want to see which filters are currently active, so that I understand why I'm seeing specific products.

#### Acceptance Criteria

1. WHEN filters are applied via URL query parameters THEN the system SHALL render the corresponding checkboxes as checked in the filter sidebar
2. WHEN a customer views the filter sidebar THEN the system SHALL display all filter groups in a clear hierarchical structure
3. WHEN a customer unchecks a filter THEN the system SHALL remove that filter from the URL and reload products
4. WHEN no products match the current filter combination THEN the system SHALL display a message indicating no results found
5. WHEN the filter sidebar is displayed THEN the system SHALL maintain proper RTL or LTR layout based on the current locale

### Requirement 5

**User Story:** As an administrator, I want to create and manage categories and sub-categories, so that I can organize the product catalog structure.

#### Acceptance Criteria

1. WHEN an administrator creates a category THEN the system SHALL store name, slug, parent_id, icon, position, and is_active fields
2. WHEN an administrator sets a parent_id for a category THEN the system SHALL create a sub-category relationship
3. WHEN an administrator updates category position values THEN the system SHALL display categories in the specified order
4. WHEN an administrator sets is_active to false for a category THEN the system SHALL hide that category and its sub-categories from customer-facing pages
5. WHEN an administrator deletes a category THEN the system SHALL prevent deletion if products are assigned to that category or its sub-categories

### Requirement 6

**User Story:** As an administrator, I want to create and manage product attributes and their values, so that I can define filterable characteristics for products.

#### Acceptance Criteria

1. WHEN an administrator creates an attribute THEN the system SHALL store name, slug, type, unit, and is_filterable fields
2. WHEN an administrator creates attribute values THEN the system SHALL associate them with the parent attribute via attribute_id
3. WHEN an administrator sets is_filterable to false for an attribute THEN the system SHALL exclude that attribute from filter sidebars
4. WHEN an administrator deletes an attribute THEN the system SHALL remove all associated attribute values and product associations
5. WHEN an administrator updates an attribute slug THEN the system SHALL maintain existing product associations

### Requirement 7

**User Story:** As an administrator, I want to assign attributes to specific sub-categories, so that each sub-category shows relevant filters.

#### Acceptance Criteria

1. WHEN an administrator selects a sub-category in the admin panel THEN the system SHALL display all available attributes for assignment
2. WHEN an administrator assigns attributes to a sub-category THEN the system SHALL create records in the attribute_category pivot table
3. WHEN an administrator removes an attribute from a sub-category THEN the system SHALL delete the corresponding attribute_category record
4. WHEN a customer views a sub-category page THEN the system SHALL display only filters for attributes assigned to that sub-category
5. WHEN an administrator assigns an attribute to multiple sub-categories THEN the system SHALL display that attribute in filters for all assigned sub-categories

### Requirement 8

**User Story:** As an administrator, I want to assign attribute values to products, so that customers can filter products by those attributes.

#### Acceptance Criteria

1. WHEN an administrator edits a product THEN the system SHALL display attributes relevant to the product's sub-category
2. WHEN an administrator selects attribute values for a product THEN the system SHALL create records in the product_attribute_values pivot table
3. WHEN an administrator saves a product with attribute values THEN the system SHALL validate that selected values belong to the displayed attributes
4. WHEN an administrator changes a product's sub-category THEN the system SHALL display the new sub-category's relevant attributes
5. WHEN a product has attribute values assigned THEN the system SHALL include that product in filter results when those values are selected

### Requirement 9

**User Story:** As an administrator, I want to mark products as "Strong Offers" with optional discount percentages, so that they appear in promotional filtering.

#### Acceptance Criteria

1. WHEN an administrator edits a product THEN the system SHALL display a "Strong Offer" checkbox and discount_percentage field
2. WHEN an administrator checks the Strong Offer checkbox THEN the system SHALL set is_strong_offer to true for that product
3. WHEN an administrator enters a discount_percentage THEN the system SHALL validate it is between 0 and 100
4. WHEN a product has is_strong_offer set to true THEN the system SHALL include it in results when the strong offers filter is applied
5. WHEN an administrator unchecks the Strong Offer checkbox THEN the system SHALL set is_strong_offer to false and exclude the product from strong offers filtering

### Requirement 10

**User Story:** As a developer, I want the filtering system to use clean query parameters and Eloquent scopes, so that the codebase is maintainable and performant.

#### Acceptance Criteria

1. WHEN filters are applied THEN the system SHALL construct query parameters following the format ?brand[]=value&attr[slug][]=value&stock=value&strong_offers=1
2. WHEN the product controller processes filter parameters THEN the system SHALL use Eloquent scopes to build the query
3. WHEN multiple filters are combined THEN the system SHALL use a single optimized database query with appropriate joins
4. WHEN filter queries execute THEN the system SHALL use indexes on frequently filtered columns for performance
5. WHEN pagination is applied with filters THEN the system SHALL maintain all filter parameters in pagination links

### Requirement 11

**User Story:** As a customer, I want the filtering system to work on mobile devices, so that I can filter products on any device.

#### Acceptance Criteria

1. WHEN a customer views the products page on mobile THEN the system SHALL display filters in a drawer or slide-over component
2. WHEN a customer opens the mobile filter drawer THEN the system SHALL display all filter groups in a scrollable interface
3. WHEN a customer applies filters on mobile THEN the system SHALL close the drawer and reload products
4. WHEN a customer views applied filters on mobile THEN the system SHALL display active filter indicators
5. WHEN the filter interface is displayed THEN the system SHALL be fully functional on touch devices

### Requirement 12

**User Story:** As a customer, I want all text in the catalog and filtering system to appear in my selected language, so that I can understand the interface.

#### Acceptance Criteria

1. WHEN category names are displayed THEN the system SHALL show the name in the current locale
2. WHEN attribute names and values are displayed THEN the system SHALL show translations in the current locale
3. WHEN filter labels are displayed THEN the system SHALL use translated strings from language files
4. WHEN the Strong Offers promotional card is displayed THEN the system SHALL show translated title, text, and button label
5. WHEN the locale is Arabic or Hebrew THEN the system SHALL render all catalog and filter UI components in RTL layout
