<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navigation -->
    <nav class="bg-sky-600 text-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
           <div class="flex items-center space-x-3">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14">
    <div class="text-xl font-bold">
        Santiago-Amancio Dental Clinic
    </div>
</div>

             

            <!-- Menu -->
            <!-- Menu -->
<div class="space-x-6 text-sm font-medium flex items-center">
    <a href="{{ url('/') }}" class="hover:text-gray-200">Home</a>
    <a href="#about" class="hover:text-gray-200">About</a>
    <a href="#doctors" class="hover:text-gray-200">Doctors</a>
    <a href="#receptionists" class="hover:text-gray-200">Receptionists</a>
    <a href="#branches" class="hover:text-gray-200">Branches</a>
    <a href="#services" class="hover:text-gray-200">Services</a>

 
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
        <a href="{{ url('/qr-login') }}" class="block px-4 py-2 hover:bg-gray-100">Login with QR</a>
        <a href="{{ url('/face-login') }}" class="block px-4 py-2 hover:bg-gray-100">Login with Face</a>
    </div>
</div>



</div>

        </div>
    </nav>

    <!-- Hero -->
    <header class="bg-sky-500 text-white p-12 shadow-md text-center mt-16">
        <h1 class="text-3xl md:text-4xl font-bold">Welcome to Santiago-Amancio Dental Clinic</h1>
        <p class="mt-2 text-sm md:text-base">Your trusted partner for healthy smiles</p>
    </header>

    <main class="max-w-7xl mx-auto p-6">

        <!-- About Us -->
        <section id="about" class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">About Us</h2>
            <p class="text-gray-700">
                Welcome to Santiago-Amancio Dental Clinic! Meet our doctors, receptionists, and branches.
            </p>
        </section>

        <!-- Doctors -->
        <section id="doctors" class="mb-12">
            <h2 class="text-xl font-semibold mb-6">Our Doctors</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($doctors as $doctor)
                    <div class="bg-white shadow-lg rounded-xl p-6 flex items-center hover:shadow-xl transition">
                        <img src="{{ $doctor->profile_image 
                                ? asset('storage/profile_pictures/' . $doctor->profile_image) 
                                : asset('images/defaultp.jpg') }}" 
                                alt="{{ $doctor->name }}" 
                                class="w-24 h-24 object-cover rounded-full shadow-md mr-6">

                        <div class="text-left">
                            <h3 class="text-lg font-bold">{{ $doctor->full_name }}</h3>
                            <p class="text-sm text-gray-500">Dentist</p>
                            <p class="text-sm text-gray-600">📞 {{ $doctor->contact_number ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">✉️ {{ $doctor->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Receptionists -->
        <section id="receptionists" class="mb-12">
            <h2 class="text-xl font-semibold mb-6">Our Receptionists</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($receptionists as $receptionist)
                    <div class="bg-white shadow-lg rounded-xl p-6 flex items-center hover:shadow-xl transition">
                        <img src="{{ $receptionist->profile_image 
                                ? asset('storage/profile_pictures/' . $receptionist->profile_image) 
                                : asset('images/defaultp.jpg') }}" 
                                alt="{{ $receptionist->name }}" 
                                class="w-24 h-24 object-cover rounded-full shadow-md mr-6">

                        <div class="text-left">
                            <h3 class="text-lg font-bold">{{ $receptionist->full_name }}</h3>
                            <p class="text-sm text-gray-500">Receptionist</p>
                            <p class="text-sm text-gray-600">📞 {{ $receptionist->contact_number ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">✉️ {{ $receptionist->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Branches -->
        <section id="branches" class="mb-12"> 
            <h2 class="text-xl font-semibold mb-6">Our Branches</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($branches as $branch)
                    <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-xl transition">
                        <h3 class="text-lg font-bold mb-2">{{ $branch->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $branch->address }}</p>
                        <p class="text-sm text-gray-600">📞 {{ $branch->contact_number ?? 'N/A' }}</p>

                        {{-- Open Days --}}
                        @if(!empty($branch->open_days))
                            <p class="text-sm text-gray-700 mt-2">
                                🗓 Open Days: 
                                {{ implode(', ', $branch->open_days) }}
                            </p>
                        @endif

                        {{-- Opening & Closing Time --}}
                        @if($branch->opening_time && $branch->closing_time)
                            <p class="text-sm text-gray-700">
                                ⏰ {{ $branch->opening_time->format('h:i A') }} - {{ $branch->closing_time->format('h:i A') }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Services -->
        <section id="services" class="mb-12">
            <h2 class="text-xl font-semibold mb-6">Services We Offer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition">
                        <!-- Service Image -->
                        @if($service->image)
                            <img src="{{ asset('storage/service_images/' . $service->image) }}" 
                                 alt="{{ $service->name }}" 
                                 class="w-full h-40 object-cover">
                        @else
                            <img src="{{ asset('images/logo.png') }}" 
                                 alt="Service" 
                                 class="w-full h-40 object-cover filter invert brightness-50">
                        @endif

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2">{{ $service->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $service->description }}</p>

                            <div class="flex justify-between text-sm text-gray-700">
                                <span>⏱ {{ $service->approx_time ?? 'N/A' }}</span>
                                <span>💰 ₱{{ number_format($service->approx_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-center py-4 text-sm text-gray-500">
        © {{ date('Y') }} Santiago-Amancio Dental Clinic. All rights reserved.
    </footer>

    <!-- Smooth Scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener("click", function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute("href"))
                    .scrollIntoView({ behavior: "smooth" });
            });
        });
    </script>

</body>
</html>
