<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>@yield('title', 'Auth Page')</title>
</head>

<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="w-full bg-gradient-to-r from-sky-400 to-sky-500 shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14">
                <div>
                    <h1 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">
                        Santiago-Amancio Dental Clinic
                    </h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow flex items-center justify-center px-4">
        <div class="w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 bg-white rounded-xl shadow-lg p-8 mt-8 mb-12">
            @yield('auth-content')
        </div>
    </main>

    <!-- Optional Footer -->
    {{-- <footer class="text-center text-sm text-blue-700 pb-4">
        © {{ date('Y') }} Santiago-Amancio Dental Clinic. All rights reserved.
    </footer> --}}

</body>
</html>
