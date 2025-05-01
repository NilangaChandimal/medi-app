<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Post;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request)
{
    $query = Payment::with(['chat.pharmacy', 'chat.customer', 'message'])
        ->whereHas('chat', function ($q) {
            $q->where('pharmacy_id', auth()->user()->id);
        });

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhereHas('chat.customer', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date')) {
        $now = now();
        switch ($request->date) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'week':
                $query->where('created_at', '>=', $now->copy()->subDays(7));
                break;
            case 'month':
                $query->where('created_at', '>=', $now->copy()->subDays(30));
                break;
        }
    }

    $orders = $query->orderBy('created_at', 'DESC')->paginate(10)->appends($request->query());

    return view('Pharmacy.orders.index', compact('orders'));
}


    public function show($orderId)
    {
        $payment = Payment::where('id', $orderId)
            ->whereHas('chat', function ($query) {
                $query->where('pharmacy_id', auth()->user()->id);
            })
            ->firstOrFail();
        $order = Payment::with(['chat.customer', 'message'])
            ->where('id', $orderId)
            ->whereHas('chat', function ($query) {
                $query->where('pharmacy_id', auth()->user()->id);
            })
            ->firstOrFail();

        $orderDetails = $this->parseOrderDetails($order->message->message);

        return view('Pharmacy.orders.show', compact('order', 'orderDetails', 'payment'));
    }

    private function parseOrderDetails($message)
{
    $items = [];
    $total = 0;

    $lines = explode("\n", $message);

    foreach ($lines as $line) {
        if (preg_match('/^\d+\.\s*(.+?)\s*-\s*\$?(\d+(?:\.\d+)?)\s*x\s*(\d+)/', trim($line), $match)) {
            $medicine = trim($match[1]);
            $price = (float) $match[2];
            $quantity = (int) $match[3];
            $subtotal = $price * $quantity;

            $items[] = [
                'medicine' => $medicine,
                'price' => $price,
                'quantity' => $quantity,
                'total' => $subtotal,
            ];
        }

        // Match total
        if (preg_match('/Total Offer Total:\s*\$?(\d+(?:\.\d+)?)/', $line, $totalMatch)) {
            $total = (float) $totalMatch[1];
        }
    }

    return [
        'items' => $items,
        'total' => $total,
    ];
}

    public function update(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,shipped,completed'
        ]);

        $order = Payment::whereHas('chat', function ($query) {
            $query->where('pharmacy_id', auth()->user()->id);
        })
            ->findOrFail($orderId);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('pharmacy.orders.index')->with('success', 'Order status updated successfully.');
    }

    public function customerindex(Request $request)
{
    $query = Payment::with(['chat.pharmacy', 'message'])
        ->where('customer_id', auth()->id());

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date')) {
        $now = now();
        switch ($request->date) {
            case 'week':
                $query->where('created_at', '>=', $now->copy()->subDays(7));
                break;
            case 'month':
                $query->where('created_at', '>=', $now->copy()->subDays(30));
                break;
            case 'year':
                $query->whereYear('created_at', $now->year);
                break;
        }
    }

    if ($request->filled('pharmacy')) {
        $query->whereHas('chat.pharmacy', function ($q) use ($request) {
            $q->where('name', $request->pharmacy);
        });
    }

    $orders = $query->orderBy('created_at', 'DESC')->paginate(10);

    return view('Customer.orders.index', compact('orders'));
}


    public function customershow($orderId)
    {
        $order = Payment::with(['chat.pharmacy', 'message', 'rating'])
            ->where('id', $orderId)
            ->where('customer_id', auth()->id())
            ->firstOrFail();

        $orderDetails = $order->message
            ? $this->customerparseOrderDetails($order->message->message)
            : ['medicines' => [], 'total' => null];


        return view('customer.orders.show', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'existingRating' => $order->rating
        ]);
    }

    private function customerparseOrderDetails($message)
    {
        $lines = explode("\n", $message);
        $medicines = [];
        $total = null;

        foreach ($lines as $line) {
            if (preg_match('/^\d+\.\s+(.*?)\s+-\s+\$(\d{1,3}(?:,\d{3})*(?:\.\d{2})?)\s+x\s+(\d+)\s+=\s+\$(\d{1,3}(?:,\d{3})*(?:\.\d{2})?)$/', trim($line), $matches)) {
                $medicines[] = [
                    'name' => $matches[1],
                    'price' => (float) str_replace(',', '', $matches[2]),
                    'quantity' => (int) $matches[3],
                    'subtotal' => (float) str_replace(',', '', $matches[4]),
                ];
            }

            if (preg_match('/Total Offer Total:\s*(\d+(?:\.\d{2})?)/', $line, $match)) {
                $total = (float) $match[1];
            }
        }

        return [
            'medicines' => $medicines,
            'total' => $total,
        ];
    }
}
