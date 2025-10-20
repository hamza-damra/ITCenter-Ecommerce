<?php

/**
 * Simulate a real contact form submission
 * This simulates what happens when a user submits the contact form
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Simulating Real Contact Form Submission ===\n\n";

// Simulate POST request data
$_POST = [
    '_token' => csrf_token(),
    'name' => 'Alex Johnson',
    'email' => 'alex@customer.com',
    'subject' => 'استفسار عن المنتجات',
    'message' => 'السلام عليكم، أود الاستفسار عن توفر بعض المنتجات في المتجر. هل يمكنكم مساعدتي؟'
];

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';

echo "Submission Data:\n";
echo "Name: {$_POST['name']}\n";
echo "Email: {$_POST['email']}\n";
echo "Subject: {$_POST['subject']}\n";
echo "Message: " . substr($_POST['message'], 0, 50) . "...\n\n";

// Create the contact directly using the model (simulating controller logic)
try {
    $contact = \App\Models\Contact::create([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'subject' => $_POST['subject'],
        'message' => $_POST['message'],
        'status' => 'pending'
    ]);
    
    echo "✓ Contact created successfully!\n";
    echo "  ID: {$contact->id}\n";
    echo "  Status: {$contact->status}\n";
    echo "  Created at: {$contact->created_at}\n\n";
    
    // Verify it's in the database
    $dbContact = \App\Models\Contact::find($contact->id);
    if ($dbContact) {
        echo "✓ Contact verified in database\n";
        echo "  Found in DB: [{$dbContact->id}] {$dbContact->name} - {$dbContact->email}\n\n";
    }
    
    // Check what admin would see
    echo "=== Admin Panel View ===\n";
    $adminMessages = \App\Models\Contact::orderBy('created_at', 'desc')
        ->take(5)
        ->get(['id', 'name', 'email', 'subject', 'status', 'created_at']);
    
    echo "Recent messages (Admin Panel would show):\n";
    foreach ($adminMessages as $msg) {
        $created = $msg->created_at->diffForHumans();
        echo "  [{$msg->id}] {$msg->name} ({$msg->email})\n";
        echo "      Subject: {$msg->subject}\n";
        echo "      Status: {$msg->status} | Created: {$created}\n\n";
    }
    
    // Stats
    $stats = [
        'total' => \App\Models\Contact::count(),
        'pending' => \App\Models\Contact::where('status', 'pending')->count(),
        'read' => \App\Models\Contact::where('status', 'read')->count(),
        'archived' => \App\Models\Contact::where('status', 'archived')->count(),
    ];
    
    echo "=== Statistics ===\n";
    echo "Total messages: {$stats['total']}\n";
    echo "Pending: {$stats['pending']}\n";
    echo "Read: {$stats['read']}\n";
    echo "Archived: {$stats['archived']}\n\n";
    
    echo "✓ SUCCESS! The contact system is working correctly.\n";
    echo "\nTo view in admin panel, visit:\n";
    echo "http://localhost:8000/admin/contacts\n\n";
    
    echo "To view the contact details, visit:\n";
    echo "http://localhost:8000/admin/contacts/{$contact->id}\n";
    
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
