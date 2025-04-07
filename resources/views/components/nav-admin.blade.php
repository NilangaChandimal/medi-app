<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('admin.home') }}" class="text-white font-bold">Admin Dashboard</a>
        <div class="flex space-x-4">
            <a href="{{ route('admin.posts') }}" class="text-gray-300 hover:text-white">Posts</a>
             {{-- <a href="{{ route('admin.news.index') }}" class="text-gray-300 hover:text-white">News</a> --}}
            {{-- <a href="{{ route('admin.chats.index') }}" class="text-gray-300 hover:text-white">Chat</a> --}}
            {{-- <a href="{{ route('admin.summary') }}" class="text-gray-300 hover:text-white">Employee Summary</a> --}}
            {{-- <a href="{{ route('admin.check-workers') }}" class="text-gray-300 hover:text-white">Check-Up</a> --}}
            {{-- <a href="{{ route('admin.profile') }}" class="text-gray-300 hover:text-white">Settings</a> --}}
            <a href="{{ route('admin.logout') }}" class="text-gray-300 hover:text-white">Logout</a>
        </div>
    </div>
</nav>
