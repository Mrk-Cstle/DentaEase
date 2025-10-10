@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')

    <!-- Hero -->
    <section id="home" class="hero-gradient pt-16 pb-20">
        <div class="max-w-7xl mx-auto px-6 pt-20 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Your Smile is Our <span class="text-yellow-300">Priority</span>
            </h1>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Since 1987, Santiago-Amancio Dental Clinic has been providing exceptional dental care across Bulacan.
            </p>
            {{-- <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100">
                    Schedule Consultation
                </button>
                <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600">
                    Learn More
                </button>
            </div> --}}
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Story</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-12">
                From a single practice to a trusted network of dental excellence.
            </p>

            <div class="grid md:grid-cols-2 gap-12 text-left">
                <div class="bg-blue-50 p-8 rounded-2xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Founded in 1987</h3>
                    <p class="text-gray-600">
                        Licensed dentists Dr. Abelardo Santiago and Dr. Marieta Santiago-Amancio started with a vision of high-quality dental care.
                    </p>
                </div>
                <div class="bg-green-50 p-8 rounded-2xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                    <p class="text-gray-600">
                        To provide exceptional treatment through professional and compassionate dental services.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Branches -->
    <section id="branches" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Locations</h2>
            <p class="text-xl text-gray-600 mb-12">Find the nearest Santiago-Amancio Dental Clinic in Bulacan</p>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($branches as $branch)
                    <div class="bg-white rounded-2xl p-6 shadow-lg card-hover text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $branch->name }}</h3>
                        <p class="text-gray-600 mb-2">{{ $branch->address }}</p>
                        @if($branch->opening_time && $branch->closing_time)
                            <p class="text-sm text-gray-500">
                                ⏰ {{ $branch->opening_time->format('h:i A') }} - {{ $branch->closing_time->format('h:i A') }}
                            </p>
                        @endif
                        {{-- <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            Book Appointment
                        </button> --}}
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Services We Offer</h2>
            <p class="text-xl text-gray-600 mb-12">Comprehensive care for your dental health</p>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($services as $service)
                    <div class="bg-gray-50 rounded-2xl shadow-md overflow-hidden card-hover text-left">
                        @if($service->image)
                            <img src="{{ asset('storage/service_images/' . $service->image) }}" 
                                 alt="{{ $service->name }}" 
                                 class="w-full h-40 object-cover">
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600 mb-3">{{ $service->description }}</p>
                            <p class="text-sm text-gray-700">⏱ {{ $service->approx_time ?? 'N/A' }} minutes</p>
                            {{-- <p class="text-sm text-gray-700">💰 ₱{{ number_format($service->approx_price, 2) }}</p> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Staff Section -->
<section id="staff" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-gray-900 mb-6">Meet Our Team</h2>
        <p class="text-xl text-gray-600 mb-12">Our experienced doctors and friendly receptionists are here to care for your smile</p>

        <!-- Doctors -->
        <h3 class="text-2xl font-semibold text-gray-800 mb-8">Our Doctors</h3>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @foreach($doctors as $doctor)
                @if($doctor)
                <div class="p-6 bg-white rounded-2xl shadow-lg card-hover text-center">
                    <img src="{{ $doctor->profile_image ? asset('storage/profile_pictures/'.$doctor->profile_image) : asset('images/default-doctor.png') }}"
                         alt="{{ $doctor->full_name ?? 'Doctor' }}"
                         class="w-32 h-32 mx-auto rounded-full object-cover mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $doctor->full_name ?? 'Unknown Doctor' }}</h3>
                    <p class="text-gray-600">{{ $doctor->specialization ?? 'General Dentistry' }}</p>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Receptionists -->
        <h3 class="text-2xl font-semibold text-gray-800 mb-8">Our Receptionists</h3>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($receptionists as $receptionist)
                @if($receptionist)
                <div class="p-6 bg-white rounded-2xl shadow-lg card-hover text-center">
                    <img src="{{ $receptionist->profile_image ? asset('storage/profile_pictures/'.$receptionist->profile_image) : asset('images/default-receptionist.png') }}"
                         alt="{{ $receptionist->full_name ?? 'Receptionist' }}"
                         class="w-32 h-32 mx-auto rounded-full object-cover mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $receptionist->full_name ?? 'Unknown Receptionist' }}</h3>
                    <p class="text-gray-600">Receptionist</p>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

    <!-- CTA -->
    <section id="contact" class="py-20 hero-gradient text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Start Your Smile Journey?</h2>
        <p class="text-xl text-blue-100 mb-8">Trusted by families for over 35 years</p>
        {{-- <button class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100">
            📞 Call Us
        </button> --}}
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-8">
            <div>
                <div class="text-2xl font-bold text-blue-400 mb-4">🦷 Santiago-Amancio</div>
                <p class="text-gray-400">Your trusted dental partner since 1987</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#about" class="hover:text-white">About</a></li>
                    <li><a href="#services" class="hover:text-white">Services</a></li>
                    <li><a href="#branches" class="hover:text-white">Locations</a></li>
                    <li><a href="#contact" class="hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Contact</h4>
                <p class="text-gray-400">✉️ info@santiago-amancio.com</p>
                <p class="text-gray-400">📍 Bulacan, PH</p>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            © {{ date('Y') }} Santiago-Amancio Dental Clinic. All rights reserved.
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener("click", e => {
                e.preventDefault();
                document.querySelector(a.getAttribute("href"))?.scrollIntoView({ behavior: "smooth" });
            });
        });
    </script>
@endsection
