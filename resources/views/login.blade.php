<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <title>Login</title>
</head>
<body >
  
        <header class="flex flex-row justify-center p-12 w-screen bg-[#02ccfe] h-auto gap-10" >
            <div id="logo" >
                <img class="h-16" src="{{ asset('images/logo.png') }}" alt="">
            </div>
            <div id="text">
               <h1 class="p-5 text-white font-black text-3xl">Santiago-Amancio Dental Clinic</h1> 
            </div>
        </header>
    
        <div class="flex m-5 justify-center  min-w-1/2">
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-5 py-5 rounded-md flex flex-col">
                <form action="" class="flex flex-col gap-5">
                    <div class="flex  justify-center" >
                        <h2>Login</h2>
                    </div>
                   
                   
                    <label>E-mail</label>
                    <input type="email" name="email" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <label>Password</label>
                    <input type="password" name="password" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <div class="flex justify-end">
                        <button type="submit" class=" bg-[#02ccfe] text-white rounded-md px-3 py-2">Login</button>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 mt-3">
                        <p class="text-sm">
                            Login using 
                            <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">Face Recognition</a> 
                            or 
                            <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">QR</a>
                        </p>
        
                        <p class="text-sm">
                            Don't have an account?  
                            <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">Sign up</a> 
                        </p>
                    </div>
                   

                </form>
            </div>
        </div>
    
   
</body>
</html>