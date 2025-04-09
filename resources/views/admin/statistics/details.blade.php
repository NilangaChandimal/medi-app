@extends('layouts.app')

@section('title', 'Statistics Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Details for {{ $month }} {{ now()->year }}</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4">Customers Registered</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    @forelse($customers as $customer)
                    <div class="py-2 border-b">
                        <p class="font-medium">{{ $customer->name }}</p>
                        <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->created_at->format('M d, Y') }}</p>
                    </div>
                    @empty
                    <p class="text-gray-500">No customers registered this month</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Pharmacies Registered</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    @forelse($pharmacies as $pharmacy)
                    <div class="py-2 border-b">
                        <p class="font-medium">{{ $pharmacy->name }}</p>
                        <p class="text-sm text-gray-600">{{ $pharmacy->email }}</p>
                        <p class="text-sm text-gray-500">{{ $pharmacy->created_at->format('M d, Y') }}</p>
                    </div>
                    @empty
                    <p class="text-gray-500">No pharmacies registered this month</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
