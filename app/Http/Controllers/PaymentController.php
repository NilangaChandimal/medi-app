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

    // Validate total exists and is valid
    if ($message->total <= 0) {
        return redirect()->back()->with('error', 'Invalid payment amount');
    }

    try {
        // ✅ Use actual message total converted to cents
        $amount = (int)($message->total * 100);

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount, // Use calculated amount
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
            'total' => $message->total // Add this to pass to view
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
        // Verify database connection first
        DB::connection()->getPdo();

        // Get the payment intent ID from the form
        $paymentIntentId = $request->paymentIntentId;

        // Retrieve payment details from Stripe
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            // Get the actual amount paid (convert from cents to dollars)
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

        // Format the address from form inputs
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

            // First try direct DB insert
            $directInsert = DB::table('payments')->insert($paymentData);

            if ($directInsert) {
                Log::info('Payment recorded via direct insert', $paymentData);
                return redirect()->route('customer.chats.show', $chatId)
                    ->with('success', "Payment of $currency $amount successful!");
            }

            // Fallback to Eloquent if direct insert fails
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
