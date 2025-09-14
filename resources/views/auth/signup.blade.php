@extends('layout.auth')

@section('title', 'Signup')

@section('auth-content')

    <form id="signupForm" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <h2 class="text-2xl font-bold text-sky-600 text-center">Patient Registration</h2>

        <div class="flex justify-between items-center text-sm font-semibold text-gray-600 mb-2">
            <div class="step-label text-center flex-1">
                <span class="block" id="label-step-1">1. Personal Info</span>
            </div>
            <div class="step-label text-center flex-1">
                <span class="block" id="label-step-2">2. Account Setup</span>
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
                <input type="hidden" name="account_type" value="patient">
                
                <div>
                    <label class="block text-gray-700 font-medium mb-1">First Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Middle Name</label>
                    <input type="text" name="middlename" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Last Name</label>
                    <input type="text" name="lastname" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="font-semibold">Suffix</label>
                    <select name="suffix" class="w-full border p-2 rounded">
                      <option value="">-- Select Suffix --</option>
                      <option value="Jr.">Jr.</option>
                      <option value="Sr.">Sr.</option>
                      <option value="II">II</option>
                      <option value="III">III</option>
                      <option value="IV">IV</option>
                      <option value="V">V</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Birthdate</label>
                    <input type="date" name="birth_date" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Place of Birth</label>
                    <input type="text" name="birthplace" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-gray-700 font-medium mb-1">Current Address</label>
                <input type="text" name="current_address" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="step hidden" id="step-2">
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Upload Valid ID</label>
                    <input type="file" name="verification_id" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Contact Number</label>
                    <input type="number" name="contact_number" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Username</label>
                    <input type="text" name="user" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white" required>
                </div>
                <div class="relative">
                    <label class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white pr-12" required>
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
            <input type="text" id="otp" name="otp" placeholder="Enter OTP from your email" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white" required>
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
                <a class="text-blue-500 underline hover:text-blue-700 transition" href="{{ route('Qr') }}">QR</a>
            </p>
            <p>
                Already have an account? 
                <a class="text-blue-500 underline hover:text-blue-700" href="{{ route('loginui') }}">Login</a>
            </p>
        </div>
    </form>


<!-- JS -->
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
            const formData = new FormData($('#signupForm')[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route("send.otp") }}',
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
        if (otp.length === 6) {
            $.ajax({
                url: '{{ route("verify.otp") }}',
                method: 'get',
                data: { otp, _token: '{{ csrf_token() }}' },
                success: function (res) {
                    Swal.fire('Success', res.message, 'success');
                    $('#signupForm')[0].reset();
                    currentStep = 1;
                    showStep(currentStep);
                     window.location.href = "{{ route('login') }}";
                },
                error: function (xhr) {
                    const response = xhr.responseJSON;
                    Swal.fire('Validation Error', response.message, 'error');
                }
            });
        } else {
            Swal.fire('Invalid OTP', 'Please enter a 6-digit OTP.', 'warning');
        }
    });
});
</script>
@endsection
