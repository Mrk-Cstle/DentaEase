@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col h-150  ">
               
                    <div class="flex  justify-center" >
                        <h2>Login using Qr</h2>
                    </div>
                   <div id="qr-reader" class="mt-4"></div>

                  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
                  <div class="flex flex-col items-center text-center gap-2 mt-3">
            <p class="text-sm">
                Login using 
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('loginui') }}">Login</a> 
                or 
               <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('faceui') }}">Face Recognition</a> 
            </p>

            <p class="text-sm">
                Don't have an account?  
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('signupui') }}">Sign up</a> 
            </p>
        </div>
                   

          
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
               function onScanSuccess(decodedText, decodedResult) {
                // Send the token to the backend
                fetch("/qr-login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ token: decodedText })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message === 'Logged in successfully.') {
                          window.location.href = data.redirect;
                    } else {
                        alert('Login failed: ' + data.message);
                    }
                });
            }

            new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 })
                .render(onScanSuccess);
            
            </script>
            @endsection