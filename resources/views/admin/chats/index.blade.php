@extends('layouts.app')

@section('title', 'Chat Management')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Chat Monitoring Dashboard</h1>
                <p class="text-indigo-100 mt-2">Monitor conversations between customers and pharmacies</p>
            </div>
            <div class="hidden md:block">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-purple-50 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Chat Monitoring</h2>
                <p class="text-sm text-gray-500">View and track customer-pharmacy conversations</p>
            </div>
            <div class="flex space-x-2">
                <span class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Communications
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pharmacy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chat ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chats as $chat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center mr-3">
                                    {{ substr($chat->customer->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $chat->customer->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center mr-3">
                                    {{ substr($chat->pharmacy->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $chat->pharmacy->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center mr-3">
                                    {{ $chat->id }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate">
                            @if($chat->latestMessage)
                                <div class="flex items-center">
                                    <span class="h-2 w-2 rounded-full {{ $chat->latestMessage->sender_type === 'App\Models\Customer' ? 'bg-blue-500' : 'bg-green-500' }} mr-2"></span>
                                    <span class="{{ $chat->latestMessage->sender_type === 'App\Models\Customer' ? 'text-blue-600' : 'text-green-600' }} font-medium">
                                        {{ $chat->latestMessage->sender->name }}:
                                    </span>
                                    <span class="text-gray-600 ml-1 truncate">{{ $chat->latestMessage->message }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">No messages</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($chat->latestMessage)
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $chat->latestMessage->created_at->format('M d, H:i') }}
                                </div>
                            @else
                                <span class="text-gray-400 italic">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.chats.show', $chat) }}"
                               class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $chats->links() }}
        </div>
    </div>
</div>
@endsection
