@extends('layouts.app')

@section('title', 'Admin Post Management')

@section('content')
    <div class="container mx-auto px-4 py-8 bg-gray-50">
        <div class="mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Post Management</h1>
                        <p class="text-indigo-100 mt-2">Manage and moderate user-generated content</p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fa fa-newspaper text-white opacity-60 text-5xl"></i>
                    </div>
                </div>
            </div>


            <div class="flex mt-4 space-x-2">
                <a href="#customer-posts"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                    Customer Posts
                </a>
                <a href="#pharmacy-posts"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors focus:ring-2 focus:ring-green-300 focus:outline-none">
                    Pharmacy Posts
                </a>
            </div>
        </div>

        <div class="space-y-8">
            <div id="customer-posts" class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-500 to-indigo-600 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Customer Posts
                    </h2>
                    <span class="bg-white bg-opacity-30 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $customerPosts->total() }} Posts
                    </span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach ($customerPosts as $post)
                        <div class="p-6 hover:bg-indigo-50 transition-colors duration-150">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-500 font-bold mr-4">
                                        {{ substr($post->customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-lg text-gray-800">{{ $post->customer->name }}</h3>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $post->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.posts.destroy', [$post->id, 'customer']) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 px-2 py-1 rounded hover:bg-red-100 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $post->content }}</p>
                            </div>

                            @if (($post->image && is_array(json_decode($post->image, true))) || $post->video)
    @php
        // Try to decode images (if it's a JSON array)
        $images = json_decode($post->image, true);
        $images = is_array($images) ? $images : [$post->image]; // fallback for pharmacy (single image)
    @endphp

    <div class="mt-4 flex flex-wrap gap-4">
        {{-- Loop through images --}}
        @foreach ($images as $image)
            <div class="flex-1 min-w-[250px]">
                <div class="relative group cursor-pointer"
                    onclick="openImageModal('{{ asset(is_array(json_decode($post->image, true)) ? $image : 'PharmacyPost/images/' . $image) }}')">
                    <img src="{{ asset(is_array(json_decode($post->image, true)) ? $image : 'PharmacyPost/images/' . $image) }}"
                        alt="Post image"
                        class="w-full h-64 object-cover rounded-lg shadow-sm">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-lg">
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Display video if exists --}}
        @if ($post->video)
            <div class="flex-1 min-w-[250px]">
                <div class="rounded-lg overflow-hidden shadow-sm h-64">
                    <video controls class="w-full h-full object-cover rounded-lg">
                        <source src="{{ asset($post->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        @endif
    </div>
@endif



                            <div class="mt-4 flex justify-end">
                                <div
                                    class="inline-flex items-center text-gray-500 bg-gray-100 rounded-full px-3 py-1 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    {{ $post->visibility }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $customerPosts->links() }}
                </div>
            </div>

            <div id="pharmacy-posts" class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-5 bg-gradient-to-r from-green-500 to-green-600 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Pharmacy Posts
                    </h2>
                    <span class="bg-white bg-opacity-30 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $pharmacyPosts->total() }} Posts
                    </span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach ($pharmacyPosts as $post)
                        <div class="p-6 hover:bg-green-50 transition-colors duration-150">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-500 font-bold mr-4">
                                        {{ substr($post->pharmacy->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-lg text-gray-800">{{ $post->pharmacy->name }}</h3>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $post->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.posts.destroy', [$post->id, 'pharmacy']) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 px-2 py-1 rounded hover:bg-red-100 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $post->content }}</p>
                            </div>

                            @if ($post->image || $post->video)
                                <div class="mt-4 flex flex-wrap gap-4">
                                    @if ($post->image)
                                        <div class="flex-1 min-w-[250px]">
                                            <div class="relative group cursor-pointer"
                                                onclick="openImageModal('{{ asset($post->image) }}')">
                                                <img src="{{ asset($post->image) }}" alt="Post image"
                                                    class="w-full h-64 object-cover rounded-lg shadow-sm">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-lg">
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    @if ($post->video)
                                        <div class="flex-1 min-w-[250px]">
                                            <div class="rounded-lg overflow-hidden shadow-sm h-64">
                                                <video controls class="w-full h-full object-cover rounded-lg">
                                                    <source src="{{ asset($post->video) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div id="imageModal"
                                class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
                                <div class="relative">
                                    <button onclick="closeImageModal()"
                                        class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                                    <img id="modalImage" src=""
                                        class="max-w-full max-h-screen rounded-lg shadow-lg" alt="Full view">
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <div
                                    class="inline-flex items-center text-gray-500 bg-gray-100 rounded-full px-3 py-1 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Public
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $pharmacyPosts->links() }}
                </div>
            </div>
        </div>
    </div>

    <button id="back-to-top"
        class="fixed bottom-6 right-6 p-2 rounded-full bg-gray-800 text-white shadow-lg opacity-0 transition-opacity duration-300 hover:bg-gray-700 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = src;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) closeImageModal();
        });
        const backToTopButton = document.getElementById('back-to-top');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.replace('opacity-0', 'opacity-100');
            } else {
                backToTopButton.classList.replace('opacity-100', 'opacity-0');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
@endsection
