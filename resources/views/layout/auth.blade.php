<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>@yield('title', 'Auth Page')</title>
</head>

<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="bg-sky-600 text-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            
            <!-- Logo + Name -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14">
                <div class="text-xl font-bold">
                    Santiago-Amancio Dental Clinic
                </div>
            </div>

            <!-- Menu -->
            <div class="space-x-6 text-sm font-medium flex items-center">
                <a href="{{ url('/') }}" class="hover:text-gray-200">Home</a>
                <a href="{{ url('/') }}#about" class="hover:text-gray-200">About</a>
                <a href="{{ url('/') }}#doctors" class="hover:text-gray-200">Doctors</a>
                <a href="{{ url('/') }}#receptionists" class="hover:text-gray-200">Receptionists</a>
                <a href="{{ url('/') }}#branches" class="hover:text-gray-200">Branches</a>
                <a href="{{ url('/') }}#services" class="hover:text-gray-200">Services</a>

                <!-- Authentication Dropdown -->
                <div x-data="{ open: false }" class="relative inline-block">
                    <button @click="open = !open" class="hover:text-gray-200 px-2 py-1">Account ▾</button>

                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        class="absolute bg-white text-gray-800 shadow-lg rounded-md mt-2 right-0 w-48 z-50"
                    >
                        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
                        <a href="{{ route('signupui') }}" class="block px-4 py-2 hover:bg-gray-100">Signup</a>
                        <a href="{{ url('/qr') }}" class="block px-4 py-2 hover:bg-gray-100">Login with QR</a>
                        <a href="{{ url('/faceui') }}" class="block px-4 py-2 hover:bg-gray-100">Login with Face</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow flex items-center justify-center px-4 pt-24">
        <div class="w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 bg-white rounded-xl shadow-lg p-8 mt-8 mb-12">
            @yield('auth-content')
        </div>
    </main>

    <!-- Optional Footer -->
    <footer class="text-center text-sm text-blue-700 pb-4">
        © {{ date('Y') }} Santiago-Amancio Dental Clinic. All rights reserved.
    </footer>

</body>
</html>
