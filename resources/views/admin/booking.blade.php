@extends('layout.navigation')

@section('title','Appointment Booking')

@section('main-content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<div class="p-4">

@if(auth()->user()->position === 'Receptionist' || auth()->user()->position === 'admin')
<form method="GET" action="{{ route('admin.booking') }}" class="flex flex-wrap md:flex-row gap-4 mb-6 bg-white p-4 rounded shadow">
    <div class="flex flex-col w-full md:w-1/3">
        <label for="dentist_id" class="font-semibold mb-1">Filter by Dentist:</label>
        <select name="dentist_id" id="dentist_id" class="border border-gray-300 rounded p-2">
            <option value="">-- All Dentists --</option>
            @foreach ($dentists as $dentist)
                <option value="{{ $dentist->id }}" {{ request('dentist_id') == $dentist->id ? 'selected' : '' }}>
                    {{ $dentist->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col w-full md:w-1/3">
        <label for="date" class="font-semibold mb-1">Filter by Date:</label>
        <input type="date" name="date" id="date" value="{{ request('date') }}" class="border border-gray-300 rounded p-2">
    </div>

    <div class="flex items-end w-full md:w-1/3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full md:w-auto">Filter</button>
    </div>
</form>
@endif

@if(auth()->user()->position == 'Dentist')
<form method="GET" action="{{ route('admin.booking') }}" class="flex gap-4 mb-6 bg-white p-4 rounded shadow">
    <div class="flex flex-col w-full md:w-1/3">
        <label for="date" class="font-semibold mb-1">Filter by Date:</label>
        <input type="date" id="date" name="date" value="{{ request('date') }}" class="border border-gray-300 rounded p-2">
    </div>
    <div class="flex items-end">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
    </div>
</form>
@endif

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Appointment Booking</h2>
    <div class="flex flex-row gap-4">
    <a href="{{ route('admin.booking.history') }}" 
       class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">
        View History Logs
    </a>
    <button onclick="$('#bookingModal').removeClass('hidden')"
    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
    + Add Appointment
</button>
    </div>
   
</div>


<!-- Booking Modal -->

<div class="overflow-x-auto bg-white p-4 rounded shadow">
    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2 border">User</th>
                <th class="px-4 py-2 border">Service</th>
                <th class="px-4 py-2 border">Date</th>
                <th class="px-4 py-2 border">Start</th>
                <th class="px-4 py-2 border">End</th>
                <th class="px-4 py-2 border">Description</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Action</th>
            </tr>
        </thead>
        <tbody id="appointments-table-body">
            @include('admin.partials.appointments-table', ['appointments' => $appointments])
        </tbody>
    </table>
</div>

@include('admin.partials.usermodal')


<div id="bookingModal" 
     class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 relative mx-4 my-8">
        
        <!-- Close Button -->
        <button type="button" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800"
                onclick="$('#bookingModal').addClass('hidden')">
            ✕
        </button>

        <h2 class="text-2xl font-bold mb-4 text-center">Book an Appointment</h2>

        <div class="flex flex-col md:flex-row md:gap-10">
            <div class="w-full">
                @include('admin.partials.booking_modal')
            </div>
        </div>
    </div>
</div>


</div> <!-- End padding wrapper -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function showUserModal(userId) {
    $('#userModalContent').html('<p class="text-center text-gray-500">Loading...</p>');
    document.getElementById('userModal').classList.remove('hidden');

    $.get(`/user/details/${userId}`, function (html) {
        $('#userModalContent').html(html);
    }).fail(function () {
        $('#userModalContent').html('<p class="text-red-500">Failed to load user details.</p>');
    });
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
}
</script>

<script>
$(document).on('click', '.approve-btn', function () {
    const button = $(this);
    const row = button.closest('tr');
    const appointmentId = button.data('id');
    const time = row.find('.appointment-time').val();
    const endTime = row.find('.booking-end-time').val();

    $.ajax({
        url: `/appointments/${appointmentId}/approve`,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            appointment_time: time,
            booking_end_time: endTime,
        },
        success: function (res) {
            Swal.fire({
                icon: 'success',
                title: 'Approved!',
                text: 'Appointment has been approved.'
            });

            $.get('{{ route('appointments.fetch') }}', function (html) {
                $('#appointments-table-body').html(html);
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Something went wrong.'
            });
        }
    });
});

$(document).on('click', '.cancel-btn', function () {
    const button = $(this);
    const appointmentId = button.data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This will cancel the appointment.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/appointments/${appointmentId}/cancel`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cancelled!',
                        text: 'Appointment has been cancelled.'
                    });

                    $.get('{{ route('appointments.fetch') }}', function (html) {
                        $('#appointments-table-body').html(html);
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong.'
                    });
                }
            });
        }
    });
});
</script>

@endsection
