@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Payment Details</h2>

        <div class="space-y-3">
            <p><strong>Customer:</strong> {{ $payment->customer->name }}</p>
            <p><strong>Pharmacy:</strong> {{ $payment->chat->pharmacy->name ?? 'N/A' }}</p>
            <p><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Chat:</strong> #{{ $payment->chat_id }}</p>
            <p><strong>Message:</strong> {{ $payment->message->message ?? 'N/A' }}</p>
        </div>

        <a href="{{ route('admin.payments.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to list</a>
    </div>
</div>
@endsection
