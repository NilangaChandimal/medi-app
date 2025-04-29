@extends('layouts.app')

@section('title', 'Pharmacy Profile')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <h2 class="text-3xl font-bold text-indigo-700">Edit Pharmacy Profile</h2>
                <div class="bg-indigo-50 px-3 py-1 rounded-full text-indigo-700 text-sm font-medium">
                    {{ $pharmacy->registration_number }}
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Please correct the following errors:</span>
                    </div>
                    <ul class="ml-5 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pharmacy.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Image Section -->
                <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-100">
                    <label class="block text-lg font-medium text-gray-700 mb-3">Profile Image</label>
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow"
                                src="{{ $pharmacy->profile_image ? asset('profile_image/' . $pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                                alt="Pharmacy profile image">
                        </div>
                        <div class="w-full">
                            <div class="relative">
                                <input type="file" name="profile_image" id="profile_image" class="hidden">
                                <label for="profile_image" class="cursor-pointer inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Change Photo
                                </label>
                                <p class="text-sm text-gray-500 mt-2">PNG, JPG up to 2MB</p>
                            </div>
                            @error('profile_image')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Information Sections -->
                <div class="space-y-8">
                    <!-- Basic Information -->
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12zm-1-5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm-3-4a1 1 0 100 2h6a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Basic Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pharmacy Name</label>
                                <input type="text" name="name" value="{{ old('name', $pharmacy->name) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @error('name')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $pharmacy->email) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @error('email')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Registration Details -->
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1.581.814L10 13.197l-4.419 2.617A1 1 0 014 15V4zm2-1a1 1 0 00-1 1v10.566l3.419-2.022a1 1 0 011.162 0L13 14.566V4a1 1 0 00-1-1H6z" clip-rule="evenodd"/>
                            </svg>
                            Registration Details
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number</label>
                                <input type="text" name="registration_number"
                                    value="{{ old('registration_number', $pharmacy->registration_number) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 transition" readonly>
                                @error('registration_number')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">License Details</label>
                                <input type="text" name="license_details"
                                    value="{{ old('license_details', $pharmacy->license_details) }}" readonly
                                    class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @error('license_details')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Contact Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $pharmacy->phone) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @error('phone')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" value="{{ old('city', $pharmacy->city) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @error('city')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Address</label>
                            <textarea name="address" required rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">{{ old('address', $pharmacy->address) }}</textarea>
                            @error('address')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Password Update
                        </h3>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Old Password</label>
                            <input type="password" name="old_password"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                            @error('old_password')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" name="password"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @error('password')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="button" onclick="history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="ml-4 px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
