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
        <label>Password</label>
        <video id="video" width="320" height="240" autoplay></video>
        <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
        <div class="flex justify-end">
            <button type="submit" class=" bg-[#02ccfe] text-white rounded-md px-3 py-2">Login</button>
        </div>
        <div class="flex flex-col items-center text-center gap-2 mt-3">
            <p class="text-sm">
                Login using 
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('faceui') }}">Face Recognition</a> 
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                document.getElementById('video').srcObject = stream;
            })
            .catch(function(err) {
                console.error("Error accessing webcam: " + err);
            });
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
            
            $.ajax({
                type: 'post',
                url: '{{ route('login-face') }}',
                data: formData,
                success: function(response){
                 if (response.status === "success") {
                    Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {

               
                    
                    window.location.href = response.redirect;
             
            });
                }else{
                    Swal.fire('Error', response.message);
                }
                    },
                    error: function(xhr, status, error){
                        console.log(xhr.responseText);
                        }
                        });


        });
        });


</script>
@endsection