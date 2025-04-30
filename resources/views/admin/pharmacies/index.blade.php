@extends('layouts.app')

@section('title', 'Pharmacy Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-bold">Pharmacy Management</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pharmacies as $pharmacy)
                    <tr>
                        <td class="px-6 py-4">{{ $pharmacy->name }}</td>
                        <td class="px-6 py-4">{{ $pharmacy->email }}</td>
                        <td class="px-6 py-4">{{ $pharmacy->registration_number }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded {{ $pharmacy->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($pharmacy->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($pharmacy->ratings_avg_rating, 1) }} ⭐
                            ({{ $pharmacy->ratings_count }} reviews)
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pharmacies.show', $pharmacy) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                            <form method="POST" action="{{ route('admin.pharmacies.toggle-status', $pharmacy) }}">
                                @csrf
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $pharmacy->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t">
            {{ $pharmacies->links() }}
        </div>
    </div>
</div>
@endsection
