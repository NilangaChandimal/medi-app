@extends('layouts.app')

@section('title', 'Contact Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Contact Admin</h1>

        <!-- Existing Tickets & Responses -->
        @if($tickets->isNotEmpty())
        <div class="mb-8 border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Your Previous Requests</h2>
            <div class="space-y-4">
                @foreach($tickets as $ticket)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-medium">{{ $ticket->subject }}</h3>
                            <p class="text-sm text-gray-500">
                                Submitted: {{ $ticket->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-sm rounded
                            {{ match($ticket->status) {
                                'open' => 'bg-yellow-100 text-yellow-800',
                                'answered' => 'bg-blue-100 text-blue-800',
                                'closed' => 'bg-green-100 text-green-800',
                            } }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>

                    <div class="mt-2">
                        <p class="text-gray-600 whitespace-pre-wrap">{{ $ticket->message }}</p>

                        @if($ticket->admin_response)
                        <div class="mt-4 bg-white p-4 rounded border border-gray-200">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-medium text-indigo-600">Admin Response:</span>
                                <span class="text-sm text-gray-500">
                                    {{ $ticket->updated_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <p class="text-gray-600 whitespace-pre-wrap">{{ $ticket->admin_response }}</p>
                        </div>
                        @else
                        <p class="mt-2 text-gray-500 text-sm">Waiting for admin response...</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- New Request Form -->
        <div class="border-t pt-6">
            <h2 class="text-xl font-semibold mb-4">New Request</h2>
            <form method="POST" action="{{ auth()->guard('pharmacy')->check()
                    ? route('pharmacy.contact.store')
                    : route('customer.contact.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" id="subject"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" id="message" rows="6"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required></textarea>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
