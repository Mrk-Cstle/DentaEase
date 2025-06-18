@extends('layout.cnav')

@section('title', 'Booking')
@section('main-content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<form id="bookingForm" class="space-y-4">
    @csrf

    <!-- Store Selection -->
    <div>
        <label for="store_id" class="block font-semibold">Select Branch</label>
        <select id="store_id" name="store_id" class="w-full p-2 border rounded" required>
            <option value="">-- Choose Branch --</option>
            @foreach ($stores as $store)
                <option value="{{ $store->id }}">{{ $store->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Date -->
    <div>
        <label for="appointment_date" class="block font-semibold">Select Date</label>
        <input type="date" id="appointment_date" name="appointment_date"
               class="w-full p-2 border rounded" required disabled>
    </div>

    <!-- Time -->
    <div>
        <label for="appointment_time" class="block font-semibold">Select Time</label>
        <select id="appointment_time" name="appointment_time" class="w-full p-2 border rounded" required disabled>
            <option value="">-- Select Date First --</option>
        </select>
    </div>

     <div>
        <label for="desc" class="block font-semibold">Appointment Description</label>
        <textarea class="w-full p-2 border rounded" rows="10" cols="30"  id="desc" name="desc" required></textarea>
    </div>

    <!-- Submit -->
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Book Appointment
    </button>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let openDays = []; // Example: ['mon', 'tue', 'wed']

// Map day string to index (Sunday = 0)
const dayMap = { sun: 0, mon: 1, tue: 2, wed: 3, thu: 4, fri: 5, sat: 6 };

let flatpickrInstance;

$('#store_id').on('change', function () {
    const storeId = $(this).val();
    if (!storeId) return;

    $.get(`/store/${storeId}/schedule`, function (data) {
        if (data.status === 'success') {
            openDays = data.open_days.map(day => dayMap[day]);

            // Init or re-init the date picker
            if (flatpickrInstance) {
                flatpickrInstance.destroy();
            }

            flatpickrInstance = flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                minDate: new Date().fp_incr(2),
                disable: [
                    function (date) {
                        return !openDays.includes(date.getDay());
                    }
                ]
            });

            $('#appointment_date').prop('disabled', false);
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
$('#appointment_date').on('change', function () {
     const selectedDate = $(this).val();
    const storeId = $('#store_id').val();
    const selectedDay = new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'short' }).toLowerCase(); // 'mon'

    // // Validate open day
    // if (!openDays.includes(selectedDay)) {
    //     alert('Store is closed on this day.');
    //     $(this).val('');
    //     $('#appointment_time').html('<option value="">Store closed on this day</option>').prop('disabled', true);
    //     return;
    // }

    $('#appointment_time').html('<option>Loading...</option>').prop('disabled', false);

    // Fetch available slots
    $.get(`/branch/${storeId}/available-slots`, { date: selectedDate }, function (response) {
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

$('#bookingForm').on('submit', function(e) {
    e.preventDefault();

    const formData = {
        _token: '{{ csrf_token() }}',
        store_id: $('#store_id').val(),
        appointment_date: $('#appointment_date').val(),
        appointment_time: $('#appointment_time').val(),
        desc: $('#desc').val()
    };

    $.ajax({
        url: '{{ route('appointments.store') }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            $('#bookingSuccess').text(response.message).removeClass('hidden');
            $('#bookingForm')[0].reset();
            $('#appointment_date').prop('disabled', true); 
            $('#appointment_time').html('<option value="">-- Select Date First --</option>').prop('disabled', true);
            
             Swal.fire('Success!', response.message, 'success');

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