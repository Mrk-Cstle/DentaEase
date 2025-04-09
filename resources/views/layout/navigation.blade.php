<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>@yield('title', 'Navigation')</title>
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
            
            <i class="text-4xl fa-solid fa-circle-user"></i>
            <div class="">
                <h2 class="text-black text-sm " >First Name, Last Name</h2>
                <h2 class="text-black text-xs" >Admin</h2>
            </div>
           
        </div>
    </header>
    <div class="flex flex-row h-screen gap-3 ">
        <aside class=" bg-[#F5F5F5] bg-opacity-75  basis-1/5 h-full max-h-full border-r-1 border-indigo-200 ">
            <ul class="mt-5 ">
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
                <li class="p-4 border-b-1">
                    <span >
                        <a href="" class="text-[#02ccfe] text">
                        <i class="fa-solid fa-house"></i> Dashboard</a>

                    </span>
                </li>
            </ul>
        </aside>
        <main class=" bg-[#F5F5F5] bg-opacity-75 basis-4/5 ">
            @yield('main-content')
        </main>
    </div>
    

</body>
</html>