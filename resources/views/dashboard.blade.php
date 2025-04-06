{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MedFinder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-2xl mt-20">
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-2xl font-bold mb-4">Welcome, {{ $user->name }}</h1>
            <div class="space-y-4">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>

                @if($user->pharmacy)
                    <div class="mt-4 p-4 bg-gray-50 rounded">
                        <h2 class="text-lg font-semibold mb-2">Pharmacy Details</h2>
                        <p><strong>Registration Number:</strong> {{ $user->pharmacy->registration_number }}</p>
                        <p><strong>Address:</strong> {{ $user->pharmacy->address }}</p>
                    </div>
                @endif

                <form method="POST" action="/logout" class="mt-6">
                    @csrf
                    <button type="submit"
                            class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html> --}}
