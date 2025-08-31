@extends('layout.cnav')

@section('title','CProfile')
@section('main-content')
<style>
    input{
        border: 1px;
        background-color:#F5F5F5;
        padding: 2px;
    }
</style>

      <!-- Tab Navigation -->
<div class="mb-6 border-b border-gray-300">
    <ul class="flex space-x-6 text-sm font-medium text-center text-gray-600" id="tabs">
        <li>
            <button class="tab-button border-b-2 border-transparent py-2 px-4 hover:text-blue-600 hover:border-blue-600 active"
                data-tab="profile-tab">Profile</button>
        </li>
        {{-- <li>
            <button class="tab-button border-b-2 border-transparent py-2 px-4 hover:text-blue-600 hover:border-blue-600"
                data-tab="medical-tab">Medical Form</button>
        </li> --}}
    </ul>
</div>

<div id="profile-tab" class="tab-content">
<div class="flex flex-col h-full ">
<div class="flex flex-row h-full  gap-5">
    <div class=" rounded-md flex flex-col w-[30%] bg-white">
        @if (Auth::user()->profile_image == null)
        <div class="basis-[30%] bg-cover bg-no-repeat bg-center bg-[url({{ asset('images/defaultp.jpg') }})]  ">
        @else
        <div class="basis-[30%] bg-cover bg-no-repeat bg-center bg-[url({{ asset('storage/profile_pictures/' . Auth::user()->profile_image) }})]  ">
        @endif
      
            
        </div>
        <div class="basis-[70%] flex flex-col p-5 overflow-y-auto">
            <form class="flex flex-col gap-3" method="POST" action="{{ route('profile.upload') }}" enctype="multipart/form-data">
                @csrf
        
                <label for="fname">Name:</label>
                <input type="text" name="fname" id="fname"
                    value="{{ Auth::user()->lastname }}, {{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->suffix }}"
                    readonly>
        
                <label for="bday">Birth Day:</label>
                <input type="date" name="bday" id="bday" value="{{ Auth::user()->birth_date }}" readonly>
        
                <label for="profile_picture">Upload Profile Picture:</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="p-2 border rounded">
        
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-3 w-max">Upload</button>
            </form>
        </div>
    </div>
    <div class="flex flex-col  basis-[70%] gap-5">
        <div class=" rounded-md grow-1 bg-white flex flex-row gap-3 p-5">
         <div class="basis-[50%] border">
              <div class="flex flex-col justify-center m-3 items-center">
                <img src="{{ asset('storage/qr_codes/' . Auth::user()->qr_code) }}" alt="User QR Code" class="mx-auto w-40 h-40 object-contain border p-2 rounded" />
                <a href="{{ asset('storage/qr_codes/' . Auth::user()->qr_code) }}" download
                   class="mt-4 inline-block bg-[#f84525] text-white px-4 py-2 rounded hover:bg-red-700 transition duration-200">
                    Download QR Code
                </a>
              </div>
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
                <form id="updateProfile" class="flex flex-col gap-3" action="">
                  
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
<div class="mt-10 bg-white p-6 rounded shadow w-full">
    <h2 class="text-lg font-bold mb-4">Completed Appointments</h2>

    @if($completedAppointments->isEmpty())
        <p class="text-gray-500">You have no completed appointments.</p>
    @else
        <div class="hidden md:block">
            {{-- Desktop Table --}}
            <table class="w-full text-sm border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-3 py-2">Date</th>
                        <th class="px-3 py-2">Time</th>
                        <th class="px-3 py-2">Branch</th>
                        <th class="px-3 py-2">Dentist</th>
                        <th class="px-3 py-2">Description</th>
                        <th class="px-3 py-2">Work Done</th>
                        <th class="px-3 py-2">Total</th>
                        <th class="px-3 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedAppointments as $appointment)
                        <tr class="border-t border-gray-300">
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                            <td class="px-3 py-2">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($appointment->booking_end_time)->format('h:i A') }}
                            </td>
                            <td class="px-3 py-2">{{ $appointment->store->name }}</td>
                            <td class="px-3 py-2">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                            <td class="px-3 py-2">{{ $appointment->desc }}</td>
                            <td class="px-3 py-2">{{ $appointment->work_done }}</td>
                            <td class="px-3 py-2">₱{{ number_format($appointment->total_price, 2) }}</td>
                            <td class="px-3 py-2 capitalize">{{ $appointment->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden flex flex-col gap-4">
            {{-- Mobile Card Layout --}}
            @foreach($completedAppointments as $appointment)
                <div class="border border-gray-300 rounded p-3 shadow-sm text-sm">
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->booking_end_time)->format('h:i A') }}</p>
                    <p><strong>Branch:</strong> {{ $appointment->store->name }}</p>
                    <p><strong>Dentist:</strong> {{ $appointment->dentist->name ?? 'N/A' }}</p>
                    <p><strong>Description:</strong> {{ $appointment->desc }}</p>
                    <p><strong>Work Done:</strong> {{ $appointment->work_done }}</p>
                    <p><strong>Total:</strong> ₱{{ number_format($appointment->total_price, 2) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

</div>
</div>
<div id="medical-tab" class="tab-content hidden">
 @if($medicalForms && count($medicalForms) > 0)
<div id="medicalCarousel" class="max-w-5xl mx-auto relative">

    @foreach($medicalForms as $index => $medicalForm)
    <div class="medical-form-slide {{ $index !== 0 ? 'hidden' : '' }} bg-white p-6 rounded-lg shadow space-y-10" data-index="{{ $index }}">


    {{-- Medical History --}}
    <div>
    <h2 class="text-xl font-bold mb-4 flex items-center justify-between gap-2">
  <span class="flex items-center gap-2">
    📜 <span>MEDICAL HISTORY</span>
  </span>
  <span class="text-sm font-normal text-gray-500">
    Submitted on: {{ \Carbon\Carbon::parse($medicalForm->created_at)->format('F d, Y h:i A') }}
  </span>
</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
          <thead class="bg-[#5D5CFF] text-white">
            <tr>
              <th class="px-4 py-2 text-left">Condition</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2 text-left">Details</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-2">Allergies</td>
                 <td class="text-center">   {{ $medicalForm->allergies == 1 ? 'Yes' : 'No' }}</td>

              <td class="px-4 py-2">{{ $medicalForm->allergies_details ?? '' }}</td>
            </tr>
            <tr class="border-t">
              <td class="px-4 py-2">Heart Condition</td>
                 <td class="text-center">   {{ $medicalForm->heart_condition == 1 ? 'Yes' : 'No' }}</td>

        
              <td class="px-4 py-2">{{ $medicalForm->heart_condition_details ?? '' }}</td>
            </tr>
            <tr class="border-t">
              <td class="px-4 py-2">Asthma</td>

              <td class="text-center"> {{ $medicalForm->asthma == 1 ? 'Yes' : 'No' }}</td>
              <td class="px-4 py-2">{{ $medicalForm->asthma_details ?? '' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- Past Surgeries --}}
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>📄</span>PAST SURGERIES</h2>
    
  <div class="overflow-x-auto">
    <table class="min-w-full border text-sm">
      <thead class="bg-[#5D5CFF] text-white">
        <tr>
          <th class="px-4 py-2">Surgery Type</th>
          <th class="px-4 py-2">Date</th>
          <th class="px-4 py-2">Hospital/Clinic</th>
          <th class="px-4 py-2">Remarks</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-t">
          <td class="px-2 py-2">{{ $medicalForm->surgery_type }}</td>
          <td class="px-2 py-2">{{ $medicalForm->surgery_date }}</td>
          <td class="px-2 py-2">{{ $medicalForm->surgery_location }}</td>
          <td class="px-2 py-2">{{ $medicalForm->surgery_remarks }}</td>
        </tr>
      </tbody>
    </table>
  </div>

    </div>

    {{-- Current Medications --}}
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>💊</span>CURRENT MEDICATIONS</h2>
    @if($medicalForm->medication_name)
  <table class="min-w-full border text-sm">
    <thead class="bg-[#5D5CFF] text-white">
      <tr>
        <th class="px-4 py-2">Medication Name</th>
        <th class="px-4 py-2">Dosage</th>
        <th class="px-4 py-2">Reason</th>
      </tr>
    </thead>
    <tbody>
      <tr class="border-t">
        <td class="px-2 py-2">{{ $medicalForm->medication_name }}</td>
        <td class="px-2 py-2">{{ $medicalForm->medication_dosage }}</td>
        <td class="px-2 py-2">{{ $medicalForm->medication_reason }}</td>
      </tr>
    </tbody>
  </table>
@else
  <p class="text-gray-500 italic">No current medications reported.</p>
@endif
    </div>

    {{-- Dental History --}}
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>🍩</span>DENTAL HISTORY</h2>
      <div class="space-y-4">
        <div>
          <label class="font-semibold">Reason for today’s visit:</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->visit_reason ?? '' }}" readonly>
        </div>
        <div>
          <label class="font-semibold">Last dental visit:</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->last_dental_visit ?? '' }}" readonly>
        </div>
        <div>
          <label class="font-semibold">Previous dental problems?</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->had_dental_issues== 1 ? 'Yes' : 'No'  }}" readonly>
        </div>
        <div>
          <label class="font-semibold">Dental anxiety:</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->dental_anxiety == 1 ? 'Yes' : 'No' }}" readonly>
        </div>
      </div>
    </div>

    {{-- Emergency Contact --}}
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>👥</span>EMERGENCY CONTACT</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block font-semibold">Name</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->emergency_name ?? '' }}" readonly>
        </div>
        <div>
          <label class="block font-semibold">Relationship</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->emergency_relationship ?? '' }}" readonly>
        </div>
        <div>
          <label class="block font-semibold">Contact Number</label>
          <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $medicalForm->emergency_contact ?? '' }}" readonly>
        </div>
      </div>
    </div>

  </div>
   @endforeach

    <!-- Carousel Controls -->
    <div class="flex justify-between items-center mt-4">
        <button id="prevBtn" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">Previous</button>
        <span id="slideIndicator" class="text-sm text-gray-600">1 of {{ count($medicalForms) }}</span>
        <button id="nextBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Next</button>
    </div>
</div> 
  @else
  <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow text-center">
    <p class="text-gray-500 italic">You have not submitted your medical history form yet.</p>
  </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.querySelectorAll(".tab-button").forEach(button => {
    button.addEventListener("click", () => {
        const tab = button.dataset.tab;

        // Remove active class from all buttons
        document.querySelectorAll(".tab-button").forEach(btn =>
            btn.classList.remove("active", "border-blue-600", "text-blue-600")
        );
        button.classList.add("active", "border-blue-600", "text-blue-600");

        // Hide all tab content
        document.querySelectorAll(".tab-content").forEach(tc =>
            tc.classList.add("hidden")
        );

        // Show clicked tab
        document.getElementById(tab).classList.remove("hidden");
    });
    });
</script>
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

    </script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.medical-form-slide');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const indicator = document.getElementById('slideIndicator');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('hidden', i !== index);
        });
        indicator.textContent = `${index + 1} of ${slides.length}`;
    }

    nextBtn.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    });

    prevBtn.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    });

    showSlide(currentSlide);
});
</script>
@endsection