<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    {{-- @vite('resources/css/app.css') --}}
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-8 sm:py-12 lg:py-16">
        <div class="max-w-lg mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 sm:px-8 py-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Create Admin Account</h1>
                    <p class="mt-2 text-gray-600">Please fill in your information to register</p>
                </div>

                <form method="POST" action="{{ route('admin.register') }}" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1">
                            <input id="name"
                                   name="name"
                                   type="text"
                                   required
                                   class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                                   placeholder="Enter your full name">
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   required
                                   class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                                   placeholder="your@email.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   required
                                   class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation"
                                   name="password_confirmation"
                                   type="password"
                                   required
                                   class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input id="terms"
                               type="checkbox"
                               required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Create Account
                        </button>
                    </div>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Already have an account?
                        <a href="{{ route('admin.login') }}"
                           class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                            Sign in instead
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
