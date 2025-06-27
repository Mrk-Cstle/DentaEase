@extends('layout.cnav')

@section('title', 'Booking')
@section('main-content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
.card-selectable {
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.card-selectable:hover {
    border-color: #3182ce;
}

.card-selected {
    border-color: #3182ce;
    background-color: #ebf8ff;
}
</style>

<form id="bookingForm" class="space-y-4">
    @csrf

    <!-- Step 1: Branch Selection -->
    <div id="step1" class="space-y-4">
        <h2 class="text-xl font-bold mb-2">Select a Branch</h2>
        <input type="hidden" name="store_id" id="store_id" required>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($stores as $store)
                <div class="card-selectable border rounded p-4 shadow hover:shadow-lg" data-id="{{ $store->id }}">
                    <h3 class="text-lg font-bold">{{ $store->name }}</h3>
                    <p>{{ $store->address }}</p>
                </div>
            @endforeach
        </div>

        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded" onclick="goToStep(2)">Next</button>
        <div id="storedetail"></div>
    </div>

 <!-- Step 2: Service Selection -->
<div id="step2" class="space-y-4 hidden">
    <h2 class="text-xl font-bold mb-2">Select a Service</h2>
    <input type="hidden" name="service_id" id="service_id" required>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($services as $service)
        <div class="card-selectable border rounded p-4 shadow hover:shadow-lg" data-id="{{ $service->id }}">
            <div class="flex justify-center mb-2">
                <img class="w-[200px] h-auto" src="{{ asset('storage/service_images/' . $service->image) }}" alt="Verification ID" />
            </div>
            <h3 class="text-lg font-bold">{{ $service->name }}</h3>
            <p>{{ $service->description }}</p>
            <p>Approx. Time: {{ $service->approx_time }}mins</p>
            <p>Approx. Price: ₱{{ $service->approx_price }}</p>
        </div>
    @endforeach
</div>


    <textarea class="w-full p-2 border rounded" rows="5" id="desc" name="desc" required placeholder="Describe your concern..."></textarea>

    <div class="flex justify-between">
        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="goToStep(1)">Back</button>
        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded" onclick="goToStep(3)">Next</button>
    </div>
    <div id="servicedetail"></div>
</div>

    <!-- Step 3: Dentist Selection -->
    <div id="step3" class="space-y-4 hidden">
        <h2 class="text-xl font-bold mb-2">Select a Dentist</h2>
        <input type="hidden" name="dentist_id" id="dentist_id" required>
        <div id="dentistCards" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
    
        <div class="flex justify-between">
            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="goToStep(2)">Back</button>
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded" onclick="goToStep(4)">Next</button>
        </div>
    </div>

    <!-- Step 4: Date & Time -->
    <!-- Step 4: Date & Time -->
<div id="step4" class="space-y-4 hidden">
    <h2 class="text-xl font-bold mb-2">Choose Date & Time</h2>
    <input type="date" id="appointment_date" name="appointment_date" class="w-full p-2 border rounded" required disabled>
    <select id="appointment_time" name="appointment_time" class="w-full p-2 border rounded" required disabled>
        <option value="">-- Select Date First --</option>
    </select>

    <div class="flex justify-between">
        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="goToStep(3)">Back</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Book Appointment</button>
    </div>
</div>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function goToStep(step) {
    for (let i = 1; i <= 4; i++) {
        $('#step' + i).addClass('hidden');
    }
    $('#step' + step).removeClass('hidden');
}

$(document).on('click', '.card-selectable', function () {
    $(this).siblings().removeClass('card-selected');
    $(this).addClass('card-selected');
    const id = $(this).data('id');

    if ($(this).closest('#step1').length) {
        $('#store_id').val(id).trigger('change');
    }
    if ($(this).closest('#step2').length) {
        $('#service_id').val(id).trigger('change');
    }
    if ($(this).closest('#step3').length) {
        $('#dentist_id').val(id).trigger('change');
    }
});

let openDays = [];
const dayMap = { sun: 0, mon: 1, tue: 2, wed: 3, thu: 4, fri: 5, sat: 6 };
let flatpickrInstance;

$('#store_id').on('change', function () {
    const storeId = $(this).val();
    if (!storeId) return;

    $.get(`/store/${storeId}/schedule`, function (data) {
        if (data.status === 'success') {
            const dayNameToNumber = dayMap;
            const dayNameToLabel = {
                sun: 'Sunday', mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday',
                thu: 'Thursday', fri: 'Friday', sat: 'Saturday'
            };

            openDays = (data.open_days || []).map(day => dayNameToNumber[day.toLowerCase()]);
            const readableDays = (data.open_days || []).map(day => dayNameToLabel[day.toLowerCase()]).join(', ');

            if (flatpickrInstance) flatpickrInstance.destroy();

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

            // Load dentists
            $.get(`/branch/${storeId}/dentists`, function (response) {
                let cards = '';
                if (response.dentists.length > 0) {
                    response.dentists.forEach(dentist => {
                        cards += `<div class="card-selectable border rounded p-4 shadow hover:shadow-lg" data-id="${dentist.id}">
                                    <h3 class="text-lg font-bold">${dentist.name}</h3>
                                  </div>`;
                    });
                } else {
                    cards = '<p>No dentists available.</p>';
                }
                $('#dentistCards').html(cards);
            });
        }
    });
});

$('#service_id').on('change', function () {
    const serviceId = $(this).val();
    $.get(`/service/${serviceId}`, function (servdata) {
        if (servdata.status === 'success') {
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

$('#dentist_id').on('change', function () {
    $('#appointment_date').val("");
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

$('#bookingForm').on('submit', function(e) {
    e.preventDefault();

    const formData = {
        _token: '{{ csrf_token() }}',
        store_id: $('#store_id').val(),
        service_id: $('#service_id').val(),
        dentist_id: $('#dentist_id').val(),
        appointment_date: $('#appointment_date').val(),
        appointment_time: $('#appointment_time').val(),
        desc: $('#desc').val()
    };

    $.ajax({
        url: '{{ route('appointments.store') }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            Swal.fire('Success!', response.message, 'success');
            $('#bookingForm')[0].reset();
            goToStep(1);
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
