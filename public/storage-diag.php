<?php
/**
 * Storage File Serving Diagnostic Tool
 * Access via: https://itcentre.vip/storage-diag.php?key=itc_diag_2026
 * DELETE THIS FILE after debugging is complete!
 */

$secretKey = 'itc_diag_2026';
if (($_GET['key'] ?? '') !== $secretKey) {
    http_response_code(403);
    echo 'Access denied. Use ?key=' . $secretKey;
    exit;
}

// Test image path (one of the failing images)
$testFile = $_GET['file'] ?? 'products/2026/02/704121b1-5582-40c2-b2a3-3b89afe6eaaf.png';

echo '<html><head><title>Storage Diagnostics</title>';
echo '<style>body{font-family:monospace;padding:20px;background:#1a1a2e;color:#e0e0e0;line-height:1.6;} ';
echo '.ok{color:#00ff88;font-weight:bold;} .err{color:#ff4444;font-weight:bold;} .warn{color:#ffaa00;font-weight:bold;} ';
echo 'h2{color:#8be9fd;border-bottom:1px solid #444;padding-bottom:5px;margin-top:30px;} ';
echo 'pre{background:#2d2d44;padding:10px;border-radius:5px;overflow-x:auto;max-height:300px;overflow-y:auto;}</style></head><body>';
echo '<h1>Storage File Serving Diagnostics</h1>';
echo '<p>Test file: <b>' . htmlspecialchars($testFile) . '</b></p>';

// ==============================
// 1. PHP & Server Environment
// ==============================
echo '<h2>1. PHP & Server Environment</h2>';
echo '<p><b>PHP Version:</b> ' . PHP_VERSION . '</p>';
echo '<p><b>SAPI:</b> ' . php_sapi_name() . '</p>';
echo '<p><b>Server Software:</b> ' . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . '</p>';
echo '<p><b>Document Root:</b> ' . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . '</p>';
echo '<p><b>Script Filename:</b> ' . ($_SERVER['SCRIPT_FILENAME'] ?? 'unknown') . '</p>';
echo '<p><b>Current User:</b> ' . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()) . '</p>';
echo '<p><b>Current UID:</b> ' . (function_exists('posix_geteuid') ? posix_geteuid() : 'N/A') . '</p>';

// open_basedir
$openBasedir = ini_get('open_basedir');
echo '<p><b>open_basedir:</b> ' . ($openBasedir ? '<span class="warn">' . htmlspecialchars($openBasedir) . '</span>' : '<span class="ok">Not set</span>') . '</p>';

// ==============================
// 2. Path Resolution
// ==============================
echo '<h2>2. Path Resolution</h2>';

$thisDir = __DIR__;
$projectRoot = dirname($thisDir); // public_html/ (one level up from public/)
echo '<p><b>__DIR__ (this script):</b> ' . $thisDir . '</p>';
echo '<p><b>Project Root (dirname):</b> ' . $projectRoot . '</p>';

// Test various storage paths
$paths = [
    'projectRoot/storage/app/public' => $projectRoot . '/storage/app/public',
    'projectRoot/storage/app/public/' . $testFile => $projectRoot . '/storage/app/public/' . $testFile,
    'thisDir/../storage/app/public' => $thisDir . '/../storage/app/public',
    'thisDir/../storage/app/public/' . $testFile => $thisDir . '/../storage/app/public/' . $testFile,
    'thisDir/storage (public/storage symlink?)' => $thisDir . '/storage',
];

foreach ($paths as $label => $path) {
    $exists = file_exists($path);
    $isLink = is_link($path);
    $isDir = is_dir($path);
    $isFile = is_file($path);
    $readable = is_readable($path);
    $realp = @realpath($path);
    
    echo '<p><b>' . htmlspecialchars($label) . '</b><br>';
    echo '  Path: <code>' . htmlspecialchars($path) . '</code><br>';
    echo '  exists: ' . ($exists ? '<span class="ok">YES</span>' : '<span class="err">NO</span>');
    echo ' | is_link: ' . ($isLink ? '<span class="warn">YES</span>' : 'no');
    echo ' | is_dir: ' . ($isDir ? 'yes' : 'no');
    echo ' | is_file: ' . ($isFile ? 'yes' : 'no');
    echo ' | readable: ' . ($readable ? '<span class="ok">YES</span>' : '<span class="err">NO</span>');
    echo ' | realpath: ' . ($realp ? htmlspecialchars($realp) : '<span class="err">FALSE</span>');
    echo '</p>';
}

// ==============================
// 3. Storage Directory Listing
// ==============================
echo '<h2>3. Storage Directory Structure</h2>';
$storageBase = $projectRoot . '/storage/app/public';

if (is_dir($storageBase)) {
    echo '<p class="ok">✅ ' . htmlspecialchars($storageBase) . ' EXISTS</p>';
    
    // List top-level contents
    $items = @scandir($storageBase);
    if ($items) {
        echo '<pre>';
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $itemPath = $storageBase . '/' . $item;
            $perms = substr(sprintf('%o', fileperms($itemPath)), -4);
            $type = is_dir($itemPath) ? 'DIR ' : 'FILE';
            echo $type . ' ' . $perms . ' ' . $item . "\n";
        }
        echo '</pre>';
    }
    
    // Check products subdirectory
    $productsDir = $storageBase . '/products';
    if (is_dir($productsDir)) {
        echo '<p><b>products/ subdirectory:</b></p><pre>';
        $listDir = function($dir, $indent = '') use (&$listDir) {
            $items = @scandir($dir);
            if (!$items) return;
            $count = 0;
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                if ($count++ > 20) { echo $indent . "... (more files)\n"; break; }
                $itemPath = $dir . '/' . $item;
                $perms = substr(sprintf('%o', @fileperms($itemPath)), -4);
                $type = is_dir($itemPath) ? 'DIR ' : 'FILE';
                $size = is_file($itemPath) ? ' (' . filesize($itemPath) . ' bytes)' : '';
                echo $indent . $type . ' ' . $perms . ' ' . $item . $size . "\n";
                if (is_dir($itemPath)) {
                    $listDir($itemPath, $indent . '  ');
                }
            }
        };
        $listDir($productsDir);
        echo '</pre>';
    } else {
        echo '<p class="err">❌ products/ subdirectory NOT FOUND at ' . htmlspecialchars($productsDir) . '</p>';
    }
} else {
    echo '<p class="err">❌ Storage base NOT FOUND: ' . htmlspecialchars($storageBase) . '</p>';
    
    // Try alternate locations
    $alternates = [
        '/home/itcentre/public_html/storage/app/public',
        '/home/itcentre/storage/app/public',
        '/home/itcentre/ITCenter-Ecommerce/storage/app/public',
        $thisDir . '/storage', // public/storage symlink
    ];
    echo '<p><b>Trying alternate locations:</b></p>';
    foreach ($alternates as $alt) {
        $exists = file_exists($alt);
        echo '<p>  ' . htmlspecialchars($alt) . ': ' . ($exists ? '<span class="ok">EXISTS</span>' : '<span class="err">NOT FOUND</span>') . '</p>';
    }
}

// ==============================
// 4. Test File Access
// ==============================
echo '<h2>4. Test File Direct Access</h2>';
$testFullPath = $projectRoot . '/storage/app/public/' . $testFile;
echo '<p><b>Full path:</b> <code>' . htmlspecialchars($testFullPath) . '</code></p>';

if (file_exists($testFullPath)) {
    echo '<p class="ok">✅ File EXISTS</p>';
    echo '<p><b>Size:</b> ' . filesize($testFullPath) . ' bytes</p>';
    echo '<p><b>Permissions:</b> ' . substr(sprintf('%o', fileperms($testFullPath)), -4) . '</p>';
    echo '<p><b>Owner:</b> ' . (function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($testFullPath))['name'] : fileowner($testFullPath)) . '</p>';
    echo '<p><b>is_readable:</b> ' . (is_readable($testFullPath) ? '<span class="ok">YES</span>' : '<span class="err">NO</span>') . '</p>';
    echo '<p><b>MIME type:</b> ' . (mime_content_type($testFullPath) ?: 'unknown') . '</p>';
    echo '<p><b>realpath:</b> ' . (realpath($testFullPath) ?: '<span class="err">FALSE</span>') . '</p>';
    
    // Test reading it
    $content = @file_get_contents($testFullPath);
    if ($content !== false) {
        echo '<p class="ok">✅ file_get_contents: SUCCESS (' . strlen($content) . ' bytes)</p>';
    } else {
        echo '<p class="err">❌ file_get_contents: FAILED</p>';
        echo '<p>Last error: ' . htmlspecialchars(json_encode(error_get_last())) . '</p>';
    }
    
    // Test serving it inline
    echo '<p><b>Inline image test:</b></p>';
    $base64 = base64_encode(substr($content, 0, 100000)); // limit to 100KB for preview
    $mime = mime_content_type($testFullPath) ?: 'image/png';
    echo '<p><img src="data:' . $mime . ';base64,' . $base64 . '" style="max-width:200px;max-height:200px;border:2px solid #444;"></p>';
} else {
    echo '<p class="err">❌ File does NOT exist at this path</p>';
    
    // Check each directory in the path
    $parts = explode('/', trim($testFile, '/'));
    $checkPath = $projectRoot . '/storage/app/public';
    foreach ($parts as $part) {
        $checkPath .= '/' . $part;
        $exists = file_exists($checkPath);
        $perms = $exists ? substr(sprintf('%o', fileperms($checkPath)), -4) : 'N/A';
        echo '<p>  ' . htmlspecialchars($checkPath) . ': ' . ($exists ? '<span class="ok">' . $perms . '</span>' : '<span class="err">MISSING</span>') . '</p>';
    }
}

// ==============================
// 5. Laravel Bootstrap Test
// ==============================
echo '<h2>5. Laravel storage_path() Test</h2>';
try {
    require_once $thisDir . '/../vendor/autoload.php';
    $app = require_once $thisDir . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    
    echo '<p><b>base_path():</b> ' . base_path() . '</p>';
    echo '<p><b>storage_path():</b> ' . storage_path() . '</p>';
    echo '<p><b>storage_path("app/public"):</b> ' . storage_path('app/public') . '</p>';
    echo '<p><b>storage_path("app/public/' . $testFile . '"):</b> ' . storage_path('app/public/' . $testFile) . '</p>';
    echo '<p><b>public_path():</b> ' . public_path() . '</p>';
    echo '<p><b>public_path("storage"):</b> ' . public_path('storage') . '</p>';
    
    $laravelStoragePath = storage_path('app/public/' . $testFile);
    echo '<p><b>file_exists(storage_path):</b> ' . (file_exists($laravelStoragePath) ? '<span class="ok">YES</span>' : '<span class="err">NO</span>') . '</p>';
    
    // Check if public/storage symlink exists
    $symlinkPath = public_path('storage');
    echo '<p><b>public/storage symlink exists:</b> ' . (file_exists($symlinkPath) ? 'YES' : 'NO') . '</p>';
    echo '<p><b>public/storage is_link:</b> ' . (is_link($symlinkPath) ? 'YES' : 'NO') . '</p>';
    if (is_link($symlinkPath)) {
        echo '<p><b>Symlink target:</b> ' . readlink($symlinkPath) . '</p>';
    }
    
} catch (\Throwable $e) {
    echo '<p class="err">❌ Laravel bootstrap failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}

// ==============================
// 6. .htaccess Chain Analysis
// ==============================
echo '<h2>6. .htaccess Chain Analysis</h2>';

$htaccessFiles = [
    'Root .htaccess' => $projectRoot . '/.htaccess',
    'public/.htaccess' => $thisDir . '/.htaccess',
    'storage/.htaccess' => $projectRoot . '/storage/.htaccess',
    'storage/app/.htaccess' => $projectRoot . '/storage/app/.htaccess',
    'storage/app/public/.htaccess' => $projectRoot . '/storage/app/public/.htaccess',
    'public/storage/.htaccess (symlink dir)' => $thisDir . '/storage/.htaccess',
];

foreach ($htaccessFiles as $label => $path) {
    echo '<p><b>' . $label . ':</b> ';
    if (file_exists($path)) {
        echo '<span class="warn">EXISTS</span></p>';
        echo '<pre>' . htmlspecialchars(file_get_contents($path)) . '</pre>';
    } else {
        echo 'Not found</p>';
    }
}

// ==============================
// 7. Request Simulation
// ==============================
echo '<h2>7. Request Flow Simulation</h2>';
echo '<p>When browser requests: <code>https://itcentre.vip/storage/' . htmlspecialchars($testFile) . '</code></p>';
echo '<ol>';
echo '<li>Apache hits <code>public_html/.htaccess</code>: <code>RewriteRule ^(.*)$ public/$1 [L]</code><br>';
echo '   → Rewrites to: <code>public/storage/' . htmlspecialchars($testFile) . '</code></li>';
echo '<li>Apache processes <code>public_html/public/.htaccess</code>: <code>RewriteRule ^storage/(.*)$ index.php [L]</code><br>';
echo '   → Rewrites to: <code>index.php</code></li>';
echo '<li>Laravel route <code>/storage/{path}</code> receives: <code>' . htmlspecialchars($testFile) . '</code></li>';
echo '<li>Builds path: <code>storage_path("app/public/' . htmlspecialchars($testFile) . '")</code></li>';
echo '<li>Calls <code>response()->file($fullPath)</code></li>';
echo '</ol>';

// ==============================
// 8. Direct File Serve Test
// ==============================
echo '<h2>8. Direct Serve Test (bypasses Laravel)</h2>';
echo '<p>Click to test direct file serving:</p>';
$serveUrl = 'storage-diag.php?key=' . $secretKey . '&action=serve&file=' . urlencode($testFile);
echo '<p><a href="' . $serveUrl . '" target="_blank" style="color:#8be9fd;text-decoration:underline;">→ Serve file directly via PHP</a></p>';

if (($_GET['action'] ?? '') === 'serve') {
    $servePath = $projectRoot . '/storage/app/public/' . $testFile;
    if (file_exists($servePath) && is_file($servePath)) {
        header('Content-Type: ' . (mime_content_type($servePath) ?: 'application/octet-stream'));
        header('Content-Length: ' . filesize($servePath));
        header('Cache-Control: public, max-age=86400');
        readfile($servePath);
        exit;
    } else {
        echo '<p class="err">File not found for direct serve: ' . htmlspecialchars($servePath) . '</p>';
    }
}

// ==============================
// 9. Test /storage/ URL via HTTP
// ==============================
echo '<h2>9. HTTP Request Test</h2>';
$testUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/storage/' . $testFile;
echo '<p><b>Test URL:</b> <code>' . htmlspecialchars($testUrl) . '</code></p>';
echo '<p>Testing via cURL...</p>';

if (function_exists('curl_init')) {
    $ch = curl_init($testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $error = curl_error($ch);
    curl_close($ch);
    
    $statusClass = $httpCode === 200 ? 'ok' : ($httpCode === 403 ? 'err' : 'warn');
    echo '<p><b>HTTP Status:</b> <span class="' . $statusClass . '">' . $httpCode . '</span></p>';
    echo '<p><b>Content-Type:</b> ' . htmlspecialchars($contentType ?? 'none') . '</p>';
    if ($error) echo '<p class="err"><b>cURL Error:</b> ' . htmlspecialchars($error) . '</p>';
    echo '<p><b>Response Headers:</b></p><pre>' . htmlspecialchars($response) . '</pre>';
} else {
    echo '<p class="warn">cURL not available. Testing with file_get_contents...</p>';
    $context = stream_context_create(['http' => ['method' => 'HEAD', 'timeout' => 10]]);
    $headers = @get_headers($testUrl);
    if ($headers) {
        echo '<pre>' . htmlspecialchars(implode("\n", $headers)) . '</pre>';
    } else {
        echo '<p class="err">Could not fetch headers</p>';
    }
}

// Also test /media/ URL
$mediaUrl = str_replace('/storage/', '/media/', $testUrl);
echo '<p><b>Also testing /media/ URL:</b> <code>' . htmlspecialchars($mediaUrl) . '</code></p>';
if (function_exists('curl_init')) {
    $ch = curl_init($mediaUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $statusClass = $httpCode === 200 ? 'ok' : ($httpCode === 403 ? 'err' : 'warn');
    echo '<p><b>/media/ HTTP Status:</b> <span class="' . $statusClass . '">' . $httpCode . '</span></p>';
    echo '<pre>' . htmlspecialchars($response) . '</pre>';
}

echo '<hr><p style="color:#666;">Generated at: ' . date('Y-m-d H:i:s T') . '</p>';
echo '<p style="color:#ff4444;font-weight:bold;">⚠️ DELETE THIS FILE after debugging!</p>';
echo '</body></html>';
