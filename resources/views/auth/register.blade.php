<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MedFinder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-md mt-20">
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Register As</label>
                    <select name="role" id="role" class="w-full px-3 py-2 border rounded" required>
                        <option value="customer">Customer</option>
                        <option value="pharmacy">Pharmacy</option>
                    </select>
                </div>

                <!-- Common Fields -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 border rounded">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required
                           class="w-full px-3 py-2 border rounded">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border rounded">
                </div>

                <!-- Pharmacy Specific Fields -->
                <div id="pharmacyFields" class="hidden">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Registration Number</label>
                        <input type="text" name="registration_number"
                               class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Address</label>
                        <textarea name="address" class="w-full px-3 py-2 border rounded">{{ old('address') }}</textarea>
                    </div>


                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone"
                               class="w-full px-3 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">License File</label>
                        <input type="file" name="license_details"
                               class="w-full px-3 py-2 border rounded">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center">
                Already have an account?
                <a href="{{ route('form.login') }}" class="text-blue-500 hover:underline">Login here</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            const pharmacyFields = document.getElementById('pharmacyFields');
            const pharmaInputs = pharmacyFields.querySelectorAll('input, textarea, select');

            if (this.value === 'pharmacy') {
                pharmacyFields.classList.remove('hidden');
                pharmaInputs.forEach(input => {
                    input.required = true;
                    input.disabled = false;
                });
            } else {
                pharmacyFields.classList.add('hidden');
                pharmaInputs.forEach(input => {
                    input.required = false;
                    input.disabled = true;
                });
            }
        });
        </script>
</body>
</html>
