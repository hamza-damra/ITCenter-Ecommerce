<?php

// Test Contact API Endpoint

$url = 'http://localhost:8000/api/v1/contact';

$data = [
    'name' => 'Test من السكريبت',
    'email' => 'script-test@example.com',
    'subject' => 'رسالة اختبارية من السكريبت',
    'message' => 'هذه رسالة اختبارية مرسلة من سكريبت PHP للتحقق من أن الـ API يعمل بشكل صحيح. التوقيت: ' . date('Y-m-d H:i:s')
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

echo "Testing Contact API Endpoint...\n";
echo "URL: $url\n";
echo "Method: POST\n";
echo "Data: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

if ($result === false) {
    echo "✗ Failed to connect to API\n";
    echo "Error: " . error_get_last()['message'] . "\n";
} else {
    $response = json_decode($result, true);
    echo "Response:\n";
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
    
    if (isset($response['success']) && $response['success']) {
        echo "✓ API call successful!\n";
    } else {
        echo "✗ API call failed!\n";
    }
}

// Now check database
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n--- Database Check ---\n";
$total = \App\Models\Contact::count();
echo "Total contacts in database: $total\n";

$latest = \App\Models\Contact::latest()->take(3)->get();
echo "\nLatest 3 contacts:\n";
foreach ($latest as $contact) {
    echo "  [{$contact->id}] {$contact->name} - {$contact->email} - {$contact->subject} [{$contact->status}] ({$contact->created_at})\n";
}
