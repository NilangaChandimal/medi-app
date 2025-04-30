@extends('layouts.app')

@section('title', 'Pharmacy Management')

@section('content')

<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">{{ $pharmacy->name }}</h2>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div><strong>Email:</strong> {{ $pharmacy->email }}</div>
        <div><strong>Registration Number:</strong> {{ $pharmacy->registration_number }}</div>
        <div><strong>License Details:</strong> {{ $pharmacy->license_details }}</div>
        <div><strong>Address:</strong> {{ $pharmacy->address }}</div>
        <div><strong>Phone:</strong> {{ $pharmacy->phone }}</div>
        <div><strong>City:</strong> {{ $pharmacy->city }}</div>
        <div><strong>Status:</strong> {{ ucfirst($pharmacy->status) }}</div>
        <div><strong>Block:</strong> {{ $pharmacy->is_blocked ? 'Yes' : 'No' }}</div>
        <div><strong>Average Rating:</strong> {{ number_format($averageRating, 1) }} ({{ $ratingCount }} reviews)</div>
    </div>

    @if($pharmacy->profile_image)
        <div class="mb-6">
            <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow"
                                src="{{ $pharmacy->profile_image ? asset('profile_image/' . $pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                                alt="Pharmacy profile image">
        </div>
    @endif

    <h3 class="text-lg font-semibold mb-2">Ratings & Reviews</h3>
    <div class="space-y-4">
        @forelse($pharmacy->ratings as $rating)
            <div class="border p-4 rounded-lg bg-gray-50">
                <div class="flex justify-between items-center">
                    <span class="font-medium">{{ $rating->customer->name ?? 'Unknown Customer' }}</span>
                    <span class="text-yellow-500">{{ str_repeat('★', $rating->rating) }}</span>
                </div>
                <p class="mt-2 text-gray-700">{{ $rating->comment ?? 'No comment' }}</p>
            </div>
        @empty
            <p>No ratings yet.</p>
        @endforelse
    </div>
</div>

@endsection
