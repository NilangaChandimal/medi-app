<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedLink - Pharmacy System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <!-- Header -->
        <header class="mb-16 text-center">
            <h1 class="text-4xl font-bold text-blue-600 mb-2">MedLink</h1>
            <p class="text-xl text-gray-600">Your Comprehensive Pharmacy Management System</p>
        </header>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="p-8 md:p-12">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Welcome to MedLink</h2>
                    <p class="text-gray-600 mb-8">Please select your login portal below to access the system</p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Pharmacy Login Card -->
                        <div class="bg-blue-50 rounded-lg p-6 transition-all hover:shadow-md">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h6a2 2 0 012 2v5m-4-9l2-2m-2 2l-2-2" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-center text-blue-800 mb-2">Pharmacy Portal</h3>
                            <p class="text-sm text-gray-600 text-center mb-4">Access pharmacy management, inventory, and prescription tools</p>
                            <div class="text-center">
                                <a href="{{ route('pharmacy.login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition-colors">
                                    Pharmacy Login
                                </a>
                            </div>
                        </div>

                        <!-- Customer Login Card -->
                        <div class="bg-green-50 rounded-lg p-6 transition-all hover:shadow-md">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-center text-green-800 mb-2">Customer Portal</h3>
                            <p class="text-sm text-gray-600 text-center mb-4">Check prescriptions, order refills, and manage your account</p>
                            <div class="text-center">
                                <a href="{{ route('customer.login') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md transition-colors">
                                    Customer Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <p class="text-sm text-gray-500 text-center">© 2025 MedLink. All rights reserved.</p>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">Need help? Contact our support team</p>
                <a href="mailto:nilangachandimal1111@gmail.com" class="text-blue-600 hover:text-blue-800 font-medium">nilangachandimal1111@gmail.com</a>
            </div>
        </div>
    </div>
</body>
</html>
