@extends('layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="space-y-8">
        <!-- Dashboard Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">User Management Dashboard</h1>
                    <p class="text-indigo-100 mt-2">Manage your customers and pharmacies in one place</p>
                </div>
                <div class="hidden md:block">
                    <!-- Font Awesome Users Icon -->
                    <i class="fa fa-users text-white opacity-80 text-4xl"></i>
                </div>
            </div>
        </div>


        <!-- Customers Section -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-purple-50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Customer Management</h2>
                    <p class="text-sm text-gray-500">View and manage customer accounts</p>
                </div>
                <div class="flex space-x-2">
                    <span class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Customers
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center mr-3">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $customer->phone }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $customer->is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $customer->is_blocked ? 'Blocked' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <form method="POST" action="{{ route('admin.users.toggle-block', $customer) }}">
                                    @csrf
                                    <input type="hidden" name="type" value="customer">
                                    <button type="submit" class="px-3 py-1 rounded text-white text-xs font-medium {{ $customer->is_blocked ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} transition-colors">
                                        {{ $customer->is_blocked ? 'Unblock' : 'Block' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $customers->links() }}
            </div>
        </div>

        <!-- Pharmacies Section -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-purple-50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Pharmacy Block Management</h2>
                    <p class="text-sm text-gray-500">View and manage pharmacy accounts</p>
                </div>
                <div class="flex space-x-2">
                    <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Pharmacies
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pharmacies as $pharmacy)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center mr-3">
                                        {{ substr($pharmacy->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $pharmacy->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $pharmacy->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $pharmacy->phone }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $pharmacy->is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $pharmacy->is_blocked ? 'Blocked' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <form method="POST" action="{{ route('admin.users.toggle-block', $pharmacy) }}">
                                    @csrf
                                    <input type="hidden" name="type" value="pharmacy">
                                    <button type="submit" class="px-3 py-1 rounded text-white text-xs font-medium {{ $pharmacy->is_blocked ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} transition-colors">
                                        {{ $pharmacy->is_blocked ? 'Unblock' : 'Block' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $pharmacies->links() }}
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>© 2025 Your Pharmacy Management System. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection
