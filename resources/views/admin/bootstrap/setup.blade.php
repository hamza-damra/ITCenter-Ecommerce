<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Database Setup') }} - Bootstrap Mode</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --light: #f8fafc;
            --dark: #0f172a;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-lg);
        }

        .header-left h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .header-left p {
            opacity: 0.9;
            font-size: 14px;
        }

        .header-right {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .status-item {
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .status-item.success {
            background: #d1fae5;
            border-color: var(--success);
        }

        .status-item.warning {
            background: #fef3c7;
            border-color: var(--warning);
        }

        .status-item.danger {
            background: #fee2e2;
            border-color: var(--danger);
        }

        .status-item-label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
            opacity: 0.7;
        }

        .status-item-value {
            font-size: 18px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .form-input, .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .file-upload {
            border: 2px dashed var(--border);
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: var(--light);
        }

        .file-upload input {
            display: none;
        }

        .file-upload-icon {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
            margin: 16px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--success) 100%);
            transition: width 0.3s;
        }

        .log-panel {
            background: #1e293b;
            color: #e2e8f0;
            padding: 16px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 16px;
        }

        .log-entry {
            margin-bottom: 4px;
        }

        .log-entry.success {
            color: var(--success);
        }

        .log-entry.error {
            color: var(--danger);
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid var(--info);
        }

        .backup-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .backup-item {
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .backup-item-info {
            flex: 1;
        }

        .backup-item-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .backup-item-meta {
            font-size: 12px;
            color: var(--secondary);
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1><i class="fas fa-database"></i> Database Setup</h1>
                <p>Restore your database to return to normal mode</p>
            </div>
            <div class="header-right">
                <button onclick="checkStatus()" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Re-check Status
                </button>
                <a href="{{ route('admin.bootstrap.logout') }}" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-info">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-info" style="background: #fee2e2; color: #991b1b; border-left-color: var(--danger);">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Status Card -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-info-circle"></i> Current Status
            </div>
            <div class="status-grid" id="statusGrid">
                <div class="status-item {{ $stateInfo['state'] === 'missing_db' ? 'warning' : 'danger' }}">
                    <div class="status-item-label">Database State</div>
                    <div class="status-item-value">{{ $stateInfo['state_label'] }}</div>
                </div>
                <div class="status-item info">
                    <div class="status-item-label">Target Database</div>
                    <div class="status-item-value">{{ $stateInfo['database'] ?? 'Not configured' }}</div>
                </div>
                <div class="status-item info">
                    <div class="status-item-label">MySQL Host</div>
                    <div class="status-item-value">{{ $stateInfo['host'] ?? 'Unknown' }}:{{ $stateInfo['port'] ?? '3306' }}</div>
                </div>
            </div>
        </div>

        <!-- Import SQL File -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-file-upload"></i> Import SQL File
            </div>
            <form id="importSqlForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Select SQL File</label>
                    <div class="file-upload" onclick="document.getElementById('sqlFile').click()">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div>
                            <strong>Click to upload</strong> or drag and drop
                            <div style="font-size: 12px; margin-top: 8px; color: var(--secondary);">
                                SQL files only (Max: {{ config('backup.max_upload_size', 512) }}MB)
                            </div>
                        </div>
                        <input type="file" id="sqlFile" name="sql_file" accept=".sql,.txt" required>
                    </div>
                    <div id="sqlFileName" style="margin-top: 12px; font-weight: 600; color: var(--primary);"></div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-database"></i> Import SQL File
                </button>
            </form>
            <div id="importProgress" class="hidden">
                <div class="progress-bar">
                    <div class="progress-fill" id="importProgressFill" style="width: 0%"></div>
                </div>
                <div class="log-panel" id="importLog"></div>
            </div>
        </div>

        <!-- Restore from Backup -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-history"></i> Restore from Backup
            </div>
            @if(count($availableBackups) > 0)
                <div class="backup-list">
                    @foreach($availableBackups as $backup)
                        <div class="backup-item">
                            <div class="backup-item-info">
                                <div class="backup-item-name">{{ $backup['filename'] }}</div>
                                <div class="backup-item-meta">
                                    Size: {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB | 
                                    Created: {{ $backup['created_at'] ?? 'Unknown' }}
                                </div>
                            </div>
                            <button onclick="restoreBackup('{{ $backup['filename'] }}')" class="btn btn-success">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--secondary); text-align: center; padding: 20px;">
                    No backup files found. Upload a backup file below.
                </p>
            @endif

            <div style="margin-top: 24px; border-top: 2px solid var(--border); padding-top: 24px;">
                <label class="form-label">Upload Backup File</label>
                <div class="file-upload" onclick="document.getElementById('backupFile').click()">
                    <div class="file-upload-icon">
                        <i class="fas fa-file-archive"></i>
                    </div>
                    <div>
                        <strong>Click to upload</strong> backup file
                        <div style="font-size: 12px; margin-top: 8px; color: var(--secondary);">
                            SQL, ZIP, or GZ files (Max: {{ config('backup.max_upload_size', 512) }}MB)
                        </div>
                    </div>
                    <input type="file" id="backupFile" name="backup_file" accept=".sql,.zip,.gz,.txt">
                </div>
                <div id="backupFileName" style="margin-top: 12px; font-weight: 600; color: var(--primary);"></div>
                <button onclick="uploadAndRestore()" class="btn btn-success" style="width: 100%; margin-top: 16px;">
                    <i class="fas fa-upload"></i> Upload and Restore
                </button>
            </div>
        </div>

        <!-- Validation Results -->
        <div class="card hidden" id="validationCard">
            <div class="card-title">
                <i class="fas fa-check-circle"></i> Validation Results
            </div>
            <div id="validationResults"></div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // File input handlers
        document.getElementById('sqlFile').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('sqlFileName').textContent = fileName || '';
        });

        document.getElementById('backupFile').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('backupFileName').textContent = fileName || '';
        });

        // Import SQL form
        document.getElementById('importSqlForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            document.getElementById('importProgress').classList.remove('hidden');
            document.getElementById('importLog').innerHTML = '<div class="log-entry">Starting import...</div>';
            updateProgress(10);

            try {
                const response = await fetch('{{ route("admin.bootstrap.import-sql") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();
                updateProgress(100);

                if (data.success) {
                    addLog('Import completed successfully!', 'success');
                    addLog(`Executed ${data.data.statements} statements`, 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.login") }}';
                    }, 2000);
                } else {
                    addLog('Import failed: ' + data.message, 'error');
                }
            } catch (error) {
                addLog('Error: ' + error.message, 'error');
            }
        });

        // Restore backup
        async function restoreBackup(filename) {
            if (!confirm('Are you sure you want to restore from this backup?')) {
                return;
            }

            document.getElementById('importProgress').classList.remove('hidden');
            document.getElementById('importLog').innerHTML = '<div class="log-entry">Starting restore...</div>';
            updateProgress(10);

            try {
                const formData = new FormData();
                formData.append('backup_filename', filename);
                formData.append('_token', csrfToken);

                const response = await fetch('{{ route("admin.bootstrap.restore-backup") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();
                updateProgress(100);

                if (data.success) {
                    addLog('Restore completed successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("admin.login") }}';
                    }, 2000);
                } else {
                    addLog('Restore failed: ' + data.message, 'error');
                }
            } catch (error) {
                addLog('Error: ' + error.message, 'error');
            }
        }

        // Upload and restore
        async function uploadAndRestore() {
            const fileInput = document.getElementById('backupFile');
            if (!fileInput.files[0]) {
                alert('Please select a backup file first');
                return;
            }

            if (!confirm('Are you sure you want to upload and restore this backup?')) {
                return;
            }

            document.getElementById('importProgress').classList.remove('hidden');
            document.getElementById('importLog').innerHTML = '<div class="log-entry">Starting upload and restore...</div>';
            updateProgress(10);

            try {
                const formData = new FormData();
                formData.append('backup_file', fileInput.files[0]);
                formData.append('_token', csrfToken);

                const response = await fetch('{{ route("admin.bootstrap.restore-backup") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();
                updateProgress(100);

                if (data.success) {
                    addLog('Upload and restore completed successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("admin.login") }}';
                    }, 2000);
                } else {
                    addLog('Restore failed: ' + data.message, 'error');
                }
            } catch (error) {
                addLog('Error: ' + error.message, 'error');
            }
        }

        // Check status
        async function checkStatus() {
            try {
                const response = await fetch('{{ route("admin.bootstrap.status") }}');
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Status check failed:', error);
            }
        }

        // Helper functions
        function updateProgress(percent) {
            document.getElementById('importProgressFill').style.width = percent + '%';
        }

        function addLog(message, type = '') {
            const logPanel = document.getElementById('importLog');
            const entry = document.createElement('div');
            entry.className = 'log-entry ' + type;
            entry.textContent = '[' + new Date().toLocaleTimeString() + '] ' + message;
            logPanel.appendChild(entry);
            logPanel.scrollTop = logPanel.scrollHeight;
        }
    </script>
</body>
</html>

