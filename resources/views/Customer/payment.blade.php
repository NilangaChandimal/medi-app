<!-- resources/views/Customer/payment.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Gateway</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: white;
        }

        .StripeElement--focus {
            border-color: #4f46e5;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .StripeElement--invalid {
            border-color: #ef4444;
        }

        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 3px solid #4f46e5;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Complete Your Payment</h1>
            <p class="text-gray-600 mt-2">Please enter your payment details to continue</p>
        </div>
        <div class="payment-total mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Payment Total: ${{ number_format($total, 2) }}
            </h2>
        </div>
        <!-- Debug Information (only for development) -->
        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Payment Status Alerts -->
        <div id="payment-success" class="hidden mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">Your payment has been processed successfully.</span>
        </div>

        <div id="payment-processing" class="hidden mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <div class="spinner"></div>
            <span class="block sm:inline">Processing your payment...</span>
        </div>

        <form action="{{ route('customer.processPayment', ['chatId' => $chatId, 'messageId' => $messageId]) }}" method="POST" id="payment-form" class="space-y-6">
            @csrf
            <input type="hidden" name="paymentIntentId" id="payment-intent-id">
            <input type="hidden" name="amount" id="payment-amount" value="{{ $total ?? 0 }}">

            <!-- Billing Information -->
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Billing Information</h3>

                <!-- Phone Number -->
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="(123) 456-7890" required>
                </div>

                <!-- Address Line 1 -->
                <div class="mb-4">
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                    <input type="text" id="address_line1" name="address_line1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Street address" required>
                </div>

                <!-- Address Line 2 (Optional) -->
                <div class="mb-4">
                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                    <input type="text" id="address_line2" name="address_line2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apartment, suite, unit, etc.">
                </div>

                <!-- City, State, Zip in a grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" id="city" name="city" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" id="state" name="state" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                        <input type="text" id="postal_code" name="postal_code" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                </div>

            </div>

            <!-- Card Element Placeholder -->
            <div class="mb-4">
                <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">Card Details</label>
                <div id="card-element" class="shadow-sm"></div>
            </div>

            <!-- Used to display form errors -->
            <div id="card-errors" role="alert" class="text-red-600 text-sm min-h-6 mt-1"></div>

            <!-- Submit Button -->
            <button type="submit" id="submit-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 flex items-center justify-center">
                <span id="button-text">Pay Now</span>
            </button>

            <!-- Security Message -->
            <div class="text-xs text-gray-500 text-center mt-4 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Secured by Stripe. We never store your card details.
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set up Stripe.js and Elements
            const stripe = Stripe('{{ $stripePublicKey }}');
            const elements = stripe.elements();

            const style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#ef4444',
                    iconColor: '#ef4444'
                }
            };

            // Create an instance of the card Element
            const card = elements.create('card', { style: style });

            // Add an instance of the card Element into the `card-element` div
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element
            card.addEventListener('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission
            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const processingAlert = document.getElementById('payment-processing');
            const successAlert = document.getElementById('payment-success');
            const paymentIntentIdField = document.getElementById('payment-intent-id');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Disable the submit button to prevent repeated clicks
                submitButton.disabled = true;
                submitButton.classList.add('bg-indigo-400');
                submitButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                buttonText.innerHTML = '<div class="spinner"></div>Processing...';
                processingAlert.classList.remove('hidden');

                // Get the billing details from the form
                const billingDetails = {
                    name: 'Customer', // You could add a name field to the form
                    phone: document.getElementById('phone').value,
                    address: {
                        line1: document.getElementById('address_line1').value,
                        line2: document.getElementById('address_line2').value,
                        city: document.getElementById('city').value,
                        state: document.getElementById('state').value,
                        postal_code: document.getElementById('postal_code').value,
                    }
                };

                // Create a payment method with the card Element and billing details
                stripe.confirmCardPayment('{{ $clientSecret }}', {
                    payment_method: {
                        card: card,
                        billing_details: billingDetails
                    }
                }).then(function(result) {
                    processingAlert.classList.add('hidden');

                    if (result.error) {
                        // Show error to your customer
                        const errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;

                        // Re-enable the submit button
                        submitButton.disabled = false;
                        submitButton.classList.remove('bg-indigo-400');
                        submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                        buttonText.textContent = 'Pay Now';
                    } else {
                        // Payment succeeded
                        if (result.paymentIntent.status === 'succeeded') {
                            // Store payment intent ID in the hidden field
                            paymentIntentIdField.value = result.paymentIntent.id;

                            // Log for debugging
                            console.log("Payment Intent ID:", result.paymentIntent.id);

                            // Show success message
                            successAlert.classList.remove('hidden');
                            buttonText.textContent = 'Payment Successful!';
                            submitButton.classList.remove('bg-indigo-400', 'bg-indigo-600');
                            submitButton.classList.add('bg-green-600');

                            // Submit the form to store data in database
                            console.log("Submitting form with payment data...");
                            setTimeout(function() {
                                form.submit();
                            }, 1500);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
