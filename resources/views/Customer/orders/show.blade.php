@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Order #{{ $order->id }}</h2>

        <!-- Pharmacy Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Pharmacy Details</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                @if($order->chat?->pharmacy)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="font-medium">Name:</p>
                            <p class="text-gray-600">{{ $order->chat->pharmacy->name }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Contact:</p>
                            <p class="text-gray-600">{{ $order->chat->pharmacy->phone }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium">Address:</p>
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
                @else
                    <p class="text-gray-500">Pharmacy information not available</p>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="font-medium">Medicine</p>
                        <p class="text-gray-600">{{ $orderDetails['medicine'] }}</p>
                    </div>
                    <div>
                        <p class="font-medium">Unit Price</p>
                        <p class="text-gray-600">${{ number_format($orderDetails['price'], 2) }}</p>
                    </div>
                    <div>
                        <p class="font-medium">Quantity</p>
                        <p class="text-gray-600">{{ $orderDetails['quantity'] }}</p>
                    </div>
                    <div class="col-span-3">
                        <p class="font-medium">Total Paid</p>
                        <p class="text-gray-600 text-xl">${{ number_format($order->amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Information -->
        @if($order->address || $order->phone_number)
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Billing Information</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    @if($order->phone_number)
                    <div>
                        <p class="font-medium">Contact Number</p>
                        <p class="text-gray-600">{{ $order->phone_number }}</p>
                    </div>
                    @endif

                    @if($order->address)
                    <div class="col-span-2">
                        <p class="font-medium">Shipping Address</p>
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
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Order Status -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Order Status</h3>
            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                <div class="flex-1 relative pt-2">
                    <div class="flex justify-between mb-2">
                        @foreach(['processing', 'shipped', 'completed'] as $status)
                        <span class="text-sm {{ $order->status === $status ? 'text-indigo-600' : 'text-gray-500' }}">
                            {{ ucfirst($status) }}
                        </span>
                        @endforeach
                    </div>
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-indigo-600 rounded-full transition-all duration-500"
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
        {{-- Add at the bottom before closing main div --}}
@if($order->status === 'completed')
@include('components.rating-form', ['order' => $order, 'existingRating' => $existingRating])
@endif
    </div>

</div>
@endsection
