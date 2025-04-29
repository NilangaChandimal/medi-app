<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-cover bg-center flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
      style="background-image: url('{{ asset('images/customer-background.jpg') }}');">
    <div class="max-w-md w-full">
        <!-- Logo or Company Name Section -->


        <!-- Login Form Card -->
        <div class="bg-white shadow-2xl rounded-lg py-8 px-6 space-y-6">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Pharmacy Portal
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sign in to access your account
                </p>
            </div>
            <form method="POST" action="{{ route('pharmacy.login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email"
                               name="email"
                               type="email"
                               required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                               placeholder="Enter your email">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password"
                               name="password"
                               type="password"
                               required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                               placeholder="Enter your password">
                    </div>
                </div>
                <a href="{{ route('pharmacy.password.request') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                 Forgot Password?
             </a>
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="text-sm text-center">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="{{ route('pharmacy.register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                        Register here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const showAlert = (icon, title, text) => {
            Swal.fire({
                icon,
                title,
                text,
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb',
                customClass: {
                    container: 'font-sans'
                }
            });
        };

        @if (session('blocked'))
            showAlert('error', 'Access Blocked', '{{ session('blocked') }}');
        @endif

        @if (session('checked'))
            showAlert('warning', 'Account Under Review', '{{ session('checked') }}');
        @endif

        @if ($errors->any())
            showAlert('error', 'Login Error', '{{ $errors->first() }}');
        @endif
    </script>
</body>
</html>
