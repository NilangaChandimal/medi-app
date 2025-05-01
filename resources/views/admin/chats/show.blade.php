@extends('layouts.app')

@section('title', 'Chat Show')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 mb-6">
        <div class="px-6 py-5 border-b bg-gradient-to-r from-indigo-50 to-purple-50 flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Conversation Details
                </h2>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <div class="flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        <div class="h-6 w-6 rounded-full bg-blue-200 flex items-center justify-center mr-2 font-medium">
                            {{ substr($chat->customer->name, 0, 1) }}
                        </div>
                        <span>{{ $chat->customer->name }}</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <div class="flex items-center bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                        <div class="h-6 w-6 rounded-full bg-purple-200 flex items-center justify-center mr-2 font-medium">
                            {{ substr($chat->pharmacy->name, 0, 1) }}
                        </div>
                        <span>{{ $chat->pharmacy->name }}</span>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Started {{ $chat->created_at->diffForHumans() }}
                </p>
            </div>
            <a href="{{ route('admin.chats.index') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to chats
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Message History
            </h3>
            <div class="flex items-center text-xs text-gray-500">
                <span class="flex items-center mr-3">
                    <div class="h-3 w-3 bg-blue-500 rounded-full mr-1"></div>
                    Customer
                </span>
                <span class="flex items-center">
                    <div class="h-3 w-3 bg-green-500 rounded-full mr-1"></div>
                    Pharmacy
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto bg-gray-50">
            @foreach($chat->messages as $message)
            <div class="flex {{ $message->sender_type === 'App\Models\Customer' ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-lg rounded-lg shadow-sm
                    {{ $message->sender_type === 'App\Models\Customer'
                        ? 'bg-white border-l-4 border-blue-500'
                        : 'bg-white border-r-4 border-green-500' }}">
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-medium
                                {{ $message->sender_type === 'App\Models\Customer' ? 'bg-blue-500' : 'bg-green-500' }}">
                                {{ substr($message->sender->name ?? 'N/A', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $message->sender->name ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message->created_at->format('M d, H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="pl-10">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>

                            @if($message->file_path)
                            <div class="mt-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                                @if(Str::startsWith($message->file_path, 'image/'))
                                <div class="flex flex-col items-center">
                                    <img src="{{ asset('storage/' . $message->file_path) }}"
                                        alt="Attached image"
                                        class="max-w-full h-auto rounded-lg shadow-sm">
                                    <a href="{{ asset('storage/' . $message->file_path) }}"
                                    class="mt-2 text-xs text-indigo-600 hover:text-indigo-900 flex items-center"
                                    download>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download image
                                    </a>
                                </div>
                                @elseif(Str::startsWith($message->file_path, 'video/'))
                                <div class="flex flex-col items-center">
                                    <video controls class="max-w-full rounded-lg shadow-sm">
                                        <source src="{{ asset('storage/' . $message->file_path) }}">
                                        Your browser does not support the video tag.
                                    </video>
                                    <a href="{{ asset('storage/' . $message->file_path) }}"
                                    class="mt-2 text-xs text-indigo-600 hover:text-indigo-900 flex items-center"
                                    download>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download video
                                    </a>
                                </div>
                                @else
                                <a href="{{ asset('storage/' . $message->file_path) }}"
                                class="flex items-center text-indigo-600 hover:text-indigo-900 py-2 px-3 bg-white rounded border border-gray-200"
                                download>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download attached file
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($chat->messages->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-700">No messages yet</h3>
            <p class="text-gray-500 mt-1">This conversation does not contain any messages.</p>
        </div>
        @endif
    </div>
</div>
@endsection
