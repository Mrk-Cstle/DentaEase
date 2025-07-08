@extends('layout.auth')

@section('title', 'Login via QR')

@section('auth-content')

    <div class="text-center mb-4">
        <h2 class="text-2xl font-bold text-sky-600">Login using QR Code</h2>
    </div>

    <!-- QR Scanner Container -->
    <div id="qr-reader" class="rounded-md border border-gray-300 p-4"></div>

    <!-- Alternative login links -->
    <div class="text-center mt-6 space-y-2 text-sm text-gray-700">
        <p>
            Login using 
            <a href="{{ route('loginui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Login</a>
            or
            <a href="{{ route('faceui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Face Recognition</a>
        </p>
        <p>
            Don't have an account? 
            <a href="{{ route('signupui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Sign up</a>
        </p>
    </div>


<!-- Scripts -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function onScanSuccess(decodedText, decodedResult) {
    // Send token to backend
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
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'QR login successful.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: data.message,
            });
        }
    })
    .catch(err => {
        console.error('QR login error:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.',
        });
    });
}

new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 })
    .render(onScanSuccess);
</script>
@endsection
