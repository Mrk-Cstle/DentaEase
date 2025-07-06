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
        
    </style>
</head>
<body >
    <div class="flex flex-col h-screen">
        <header class="flex flex-row justify-between   bg-[#02ccfe] h-auto gap-10">
      
            <div class="flex flex-row h-auto p-3 gap-5 ">
                
                <img class="h-10 " src="{{ asset('images/logo.png') }}" alt="">
                <div class="flex flex-col">
                    <h1 class="text-white font-black ">Santiago-Amancio</h1> 
                    <h1 class="text-white font-black ">Dental Clinic</h1> 
                </div>
                <div  class="flex flex-col mx-5">

                    @php
                        $branch = \App\Models\Store::find(session('active_branch_id'));
                    @endphp
    
                    @if ($branch)
                        <p class="text-lg font-semibold text-white font-black">{{ $branch->name }}</p>
                        <p class="text-sm font-semibold text-white font-black">{{ $branch->address }}</p>
                    @else
                    
                        <p class="text-red-500">Admin View</p>
                    @endif
                </div>
               
            </div>
           
            <div class="flex flex-row h-auto p-3 gap-5 ">
                
                <div class="relative md:inline-block text-left">
                <div class="flex items-center gap-2 cursor-pointer" id="dropdownToggle">
                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-300 shadow-sm bg-white flex items-center justify-center">
                        @if(Auth::user()->profile_image)
                            <img 
                                src="{{ asset('storage/profile_pictures/' . Auth::user()->profile_image) }}" 
                                alt="Profile" 
                                class="w-full h-full object-cover"
                            >
                        @else
                            <i class="fa-solid fa-user text-gray-500 text-xl"></i>
                        @endif
                    </div>
                    <div class="text-sm text-gray-800 text-left">
                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-blue-600">{{ Auth::user()->position }}</div>
                    </div>
                    <i class="fa-solid fa-caret-down text-sm text-gray-600"></i>
                </div>

                <ul id="dropdownMenu" class="absolute right-0 mt-3 w-44 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                    <li>
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-xl">
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
        <div class="flex flex-row flex-1 gap-3 h-full ">
            <aside class=" bg-[#F5F5F5] bg-opacity-75  basis-1/5 h-full max-h-full border-r-1 border-indigo-200 ">
                <ul class="mt-5 ">
                    

                    @if (auth()->user()->position == 'admin')
                        <select id="branchSelector" class="border p-1 rounded">
                        <option value="">-- Select Branch --</option>
                    </select>
                    @endif
                    

                    <li class="p-4 border-b-1">
                        <span >
                            <a href="/dashboard" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Dashboard</a>
    
                        </span>
                    </li>
                    @if (session('active_branch_id') == "admin")
                    <li class="p-4 border-b-1">
                        <span >
                            <a href="/useraccount" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Staff Accounts</a>
    
                        </span>
                    </li>
                    @endif
                     <li class="p-4 border-b-1">
                        <span >
                            <a href="/patientaccount" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Patient Accounts</a>
    
                        </span>
                    </li>
                    <li class="p-4 border-b-1">
                        <span >
                            <a href="/services" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Services</a>
    
                        </span>
                    </li>
                    {{-- <li class="p-4 border-b-1 ">
                        <span >
                            <a href="/userverify" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> New User</a>
    
                        </span>
                    </li> --}}
                    <li class="p-4 border-b-1 ">
                        <span >
                            <a href="/branch" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Branch</a>
    
                        </span>
                    </li>
                    <li class="p-4 border-b-1 ">
                        <span >
                            <a href="/logs" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Logs</a>
    
                        </span>
                    </li>

                   @if (session('active_branch_id') != "admin")
                         <li class="p-4 border-b-1 ">
                        <span >
                            <a href="/appointments" class="text-[#02ccfe] text">
                            <i class="fa-solid fa-house"></i> Appointments</a>
    
                        </span>
                    </li>
                    @endif
                   
                </ul>
            </aside>
            <main class=" bg-[#F5F5F5] bg-opacity-75 basis-4/5 overflow-y-auto">
                <div class="content h-full flex-1 flex flex-col p-10 gap-5 ">
                    @yield('main-content')
                </div>
                
            </main>
        </div>
        
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fetch branches on page load
    $.get('/get-branches', function(data) {
        let selector = $('#branchSelector');
        selector.empty().append('<option value="">-- Select Branch --</option>');
        
        data.forEach(branch => {
            let selected = branch.id == '{{ session('active_branch_id') }}' ? 'selected' : '';
            selector.append(`<option value="${branch.id}" ${selected}>${branch.name}</option>`);
        });
    });

    // On change: update session and reload
    $('#branchSelector').on('change', function() {
        const branchId = $(this).val();

        if (branchId) {
            $.post('/set-active-branch', {
                id: branchId,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.status === 'success') {
                    location.reload(); // Refresh whole page
                }
            });
        }
    });
});
</script>
</body>
</html>