<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function startChat(Post $post, Request $request)
    {
        $chat = Chat::firstOrCreate([
            'post_id' => $post->id,
            'customer_id' => $post->customer_id,
            'pharmacy_id' => $request->user()->pharmacy->id
        ]);

        return response()->json($chat);
    }

    public function sendMessage(Chat $chat, Request $request)
    {
        $message = $chat->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $request->message
        ]);

        // Broadcast message using WebSockets
        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message, 201);
    }

    public function getMessages(Chat $chat)
    {
        return response()->json($chat->messages()->with('user')->paginate(20));
    }
}
