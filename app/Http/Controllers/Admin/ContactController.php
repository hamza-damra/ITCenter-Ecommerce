<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $query = Contact::orderBy('created_at', 'desc');

        // Search by name, email, or subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_messages' => Contact::count(),
            'pending_messages' => Contact::where('status', 'pending')->count(),
            'read_messages' => Contact::where('status', 'read')->count(),
            'archived_messages' => Contact::where('status', 'archived')->count(),
        ];

        return view('admin.contacts.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified contact message
     */
    public function show($id)
    {
        $message = Contact::findOrFail($id);
        
        // Mark as read if it's pending
        if ($message->status === 'pending') {
            $message->markAsRead();
        }
        
        return view('admin.contacts.show', compact('message'));
    }

    /**
     * Update message status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,read,archived',
        ]);

        $message = Contact::findOrFail($id);
        $message->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Message status updated successfully.');
    }

    /**
     * Remove the specified contact message
     */
    public function destroy($id)
    {
        $message = Contact::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message deleted successfully.');
    }

    /**
     * Bulk delete messages
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:contact_messages,id',
        ]);

        $deletedCount = Contact::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', __('messages.messages_deleted_successfully', ['count' => $deletedCount]));
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:contact_messages,id',
            'status' => 'required|in:pending,read,archived',
        ]);

        $updatedCount = Contact::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('messages.messages_status_updated_successfully', ['count' => $updatedCount]));
    }
}
