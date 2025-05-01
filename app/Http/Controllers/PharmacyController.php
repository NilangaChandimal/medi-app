<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\CustomerPost;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\PharmacyPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PharmacyController extends Controller
{

    public function index()
    {
        $pharmacy = auth()->guard('pharmacy')->user();

        if (!$pharmacy) {
            return redirect()->route('pharmacy.login')->with('error', 'Please login to continue.');
        }

        $pharmacyPosts = PharmacyPost::with('pharmacy')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $medicinePosts = CustomerPost::with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        $allPosts = $pharmacyPosts->merge($medicinePosts)->sortByDesc('created_at');

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

        $notifications = Notification::with('customer', 'posts')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

            $latestChats = Chat::where('pharmacy_id', auth()->id())
    ->with(['customer', 'lastMessage'])
    ->latest('updated_at')
    ->take(5)
    ->get();

    $pharmacyId = auth()->id();
    $payments = Payment::whereHas('chat', function ($query) use ($pharmacyId) {
        $query->where('pharmacy_id', $pharmacyId);
    })
    ->with(['chat.customer'])
    ->latest()
    ->take(5)
    ->get();

        return view('pharmacy.home', compact('pharmacy', 'paginatedPosts', 'chats', 'userType', 'notifications', 'latestChats', 'payments'));
    }

    public function showModal($id)
{
    Log::info('Fetching modal for post ID: ' . $id);

    $post = CustomerPost::with(['customer', 'pharmacy'])->find($id);

    if (!$post) {
        return response('Post not found', 404);
    }
    if (!empty($post->image) && is_string($post->image)) {
        $post->image = json_decode($post->image, true);
    }
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

    return view('Pharmacy.post_modal', compact('post', 'chats', 'userType'));
}

public function markAsRead($notificationId)
{
    $notification = Notification::findOrFail($notificationId);

    if ($notification->pharmacy_id !== auth()->guard('pharmacy')->id()) {
        abort(403, 'Unauthorized');
    }

    $notification->read_at = Carbon::now();
    $notification->save();

    return response()->json(['message' => 'Notification marked as read']);
}

    public function startChat(Request $request, Pharmacy $pharmacy)
    {
        $customer = Auth::user();

        if (!$customer || !$customer instanceof \App\Models\Customer) {
            return redirect()->route('customer.home')->with('error', 'You need to be logged in as a customer to start a chat.');
        }

        $chat = Chat::firstOrCreate([
            'customer_id' => $customer->id,
            'pharmacy_id' => $pharmacy->id
        ]);

        return redirect()->route('customer.chats.show', $chat->id);
    }
}
