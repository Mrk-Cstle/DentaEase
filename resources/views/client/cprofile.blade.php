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
                   
                    <p>Face Recognition</p><button id="removeFaceToken" class="bg-[#FF0000] p-1 rounded-sm text-white">Remove</button>
                </div>
              
                <div id="loadingSpinner" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[9999]">
                    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
                </div>
               
                <br>
                @if(Auth::user()->face_token !== null && Auth::user()->face_token !== "")
                <button id="capturemodal" class="px-4 m-5 py-2 bg-blue-200 text-white rounded" disabled>Capture & Register</button>
                @else
                    <button id="capturemodal" class="px-4  m-5 py-2 bg-blue-500 text-white rounded" >Capture & Register</button>
                @endif
                

            <!-- Modal -->
                    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
                        <div class="bg-white p-6 rounded shadow-lg w-96">
                            <h2 class="text-lg font-bold mb-4">Capture & Register</h2>
                            <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                            <video id="video" width="320" height="240" autoplay></video>
                            <button class="mt-4 px-4 py-2 bg-green-500 text-white rounded" id="capture">Capture</button>
                            <button id="closemodal" class="mt-4 px-4 py-2 bg-red-500 text-white rounded">Close</button>
                        </div>
                    </div>
             
            </div>
        </div>
        <div class=" rounded-md grow-1 bg-white">
            <div class="basis-[70%]  flex flex-col p-5 overflow-y-auto">
                <form class="flex flex-col gap-3" action="">
                  
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
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const openBtn = document.getElementById('capturemodal');
    const modal = document.getElementById('modal');
    const closeBtn = document.getElementById('closemodal');
  
    openBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Error accessing webcam: ", err);
        });
    });
  
    closeBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });
  </script>
  
<script>
    
     const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture');
    const context = canvas.getContext('2d');
  

        captureButton.addEventListener('click', () => {
    // Draw video frame onto canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert canvas to Blob
    canvas.toBlob(function(blob) {
        let formData = new FormData();
        formData.append('face_image', blob, 'face_capture.jpg');

        // Show loading spinner
        document.getElementById('loadingSpinner').classList.remove('hidden');

        fetch('/cregister-face', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // for Laravel Blade
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Hide loading spinner
            document.getElementById('loadingSpinner').classList.add('hidden');

            // Show SweetAlert success
            Swal.fire({
                title: 'Success!',
                text: data.message || 'Face registered!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Close modal (optional)
               
                document.getElementById('modal').classList.add('hidden');
                location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingSpinner').classList.add('hidden');

            Swal.fire({
                title: 'Error!',
                text: 'Failed to register face.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }, 'image/jpeg');
});

</script>

<script>
    document.getElementById('removeFaceToken').addEventListener('click', () => {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove your registered face.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/cremove-face-token', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Removed!', data.message, 'success').then(() => {
                        location.reload(); // Optional: refresh to reflect change
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to remove face token.', 'error');
                });
            }
        });
    });
    </script>

@endsection