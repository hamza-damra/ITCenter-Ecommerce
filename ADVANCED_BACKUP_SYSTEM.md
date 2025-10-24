# Advanced Backup & Import/Export System

## Overview

The ITCenter E-commerce platform now includes an **advanced backup management system** with full import/export capabilities. This system allows administrators to create selective backups, upload external backups, and restore data with granular control.

---

## Features

### 1. **Manual Export Wizard (On-Demand Backups)**
Create backups on-demand with two options:

#### A. Full Database Backup
- **Includes:** All database tables
- **Use Case:** Complete database snapshot for disaster recovery
- **File Naming:** `backup_db_YYYY-MM-DD_HH-MM-SS.sql.gz`
- **Note:** Images are stored as URLs in the database, not as physical files

#### B. Specific Modules
- **Includes:** Selected module tables
- **Available Modules:**
  - Products & Inventory
  - Categories & Brands
  - Users & Authentication
  - Orders & Transactions
  - Shopping Cart
  - Wishlist & Favorites
  - Offers & Deals
  - Contact Messages
  - Product Attributes
- **Use Case:** Partial backups for specific features
- **File Naming:** `backup_modules_YYYY-MM-DD_HH-MM-SS.sql.gz`
- **Note:** All modules contain only database tables (images are stored as URLs)

---

## Important Note About Images

⚠️ **This system stores image URLs in the database, not actual image files.**

- Product images, category images, brand images, and offer images are stored as **external URLs** in the database
- The backup system **only backs up the database tables** containing these URLs
- No physical image files are included in any backup type
- When restoring a backup, the image URLs will be restored, and images will continue to load from their external sources

---

### 2. **Import & Restore System**

Upload and restore backups from external sources with built-in validation.

#### Features:
- **Drag & Drop Upload:** Modern file upload interface
- **File Validation:** Automatic format and compatibility checking
- **Metadata Detection:** Reads backup type, date, tables count, modules
- **Safety Confirmations:** Requires explicit user confirmation before restore
- **Format Support:** `.sql`, `.gz`, `.zip`
- **Size Limit:** Configurable (default: 512 MB)

#### Upload Process:
1. Click "Import Backup" button
2. Select or drag backup file
3. System validates file automatically
4. Review file details (type, size, tables, etc.)
5. Confirm restoration (checkbox required)
6. Click "Import and Restore"

---

## Configuration

Located in `config/backup.php`:

### Module Definitions
```php
'modules' => [
    'products' => [
        'name' => 'Products & Inventory',
        'tables' => ['products', 'product_offers', 'product_attributes'],
        'files' => ['public/images/products'],
    ],
    // ... more modules
]
```

### Import Settings
```php
'max_upload_size' => 512, // MB
'allowed_extensions' => ['sql', 'gz', 'zip'],
'include_files_in_full_backup' => false, // Toggle file inclusion
```

---

## Backend Implementation

### New Routes
- `POST /admin/backup/create-with-options` - Create selective backup
- `POST /admin/backup/validate-upload` - Validate uploaded file
- `POST /admin/backup/import-and-restore` - Import and restore
- `GET /admin/backup/modules` - Get available modules

### Controller Methods (`BackupController`)
- `createWithOptions(Request $request)` - Handle wizard backups
- `validateUpload(Request $request)` - AJAX file validation
- `importAndRestore(Request $request)` - Upload + restore flow
- `getModules()` - Return module configuration

### Service Methods (`DatabaseBackupService`)
- `createBackupWithOptions(array $options)` - Selective backup creation
- `validateUploadedBackup($file)` - File validation + metadata extraction
- `importAndRestore($file)` - Complete import/restore workflow
- `getAvailableModules()` - Fetch module config
- `getModuleTables(array $modules)` - Map modules to tables

---

## Backup File Structure

### Metadata Header (in SQL comments)
```sql
-- Advanced Database Backup
-- Type: full|database|modules
-- Generated: 2025-01-15 14:30:00
-- Database: itcenter_ecommerce
-- Tables: 25
-- Modules: products, categories (for module backups)
```

This metadata allows the system to:
- Detect backup type on import
- Validate compatibility
- Display info to users before restore

---

## User Interface

### Export Modal
**Button:** "Create Backup Now" (green button)

**Workflow:**
1. Opens modal with 2 backup type cards (radio selection)
2. If "Specific Modules" selected → shows module checkboxes
3. Validates at least one module selected
4. Submits to create backup
5. Redirects with success/error message

### Import Modal
**Button:** "Import Backup" (blue button)

**Workflow:**
1. Opens upload modal
2. User selects/drags file
3. AJAX validation runs automatically
4. Displays file details or error
5. Shows confirmation checkbox + warning
6. Enables "Import and Restore" button
7. Submits for restoration

### Existing Backups Table
- Download button (blue)
- Restore button (orange) - uses existing modal
- Delete button (red) - with confirmation

---

## Multi-Language Support

All UI text supports **3 languages**:
- **English** (`en`)
- **Arabic** (`ar`) - RTL layout
- **Hebrew** (`he`) - RTL layout

### New Translation Keys (Added to all languages)
```php
'Create Backup Now' => ...
'Import Backup' => ...
'Select Backup Type' => ...
'Full System Backup' => ...
'Database Only' => ...
'Specific Modules' => ...
'Select Modules' => ...
'Generate Backup' => ...
'Upload Backup File' => ...
'File Details' => ...
'Backup Type:' => ...
'Import and Restore' => ...
// ... and more
```

---

## Security Features

1. **CSRF Protection:** All forms use `@csrf` tokens
2. **File Validation:** Type, size, format checks
3. **Confirmation Modals:** Custom modal system for destructive actions
4. **Logging:** All backup/restore actions logged with admin info
5. **Access Control:** Admin-only routes (assumed middleware in place)

---

## RTL Support

The interface fully supports **Right-to-Left** languages:

### CSS Adjustments
```css
[dir="rtl"] .option-content {
    flex-direction: row-reverse;
    border-left: none;
    border-right: 4px solid transparent;
}
```

### Auto-Detection
Uses `app()->getLocale()` to set direction dynamically:
```blade
<div dir="{{ app()->getLocale() === 'ar' || app()->getLocale() === 'he' ? 'rtl' : 'ltr' }}">
```

---

## Testing Checklist

### Export Tests
- ✅ Full system backup creation
- ✅ Database-only backup creation
- ✅ Module-specific backup (single module)
- ✅ Module-specific backup (multiple modules)
- ✅ Validation: No modules selected error

### Import Tests
- ✅ Upload valid `.sql` file
- ✅ Upload valid `.gz` file
- ✅ Upload invalid file (shows error)
- ✅ Upload oversized file (shows error)
- ✅ Metadata extraction (full backup)
- ✅ Metadata extraction (module backup)
- ✅ Restore from uploaded backup
- ✅ Drag & drop file upload

### UI/UX Tests
- ✅ Modal open/close (Export)
- ✅ Modal open/close (Import)
- ✅ Module selection visibility toggle
- ✅ Confirmation checkbox enables button
- ✅ RTL layout (Arabic)
- ✅ RTL layout (Hebrew)
- ✅ Success messages
- ✅ Error messages

---

## Usage Examples

### Creating a Products-Only Backup
1. Click "Create Backup Now"
2. Select "Specific Modules"
3. Check "Products & Inventory"
4. Click "Generate Backup"
5. Download from table when ready

### Importing External Backup
1. Click "Import Backup"
2. Drag `backup_full_2025-01-14.sql.gz` to upload area
3. Review detected metadata:
   - Type: Full backup
   - Tables: 25
   - Size: 45 MB
4. Check confirmation box
5. Click "Import and Restore"

---

## Error Handling

### Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| "Invalid file type" | Wrong extension | Use `.sql`, `.gz`, or `.zip` |
| "File too large" | Exceeds max size | Compress or increase `max_upload_size` config |
| "Incompatible backup" | Different system format | Use backups from this system only |
| "No modules selected" | Module backup with 0 modules | Select at least one module |

---

## File Structure

```
app/
├── Http/Controllers/Admin/
│   └── BackupController.php (updated)
├── Services/
│   └── DatabaseBackupService.php (updated)
└── Providers/
    └── AppServiceProvider.php (custom validator)

config/
└── backup.php (module definitions added)

resources/views/admin/backup/
└── index.blade.php (new modals + JS)

lang/
├── en/messages.php (new translations)
├── ar/messages.php (new translations)
└── he/messages.php (new translations)

routes/
└── web.php (new routes)
```

---

## Future Enhancements (Optional)

1. **Scheduled Selective Backups:** Allow scheduling module-specific backups
2. **Cloud Upload:** Auto-upload to S3/Google Drive
3. **Backup Encryption:** Encrypt backups with password
4. **Incremental Backups:** Only backup changed data
5. **Restore Preview:** Preview what will change before restore
6. **Multi-File Import:** Restore files + database separately

---

## Changelog

### Version 2.0 (Current)
- ✅ Manual export wizard with 3 backup types
- ✅ Module-specific backups (9 modules)
- ✅ Import/upload system with validation
- ✅ Metadata detection in backups
- ✅ Drag & drop file upload
- ✅ Full RTL support (Arabic, Hebrew)
- ✅ Custom confirmation modals
- ✅ Enhanced logging

### Version 1.0 (Original)
- Basic backup creation
- Download existing backups
- Restore from local backups
- Cleanup old backups

---

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Verify config: `config/backup.php`
3. Test file permissions: `storage/app/backups/`
4. Review validation errors in browser console

---

## Credits

**Developed for ITCenter E-commerce Platform**
- Built on Laravel 12
- Supports multi-language (EN, AR, HE)
- RTL-first design
- Admin panel integration

---

**Last Updated:** January 2025
**Version:** 2.0
