<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('customer.home') }}" class="text-white font-bold">Customer Dashboard</a>
        <div class="flex space-x-4">
            <a href="{{ route('customer.post.index') }}" class="text-gray-300 hover:text-white">Posts</a>
            <a href="{{ route('customer.pharmacy') }}" class="text-gray-300 hover:text-white">Pharmacies</a>
            <a href="{{ route('customer.orders.index') }}" class="text-gray-300 hover:text-white">My Orders</a>
            <a href="{{ route('customer.chats.index') }}" class="text-gray-300 hover:text-white">Chats</a>
            <a href="{{ route('customer.contact.create') }}" class="text-gray-300 hover:text-white">Contact Admin</a>
            {{-- <a href="{{ route('customer.profile') }}" class="text-gray-300 hover:text-white">Profile</a> --}}
            <a href="{{ route('customer.logout') }}" class="text-gray-300 hover:text-white">Logout</a>
        </div>
    </div>
</nav>
