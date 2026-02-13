<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderCancellation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', __t('messages.please_login'));
        }

        $query = Order::with(['items.product'])
            ->forUser($user->id)
            ->recent();

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->byStatus($request->status);
        }

        $orders = $query->paginate(10);
        
        // Get status counts
        $statusCounts = [
            'all' => Order::forUser($user->id)->count(),
            'pending' => Order::forUser($user->id)->byStatus('pending')->count(),
            'processing' => Order::forUser($user->id)->byStatus('processing')->count(),
            'shipped' => Order::forUser($user->id)->byStatus('shipped')->count(),
            'delivered' => Order::forUser($user->id)->byStatus('delivered')->count(),
            'cancelled' => Order::forUser($user->id)->byStatus('cancelled')->count(),
        ];

        return view('orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the order confirmation page (PRG pattern landing page)
     */
    public function confirmation($orderNumber)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', __t('messages.please_login'));
        }

        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->forUser($user->id)
            ->firstOrFail();

        return view('orders.confirmation', compact('order'));
    }

    public function show($orderNumber)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', __t('messages.please_login'));
        }

        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->forUser($user->id)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function cancel(Request $request, $orderNumber)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => __t('messages.please_login'),
            ], 401);
        }

        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->forUser($user->id)
            ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return response()->json([
                'message' => __t('messages.cannot_cancel_order'),
            ], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($order, $request, $user) {
            // Restore stock for each order item
            foreach ($order->items as $item) {
                if ($item->product && $item->product->track_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                    $item->product->updateStockStatus();
                }
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            OrderCancellation::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'reason' => $request->reason,
                'note' => $request->note,
            ]);
        });

        return response()->json([
            'message' => __t('messages.order_cancelled_successfully'),
        ]);
    }
}
