<?php
// Quick test to check password reset codes in database
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PasswordResetCode;

echo "=== Password Reset Codes (Latest 5) ===\n\n";

$codes = PasswordResetCode::latest()->take(5)->get();

if ($codes->isEmpty()) {
    echo "❌ No reset codes found in database.\n";
    echo "This means the code was NOT saved to the database.\n";
} else {
    foreach ($codes as $code) {
        echo "Email: {$code->email}\n";
        echo "Code: {$code->code}\n";
        echo "Created: {$code->created_at}\n";
        echo "Expires: {$code->expires_at}\n";
        echo "Used: " . ($code->used ? 'Yes' : 'No') . "\n";
        echo "Attempts: {$code->attempts}\n";
        echo "---\n";
    }
}

echo "\n=== Mail Configuration ===\n\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n";

echo "\n✅ Test complete!\n";
