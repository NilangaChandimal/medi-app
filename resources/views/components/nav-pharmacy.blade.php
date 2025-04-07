<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('pharmacy.home') }}" class="text-white font-bold">Pharmacy Dashboard {{ Auth::user()->name }}</a>
        <div class="flex space-x-4">
            {{-- <a href="{{ route('worker.news') }}" class="text-gray-300 hover:text-white">News</a> --}}
            <a href="{{ route('pharmacy.post.index') }}" class="text-gray-300 hover:text-white">Post</a>
            <a href="{{ route('pharmacy.chats.index') }}" class="text-gray-300 hover:text-white">Chats</a>
            {{-- <a href="{{ route('worker.profile') }}" class="text-gray-300 hover:text-white">Profile</a> --}}
            <a href="{{ route('pharmacy.logout') }}" class="text-gray-300 hover:text-white">Logout</a>
        </div>
    </div>
</nav>
