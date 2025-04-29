@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<!-- Background pattern -->
<div class="absolute top-0 right-0 -z-10 w-full h-64 bg-gradient-to-r from-indigo-500/10 to-purple-500/10"></div>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
            <p class="mt-1 text-gray-500">Manage your customer orders</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex items-center transition-transform hover:scale-105">
            <div class="rounded-full bg-blue-50 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ count($orders) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex items-center transition-transform hover:scale-105">
            <div class="rounded-full bg-green-50 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'completed')->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex items-center transition-transform hover:scale-105">
            <div class="rounded-full bg-amber-50 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', '!=', 'completed')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="relative flex-grow">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" class="pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg" placeholder="Search by customer name or order ID...">
        </div>
        <div class="flex gap-4">
            <select class="focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-lg py-2">
                <option>All Statuses</option>
                <option>Completed</option>
                <option>Processing</option>
                <option>Pending</option>
            </select>
            <select class="focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-lg py-2">
                <option>Last 30 days</option>
                <option>Last 7 days</option>
                <option>Today</option>
                <option>All Time</option>
            </select>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="group px-6 py-3 text-left">
                            <div class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span>Order ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left">
                            <div class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span>Customer</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left">
                            <div class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span>Amount</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left">
                            <div class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span>Payment Date</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left">
                            <div class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span>Status</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-indigo-600">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 flex-shrink-0 rounded-full bg-gray-200 flex items-center justify-center">
                                    {{ substr($order->chat->customer->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $order->chat->customer->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">${{ number_format($order->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('pharmacy.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium bg-indigo-50 hover:bg-indigo-100 rounded-lg px-3 py-1 transition-colors">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ count($orders) }}</span> orders
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
