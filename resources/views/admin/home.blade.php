@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                    <p class="text-indigo-100 mt-2">Welcome back! Here's your pharmacy management overview</p>
                </div>
                <div class="hidden md:block">
                    <i class="fa fa-tachometer-alt text-white opacity-60 text-5xl"></i>
                </div>
            </div>
        </div>
    </div>



    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Customers</h3>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $customerCount }}</p>
                    <p class="text-sm text-gray-500 mt-1">Active platform users</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.customers.show') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">
                    View Details
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Pharmacies</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $pharmacyCount }}</p>
                    <p class="text-sm text-gray-500 mt-1">Registered partners</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.pharmacies.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium transition-colors duration-200">
                    View Details
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Registration Trends</h3>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <canvas id="registrationChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Pharmacy Earnings (Rs.)</h3>
            <div class="flex space-x-2">
                <button id="btnMonthly" class="chart-toggle px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200"  data-type="monthly">Monthly</button>
                <button id="btnYearly" class="chart-toggle px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200" data-type="yearly">Yearly</button>
            </div>

        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <canvas id="pharmacyAmountChart" class="w-full h-64"></canvas>
        </div>
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
                    borderWidth: 0,
                    borderRadius: 4,
                    barThickness: 12,
                },
                {
                    label: 'Pharmacies',
                    data: @json($chartData['pharmacies']),
                    backgroundColor: '#10B981',
                    borderWidth: 0,
                    borderRadius: 4,
                    barThickness: 12,
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
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false
                    }
                }
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const ctxPharmacy = document.getElementById('pharmacyAmountChart').getContext('2d');

    const monthlyLabels = @json($monthlyChart['labels']);
const yearlyLabels = @json($yearlyChart['labels']);

function generateColors(count) {
    const palette = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#6366f1'];
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(palette[i % palette.length]);
    }
    return colors;
}

const monthlyData = {
    labels: monthlyLabels,
    datasets: [{
        label: 'Monthly Earnings (Rs.)',
        data: @json($monthlyChart['amounts']),
        backgroundColor: generateColors(monthlyLabels.length),
        borderRadius: 4,
        barThickness: 12
    }]
};

const yearlyData = {
    labels: yearlyLabels,
    datasets: [{
        label: 'Yearly Earnings (Rs.)',
        data: @json($yearlyChart['amounts']),
        backgroundColor: generateColors(yearlyLabels.length),
        borderRadius: 4,
        barThickness: 12
    }]
};


    // Initialize with Monthly
    let pharmacyChart = new Chart(ctxPharmacy, {
        type: 'bar',
        data: monthlyData,
        options: chartOptions()
    });

    document.getElementById('btnMonthly').addEventListener('click', function () {
        pharmacyChart.data = monthlyData;
        pharmacyChart.update();
    });

    document.getElementById('btnYearly').addEventListener('click', function () {
        pharmacyChart.data = yearlyData;
        pharmacyChart.update();
    });

    function chartOptions() {
        return {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 12 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 12 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: false
                }
            }
        };
    }

    function fakeColor() {
        const colors = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6', '#ec4899'];
        return colors[Math.floor(Math.random() * colors.length)];
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const chartToggles = document.querySelectorAll('.chart-toggle');

    chartToggles.forEach(btn => {
        btn.addEventListener('click', function () {
            chartToggles.forEach(b => b.classList.remove('active-red'));

            this.classList.add('active-red');

            const type = this.dataset.type;
            if (type === 'monthly') {
                pharmacyChart.data = monthlyData;
            } else if (type === 'yearly') {
                pharmacyChart.data = yearlyData;
            }
            pharmacyChart.update();
        });
    });

    document.getElementById('btnMonthly').classList.add('active-red');
});
</script>
<style>
    .active-red {
        background-color: #fee2e2;
        color: #b91c1c;
    }
</style>
@endsection
