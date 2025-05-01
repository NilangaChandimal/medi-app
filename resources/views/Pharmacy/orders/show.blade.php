@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-100">
                Order #{{ $order->id }}
            </h1>
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                @if($order->status === 'processing') bg-yellow-100 text-yellow-800
                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                @elseif($order->status === 'completed') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="grid md:grid-cols-2 border-b">
                <div class="p-6 border-r">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Customer Information</h3>
                    </div>

                    <div class="ml-7 space-y-2">
                        <p class="text-gray-700 font-medium">{{ $order->chat->customer->name }}</p>
                        <p class="text-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $order->chat->customer->email }}
                        </p>
                        <p class="text-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $order->chat->customer->phone }}
                        </p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Order Summary</h3>
                    </div>

                    <div class="ml-7 space-y-2">
                        @foreach ($orderDetails['items'] as $item)
                            <div class="pb-2 border-b border-gray-100">
                                <p class="text-gray-700">
                                    <span class="font-medium">Medicine:</span>
                                    <span class="text-gray-600">{{ $item['medicine'] }}</span>
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">Price per Unit:</span>
                                    <span class="text-gray-600">Rs.{{ number_format($item['price'], 2) }}</span>
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">Quantity:</span>
                                    <span class="text-gray-600">{{ $item['quantity'] }}</span>
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">Subtotal:</span>
                                    <span class="text-gray-600">Rs.{{ number_format($item['total'], 2) }}</span>
                                </p>
                            </div>
                        @endforeach

                        <div class="pt-2 mt-2 border-t border-gray-100">
                            <p class="text-gray-800 font-bold text-lg">
                                Total: Rs.{{ number_format($orderDetails['total'], 2) }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-6 border-b">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Delivery Details</h3>
                </div>

                <div class="ml-7 grid md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $payment->phone_number }}
                        </p>

                        @php
                            $address = is_string($payment->address) ? json_decode($payment->address, true) : $payment->address;
                        @endphp

                        <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                            @if(is_array($address))
                                <p class="font-medium text-gray-700">{{ $address['line1'] ?? '' }}</p>
                                @if(!empty($address['line2']))
                                    <p class="text-gray-600">{{ $address['line2'] }}</p>
                                @endif
                                <p class="text-gray-600">
                                    {{ $address['city'] ?? '' }},
                                    {{ $address['state'] ?? '' }}
                                    {{ $address['postal_code'] ?? '' }}
                                </p>
                            @else
                                <p class="text-gray-600">{{ $payment->address }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-md border border-blue-100">
                        <h4 class="font-medium text-blue-800 mb-2">Delivery Notes</h4>
                        <p class="text-blue-700 text-sm">
                            Standard shipping time is 3-5 business days.
                            Contact customer for special delivery instructions.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Update Order Status</h3>
                </div>

                <form action="{{ route('pharmacy.orders.update', $order->id) }}" method="POST" class="ml-7">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-wrap items-center">
                        <select name="status" class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        <button type="submit" class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('pharmacy.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
            <div>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Order
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
