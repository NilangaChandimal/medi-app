@php
    $isPharmacy = isset($post->pharmacy);
    $name = $isPharmacy ? $post->pharmacy->name : $post->customer->name;
    $profileImage = $isPharmacy
        ? asset('profile_image/' . $post->pharmacy->profile_image)
        : asset('profile_image/' . ($post->customer->profile_image ?? 'default.jpg'));
    $createdAt = $post->created_at->format('M d, Y h:i A');
@endphp

<div class="flex items-center mb-4">
    <a href="{{ route($userType . '.chats.show', $chats[0]->id) }}"
       class="flex items-center w-full hover:bg-gray-50 dark:hover:bg-gray-700 p-2 -m-2 rounded-xl transition duration-200">
        <img src="{{ $profileImage }}" alt="Profile" class="w-12 h-12 rounded-full object-cover mr-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $createdAt }}</p>
        </div>
    </a>
</div>

<p class="text-gray-700 dark:text-gray-300 mb-4">{{ $post->content }}</p>

@if (!empty($post->image) && is_array($post->image))
    @foreach ($post->image as $image)
        <img src="{{ asset($image) }}"
             alt="Post Image"
             class="w-full max-w-md max-h-96 rounded-xl mb-4 object-cover">
    @endforeach
@endif




@if ($post->video)
    <video controls class="w-full rounded-xl mb-4">
        <source src="{{ asset($post->video) }}" type="video/mp4">
    </video>
@endif

