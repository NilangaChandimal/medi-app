<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-8 sm:py-12">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 sm:px-8 py-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Admin Login</h1>
                    <p class="text-gray-600 mt-2">Welcome back! Please enter your credentials.</p>
                </div>
                @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="your@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me"
                                   type="checkbox"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>
                        <a href="{{ route('admin.password.request') }}"
                           class="text-sm font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Sign in
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Don't have an account?
                        <a href="{{ route('admin.register') }}"
                           class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                            Create one now
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
