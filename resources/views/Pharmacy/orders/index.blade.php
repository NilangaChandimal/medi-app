@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Orders</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4">#{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->chat->customer->name }}</td>
                    <td class="px-6 py-4">${{ number_format($order->amount, 2) }}</td>
                    <td class="px-6 py-4">{{ $order->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-sm rounded-full
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                           class="text-indigo-600 hover:text-indigo-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-4 py-3 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
