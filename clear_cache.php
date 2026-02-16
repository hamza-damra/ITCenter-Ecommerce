<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');

echo "<h2>Cache Clearing</h2>";

// Clear views
$kernel->call('view:clear');
echo "Views cleared!<br>";

// Clear route cache
$routeCache = __DIR__ . '/bootstrap/cache/routes-v7.php';
$routeCache2 = __DIR__ . '/bootstrap/cache/routes.php';
if (file_exists($routeCache)) { unlink($routeCache); echo "Route cache cleared!<br>"; }
if (file_exists($routeCache2)) { unlink($routeCache2); echo "Route cache (v2) cleared!<br>"; }

// Clear config cache
$configCache = __DIR__ . '/bootstrap/cache/config.php';
if (file_exists($configCache)) { unlink($configCache); echo "Config cache cleared!<br>"; }

// Clear compiled services
$servicesCache = __DIR__ . '/bootstrap/cache/services.php';
$packagesCache = __DIR__ . '/bootstrap/cache/packages.php';
if (file_exists($servicesCache)) { unlink($servicesCache); echo "Services cache cleared!<br>"; }
if (file_exists($packagesCache)) { unlink($packagesCache); echo "Packages cache cleared!<br>"; }

// Regenerate security files
try {
    \App\Services\ImageUploadService::ensureSecurityFiles();
    echo "Storage .htaccess regenerated!<br>";
} catch (\Exception $e) {
    echo "Failed to regenerate .htaccess: " . $e->getMessage() . "<br>";
}

echo "<br><b>All caches cleared!</b>";
