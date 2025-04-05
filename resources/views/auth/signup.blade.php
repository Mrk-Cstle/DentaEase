@extends('layout.auth')

@section('title', 'Signup')

@section('auth-content')
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col ">
                <form action="{{ route(name: 'signupform') }}" class="flex flex-col gap-5">
                    <div class="flex  justify-center" >
                        <h2>Sign Up</h2>
                    </div>
                   
                    @csrf

                  <div class="flex flex-row gap-10 w-full mt-5">
                    <div class="flex flex-col gap-5 flex-1">
                        <label>First Name:</label>
                        <input type="text" name="firstname" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    </div>
                    <div class="flex flex-col gap-5 flex-1">
                        <label>Last Name:</label>
                        <input type="text" name="lastname" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                     
                       
                    </div>
                  </div>
                        
                       
              
                   
                    <label>Email:</label>
                    <input type="email" name="email" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <label>Contact Number:</label>
                    <input type="number" name="number" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <label>User:</label>
                    <input type="text" name="user" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <label>Password</label>
                    <input type="password" name="password" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <div class="flex justify-end">
                        <button type="submit" class=" bg-[#02ccfe] text-white rounded-md px-3 py-2">Login</button>
                    </div>
                    
                   

                </form>
                <div class="flex flex-col items-center text-center gap-2 mt-3">
                    <p class="text-sm">
                        Login using 
                        <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">Face Recognition</a> 
                        or 
                        <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">QR</a>
                    </p>
    
                    <p class="text-sm">
                        Don't have an account?  
                        <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('loginui') }}">Login</a> 
                    </p>
                </div>
            </div>
            @endsection