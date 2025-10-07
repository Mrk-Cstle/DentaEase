@extends('layout.auth')
@section('title', 'Face Recognition')

@section('auth-content')
<div class="p-[50px] bg-sky-100">
    <form id="loginForm" method="post" class="flex flex-col gap-5">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-sky-600">Face Recognition Login</h2>
        </div>

        @csrf

        <div>
            <label class="text-gray-700 text-sm font-medium">Username</label>
            <input type="text" name="user" class="mt-1 w-full border border-sky-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-sky-400 bg-white">
        </div>

        <div class="flex justify-end">
            <button type="button" onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white font-medium rounded-md px-4 py-2 transition duration-150">
                Face Login
            </button>
        </div>

        <div class="text-center mt-4 space-y-2 text-sm text-gray-700">
            <p>
                Login using 
                <a href="{{ route('loginui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Login</a> 
                or 
                <a href="{{ route('Qr') }}" class="text-blue-500 hover:text-blue-700 underline transition">QR</a>
            </p>
            <p>
                Don’t have an account? 
                <a href="{{ route('signupui') }}" class="text-blue-500 hover:text-blue-700 underline transition">Sign up</a>
            </p>
        </div>

        <!-- Face Login Modal -->
        <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Face Login</h3>
                <video id="video" width="320" height="240" autoplay class="border border-gray-300 rounded-md"></video>
                <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
                <div class="flex items-center justify-between mt-4">
                    <span id="countdownText" class="text-sm text-gray-600">Logging in in 3 seconds...</span>
                    <button onclick="closeModal()" type="button" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-2 rounded-md">Close</button>
                </div>
            </div>
        </div>

        <!-- Loader -->
        <div id="loadingSpinner" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[9999]">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-sky-500"></div>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let countdownInterval = null;
    let hasCancelled = false;

    function openModal() {
        document.getElementById('videoModal').classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                document.getElementById('video').srcObject = stream;
            })
            .catch(function(err) {
                console.error("Error accessing webcam: " + err);
            });

        if (countdownInterval) clearInterval(countdownInterval);
        hasCancelled = false;

        let countdown = 5;
        const countdownText = document.getElementById('countdownText');
        countdownText.textContent = `Logging in in ${countdown} seconds...`;

        countdownInterval = setInterval(() => {
            if (hasCancelled) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                return;
            }

            countdown--;
            countdownText.textContent = `Logging in in ${countdown} seconds...`;

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                if (!hasCancelled) {
                    $('#loginForm').submit();
                }
            }
        }, 1000);
    }

    function closeModal() {
        document.getElementById('videoModal').classList.add('hidden');
        const video = document.getElementById('video');
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
        hasCancelled = true;
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        document.getElementById('countdownText').textContent = '';
        window.location.href = "/faceui";
    }

    $(document).ready(function () {
        $('#loginForm').submit(function (event) {
            event.preventDefault();

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/jpeg');

            const formData = {
                user: $('input[name="user"]').val(),
                image_base64: dataURL,
                _token: '{{ csrf_token() }}'
            };

            document.getElementById('loadingSpinner').classList.remove('hidden');

            $.ajax({
                type: 'POST',
                url: '{{ route('login-face') }}',
                data: formData,
                success: function (response) {
                    document.getElementById('loadingSpinner').classList.add('hidden');
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
                        document.getElementById('videoModal').classList.add('hidden');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    document.getElementById('loadingSpinner').classList.add('hidden');
                    document.getElementById('videoModal').classList.add('hidden');
                }
            });
        });
    });
</script>
@endsection
