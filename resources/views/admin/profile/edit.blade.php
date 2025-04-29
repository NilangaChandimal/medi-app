@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Admin Profile</h2>
{{-- error handle --}}
@if ($errors->any())
<div class="bg-red-500 text-white p-3 rounded mb-4">
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
</div>
@endif
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Old Password</label>
                <input type="password" name="old_password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('old_password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" name="password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="text-right">
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
