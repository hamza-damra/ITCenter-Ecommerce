<?php

/**
 * Storage/Media Diagnostic Tool v3
 * Access via: https://itcentre.vip/storage-debug.php?key=itc_debug_2026
 * DELETE THIS FILE after debugging is complete!
 */

// Security: only allow with a secret key
$secretKey = 'itc_debug_2026';
if (($_GET['key'] ?? '') !== $secretKey) {
    http_response_code(403);
    echo 'Access denied. Use ?key='.$secretKey;
    exit;
}

echo '<html><head><title>Storage/Media Diagnostics v3</title>';
echo '<style>body{font-family:monospace;padding:20px;background:#1a1a2e;color:#e0e0e0;} ';
echo '.ok{color:#00ff88;} .err{color:#ff4444;} .warn{color:#ffaa00;} ';
echo 'h2{color:#8be9fd;border-bottom:1px solid #444;padding-bottom:5px;} ';
echo 'pre{background:#2d2d44;padding:10px;border-radius:5px;overflow-x:auto;max-height:400px;overflow-y:auto;}';
echo 'a.btn{display:inline-block;padding:8px 16px;margin:5px;border-radius:5px;text-decoration:none;color:#fff;font-weight:bold;}';
echo 'a.btn-green{background:#27ae60;} a.btn-red{background:#e74c3c;} a.btn-blue{background:#2980b9;}</style></head><body>';

echo '<h1>Storage/Media Diagnostics v3</h1>';
echo '<p>URLs now use <b>/media/</b> path instead of <b>/storage/</b> to avoid public/storage/ directory conflict.</p>';

$basePath = dirname(__DIR__);
$storagePath = $basePath.'/storage/app/public';
$publicStorageDir = __DIR__.'/storage';
$testImage = 'banners/2026/02/a0636023-e414-42b2-a517-0c7f30674e9e.png';
$testFullPath = $storagePath.'/'.$testImage;

// ══════════════════════════════════════════════════
// CRITICAL CHECK: What's inside public/storage/ ?
// ══════════════════════════════════════════════════
echo '<h2>1. CRITICAL: public/storage/ Directory Analysis</h2>';
echo "<p><b>Path:</b> $publicStorageDir</p>";
echo '<p><b>is_link():</b> '.(is_link($publicStorageDir) ? '<span class="ok">YES (symlink)</span>' : '<span class="warn">NO (not a symlink)</span>').'</p>';
echo '<p><b>is_dir():</b> '.(is_dir($publicStorageDir) ? 'YES' : 'NO').'</p>';

if (is_dir($publicStorageDir)) {
    // Check for .htaccess inside public/storage/
    $publicStorageHtaccess = $publicStorageDir.'/.htaccess';
    echo '<p><b>.htaccess in public/storage/:</b> '.(file_exists($publicStorageHtaccess) ? '<span class="warn">EXISTS!</span>' : '<span class="ok">Not found</span>').'</p>';
    if (file_exists($publicStorageHtaccess)) {
        echo '<pre>'.htmlspecialchars(file_get_contents($publicStorageHtaccess)).'</pre>';
    }

    // List top-level contents
    echo '<p><b>Contents of public/storage/:</b></p>';
    $contents = @scandir($publicStorageDir);
    if ($contents) {
        echo '<pre>';
        foreach ($contents as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $itemPath = $publicStorageDir.'/'.$item;
            $type = is_dir($itemPath) ? 'DIR ' : 'FILE';
            $isItemLink = is_link($itemPath) ? ' (SYMLINK → '.readlink($itemPath).')' : '';
            $perms = substr(sprintf('%o', fileperms($itemPath)), -4);
            echo "$type [$perms] $item{$isItemLink}\n";
        }
        echo '</pre>';
    }

    // Check if the test image exists inside public/storage/ (would mean Apache serves it directly)
    $publicTestPath = $publicStorageDir.'/'.$testImage;
    echo '<p><b>Test image in public/storage/:</b> '.(file_exists($publicTestPath) ? '<span class="warn">EXISTS! Apache might serve this directly</span>' : '<span class="ok">Not found (good - requests go through Laravel)</span>').'</p>';
} else {
    echo '<p><span class="err">public/storage/ directory does NOT exist</span></p>';
}

// ══════════════════════════════════════════════════
// Route Cache Check
// ══════════════════════════════════════════════════
echo '<h2>2. Route Cache Check</h2>';
$routeCacheFiles = [
    'routes-v7.php' => $basePath.'/bootstrap/cache/routes-v7.php',
    'routes.php' => $basePath.'/bootstrap/cache/routes.php',
];
$hasRouteCache = false;
foreach ($routeCacheFiles as $name => $path) {
    $exists = file_exists($path);
    if ($exists) {
        $hasRouteCache = true;
    }
    echo "<p><b>$name:</b> ".($exists ? '<span class="warn">EXISTS (route cache active!)</span>' : '<span class="ok">Not found</span>').'</p>';
}
if ($hasRouteCache) {
    echo '<p><span class="err">WARNING: Route cache is active! New route changes won\'t take effect until cache is cleared.</span></p>';
    echo '<p><a href="?key='.$secretKey.'&clear_route_cache=1" style="color:#ff4444;text-decoration:underline;">Click to delete route cache</a></p>';
}

// Handle cache clearing
if (isset($_GET['clear_route_cache'])) {
    foreach ($routeCacheFiles as $name => $path) {
        if (file_exists($path)) {
            @unlink($path);
            echo "<p class='ok'>Deleted: $name</p>";
        }
    }
}

$configCacheFile = $basePath.'/bootstrap/cache/config.php';
echo '<p><b>config.php cache:</b> '.(file_exists($configCacheFile) ? '<span class="warn">EXISTS</span>' : '<span class="ok">Not found</span>').'</p>';

// ══════════════════════════════════════════════════
// Compiled Views Check
// ══════════════════════════════════════════════════
echo '<h2>3. Compiled Views</h2>';
$viewsDir = $basePath.'/storage/framework/views';
$viewCount = count(glob($viewsDir.'/*.php'));
echo "<p><b>Compiled views count:</b> $viewCount</p>";
if (isset($_GET['clear_views'])) {
    $files = glob($viewsDir.'/*.php');
    foreach ($files as $f) {
        @unlink($f);
    }
    echo "<p class='ok'>Cleared ".count($files).' compiled views</p>';
} else {
    echo '<p><a href="?key='.$secretKey.'&clear_views=1" style="color:#ffaa00;text-decoration:underline;">Click to clear compiled views</a></p>';
}

// ══════════════════════════════════════════════════
// Test new /media/ URL via internal HTTP request
// ══════════════════════════════════════════════════
echo '<h2>4. Test /media/ URL (NEW - should work)</h2>';
$mediaUrl = 'https://'.($_SERVER['HTTP_HOST'] ?? 'itcentre.vip').'/media/'.$testImage;
echo "<p><b>Testing URL:</b> <a href='$mediaUrl' target='_blank' style='color:#00ff88;'>$mediaUrl</a></p>";

if (function_exists('curl_init')) {
    $ch = curl_init($mediaUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    $statusClass = ($httpCode >= 200 && $httpCode < 300) ? 'ok' : 'err';
    echo "<p><b>HTTP Status:</b> <span class='$statusClass'>$httpCode</span></p>";
    echo "<p><b>Content-Type:</b> $contentType</p>";
    if ($httpCode === 200 && str_starts_with($contentType ?? '', 'image/')) {
        echo "<p class='ok'>SUCCESS! /media/ route is serving images correctly!</p>";
    } elseif ($httpCode !== 200) {
        $headerSize = strpos($response, "\r\n\r\n");
        $headers = substr($response, 0, $headerSize);
        echo '<p><b>Response Headers:</b></p><pre>'.htmlspecialchars($headers).'</pre>';
        $body = substr($response, $headerSize + 4);
        echo '<p><b>Body (first 1000 chars):</b></p><pre>'.htmlspecialchars(substr($body, 0, 1000)).'</pre>';
    }
}

// ══════════════════════════════════════════════════
// Test old /storage/ URL (should redirect to /media/)
// ══════════════════════════════════════════════════
echo '<h2>5. Test /storage/ URL (OLD - should redirect)</h2>';
$storageUrl = 'https://'.($_SERVER['HTTP_HOST'] ?? 'itcentre.vip').'/storage/'.$testImage;
echo "<p><b>Testing URL:</b> $storageUrl</p>";

if (function_exists('curl_init')) {
    $ch = curl_init($storageUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);

    echo "<p><b>HTTP Status:</b> $httpCode</p>";
    if ($httpCode === 301) {
        echo "<p class='ok'>Correctly redirecting to: $redirectUrl</p>";
    } elseif ($httpCode === 403) {
        echo "<p class='warn'>Still getting 403 on /storage/ (expected if public/storage/ directory still interferes).</p>";
        echo "<p class='ok'>This is OK - all URLs now use /media/ instead.</p>";
    } else {
        echo "<p><b>Redirect URL:</b> ".($redirectUrl ?: 'none')."</p>";
        $headerSize = strpos($response, "\r\n\r\n");
        echo '<pre>'.htmlspecialchars(substr($response, 0, $headerSize)).'</pre>';
    }
}

// ══════════════════════════════════════════════════
// All .htaccess files in the chain
// ══════════════════════════════════════════════════
echo '<h2>6. All .htaccess Files</h2>';
$htaccessPaths = [
    'Root (.htaccess)' => $basePath.'/.htaccess',
    'public/.htaccess' => __DIR__.'/.htaccess',
    'public/storage/.htaccess' => $publicStorageDir.'/.htaccess',
    'storage/app/public/.htaccess' => $storagePath.'/.htaccess',
];
foreach ($htaccessPaths as $label => $htPath) {
    echo "<h3>$label</h3>";
    echo "<p><small>$htPath</small></p>";
    if (file_exists($htPath)) {
        echo '<pre>'.htmlspecialchars(file_get_contents($htPath)).'</pre>';
    } else {
        echo '<p><span class="warn">Not found</span></p>';
    }
}

// ══════════════════════════════════════════════════
// File existence test
// ══════════════════════════════════════════════════
echo '<h2>7. File Path Tests</h2>';
$pathsToTest = [
    'storage/app/public/'.$testImage => $storagePath.'/'.$testImage,
    'public/storage/'.$testImage => __DIR__.'/storage/'.$testImage,
];
foreach ($pathsToTest as $label => $p) {
    $exists = file_exists($p);
    $readable = is_readable($p);
    echo "<p><b>$label:</b> exists=".($exists ? '<span class="ok">YES</span>' : 'NO').' readable='.($readable ? '<span class="ok">YES</span>' : 'NO').'</p>';
}

// ══════════════════════════════════════════════════
// Recent Laravel Log
// ══════════════════════════════════════════════════
echo '<h2>8. Recent Laravel Log</h2>';
$logFile = $basePath.'/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -20);
    echo '<pre>'.htmlspecialchars(implode('', $lastLines)).'</pre>';
} else {
    echo '<p>No log file found</p>';
}

// ══════════════════════════════════════════════════
// Fix Actions
// ══════════════════════════════════════════════════
echo '<h2>9. Fix Actions</h2>';

// Fix: Clear all caches (compiled views, route cache, etc.)
if (isset($_GET['clear_all'])) {
    // Clear compiled views
    $viewsDir = $basePath.'/storage/framework/views';
    $files = glob($viewsDir.'/*.php');
    foreach ($files as $f) { @unlink($f); }
    echo "<p class='ok'>Cleared ".count($files)." compiled views</p>";

    // Clear route cache
    foreach (['routes-v7.php', 'routes.php'] as $rc) {
        $rcPath = $basePath.'/bootstrap/cache/'.$rc;
        if (file_exists($rcPath)) { @unlink($rcPath); echo "<p class='ok'>Deleted route cache: $rc</p>"; }
    }

    // Clear config cache
    $ccPath = $basePath.'/bootstrap/cache/config.php';
    if (file_exists($ccPath)) { @unlink($ccPath); echo "<p class='ok'>Deleted config cache</p>"; }

    echo "<p class='ok'>All caches cleared! Reload the page to test again.</p>";
}

// Fix: Rename public/storage/ directory
if (isset($_GET['rename_storage'])) {
    if (is_dir($publicStorageDir) && !is_link($publicStorageDir)) {
        $backupName = __DIR__.'/storage_backup_'.date('Ymd_His');
        $result = @rename($publicStorageDir, $backupName);
        if ($result) {
            echo "<p class='ok'>Renamed public/storage/ to ".basename($backupName)."/</p>";
            echo "<p class='ok'>The /storage/ URLs should now redirect to /media/ through Laravel!</p>";
        } else {
            echo "<p class='err'>Failed to rename. Try using cPanel File Manager to rename public/storage/ manually.</p>";
        }
    } elseif (is_link($publicStorageDir)) {
        echo "<p class='warn'>public/storage is a symlink - no need to rename.</p>";
    } else {
        echo "<p class='ok'>public/storage/ directory doesn't exist - already fixed!</p>";
    }
}

echo '<p><a href="?key='.$secretKey.'&clear_all=1" class="btn btn-blue">Clear All Caches</a></p>';
echo '<p><a href="?key='.$secretKey.'&rename_storage=1" class="btn btn-red">Rename public/storage/ Directory</a> <small>(removes the conflicting directory)</small></p>';

echo '<hr><p class="err"><b>DELETE THIS FILE after everything works!</b></p>';
echo '</body></html>';
