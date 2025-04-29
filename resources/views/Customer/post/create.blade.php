@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Create a Post
            </h1>
            <p class="text-blue-100 mt-1">Share updates, images or videos with your community</p>
        </div>

        <!-- Post Creation Form -->
        <div class="p-6">
            <form action="{{ route('customer.post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Textarea for post content -->
                <div class="rounded-lg bg-gray-50 p-4 border border-gray-200">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">What's on your mind?</label>
                    <textarea name="content" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white resize-none" placeholder="Share your thoughts, questions or updates..." required></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- File upload for images -->
                    <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center hover:bg-gray-50 transition-colors group">
                        <div class="space-y-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div class="flex flex-col text-sm text-gray-600">
                                <label for="image" class="font-medium text-blue-600 cursor-pointer hover:text-blue-700">
                                    Upload Image
                                </label>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            <input id="image" name="image" type="file" accept="image/*" class="hidden">
                        </div>
                    </div>

                    <!-- File upload for videos -->
                    <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center hover:bg-gray-50 transition-colors group">
                        <div class="space-y-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <div class="flex flex-col text-sm text-gray-600">
                                <label for="video" class="font-medium text-blue-600 cursor-pointer hover:text-blue-700">
                                    Upload Video
                                </label>
                                <p class="text-xs text-gray-500 mt-1">MP4, MOV up to 100MB</p>
                            </div>
                            <input id="video" name="video" type="file" accept="video/*" class="hidden">
                        </div>
                    </div>
                </div>

                <!-- Tags/Visibility -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">Who can see this post?</label>
                    <select name="visibility" class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                        <option value="pharmacy">Pharmacy Only</option>
                        <option value="public">Public</option>
                    </select>
                    <p class="mt-2 text-xs text-gray-500">Select who can view your post content</p>
                </div>

                <!-- Submit button -->
                <div class="flex items-center justify-between pt-4">
                    <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <a href="{{ route('customer.post.index') }}">
                            Back
                    </button>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Publish Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display validation errors if any -->
    @if ($errors->any())
    <div class="mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Display file name when selected
    document.getElementById('image').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
        const parent = this.closest('.group');
        const label = parent.querySelector('label');
        label.textContent = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName;
    });

    document.getElementById('video').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
        const parent = this.closest('.group');
        const label = parent.querySelector('label');
        label.textContent = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName;
    });
</script>
@endsection
