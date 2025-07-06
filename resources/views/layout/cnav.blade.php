<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>@yield('title', 'Navigation')</title>
    <style>
        /* Base style: Mobile first (icon on top) */
        .navlist {
            display: flex;
            flex-direction: row; /* Mobile layout for the menu list */
            padding: 0;
            margin: 0;
            list-style: none;
            justify-content:space-between;
        }
        
        .navlist li {
            padding: 16px;
            border-bottom: 1px solid #ccc;
            width: auto;
            flex: 1;
        }
        
        /* Anchor styles */
        .navlist a {
            display: flex;
            flex-direction: column; /* Icon on top (mobile) */
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: #02ccfe;
            font-size: 14px;
        }
        
        /* Desktop styles */
        @media (min-width: 768px) {
            .navlist {
                flex-direction: column; /* Still vertical list layout */
            }
        
            .navlist a {
                flex-direction: row;  /* Icon beside text (desktop) */
                align-items: center;
                justify-content: flex-start;
            }
        }
        </style>
</head>
<body >
    <header class="flex flex-row justify-between   bg-[#02ccfe] h-auto gap-10">
      
        <div class="flex flex-row h-auto p-3 gap-5 ">
            
            <img class="h-10 " src="{{ asset('images/logo.png') }}" alt="">
            
            <div class="flex flex-col">
                <h1 class="text-white font-black ">Santiago-Amancio</h1> 
                <h1 class="text-white font-black ">Dental Clinic</h1> 
            </div>
           
           
        </div>
  
        <div class="flex flex-row h-auto p-3 gap-5 ">
            
<!-- Notification Bell Icon -->
<div class="relative inline-block text-left m-2">
    <button id="notificationToggle" class="relative focus:outline-none">
        <i class="fa-solid fa-bell text-xl text-gray-600"></i>
        @if(Auth::user()->unreadNotifications->count())
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
        @endif
    </button>

    <!-- Dropdown -->
    <div id="notificationDropdown"
         class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 transition duration-200 ease-in-out">
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
        {{-- <div class="p-2 border-t text-center">
            <a href="{{ route('notifications.index') }}"
               class="text-xs text-blue-600 hover:underline">View all</a>
        </div> --}}
    </div>
</div>


           <div class="relative md:inline-block text-left">
            
            <div class="flex items-center gap-2 cursor-pointer" id="dropdownToggle">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-300 shadow-sm bg-white flex items-center justify-center">
                    @if(Auth::user()->profile_image)
                        <img 
                            src="{{ asset('DentaEase/public/storage/profile_pictures/' . Auth::user()->profile_image) }}" 
                            alt="Profile" 
                            class="w-full h-full object-cover"
                        >
                    @else
                        <i class="fa-solid fa-user text-gray-500 text-xl"></i>
                    @endif
                </div>
                <div class="text-sm text-gray-800 text-left">
                    <div class="font-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-blue-600">{{ Auth::user()->account_type }}</div>
                </div>
                <i class="fa-solid fa-caret-down text-sm text-gray-600"></i>
            </div>

            <ul id="dropdownMenu" class="absolute right-0 mt-3 w-44 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                <li>
                    <a href="/cprofile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-xl">
                        <i class="fa-regular fa-user mr-2"></i> Profile
                    </a>
                </li>
                {{-- <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa-solid fa-gear mr-2"></i> Settings
                    </a>
                </li> --}}
                <li>
                    <a href="/logouts" class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50 rounded-b-xl">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Sign out
                    </a>
                </li>
            </ul>
        </div>
           
        </div>
    </header>
    <div class="flex flex-col md:flex-row h-screen gap-3 ">
        <aside class=" bg-[#F5F5F5] bg-opacity-75  md:basis-1/5 md:h-full md:max-h-full border-r-1 border-indigo-200 ">
            <ul class="navlist mt-5 flex flex-row md:flex-col">
                
                {{-- <li class="p-4 border-b-1">
                    <span >
                        <a href="" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-house"></i> Dashboard</a>

                    </span>
                </li> --}}
                <li class="p-4 border-b-1">
                    <span >
                        <a href="/bookingongoing" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-house"></i> Booking Ongoing</a>

                    </span>
                </li>
                <li class="p-4 border-b-1">
                    <span >
                        <a href="/booking" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-house"></i> Booking</a>

                    </span>
                </li>

           
                
                <li class="p-4 border-b-1 md:hidden">
                    <span >
                        <a href="/cprofile" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-circle-user"></i>Profile</a>

                    </span>
                </li>
            </ul>
        </aside>
        <main class=" bg-[#F5F5F5] bg-opacity-75 basis-4/5 overflow-y-auto">
            <div class="content flex flex-col p-10 mt-10 gap-5 ">
                @yield('main-content')
            </div>
            
        </main>
    </div>
    
    <script>
        const toggleBtn = document.getElementById('dropdownToggle');
        const dropdown = document.getElementById('dropdownMenu');
    
        toggleBtn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });
    
        // Optional: Close when clicking outside
        window.addEventListener('click', function (e) {
            if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.getElementById('notificationToggle');
        const dropdown = document.getElementById('notificationDropdown');
        let hasMarked = false;

        toggle.addEventListener('click', function (e) {
            dropdown.classList.toggle('hidden');

            if (!hasMarked && !dropdown.classList.contains('hidden')) {
                // Mark notifications as read
                fetch("{{ route('notifications.markAsRead') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token() }}',
                        "Content-Type": "application/json",
                    },
                }).then(res => res.json()).then(data => {
                    hasMarked = true;
                    // Optionally: hide red dot after mark
                    document.querySelectorAll('.fa-bell + span').forEach(el => el.remove());
                });
            }
        });

        document.addEventListener('click', function (e) {
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>


</body>
</html>