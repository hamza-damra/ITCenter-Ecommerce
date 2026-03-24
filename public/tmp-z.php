<?php
header('Content-Type: text/plain');
// Clear views
$count = 0;
foreach (glob(__DIR__ . '/../storage/framework/views/*.php') as $f) { unlink($f); $count++; }
echo "Views: $count\n";
// Clear file cache
$cachePath = __DIR__ . '/../storage/framework/cache/data';
if (is_dir($cachePath)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($cachePath, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $item) { if ($item->isFile()) unlink($item->getRealPath()); }
    echo "File cache cleared\n";
}
// Clear route cache
foreach (glob(__DIR__ . '/../bootstrap/cache/route*.php') as $f) { unlink($f); echo "Route cache cleared\n"; }
// Update DB
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=itcentre_ecommerce;charset=utf8mb4', 'itcentre_itcentre', '@01YWBxss8yL{.8j', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $v = (string)time();
    $pdo->prepare("INSERT INTO site_settings (`key`,`value`,`type`,`group`,created_at,updated_at) VALUES ('site_favicon_version',?,'string','branding',NOW(),NOW()) ON DUPLICATE KEY UPDATE `value`=?,updated_at=NOW()")->execute([$v,$v]);
    $pdo->exec("DELETE FROM cache WHERE `key` LIKE '%site_setting%' OR `key` LIKE '%site_settings%'");
    echo "DB version=$v\n";
} catch (Throwable $e) { echo "DB: ".$e->getMessage()."\n"; }
echo "DONE\n";
