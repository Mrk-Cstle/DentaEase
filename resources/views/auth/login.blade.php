@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col h-150  ">
                <form action="{{ route(name: 'loginform') }}" class="flex flex-col gap-5">
                    <div class="flex  justify-center" >
                        <h2>Login</h2>
                    </div>
                   
                    @csrf
                    <label>User</label>
                    <input type="text" name="user" class="border border-[#02ccfe] rounded-md p-2 bg-white">
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
                            <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('signupui') }}">Sign up</a> 
                        </p>
                    </div>
                   

                </form>
            </div>
            @endsection