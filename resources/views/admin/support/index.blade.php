@extends('layouts.app')

@section('title', 'Admin Support Tickets')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Customer Support</h1>
                        <p class="text-indigo-100 mt-2">Manage your pharmacies and customer complain and support</p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fa fa-headphones-alt text-white opacity-60 text-5xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex justify-between items-center p-4 bg-gray-50 border-b border-gray-200">
                <div class="text-gray-600 font-medium">All tickets</div>
                <form method="GET" class="flex space-x-2 items-center mb-4">
                    <select name="status"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        <option value="">All statuses</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Answered" {{ request('status') == 'Answered' ? 'selected' : '' }}>Answered</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150">
                        Filter
                    </button>
                </form>

            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                {{ $ticket->user->name ?? 'N/A' }}
                                <p class="text-sm text-gray-500">{{ class_basename($ticket->user_type) }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $ticket->subject }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-sm rounded-full
                            {{ match ($ticket->status) {
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
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
