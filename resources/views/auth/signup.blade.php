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
                        <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('faceui') }}">Face Recognition</a> 
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
      $('#signupForm').on('submit', function(e) {
    e.preventDefault();

    const formData = {
        name: $('input[name="name"]').val(),
        email: $('input[name="email"]').val(),
        password: $('input[name="password"]').val(),
        contact_number: $('input[name="contact_number"]').val(),
        user: $('input[name="user"]').val(),
        account_type : "patient",
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route("send.otp") }}', // ✅ NOT "signupui"
        method: 'get',
        data: formData,
        success: function(response) {
            Swal.fire({
                title: 'Enter OTP',
                input: 'text',
                inputLabel: 'We sent an OTP to your email',
                inputPlaceholder: 'Enter OTP here',
                showCancelButton: true,
                confirmButtonText: 'Verify'
            }).then(result => {
                if (result.isConfirmed) {
                    $.get('{{ route("verify.otp") }}', {
                        ...formData,
                        otp: result.value,
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        Swal.fire('Success!', res.message, 'success');
                        $('#signupForm')[0].reset();
                    }).fail(() => {
                        Swal.fire('Error!', 'OTP verification failed.', 'error');
                    });
                }
            });
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Could not send OTP.', 'error');
        }
    });
});

             })
             </script>
            @endsection