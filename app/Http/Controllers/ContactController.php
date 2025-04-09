<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create()
{
    $user = auth()->user();
    $tickets = SupportTicket::where('user_id', $user->id)
        ->where('user_type', get_class($user))
        ->orderBy('created_at', 'desc')
        ->get();

    return view('contact.form', compact('tickets'));
}

    public function store(Request $request)
{
    $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:2000',
    ]);

    $user = auth()->user();

    SupportTicket::create([
        'subject' => $request->subject,
        'message' => $request->message,
        'user_id' => $user->id,
        'user_type' => get_class($user), // Will return either App\Models\Customer or App\Models\Pharmacy
    ]);

    return redirect()->back()->with('success', 'Your message has been sent to admin!');
}


}
