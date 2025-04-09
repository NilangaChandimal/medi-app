@extends('layouts.app')

@section('title', 'Admin Support Tickets')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <!-- Back Button -->
        <a href="{{ route('admin.support.index') }}"
           class="mb-4 inline-block text-indigo-600 hover:text-indigo-900">
            &larr; Back to Tickets
        </a>

        <!-- Ticket Header -->
        <div class="border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold mb-2">{{ $ticket->subject }}</h1>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Submitted by:</span>
                    {{ $ticket->user->name ?? 'Unknown User' }}
                    ({{ class_basename($ticket->user_type) }})
                </div>
                <div>
                    <span class="font-medium">Status:</span>
                    <span class="px-2 py-1 rounded
                          {{ match($ticket->status) {
                              'open' => 'bg-red-100 text-red-800',
                              'answered' => 'bg-blue-100 text-blue-800',
                              'closed' => 'bg-green-100 text-green-800',
                          } }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
                <div>
                    <span class="font-medium">Submitted:</span>
                    {{ $ticket->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>

        <!-- Ticket Content -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Original Message</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="whitespace-pre-wrap">{{ $ticket->message }}</p>
            </div>

            @if($ticket->user->email || $ticket->user->phone)
            <div class="mt-4">
                <h4 class="font-medium mb-2">Contact Information:</h4>
                <ul class="list-disc pl-6">
                    @if($ticket->user->email)
                    <li>Email: {{ $ticket->user->email }}</li>
                    @endif
                    @if($ticket->user->phone)
                    <li>Phone: {{ $ticket->user->phone }}</li>
                    @endif
                </ul>
            </div>
            @endif
        </div>

        <!-- Response Section -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Admin Response</h3>

            @if($ticket->status === 'closed')
            <div class="bg-gray-100 p-4 rounded-lg">
                <p class="text-gray-600">This ticket has been closed and can no longer be modified.</p>
            </div>
            @else
            <form method="POST" action="{{ route('admin.support.update', $ticket) }}">
                @csrf
                @method('PUT')

                <!-- Response Input -->
                <div class="mb-4">
                    <label for="response" class="block text-sm font-medium text-gray-700">
                        Your Response
                    </label>
                    <textarea name="response" id="response" rows="6"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required {{ $ticket->status === 'closed' ? 'disabled' : '' }}
                    >{{ old('response', $ticket->admin_response) }}</textarea>
                </div>

                <!-- Status Selection -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">
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

                <!-- Response History -->
                @if($ticket->admin_response)
                <div class="mb-4 bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-medium mb-2">Previous Response:</h4>
                    <p class="whitespace-pre-wrap">{{ $ticket->admin_response }}</p>
                    @if($ticket->admin)
                    <p class="mt-2 text-sm text-gray-600">
                        - {{ $ticket->admin->name }}
                        ({{ $ticket->updated_at->format('M d, Y h:i A') }})
                    </p>
                    @endif
                </div>
                @endif

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700
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
