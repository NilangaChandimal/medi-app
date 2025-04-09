@extends('layouts.app')

@section('title', 'Admin Support Tickets')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Support Tickets</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($tickets as $ticket)
                <tr>
                    <td class="px-6 py-4">
                        {{ $ticket->user->name ?? 'N/A' }}
                        <p class="text-sm text-gray-500">{{ class_basename($ticket->user_type) }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $ticket->subject }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-sm rounded-full
                            {{ match($ticket->status) {
                                'open' => 'bg-red-100 text-red-800',
                                'answered' => 'bg-blue-100 text-blue-800',
                                'closed' => 'bg-green-100 text-green-800',
                            } }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.support.show', $ticket) }}"
                           class="text-indigo-600 hover:text-indigo-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
