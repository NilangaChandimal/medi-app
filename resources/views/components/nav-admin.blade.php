<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('admin.home') }}" class="text-white font-bold">Admin Dashboard</a>
        <div class="hidden md:flex space-x-4">
            <a href="{{ route('admin.posts.index') }}" class="text-gray-300 hover:text-white">Posts</a>
            <a href="{{ route('admin.support.index') }}" class="text-gray-300 hover:text-white">Support Tickets</a>
            <a href="{{ route('admin.pharmacies.index') }}" class="text-gray-300 hover:text-white">Pharmacy Checkup</a>
            <a href="{{ route('admin.users.index') }}" class="text-gray-300 hover:text-white">Service Management</a>
            <a href="{{ route('admin.chats.index') }}" class="text-gray-300 hover:text-white">Chat</a>
            <a href="{{ route('admin.payments.index') }}" class="text-gray-300 hover:text-white">Payments</a>
            <a href="{{ route('admin.profile.edit') }}" class="text-gray-300 hover:text-white">Profile</a>
            <a href="{{ route('admin.logout') }}" class="text-gray-300 hover:text-white">Logout</a>
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
        <a href="{{ route('admin.posts.index') }}" class="block text-gray-300 hover:text-white">Posts</a>
        <a href="{{ route('admin.support.index') }}" class="block text-gray-300 hover:text-white">Support Tickets</a>
        <a href="{{ route('admin.pharmacies.index') }}" class="block text-gray-300 hover:text-white">Pharmacy Checkup</a>
        <a href="{{ route('admin.users.index') }}" class="block text-gray-300 hover:text-white">Service Management</a>
        <a href="{{ route('admin.chats.index') }}" class="block text-gray-300 hover:text-white">Chat</a>
        <a href="{{ route('admin.payments.index') }}" class="block text-gray-300 hover:text-white">Payments</a>
        <a href="{{ route('admin.profile.edit') }}" class="block text-gray-300 hover:text-white">Profile</a>
        <a href="{{ route('admin.logout') }}" class="block text-gray-300 hover:text-white">Logout</a>
    </div>
</nav>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
