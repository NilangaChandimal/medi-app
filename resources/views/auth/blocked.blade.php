@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Account Blocked
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Your account has been restricted. Please contact support for assistance.
            </p>
            <div class="mt-6">
                <a href="mailto:support@example.com" class="text-indigo-600 hover:text-indigo-500">
                    Contact Support →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
