<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('customer.home') }}" class="text-white font-bold">Customer Dashboard</a>

        <div class="hidden md:flex space-x-4">
            <a href="{{ route('customer.post.index') }}" class="text-gray-300 hover:text-white">Posts</a>
            <a href="{{ route('customer.pharmacy') }}" class="text-gray-300 hover:text-white">Pharmacies</a>
            <a href="{{ route('customer.orders.index') }}" class="text-gray-300 hover:text-white">My Orders</a>
            <a href="{{ route('customer.chats.index') }}" class="text-gray-300 hover:text-white">Chats</a>
            <a href="{{ route('customer.contact.create') }}" class="text-gray-300 hover:text-white">Contact Admin</a>
            <a href="{{ route('customer.profile.edit') }}" class="text-gray-300 hover:text-white">Profile</a>
            <a href="{{ route('customer.logout') }}" class="text-gray-300 hover:text-white">Logout</a>
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
        <a href="{{ route('customer.post.index') }}" class="block text-gray-300 hover:text-white">Posts</a>
        <a href="{{ route('customer.pharmacy') }}" class="block text-gray-300 hover:text-white">Pharmacies</a>
        <a href="{{ route('customer.orders.index') }}" class="block text-gray-300 hover:text-white">My Orders</a>
        <a href="{{ route('customer.chats.index') }}" class="block text-gray-300 hover:text-white">Chats</a>
        <a href="{{ route('customer.contact.create') }}" class="block text-gray-300 hover:text-white">Contact Admin</a>
        <a href="{{ route('customer.profile.edit') }}" class="block text-gray-300 hover:text-white">Profile</a>
        <a href="{{ route('customer.logout') }}" class="block text-gray-300 hover:text-white">Logout</a>
    </div>
</nav>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
