@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Payments</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pharmacy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="px-6 py-4">{{ $payment->customer?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $payment->chat->pharmacy->name ?? 'N/A' }}</td>

                                <td class="px-6 py-4">
                                    {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded
                                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                           ($payment->status === 'shipped' ? 'bg-blue-100 text-blue-800' :
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>


                                <td class="px-6 py-4">
                                    {{ $payment->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded text-sm">
                                        Show
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@endsection
