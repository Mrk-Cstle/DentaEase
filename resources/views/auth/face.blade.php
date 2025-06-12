@extends('layout.auth')
@section('title','Face Recognition')
@section('auth-content')
    
<div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col h-150  ">
    <form id="loginForm" class="flex flex-col gap-5" method="post">
        <div class="flex  justify-center" >
            <h2>Login</h2>
        </div>

       
        @csrf
        <label>User</label>
        <input type="text" name="user" class="border border-[#02ccfe] rounded-md p-2 bg-white">
        
        {{-- <video id="video" width="320" height="240" autoplay></video>
        <canvas id="canvas" width="320" height="240" style="display:none;"></canvas> --}}
        <div class="flex justify-end">
            <button type="button" onclick="openModal()" class="bg-green-500 text-white px-3 py-2 rounded-md">Face Login</button>
            
        </div>
        <div class="flex flex-col items-center text-center gap-2 mt-3">
            <p class="text-sm">
                Login using 
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('loginui') }}">Login</a> 
                or 
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="#">QR</a>
            </p>

            <p class="text-sm">
                Don't have an account?  
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('signupui') }}">Sign up</a> 
            </p>
        </div>
        {{-- modal --}}
        <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-4 rounded-md shadow-md w-fit">
                <h3 class="text-lg font-semibold mb-2">Face Login</h3>
                <video id="video" width="320" height="240" autoplay class="border border-gray-300"></video>
                <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                <div class="flex justify-end gap-2 mt-3">
                    {{-- <button type="submit" class=" bg-[#02ccfe] text-white rounded-md px-3 py-2">Login</button> --}}
                    <span id="countdownText" class="text-sm text-gray-700 mr-auto">Logging in in 3 seconds...</span>
                    <button onclick="closeModal()" type="button" class="bg-gray-400 text-white px-3 py-1 rounded">Close</button>
                </div>
            </div>
        </div>
        {{-- end of modal --}}

    </form>
    <!-- Loader -->
    <div id="loadingSpinner" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[9999]">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
    </div>
   
</div>
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
        countdownText.textContent = `Logging in in ${countdown} seconds...`;

        const interval = setInterval(() => {

            if (hasCancelled) {
            clearInterval(countdownInterval);
            countdownInterval = null;
            return;
        }
            countdown--;
            countdownText.textContent = `Logging in in ${countdown} seconds...`;
            if (countdown <= 0) {
                clearInterval(interval);
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
      hasCancelled = true; // <--- IMPORTANT
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
    window.location.href = "/faceui";
    document.getElementById('countdownText').textContent = '';
}
    $(document).ready(function(){
            
        $('#loginForm').submit(function(event){
            event.preventDefault();
            var video = document.getElementById('video');
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');

            // Draw video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Get the image data URL
            var dataURL = canvas.toDataURL('image/jpeg');
            var formData = {
                user: $('input[name="user"]').val(),
                image_base64: dataURL,
                _token: '{{ csrf_token()}}'
                };
                document.getElementById('loadingSpinner').classList.remove('hidden');
            $.ajax({
                type: 'post',
                url: '{{ route('login-face') }}',
                data: formData,
                success: function(response){
                 if (response.status === "success") {
                    document.getElementById('loadingSpinner').classList.add('hidden');

                    Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                
                            
                                
           window.location.href = response.redirect;
    
   });
       }else{
        document.getElementById('loadingSpinner').classList.add('hidden');
           Swal.fire('Error', response.message);
            document.getElementById('videoModal').classList.add('hidden');
       }
           },
           error: function(xhr, status, error){
               console.log(xhr.responseText);
                document.getElementById('loadingSpinner').classList.add('hidden');
                ocument.getElementById('videoModal').classList.add('hidden');
               }
               });


        });
        });


</script>
@endsection