@extends('layout.auth')

@section('title', 'Signup')

@section('auth-content')
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col ">
                <form id="signupForm" method="post"  class="flex flex-col gap-5">
                    <div class="flex  justify-center" >
                        <h2>Sign Up</h2>
                    </div>
                   
                    @csrf

                  <div class="flex flex-row gap-10 w-full mt-5">
                    <div class="flex flex-col gap-5 flex-1">
                        <label>Name:</label>
                        <input type="text" name="name" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    </div>
                    
                  </div>
                        
                       
              
                   
                    <label>Email:</label>
                    <input type="email" name="email" class="border border-[#02ccfe] rounded-md p-2 bg-white">
                    <label>Contact Number:</label>
                    <input type="number" name="contact_number" class="border border-[#02ccfe] rounded-md p-2 bg-white">
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
             <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
             <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

             <script>
             $(document).ready(function(event){
                $('#signupForm').submit(function (event) {
                    event.preventDefault();

                    var account_type = 'Admin'
                    var formData = {
                        _token: $('input[name="_token"]').val(),
                        name : $('input[name="name"]').val(),
                       
                        email : $('input[name="email"]').val(),
                        contact_number : $('input[name="contact_number"]').val(),
                        user : $('input[name="user"]').val(),
                        password : $('input[name="password"]').val(),
                        account_type: account_type

                    }

                    $.ajax({
                        type: 'post',
                        url: '{{route('signupform')}}',

                        data: formData,
                       
                        success: function (response) {
                            if (response.status === "success") {
                                Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
           
                           
                                
                                window.location.href = '/loginui';
                         
                        });
                            }else{
                                Swal.fire('Error', response.message);
                            }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText); // helpful info from Laravel
                                Swal.fire('Error', 'Something went wrong on the server.', 'error');
                            }
                         });
                })
             })
             </script>
            @endsection