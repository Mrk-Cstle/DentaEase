@extends('layout.navigation')

@section('title','Profile')
@section('main-content')
<style>
    input {
        border: 1px;
        background-color: #F5F5F5;
        padding: 8px;
        border-radius: 6px;
        width: 100%;
    }
</style>

<h1 class="text-2xl font-semibold mb-4">View User Details</h1>
<div class="flex flex-col h-full">
    <div class="flex flex-row h-full gap-5">
        <!-- Profile Card -->
        <div class="rounded-md flex flex-col basis-[30%] bg-white shadow p-4">
            <div class="h-40 w-full bg-cover bg-center rounded"
                style="background-image: url('{{ $user->profile_image ? asset('storage/profile_pictures/' . $user->profile_image) : asset('images/defaultp.jpg') }}')">
            </div>
            <div class="flex flex-col pt-5 overflow-y-auto gap-5">
                <form class="flex flex-col gap-3">
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" value="{{ $user->lastname }}">

                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="{{ $user->name }}">

                    <label for="middlename">Middle Name:</label>
                    <input type="text" name="middlename" id="middlename" value="{{ $user->middlename }}">

                    <label for="suffix">Suffix:</label>
                    <input type="text" name="suffix" id="suffix" value="{{ $user->suffix }}">

                    <label for="birthdate">Birth Day</label>
                    <input type="date" name="birthdate" id="birthdate" value="{{ $user->birth_date }}">
                </form>
                <button id="deletebtn" data-id="{{ $user->id }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">Delete</button>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="flex flex-col basis-[70%] gap-5">
            <div class="rounded-md bg-white shadow p-5">
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('storage/qr_codes/' . $user->qr_code) }}" alt="User QR Code" class="h-40">
                </div>
                <form id="updateProfile" class="flex flex-col gap-3">
                    <input type="hidden" name="id" id="id" value="{{ $user->id }}">

                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="{{ $user->email }}">

                    <label for="contact">Contact Number</label>
                    <input type="number" name="contact" id="contact" value="{{ $user->contact_number }}">

                    <label for="user">User</label>
                    <input type="text" name="user" id="user" value="{{ $user->user }}">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow w-max" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>

    @if ($user->account_type == "patient")
        <div class="mt-10 bg-white p-6 rounded shadow w-full">
            <h2 class="text-xl font-bold mb-4">Completed Appointments</h2>
            @if($completedAppointments->isEmpty())
                <p class="text-gray-500">You have no completed appointments.</p>
            @else
                <table class="table-auto w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-3 py-2">Date</th>
                            <th class="px-3 py-2">Time</th>
                            <th class="px-3 py-2">End Time</th>
                            <th class="px-3 py-2">Dentist</th>
                            <th class="px-3 py-2">Description</th>
                            <th class="px-3 py-2">Work Done</th>
                            <th class="px-3 py-2">Total Price</th>
                            <th class="px-3 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedAppointments as $appointment)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($appointment->booking_end_time)->format('h:i A') }}</td>
                                <td class="px-3 py-2">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                <td class="px-3 py-2">{{ $appointment->desc }}</td>
                                <td class="px-3 py-2">{{ $appointment->work_done }}</td>
                                <td class="px-3 py-2">₱{{ number_format($appointment->total_price, 2) }}</td>
                                <td class="px-3 py-2">{{ $appointment->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
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
                        name : $('input[name="name"]').val(),
                        lastname : $('input[name="lastname"]').val(),
                        middlename : $('input[name="middlename"]').val(),
                        suffix : $('input[name="suffix"]').val(),
                        birthdate : $('input[name="birthdate"]').val(),
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