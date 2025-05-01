@extends('layouts.app')

@section('title', ucfirst($userType) . ' Chats')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="container mx-auto px-4 py-8 sm:py-16">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h1 class="text-3xl sm:text-5xl font-black mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent animate-gradient">
                        {{ ucfirst($userType) }} Chats
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">Connect and communicate with your contacts</p>
                </div>

                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                        @foreach($chats as $chat)
                            <div class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 to-purple-500/0 group-hover:from-blue-500/5 group-hover:to-purple-500/5 rounded-xl transition-all duration-500"></div>

                                <div class="relative bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-xl border border-gray-100 dark:border-gray-700 group-hover:border-blue-300 dark:group-hover:border-blue-500/30 transition-all duration-300 ease-out transform hover:-translate-y-1 hover:shadow-xl">
                                    <a href="{{ route($userType . '.chats.show', $chat->id) }}" class="flex items-center space-x-4 sm:space-x-5">
                                        <div class="relative">
                                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 blur transition-opacity duration-500"></div>
                                            <img
                                                src="{{ asset('profile_image/' . ($userType === 'customer' ? $chat->pharmacy->profile_image : $chat->customer->profile_image)) }}"
                                                alt="Avatar"
                                                class="relative w-14 sm:w-16 h-14 sm:h-16 rounded-full object-cover border-2 border-white dark:border-gray-700 group-hover:border-transparent transition-all duration-300"
                                            >
                                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800 shadow-lg"></div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                                {{ $userType === 'customer' ? $chat->pharmacy->name : $chat->customer->name }}
                                            </h2>

                                            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-2 truncate flex items-center space-x-2">
                                                @if($chat->lastMessage)
                                                    @if($chat->lastMessage->message)
                                                        <span class="inline-flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                            </svg>
                                                            {{ Str::limit($chat->lastMessage->message, 30) }}
                                                        </span>
                                                    @elseif($chat->lastMessage->image)
                                                        <span class="inline-flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Picture message
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center text-blue-500 dark:text-blue-400">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Start a conversation
                                                    </span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="text-gray-400 group-hover:text-blue-500 transition-colors duration-300">
                                            <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 200% auto;
            animation: gradient 4s linear infinite;
        }
    </style>
@endsection
