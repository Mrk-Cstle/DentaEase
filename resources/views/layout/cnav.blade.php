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
            <div class="hidden md:block">
                <i class="text-4xl fa-solid fa-circle-user"></i>
            </div>
            
            <div class="md:inline hidden">
                
                <h2 class="text-black text-sm " >{{Auth::user()->name}}</h2>
                <div class="flex flex-row justify-between ">
                    <h2 class="text-black text-xs " >{{Auth::user()->account_type}}</h2>
                    <button id="dropdownToggle" class="text-black text-xs focus:outline-none ">
                        <i class="fa-solid fa-caret-down"></i>
                    </button>
                </div>
                
               
                
                <ul id="dropdownMenu" class="absolute mt-2 right-0 bg-white border rounded shadow-lg text-sm hidden z-50">
                    <li><a href="/cprofile" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
                    <li><a href="/logout" class="block px-4 py-2 hover:bg-gray-100 text-red-500">Sign out</a></li>
                </ul>
                
            </div>
           
        </div>
    </header>
    <div class="flex flex-col md:flex-row h-screen gap-3 ">
        <aside class=" bg-[#F5F5F5] bg-opacity-75  md:basis-1/5 md:h-full md:max-h-full border-r-1 border-indigo-200 ">
            <ul class="navlist mt-5 flex flex-row md:flex-col">
                
                <li class="p-4 border-b-1">
                    <span >
                        <a href="" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-house"></i> Dashboard</a>

                    </span>
                </li>
                <li class="p-4 border-b-1">
                    <span >
                        <a href="" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-house"></i> Dashboard</a>

                    </span>
                </li>
                
                <li class="p-4 border-b-1 md:hidden">
                    <span >
                        <a href="/userverify" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-circle-user"></i>Profile</a>

                    </span>
                </li>
            </ul>
        </aside>
        <main class=" bg-[#F5F5F5] bg-opacity-75 basis-4/5 ">
            <div class="content flex flex-col p-10 mt-10 gap-5">
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

</body>
</html>