@extends('layouts.app')

@section('title', 'Chat Show')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">
                    Chat between {{ $chat->customer->name }} and {{ $chat->pharmacy->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    Started {{ $chat->created_at->diffForHumans() }}
                </p>
            </div>
            <a href="{{ route('admin.chats.index') }}"
               class="text-indigo-600 hover:text-indigo-900">
                &larr; Back to chats
            </a>
        </div>

        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
            @foreach($chat->messages as $message)
            <div class="flex {{ $message->sender_type === 'App\Models\Customer' ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-3xl p-4 rounded-lg
                    {{ $message->sender_type === 'App\Models\Customer'
                        ? 'bg-blue-50 ml-4'
                        : 'bg-green-50 mr-4' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-medium">
                            {{ $message->sender->name?? 'N/A' }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $message->created_at->format('M d, H:i') }}
                        </span>
                    </div>

                    <p class="text-gray-600 whitespace-pre-wrap">{{ $message->message }}</p>

                    @if($message->file_path)
                    <div class="mt-4">
                        @if(Str::startsWith($message->file_path, 'image/'))
                        <img src="{{ asset('storage/' . $message->file_path) }}"
                             alt="Attached image"
                             class="max-w-xs rounded-lg">
                        @elseif(Str::startsWith($message->file_path, 'video/'))
                        <video controls class="max-w-xs rounded-lg">
                            <source src="{{ asset('storage/' . $message->file_path) }}">
                            Your browser does not support the video tag.
                        </video>
                        @else
                        <a href="{{ asset('storage/' . $message->file_path) }}"
                           class="text-indigo-600 hover:text-indigo-900"
                           download>
                            Download attached file
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
