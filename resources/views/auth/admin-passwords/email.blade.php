<!doctype html>
<html lang="en">

<head>
    <base href="/" />
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin Password Reset</title>

    <meta name="description" content="" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Full page flex container to center content -->
    <div class="min-h-screen flex items-center justify-center px-4">
        <!-- Card -->
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
            <h2 class="text-center text-3xl font-bold text-gray-800">Forgot Your Password?</h2>

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

            <form action="{{ route('admin.password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <button type="submit"
                    class="w-full mt-4 py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm transition">
                    Send Reset Link
                </button>
            </form>
        </div>
    </div>
</body>

</html>
