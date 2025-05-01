<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-transparent bg-gray-900">

    @if(auth()->guard('customer')->check())
        @include('components.nav-customer')
    @elseif(auth()->guard('pharmacy')->check())
        @include('components.nav-pharmacy')
    @elseif(auth()->guard('admin')->check())
        @include('components.nav-admin')
    @endif

    <div class="container mx-auto px-4 py-12">
        @yield('content')
        {{-- <div class="text-center text-gray-500 text-sm mt-8">
            <p>© 2025 Your Pharmacy Management System. All rights reserved.</p>
        </div> --}}
    </div>

</body>
</html>
