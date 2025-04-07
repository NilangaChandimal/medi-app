<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
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

        return view('chats.index', compact('chats', 'userType'));
    }

    public function show($id)
    {
        $chat = Chat::findOrFail($id);
        $messages = $chat->messages;
        $user = Auth::user();

        // Filter chats based on the user type (Customer or pharmacy)
        if ($user instanceof \App\Models\Customer) {
            $chats = Chat::where('customer_id', $user->id)->get();
            $userType = 'customer';
        } elseif ($user instanceof \App\Models\Pharmacy) {
            $chats = Chat::where('pharmacy_id', $user->id)->get();
            $userType = 'pharmacy';
        } else {
            $chats = collect(); // No chats for other users
            $userType = '';
        }

        return view('chats.show', compact('chat', 'messages', 'userType', 'chats'));
    }



    public function storeMessage(Request $request, $id)
{
    $request->validate([
        'message' => 'nullable|string',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,zip|max:20480',
        'audio' => 'nullable|string',
    ]);

    $chat = Chat::findOrFail($id);
    $user = Auth::user();

    $message = new Message();
    $message->chat_id = $chat->id;
    $message->sender_id = $user->id;
    $message->sender_type = get_class($user); // Store the sender type (Customer, pharmacy, etc.)

    // Handle text message
    if ($request->filled('message')) {
        $message->message = $request->message;
    }

    // Handle file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('messages', 'public'); // Store file in public disk
        $message->file_path = $filePath;
    }

    // Handle audio
    if ($request->filled('audio')) {
        $audioData = $request->audio;
        if (preg_match('/data:audio\/[a-zA-Z]*;base64,(.*)/', $audioData, $matches)) {
            $audioContent = base64_decode($matches[1]);
            $audioPath = 'audios/' . uniqid() . '.mp3';
            Storage::disk('public')->put($audioPath, $audioContent);
            $message->audio = $audioPath;
        }
    }

    $message->save();

    // For real-time update
    broadcast(new \App\Events\MessageSent($message))->toOthers();

    $userType = $request->user() instanceof \App\Models\Customer ? 'customer' : 'pharmacy';
    return redirect()->route($userType . '.chats.show', $chat->id);
}
}
