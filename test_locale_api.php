<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║     Testing Arabic Locale in Contact API Response        ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

function testContactAPI($locale, $name) {
    echo "Testing with locale: $locale ($name)\n";
    echo "─────────────────────────────────────────────────────────\n";
    
    $url = 'http://localhost:8000/api/v1/contact';
    $data = [
        'name' => "Test User ($locale)",
        'email' => "test-{$locale}-" . time() . '@example.com',
        'subject' => "Test Subject ($locale)",
        'message' => "Test message for locale $locale"
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\nAccept-Language: $locale\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === false) {
        echo "✗ Failed to connect\n\n";
        return;
    }

    $response = json_decode($result, true);
    
    if ($response['success']) {
        echo "✓ Success: true\n";
        echo "✓ Message: {$response['message']}\n";
        echo "✓ Contact ID: {$response['data']['id']}\n";
    } else {
        echo "✗ Failed: {$response['message']}\n";
    }
    echo "\n";
}

// Test all locales
testContactAPI('ar', 'Arabic');
testContactAPI('en', 'English');
testContactAPI('he', 'Hebrew');

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                  Expected Results:                        ║\n";
echo "╠═══════════════════════════════════════════════════════════╣\n";
echo "║ Arabic:  تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.       ║\n";
echo "║ English: Your message has been sent successfully...      ║\n";
echo "║ Hebrew:  הודעתך נשלחה בהצלחה. ניצור איתך קשר בקרוב.      ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Cleanup test data
echo "🧹 Cleaning up test data...\n";
\App\Models\Contact::where('email', 'like', 'test-%@example.com')->forceDelete();
echo "✓ Done\n\n";

echo "Now test in browser: http://localhost:8000/contact\n";
echo "The success message will be in Arabic! 🎉\n";
