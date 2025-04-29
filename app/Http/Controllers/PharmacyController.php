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

        $notifications = Notification::with('customer', 'posts')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

            $latestChats = Chat::where('pharmacy_id', auth()->id())
    ->with(['customer', 'lastMessage']) // eager load related models
    ->latest('updated_at') // or use 'lastMessage.created_at' if needed
    ->take(5)
    ->get();

    $pharmacyId = auth()->id(); // assuming pharmacy is authenticated using default guard
    $payments = Payment::whereHas('chat', function ($query) use ($pharmacyId) {
        $query->where('pharmacy_id', $pharmacyId);
    })
    ->with(['chat.customer']) // eager load to avoid N+1 issues
    ->latest()
    ->take(5) // or paginate if needed
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

    // Optionally, authorize this if needed (e.g., belongs to auth pharmacy)
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

    // public function sendMedicineDetails(Request $request)
    // {
    //     $request->validate([
    //         'message_id' => 'required|exists:chat_messages,id',
    //         'medicine_name' => 'required|string',
    //         'medicine_price' => 'required|numeric',
    //         'total_amount' => 'required|numeric',
    //     ]);

    //     // Retrieve the chat message to link the medicine details to the right chat
    //     $message = ChatMessage::findOrFail($request->message_id);

    //     // Update or create a new medicine order message (you can customize this part)
    //     $message->medicine_name = $request->medicine_name;
    //     $message->medicine_price = $request->medicine_price;
    //     $message->total_amount = $request->total_amount;
    //     $message->is_order_sent = true; // You may want to track this
    //     $message->save();

    //     return back()->with('success', 'Medicine details sent successfully!');
    // }


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
