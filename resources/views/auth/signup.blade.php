@extends('layout.auth')

@section('title', 'Signup')

@section('auth-content')
           <div class="bg-[#F5F5F5] bg-opacity-75 w-full max-w-3xl mx-auto px-10 py-10 rounded-md flex flex-col ">
    <form id="signupForm" method="post" enctype="multipart/form-data" class="flex flex-col gap-5">
    <div class="flex justify-center">
        <h2 class="text-xl font-semibold">Sign Up</h2>
    </div>

    @csrf

    <!-- Row: Name Fields -->
    <div class="flex flex-wrap gap-4">
        <div class="flex flex-col flex-1 min-w-[150px]">
            <label>First Name:</label>
            <input type="text" name="name" class="border border-[#02ccfe] rounded-md p-2 bg-white">
        </div>
        <div class="flex flex-col flex-1 min-w-[150px]">
            <label>Middle Name:</label>
            <input type="text" name="middlename" class="border border-[#02ccfe] rounded-md p-2 bg-white">
        </div>
        <div class="flex flex-col flex-1 min-w-[150px]">
            <label>Last Name:</label>
            <input type="text" name="lastname" class="border border-[#02ccfe] rounded-md p-2 bg-white">
        </div>
        <div class="flex flex-col w-[80px]">
            <label>Suffix</label>
            <input type="text" name="suffix" class="border border-[#02ccfe] rounded-md p-2 bg-white text-sm">
        </div>
    </div>

    <!-- Row: Birth -->
    <div class="flex flex-row gap-5">
        <div class="flex flex-col flex-1">
            <label>Birthdate:</label>
            <input type="date" name="birth_date" class="border border-[#02ccfe] rounded-md p-2 bg-white">
        </div>
        <div class="flex flex-col flex-1">
            <label>Birthplace:</label>
            <input type="text" name="birthplace" class="border border-[#02ccfe] rounded-md p-2 bg-white">
        </div>
    </div>

    <!-- Address -->
    <div class="flex flex-col">
        <label>Current Address:</label>
        <input type="text" name="current_address" class="border border-[#02ccfe] rounded-md p-2 bg-white">
    </div>

    <!-- Verification ID Upload -->
    <div class="flex flex-col">
        <label>Upload Valid ID (for Verification):</label>
        <input type="file" name="verification_id" accept="image/*" class="border border-[#02ccfe] rounded-md p-2 bg-white">
    </div>

    <!-- Account Info -->
    <label>Email:</label>
    <input type="email" name="email" class="border border-[#02ccfe] rounded-md p-2 bg-white">

    <label>Contact Number:</label>
    <input type="number" name="contact_number" class="border border-[#02ccfe] rounded-md p-2 bg-white">

    <label>User:</label>
    <input type="text" name="user" class="border border-[#02ccfe] rounded-md p-2 bg-white">

   <div class="flex flex-col relative">
    <label>Password:</label>
    <input type="password" id="password" name="password" class="border border-[#02ccfe] rounded-md p-2 bg-white pr-10">
    
    <!-- Toggle checkbox or icon -->
    <div class="absolute right-2 top-[38px]">
        <input type="checkbox" id="showPassword">
        <label for="showPassword" class="text-sm text-gray-600">Show</label>
    </div>
</div>

    <!-- Submit -->
    <div class="flex justify-end">
        <button type="submit" class="bg-[#02ccfe] text-white rounded-md px-4 py-2 hover:bg-[#00bcd4]">Register</button>
    </div>
</form>

    <!-- Additional Login Options -->
    <div class="flex flex-col items-center text-center gap-2 mt-3">
        <p class="text-sm">
            Login using 
            <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('faceui') }}">Face Recognition</a> 
            or 
            <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">QR</a>
        </p>

        <p class="text-sm">
            Already have an account?  
            <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('loginui') }}">Login</a> 
        </p>
    </div>
</div>

             <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
             <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

             <script>
                document.getElementById('showPassword').addEventListener('change', function() {
                    const passwordInput = document.getElementById('password');
                    if (this.checked) {
                        passwordInput.type = 'text';
                    } else {
                        passwordInput.type = 'password';
                    }
                });
             $(document).ready(function(event){
                     $('#signupForm').on('submit', function (e) {
                    e.preventDefault();

                    const form = document.getElementById('signupForm');
                    const formData = new FormData(form); // handles all fields, including file

                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('account_type', 'patient');

                    $.ajax({
                        url: '{{ route("send.otp") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
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
                            const response = xhr.responseJSON;

                        // Option 1: Display the top-level message directly
                        Swal.fire('Validation Error', response.message, 'error');
                        }
                    });
                });

             })
             </script>
            @endsection