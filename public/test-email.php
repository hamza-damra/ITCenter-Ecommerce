<?php
// Test email sending
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\SendResetCodeMail;

echo "=== Testing Email Sending ===\n\n";

$testEmail = 'hamza.damra@students.alquds.edu';
$testCode = '1234';

try {
    echo "Sending test email to: {$testEmail}\n";
    echo "From: " . config('mail.from.address') . "\n";
    echo "Using SMTP: " . config('mail.mailers.smtp.host') . ":" . config('mail.mailers.smtp.port') . "\n\n";
    
    Mail::to($testEmail)->send(new SendResetCodeMail($testCode, $testEmail));
    
    echo "✅ Email sent successfully!\n";
    echo "Check the inbox of {$testEmail}\n";
    echo "Check spam/junk folder if not in inbox.\n";
    
} catch (\Exception $e) {
    echo "❌ Error sending email:\n";
    echo $e->getMessage() . "\n\n";
    echo "Full error:\n";
    echo $e->getTraceAsString() . "\n";
}
