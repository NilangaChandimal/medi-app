@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Create a Post
            </h1>
            <p class="text-blue-100">Share updates, photos or videos with your customers</p>
        </div>

        <div class="p-6">
            <!-- Display validation errors if any -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <div class="flex items-center mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="font-medium">Please fix the following errors:</p>
                    </div>
                    <ul class="list-disc list-inside ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Post Creation Form -->
            <form action="{{ route('pharmacy.post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Textarea for post content -->
                <div class="bg-gray-50 rounded-xl p-4 shadow-sm border border-gray-100">
                    <label for="content" class="block text-gray-700 text-sm font-medium mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        What's on your mind?
                    </label>
                    <textarea name="content" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Share updates, offers, or health tips with your customers..." required></textarea>
                </div>

                <!-- Media Upload Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- File upload for images -->
                    <div class="bg-blue-50 rounded-xl p-4 shadow-sm border border-blue-100 transition-all hover:shadow-md">
                        <label for="image" class="block text-gray-700 text-sm font-medium mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                            Add Image
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-blue-300 rounded-lg hover:border-blue-500 transition-colors bg-white">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-700 focus-within:outline-none">
                                        <span>Upload an image</span>
                                        <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- File upload for videos -->
                    <div class="bg-teal-50 rounded-xl p-4 shadow-sm border border-teal-100 transition-all hover:shadow-md">
                        <label for="video" class="block text-gray-700 text-sm font-medium mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                            </svg>
                            Add Video
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors bg-white">
                            <div class="space-y-1 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="video" class="relative cursor-pointer rounded-md font-medium text-teal-600 hover:text-teal-700 focus-within:outline-none">
                                        <span>Upload a video</span>
                                        <input id="video" name="video" type="file" accept="video/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">MP4, MOV, AVI up to 50MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Area (Optional) -->
                <div id="preview" class="hidden bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Preview</h3>
                    <div id="preview-content" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Submit button -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                        </svg>
                        Publish Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Simple Javascript for file preview (optional) -->
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewContent = document.getElementById('preview-content');

        if (e.target.files.length > 0) {
            preview.classList.remove('hidden');

            const file = e.target.files[0];
            const img = document.createElement('img');
            img.classList.add('h-20', 'w-20', 'rounded', 'object-cover');
            img.file = file;

            previewContent.appendChild(img);

            const reader = new FileReader();
            reader.onload = (function(aImg) {
                return function(e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('video').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewContent = document.getElementById('preview-content');

        if (e.target.files.length > 0) {
            preview.classList.remove('hidden');

            const file = e.target.files[0];
            const video = document.createElement('video');
            video.classList.add('h-20', 'w-20', 'rounded');
            video.controls = true;
            video.file = file;

            previewContent.appendChild(video);

            const reader = new FileReader();
            reader.onload = (function(aVideo) {
                return function(e) {
                    aVideo.src = e.target.result;
                };
            })(video);
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
