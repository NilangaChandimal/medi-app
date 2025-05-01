<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('pharmacy.home') }}" class="text-white font-bold">Pharmacy Dashboard</a>

        <div class="hidden md:flex space-x-4">
            <a href="{{ route('pharmacy.orders.index') }}" class="text-gray-300 hover:text-white">Orders</a>
            <a href="{{ route('pharmacy.post.index') }}" class="text-gray-300 hover:text-white">Post</a>
            <a href="{{ route('pharmacy.chats.index') }}" class="text-gray-300 hover:text-white">Chats</a>

            <!-- Notification Icon -->
            <button onclick="toggleNotificationModal()" class="relative focus:outline-none">
                <svg class="w-6 h-6 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
            </button>

            <a href="{{ route('pharmacy.contact.create') }}" class="text-gray-300 hover:text-white">Contact Admin</a>
            <a href="{{ route('pharmacy.profile.edit') }}" class="text-gray-300 hover:text-white">Profile</a>
            <a href="{{ route('pharmacy.logout') }}" class="text-gray-300 hover:text-white">Logout</a>
        </div>

        <!-- Mobile -->
        <div class="md:hidden flex items-center">
            <button id="menu-toggle" class="text-gray-300 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-gray-800 text-white space-y-2 px-4 py-2">
        <a href="{{ route('pharmacy.orders.index') }}" class="block text-gray-300 hover:text-white">Orders</a>
        <a href="{{ route('pharmacy.post.index') }}" class="block text-gray-300 hover:text-white">Post</a>
        <a href="{{ route('pharmacy.chats.index') }}" class="block text-gray-300 hover:text-white">Chats</a>

        <button onclick="toggleNotificationModal()" class="relative focus:outline-none w-full text-left">
            <svg class="w-6 h-6 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
        </button>

        <a href="{{ route('pharmacy.contact.create') }}" class="block text-gray-300 hover:text-white">Contact Admin</a>
        <a href="{{ route('pharmacy.profile.edit') }}" class="block text-gray-300 hover:text-white">Profile</a>
        <a href="{{ route('pharmacy.logout') }}" class="block text-gray-300 hover:text-white">Logout</a>
    </div>
</nav>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
