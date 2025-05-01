@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-blue-50 dark:from-gray-900 dark:to-gray-900">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <div class="lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">My Orders</h2>

                        @forelse($orders as $order)
                            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-4 last:pb-0 last:border-none">
                                <h3 class="text-sm font-medium text-gray-800 dark:text-white">
                                    <a href="{{ route('customer.orders.show', $order->id) }}">
                                        #{{ $order->id }} - {{ $order->chat->pharmacy->name ?? 'N/A' }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('M d, Y') }} · ${{ number_format($order->amount, 2) }}
                                </p>
                                <span
                                    class="inline-block mt-1 text-xs font-semibold px-2 py-1 rounded-full
                                    {{ match ($order->status) {
                                        'completed' => 'bg-green-100 text-green-800',
                                        'shipped' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    } }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">You have no orders yet.</p>
                        @endforelse
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-6">

                    @forelse($paginatedPosts as $post)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex items-center">
                                    @php
                                        $isPharmacy = isset($post->pharmacy);
                                        $name = $isPharmacy ? $post->pharmacy->name : $post->customer->name;
                                        $profileImage = $isPharmacy
                                            ? asset('profile_image/' . $post->pharmacy->profile_image)
                                            : asset(
                                                'profile_image/' . ($post->customer->profile_image ?? 'default.jpg'),
                                            );

                                        $decodedImages = json_decode($post->image, true);
                                        if ($isPharmacy && $post->image && is_string($post->image)) {
                                            $imagePath = $post->image;
                                            $imagePath = str_replace('PharmacyPost/images/', '', $imagePath);
                                            $images = ['PharmacyPost/images/' . $imagePath];
                                        } elseif (is_array($decodedImages)) {
                                            $images = $decodedImages;
                                        } else {
                                            $images = [];
                                        }

                                        $mediaCount = count($images) + ($post->video ? 1 : 0);
                                    @endphp

                                    <img src="{{ $profileImage }}" alt="Profile Image"
                                        class="w-12 h-12 rounded-full object-cover ring-2 ring-blue-500/20">
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $post->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>

                                <p class="mt-4 text-gray-600 dark:text-gray-300">{{ $post->content }}</p>

                                @if (!empty($images) || $post->video)
                                    <div class="mt-4 gap-4 {{ $mediaCount > 1 ? 'grid grid-cols-2' : '' }}">
                                        @foreach ($images as $image)
                                            <div class="relative overflow-hidden rounded-xl group cursor-pointer"
                                                onclick="openModal('{{ asset($image) }}', 'image')">
                                                <img src="{{ asset($image) }}" alt="Post Image"
                                                    class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-105">
                                            </div>
                                        @endforeach

                                        @if ($post->video)
                                            <div class="relative rounded-xl overflow-hidden">
                                                <video class="w-full h-64 object-cover cursor-pointer" controls
                                                    onclick="openModal('{{ asset($post->video) }}', 'video')">
                                                    <source src="{{ asset($post->video) }}" type="video/mp4">
                                                </video>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            No posts available
                        </div>
                    @endforelse


                    <div class="mt-8">
                        {{ $paginatedPosts->links() }}
                    </div>


                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Chats</h2>

                        @forelse ($latestChats as $chat)
                            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-4 last:pb-0 last:border-none">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ asset('profile_image/' . $chat->pharmacy->profile_image) }}"
                                        alt="Pharmacy Avatar"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/20">
                                    <div>
                                        <a href="{{ route('customer.chats.show', $chat->id) }}">
                                            <h3
                                                class="text-sm font-semibold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                                {{ $chat->pharmacy->name }}
                                            </h3>
                                        </a>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ optional($chat->lastMessage)->created_at->diffForHumans() ?? 'No messages yet' }}
                                        </p>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 truncate">
                                    @if ($chat->lastMessage)
                                        @if ($chat->lastMessage->message)
                                            {{ Str::limit($chat->lastMessage->message, 50) }}
                                        @elseif ($chat->lastMessage->image)
                                            <span class="italic text-blue-500">[Picture message]</span>
                                        @endif
                                    @else
                                        <span class="text-blue-500">Start a conversation</span>
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">No recent chats found.</p>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div id="mediaModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden"
        onclick="outsideClickClose(event)">
        <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-2xl w-full p-4 relative"
            onclick="event.stopPropagation()">
            <span class="absolute top-4 right-4 text-gray-500 text-2xl cursor-pointer" onclick="closeModal()">✕</span>
            <div id="modalContent" class="w-full"></div>
        </div>
    </div>
    <script>
        function openModal(src, type) {
            const modal = document.getElementById('mediaModal');
            const content = document.getElementById('modalContent');

            if (type === 'image') {
                content.innerHTML = `<img src="${src}" class="w-full h-auto rounded-lg">`;
            } else if (type === 'video') {
                content.innerHTML = `<video id="modalVideo" controls autoplay class="w-full h-auto rounded-lg">
                                    <source src="${src}" type="video/mp4">
                                    Your browser does not support the video tag.
                                 </video>`;
            }

            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('mediaModal');
            const video = document.getElementById('modalVideo');

            if (video) {
                video.pause();
                video.currentTime = 0;
            }

            modal.classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        function outsideClickClose(event) {
            const modal = document.getElementById('mediaModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
@endsection
