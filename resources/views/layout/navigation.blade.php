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
        <header class="bg-primary px-6 py-4 shadow-lg flex justify-between items-center">
            <div class="flex items-center space-x-4">
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
                            <img src="{{ asset('DentaEase/public/storage/profile_pictures/' . Auth::user()->profile_image) }}" class="object-cover w-full h-full">
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
            <aside class="bg-secondary w-full sm:w-64 border-r min-h-full px-4 py-6 shadow-md">
                <nav class="flex flex-col space-y-2 text-sm text-accent font-medium">
                    @if (auth()->user()->position == 'admin')
                        <select id="branchSelector" class="mb-4 border border-gray-300 rounded px-2 py-1 w-full text-sm">
                            <option value="">-- Select Branch --</option>
                        </select>
                    @endif

                    <a href="/dashboard" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-house"></i> <span>Dashboard</span>
                    </a>

                    @if (session('active_branch_id') == "admin")
                    <a href="/useraccount" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-users"></i> <span>Staff Accounts</span>
                    </a>
                    @endif

                    <a href="/patientaccount" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-user-injured"></i> <span>Patient Accounts</span>
                    </a>

                    <a href="/inventory" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-user-injured"></i> <span>Inventory Management</span>
                    </a>
                    @if (auth()->user()->position != 'Receptionist')
                    <a href="/services" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-tooth"></i> <span>Services</span>
                    </a>
                    <a href="/branch" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-code-branch"></i> <span>Branch</span>
                    </a>
                    @endif

                    @if (session('active_branch_id') != "admin")
                    <a href="/appointments" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-calendar-check"></i> <span>Appointments</span>
                    </a>

                    <a href="/logs" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-file-lines"></i> <span>Logs</span>
                    </a>
                    @endif

                      {{-- <a href="/try" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-navItem hover:text-white">
                        <i class="fa-solid fa-calendar-check"></i> <span>try</span>
                    </a> --}}
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 bg-background overflow-y-auto">
                @yield('main-content')
            </main>
        </div>
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
            $.get('/get-branches', function (data) {
                let selector = $('#branchSelector');
                selector.empty().append('<option value="">-- Select Branch --</option>');

                data.forEach(branch => {
                    let selected = branch.id == '{{ session('active_branch_id') }}' ? 'selected' : '';
                    selector.append(`<option value="${branch.id}" ${selected}>${branch.name}</option>`);
                });
            });

            $('#branchSelector').on('change', function () {
                const branchId = $(this).val();
                if (branchId) {
                    $.post('/set-active-branch', {
                        id: branchId,
                        _token: '{{ csrf_token() }}'
                    }, function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
