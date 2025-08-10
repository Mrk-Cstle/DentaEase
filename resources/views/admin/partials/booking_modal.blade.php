
<div class="flex flex-col md:flex-row md:gap-10">
    <!-- 70% Booking Form -->
    <div class="md:w-[100%]">
        <form id="bookingForm" class="space-y-4">
            @csrf

              {{-- Services --}}
            <div>
                <label for="user_id" class="block font-semibold">Select Patient</label>
                <select id="user_id" name="user_id" class="w-full p-2 border rounded" required>
                    <option value="">-- Choose Patient --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->lastname }}, {{ $client->name }}</option>
                    @endforeach
                </select>
              
            </div>
            <!-- Store Selection -->
            <div>
                <label for="store_id" class="block font-semibold">Select Branch</label>
                <select id="store_id" name="store_id" class="w-full p-2 border rounded" required>
                    <option value="">-- Choose Branch --</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
                <div id="storedetail"></div>
            </div>

            {{-- Services --}}
            <div>
                <label for="service_id" class="block font-semibold">Select Service</label>
                <select id="service_id" name="service_id" class="w-full p-2 border rounded" required>
                    <option value="">-- Choose Service --</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
                <div id="servicedetail"></div>
            </div>
            <!-- Dentist Selection -->
            <div>
                <label for="dentist_id" class="block font-semibold">Select Dentist</label>
                <select id="dentist_id" name="dentist_id" class="w-full p-2 border rounded" required >
                    <option value="">-- Choose Dentist --</option>
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

            <!-- Description -->
            <div>
                <label for="desc" class="block font-semibold">Appointment Description</label>
                <textarea class="w-full p-2 border rounded" rows="10" cols="30" id="desc" name="desc" required></textarea>
            </div>

            <!-- Submit -->
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Book Appointment
            </button>
        </form>
    </div>

  





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
