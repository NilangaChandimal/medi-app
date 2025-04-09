@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Pharmacies!</h1>

        <!-- Search Bar -->
        <div class="max-w-2xl mb-10">
            <form method="GET" action="{{ route('customer.pharmacy') }}" class="relative">
                <input type="text" name="search" placeholder="Search workers..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    value="{{ request('search') }}">
                <button type="submit"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition-colors">
                    Search
                </button>
            </form>
        </div>

        @php
            $filteredPharmacies = session('filteredPharmacies');
            $pharmacies = $filteredPharmacies ?? $pharmacies;
        @endphp

        @if ($pharmacies->isEmpty())
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center shadow-md">
                <p class="text-gray-600 dark:text-gray-300 text-lg">No pharmacies found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($pharmacies as $pharmacy)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <div class="flex-shrink-0">
                                    @php
                                        $imagePath = 'profile_image/' . $pharmacy->profile_image;
                                    @endphp

                                    @if (file_exists(public_path($imagePath)))
                                        <img src="{{ asset($imagePath) }}" alt="{{ $pharmacy->name }}"
                                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                    @else
                                        <img src="{{ asset('profile_image/default.jpg') }}" alt="Default"
                                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                    @endif

                                </div>
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $pharmacy->name }}
                                    </h3>
                                    <div class="space-y-1">
                                        <p class="text-blue-600 dark:text-blue-400 font-medium">
                                            {{ $pharmacy->registration_number }}</p>
                                        <p class="text-gray-600 dark:text-gray-300">{{ $pharmacy->address }}</p>
                                        <p class="text-gray-600 dark:text-gray-300">{{ $pharmacy->city }}</p>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-600 dark:text-gray-300 text-sm line-clamp-3">
                                {{ $pharmacy->phone }}
                            </p>
                            @php
                            $averageRating = round($pharmacy->ratings_avg_rating ?? 0);
                        @endphp

                        <div class="flex items-center mt-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.174c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.967c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.967a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.174a1 1 0 00.95-.69l1.286-3.967z" />
                                </svg>
                            @endfor

                            <span class="ml-2 text-sm text-gray-500">
                                {{ $averageRating > 0 ? "$averageRating / 5" : "No ratings yet" }}
                            </span>
                        </div>

                            <form method="POST" action="{{ route('customer.startChat', $pharmacy->id) }}" class="mt-6">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                                    <span>Start Chat</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
