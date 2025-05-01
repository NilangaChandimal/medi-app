<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\CustomerPost;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\PharmacyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
{
    $customer = auth()->guard('customer')->user();

    if (!$customer) {
        return redirect()->route('customer.login')->with('error', 'Please login to continue.');
    }

    // Load posts
    $pharmacyPosts = PharmacyPost::with('pharmacy')
        ->orderBy('created_at', 'desc')
        ->get();

    $medicinePosts = CustomerPost::with('customer')
        ->where('visibility', 'public')
        ->orderBy('created_at', 'desc')
        ->get();

    // Merge and sort by created_at
    $allPosts = $pharmacyPosts->merge($medicinePosts)->sortByDesc('created_at');

    // Paginate manually
    $perPage = 10;
    $page = request()->get('page', 1);
    $paginatedPosts = new \Illuminate\Pagination\LengthAwarePaginator(
        $allPosts->forPage($page, $perPage),
        $allPosts->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    $latestChats = Chat::where('customer_id', auth()->id())
    ->with(['pharmacy', 'lastMessage'])
    ->latest('updated_at')
    ->take(5)
    ->get();

    $orders = Payment::where('customer_id', auth()->id())
    ->latest('updated_at')
    ->take(5)
    ->get();

    return view('customer.home', compact('customer', 'paginatedPosts', 'latestChats', 'orders'));
}

public function pharmacy(Request $request)
{
    $search = $request->input('search');

    $query = Pharmacy::query()
        ->withAvg('ratings', 'rating'); // Load average rating

    // Apply search if provided
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('city', 'LIKE', "%{$search}%");
        });
    }

    $pharmacies = $query->orderBy('created_at', 'desc')->get();

    return view('Customer.pharmacy', compact('pharmacies', 'search'));
}


}
