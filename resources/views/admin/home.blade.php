@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Customer Statistics Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-600">Total Customers</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $customerCount }}</p>
                </div>
                <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <a href="{{ route('admin.customers.show') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900">
                View Details →
            </a>
        </div>

        <!-- Pharmacy Statistics Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-600">Total Pharmacies</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $pharmacyCount }}</p>
                </div>
                <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <a href="{{ route('admin.pharmacies.index') }}" class="mt-4 inline-block text-green-600 hover:text-green-900">
                View Details →
            </a>
        </div>
    </div>

    <!-- Statistics Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Registration Trends</h3>
        <canvas id="registrationChart" class="w-full h-64"></canvas>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js@latest"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('registrationChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Customers',
                    data: @json($chartData['customers']),
                    backgroundColor: '#4F46E5',
                    borderWidth: 0
                },
                {
                    label: 'Pharmacies',
                    data: @json($chartData['pharmacies']),
                    backgroundColor: '#10B981',
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                onClick: (e, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const month = chart.data.labels[index];
                        window.location.href = `/admin/statistics/details/${index+1}`;
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
