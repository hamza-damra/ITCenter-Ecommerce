# 📸 Backup System Visual Reference

## UI Components Overview

### Main Backup Page (`/admin/backup`)
```
┌─────────────────────────────────────────────────────────────┐
│ ITCenter Admin - Backup Management                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  📦 Backup & Restore                                        │
│                                                             │
│  [ Create Backup Now ]  [ Import Backup ]                  │
│                                                             │
│  📋 Available Backups:                                      │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ backup_db_2025-01-24_21-32-37.sql.gz        62 KB     │ │
│  │ 📅 Jan 24, 2025  ⏰ 9:32 PM                           │ │
│  │ [ Download ] [ Delete ] [ Restore ]                   │ │
│  ├───────────────────────────────────────────────────────┤ │
│  │ backup_modules_2025-01-24_21-32-29.sql.gz   39 KB     │ │
│  │ 📅 Jan 24, 2025  ⏰ 9:32 PM                           │ │
│  │ [ Download ] [ Delete ] [ Restore ]                   │ │
│  └───────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

---

## Export Modal (Create Backup Now)

### Step 1: Backup Type Selection
```
┌─────────────────────────────────────────────────┐
│ ✨ Export Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ Select Backup Type:                             │
│                                                 │
│ ○ Database Only                                 │
│   Complete database backup (all tables)         │
│                                                 │
│ ○ Specific Modules                              │
│   Select which parts to backup                  │
│                                                 │
│ [Module Selection Area - Hidden]                │
│                                                 │
│                    [ Cancel ] [ Create Backup ] │
└─────────────────────────────────────────────────┘
```

### Step 2: Module Selection (When "Specific Modules" Selected)
```
┌─────────────────────────────────────────────────┐
│ ✨ Export Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ Select Backup Type:                             │
│                                                 │
│ ○ Database Only                                 │
│   Complete database backup (all tables)         │
│                                                 │
│ ⦿ Specific Modules                              │
│   Select which parts to backup                  │
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │ Select Modules to Backup:                   │ │
│ │                                             │ │
│ │ ☑ Products & Inventory                      │ │
│ │ ☑ Categories & Brands                       │ │
│ │ ☑ Users & Authentication                    │ │
│ │ ☑ Orders & Payments                         │ │
│ │ ☑ Shopping Cart                             │ │
│ │ ☑ User Favorites                            │ │
│ │ ☑ Offers & Promotions                       │ │
│ │ ☑ Contact Messages                          │ │
│ │ ☑ Product Attributes                        │ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│                    [ Cancel ] [ Create Backup ] │
└─────────────────────────────────────────────────┘
```

---

## Import Modal

### Step 1: Empty State
```
┌─────────────────────────────────────────────────┐
│ 📥 Import Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │                                             │ │
│ │         📁                                  │ │
│ │   Drop backup file here                     │ │
│ │   or click to browse                        │ │
│ │                                             │ │
│ │   Accepted formats: .sql.gz, .sql           │ │
│ │   Maximum size: 512 MB                      │ │
│ │                                             │ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│                    [ Cancel ] [Import & Restore]│
└─────────────────────────────────────────────────┘
```

### Step 2: File Dragging
```
┌─────────────────────────────────────────────────┐
│ 📥 Import Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │╔═══════════════════════════════════════════╗│ │
│ │║         📁                                ║│ │
│ │║   Drop backup file here                   ║│ │
│ │║   or click to browse                      ║│ │
│ │║                                           ║│ │
│ │║   Accepted formats: .sql.gz, .sql         ║│ │
│ │║   Maximum size: 512 MB                    ║│ │
│ │║                                           ║│ │
│ │╚═══════════════════════════════════════════╝│ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│                    [ Cancel ] [Import & Restore]│
└─────────────────────────────────────────────────┘
```

### Step 3: File Uploaded (Validating)
```
┌─────────────────────────────────────────────────┐
│ 📥 Import Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │ 📄 backup_db_2025-01-24_21-32-37.sql.gz     │ │
│ │                                             │ │
│ │ ⏳ Validating file...                       │ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│                    [ Cancel ] [Import & Restore]│
└─────────────────────────────────────────────────┘
```

### Step 4: File Validated (Metadata Displayed)
```
┌─────────────────────────────────────────────────┐
│ 📥 Import Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ✅ File validated successfully                  │
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │ 📄 backup_db_2025-01-24_21-32-37.sql.gz     │ │
│ │                                             │ │
│ │ 📊 File Size: 62.5 KB                       │ │
│ │ 🗂️  Backup Type: Database                   │ │
│ │ 📅 Created: Jan 24, 2025 9:32 PM            │ │
│ │ 📋 Tables: 15                               │ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│ ⚠️ This will replace the current database!      │
│                                                 │
│                    [ Cancel ] [Import & Restore]│
└─────────────────────────────────────────────────┘
```

### Step 5: Validation Error
```
┌─────────────────────────────────────────────────┐
│ 📥 Import Backup                          [X]  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ❌ Invalid file                                 │
│                                                 │
│ ┌─────────────────────────────────────────────┐ │
│ │ 📄 document.pdf                             │ │
│ │                                             │ │
│ │ Error: Invalid file format                  │ │
│ │ Only .sql.gz and .sql files are allowed     │ │
│ └─────────────────────────────────────────────┘ │
│                                                 │
│                    [ Cancel ] [Import & Restore]│
└─────────────────────────────────────────────────┘
```

---

## Confirmation Modal (Restore Action)

```
┌─────────────────────────────────────────────────┐
│ ⚠️ Confirm Restore                              │
├─────────────────────────────────────────────────┤
│                                                 │
│ Are you sure you want to restore this backup?   │
│                                                 │
│ This will:                                      │
│ • Replace the current database                  │
│ • Delete all existing data                      │
│ • Cannot be undone                              │
│                                                 │
│ Backup file:                                    │
│ backup_db_2025-01-24_21-32-37.sql.gz            │
│                                                 │
│                      [ Cancel ] [ Yes, Restore ]│
└─────────────────────────────────────────────────┘
```

---

## Multi-Language Layouts

### English (LTR)
```
┌─────────────────────────────────────────────────┐
│ ✨ Export Backup                          [X]  │
├─────────────────────────────────────────────────┤
│ Content aligned left →                          │
│ Buttons: [ Cancel ] [ Create Backup ]           │
└─────────────────────────────────────────────────┘
```

### Arabic (RTL)
```
┌─────────────────────────────────────────────────┐
│  [X]                     ✨ تصدير النسخة الاحتياطية │
├─────────────────────────────────────────────────┤
│                          ← Content aligned right│
│           [ إنشاء نسخة احتياطية ] [ إلغاء ] Buttons│
└─────────────────────────────────────────────────┘
```

### Hebrew (RTL)
```
┌─────────────────────────────────────────────────┐
│  [X]                          ✨ ייצוא גיבוי   │
├─────────────────────────────────────────────────┤
│                          ← Content aligned right│
│                 [ צור גיבוי ] [ ביטול ] Buttons│
└─────────────────────────────────────────────────┘
```

---

## Toast Notifications

### Success
```
┌─────────────────────────────────────┐
│ ✅ Success                          │
│ Backup created successfully         │
│ backup_db_2025-01-24_21-32-37.sql.gz│
└─────────────────────────────────────┘
```

### Error
```
┌─────────────────────────────────────┐
│ ❌ Error                            │
│ Failed to create backup             │
│ Please check disk space             │
└─────────────────────────────────────┘
```

### Warning
```
┌─────────────────────────────────────┐
│ ⚠️ Warning                          │
│ Please select at least one module   │
└─────────────────────────────────────┘
```

### Info
```
┌─────────────────────────────────────┐
│ ℹ️ Info                             │
│ Backup restoration in progress...   │
│ Please wait...                      │
└─────────────────────────────────────┘
```

---

## Test Suite Page (`/test-backup-api.html`)

```
┌─────────────────────────────────────────────────────────────┐
│ 🧪 Backup System API Test Suite                            │
│ This page tests all backup system endpoints                │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Test 1: Get Available Modules              [PASS]      │ │
│ │ Tests the GET /admin/backup/modules endpoint           │ │
│ │ [ Run Test ]                                            │ │
│ │ ┌─────────────────────────────────────────────────────┐ │ │
│ │ │ ✅ SUCCESS                                          │ │ │
│ │ │                                                     │ │ │
│ │ │ Found 9 modules:                                    │ │ │
│ │ │ {                                                   │ │ │
│ │ │   "products": {                                     │ │ │
│ │ │     "name": "Products & Inventory",                 │ │ │
│ │ │     "tables": ["products", "product_offers", ...]   │ │ │
│ │ │   },                                                │ │ │
│ │ │   ...                                               │ │ │
│ │ │ }                                                   │ │ │
│ │ └─────────────────────────────────────────────────────┘ │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Test 2: Create Database Backup             [PASS]      │ │
│ │ [ Run Test ]                                            │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Test 3: Create Modules Backup              [ ]         │ │
│ │ ☑ Products  ☑ Categories  ☑ Users  ☑ Orders           │ │
│ │ ☑ Cart      ☑ Favorites   ☑ Offers ☑ Contacts         │ │
│ │ ☑ Attributes                                            │ │
│ │ [ Run Test ]                                            │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Test 4: Validate Backup File               [ ]         │ │
│ │ [Choose File: backup_db_2025-01-24.sql.gz ]            │ │
│ │ [ Run Test ]                                            │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Test 5: Import and Restore Backup          [ ]         │ │
│ │ ⚠️ WARNING: This will restore the database!            │ │
│ │ [Choose File: No file chosen ]                         │ │
│ │ [ Run Test (Destructive) ]                             │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ 📊 Test Summary                                         │ │
│ │ Total Tests: 5                                          │ │
│ │ Run: 2 / 5                                              │ │
│ │ Passed: 2                                               │ │
│ │ Failed: 0                                               │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

---

## Color Scheme

### Status Colors
- **Success:** Green (#d4edda border, #155724 text)
- **Error:** Red (#f8d7da border, #721c24 text)
- **Warning:** Orange (#fff3cd border, #856404 text)
- **Info:** Blue (#d1ecf1 border, #0c5460 text)

### Button Colors
- **Primary:** Green (#4CAF50)
- **Secondary:** Gray (#6c757d)
- **Danger:** Red (#f44336)
- **Warning:** Orange (#ff9800)

### Modal Backgrounds
- **Overlay:** rgba(0, 0, 0, 0.5) - Semi-transparent black
- **Content:** White (#ffffff)
- **Border:** Light gray (#e0e0e0)

---

## Responsive Breakpoints

### Desktop (1200px+)
- Full width modals (max-width: 600px)
- 3-column module grid
- Side-by-side buttons

### Tablet (768px - 1199px)
- 90% width modals
- 2-column module grid
- Side-by-side buttons

### Mobile (<768px)
- Full width modals
- 1-column module grid
- Stacked buttons

---

## Icons Used

- 📦 Backup/Storage
- 📥 Import/Download
- ✨ Export/Create
- 📁 File/Folder
- 📄 Document
- ✅ Success/Checked
- ❌ Error/Failed
- ⚠️ Warning/Alert
- ℹ️ Information
- ⏳ Loading/Processing
- 📊 Statistics/Data
- 🗂️ Database/Tables
- 📅 Date/Calendar
- ⏰ Time/Clock
- 🧪 Testing/Experiment

---

## Animation States

### Button Hover
```
Normal:    background: #4CAF50
Hover:     background: #45a049
Transition: 0.3s ease
```

### Modal Fade In
```
Initial:   opacity: 0
Final:     opacity: 1
Duration:  0.3s
Easing:    ease-in-out
```

### Drag and Drop Active
```
Normal:    border: 2px dashed #ccc
Drag Over: border: 2px dashed #4CAF50
           background: rgba(76, 175, 80, 0.05)
```

### Loading Spinner
```
Rotation:  360deg
Duration:  1s
Timing:    linear
Infinite:  yes
```

---

## Keyboard Shortcuts

- **Esc:** Close any open modal
- **Enter:** Confirm action in modal (when focused)
- **Tab:** Navigate through form elements
- **Space:** Toggle checkbox (when focused)

---

## Accessibility Features

- **ARIA Labels:** All buttons and inputs have descriptive labels
- **Focus Indicators:** Visible focus states for keyboard navigation
- **Screen Reader Text:** Hidden text for icon-only buttons
- **Contrast Ratios:** WCAG AA compliant color contrasts
- **Keyboard Navigation:** Full keyboard support for all interactions

---

**Visual Reference Complete** ✅
