#!/usr/bin/env php
<?php

/**
 * Quick Contact System Test
 * Run this to verify the contact system is working
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║        ITCenter E-commerce - Contact System Test         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Test 1: Create a test contact
echo "📝 Creating test contact...\n";
try {
    $contact = \App\Models\Contact::create([
        'name' => 'Test User ' . time(),
        'email' => 'test' . time() . '@example.com',
        'subject' => 'Test Subject',
        'message' => 'Test message created at ' . now()->toDateTimeString(),
        'status' => 'pending'
    ]);
    echo "   ✓ Contact created (ID: {$contact->id})\n\n";
} catch (\Exception $e) {
    echo "   ✗ FAILED: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Verify in database
echo "🔍 Verifying in database...\n";
$found = \App\Models\Contact::find($contact->id);
if ($found) {
    echo "   ✓ Contact found in database\n\n";
} else {
    echo "   ✗ FAILED: Contact not found\n\n";
    exit(1);
}

// Test 3: Check admin view
echo "👤 Checking admin view...\n";
$adminMessages = \App\Models\Contact::orderBy('created_at', 'desc')->take(3)->get();
echo "   Latest 3 contacts:\n";
foreach ($adminMessages as $msg) {
    echo "   • [{$msg->id}] {$msg->name} - {$msg->subject} [{$msg->status}]\n";
}
echo "\n";

// Test 4: Statistics
echo "📊 Statistics:\n";
$stats = [
    'total' => \App\Models\Contact::count(),
    'pending' => \App\Models\Contact::where('status', 'pending')->count(),
    'read' => \App\Models\Contact::where('status', 'read')->count(),
    'archived' => \App\Models\Contact::where('status', 'archived')->count(),
];
echo "   Total: {$stats['total']} | Pending: {$stats['pending']} | Read: {$stats['read']} | Archived: {$stats['archived']}\n\n";

// Test 5: Routes check
echo "🛣️  Checking routes...\n";
try {
    $contactRoute = route('contact');
    $apiRoute = route('api.contact.store');
    $adminRoute = route('admin.contacts.index');
    echo "   ✓ All routes configured correctly\n\n";
} catch (\Exception $e) {
    echo "   ✗ FAILED: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Cleanup test data
echo "🧹 Cleaning up test data...\n";
$contact->forceDelete();
echo "   ✓ Test contact deleted\n\n";

// Final summary
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                    ✅ ALL TESTS PASSED!                   ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "🌐 Next steps:\n";
echo "   1. Visit: http://localhost:8000/contact\n";
echo "   2. Send a test message\n";
echo "   3. Check: http://localhost:8000/admin/contacts\n";
echo "\n";

exit(0);
