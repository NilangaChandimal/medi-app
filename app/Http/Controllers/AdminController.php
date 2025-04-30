<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\CustomerPost;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\PharmacyPost;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
{
    $customerCount = Customer::count();
    $pharmacyCount = Pharmacy::count();

    // Example: Last 6 months data
    $chartData = [
        'labels' => [],
        'customers' => [],
        'pharmacies' => []
    ];

    for ($i = 5; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $chartData['labels'][] = $date->format('M Y');
        $chartData['customers'][] = Customer::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
        $chartData['pharmacies'][] = Pharmacy::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
    }

    // Get total amount paid to each pharmacy
    $pharmacyAmounts = Pharmacy::all()->map(function ($pharmacy) {
        // Get all chat IDs where this pharmacy is the sender
        $chatIds = Message::where('sender_type', Pharmacy::class)
            ->where('sender_id', $pharmacy->id)
            ->pluck('chat_id')
            ->unique();

        // Sum payments for those chat IDs
        $amount = Payment::whereIn('chat_id', $chatIds)->sum('amount');

        return [
            'name' => $pharmacy->name,
            'amount' => $amount
        ];
    });

    // Monthly earnings per pharmacy (this month only)
$monthlyEarnings = Pharmacy::all()->map(function ($pharmacy) {
    $chatIds = Message::where('sender_type', Pharmacy::class)
        ->where('sender_id', $pharmacy->id)
        ->pluck('chat_id')
        ->unique();

    $amount = Payment::whereIn('chat_id', $chatIds)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount');

    return [
        'name' => $pharmacy->name,
        'amount' => $amount
    ];
});

// Yearly earnings per pharmacy (this year only)
$yearlyEarnings = Pharmacy::all()->map(function ($pharmacy) {
    $chatIds = Message::where('sender_type', Pharmacy::class)
        ->where('sender_id', $pharmacy->id)
        ->pluck('chat_id')
        ->unique();

    $amount = Payment::whereIn('chat_id', $chatIds)
        ->whereYear('created_at', now()->year)
        ->sum('amount');

    return [
        'name' => $pharmacy->name,
        'amount' => $amount
    ];
});

$monthlyChart = [
    'labels' => $monthlyEarnings->pluck('name'),
    'amounts' => $monthlyEarnings->pluck('amount')
];

$yearlyChart = [
    'labels' => $yearlyEarnings->pluck('name'),
    'amounts' => $yearlyEarnings->pluck('amount')
];


    $pharmacyChartData = [
        'labels' => $pharmacyAmounts->pluck('name'),
        'amounts' => $pharmacyAmounts->pluck('amount')
    ];

    $admin = auth()->guard('admin')->user();

    if ($admin) {
        return view('admin.home', compact(
            'admin',
            'customerCount',
            'pharmacyCount',
            'chartData',
            'pharmacyChartData',
            'monthlyChart',
            'yearlyChart'
        ));
    }

    return redirect()->route('admin.login')->with('error', 'You must be logged in to view the admin dashboard.');
}


public function statisticsDetails($month)
{
    $year = now()->year;
    $date = Carbon::createFromDate($year, $month, 1);

    return view('admin.statistics.details', [
        'month' => $date->format('F Y'),
        'customers' => Customer::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get(),
        'pharmacies' => Pharmacy::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
    ]);
}
public function pharmacystatus()
    {
        $pharmacies = Pharmacy::withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->paginate(10);

        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function toggleStatus(Pharmacy $pharmacy)
    {
        $pharmacy->update([
            'status' => $pharmacy->status === 'active' ? 'inactive' : 'active',
            'verified_at' => $pharmacy->status === 'inactive' ? now() : null
        ]);

        return back()->with('success', 'Pharmacy status updated successfully');
    }
    public function pharmacyshow(Pharmacy $pharmacy)
    {
        // Load ratings with customer info
    $pharmacy->load(['ratings.customer']);

    // Calculate average rating
    $averageRating = $pharmacy->ratings()->avg('rating');
    $ratingCount = $pharmacy->ratings()->count();

    return view('admin.pharmacies.show', compact('pharmacy', 'averageRating', 'ratingCount'));
    }

    public function customerindex()
    {
        $customers = Customer::paginate(10, ['*'], 'customers');
        $pharmacies = Pharmacy::paginate(10, ['*'], 'pharmacies');

        return view('admin.customers.index', compact('customers', 'pharmacies'));
    }

    public function toggleBlock(Request $request, $id)
    {
        $model = $request->type === 'customer' ? Customer::class : Pharmacy::class;
        $user = $model::findOrFail($id);

        $user->update(['is_blocked' => !$user->is_blocked]);

        return back()->with('success', 'User status updated successfully');
    }

    public function postsindex()
    {
        $customerPosts = CustomerPost::with('customer')
            ->latest()
            ->paginate(10, ['*'], 'customer_posts');

        $pharmacyPosts = PharmacyPost::with('pharmacy')
            ->latest()
            ->paginate(10, ['*'], 'pharmacy_posts');

        return view('admin.posts.index', compact('customerPosts', 'pharmacyPosts'));
    }

    public function destroy($id, $type)
    {
        try {
            if ($type === 'customer') {
                $post = CustomerPost::findOrFail($id);
            } elseif ($type === 'pharmacy') {
                $post = PharmacyPost::findOrFail($id);
            } else {
                abort(404);
            }

            $post->delete();
            return back()->with('success', 'Post deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting post: ' . $e->getMessage());
        }
    }

    public function chatindex()
    {
        $chats = Chat::with(['customer', 'pharmacy', 'latestMessage'])
            ->latest()
            ->paginate(10);

        return view('admin.chats.index', compact('chats'));
    }

    public function chatshow(Chat $chat)
    {
        $chat->load(['messages' => function($query) {
            $query->with(['sender'])->latest();
        }, 'customer', 'pharmacy']);

        return view('admin.chats.show', compact('chat'));
    }

    public function customerstatus(Request $request)
    {
        $search = $request->query('search');

        $customers = Customer::when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.show', compact('customers', 'search'));
    }

    public function payments(){
        $payments = Payment::with(['customer', 'pharmacy', 'chat.customer', 'message'])->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }
    public function show(Payment $payment)
{
    return view('admin.payments.show', compact('payment'));
}


    // public function chatdestroy(Chat $chat)
    // {
    //     $chat->messages()->delete();
    //     $chat->delete();

    //     return redirect()->route('admin.chats.index')
    //         ->with('success', 'Chat history deleted successfully');
    // }

}
