@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
    <div class="container mx-auto px-4 py-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-blue-800 mb-4">Find Your Pharmacy</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Connect with local pharmacies, check ratings, and start conversations with healthcare professionals.</p>
        </div>

        <div class="max-w-2xl mx-auto mb-12 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <form method="GET" action="{{ route('customer.pharmacy') }}" class="group">
                <input type="text" name="search" placeholder="Search by pharmacy name, location or services..."
                    class="w-full pl-10 pr-28 py-4 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                    value="{{ request('search') }}">
                <button type="submit"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full transition-all duration-200 shadow-md group-hover:shadow-lg">
                    Search
                </button>
            </form>
        </div>

        @php
            $filteredPharmacies = session('filteredPharmacies');
            $pharmacies = $filteredPharmacies ?? $pharmacies;
        @endphp

        @if ($pharmacies->isEmpty())
            <div class="bg-white rounded-2xl p-10 text-center shadow-lg max-w-2xl mx-auto border border-gray-100">
                <div class="bg-blue-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Pharmacies Found</h3>
                <p class="text-gray-600 mb-6">We couldn't find any pharmacies matching your search criteria.</p>
                <a href="{{ route('customer.pharmacy') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    View All Pharmacies
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($pharmacies as $pharmacy)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-24 relative">
                            <div class="absolute -bottom-10 left-6">
                                @php
                                    $imagePath = 'profile_image/' . $pharmacy->profile_image;
                                @endphp

                                @if (file_exists(public_path($imagePath)))
                                    <img src="{{ asset($imagePath) }}" alt="{{ $pharmacy->name }}"
                                        class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                                @else
                                    <img src="{{ asset('profile_image/default.jpg') }}" alt="Default"
                                        class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                                @endif
                            </div>
                        </div>

                        <div class="p-6 pt-12">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $pharmacy->name }}</h3>

                            <div class="inline-flex items-center bg-blue-50 rounded-full px-3 py-1 text-sm text-blue-700 font-medium mb-3">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $pharmacy->registration_number }}
                            </div>

                            <div class="flex items-start space-x-2 mb-2">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div class="text-gray-600 text-sm">
                                    <p>{{ $pharmacy->address }}</p>
                                    <p>{{ $pharmacy->city }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-600 text-sm">{{ $pharmacy->phone }}</span>
                            </div>

                            @php
                                $averageRating = round($pharmacy->ratings_avg_rating ?? 0);
                            @endphp
                            <div class="flex items-center mb-6">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.174c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.967c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.967a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.174a1 1 0 00.95-.69l1.286-3.967z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm font-medium {{ $averageRating > 0 ? 'text-gray-600' : 'text-gray-400' }}">
                                    {{ $averageRating > 0 ? "$averageRating / 5" : "No ratings yet" }}
                                </span>
                            </div>

                            <form method="POST" action="{{ route('customer.startChat', $pharmacy->id) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>Start Chat</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
