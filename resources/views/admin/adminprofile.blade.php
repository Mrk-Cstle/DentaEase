@extends('layout.navigation')

@section('title','Profile')
@section('main-content')
<h1 class="text-2xl font-bold mb-4 text-accent">My Profile</h1>

<div class="flex flex-row h-full gap-5">
    <div class="rounded-md flex flex-col w-[30%] bg-white shadow p-4">
        @if (Auth::user()->profile_image == null)
        <div class="aspect-square bg-cover bg-no-repeat bg-center rounded-t-md bg-[url({{ asset('images/defaultp.jpg') }})]">
        @else
        <div class="aspect-square bg-cover bg-no-repeat bg-center rounded-t-md bg-[url({{ asset('storage/profile_pictures/' . Auth::user()->profile_image) }})]">
        @endif
        </div>

        <div class="flex flex-col gap-4 mt-4">
            <form class="flex flex-col gap-4" method="POST" action="{{ route('profile.upload') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="fname" class="block font-medium text-gray-700">Name</label>
                    <input type="text" name="fname" id="fname" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" value="{{ Auth::user()->lastname }}, {{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->suffix }}" readonly>
                </div>
                <div>
                    <label for="bday" class="block font-medium text-gray-700">Birth Day</label>
                    <input type="date" name="bday" id="bday" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" value="{{ Auth::user()->birth_date }}" readonly>
                </div>
                <div>
                    <label for="profile_image" class="block font-medium text-gray-700">Upload Profile Picture</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded self-start">Upload</button>
            </form>
        </div>
    </div>

    <div class="flex flex-col basis-[70%] gap-5">
        <div class="rounded-md bg-white shadow flex flex-row gap-3 p-5">
            <div class="basis-[50%] border rounded p-4 flex items-center justify-center">
                <a href="{{ asset('storage/qr_codes/' . Auth::user()->qr_code) }}" download="qr_code.svg">
                <img src="{{ asset('storage/qr_codes/' . Auth::user()->qr_code) }}" alt="QR Code" class="w-32 h-32 hover:opacity-80" />
                <p class="text-blue-600 text-sm mt-2 text-center hover:underline">Download QR Code</p>
                </a>
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

        <div class="rounded-md bg-white shadow p-5">
            <form id="updateProfile" class="flex flex-col gap-4">
                <div>
                    <label for="email" class="block font-medium text-gray-700">Email</label>
                    <input type="text" name="email" id="email" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" value="{{ Auth::user()->email }}">
                </div>
                <div>
                    <label for="contact" class="block font-medium text-gray-700">Contact Number</label>
                    <input type="number" name="contact" id="contact" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" value="{{ Auth::user()->contact_number }}">
                </div>
                <div>
                    <label for="user" class="block font-medium text-gray-700">Username</label>
                    <input type="text" name="user" id="user" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" value="{{ Auth::user()->user }}">
                </div>
                <div>
                    <label for="password" class="block font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" placeholder="••••••••">
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded self-start" type="submit">Update</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const openBtn = document.getElementById('capturemodal');
    const modal = document.getElementById('modal');
    const closeBtn = document.getElementById('closemodal');
  
    openBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
      if (window.isSecureContext) {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(error => {
            console.error("Error accessing media devices.", error);
        });
} else {
    console.error("getUserMedia requires a secure context (HTTPS).");
}
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

        fetch('/register-face', {
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
    ///update profile
    $('#updateProfile').submit(function (event) {
        event.preventDefault();
        var formData = {
                       
                        email : $('input[name="email"]').val(),
                       
                        contact : $('input[name="contact"]').val(),
                        user : $('input[name="user"]').val(),
                        password : $('input[name="password"]').val(),
                      

                    }
       $.ajax({
            type: "patch",
            url: "{{route('updateProfile')}}",
            data:formData,
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function (response) {
                if (response.status == 'success') {
                    Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                              
                            })
                } else {
                    Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                              
                            })
                }
            }, error: function (xhr) {
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            let errorList = '';

            for (let field in errors) {
                errorList += `${errors[field].join(', ')}\n`;
            }

            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorList.trim(),
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            });
        }
    }
        });
    })


    ///remove face token
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
                fetch('/remove-face-token', {
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