<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Banner;
use Illuminate\Support\Facades\DB;

echo "Banner count: " . Banner::count() . "\n";
echo "Max allowed packet: " . DB::select("SHOW VARIABLES LIKE 'max_allowed_packet'")[0]->Value . "\n";

$banners = Banner::all();
foreach ($banners as $b) {
    echo "ID: {$b->id}, Title: {$b->title_en}, Active: {$b->is_active}, Source: {$b->image_source}\n";
}
