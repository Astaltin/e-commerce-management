<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        $attributes = $request->all();
        $attributes['user_id'] = $user->id;
        $attributes['slug'] = Str::random();
        $cartItems = $user->cartItems;

        if (!$cartItems->count()) {
            return response()->json(['message' => 'You have not yet added anything to the cart'], 400);
        }

        $order = Order::create($attributes);

        foreach ($cartItems as $cartItem) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'flavor_id' => $cartItem->flavor_id,
                'quantity' => $cartItem->quantity,
            ]);
        }

        $user->cartItems()->delete();

        return response()->json(['message' => 'Order placed', 'order' => $order], 201);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json([
            'order' => $order,
            'orderDetails' => $order->orderDetails,
        ]);
    }

    public function update(Order $order): JsonResponse
    {
        $order->update(['status' => 'complete']);

        return response()->json(['message' => 'Your order has been received']);
    }
}
