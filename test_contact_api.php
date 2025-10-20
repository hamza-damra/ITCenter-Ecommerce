<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test creating a contact
echo "Testing Contact API...\n\n";

try {
    $contact = \App\Models\Contact::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message from the API test script.',
        'status' => 'pending'
    ]);
    
    echo "✓ Contact created successfully!\n";
    echo "  ID: " . $contact->id . "\n";
    echo "  Name: " . $contact->name . "\n";
    echo "  Email: " . $contact->email . "\n";
    echo "  Subject: " . $contact->subject . "\n";
    echo "  Status: " . $contact->status . "\n";
    echo "  Created: " . $contact->created_at . "\n\n";
    
    // Count total contacts
    $total = \App\Models\Contact::count();
    echo "Total contacts in database: " . $total . "\n\n";
    
    // Test fetching from admin controller perspective
    echo "Testing Admin View...\n";
    $messages = \App\Models\Contact::orderBy('created_at', 'desc')->take(5)->get();
    echo "Found " . $messages->count() . " messages\n";
    
    foreach ($messages as $msg) {
        echo "  - [{$msg->id}] {$msg->name} ({$msg->email}) - {$msg->subject} [{$msg->status}]\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
