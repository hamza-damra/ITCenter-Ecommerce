# Requirements Document

## Introduction

This feature transforms the static hero banner/slider on the public website into a fully dynamic, admin-manageable system. Currently, the home page displays hard-coded banner images (Banner.jpg, wallpaper.png, wallpaper2.png) with static titles and links. This feature will allow administrators to upload, edit, reorder, and manage banner images through the admin dashboard, while the public website dynamically fetches and displays active banners.

## Glossary

- **Banner**: A promotional image displayed in the hero slider section of the home page
- **Banner_System**: The complete system for managing and displaying dynamic banners
- **Admin_Dashboard**: The administrative interface for managing the e-commerce platform
- **Hero_Slider**: The carousel/slider component on the home page that displays banners
- **Display_Order**: A numeric value determining the sequence in which banners appear
- **Active_Status**: A boolean flag indicating whether a banner should be displayed publicly

## Requirements

### Requirement 1

**User Story:** As an administrator, I want to upload new banner images, so that I can add promotional content to the website's hero slider.

#### Acceptance Criteria

1. WHEN an administrator submits a banner upload form with a valid image file THEN the Banner_System SHALL store the image in the designated storage path and create a database record
2. WHEN an administrator attempts to upload a file that is not an image (jpg, jpeg, png, gif, webp) THEN the Banner_System SHALL reject the upload and display a validation error
3. WHEN an administrator attempts to upload an image exceeding 5MB THEN the Banner_System SHALL reject the upload and display a file size error
4. WHEN a banner is successfully uploaded THEN the Banner_System SHALL redirect to the banner list with a success message

### Requirement 2

**User Story:** As an administrator, I want to set title/alt text and optional link for each banner, so that I can provide accessibility and navigation functionality.

#### Acceptance Criteria

1. WHEN an administrator creates or edits a banner THEN the Banner_System SHALL allow input of title text in English, Arabic, and Hebrew
2. WHEN an administrator creates or edits a banner THEN the Banner_System SHALL allow input of an optional URL link
3. WHEN a banner has a link configured THEN the Hero_Slider SHALL make the banner image clickable and navigate to the specified URL
4. WHEN a banner has title text configured THEN the Hero_Slider SHALL display the title as overlay text and use it as alt text for accessibility

### Requirement 3

**User Story:** As an administrator, I want to edit existing banners, so that I can update promotional content without deleting and recreating.

#### Acceptance Criteria

1. WHEN an administrator accesses the edit form for a banner THEN the Banner_System SHALL display all current banner data in editable fields
2. WHEN an administrator updates a banner's image file THEN the Banner_System SHALL replace the old image with the new one
3. WHEN an administrator updates banner metadata without changing the image THEN the Banner_System SHALL preserve the existing image
4. WHEN an administrator toggles the active status THEN the Banner_System SHALL update the banner's visibility on the public website

### Requirement 4

**User Story:** As an administrator, I want to delete banners, so that I can remove outdated promotional content.

#### Acceptance Criteria

1. WHEN an administrator requests to delete a banner THEN the Banner_System SHALL display a confirmation prompt
2. WHEN an administrator confirms banner deletion THEN the Banner_System SHALL remove the database record and the associated image file
3. WHEN a banner is deleted THEN the Banner_System SHALL redirect to the banner list with a success message

### Requirement 5

**User Story:** As an administrator, I want to control the display order of banners, so that I can prioritize certain promotional content.

#### Acceptance Criteria

1. WHEN an administrator creates or edits a banner THEN the Banner_System SHALL allow setting a numeric display order value
2. WHEN the Hero_Slider displays banners THEN the Banner_System SHALL order banners by their display order value in ascending sequence
3. WHEN multiple banners have the same display order THEN the Banner_System SHALL use creation timestamp as secondary sort criteria

### Requirement 6

**User Story:** As a website visitor, I want to see dynamic promotional banners, so that I can discover current offers and navigate to relevant pages.

#### Acceptance Criteria

1. WHEN a visitor loads the home page THEN the Hero_Slider SHALL fetch and display only active banners from the database
2. WHEN active banners exist THEN the Hero_Slider SHALL display them in the configured display order
3. WHEN a banner has a link configured THEN the Hero_Slider SHALL wrap the banner in a clickable anchor element
4. WHEN no active banners exist THEN the Hero_Slider SHALL display a default placeholder or hide the section gracefully

### Requirement 7

**User Story:** As a system administrator, I want banner management restricted to admin users, so that unauthorized users cannot modify promotional content.

#### Acceptance Criteria

1. WHEN a non-admin user attempts to access banner management routes THEN the Banner_System SHALL redirect to the login page or display an unauthorized error
2. WHEN an admin user accesses banner management THEN the Banner_System SHALL allow full CRUD operations
3. WHEN banner management actions are performed THEN the Banner_System SHALL use the existing authentication and authorization middleware

### Requirement 8

**User Story:** As a developer, I want secure file upload handling, so that the system is protected from malicious uploads.

#### Acceptance Criteria

1. WHEN a file is uploaded THEN the Banner_System SHALL validate the file MIME type matches allowed image types
2. WHEN a file is uploaded THEN the Banner_System SHALL generate a unique filename to prevent overwrites and path traversal attacks
3. WHEN a file is uploaded THEN the Banner_System SHALL store images in a dedicated banners directory within public storage
4. WHEN banner data is serialized THEN the Banner_System SHALL store the relative file path in the database

### Requirement 9

**User Story:** As a developer, I want banner data to be properly structured, so that the system maintains data integrity and supports multilingual content.

#### Acceptance Criteria

1. WHEN a banner record is created THEN the Banner_System SHALL store image path, titles (en/ar/he), link, display order, active status, and timestamps
2. WHEN banner data is retrieved THEN the Banner_System SHALL return the appropriate title based on current locale
3. WHEN banner data is validated THEN the Banner_System SHALL require at least one title field to be filled
