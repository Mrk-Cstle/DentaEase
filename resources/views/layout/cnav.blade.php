<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Client Dashboard')</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0284c7',
            secondary: '#e0f2fe',
            accent: '#0f172a',
            navItem: '#38bdf8',
            background: '#f8fafc',
          },
          fontFamily: {
            sans: ['"Segoe UI"', 'Roboto', 'sans-serif'],
          }
        }
      }
    };
  </script>

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="bg-background font-sans">
  <div class="flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-primary px-4 py-4 shadow-lg flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <img class="h-10" src="{{ asset('images/logo.png') }}" alt="Logo" />
        <h1 class="text-white font-bold text-lg sm:text-xl">Santiago-Amancio Dental Clinic</h1>
      </div>

      <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative">
          <button id="notificationToggle" class="relative focus:outline-none">
            <i class="fa-solid fa-bell text-xl text-white"></i>
            @if(Auth::user()->unreadNotifications->count())
              <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
              <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            @endif
          </button>

          <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border rounded-lg shadow-lg z-50">
            <div class="p-4 border-b">
              <h3 class="text-sm font-bold text-gray-700">Notifications</h3>
            </div>
            <ul class="max-h-80 overflow-y-auto divide-y divide-gray-100">
              @forelse($notifications ?? Auth::user()->notifications->take(10) as $notification)
                <li class="px-4 py-3 hover:bg-gray-100 transition">
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
            <div class="text-sm hidden sm:block">
              <div class="font-bold">{{ Auth::user()->name }}</div>
              <div class="text-xs">{{ Auth::user()->account_type }}</div>
            </div>
            <i class="fa-solid fa-caret-down text-sm ml-1"></i>
          </div>
          <ul id="dropdownMenu" class="absolute right-0 mt-2 w-44 bg-white border rounded-md shadow-md hidden z-50">
            <li><a href="/cprofile" class="block px-4 py-2 hover:bg-gray-100 text-sm"><i class="fa-regular fa-user mr-2"></i>Profile</a></li>
            <li><a href="/logouts" class="block px-4 py-2 text-red-500 hover:bg-red-100 text-sm"><i class="fa-solid fa-right-from-bracket mr-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Main Layout Container -->
    <div class="flex flex-col sm:flex-row flex-1">

      <!-- Sidebar -->
      <nav class="bg-secondary sm:w-64 w-full border-b sm:border-b-0 sm:border-r px-4 py-4 shadow-md flex sm:flex-col flex-row flex-wrap sm:space-y-2 space-x-2 sm:space-x-0 justify-center sm:justify-start text-sm text-accent font-medium">

        {{-- Use x-nav-link components --}}
        <x-nav-link href="/bookingongoing" icon="fa-solid fa-calendar-check" label="Booking Ongoing" />
        <x-nav-link href="/booking" icon="fa-solid fa-calendar-plus" label="Booking" />
        {{-- Uncomment if needed --}}
        {{-- <x-nav-link href="/cforms" icon="fa-solid fa-file-medical" label="Medical History Update" /> --}}
        {{-- <x-nav-link href="/cprofile" icon="fa-solid fa-user" label="Profile" /> --}}
        <x-nav-link href="/patient/chat" icon="fa-solid fa-comments" label="Message" />

      </nav>

      <!-- Main Content -->
      <main class="flex-1 py-6 px-20 overflow-y-auto bg-background bg-gray-100">
        @yield('main-content')
      </main>
    </div>
  </div>

  <!-- JS -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const dropdownToggle = document.getElementById('dropdownToggle');
      const dropdownMenu = document.getElementById('dropdownMenu');
      const notificationToggle = document.getElementById('notificationToggle');
      const notificationDropdown = document.getElementById('notificationDropdown');
      let hasMarked = false;

      dropdownToggle.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
      });

      notificationToggle.addEventListener('click', () => {
        notificationDropdown.classList.toggle('hidden');
        if (!hasMarked && !notificationDropdown.classList.contains('hidden')) {
          fetch("{{ route('notifications.markAsRead') }}", {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": '{{ csrf_token() }}',
              "Content-Type": "application/json"
            },
          }).then(res => res.json()).then(() => {
            hasMarked = true;
            document.querySelectorAll('.fa-bell + span').forEach(el => el.remove());
          });
        }
      });

      window.addEventListener('click', function (e) {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
          dropdownMenu.classList.add('hidden');
        }
        if (!notificationToggle.contains(e.target) && !notificationDropdown.contains(e.target)) {
          notificationDropdown.classList.add('hidden');
        }
      });
    });
  </script>
</body>
</html>
