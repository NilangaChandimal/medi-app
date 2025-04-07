<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\CustomerPost;
use App\Models\Pharmacy;
use App\Models\PharmacyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PharmacyController extends Controller
{

    public function index()
    {
        $pharmacy = auth()->guard('pharmacy')->user();

        if (!$pharmacy) {
            // Optional: redirect to login or show error
            return redirect()->route('pharmacy.login')->with('error', 'Please login to continue.');
        }

        $pharmacyPosts = PharmacyPost::with('pharmacy')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $medicinePosts = CustomerPost::with('customer')
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
    $user = Auth::user();
    $chats = Chat::with('lastMessage')->get();

        if ($user instanceof \App\Models\Customer) {
            $chats = Chat::where('customer_id', $user->id)->get();
            $userType = 'customer';
        } elseif ($user instanceof \App\Models\Pharmacy) {
            $chats = Chat::where('pharmacy_id', $user->id)->get();
            $userType = 'pharmacy';
        } else {
            $chats = collect();
            $userType = '';
        }


        return view('pharmacy.home', compact('pharmacy', 'paginatedPosts', 'chats', 'userType'));
    }

    public function startChat(Request $request, Pharmacy $pharmacy)
    {
        $customer = Auth::user();

        // Ensure the user is authenticated and is a customer
        if (!$customer || !$customer instanceof \App\Models\Customer) {
            return redirect()->route('customer.home')->with('error', 'You need to be logged in as a customer to start a chat.');
        }

        // Create or find an existing chat
        $chat = Chat::firstOrCreate([
            'customer_id' => $customer->id,
            'pharmacy_id' => $pharmacy->id
        ]);

        // Redirect to the customer's chat page
        return redirect()->route('customer.chats.show', $chat->id);
    }

    // public function dashboard(Request $request)
    // {
    //     $pharmacy = $request->user()->pharmacy()->withCount([
    //         'orders',
    //         'chats',
    //         'notifications' => function($query) {
    //             $query->whereNull('read_at');
    //         }
    //     ])->first();

    //     return response()->json([
    //         'stats' => $pharmacy,
    //         'pending_orders' => $pharmacy->orders()->where('status', 'pending')->count(),
    //         'active_chats' => $pharmacy->chats()->count()
    //     ]);
    // }

    // public function updateProfile(Request $request)
    // {
    //     $data = $request->validate([
    //         'address' => 'sometimes|string',
    //         'phone' => 'sometimes|string',
    //         'license_details' => 'sometimes|file'
    //     ]);

    //     $pharmacy = $request->user()->pharmacy;

    //     if ($request->hasFile('license_details')) {
    //         Storage::delete($pharmacy->license_details);
    //         $data['license_details'] = $request->file('license_details')->store('licenses');
    //     }

    //     $pharmacy->update($data);

    //     return response()->json($pharmacy);
    // }
}
