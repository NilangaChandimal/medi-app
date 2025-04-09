<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Post;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Payment::with(['chat.pharmacy', 'chat.customer', 'message'])
            ->whereHas('chat', function($query) {
                $query->where('pharmacy_id', auth()->user()->id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('Pharmacy.orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $payment = Payment::where('id', $orderId)
            ->whereHas('chat', function($query) {
                $query->where('pharmacy_id', auth()->user()->id);
            })
            ->firstOrFail();
        $order = Payment::with(['chat.customer', 'message'])
            ->where('id', $orderId)
            ->whereHas('chat', function($query) {
                $query->where('pharmacy_id', auth()->user()->id);
            })
            ->firstOrFail();

        // Parse order details from message
        $orderDetails = $this->parseOrderDetails($order->message->message);

        return view('Pharmacy.orders.show', compact('order', 'orderDetails', 'payment'));
    }

    private function parseOrderDetails($message)
    {
        $pattern = '/Offer: (.+) \| Price: (\d+\.?\d*) \| Quantity: (\d+) \| Total: (\d+\.?\d*)/';
        preg_match($pattern, $message, $matches);

        return [
            'medicine' => $matches[1] ?? 'Unknown',
            'price' => $matches[2] ?? 0,
            'quantity' => $matches[3] ?? 0,
            'total' => $matches[4] ?? 0
        ];
    }

    public function update(Request $request, $orderId)
{
    $validated = $request->validate([
        'status' => 'required|in:processing,shipped,completed'
    ]);

    $order = Payment::whereHas('chat', function($query) {
            $query->where('pharmacy_id', auth()->user()->id);
        })
        ->findOrFail($orderId);

    $order->update(['status' => $validated['status']]);

    return redirect()->route('pharmacy.orders.index')->with('success', 'Order status updated successfully.');
}

public function customerindex()
    {
        $orders = Payment::with(['chat.pharmacy', 'message'])
            ->where('customer_id', auth()->id())
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('Customer.orders.index', compact('orders'));
    }

    public function customershow($orderId)
    {
        $order = Payment::with(['chat.pharmacy', 'message', 'rating'])
            ->where('id', $orderId)
            ->where('customer_id', auth()->id())
            ->firstOrFail();

        $orderDetails = $this->customerparseOrderDetails(
            optional($order->message)->message ?? ''
        );

        return view('customer.orders.show', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'existingRating' => $order->rating
        ]);
    }

    private function customerparseOrderDetails($message)
    {
        $pattern = '/Offer:\s*(.+?)\s*\|\s*Price:\s*(\d+\.?\d*)\s*\|\s*Quantity:\s*(\d+)\s*\|\s*Total:\s*(\d+\.?\d*)/i';
        preg_match($pattern, $message, $matches);

        return [
            'medicine' => $matches[1] ?? 'Unknown Medicine',
            'price' => (float)($matches[2] ?? 0),
            'quantity' => (int)($matches[3] ?? 0),
            'total' => (float)($matches[4] ?? 0)
        ];
    }

//     public function store(Request $request)
// {
//     $request->validate([
//         'pharmacy_id' => 'required|exists:pharmacies,id',
//         'customer_post_id' => 'required|exists:customer_posts,id',
//     ]);

//     Order::create([
//         'customer_id' => auth('customer')->id(),
//         'pharmacy_id' => $request->pharmacy_id,
//         'customer_post_id' => $request->customer_post_id,
//         'status' => 'pending',
//     ]);

//     return back()->with('success', 'Order placed successfully!');
// }


    // public function create(Request $request, Post $post)
    // {
    //     $request->validate([
    //         'total_amount' => 'required|numeric',
    //         'payment_method' => 'required|in:card,upi,cod'
    //     ]);

    //     $order = Order::create([
    //         'customer_id' => $post->customer_id,
    //         'pharmacy_id' => $request->user()->pharmacy->id,
    //         'post_id' => $post->id,
    //         'status' => 'pending',
    //         'total_amount' => $request->total_amount
    //     ]);

    //     // Create payment record
    //     $order->payment()->create([
    //         'payment_method' => $request->payment_method,
    //         'status' => $request->payment_method === 'cod' ? 'success' : 'pending'
    //     ]);

    //     return response()->json($order, 201);
    // }

    // public function updateStatus(Order $order, Request $request)
    // {
    //     $request->validate(['status' => 'required|in:confirmed,processing,shipped,delivered,cancelled']);

    //     $order->update(['status' => $request->status]);

    //     // Notify customer about status change
    //     $order->customer->notify(new OrderStatusUpdated($order));

    //     return response()->json($order);
    // }
}
