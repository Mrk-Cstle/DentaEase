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

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0284c7', // same as login top nav
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
        }
    </script>
</head>
<body class="bg-background font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
       <!-- Header -->
<header class="bg-primary px-6 py-4 shadow-lg flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <button id="toggleSidebar" class="text-white text-2xl sm:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>

        <img class="h-10" src="{{ asset('images/logo.png') }}" alt="Logo">
        <h1 class="text-white font-bold text-xl">Santiago-Amancio Dental Clinic</h1>

        @php
            $branch = \App\Models\Store::find(session('active_branch_id'));
        @endphp

        <div class="ml-6 text-white hidden sm:block">
            @if ($branch)
                <div class="font-medium text-base">{{ $branch->name }}</div>
                <div class="text-sm">{{ $branch->address }}</div>
            @else
                <div class="text-red-100">Admin View</div>
            @endif
        </div>
    </div>

    <!-- User Dropdown -->
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
                <div class="text-xs">{{ Auth::user()->position }}</div>
            </div>
            <i class="fa-solid fa-caret-down text-sm ml-1"></i>
        </div>
        <ul id="dropdownMenu" class="absolute right-0 mt-2 w-44 bg-white border rounded-md shadow-md hidden z-50">
            <li><a href="/profile" class="block px-4 py-2 hover:bg-gray-100 text-sm"><i class="fa-regular fa-user mr-2"></i>Profile</a></li>
            <li><a href="/logouts" class="block px-4 py-2 text-red-500 hover:bg-red-100 text-sm"><i class="fa-solid fa-right-from-bracket mr-2"></i>Logout</a></li>
        </ul>
    </div>
</header>

<div class="flex flex-1">
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-secondary sm:w-64 w-56 fixed sm:relative z-40 min-h-full px-4 py-6 shadow-md transform transition-transform duration-300 -translate-x-full sm:translate-x-0">
        <nav class="flex flex-col space-y-1 text-sm text-accent font-medium">
            @if (auth()->user()->position == 'admin')
                <select id="branchSelector" class="mb-4 border border-gray-300 rounded px-2 py-1 w-full text-sm">
                    <option value="">-- Select Branch --</option>
                </select>
            @else
                <select id="assignedBranchSelector" class="mb-4 border border-gray-300 rounded px-2 py-1 w-full text-sm">
                    <option value="">-- Select Branch --</option>
                </select>
            @endif

            <!-- Nav links -->
            <x-nav-link href="/dashboard" icon="fa-solid fa-gauge" label="Dashboard" />

            @if (session('active_branch_id') == "admin")
                <x-nav-link href="/useraccount" icon="fa-solid fa-user-gear" label="Staff Accounts" />
            @endif

            <x-nav-link href="/patientaccount" icon="fa-solid fa-hospital-user" label="Patient Accounts" />
            <x-nav-link href="/inventory" icon="fa-solid fa-boxes-stacked" label="Inventory Management" />

            @if (auth()->user()->position != 'Receptionist')
                <x-nav-link href="/services" icon="fa-solid fa-tooth" label="Services" />
                <x-nav-link href="/branch" icon="fa-solid fa-code-branch" label="Branch" />
            @endif

            @if (session('active_branch_id') != "admin")
                <x-nav-link href="/appointments" icon="fa-solid fa-calendar-check" label="Appointments" />
                <x-nav-link href="/logs" icon="fa-solid fa-list-check" label="Logs" />
                <x-nav-link href="/pos/{{ session('active_branch_id') }}" icon="fa-solid fa-cash-register" label="POS" />
                <x-nav-link href="/reports/sales" icon="fa-solid fa-chart-line" label="Reports" />
                <x-nav-link href="/chat" icon="fa-solid fa-comments" label="Message" />
            @endif
        </nav>
    </aside>

    <!-- Overlay (for mobile) -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden sm:hidden"></div>

    <!-- Main Content -->
    <main class="flex-1 p-6 bg-background overflow-y-auto sm:ml-0">
        @yield('main-content')
    </main>
</div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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

        $(document).ready(function () {
    // For Admin
    $.get('/get-branches', function (data) {
        let selector = $('#branchSelector');
        if (selector.length) { // only run if admin selector exists
            selector.empty().append('<option value="">-- Select Branch --</option>');

            data.forEach(branch => {
                let selected = branch.id == '{{ session('active_branch_id') }}' ? 'selected' : '';
                selector.append(`<option value="${branch.id}" ${selected}>${branch.name}</option>`);
            });

            selector.on('change', function () {
                const branchId = $(this).val();
                if (branchId) {
                    $.post('/set-active-branch', {
                        id: branchId,
                        _token: '{{ csrf_token() }}'
                    }, function (response) {
                        if (response.status === 'success') {
                            //location.reload();
                             window.location.href = '/dashboard';
                        }
                    });
                }
            });
        }
    });

    // For Dentist / Receptionist
    $.get('/get-assigned-branches', function (data) {
        let selector = $('#assignedBranchSelector');
        if (selector.length) { // only run if dentist/receptionist selector exists
            selector.empty().append('<option value="">-- Select Branch --</option>');

            data.forEach(branch => {
                let selected = branch.id == '{{ session('active_branch_id') }}' ? 'selected' : '';
                selector.append(`<option value="${branch.id}" ${selected}>${branch.name}</option>`);
            });

            selector.on('change', function () {
                const branchId = $(this).val();
                if (branchId) {
                    $.post('/set-active-branch', {
                        id: branchId,
                        _token: '{{ csrf_token() }}'
                    }, function (response) {
                        if (response.status === 'success') {
                            // location.reload();
                             window.location.href = '/dashboard';
                        }
                    });
                }
            });
        }
    });
});

    </script>
    <script>
$(function() {
    const sidebar = $('#sidebar');
    const overlay = $('#overlay');

    $('#toggleSidebar').on('click', function() {
        sidebar.toggleClass('-translate-x-full');
        overlay.toggleClass('hidden');
    });

    overlay.on('click', function() {
        sidebar.addClass('-translate-x-full');
        overlay.addClass('hidden');
    });

    // User dropdown
    const toggleBtn = $('#dropdownToggle');
    const dropdown = $('#dropdownMenu');

    toggleBtn.on('click', () => dropdown.toggleClass('hidden'));

    $(window).on('click', function (e) {
        if (!toggleBtn[0].contains(e.target) && !dropdown[0].contains(e.target)) {
            dropdown.addClass('hidden');
        }
    });
});
</script>

</body>
</html>
