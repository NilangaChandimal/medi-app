@extends('layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="space-y-8">
        <!-- Customers Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Customer Management</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                        <tr>
                            <td class="px-6 py-4">{{ $customer->name }}</td>
                            <td class="px-6 py-4">{{ $customer->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded {{ $customer->is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $customer->is_blocked ? 'Blocked' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.users.toggle-block', $customer) }}">
                                    @csrf
                                    <input type="hidden" name="type" value="customer">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t">
                {{ $customers->links() }}
            </div>
        </div>

        <!-- Pharmacies Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Pharmacy Block Management</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pharmacies as $pharmacy)
                        <tr>
                            <td class="px-6 py-4">{{ $pharmacy->name }}</td>
                            <td class="px-6 py-4">{{ $pharmacy->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded {{ $pharmacy->is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $pharmacy->is_blocked ? 'Blocked' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.users.toggle-block', $pharmacy) }}">
                                    @csrf
                                    <input type="hidden" name="type" value="pharmacy">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $pharmacy->is_blocked ? 'Unblock' : 'Block' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t">
                {{ $pharmacies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
