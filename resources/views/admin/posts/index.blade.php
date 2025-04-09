@extends('layouts.app')

@section('title', 'Admin Post Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="space-y-8">
        <!-- Customer Posts Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Customer Posts</h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($customerPosts as $post)
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-medium">{{ $post->customer->name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $post->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <form action="{{ route('admin.posts.destroy', [$post->id, 'customer']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </button>
                        </form>
                    </div>

                    <p class="text-gray-600 mb-4 whitespace-pre-wrap">{{ $post->content }}</p>

                    @if($post->image || $post->video)
                    <div class="mt-4">
                        @if($post->image)
                        <img src="{{ asset($post->image) }}"
                             alt="Post image"
                             class="max-w-full h-64 object-cover rounded-lg">
                        @endif

                        @if($post->video)
                        <video controls class="mt-4 w-full rounded-lg">
                            <source src="{{ asset($post->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="px-4 py-3 border-t">
                {{ $customerPosts->links() }}
            </div>
        </div>

        <!-- Pharmacy Posts Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Pharmacy Posts</h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($pharmacyPosts as $post)
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-medium">{{ $post->pharmacy->name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $post->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <form action="{{ route('admin.posts.destroy', [$post->id, 'pharmacy']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </button>
                        </form>
                    </div>

                    <p class="text-gray-600 mb-4 whitespace-pre-wrap">{{ $post->content }}</p>

                    @if($post->image || $post->video)
                    <div class="mt-4">
                        @if($post->image)
                        <img src="{{ asset($post->image) }}"
                             alt="Post image"
                             class="max-w-full h-64 object-cover rounded-lg">
                        @endif

                        @if($post->video)
                        <video controls class="mt-4 w-full rounded-lg">
                            <source src="{{ asset($post->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="px-4 py-3 border-t">
                {{ $pharmacyPosts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
