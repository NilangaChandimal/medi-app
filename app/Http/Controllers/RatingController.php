<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Payment;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);

        $order = Payment::with('chat.pharmacy')
            ->where('id', $orderId)
            ->where('customer_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if already rated
        if($order->rating) {
            return redirect()->back()->withErrors(['rating' => 'You already rated this order']);
        }

        Rating::create([
            'pharmacy_id' => $order->chat->pharmacy_id,
            'customer_id' => auth()->id(),
            'order_id' => $orderId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return redirect()->back()->with('success', 'Thank you for your rating!');
    }
}
