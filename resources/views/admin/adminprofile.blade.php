@extends('layout.navigation')

@section('title','Profile')
@section('main-content')
<style>
    input{
        border: 1px;
        background-color:#F5F5F5;
        padding: 2px;
    }
</style>

<div class="flex flex-row h-full m-10 gap-5">
    <div class=" rounded-md flex flex-col basis-[30%] bg-white">
        <div class="basis-[30%] bg-cover bg-no-repeat bg-center bg-[url({{ asset('images/defaultp.jpg') }})]  ">
          
        </div>
        <div class="basis-[70%]  flex flex-col p-5 overflow-y-auto">
            <form class="flex flex-col gap-3" action="">
                <label for="fname">Name:</label>
                <input type="text" name="fname" id="fname" value="{{ Auth::user()->name}}">
                <label for="bday">Birth Day</label>
                <input type="date" name="bday" id="bday" value="{{Auth::user()->birth_date}}">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="{{Auth::user()->email}}">
                <label for="contact">Contact Number</label>
                <input type="number" name="contact" id="contact" value="{{Auth::user()->contact_number}}">
                <label for="user">User</label>
                <input type="text" name="user" id="user" value="{{Auth::user()->user}}">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" >
                <input type="hidden" name="oldpassword" id="oldpassword" value="{{Auth::user()->password}}">

                <button class="border rounded-md p-3" type="submit">Update</button>

            </form>
        </div>
    </div>
    <div class="flex flex-col  basis-[70%] gap-5">
        <div class=" rounded-md grow-1 bg-white flex flex-row gap-3 p-5">
            <div class="basis-[50%] border">
                QR
            </div>
            <div class="basis-[50%] border flex flex-col">
                <div class="flex flex-row justify-between m-3">
                    <p>Face Recognition</p><button class="bg-[#FF0000] p-1 rounded-sm">Remove</button>
                </div>
              

                <video id="video" width="320" height="240" autoplay></video>
                <br>
                
                <!-- Capture Button -->
                <button id="capture">Capture & Register</button>
                
                <!-- Preview captured image -->
                <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
            </div>
        </div>
        <div class=" rounded-md grow-1 bg-white">
            
        </div>
    </div>
</div>
<script>
     const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture');
    const context = canvas.getContext('2d');
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Error accessing webcam: ", err);
        });

    captureButton.addEventListener('click', () => {
        // Draw the video frame onto the canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert canvas to Blob
        canvas.toBlob(function(blob) {
            // Prepare form data
            let formData = new FormData();
            formData.append('face_image', blob, 'face_capture.jpg');

            // Send to backend (adjust the URL to your backend endpoint!)
            fetch('/register-face', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // if you are using Laravel Blade
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                alert(data.message || 'Face registered!');
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }, 'image/jpeg');
    });
</script>


@endsection