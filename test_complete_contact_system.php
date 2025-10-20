<?php

/**
 * Complete Contact System Test
 * Tests all aspects of the contact functionality
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Contact System Complete Test ===\n\n";

// Test 1: Database Model
echo "1. Testing Database Model...\n";
try {
    $testContact = \App\Models\Contact::create([
        'name' => 'Complete Test User',
        'email' => 'complete-test@example.com',
        'subject' => 'Complete System Test',
        'message' => 'This is a complete system test message created at: ' . now()->toDateTimeString(),
        'status' => 'pending'
    ]);
    echo "   ✓ Contact model created successfully (ID: {$testContact->id})\n";
} catch (\Exception $e) {
    echo "   ✗ Failed to create contact: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Count contacts
echo "\n2. Testing Database Queries...\n";
$totalContacts = \App\Models\Contact::count();
$pendingContacts = \App\Models\Contact::where('status', 'pending')->count();
$readContacts = \App\Models\Contact::where('status', 'read')->count();
$archivedContacts = \App\Models\Contact::where('status', 'archived')->count();

echo "   Total contacts: $totalContacts\n";
echo "   Pending: $pendingContacts\n";
echo "   Read: $readContacts\n";
echo "   Archived: $archivedContacts\n";
echo "   ✓ Database queries working correctly\n";

// Test 3: API Endpoint
echo "\n3. Testing API Endpoint...\n";
$url = 'http://localhost:8000/api/v1/contact';
$data = [
    'name' => 'API Test User',
    'email' => 'api-test@example.com',
    'subject' => 'API Test Subject',
    'message' => 'API test message created at: ' . date('Y-m-d H:i:s')
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === false) {
    echo "   ✗ Failed to connect to API endpoint\n";
    echo "   Make sure the server is running: php artisan serve\n";
} else {
    $response = json_decode($result, true);
    if (isset($response['success']) && $response['success']) {
        echo "   ✓ API endpoint working correctly\n";
        echo "   Created contact ID: " . ($response['data']['id'] ?? 'N/A') . "\n";
    } else {
        echo "   ✗ API returned error: " . ($response['message'] ?? 'Unknown error') . "\n";
    }
}

// Test 4: Admin Controller Query
echo "\n4. Testing Admin Controller Logic...\n";
try {
    $adminMessages = \App\Models\Contact::orderBy('created_at', 'desc')->take(5)->get();
    echo "   ✓ Admin query successful\n";
    echo "   Found {$adminMessages->count()} recent messages:\n";
    foreach ($adminMessages as $msg) {
        echo "      - [{$msg->id}] {$msg->name} ({$msg->email}) - {$msg->subject} [{$msg->status}]\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Admin query failed: " . $e->getMessage() . "\n";
}

// Test 5: Status Update
echo "\n5. Testing Status Update...\n";
try {
    $testContact->update(['status' => 'read']);
    echo "   ✓ Status updated successfully to 'read'\n";
    
    $testContact->update(['status' => 'archived']);
    echo "   ✓ Status updated successfully to 'archived'\n";
    
    $testContact->update(['status' => 'pending']);
    echo "   ✓ Status reset to 'pending'\n";
} catch (\Exception $e) {
    echo "   ✗ Status update failed: " . $e->getMessage() . "\n";
}

// Test 6: Soft Delete
echo "\n6. Testing Soft Delete...\n";
try {
    $testContact->delete();
    echo "   ✓ Contact soft deleted successfully\n";
    
    $deletedCount = \App\Models\Contact::onlyTrashed()->count();
    echo "   Soft deleted contacts: $deletedCount\n";
    
    $testContact->restore();
    echo "   ✓ Contact restored successfully\n";
} catch (\Exception $e) {
    echo "   ✗ Soft delete test failed: " . $e->getMessage() . "\n";
}

// Test 7: Routes Check
echo "\n7. Checking Routes...\n";
$routes = [
    'Web Contact Page' => route('contact'),
    'API Contact Store' => route('api.contact.store'),
    'Admin Contacts Index' => route('admin.contacts.index'),
];

foreach ($routes as $name => $url) {
    echo "   ✓ $name: $url\n";
}

// Final Summary
echo "\n=== Test Summary ===\n";
$finalCount = \App\Models\Contact::count();
echo "Total contacts in database: $finalCount\n";
echo "All tests completed!\n\n";

echo "To test in browser:\n";
echo "1. Contact page: http://localhost:8000/contact\n";
echo "2. Admin panel: http://localhost:8000/admin/contacts\n";
echo "3. Test page: http://localhost:8000/test-contact.html\n";
