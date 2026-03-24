<?php
$viewsPath = __DIR__ . '/../storage/framework/views';
$count = 0;
foreach (glob($viewsPath . '/*.php') as $file) { unlink($file); $count++; }
echo "Cleared $count views";
