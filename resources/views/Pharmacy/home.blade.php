@extends('layouts.app')

@section('title', 'Pharmacy Dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}">


@section('content')
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <!-- Notification Modal -->
    <div id="notificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-end p-4">
        <div class="bg-white dark:bg-gray-800 w-96 rounded-lg shadow-xl overflow-y-auto max-h-[80vh]">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Notifications</h2>
                <button onclick="toggleNotificationModal()" class="text-gray-600 dark:text-gray-300">✕</button>
            </div>
            <div class="p-4 space-y-4">
                @foreach ($notifications as $notification)
                <a href="javascript:void(0);"
                id="notification-{{ $notification->id }}"
                onclick="openPostModal({{ $notification->customer_post_id }}, {{ $notification->id }})"
                class="block hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg {{ $notification->read_at ? 'opacity-50' : '' }}">



                        <div class="flex items-start gap-4">
                            <img src="{{ asset('profile_image/' . ($notification->customer->profile_image ?? 'default.jpg')) }}"
                                class="w-10 h-10 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold">{{ $notification->customer->name }}</span>
                                    {{ $notification->message }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>



    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto px-4 lg:px-0 space-y-6">
                <!-- Main Content Section -->
                @forelse($paginatedPosts as $post)
                    @php
                        $isPharmacy = isset($post->pharmacy);
                        $name = $isPharmacy ? $post->pharmacy->name : $post->customer->name;
                        $profileImage = $isPharmacy
                            ? asset('profile_image/' . $post->pharmacy->profile_image)
                            : asset('profile_image/' . ($post->customer->profile_image ?? 'default.jpg'));
                        $createdAt = $post->created_at->format('M d, Y h:i A');
                        $postId = $isPharmacy ? 'pharmacy-post-' . $post->id : 'customer-post-' . $post->id;
                    @endphp

                    <div id="{{ $postId }}"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300">
                        <div class="p-6">
                            {{-- Profile section with conditional link --}}
                            @if (!$isPharmacy)
                                @foreach ($chats as $chat)
                                    <a href="{{ route($userType . '.chats.show', $chat->id) }}"
                                        class="block hover:bg-gray-50 dark:hover:bg-gray-700 p-2 -m-2 rounded-xl transition duration-200">
                                @endforeach
                            @endif

                            <div class="flex items-center">
                                <img src="{{ $profileImage }}" alt="Profile Image"
                                    class="w-12 h-12 rounded-full object-cover ring-2 ring-blue-500/20">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $createdAt }}</p>
                                </div>
                            </div>

                            @if (!$isPharmacy)
                                </a>
                            @endif

                            {{-- Post content --}}
                            <p class="mt-4 text-gray-600 dark:text-gray-300">{{ $post->content }}</p>

                            {{-- Media content --}}
                            @php
                                $mediaCount = ($post->image ? 1 : 0) + ($post->video ? 1 : 0);
                            @endphp

                            @if ($post->image || $post->video)
                                <div class="mt-4 gap-4 {{ $mediaCount > 1 ? 'grid grid-cols-2' : '' }}">
                                    @if ($post->image)
                                        <div class="relative overflow-hidden rounded-xl group cursor-pointer"
                                            onclick="openModal('{{ asset($post->image) }}', 'image')">
                                            <img src="{{ asset($post->image) }}" alt="Post Image"
                                                class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-105">
                                        </div>
                                    @endif

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

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $paginatedPosts->links() }}
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

    <!-- Post Modal -->
    <div id="postModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-4 max-w-xl w-full relative">
            <button onclick="closePostModal()" class="absolute top-3 right-3 text-gray-500 text-2xl">✕</button>
            <div id="postModalContent"></div>
        </div>
    </div>


    <script>
        function toggleNotificationModal() {
            const modal = document.getElementById('notificationModal');
            modal.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash;
            if (hash) {
                const el = document.querySelector(hash);
                if (el) {
                    el.classList.add('ring-4', 'ring-blue-400', 'shadow-lg');
                    setTimeout(() => {
                        el.classList.remove('ring-4', 'ring-blue-400', 'shadow-lg');
                    }, 2000);
                }
            }
        });

        const baseNotificationURL = "{{ url('pharmacy/notifications') }}";

function markAsRead(notificationId) {
    fetch(`/pharmacy/notifications/${notificationId}/read`, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
.then(response => {
    if (!response.ok) throw new Error("Failed to mark as read");
    return response.json();
})
.then(data => {
    console.log("Server response:", data);
})
.catch(error => console.error("Error:", error));
}





        function openModal(src, type) {
    const modal = document.getElementById('mediaModal');
    const content = document.getElementById('modalContent');

    // Close the notification modal if open
    const notificationModal = document.getElementById('notificationModal');
    if (notificationModal && !notificationModal.classList.contains('hidden')) {
        notificationModal.classList.add('hidden');
    }

    // Set content based on type
    if (type === 'image') {
        content.innerHTML = `<img src="${src}" class="w-full h-auto rounded-lg">`;
    } else if (type === 'video') {
        content.innerHTML = `<video id="modalVideo" controls autoplay class="w-full h-auto rounded-lg">
                                <source src="${src}" type="video/mp4">
                                Your browser does not support the video tag.
                             </video>`;
    }

    // Show the modal
    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('mediaModal');
    const video = document.getElementById('modalVideo');

    if (video) {
        video.pause(); // Pause the video
        video.currentTime = 0; // Reset video to the start
    }

    modal.classList.add('hidden');
    document.getElementById('modalContent').innerHTML = ''; // Clear modal content
}

        function outsideClickClose(event) {
            const modal = document.getElementById('mediaModal');
            if (event.target === modal) {
                closeModal();
            }
        }


        async function openPostModal(postId, notificationId) {
        // Hide notification modal
        const notificationModal = document.getElementById('notificationModal');
        if (notificationModal && !notificationModal.classList.contains('hidden')) {
            notificationModal.classList.add('hidden');
        }

        // Mark notification as read
        await markAsRead(notificationId);

        // Fetch and show post
        fetch(`/pharmacy/posts/${postId}`)
            .then(res => {
                if (!res.ok) throw new Error("Post not found");
                return res.text();
            })
            .then(html => {
                document.getElementById('postModalContent').innerHTML = html;
                document.getElementById('postModal').classList.remove('hidden');
            })
            .catch(err => {
                console.error('Error loading post content:', err);
                alert('Post not found or inaccessible.');
            });
    }


        function closePostModal() {
            document.getElementById('postModal').classList.add('hidden');
            document.getElementById('postModalContent').innerHTML = ''; // clear content
        }
    </script>
@endsection
