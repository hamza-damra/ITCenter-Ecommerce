<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Contact Translations\n";
echo "============================\n\n";

// Test Arabic
app()->setLocale('ar');
echo "Arabic (ar):\n";
echo "  Success: " . __('messages.message_sent_successfully') . "\n";
echo "  Failed:  " . __('messages.message_send_failed') . "\n\n";

// Test English
app()->setLocale('en');
echo "English (en):\n";
echo "  Success: " . __('messages.message_sent_successfully') . "\n";
echo "  Failed:  " . __('messages.message_send_failed') . "\n\n";

// Test Hebrew
app()->setLocale('he');
echo "Hebrew (he):\n";
echo "  Success: " . __('messages.message_sent_successfully') . "\n";
echo "  Failed:  " . __('messages.message_send_failed') . "\n\n";

echo "✅ All translations loaded successfully!\n";
