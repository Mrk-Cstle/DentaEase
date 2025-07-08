<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Navigation')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-[#02ccfe] px-6 py-4 shadow-md flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img class="h-10" src="{{ asset('images/logo.png') }}" alt="Logo">
                <div>
                    <h1 class="text-white font-bold leading-tight">Santiago-Amancio Dental Clinic</h1>
                </div>
            </div>

            <!-- Notification + User Dropdown -->
            <div class="flex items-center space-x-6">
                <!-- Notification Bell -->
                <div class="relative">
                    <button id="notificationToggle" class="relative focus:outline-none">
                        <i class="fa-solid fa-bell text-xl text-white"></i>
                        @if(Auth::user()->unreadNotifications->count())
                            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </button>
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 transition duration-200 ease-in-out">
                        <div class="p-4 border-b">
                            <h3 class="text-sm font-bold text-gray-700">Notifications</h3>
                        </div>
                        <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                            @forelse($notifications ?? Auth::user()->notifications->take(10) as $notification)
                                <li class="px-4 py-3 hover:bg-gray-100 transition cursor-pointer">
                                    <p class="text-sm text-gray-800 font-medium">
                                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                    </p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="px-4 py-3 text-center text-sm text-gray-500">No notifications</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <div id="dropdownToggle" class="cursor-pointer flex items-center space-x-2 text-white">
                        <div class="w-10 h-10 rounded-full bg-white overflow-hidden border">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/profile_pictures/' . Auth::user()->profile_image) }}" class="object-cover w-full h-full">
                            @else
                                <i class="fa-solid fa-user text-gray-600 text-xl flex justify-center items-center h-full"></i>
                            @endif
                        </div>
                        <div class="text-sm">
                            <div class="font-bold">{{ Auth::user()->name }}</div>
                            <div class="text-xs">{{ Auth::user()->account_type }}</div>
                        </div>
                        <i class="fa-solid fa-caret-down text-sm ml-1"></i>
                    </div>
                    <ul id="dropdownMenu" class="absolute right-0 mt-2 w-44 bg-white border rounded-md shadow-lg hidden z-50">
                        <li><a href="/cprofile" class="block px-4 py-2 hover:bg-gray-100 text-sm"><i class="fa-regular fa-user mr-2"></i>Profile</a></li>
                        <li><a href="/logouts" class="block px-4 py-2 text-red-500 hover:bg-red-100 text-sm"><i class="fa-solid fa-right-from-bracket mr-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="bg-white w-64 border-r">
                <ul class="mt-6 space-y-2 px-4">
                    <li><a href="/bookingongoing" class="flex items-center space-x-2 px-3 py-2 rounded hover:bg-blue-50 text-blue-600"><i class="fa-solid fa-calendar-check"></i><span>Booking Ongoing</span></a></li>
                    <li><a href="/booking" class="flex items-center space-x-2 px-3 py-2 rounded hover:bg-blue-50 text-blue-600"><i class="fa-solid fa-calendar-plus"></i><span>Booking</span></a></li>
                    <li class="md:hidden"><a href="/cprofile" class="flex items-center space-x-2 px-3 py-2 rounded hover:bg-blue-50 text-blue-600"><i class="fa-solid fa-user"></i><span>Profile</span></a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
                <div class="content flex flex-col gap-5">
                    @yield('main-content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById('dropdownToggle');
            const dropdown = document.getElementById('dropdownMenu');

            toggleBtn.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
            });

            window.addEventListener('click', function (e) {
                if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            const notificationToggle = document.getElementById('notificationToggle');
            const notificationDropdown = document.getElementById('notificationDropdown');
            let hasMarked = false;

            notificationToggle.addEventListener('click', function () {
                notificationDropdown.classList.toggle('hidden');

                if (!hasMarked && !notificationDropdown.classList.contains('hidden')) {
                    fetch("{{ route('notifications.markAsRead') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                            "Content-Type": "application/json",
                        },
                    }).then(res => res.json()).then(data => {
                        hasMarked = true;
                        document.querySelectorAll('.fa-bell + span').forEach(el => el.remove());
                    });
                }
            });

            document.addEventListener('click', function (e) {
                if (!notificationToggle.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
