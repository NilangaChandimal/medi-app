@extends('layouts.app')

@section('title', 'All Customers')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold">Customer Management</h2>
            <div class="w-1/3">
                <form method="GET" action="">
                    <input type="text"
                           name="search"
                           placeholder="Search customers..."
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr>
                        <td class="px-6 py-4">{{ $customer->name }}</td>
                        <td class="px-6 py-4">{{ $customer->email }}</td>
                        <td class="px-6 py-4">{{ $customer->phone }}</td>
                        <td class="px-6 py-4">{{ $customer->city }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-sm rounded-full
                                {{ $customer->is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $customer->is_blocked ? 'Blocked' : 'Active' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $customer->created_at->diffForHumans() }}</td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No customers found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
