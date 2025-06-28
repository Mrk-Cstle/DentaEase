@extends('layout.auth')

@section('title', 'Signup')
   <style>
    .input-field {
        @apply w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white;
    }
    .btn-primary {
        @apply bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition;
    }
    .btn-secondary {
        @apply bg-gray-400 text-white px-5 py-2 rounded hover:bg-gray-500 transition;
    }
    .step-indicator {
        @apply px-2 py-1 rounded-full;
    }
    .step-indicator.active {
        @apply text-blue-600 font-bold;
    }
    .form-input {
        @apply w-full text-base px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500;
    }
</style>

@section('auth-content')
           {{-- <div class="bg-[#F5F5F5] bg-opacity-75 w-full max-w-3xl mx-auto px-10 py-10 rounded-md flex flex-col "> --}}


  
    <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg relative m-5">
  <form id="signupForm" method="POST" enctype="multipart/form-data" class="p-8 max-w-3xl mx-auto bg-white rounded-lg shadow space-y-8">
    @csrf

    <h2 class="text-2xl font-bold text-gray-800 text-center">Patient Registration</h2>
<div class="flex justify-between items-center text-sm font-semibold text-gray-600 mb-2">
    <div class="step-label text-center flex-1">
        <span class="block " id="label-step-1">1. Personal Info</span>
    </div>
    <div class="step-label text-center flex-1">
        <span class="block" id="label-step-2">2.Account Setup</span>
    </div>
    <div class="step-label text-center flex-1">
        <span class="block" id="label-step-3">3. OTP Verification</span>
    </div>
</div>
    <!-- Progress Bar -->
    <div class="w-full h-2 bg-gray-200 rounded">
        <div id="progressBar" class="h-full bg-blue-600 rounded transition-all duration-300" style="width: 33%;"></div>
    </div>

    <!-- Step 1 -->
    <div class="step" id="step-1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 mb-1 font-medium">First Name</label>
                <input name="account_type" id="account_type" value="patient" hidden class="w-full border border-gray-300 rounded-md p-2" required>
                <input name="name" type="text" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Middle Name</label>
                <input name="middlename" type="text" class="w-full border border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Last Name</label>
                <input name="lastname" type="text" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Suffix</label>
                <input name="suffix" type="text" class="w-full border border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Birthdate</label>
                <input name="birth_date" type="date" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Place of Birth</label>
                <input name="birthplace" type="text" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 mb-1 font-medium">Current Address</label>
            <input name="current_address" type="text" class="w-full border border-gray-300 rounded-md p-2" required>
        </div>
    </div>

    <!-- Step 2 -->
    <div class="step hidden" id="step-2">
        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Upload Valid ID</label>
                <input type="file" name="verification_id" class="w-full border border-gray-300 rounded-md p-2 bg-white" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Contact Number</label>
                <input type="number" name="contact_number" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Username</label>
                <input type="text" name="user" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="relative">
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded-md p-2 pr-12" required>
                <div class="absolute right-3 top-[38px]">
                    <input type="checkbox" id="showPassword" class="mr-1">
                    <label for="showPassword" class="text-sm text-gray-600">Show</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3 -->
    <div class="step hidden" id="step-3">
        <label class="block text-gray-700 font-medium mb-1">Enter OTP</label>
        <input type="text" id="otp" name="otp" placeholder="Enter OTP from your email" class="w-full border border-gray-300 rounded-md p-2" required>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between pt-4 border-t mt-8">
        <button type="button" class="prev hidden bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Back</button>
        <button type="button" class="next bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Next</button>
        <button type="submit" class="submit hidden bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Submit</button>
    </div>

    <!-- Footer Note -->
    <div class="text-center space-y-1 text-sm mt-4">
        <p>
            Login using 
            <a class="text-blue-500 underline hover:text-blue-700" href="{{ route('faceui') }}">Face Recognition</a> 
            or 
            <a class="text-blue-500 underline hover:text-blue-700" href="#">QR</a>
        </p>
        <p>
            Already have an account? 
            <a class="text-blue-500 underline hover:text-blue-700" href="{{ route('loginui') }}">Login</a>
        </p>
    </div>
</form>


<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentStep = 1;
const totalSteps = 3;

function showStep(step) {
    $('.step').addClass('hidden');
    $('#step-' + step).removeClass('hidden');

    $('.prev').toggleClass('hidden', step === 1);
    $('.next').toggleClass('hidden', step >= totalSteps);
    $('.submit').toggleClass('hidden', step !== totalSteps);

    $('#progressBar').css('width', `${(step / totalSteps) * 100}%`);
}

$(document).ready(function () {
    showStep(currentStep);

    $('.next').click(function () {
        if (currentStep === 2) {
            // Simulate OTP send
            const formData = new FormData($('#signupForm')[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route("send.otp") }}', // <-- Your route here
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    currentStep++;
                    showStep(currentStep);
                },
                error: function (xhr) {
                     const response = xhr.responseJSON;

                        // Option 1: Display the top-level message directly
                        Swal.fire('Validation Error', response.message, 'error');
                }
            });
        } else {
            currentStep++;
            showStep(currentStep);
        }
    });

    $('.prev').click(function () {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    $('#showPassword').on('change', function () {
        $('#password').attr('type', this.checked ? 'text' : 'password');
    });

    $('#signupForm').on('submit', function (e) {
        e.preventDefault();
        const otp = $('#otp').val();
        if (otp.length == "6") {
            $.ajax({
                url: '{{ route("verify.otp") }}', // <-- Your route here
                method: 'get',
                data: { otp, _token: '{{ csrf_token() }}' },
                success: function (res) {
                     Swal.fire('Success Error', response.message, 'success');
                    // alert("Success: " + res.message);
                    $('#signupForm')[0].reset();
                    currentStep = 1;
                    showStep(currentStep);
                },
                error: function (xhr) {
                     const response = xhr.responseJSON;

                        // Option 1: Display the top-level message directly
                        Swal.fire('Validation Error', response.message, 'error');
                }
            });
        } else {
            alert("Please enter a valid OTP");
        }
    });
});
</script>


            @endsection