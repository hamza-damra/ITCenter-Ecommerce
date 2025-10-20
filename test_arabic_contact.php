<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Contact Form with Arabic Language\n";
echo "=========================================\n\n";

// Set locale to Arabic
app()->setLocale('ar');

// Simulate sending a message
echo "1. Testing API endpoint with Arabic locale...\n";

$url = 'http://localhost:8000/api/v1/contact';
$data = [
    'name' => 'أحمد محمد',
    'email' => 'ahmed@example.com',
    'subject' => 'استفسار عن المنتجات',
    'message' => 'أريد معرفة المزيد عن منتجاتكم. التوقيت: ' . now()->toDateTimeString()
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\nAccept-Language: ar\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === false) {
    echo "   ✗ Failed to connect to API\n";
} else {
    $response = json_decode($result, true);
    echo "   Response:\n";
    echo "   - Success: " . ($response['success'] ? 'true' : 'false') . "\n";
    echo "   - Message: " . $response['message'] . "\n";
    
    if ($response['success']) {
        echo "   ✓ Message sent successfully!\n";
        echo "   ✓ Contact ID: " . ($response['data']['id'] ?? 'N/A') . "\n";
    }
}

echo "\n2. Checking translations...\n";
echo "   Arabic success message: " . __('messages.message_sent_successfully') . "\n";

echo "\n3. Verifying in database...\n";
$totalContacts = \App\Models\Contact::count();
echo "   Total contacts: $totalContacts\n";

$latest = \App\Models\Contact::latest()->first();
if ($latest) {
    echo "   Latest contact:\n";
    echo "   - Name: {$latest->name}\n";
    echo "   - Email: {$latest->email}\n";
    echo "   - Subject: {$latest->subject}\n";
    echo "   - Status: {$latest->status}\n";
}

echo "\n✅ Test completed!\n";
echo "\nNext: Visit http://localhost:8000/contact and send a message\n";
echo "The success message should now be in Arabic! 🎉\n";
