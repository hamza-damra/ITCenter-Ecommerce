<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Try to increase max_allowed_packet for this session
try {
    DB::statement("SET GLOBAL max_allowed_packet = 67108864"); // 64MB
    echo "Set max_allowed_packet to 64MB globally\n";
} catch (Exception $e) {
    echo "Could not set globally (need SUPER privilege): " . $e->getMessage() . "\n";
}

// Check current value
$result = DB::select("SHOW VARIABLES LIKE 'max_allowed_packet'");
echo "Current max_allowed_packet: " . number_format($result[0]->Value) . " bytes (" . round($result[0]->Value / 1024 / 1024, 2) . " MB)\n";
