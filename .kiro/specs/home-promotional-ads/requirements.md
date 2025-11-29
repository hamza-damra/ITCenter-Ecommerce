# Requirements Document

## Introduction

هذه الميزة تحول الإعلانات الترويجية الثابتة في الصفحة الرئيسية (Strong Offers و International Gift Store banners) إلى نظام ديناميكي قابل للإدارة من لوحة التحكم. حالياً، هذه الإعلانات تستخدم صور وروابط ثابتة في الكود. هذه الميزة ستسمح للمسؤولين بتحميل وتعديل وإدارة صور الإعلانات الترويجية من خلال لوحة التحكم، مع الحفاظ على نفس المقاسات والتصميم الحالي.

## Glossary

- **Promotional_Ad**: إعلان ترويجي يُعرض في الصفحة الرئيسية ضمن قسم العروض القوية
- **Promotional_Ad_System**: النظام الكامل لإدارة وعرض الإعلانات الترويجية الديناميكية
- **Admin_Dashboard**: واجهة الإدارة لإدارة منصة التجارة الإلكترونية
- **Home_Page**: الصفحة الرئيسية للموقع
- **Position**: موقع الإعلان (left أو right) في قسم العروض
- **Active_Status**: علامة منطقية تحدد ما إذا كان الإعلان يُعرض علنياً

## Requirements

### Requirement 1

**User Story:** As an administrator, I want to upload promotional ad images for the home page, so that I can update promotional content without modifying code.

#### Acceptance Criteria

1. WHEN an administrator submits a promotional ad upload form with a valid image file THEN the Promotional_Ad_System SHALL store the image in the designated storage path and create a database record
2. WHEN an administrator attempts to upload a file that is not an image (jpg, jpeg, png, gif, webp) THEN the Promotional_Ad_System SHALL reject the upload and display a validation error
3. WHEN a promotional ad is successfully uploaded THEN the Promotional_Ad_System SHALL redirect to the promotional ads list with a success message

### Requirement 2

**User Story:** As an administrator, I want to set position and link for each promotional ad, so that I can control where the ad appears and where it navigates to.

#### Acceptance Criteria

1. WHEN an administrator creates or edits a promotional ad THEN the Promotional_Ad_System SHALL allow selection of position (left or right)
2. WHEN an administrator creates or edits a promotional ad THEN the Promotional_Ad_System SHALL allow input of a URL link for navigation
3. WHEN a promotional ad has a link configured THEN the Home_Page SHALL make the ad image clickable and navigate to the specified URL
4. WHEN the system has two active promotional ads THEN the Promotional_Ad_System SHALL display one in each position (left and right)

### Requirement 3

**User Story:** As an administrator, I want to edit existing promotional ads, so that I can update promotional content without deleting and recreating.

#### Acceptance Criteria

1. WHEN an administrator accesses the edit form for a promotional ad THEN the Promotional_Ad_System SHALL display all current ad data in editable fields
2. WHEN an administrator updates a promotional ad's image file THEN the Promotional_Ad_System SHALL replace the old image with the new one
3. WHEN an administrator updates promotional ad metadata without changing the image THEN the Promotional_Ad_System SHALL preserve the existing image
4. WHEN an administrator toggles the active status THEN the Promotional_Ad_System SHALL update the ad's visibility on the home page

### Requirement 4

**User Story:** As an administrator, I want to delete promotional ads, so that I can remove outdated promotional content.

#### Acceptance Criteria

1. WHEN an administrator requests to delete a promotional ad THEN the Promotional_Ad_System SHALL display a confirmation prompt
2. WHEN an administrator confirms promotional ad deletion THEN the Promotional_Ad_System SHALL remove the database record and the associated image file
3. WHEN a promotional ad is deleted THEN the Promotional_Ad_System SHALL redirect to the promotional ads list with a success message

### Requirement 5

**User Story:** As a website visitor, I want to see dynamic promotional ads on the home page, so that I can discover current offers and navigate to relevant pages.

#### Acceptance Criteria

1. WHEN a visitor loads the home page THEN the Home_Page SHALL fetch and display only active promotional ads from the database
2. WHEN active promotional ads exist THEN the Home_Page SHALL display them in their configured positions (left/right)
3. WHEN a promotional ad has a link configured THEN the Home_Page SHALL wrap the ad in a clickable element
4. WHEN no active promotional ads exist for a position THEN the Home_Page SHALL hide that ad slot gracefully
5. WHEN displaying promotional ads THEN the Home_Page SHALL maintain the same dimensions and styling as the current static ads

### Requirement 6

**User Story:** As a system administrator, I want promotional ad management restricted to admin users, so that unauthorized users cannot modify promotional content.

#### Acceptance Criteria

1. WHEN a non-admin user attempts to access promotional ad management routes THEN the Promotional_Ad_System SHALL redirect to the login page or display an unauthorized error
2. WHEN an admin user accesses promotional ad management THEN the Promotional_Ad_System SHALL allow full CRUD operations
3. WHEN promotional ad management actions are performed THEN the Promotional_Ad_System SHALL use the existing authentication and authorization middleware

### Requirement 7

**User Story:** As a developer, I want secure file upload handling, so that the system is protected from malicious uploads.

#### Acceptance Criteria

1. WHEN a file is uploaded THEN the Promotional_Ad_System SHALL validate the file MIME type matches allowed image types
2. WHEN a file is uploaded THEN the Promotional_Ad_System SHALL generate a unique filename to prevent overwrites and path traversal attacks
3. WHEN a file is uploaded THEN the Promotional_Ad_System SHALL store images in a dedicated promotional-ads directory within public storage
4. WHEN promotional ad data is serialized THEN the Promotional_Ad_System SHALL store the relative file path in the database

### Requirement 8

**User Story:** As a developer, I want promotional ad data to be properly structured, so that the system maintains data integrity.

#### Acceptance Criteria

1. WHEN a promotional ad record is created THEN the Promotional_Ad_System SHALL store image path, position, link, active status, and timestamps
2. WHEN promotional ad data is validated THEN the Promotional_Ad_System SHALL require an image file for new ads
3. WHEN promotional ad data is validated THEN the Promotional_Ad_System SHALL ensure position is either 'left' or 'right'
4. WHEN two promotional ads have the same position THEN the Promotional_Ad_System SHALL use the most recently updated active ad for that position
