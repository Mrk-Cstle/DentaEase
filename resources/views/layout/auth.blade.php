<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Santiago-Amancio Dental Clinic')</title>

    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .logo-blue {
  filter: invert(43%) sepia(99%) saturate(5121%) hue-rotate(200deg) brightness(95%) contrast(90%);
}

    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav x-data="{ mobileOpen: false, accountOpen: false }" class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo + Name -->
                <div class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 logo-blue">


                    Santiago-Amancio Dental Clinic
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="{{ url('/') }}#about" class="text-gray-700 hover:text-blue-600">About</a>
                    <a href="{{ url('/') }}#branches" class="text-gray-700 hover:text-blue-600">Locations</a>
                    <a href="{{ url('/') }}#services" class="text-gray-700 hover:text-blue-600">Services</a>

                    <!-- Account Dropdown -->
                    <div class="relative inline-block">
                        <button @click="accountOpen = !accountOpen" class="text-gray-700 hover:text-blue-600 px-2 py-1">
                            Account ▾
                        </button>
                        <div 
                            x-show="accountOpen" 
                            @click.away="accountOpen = false"
                            x-transition
                            class="absolute bg-white text-gray-800 shadow-lg rounded-md mt-2 right-0 w-48 z-50"
                        >
                            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
                            <a href="{{ route('signupui') }}" class="block px-4 py-2 hover:bg-gray-100">Signup</a>
                            <a href="{{ url('/qr') }}" class="block px-4 py-2 hover:bg-gray-100">Login with QR</a>
                            <a href="{{ url('/faceui') }}" class="block px-4 py-2 hover:bg-gray-100">Login with Face</a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Hamburger -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="focus:outline-none">
                        <!-- Hamburger icon -->
                        <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <!-- Close icon -->
                        <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileOpen" x-transition class="md:hidden bg-white shadow-lg">
            <a href="{{ url('/') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Home</a>
            <a href="{{ url('/') }}#about" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">About</a>
            <a href="{{ url('/') }}#branches" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Locations</a>
            <a href="{{ url('/') }}#services" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Services</a>
            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Login</a>
            <a href="{{ route('signupui') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Signup</a>
            <a href="{{ url('/qr') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Login with QR</a>
            <a href="{{ url('/faceui') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Login with Face</a>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow flex items-center justify-center px-4 pt-24">
        <div class="bg-gray-50">
            @yield('auth-content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-500 pb-4">
        © {{ date('Y') }} Santiago-Amancio Dental Clinic. All rights reserved.
    </footer>
</body>
</html>
