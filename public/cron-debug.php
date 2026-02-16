<?php
/**
 * Cron Job Debugger for CPanel
 * 
 * Upload this to public/ folder and access via browser:
 * https://itcentre.vip/cron-debug.php
 * 
 * DELETE THIS FILE AFTER DEBUGGING!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>Cron Debug</title><style>
body { font-family: monospace; background: #1a1a2e; color: #e0e0e0; padding: 20px; }
h2 { color: #00d4ff; border-bottom: 1px solid #333; padding-bottom: 8px; }
.ok { color: #00ff88; } .fail { color: #ff4444; } .warn { color: #ffaa00; }
pre { background: #16213e; padding: 12px; border-radius: 6px; overflow-x: auto; }
.section { margin-bottom: 24px; }
</style></head><body>";

echo "<h1>🔧 Cron Job Debugger</h1>";
echo "<p>Time: " . date('Y-m-d H:i:s T') . "</p>";

// ============================================================
// 1. PHP Binary Path
// ============================================================
echo "<div class='section'><h2>1. PHP Binary Path</h2>";

$phpPaths = [
    '/usr/local/bin/php',
    '/usr/local/bin/ea-php99',
    '/usr/local/bin/ea-php83',
    '/usr/local/bin/ea-php82',
    '/usr/local/bin/ea-php81',
    '/usr/local/bin/ea-php80',
    '/usr/bin/php',
];

echo "<p>Current PHP version: <b>" . PHP_VERSION . "</b></p>";
echo "<p>PHP SAPI: <b>" . php_sapi_name() . "</b></p>";

foreach ($phpPaths as $path) {
    if (file_exists($path)) {
        $version = shell_exec("$path -v 2>&1");
        $ver = $version ? explode("\n", $version)[0] : 'unknown';
        echo "<p class='ok'>✅ $path → $ver</p>";
    } else {
        echo "<p class='fail'>❌ $path → NOT FOUND</p>";
    }
}

// Try to find PHP via which
$whichPhp = trim(shell_exec('which php 2>&1') ?? '');
if ($whichPhp) {
    echo "<p class='warn'>⚡ 'which php' = $whichPhp</p>";
}
echo "</div>";

// ============================================================
// 2. Artisan File Check
// ============================================================
echo "<div class='section'><h2>2. Artisan File</h2>";

$artisanPaths = [
    '/home/itcentre/artisan',
    '/home/itcentre/public_html/artisan',
    dirname(__DIR__) . '/artisan',
];

foreach ($artisanPaths as $path) {
    if (file_exists($path)) {
        echo "<p class='ok'>✅ $path → EXISTS (size: " . filesize($path) . " bytes)</p>";
        echo "<p>Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "</p>";
    } else {
        echo "<p class='fail'>❌ $path → NOT FOUND</p>";
    }
}
echo "</div>";

// ============================================================
// 3. Try Running Artisan Commands
// ============================================================
echo "<div class='section'><h2>3. Artisan Command Tests</h2>";

$artisanPath = null;
foreach ($artisanPaths as $path) {
    if (file_exists($path)) {
        $artisanPath = $path;
        break;
    }
}

$phpBin = '/usr/local/bin/php';
if (!file_exists($phpBin)) {
    // Try ea-php versions
    foreach (['/usr/local/bin/ea-php83', '/usr/local/bin/ea-php82', '/usr/local/bin/ea-php81'] as $p) {
        if (file_exists($p)) { $phpBin = $p; break; }
    }
}

if ($artisanPath) {
    $baseDir = dirname($artisanPath);
    
    // Test: artisan --version
    echo "<h3>a) artisan --version</h3>";
    $cmd = "cd $baseDir && $phpBin artisan --version 2>&1";
    echo "<p>Command: <code>$cmd</code></p>";
    $output = shell_exec($cmd);
    echo "<pre>" . htmlspecialchars($output) . "</pre>";

    // Test: artisan schedule:list
    echo "<h3>b) artisan schedule:list</h3>";
    $cmd = "cd $baseDir && $phpBin artisan schedule:list 2>&1";
    echo "<p>Command: <code>$cmd</code></p>";
    $output = shell_exec($cmd);
    echo "<pre>" . htmlspecialchars($output) . "</pre>";

    // Test: artisan schedule:run (dry-run via test)
    echo "<h3>c) artisan schedule:test (shows what WOULD run)</h3>";
    $cmd = "cd $baseDir && $phpBin artisan schedule:list --next 2>&1";
    echo "<p>Command: <code>$cmd</code></p>";
    $output = shell_exec($cmd);
    echo "<pre>" . htmlspecialchars($output) . "</pre>";

} else {
    echo "<p class='fail'>❌ Artisan file not found in any expected location!</p>";
}
echo "</div>";

// ============================================================
// 4. Check Environment
// ============================================================
echo "<div class='section'><h2>4. Environment Variables</h2>";

if ($artisanPath) {
    $envFile = dirname($artisanPath) . '/.env';
    if (file_exists($envFile)) {
        echo "<p class='ok'>✅ .env file exists</p>";
        
        $envContent = file_get_contents($envFile);
        
        // Show relevant vars (masked)
        $relevantKeys = ['APP_ENV', 'APP_DEBUG', 'BACKUP_SCHEDULE', 'BACKUP_DAILY_TIME', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'QUEUE_CONNECTION'];
        foreach ($relevantKeys as $key) {
            if (preg_match("/^{$key}=(.*)$/m", $envContent, $m)) {
                $val = trim($m[1]);
                // Mask sensitive values
                if (in_array($key, ['DB_PASSWORD'])) {
                    $val = '***masked***';
                }
                echo "<p>$key = <b>$val</b></p>";
            }
        }
    } else {
        echo "<p class='fail'>❌ .env file NOT found at $envFile</p>";
    }
}
echo "</div>";

// ============================================================
// 5. Check Log Files
// ============================================================
echo "<div class='section'><h2>5. Recent Laravel Logs (last 30 lines with 'schedule' or 'backup' or 'cron')</h2>";

if ($artisanPath) {
    $logFile = dirname($artisanPath) . '/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        echo "<p class='ok'>✅ Log file exists (size: " . round(filesize($logFile) / 1024, 1) . " KB)</p>";
        
        // Get last 50 lines and filter for schedule/backup/cron
        $cmd = "tail -100 $logFile 2>&1";
        $output = shell_exec($cmd);
        $lines = explode("\n", $output);
        
        $filtered = array_filter($lines, function($line) {
            $lower = strtolower($line);
            return str_contains($lower, 'schedule') || str_contains($lower, 'backup') || str_contains($lower, 'cron');
        });
        
        if (empty($filtered)) {
            echo "<p class='warn'>⚠️ No schedule/backup/cron entries found in last 100 lines of log</p>";
            echo "<h3>Last 15 log lines:</h3>";
            echo "<pre>" . htmlspecialchars(implode("\n", array_slice($lines, -15))) . "</pre>";
        } else {
            echo "<pre>" . htmlspecialchars(implode("\n", array_slice($filtered, -30))) . "</pre>";
        }
    } else {
        echo "<p class='fail'>❌ Log file NOT found at $logFile</p>";
    }
}
echo "</div>";

// ============================================================
// 6. Check Cron Output
// ============================================================
echo "<div class='section'><h2>6. Cron Job Suggestion</h2>";
echo "<p>Your current cron job from the screenshot:</p>";
echo "<pre>* * * * * /usr/local/bin/php /home/itcentre/artisan schedule:run >> /dev/null 2>&1</pre>";

echo "<p class='warn'>⚠️ The problem with <code>>> /dev/null 2>&1</code> is that it hides ALL output and errors!</p>";
echo "<p>To debug, <b>temporarily</b> change the cron job to log output:</p>";
echo "<pre>* * * * * $phpBin $artisanPath schedule:run >> /home/itcentre/cron-debug.log 2>&1</pre>";
echo "<p>Then check <code>/home/itcentre/cron-debug.log</code> after a few minutes.</p>";

echo "</div>";

// ============================================================
// 7. Direct Schedule Run Test
// ============================================================
echo "<div class='section'><h2>7. 🔴 Run Schedule NOW (Click to test)</h2>";
if (isset($_GET['run_schedule']) && $_GET['run_schedule'] === 'yes') {
    echo "<p class='warn'>Running schedule:run...</p>";
    $cmd = "cd " . dirname($artisanPath) . " && $phpBin artisan schedule:run 2>&1";
    echo "<p>Command: <code>$cmd</code></p>";
    $output = shell_exec($cmd);
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    echo "<p class='ok'>Done!</p>";
} else {
    echo "<p><a href='?run_schedule=yes' style='color: #ff4444; font-size: 18px; text-decoration: underline;'>⚡ Click here to run schedule:run NOW</a></p>";
    echo "<p>(This will execute the scheduler once, just like the cron job would)</p>";
}
echo "</div>";

// ============================================================
// 8. Database connectivity
// ============================================================
echo "<div class='section'><h2>8. Database Connectivity</h2>";
if ($artisanPath) {
    $cmd = "cd " . dirname($artisanPath) . " && $phpBin artisan db:show --counts 2>&1";
    echo "<p>Command: <code>cd " . dirname($artisanPath) . " && $phpBin artisan db:show 2>&1</code></p>";
    $output = shell_exec($cmd);
    // Show only first 20 lines
    $lines = explode("\n", $output);
    echo "<pre>" . htmlspecialchars(implode("\n", array_slice($lines, 0, 20))) . "</pre>";
}
echo "</div>";

echo "<hr><p class='fail'><b>⚠️ DELETE THIS FILE AFTER DEBUGGING! It exposes sensitive information.</b></p>";
echo "</body></html>";
