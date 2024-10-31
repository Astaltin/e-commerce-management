<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    public function index()
    {
        return response()->json([
            'orders' => Order::all(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'preparing']);

            return response()->json(['message' => 'Order is now being prepared']);
        }

        if ($order->status === 'preparing') {
            $order->update(['status' => 'ongoing']);

            return response()->json(['message' => 'Order is now ongoing']);
        }

        $order->update(['status' => 'out for delivery']);

        return response()->json(['message' => 'Order is now out for delivery']);
    }

    public function show(Order $order)
    {
        return response()->json([
            'order' => $order,
            'orderDetails' => $order->orderDetails,
        ]);
    }
}
