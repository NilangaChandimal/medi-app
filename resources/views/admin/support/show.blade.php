@extends('layouts.app')

@section('title', 'Admin Support Tickets')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <a href="{{ route('admin.support.index') }}"
           class="mb-4 inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-150">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tickets
        </a>

        <div class="border-b border-gray-200 pb-4 mb-6">
            <h1 class="text-2xl font-bold mb-3 text-gray-800">{{ $ticket->subject }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">Submitted by:</span>
                    <span class="ml-1">{{ $ticket->user->name ?? 'Unknown User' }}
                    ({{ class_basename($ticket->user_type) }})</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Status:</span>
                    <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium
                          {{ match($ticket->status) {
                              'open' => 'bg-red-100 text-red-800',
                              'answered' => 'bg-blue-100 text-blue-800',
                              'closed' => 'bg-green-100 text-green-800',
                          } }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Submitted:</span>
                    <span class="ml-1">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                Original Message
            </h3>
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 shadow-sm">
                <p class="whitespace-pre-wrap text-gray-700">{{ $ticket->message }}</p>
            </div>

            @if($ticket->user->email || $ticket->user->phone)
            <div class="mt-5 bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h4 class="font-medium mb-2 text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Contact Information:
                </h4>
                <ul class="list-disc pl-6 text-blue-700">
                    @if($ticket->user->email)
                    <li>Email: <span class="font-medium">{{ $ticket->user->email }}</span></li>
                    @endif
                    @if($ticket->user->phone)
                    <li>Phone: <span class="font-medium">{{ $ticket->user->phone }}</span></li>
                    @endif
                </ul>
            </div>
            @endif
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Admin Response
            </h3>

            @if($ticket->status === 'closed')
            <div class="bg-gray-100 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <p class="text-gray-600">This ticket has been closed and can no longer be modified.</p>
                </div>
            </div>
            @else
            <form method="POST" action="{{ route('admin.support.update', $ticket) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="response" class="block text-sm font-medium text-gray-700 mb-1">
                        Your Response
                    </label>
                    <textarea name="response" id="response" rows="6"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required {{ $ticket->status === 'closed' ? 'disabled' : '' }}
                    >{{ old('response', $ticket->admin_response) }}</textarea>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Update Status
                    </label>
                    <select name="status" id="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        {{ $ticket->status === 'closed' ? 'disabled' : '' }}>
                        <option value="answered" {{ $ticket->status === 'answered' ? 'selected' : '' }}>
                            Answered
                        </option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>
                            Closed
                        </option>
                    </select>
                </div>

                @if($ticket->admin_response)
                <div class="bg-blue-50 p-5 rounded-lg border border-blue-100">
                    <h4 class="font-medium mb-2 text-blue-800 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Previous Response:
                    </h4>
                    <p class="whitespace-pre-wrap text-gray-700">{{ $ticket->admin_response }}</p>
                    @if($ticket->admin)
                    <p class="mt-2 text-sm text-gray-600 italic">
                        - {{ $ticket->admin->name }}
                        ({{ $ticket->updated_at->format('M d, Y h:i A') }})
                    </p>
                    @endif
                </div>
                @endif

                <div class="text-right">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700 shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               {{ $ticket->status === 'closed' ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $ticket->status === 'closed' ? 'disabled' : '' }}>
                        Update Ticket
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });
    });
</script>
@endif
@endsection
