@extends('layouts.app')

@section('title', 'Chat Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-bold">Chat Monitoring</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pharmacy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chats as $chat)
                    <tr>
                        <td class="px-6 py-4">{{ $chat->customer->name }}</td>
                        <td class="px-6 py-4">{{ $chat->pharmacy->name }}</td>
                        <td class="px-6 py-4 max-w-xs truncate">
                            @if($chat->latestMessage)
                                <span class="{{ $chat->latestMessage->sender_type === 'App\Models\Customer' ? 'text-blue-600' : 'text-green-600' }}">
                                    {{ $chat->latestMessage->sender->name }}:
                                </span>
                                {{ $chat->latestMessage->message }}
                            @else
                                No messages
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $chat->latestMessage->created_at->format('M d, H:i') ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.chats.show', $chat) }}"
                               class="text-indigo-600 hover:text-indigo-900">
                                View
                            </a>
                            {{-- <form class="inline-block" method="POST"
                                  action="{{ route('admin.chats.destroy', $chat) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Delete entire chat history?')">
                                    Delete
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t">
            {{ $chats->links() }}
        </div>
    </div>
</div>
@endsection
