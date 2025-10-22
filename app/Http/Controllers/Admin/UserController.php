<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->withCount(['orders', 'favorites', 'cartItems'])->paginate(20);

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'customer_users' => User::where('role', 'customer')->count(),
            'users_with_orders' => User::has('orders')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,customer',
        ]);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.user_created_successfully'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['orders.items.product', 'favorites.product', 'cartItems.product']);
        
        // User statistics
        $userStats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->sum('total'),
            'total_favorites' => $user->favorites()->count(),
            'cart_items' => $user->cartItems()->count(),
            'average_order_value' => $user->orders()->count() > 0 
                ? $user->orders()->avg('total') 
                : 0,
            'last_order_date' => $user->orders()->latest()->first()?->created_at,
        ];

        // Recent orders
        $recentOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.users.show', compact('user', 'userStats', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,customer',
        ]);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.user_updated_successfully'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.cannot_delete_own_account')
                ], 400);
            }
            return redirect()->route('admin.users.index')
                ->with('error', __('messages.cannot_delete_own_account'));
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.user_deleted_successfully')
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.user_deleted_successfully'));
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Prevent deleting own account
        $userIds = array_diff($request->user_ids, [auth()->id()]);

        User::whereIn('id', $userIds)->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.users_deleted_successfully', ['count' => count($userIds)]));
    }
}
