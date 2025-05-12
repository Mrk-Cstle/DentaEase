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

  <h1>View User Details</h1>

<div class="flex flex-row h-full m-10 gap-5">
    <div class=" rounded-md flex flex-col basis-[30%] bg-white">
        <div class="basis-[30%] bg-cover bg-no-repeat bg-center bg-[url({{ asset('images/defaultp.jpg') }})]  ">
          
        </div>
        <div class="basis-[70%]  flex flex-col p-5 overflow-y-auto gap-5 ">
            <form class="flex flex-col gap-3" action="">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="{{ $user->name}}">
                <label for="bday">Birth Day</label>
                <input type="date" name="bday" id="bday" value="{{$user->birth_date}}">
               

            </form>
            <button id="deletebtn" data-id="{{ $user->id}}" class="border rounded-md p-3 bg-[#FF0000] text-white" type="submit">Delete</button>
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
                <form id="updateProfile" class="flex flex-col gap-3" >
                
                    <label for="email">Email</label>
                    <input type="text" name="id" id="id" value="{{$user->id}}" hidden>
                    <input type="text" name="email" id="email" value="{{$user->email}}">
                    <label for="contact">Contact Number</label>
                    <input type="number" name="contact" id="contact" value="{{$user->contact_number}}">
                    <label for="user">User</label>
                    <input type="text" name="user" id="user" value="{{$user->user}}">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" >
                    
    
                    <button class="border rounded-md p-3" type="submit">Update</button>
    
                </form>
            </div>
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
   $('#deletebtn').click(function (e) {
    e.preventDefault(); // prevent default if inside a form or link

const userId = $(this).data('id'); 

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this user!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "{{ route('deleteuser') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: userId
                },
                dataType: "json",
                success: function (response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                        if (result.isConfirmed) {
                           
                            window.location.href = '/useraccount'; 
                        }
                        });
                                        },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong.', 'error');
                }
            });
        }
    });
    });
    
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
                        id : $('input[name="id"]').val(),
                        email : $('input[name="email"]').val(),
                        names : $('input[name="name"]').val(),
                        bday : $('input[name="bday"]').val(),
                        contact : $('input[name="contact"]').val(),
                        user : $('input[name="user"]').val(),
                        password : $('input[name="password"]').val(),


                    }
       $.ajax({
            type: "patch",
            url: "{{route('updateUser')}}",
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