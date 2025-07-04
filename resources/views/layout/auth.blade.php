<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <title>@yield('title', 'Auth Page')</title>
 
</head>

{{-- <body class="bg-cover bg-center relative " style="background-image: url('{{ asset('images/bg.jpg') }}' );  overflow-x: hidden; "> --}}
<body>
  
        <header class="flex flex-row justify-center p-3 w-screen bg-[#02ccfe] h-auto gap-10" >
            <div id="logo" >
                <img class="h-16" src="{{ asset('images/logo.png') }}" alt="">
            </div>
            <div id="text">
               <h1 class="p-5 text-white font-black text-2xl">Santiago-Amancio Dental Clinic</h1> 
            </div>
        </header>
    
      <div class="min-h-screen flex items-center justify-center bg-gray-50">
            @yield('auth-content')
        </div>
    
   
</body>
</html>