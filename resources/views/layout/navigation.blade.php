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
                
                <i class="text-4xl fa-solid fa-circle-user"></i>
                <div class="">
                    
                    <h2 class="text-black text-sm " > {{ Auth::user()->name}}</h2>
                    <div class="flex flex-row justify-between">
                        <h2 class="text-black text-xs" >{{ Auth::user()->account_type }}</h2>
                        <button id="dropdownToggle" class="text-black text-xs focus:outline-none">
                            <i class="fa-solid fa-caret-down"></i>
                        </button>
                    </div>
                    
    
                    <ul id="dropdownMenu" class="absolute mt-2 right-0 bg-white border rounded shadow-lg text-sm hidden z-50">
                        <li><a href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
                        <li><a href="/logouts" class="block px-4 py-2 hover:bg-gray-100 text-red-500">Sign out</a></li>
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