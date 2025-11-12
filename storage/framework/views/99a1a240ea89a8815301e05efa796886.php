<?php $__env->startSection('title', __('messages.Database Backup Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" dir="<?php echo e(app()->getLocale() === 'ar' || app()->getLocale() === 'he' ? 'rtl' : 'ltr'); ?>">
    <div class="page-header-content">
        <h1>
            <?php if(app()->getLocale() === 'ar' || app()->getLocale() === 'he'): ?>
                <?php echo e(__('messages.Database Backup Management')); ?> <i class="fas fa-database"></i>
            <?php else: ?>
                <i class="fas fa-database"></i> <?php echo e(__('messages.Database Backup Management')); ?>

            <?php endif; ?>
        </h1>
        <p><?php echo e(__('messages.Create, restore, and manage database backups')); ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo e(route('admin.backup.settings')); ?>" class="btn btn-secondary">
            <i class="fas fa-cog"></i> <?php echo e(__('messages.Settings')); ?>

        </a>
        <button type="button" class="btn btn-success" onclick="showExportModal()">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.Create Backup Now')); ?>

        </button>
        <button type="button" class="btn btn-info" onclick="showImportModal()">
            <i class="fas fa-upload"></i> <?php echo e(__('messages.Import Backup')); ?>

        </button>
        <form method="POST" action="<?php echo e(route('admin.backup.cleanup')); ?>" style="display: inline;" id="cleanupBackupForm">
            <?php echo csrf_field(); ?>
            <button type="button" class="btn btn-warning" onclick="handleCleanupBackups()">
                <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.Cleanup Old Backups')); ?>

            </button>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-database"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo e($statistics['total_backups']); ?></h3>
            <p><?php echo e(__('messages.Total Backups')); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-hdd"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo e($statistics['total_size_formatted']); ?></h3>
            <p><?php echo e(__('messages.Total Size')); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo e($statistics['retention_days']); ?> <?php echo e(__('messages.days')); ?></h3>
            <p><?php echo e(__('messages.Retention Policy')); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo e($statistics['backup_frequency_days']); ?> 
                <?php if($statistics['backup_frequency_days'] == 1): ?>
                    <?php echo e(__('messages.day')); ?>

                <?php elseif($statistics['backup_frequency_days'] == 2): ?>
                    <?php echo e(__('messages.two days')); ?>

                <?php elseif($statistics['backup_frequency_days'] >= 3 && $statistics['backup_frequency_days'] <= 10): ?>
                    <?php echo e(__('messages.days')); ?>

                <?php else: ?>
                    <?php echo e(__('messages.day')); ?>

                <?php endif; ?>
            </h3>
            <p><?php echo e(__('messages.Backup every')); ?></p>
        </div>
    </div>
</div>

<!-- Configuration Info -->
<div class="content-card" style="margin-bottom: 30px;">
    <div class="card-header">
        <h3><i class="fas fa-cog"></i> <?php echo e(__('messages.Backup Configuration')); ?></h3>
    </div>
    <div class="card-body">
        <div class="info-grid">
            <div class="info-item">
                <strong><?php echo e(__('messages.Schedule:')); ?></strong>
                <span><?php echo e(ucfirst($statistics['schedule'])); ?></span>
            </div>
            <div class="info-item">
                <strong><?php echo e(__('messages.Retention Period:')); ?></strong>
                <span><?php echo e($statistics['retention_days']); ?> <?php echo e(__('messages.days')); ?></span>
            </div>
            <div class="info-item">
                <strong><?php echo e(__('messages.Oldest Backup:')); ?></strong>
                <span><?php echo e($statistics['oldest_backup'] ?? __('messages.N/A')); ?></span>
            </div>
            <div class="info-item">
                <strong><?php echo e(__('messages.Newest Backup:')); ?></strong>
                <span><?php echo e($statistics['newest_backup'] ?? __('messages.N/A')); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Backups List -->
<div class="content-card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> <?php echo e(__('messages.Available Backups')); ?> (<?php echo e(count($backups)); ?>)</h3>
    </div>
    <div class="card-body">
        <?php if(count($backups) > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-file"></i> <?php echo e(__('messages.Filename')); ?></th>
                            <th><i class="fas fa-hdd"></i> <?php echo e(__('messages.Size')); ?></th>
                            <th><i class="fas fa-calendar"></i> <?php echo e(__('messages.Created At')); ?></th>
                            <th><i class="fas fa-clock"></i> <?php echo e(__('messages.Age')); ?></th>
                            <th><i class="fas fa-cogs"></i> <?php echo e(__('messages.Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <code style="font-size: 0.85em;"><?php echo e($backup['filename']); ?></code>
                                </td>
                                <td><?php echo e($backup['size_formatted']); ?></td>
                                <td><?php echo e($backup['created_at_formatted']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo e($backup['age_days'] > config('backup.retention_days') ? 'danger' : 'success'); ?>" 
                                          title="<?php echo e($backup['age_days']); ?> <?php echo e(__('messages.days')); ?>">
                                        <?php echo e($backup['age_human'] ?? ($backup['age_days'] . ' ' . __('messages.days ago'))); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- Download -->
                                        <a href="<?php echo e(route('admin.backup.download', $backup['filename'])); ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="<?php echo e(__('messages.Download')); ?>">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <!-- Restore -->
                                        <button type="button" 
                                                class="btn btn-sm btn-warning" 
                                                onclick="showRestoreModal('<?php echo e($backup['filename']); ?>')"
                                                title="<?php echo e(__('messages.Restore')); ?>">
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        <!-- Delete -->
                                        <form method="POST" 
                                              action="<?php echo e(route('admin.backup.delete', $backup['filename'])); ?>" 
                                              style="display: inline;"
                                              class="delete-backup-form"
                                              data-filename="<?php echo e($backup['filename']); ?>"
                                              onsubmit="return false;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger delete-backup-btn" 
                                                    data-filename="<?php echo e($backup['filename']); ?>"
                                                    title="<?php echo e(__('messages.Delete')); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-database" style="font-size: 48px; color: #cbd5e0; margin-bottom: 16px;"></i>
                <p><?php echo e(__('messages.No backups available')); ?></p>
                <p style="color: #64748b; font-size: 14px;"><?php echo e(__('messages.Click "Create Backup" to create your first backup')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Export/Create Backup Modal -->
<div id="exportModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-download"></i> <?php echo e(__('messages.Create Backup Now')); ?></h3>
            <button type="button" class="close-modal" onclick="closeExportModal()">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.backup.create-with-options')); ?>" id="exportForm">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <h4 style="margin-bottom: 20px; color: #1e293b;"><?php echo e(__('messages.Select Backup Type')); ?></h4>
                <p style="color: #64748b; margin-bottom: 24px;"><?php echo e(__('messages.Choose what to include in the backup')); ?></p>
                
                <div class="backup-type-options">
                    <!-- Full Database Backup -->
                    <label class="backup-option-card">
                        <input type="radio" name="type" value="database" checked onchange="handleBackupTypeChange()">
                        <div class="option-content">
                            <div class="option-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="option-details">
                                <h5><?php echo e(__('messages.Full System Backup')); ?></h5>
                                <p><?php echo e(__('messages.All database tables')); ?></p>
                            </div>
                        </div>
                    </label>

                    <!-- Specific Modules -->
                    <label class="backup-option-card">
                        <input type="radio" name="type" value="modules" onchange="handleBackupTypeChange()">
                        <div class="option-content">
                            <div class="option-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="fas fa-puzzle-piece"></i>
                            </div>
                            <div class="option-details">
                                <h5><?php echo e(__('messages.Specific Modules')); ?></h5>
                                <p><?php echo e(__('messages.Select specific modules to backup')); ?></p>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Module Selection (hidden by default) -->
                <div id="moduleSelection" style="display: none; margin-top: 24px;">
                    <h5 style="margin-bottom: 16px; color: #1e293b;"><?php echo e(__('messages.Select Modules')); ?></h5>
                    <p style="color: #64748b; margin-bottom: 16px; font-size: 14px;"><?php echo e(__('messages.Choose the modules you want to backup:')); ?></p>
                    <div class="module-checkboxes" id="moduleCheckboxes">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeExportModal()">
                    <?php echo e(__('messages.Cancel')); ?>

                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-download"></i> <?php echo e(__('messages.Generate Backup')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<!-- Import Backup Modal -->
<div id="importModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-upload"></i> <?php echo e(__('messages.Import Backup')); ?></h3>
            <button type="button" class="close-modal" onclick="closeImportModal()">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.backup.import-and-restore')); ?>" id="importForm" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <h4 style="margin-bottom: 20px; color: #1e293b;"><?php echo e(__('messages.Upload Backup File')); ?></h4>
                <p style="color: #64748b; margin-bottom: 24px;"><?php echo e(__('messages.Choose a backup file to restore')); ?></p>
                
                <!-- File Upload Area -->
                <div class="file-upload-area" id="fileUploadArea">
                    <input type="file" name="backup_file" id="backupFileInput" accept=".sql,.gz,.zip" style="display: none;" onchange="handleFileSelect(event)">
                    <div class="upload-placeholder" onclick="document.getElementById('backupFileInput').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #cbd5e0; margin-bottom: 16px;"></i>
                        <p style="font-weight: 600; margin-bottom: 8px;"><?php echo e(__('messages.Select File')); ?></p>
                        <p style="color: #64748b; font-size: 14px;"><?php echo e(__('messages.or drag and drop')); ?></p>
                        <p style="color: #94a3b8; font-size: 12px; margin-top: 16px;">
                            <?php echo e(__('messages.Maximum file size:')); ?> <?php echo e(config('backup.max_upload_size', 512)); ?> MB<br>
                            <?php echo e(__('messages.Supported formats:')); ?> .sql, .gz, .zip
                        </p>
                    </div>
                </div>

                <!-- File Details (hidden initially) -->
                <div id="fileDetails" style="display: none; margin-top: 24px;">
                    <div class="alert alert-info" style="background: #dbeafe; border-left-color: #3b82f6;">
                        <i class="fas fa-info-circle"></i>
                        <div id="fileDetailsContent"></div>
                    </div>
                </div>

                <!-- Validation Warning -->
                <div id="validationWarning" style="display: none; margin-top: 16px;">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong><?php echo e(__('messages.Incompatible backup file')); ?></strong><br>
                        <span id="validationMessage"></span>
                    </div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="form-group" id="confirmSection" style="margin-top: 24px; display: none;">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong><?php echo e(__('messages.Warning!')); ?></strong><br>
                        <?php echo e(__('messages.This will overwrite all current data')); ?>

                    </div>
                    <label class="checkbox-label">
                        <input type="checkbox" name="confirm" value="1" id="importConfirm">
                        <span><?php echo e(__('messages.I understand that this will replace all current data')); ?></span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()">
                    <?php echo e(__('messages.Cancel')); ?>

                </button>
                <button type="submit" class="btn btn-danger" id="importSubmitBtn" disabled>
                    <i class="fas fa-upload"></i> <?php echo e(__('messages.Import and Restore')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<!-- Restore Modal -->
<div id="restoreModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.Restore Database')); ?></h3>
            <button type="button" class="close-modal" onclick="closeRestoreModal()">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.backup.restore')); ?>" id="restoreForm">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <input type="hidden" name="filename" id="restoreFilename">
                
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong><?php echo e(__('messages.Warning!')); ?></strong><br>
                    <?php echo e(__('messages.This will replace ALL current database data with the backup.')); ?><br>
                    <?php echo e(__('messages.This action cannot be undone. Make sure you have a current backup before proceeding.')); ?>

                </div>

                <p><strong><?php echo e(__('messages.Backup File:')); ?></strong> <code id="restoreFilenameDisplay"></code></p>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="confirm" value="1" required>
                        <span><?php echo e(__('messages.I understand that this will replace all current data')); ?></span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeRestoreModal()">
                    <?php echo e(__('messages.Cancel')); ?>

                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-undo"></i> <?php echo e(__('messages.Restore Database')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-header-content {
    flex: 1;
}

.page-header-content h1 {
    font-size: 28px;
    margin-bottom: 8px;
    color: #1e293b;
}

.page-header-content p {
    color: #64748b;
    font-size: 14px;
}

.page-header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

/* RTL Layout: Title right, Buttons left */
[dir="rtl"] .page-header {
    direction: rtl;
}

[dir="rtl"] .page-header-content h1,
[dir="rtl"] .page-header-content p {
    text-align: right;
}

/* Responsive: Stack on mobile */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .page-header-actions {
        justify-content: flex-start;
    }
    
    [dir="rtl"] .page-header {
        align-items: flex-end;
    }
    
    [dir="rtl"] .page-header-actions {
        justify-content: flex-end;
    }
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 4px;
    color: #1e293b;
}

.stat-content p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.card-header h3 {
    font-size: 18px;
    color: #1e293b;
    margin: 0;
}

.card-body {
    padding: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item strong {
    color: #475569;
    font-size: 13px;
}

.info-item span {
    color: #1e293b;
    font-size: 15px;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f8fafc;
}

.data-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    border-bottom: 2px solid #e2e8f0;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #1e293b;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-info {
    background: #06b6d4;
    color: white;
}

.btn-info:hover {
    background: #0891b2;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-secondary:hover {
    background: #475569;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.badge-success {
    background: #dcfce7;
    color: #15803d;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #dcfce7;
    border-left: 4px solid #10b981;
    color: #15803d;
}

.alert-danger {
    background: #fee2e2;
    border-left: 4px solid #ef4444;
    color: #991b1b;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.2s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background-color: white;
    /* Use small, safe margins so content never exceeds viewport */
    margin: 20px auto;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    /* Ensure the card stays within the viewport; body will scroll */
    max-height: calc(100vh - 40px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive adjustments for small screens */
@media (max-width: 576px) {
    .modal-content {
        margin: 10px auto;
        width: 95%;
        max-height: calc(100vh - 20px);
    }
}

/* Specific styles for import and export modals with larger max-width */
#importModal .modal-content,
#exportModal .modal-content {
    max-width: 700px;
}

/* Ensure import modal body is scrollable even on very small heights */
#importModal .modal-body {
    max-height: calc(100vh - 180px); /* header+footer+safe margins */
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.modal-header h3 {
    margin: 0;
    color: #1e293b;
    font-size: 20px;
}

.close-modal {
    background: none;
    border: none;
    font-size: 28px;
    color: #64748b;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 32px;
    height: 32px;
}

.close-modal:hover {
    color: #1e293b;
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 1;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    flex-shrink: 0;
}

.form-group {
    margin-bottom: 20px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    user-select: none;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* RTL Support for Arabic and Hebrew */
[dir="rtl"] .data-table th,
[dir="rtl"] .data-table td {
    text-align: right;
}

[dir="rtl"] .stat-card {
    flex-direction: row-reverse;
}

[dir="rtl"] .stat-content {
    text-align: right;
}

[dir="rtl"] .stat-content h3 {
    text-align: right;
}

[dir="rtl"] .stat-content p {
    text-align: right;
}

[dir="rtl"] .info-grid {
    direction: rtl;
}

[dir="rtl"] .info-item {
    text-align: right;
}

[dir="rtl"] .card-header h3 {
    text-align: right;
}

[dir="rtl"] .modal-footer {
    flex-direction: row-reverse;
}

[dir="rtl"] .checkbox-label {
    flex-direction: row-reverse;
}

[dir="rtl"] .action-buttons {
    flex-direction: row-reverse;
}

/* Advanced Backup Modals */
.backup-type-options {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.backup-option-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.backup-option-card:hover {
    border-color: #cbd5e0;
    background: #f8fafc;
}

.backup-option-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.backup-option-card input[type="radio"]:checked ~ .option-content {
    border-left: 4px solid #2563eb;
}

.option-content {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-left: 8px;
    border-left: 4px solid transparent;
    transition: all 0.2s;
}

.option-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.option-details h5 {
    margin: 0 0 4px 0;
    color: #1e293b;
    font-size: 16px;
    font-weight: 600;
}

.option-details p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.module-checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 12px;
    max-height: 300px;
    overflow-y: auto;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
}

.module-checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
}

.module-checkbox-item:hover {
    border-color: #cbd5e0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.module-checkbox-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.module-checkbox-item label {
    margin: 0;
    cursor: pointer;
    flex: 1;
}

/* File Upload Area */
.file-upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 48px 24px;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s;
}

.file-upload-area:hover {
    border-color: #94a3b8;
    background: #f1f5f9;
}

.file-upload-area.drag-over {
    border-color: #2563eb;
    background: #dbeafe;
}

.upload-placeholder {
    cursor: pointer;
}

.file-selected {
    border: 2px solid #10b981;
    background: #d1fae5;
}

/* RTL Support for New Components */
[dir="rtl"] .option-content {
    flex-direction: row-reverse;
    padding-left: 0;
    padding-right: 8px;
    border-left: none;
    border-right: 4px solid transparent;
}

[dir="rtl"] .backup-option-card input[type="radio"]:checked ~ .option-content {
    border-left: none;
    border-right: 4px solid #2563eb;
}

[dir="rtl"] .module-checkbox-item {
    flex-direction: row-reverse;
}

/* Button colors */
.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}
</style>

<script>
// Debug: Check if confirmModal is available
console.log('[INIT] Checking confirmModal availability...');
console.log('[INIT] window.confirmModal:', window.confirmModal);
console.log('[INIT] typeof window.confirmModal:', typeof window.confirmModal);

// Wait for confirmModal to be available
function waitForConfirmModal() {
    return new Promise((resolve) => {
        if (window.confirmModal) {
            console.log('[INIT] confirmModal is already available');
            resolve();
            return;
        }
        
        console.log('[INIT] Waiting for confirmModal...');
        const checkInterval = setInterval(() => {
            if (window.confirmModal) {
                console.log('[INIT] confirmModal is now available');
                clearInterval(checkInterval);
                resolve();
            }
        }, 100);
        
        // Timeout after 5 seconds
        setTimeout(() => {
            clearInterval(checkInterval);
            console.error('[INIT] confirmModal timeout - not available after 5 seconds');
            resolve(); // Resolve anyway to prevent hanging
        }, 5000);
    });
}

// Global error handler
window.addEventListener('error', function(event) {
    console.error('[GLOBAL ERROR]', event.error);
});

window.addEventListener('unhandledrejection', function(event) {
    console.error('[UNHANDLED PROMISE REJECTION]', event.reason);
});

function showRestoreModal(filename) {
    document.getElementById('restoreFilename').value = filename;
    document.getElementById('restoreFilenameDisplay').textContent = filename;
    document.getElementById('restoreModal').style.display = 'block';
    // Prevent background page from scrolling while modal is open
    document.body.style.overflow = 'hidden';
}

function closeRestoreModal() {
    document.getElementById('restoreModal').style.display = 'none';
    document.getElementById('restoreForm').reset();
    // Restore page scroll
    document.body.style.overflow = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('restoreModal');
    if (event.target == modal) {
        closeRestoreModal();
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRestoreModal();
    }
});

/**
 * Handle Create Backup with custom confirmation modal
 */
async function handleCreateBackup() {
    const confirmed = await window.confirmModal.show({
        title: '<?php echo e(__('messages.confirm_action')); ?>',
        message: '<?php echo e(__('messages.Create a new backup now?')); ?>',
        confirmText: '<?php echo e(__('messages.execute')); ?>',
        type: 'info',
        confirmButtonType: 'primary'
    });
    
    if (confirmed) {
        document.getElementById('createBackupForm').submit();
    }
}

/**
 * Handle Cleanup Old Backups with custom confirmation modal
 */
async function handleCleanupBackups() {
    console.log('[CLEANUP] Function called');
    
    // Wait for confirmModal to be available
    await waitForConfirmModal();
    
    if (!window.confirmModal) {
        console.error('[CLEANUP] confirmModal is not available!');
        alert('Error: Confirmation system not loaded. Please refresh the page.');
        return;
    }
    
    // Prevent duplicate confirmation modals
    if (window.confirmModal.isOpen) {
        console.warn('[CLEANUP] Confirmation already open, ignoring duplicate call');
        return;
    }

    const confirmed = await window.confirmModal.show({
        title: '<?php echo e(__('messages.confirm_action')); ?>',
        message: '<?php echo e(__('messages.Delete old backups based on retention policy?')); ?>',
        confirmText: '<?php echo e(__('messages.yes_proceed')); ?>',
        type: 'warning',
        confirmButtonType: 'warning'
    });
    
    console.log('[CLEANUP] Confirmation result:', confirmed);
    
    if (confirmed) {
        console.log('[CLEANUP] User confirmed, proceeding with cleanup');
        
        // Show loading state
        const cleanupBtn = document.querySelector('button[onclick="handleCleanupBackups()"]');
        console.log('[CLEANUP] Button found:', cleanupBtn);
        
        const originalHtml = cleanupBtn.innerHTML;
        cleanupBtn.disabled = true;
        cleanupBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__('messages.Processing...')); ?>';
        
        console.log('[CLEANUP] Button state changed to loading');
        
        try {
            const url = '<?php echo e(route('admin.backup.cleanup-ajax')); ?>';
            const csrfToken = '<?php echo e(csrf_token()); ?>';
            
            console.log('[CLEANUP] Making request to:', url);
            console.log('[CLEANUP] CSRF Token:', csrfToken);
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('[CLEANUP] Response status:', response.status, response.statusText);
            console.log('[CLEANUP] Response headers:', [...response.headers.entries()]);
            
            const data = await response.json();
            console.log('[CLEANUP] Response data:', data);
            
            // Restore button state
            cleanupBtn.disabled = false;
            cleanupBtn.innerHTML = originalHtml;
            console.log('[CLEANUP] Button state restored');
            
            if (response.ok && data.success) {
                console.log('[CLEANUP] Success! Showing success modal');
                
                // Show success modal
                await window.confirmModal.show({
                    title: '<?php echo e(__('messages.Success')); ?>',
                    message: data.message,
                    confirmText: '<?php echo e(__('messages.OK')); ?>',
                    type: 'success',
                    confirmButtonType: 'success'
                });
                
                console.log('[CLEANUP] Reloading page...');
                // Reload page to show updated backup list
                window.location.reload();
            } else {
                console.error('[CLEANUP] Error response:', data);
                
                // Show error modal
                await window.confirmModal.show({
                    title: '<?php echo e(__('messages.Error')); ?>',
                    message: data.message || '<?php echo e(__('messages.An error occurred during cleanup')); ?>',
                    confirmText: '<?php echo e(__('messages.OK')); ?>',
                    type: 'danger',
                    confirmButtonType: 'danger'
                });
            }
        } catch (error) {
            console.error('[CLEANUP] Exception caught:', error);
            console.error('[CLEANUP] Error stack:', error.stack);
            
            // Restore button state
            cleanupBtn.disabled = false;
            cleanupBtn.innerHTML = originalHtml;
            
            // Show error modal
            await window.confirmModal.show({
                title: '<?php echo e(__('messages.Error')); ?>',
                message: '<?php echo e(__('messages.Failed to cleanup backups. Please try again.')); ?>',
                confirmText: '<?php echo e(__('messages.OK')); ?>',
                type: 'danger',
                confirmButtonType: 'danger'
            });
        }
    } else {
        console.log('[CLEANUP] User cancelled');
    }
}

/**
 * Handle Delete Backup with custom confirmation modal
 */
document.addEventListener('DOMContentLoaded', async function() {
    console.log('[DELETE] DOM Content Loaded - Initializing delete handlers');
    
    // Wait for confirmModal to be available
    await waitForConfirmModal();
    
    if (!window.confirmModal) {
        console.error('[DELETE] confirmModal is not available! Delete functionality will not work.');
        return;
    }
    
    // Attach event listeners to all delete buttons
    const deleteButtons = document.querySelectorAll('.delete-backup-btn');
    console.log('[DELETE] Found delete buttons:', deleteButtons.length);
    
    deleteButtons.forEach((button, index) => {
        console.log(`[DELETE] Attaching handler to button ${index + 1}`);
        
        button.addEventListener('click', async function(event) {
            console.log('[DELETE] Delete button clicked!');
            console.log('[DELETE] Event:', event);
            console.log('[DELETE] Button:', this);
            
            const filename = this.getAttribute('data-filename');
            console.log('[DELETE] Filename:', filename);
            
            const form = this.closest('form');
            console.log('[DELETE] Form found:', form);
            console.log('[DELETE] Form action:', form?.action);
            
            const row = this.closest('tr');
            console.log('[DELETE] Row found:', row);
            
            console.log('[DELETE] Showing confirmation modal...');
            console.log('[DELETE] confirmModal exists:', !!window.confirmModal);
            
            if (!window.confirmModal) {
                console.error('[DELETE] confirmModal is not available at click time!');
                alert('Error: Confirmation system not loaded. Please refresh the page.');
                return;
            }

            // Prevent duplicate confirmation modals for rapid clicks
            if (this.dataset.confirmOpen === '1') {
                console.warn('[DELETE] Confirmation already open for this button; ignoring duplicate click');
                return;
            }
            this.dataset.confirmOpen = '1';
            
            const confirmed = await window.confirmModal.show({
                title: '<?php echo e(__('messages.confirm_action')); ?>',
                message: '<?php echo e(__('messages.Are you sure you want to delete this backup?')); ?>',
                confirmText: '<?php echo e(__('messages.yes_delete')); ?>',
                type: 'danger',
                confirmButtonType: 'danger'
            });
            // Clear confirm-open guard regardless of result
            delete this.dataset.confirmOpen;
            
            console.log('[DELETE] Confirmation result:', confirmed);
            
            if (confirmed) {
                console.log('[DELETE] User confirmed deletion, proceeding...');
                
                // Show loading state on button
                const originalHtml = this.innerHTML;
                console.log('[DELETE] Original button HTML:', originalHtml);
                
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                console.log('[DELETE] Button changed to loading state');
                
                try {
                    // Get the delete URL from the form action
                    const deleteUrl = form.action;
                    console.log('[DELETE] Delete URL:', deleteUrl);
                    
                    const csrfToken = '<?php echo e(csrf_token()); ?>';
                    console.log('[DELETE] CSRF Token:', csrfToken);
                    
                    console.log('[DELETE] Making DELETE request...');
                    const response = await fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    console.log('[DELETE] Response status:', response.status, response.statusText);
                    console.log('[DELETE] Response headers:', [...response.headers.entries()]);
                    
                    const data = await response.json();
                    console.log('[DELETE] Response data:', data);
                    
                    if (response.ok && data.success) {
                        console.log('[DELETE] Success! Showing success modal');
                        
                        // Show success modal
                        await window.confirmModal.show({
                            title: '<?php echo e(__('messages.Success')); ?>',
                            message: data.message || '<?php echo e(__('messages.Backup deleted successfully')); ?>',
                            confirmText: '<?php echo e(__('messages.OK')); ?>',
                            type: 'success',
                            confirmButtonType: 'success'
                        });
                        
                        console.log('[DELETE] Removing row with animation...');
                        // Remove the row from the table with animation
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            console.log('[DELETE] Row removed');
                            
                            // Check if table is empty and reload if needed
                            const remainingRows = document.querySelectorAll('.data-table tbody tr').length;
                            console.log('[DELETE] Remaining rows:', remainingRows);
                            
                            if (remainingRows === 0) {
                                console.log('[DELETE] No more rows, reloading page...');
                                window.location.reload();
                            }
                        }, 300);
                    } else {
                        console.error('[DELETE] Delete failed:', data);
                        
                        // Show error modal
                        await window.confirmModal.show({
                            title: '<?php echo e(__('messages.Error')); ?>',
                            message: data.message || '<?php echo e(__('messages.Failed to delete backup')); ?>',
                            confirmText: '<?php echo e(__('messages.OK')); ?>',
                            type: 'danger',
                            confirmButtonType: 'danger'
                        });
                        
                        // Restore button state
                        console.log('[DELETE] Restoring button state');
                        this.disabled = false;
                        this.innerHTML = originalHtml;
                    }
                } catch (error) {
                    console.error('[DELETE] Exception caught:', error);
                    console.error('[DELETE] Error stack:', error.stack);
                    
                    // Show error modal
                    await window.confirmModal.show({
                        title: '<?php echo e(__('messages.Error')); ?>',
                        message: '<?php echo e(__('messages.Failed to delete backup. Please try again.')); ?>',
                        confirmText: '<?php echo e(__('messages.OK')); ?>',
                        type: 'danger',
                        confirmButtonType: 'danger'
                    });
                    
                    // Restore button state
                    console.log('[DELETE] Restoring button state after error');
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                }
            } else {
                console.log('[DELETE] User cancelled deletion');
            }
        });
    });
    
    console.log('[DELETE] All delete handlers attached successfully');
});

// ============================================
// ADVANCED BACKUP SYSTEM - EXPORT MODAL
// ============================================

let availableModules = {};

/**
 * Load available modules from backend
 */
async function loadAvailableModules() {
    try {
        const response = await fetch('<?php echo e(route('admin.backup.modules')); ?>');
        const data = await response.json();
        if (data.success) {
            availableModules = data.modules;
            populateModuleCheckboxes();
        }
    } catch (error) {
        console.error('Failed to load modules:', error);
    }
}

/**
 * Populate module checkboxes
 */
function populateModuleCheckboxes() {
    const container = document.getElementById('moduleCheckboxes');
    container.innerHTML = '';
    
    Object.keys(availableModules).forEach(moduleKey => {
        const module = availableModules[moduleKey];
        const div = document.createElement('div');
        div.className = 'module-checkbox-item';
        
        div.innerHTML = `
            <input type="checkbox" name="modules[]" value="${moduleKey}" id="module_${moduleKey}">
            <label for="module_${moduleKey}">
                <strong>${module.name}</strong><br>
                <small style="color: #64748b;">${module.tables.length} tables</small>
            </label>
        `;
        
        container.appendChild(div);
    });
}

/**
 * Show export modal
 */
function showExportModal() {
    loadAvailableModules();
    document.getElementById('exportModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

/**
 * Close export modal
 */
function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
    document.getElementById('exportForm').reset();
    document.getElementById('moduleSelection').style.display = 'none';
    document.body.style.overflow = '';
}

/**
 * Handle backup type change
 */
function handleBackupTypeChange() {
    const type = document.querySelector('input[name="type"]:checked').value;
    const moduleSelection = document.getElementById('moduleSelection');
    
    if (type === 'modules') {
        moduleSelection.style.display = 'block';
    } else {
        moduleSelection.style.display = 'none';
    }
}

/**
 * Validate export form before submission
 */
document.getElementById('exportForm').addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="type"]:checked').value;
    
    if (type === 'modules') {
        const selectedModules = document.querySelectorAll('input[name="modules[]"]:checked');
        if (selectedModules.length === 0) {
            e.preventDefault();
            alert('<?php echo e(__('messages.Please select at least one module')); ?>');
            return false;
        }
    }
});

// ============================================
// ADVANCED BACKUP SYSTEM - IMPORT MODAL
// ============================================

let selectedFile = null;
let validationResult = null;

/**
 * Show import modal
 */
function showImportModal() {
    document.getElementById('importModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

/**
 * Close import modal
 */
function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
    document.getElementById('importForm').reset();
    resetImportModal();
    document.body.style.overflow = '';
}

/**
 * Reset import modal state
 */
function resetImportModal() {
    selectedFile = null;
    validationResult = null;
    document.getElementById('fileDetails').style.display = 'none';
    document.getElementById('validationWarning').style.display = 'none';
    document.getElementById('confirmSection').style.display = 'none';
    document.getElementById('importSubmitBtn').disabled = true;
    document.getElementById('fileUploadArea').classList.remove('file-selected');
}

/**
 * Handle file selection
 */
async function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    selectedFile = file;
    document.getElementById('fileUploadArea').classList.add('file-selected');
    
    // Validate file
    await validateBackupFile(file);
}

/**
 * Validate backup file
 */
async function validateBackupFile(file) {
    const formData = new FormData();
    formData.append('backup_file', file);
    
    try {
        // Show loading
        document.getElementById('fileDetailsContent').innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__('messages.Validating file...')); ?>';
        document.getElementById('fileDetails').style.display = 'block';
        document.getElementById('validationWarning').style.display = 'none';
        
        const response = await fetch('<?php echo e(route('admin.backup.validate-upload')); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            validationResult = data.data;
            displayFileDetails(data.data);
            document.getElementById('confirmSection').style.display = 'block';
        } else {
            showValidationError(data.error);
        }
        
    } catch (error) {
        showValidationError('<?php echo e(__('messages.The uploaded file is not a valid backup or is corrupted')); ?>');
    }
}

/**
 * Display file details
 */
function displayFileDetails(data) {
    const metadata = data.metadata;
    const typeLabels = {
        'database': '<?php echo e(__('messages.Database backup with all tables')); ?>',
        'modules': '<?php echo e(__('messages.Selective module backup')); ?>',
        'unknown': '<?php echo e(__('messages.Unknown')); ?>'
    };
    
    let html = `
        <h5 style="margin-bottom: 12px;"><?php echo e(__('messages.File Details')); ?></h5>
        <div style="display: grid; gap: 8px;">
            <div><strong><?php echo e(__('messages.Backup Type:')); ?></strong> ${typeLabels[metadata.type] || metadata.type}</div>
            ${metadata.date ? `<div><strong><?php echo e(__('messages.Created:')); ?></strong> ${metadata.date}</div>` : ''}
            ${metadata.tables ? `<div><strong><?php echo e(__('messages.Number of Tables:')); ?></strong> ${metadata.tables}</div>` : ''}
            ${metadata.modules && metadata.modules.length > 0 ? `<div><strong><?php echo e(__('messages.Modules Included:')); ?></strong> ${metadata.modules.join(', ')}</div>` : ''}
            <div><strong><?php echo e(__('messages.File Size:')); ?></strong> ${formatBytes(data.size)}</div>
        </div>
    `;
    
    document.getElementById('fileDetailsContent').innerHTML = html;
    document.getElementById('fileDetails').style.display = 'block';
}

/**
 * Show validation error
 */
function showValidationError(message) {
    document.getElementById('fileDetails').style.display = 'none';
    document.getElementById('confirmSection').style.display = 'none';
    document.getElementById('validationMessage').textContent = message + '. <?php echo e(__('messages.Please upload a valid backup file created by this system')); ?>';
    document.getElementById('validationWarning').style.display = 'block';
    document.getElementById('importSubmitBtn').disabled = true;
}

/**
 * Format bytes
 */
function formatBytes(bytes) {
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    let i = 0;
    while (bytes > 1024 && i < units.length - 1) {
        bytes /= 1024;
        i++;
    }
    return Math.round(bytes * 100) / 100 + ' ' + units[i];
}

/**
 * Enable/disable submit button based on confirmation
 */
document.getElementById('importConfirm')?.addEventListener('change', function() {
    document.getElementById('importSubmitBtn').disabled = !this.checked;
});

/**
 * Drag and drop support
 */
const uploadArea = document.getElementById('fileUploadArea');

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
});

uploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('backupFileInput').files = files;
        handleFileSelect({ target: { files: files } });
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.id === 'exportModal') {
        closeExportModal();
    }
    if (event.target.id === 'importModal') {
        closeImportModal();
    }
    if (event.target.id === 'restoreModal') {
        closeRestoreModal();
    }
}

// Close modals on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeExportModal();
        closeImportModal();
        closeRestoreModal();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/admin/backup/index.blade.php ENDPATH**/ ?>