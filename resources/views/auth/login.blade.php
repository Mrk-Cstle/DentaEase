@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')
<div class="p-[50px] bg-sky-100">

 <form id="loginForm" method="post" class="flex flex-col gap-5">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-sky-600">Login</h2>
        </div>

        @csrf

        <div>
            <label class="text-gray-700 text-sm font-medium">Username</label>
            <input type="text" name="user" class="mt-1 w-full border border-sky-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-sky-400 bg-white">
        </div>

        <div>
            <label class="text-gray-700 text-sm font-medium">Password</label>
            <div class="relative">
              <input 
                type="password" 
                name="password" 
                id="passwordInput"
                class="mt-1 w-full border border-sky-300 rounded-md p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-sky-400 bg-white"
              >
          
              <!-- Toggle button -->
              <button 
                type="button" 
                onclick="togglePassword()" 
                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600"
              >
                Show
              </button>
            </div>
          </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white font-medium rounded-md px-4 py-2 transition duration-150">
                Login
            </button>
        </div>

        <div class="text-center mt-4 space-y-2 text-sm text-gray-700">
            <p>
                Login using 
                <a href="{{ route('faceui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Face Recognition</a> 
                or 
                <a href="{{ route('Qr') }}" class="text-blue-500 hover:text-blue-700 underline transition">QR</a>
            </p>
            <p>
                Don’t have an account? 
                <a href="{{ route('signupui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Sign up</a>
            </p>
        </div>
    </form>

</div>
   

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function togglePassword() {
      const input = document.getElementById('passwordInput');
      const btn = event.currentTarget;
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Hide';
      } else {
        input.type = 'password';
        btn.textContent = 'Show';
      }
    }
    </script>
<script>
$(document).ready(function () {
    $('#loginForm').submit(function (event) {
        event.preventDefault();

        var formData = {
            user: $('input[name="user"]').val(),
            password: $('input[name="password"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            type: 'POST',
            url: '{{ route('loginform') }}',
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endsection
