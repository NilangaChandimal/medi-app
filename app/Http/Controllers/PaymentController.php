<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function showPaymentPage($chatId, $messageId)
{
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $message = Message::findOrFail($messageId);

    if ($message->total <= 0) {
        return redirect()->back()->with('error', 'Invalid payment amount');
    }

    try {
        $amount = (int)($message->total * 100);

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'metadata' => [
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]
        ]);

        return view('Customer.payment', [
            'clientSecret' => $paymentIntent->client_secret,
            'chatId' => $chatId,
            'messageId' => $messageId,
            'stripePublicKey' => config('services.stripe.key'),
            'total' => $message->total
        ]);

    } catch (\Exception $e) {
        Log::error('Stripe error: ' . $e->getMessage());
        return back()->withErrors(['message' => 'Payment processing error: ' . $e->getMessage()]);
    }
}

public function processPayment(Request $request, $chatId, $messageId)
{

    // dd($request->all());
    Log::info('Payment processing started', [
        'request_data' => $request->all(),
        'chat_id' => $chatId,
        'message_id' => $messageId
    ]);

    try {
        DB::connection()->getPdo();

        $paymentIntentId = $request->paymentIntentId;

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            $amount = $paymentIntent->amount / 100;
            $currency = strtoupper($paymentIntent->currency);

            // Validate currency
            if ($currency !== 'USD') {
                throw new \Exception('Invalid currency: ' . $currency);
            }
        } catch (\Exception $e) {
            Log::error('Stripe retrieval error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        $address = [
            'line1' => $request->address_line1,
            'line2' => $request->address_line2 ?: null,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code
        ];

        // Create payment record
        try {
            $paymentData = [
                'payment_intent_id' => $paymentIntentId,
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'amount' => $amount,
                'status' => strtolower($paymentIntent->status),
                'payment_date' => now(),
                'customer_id' => auth()->id(),
                'phone_number' => $request->phone,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'address' => json_encode($address), // Store complete address as JSON for future use
                'created_at' => now(),
                'updated_at' => now()
            ];

            $directInsert = DB::table('payments')->insert($paymentData);

            if ($directInsert) {
                Log::info('Payment recorded via direct insert', $paymentData);
                return redirect()->route('customer.chats.show', $chatId)
                    ->with('success', "Payment of $currency $amount successful!");
            }

            $payment = new Payment($paymentData);
            $payment->save();

            Log::info('Payment recorded via Eloquent', $paymentData);
            return redirect()->route('customer.chats.show', $chatId)
                ->with('success', "Payment of $currency $amount successful!");

        } catch (\Exception $e) {
            Log::error('Payment recording failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Payment recording failed: ' . $e->getMessage());
        }

    } catch (\Exception $e) {
        Log::error('Payment processing error', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
    }
}
}
