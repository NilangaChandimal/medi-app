@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Create a Post</h1>

    <!-- Post Creation Form -->
    <form action="{{ route('pharmacy.post.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf

        <!-- Textarea for post content -->
        <div class="mb-4">
            <textarea name="content" rows="4" class="w-full p-2 border rounded" placeholder="What's on your mind?" required></textarea>
        </div>

        <!-- File upload for images -->
        <div class="mb-4">
            <label for="image" class="block text-gray-700">Upload Image</label>
            <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
        </div>

        <!-- File upload for videos -->
        <div class="mb-4">
            <label for="video" class="block text-gray-700">Upload Video</label>
            <input type="file" name="video" accept="video/*" class="w-full p-2 border rounded">
        </div>

        <!-- Submit button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
    </form>

    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
