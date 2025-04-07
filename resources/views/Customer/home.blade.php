@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto px-4 lg:px-0 space-y-6">
                <!-- Main Content Section -->
                @forelse($paginatedPosts as $post)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-6 ">
                            <div class="flex items-center">
                                @php
                                    $isPharmacy = isset($post->pharmacy);
                                    $name = $isPharmacy ? $post->pharmacy->name : $post->customer->name;
                                    $profileImage = $isPharmacy
                                        ? asset('profile_image/' . $post->pharmacy->profile_image)
                                        : asset('profile_image/' . ($post->customer->profile_image ?? 'default.jpg'));
                                @endphp
                                <img src="{{ $profileImage }}" alt="Profile Image"
                                    class="w-12 h-12 rounded-full object-cover ring-2 ring-blue-500/20">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $post->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-600 dark:text-gray-300">{{ $post->content }}</p>

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
    </script>
@endsection
