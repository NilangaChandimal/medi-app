<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request, Post $post)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:card,upi,cod'
        ]);

        $order = Order::create([
            'customer_id' => $post->customer_id,
            'pharmacy_id' => $request->user()->pharmacy->id,
            'post_id' => $post->id,
            'status' => 'pending',
            'total_amount' => $request->total_amount
        ]);

        // Create payment record
        $order->payment()->create([
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cod' ? 'success' : 'pending'
        ]);

        return response()->json($order, 201);
    }

    public function updateStatus(Order $order, Request $request)
    {
        $request->validate(['status' => 'required|in:confirmed,processing,shipped,delivered,cancelled']);

        $order->update(['status' => $request->status]);

        // Notify customer about status change
        $order->customer->notify(new OrderStatusUpdated($order));

        return response()->json($order);
    }
}
