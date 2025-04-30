@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Order #{{ $order->id }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-indigo-700">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-8">
            <!-- Pharmacy Information -->
            <div class="bg-indigo-50 rounded-xl overflow-hidden">
                <div class="bg-indigo-100 px-4 py-3">
                    <h3 class="text-lg font-semibold text-indigo-800">Pharmacy Details</h3>
                </div>
                <div class="p-4">
                    @if($order->chat?->pharmacy)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Name</p>
                                    <p class="text-gray-600">{{ $order->chat->pharmacy->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Contact</p>
                                    <p class="text-gray-600">{{ $order->chat->pharmacy->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 col-span-1 md:col-span-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Address</p>
                                    @php
                                        $pharmacyAddress = is_string($order->chat->pharmacy->address)
                                            ? json_decode($order->chat->pharmacy->address, true)
                                            : $order->chat->pharmacy->address;
                                    @endphp
                                    @if(is_array($pharmacyAddress))
                                        <p class="text-gray-600">
                                            {{ $pharmacyAddress['street'] ?? '' }},
                                            {{ $pharmacyAddress['city'] ?? '' }},
                                            {{ $pharmacyAddress['state'] ?? '' }}
                                            {{ $pharmacyAddress['postal_code'] ?? '' }}
                                        </p>
                                    @else
                                        <p class="text-gray-600">{{ $order->chat->pharmacy->address }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Pharmacy information not available</p>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-purple-50 rounded-xl overflow-hidden">
                <div class="bg-purple-100 px-4 py-3">
                    <h3 class="text-lg font-semibold text-purple-800">Order Summary</h3>
                </div>
                <div class="p-4 space-y-4">
                    @foreach ($orderDetails['medicines'] ?? [] as $medicine)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white rounded-lg shadow-sm p-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Medicine</p>
                                <p class="font-semibold text-gray-700">{{ $medicine['name'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Unit Price</p>
                                <p class="font-semibold text-gray-700">Rs.{{ number_format($medicine['price'], 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Quantity</p>
                                <p class="font-semibold text-gray-700">{{ $medicine['quantity'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Subtotal</p>
                                <p class="font-semibold text-gray-700">Rs.{{ number_format($medicine['subtotal'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-between items-center p-4 bg-white rounded-lg shadow-sm border-l-4 border-purple-500">
                        <p class="text-lg font-medium text-gray-700">Total Paid</p>
                        <p class="text-xl font-bold text-purple-700">
                            Rs.{{ number_format($orderDetails['total'] ?? $order->amount, 2) }}
                        </p>
                    </div>
                </div>
            </div>


            <!-- Billing Information -->
            @if($order->address || $order->phone_number)
            <div class="bg-green-50 rounded-xl overflow-hidden">
                <div class="bg-green-100 px-4 py-3">
                    <h3 class="text-lg font-semibold text-green-800">Billing Information</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($order->phone_number)
                        <div class="flex items-start space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="font-medium text-gray-700">Contact Number</p>
                                <p class="text-gray-600">{{ $order->phone_number }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->address)
                        <div class="flex items-start space-x-3 col-span-1 md:col-span-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="font-medium text-gray-700">Shipping Address</p>
                                @php
                                    $shippingAddress = is_string($order->address)
                                        ? json_decode($order->address, true)
                                        : $order->address;
                                @endphp
                                @if(is_array($shippingAddress))
                                    <p class="text-gray-600">
                                        {{ $shippingAddress['line1'] ?? '' }}<br>
                                        @if($shippingAddress['line2'] ?? false)
                                        {{ $shippingAddress['line2'] }}<br>
                                        @endif
                                        {{ $shippingAddress['city'] ?? '' }},
                                        {{ $shippingAddress['state'] ?? '' }}
                                        {{ $shippingAddress['postal_code'] ?? '' }}
                                    </p>
                                @else
                                    <p class="text-gray-600">{{ $order->address }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Status -->
            <div class="bg-blue-50 rounded-xl overflow-hidden">
                <div class="bg-blue-100 px-4 py-3">
                    <h3 class="text-lg font-semibold text-blue-800">Order Status</h3>
                </div>
                <div class="p-4">
                    <div class="relative pt-6 pb-4">
                        <div class="flex justify-between mb-2">
                            @foreach(['processing', 'shipped', 'completed'] as $index => $status)
                            <div class="flex flex-col items-center">
                                <div class="rounded-full h-10 w-10 flex items-center justify-center {{ $order->status === $status || array_search($order->status, ['processing', 'shipped', 'completed']) > $index ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                    @if($status === 'processing')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($status === 'shipped')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="text-sm mt-2 font-medium {{ $order->status === $status ? 'text-blue-600' : 'text-gray-500' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="overflow-hidden h-2 mb-4 flex rounded bg-gray-200">
                            <div class="h-2 bg-blue-600 rounded transition-all duration-500"
                                 style="width: {{ match($order->status) {
                                     'processing' => '33%',
                                     'shipped' => '66%',
                                     'completed' => '100%',
                                     default => '0%'
                                 } }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Form when order is completed -->
            @if($order->status === 'completed')
                <div class="bg-yellow-50 rounded-xl overflow-hidden">
                    <div class="bg-yellow-100 px-4 py-3">
                        <h3 class="text-lg font-semibold text-yellow-800">Rate Your Order</h3>
                    </div>
                    <div class="p-4">
                        @include('components.rating-form', ['order' => $order, 'existingRating' => $existingRating])
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
