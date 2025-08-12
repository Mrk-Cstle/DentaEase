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
<script>
let openDays = []; // Example: ['mon', 'tue', 'wed']

// Map day string to index (Sunday = 0)
const dayMap = { sun: 0, mon: 1, tue: 2, wed: 3, thu: 4, fri: 5, sat: 6 };

let flatpickrInstance;

$('#store_id').on('change', function () {
    const storeId = $(this).val();
    if (!storeId) return;

    // Fetch store schedule
    $.get(`/store/${storeId}/schedule`, function (data) {
        if (data.status === 'success') {
            const dayNameToNumber = {
                sun: 0, mon: 1, tue: 2, wed: 3, thu: 4, fri: 5, sat: 6
            };
            const dayNameToLabel = {
                sun: 'Sunday', mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday',
                thu: 'Thursday', fri: 'Friday', sat: 'Saturday'
            };

            openDays = (data.open_days || []).map(day => dayNameToNumber[day.toLowerCase()]);
            const readableDays = (data.open_days || []).map(day => dayNameToLabel[day.toLowerCase()]).join(', ');

            if (flatpickrInstance) {
                flatpickrInstance.destroy();
            }

            flatpickrInstance = flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                minDate: new Date().fp_incr(2),
                disable: [date => !openDays.includes(date.getDay())]
            });

            $('#storedetail').html(`
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${data.name}</h2>
                    <p><strong>Address:</strong> ${data.address}</p>
                    <p><strong>Opening Time:</strong> ${data.opening_time}</p>
                    <p><strong>Closing Time:</strong> ${data.closing_time}</p>
                    <p><strong>Open Days:</strong> ${readableDays}</p>
                </div>
            `);

            $('#appointment_date').prop('disabled', false);
        }
    });

    // Fetch dentists for the selected store
    $.get(`/branch/${storeId}/dentists`, function (response) {
        if (response.dentists && response.dentists.length > 0) {
            let dentistOptions = '<option value="">-- Choose Dentist --</option>';
            response.dentists.forEach(dentist => {
                dentistOptions += `<option value="${dentist.id}">${dentist.name}</option>`;
            });
            console.log("Dentist select found?", $('#dentist_id').length);

            $('#dentist_id').html(dentistOptions).prop('disabled', false);
        } else {
            $('#dentist_id').html('<option value="">No dentists available</option>').prop('disabled', true);
        }
    });
});

// Service change event — stays only for service details
$('#service_id').on('change', function () {
    const serviceId = $(this).val();
    if (!serviceId) return;

    $.get(`/service/${serviceId}`, function (servdata) {
        if (servdata.status == 'success') {
            $('#servicedetail').html(`
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${servdata.name}</h2>
                    <p><strong>Description:</strong> ${servdata.desc}</p>
                    <p><strong>Type:</strong> ${servdata.type}</p>
                    <p><strong>Approx. Time:</strong> ${servdata.time}</p>
                    <p><strong>Approx. Price:</strong> ${servdata.price}</p>
                </div>
            `);
        }
    });
});


flatpickr("#appointment_date", {
    disable: [
        function(date) {
            return !openDays.includes(date.getDay()); // disables closed days
        }
    ]
});

$('#dentist_id').on('change', function () {
 
    document.getElementById('appointment_date').value = "";
});

$('#appointment_date').on('change', function () {
    const selectedDate = $(this).val();
    const storeId = $('#store_id').val();
    const dentistId = $('#dentist_id').val();

    if (!storeId || !dentistId) {
        $('#appointment_time').html('<option>Please select a branch and dentist</option>');
        return;
    }

    $('#appointment_time').html('<option>Loading...</option>').prop('disabled', false);

    // Updated endpoint to include dentist
    $.get(`/branch/${storeId}/dentist/${dentistId}/slots`, { date: selectedDate }, function (response) {
        if (response.slots && response.slots.length > 0) {
            let options = `<option value="">-- Select Time --</option>`;
            response.slots.forEach(time => {
                options += `<option value="${time}">${time}</option>`;
            });
            $('#appointment_time').html(options);
        } else {
            $('#appointment_time').html('<option value="">No slots available</option>');
        }
    });
});

// $('#appointment_date').on('change', function () {
//      const selectedDate = $(this).val();
//     const storeId = $('#store_id').val();
//     const selectedDay = new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'short' }).toLowerCase(); // 'mon'

//     // // Validate open day
//     // if (!openDays.includes(selectedDay)) {
//     //     alert('Store is closed on this day.');
//     //     $(this).val('');
//     //     $('#appointment_time').html('<option value="">Store closed on this day</option>').prop('disabled', true);
//     //     return;
//     // }

//     $('#appointment_time').html('<option>Loading...</option>').prop('disabled', false);

//     // Fetch available slots
//     $.get(`/branch/${storeId}/available-slots`, { date: selectedDate }, function (response) {
//         if (response.slots && response.slots.length > 0) {
//             let options = `<option value="">-- Select Time --</option>`;
//             response.slots.forEach(time => {
//                 options += `<option value="${time}">${time}</option>`;
//             });
//             $('#appointment_time').html(options);
//         } else {
//             $('#appointment_time').html('<option value="">No slots available</option>');
//         }
//     });
// });

$('#bookingForm').on('submit', function(e) {
    e.preventDefault();

    const formData = {
        _token: '{{ csrf_token() }}',
        user_id: $('#user_id').val(),
        store_id: $('#store_id').val(),
        service_id: $('#service_id').val(),
         dentist_id: $('#dentist_id').val(),
        appointment_date: $('#appointment_date').val(),
        appointment_time: $('#appointment_time').val(),
        desc: $('#desc').val()
    };

    $.ajax({
        url: '{{ route('appointments.storeadmin') }}',
        method: 'POST',
        data: formData,
        success: function(response) {

        if (response.status === 'success') {
            Swal.fire('Success!', response.message, 'success');
            $('#bookingForm')[0].reset();
            $('#appointment_date').prop('disabled', true); 
            $('#appointment_time').html('<option value="">-- Select Date First --</option>').prop('disabled', true);
              $('#storedetail').html(`
        `);

        $('#appointment_date').prop('disabled', false);
        //  window.location.href = '{{ route('appointments.incomplete') }}';   
     
        } else if (response.status === 'error') {
            Swal.fire('Error!', response.message, 'error');
        }
       
      
        },
        error: function(xhr) {
            const errors = xhr.responseJSON.errors;
            let message = 'Error booking appointment.';

            if (errors) {
                message = Object.values(errors).map(e => e.join(', ')).join(' ');
            }

            alert(message);
        }
    });
});
</script>

@endsection
