@extends('layouts.app')

@section('title', 'Contact Admin')

@section('content')
<div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-white">Contact Admin</h1>
            <p class="text-indigo-100 mt-1">Send a new message or view responses from admin.</p>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Previous Requests -->
            @if($tickets->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Previous Requests</h2>
                <div class="space-y-6">
                    @foreach($tickets as $ticket)
                    <div class="bg-gray-50 border border-gray-200 p-5 rounded-xl shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $ticket->subject }}</h3>
                                <p class="text-sm text-gray-500">
                                    Submitted: {{ $ticket->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <span class="text-sm font-medium px-3 py-1 rounded-full
                                {{ match($ticket->status) {
                                    'open' => 'bg-yellow-100 text-yellow-800',
                                    'answered' => 'bg-blue-100 text-blue-800',
                                    'closed' => 'bg-green-100 text-green-800',
                                } }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <p class="mt-4 text-gray-600 whitespace-pre-wrap">{{ $ticket->message }}</p>

                        @if($ticket->admin_response)
                        <div class="mt-5 bg-white border-l-4 border-indigo-600 pl-4 pr-4 py-3 rounded-md">
                            <div class="flex items-center text-indigo-600 font-medium mb-2">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Admin Response <span class="ml-2 text-sm text-gray-500">({{ $ticket->updated_at->format('M d, Y h:i A') }})</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->admin_response }}</p>
                        </div>
                        @else
                        <p class="mt-3 text-sm text-gray-500 italic">Waiting for admin response...</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- New Request -->
            <div class="border-t pt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">New Request</h2>
                <form method="POST"
                      action="{{ auth()->guard('pharmacy')->check() ? route('pharmacy.contact.store') : route('customer.contact.store') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" name="subject" id="subject"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>

                    <div class="mb-5">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" id="message" rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  required></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit"
                                class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium shadow-md transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
