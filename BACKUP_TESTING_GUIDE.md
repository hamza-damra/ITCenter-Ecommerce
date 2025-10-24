# Advanced Backup System - Testing Guide

## Quick Start Testing

Navigate to: `http://your-domain/admin/backup`

---

## Test Scenarios

### 1. Export Tests

#### A. Full System Backup
1. Click **"Create Backup Now"** (green button)
2. Select **"Full System Backup"** (first option - purple icon)
3. Click **"Generate Backup"**
4. ✅ Should create `backup_full_YYYY-MM-DD_HH-MM-SS.sql.gz`
5. ✅ Should show success message with filename and size
6. ✅ Should appear in backups table

#### B. Database Only Backup
1. Click **"Create Backup Now"**
2. Select **"Database Only"** (second option - blue icon)
3. Click **"Generate Backup"**
4. ✅ Should create `backup_db_YYYY-MM-DD_HH-MM-SS.sql.gz`
5. ✅ Should show success message
6. ✅ Should appear in backups table

#### C. Module-Specific Backup
1. Click **"Create Backup Now"**
2. Select **"Specific Modules"** (third option - pink icon)
3. ✅ Module selection area should appear
4. Check **2-3 modules** (e.g., Products, Categories, Orders)
5. Click **"Generate Backup"**
6. ✅ Should create `backup_modules_YYYY-MM-DD_HH-MM-SS.sql.gz`
7. ✅ Success message should mention selected modules
8. ✅ Should appear in backups table

#### D. Validation Test
1. Click **"Create Backup Now"**
2. Select **"Specific Modules"**
3. **DO NOT** check any modules
4. Click **"Generate Backup"**
5. ✅ Should show alert: "Please select at least one module"
6. ✅ Should NOT create backup

---

### 2. Import Tests

#### A. Upload Valid Backup
1. Download an existing backup from the table first
2. Click **"Import Backup"** (blue button)
3. Click **"Select File"** or drag the downloaded backup
4. ✅ Should show "Validating file..." message
5. ✅ Should display **File Details** card with:
   - Backup Type
   - Created date
   - Number of tables
   - File size
6. ✅ Confirmation section should appear
7. Check **"I understand..."** checkbox
8. ✅ "Import and Restore" button should enable
9. Click **"Import and Restore"**
10. ✅ Should restore successfully
11. ✅ Should show success message with filename

#### B. Upload Invalid File
1. Create a text file `test.sql` with random content
2. Click **"Import Backup"**
3. Upload the fake file
4. ✅ Should show **"Incompatible backup file"** error
5. ✅ Should display red warning box
6. ✅ Submit button should remain disabled

#### C. Drag & Drop Test
1. Click **"Import Backup"**
2. **Drag** a valid backup file over the upload area
3. ✅ Upload area should highlight (blue border)
4. **Drop** the file
5. ✅ Should validate automatically
6. ✅ Should show file details

#### D. Large File Test
1. Try uploading a file > 512 MB (if available)
2. ✅ Should show error: "File too large. Maximum size: 512 MB"

---

### 3. UI/UX Tests

#### A. Modal Behavior
1. **Export Modal:**
   - ✅ Opens on button click
   - ✅ Closes on X button
   - ✅ Closes on Cancel button
   - ✅ Closes on ESC key
   - ✅ Closes when clicking outside modal

2. **Import Modal:**
   - ✅ Same close behaviors as above
   - ✅ Resets file selection on close
   - ✅ Hides validation messages on close

#### B. RTL Languages
**Test in Arabic:**
1. Change language to Arabic: `/ar/admin/backup`
2. ✅ Page header should be RTL (icon on right, text on left)
3. ✅ Buttons should align to the right
4. ✅ Modal content should be RTL
5. ✅ Module checkboxes should be RTL
6. ✅ All text should be in Arabic

**Test in Hebrew:**
1. Change language to Hebrew: `/he/admin/backup`
2. ✅ Same RTL checks as Arabic
3. ✅ All text should be in Hebrew

---

### 4. Existing Features (Regression Tests)

#### A. Download Backup
1. Click **download icon** (blue) on any backup
2. ✅ Should download the file
3. ✅ Filename should match table

#### B. Restore Backup (Old Modal)
1. Click **restore icon** (orange) on any backup
2. ✅ Old restore modal should open
3. ✅ Should show warning message
4. ✅ Requires checkbox confirmation
5. Check confirmation and submit
6. ✅ Should restore successfully

#### C. Delete Backup
1. Click **delete icon** (red) on any backup
2. ✅ Custom confirmation modal should appear
3. Confirm deletion
4. ✅ Backup should be removed from table
5. ✅ File should be deleted from storage

#### D. Cleanup Old Backups
1. Click **"Cleanup Old Backups"** (orange button)
2. ✅ Confirmation modal should appear
3. Confirm
4. ✅ Should delete backups older than retention policy
5. ✅ Should show count of deleted/kept backups

---

## Expected File Locations

- **Backups stored in:** `storage/app/backups/`
- **Backup naming pattern:** `backup_{type}_{YYYY-MM-DD_HH-MM-SS}.sql.gz`
- **Import uploads saved as:** `import_{YYYY-MM-DD_HH-MM-SS}.{ext}`

---

## Console Checks

### Verify Routes
```bash
php artisan route:list --path=backup
```
✅ Should show 10 routes including new ones:
- `admin.backup.create-with-options`
- `admin.backup.validate-upload`
- `admin.backup.import-and-restore`
- `admin.backup.modules`

### Check Config
```bash
php artisan tinker
>>> config('backup.modules')
```
✅ Should return array of 9 modules with tables and files

### Test Module Endpoint
```bash
curl http://localhost/admin/backup/modules
```
✅ Should return JSON with modules array

---

## Browser Console Tests

### Export Modal - Module Loading
1. Open browser DevTools (F12)
2. Go to **Network** tab
3. Click "Create Backup Now" → Select "Specific Modules"
4. ✅ Should see AJAX request to `/admin/backup/modules`
5. ✅ Response should contain module data
6. ✅ Checkboxes should populate

### Import Modal - File Validation
1. Open DevTools → Network tab
2. Click "Import Backup" → Upload file
3. ✅ Should see POST to `/admin/backup/validate-upload`
4. ✅ Response should contain validation result
5. ✅ UI should update with file details

---

## Error Scenarios

### Test Error Handling

1. **No Modules Selected:**
   - ✅ Alert: "Please select at least one module"

2. **Invalid File Type:**
   - ✅ Error: "Invalid file type. Allowed: sql, gz, zip"

3. **File Too Large:**
   - ✅ Error: "File too large. Maximum size: 512 MB"

4. **Corrupted Backup:**
   - ✅ Error: "The uploaded file is not a valid backup or is corrupted"

5. **Database Error During Restore:**
   - ✅ Should show error message (check logs)
   - ✅ Should NOT corrupt existing data

---

## Performance Checks

1. **Large Backup Creation:**
   - Create full system backup
   - ✅ Should complete without timeout (may take 30s-2min)
   - ✅ File should be compressed (.gz)

2. **Large File Upload:**
   - Upload 100+ MB backup
   - ✅ Should upload without timeout
   - ✅ Validation should complete

3. **Module Loading:**
   - ✅ Module list should load instantly (<200ms)

---

## Multi-Browser Testing

Test on:
- ✅ Chrome/Edge (primary)
- ✅ Firefox
- ✅ Safari (if available)

Check:
- Modal display
- File upload (especially drag & drop)
- AJAX requests
- RTL layout

---

## Security Checks

1. **CSRF Protection:**
   - ✅ All forms should have CSRF token
   - ✅ Requests without token should fail (403)

2. **Access Control:**
   - ✅ Non-admin users should NOT access `/admin/backup`
   - ✅ Should redirect to login

3. **File Validation:**
   - ✅ Cannot upload `.exe`, `.php`, etc.
   - ✅ Size limits enforced

---

## Logging Verification

Check `storage/logs/laravel.log`:

### On Backup Creation:
```
[INFO] Database backup created successfully
- filename: backup_full_2025-01-15_14-30-00.sql.gz
- size: 45678901
- tables: 25
```

### On Import:
```
[INFO] Backup imported and restored successfully
- original_filename: my_backup.sql.gz
- saved_filename: import_2025-01-15_14-35-00.sql.gz
```

### On Errors:
```
[ERROR] Backup creation failed
- error: ...
```

---

## Sign-Off Checklist

- [ ] All export types work (Full, Database, Modules)
- [ ] Import validates files correctly
- [ ] Import restores data successfully
- [ ] All modals open/close properly
- [ ] RTL languages display correctly (AR, HE)
- [ ] All translations present in 3 languages
- [ ] No console errors
- [ ] No PHP errors in logs
- [ ] All routes registered
- [ ] Existing features still work (download, restore, delete)
- [ ] Confirmation modals working
- [ ] File drag & drop working
- [ ] Module selection working
- [ ] Documentation complete

---

## Troubleshooting

### Issue: "Class not found" error
**Solution:** Run `composer dump-autoload`

### Issue: Routes not found
**Solution:** Run `php artisan route:clear`

### Issue: Config not loading
**Solution:** Run `php artisan config:clear`

### Issue: Upload fails silently
**Solution:** Check `php.ini` settings:
- `upload_max_filesize = 512M`
- `post_max_size = 512M`
- `max_execution_time = 300`

### Issue: Module checkboxes not showing
**Solution:** Check browser console for AJAX errors

---

## Next Steps After Testing

1. Test in production-like environment
2. Create sample backups for different scenarios
3. Document restore procedures for team
4. Set up automated backup monitoring
5. Configure cloud backup storage (optional)

---

**Happy Testing! 🚀**
