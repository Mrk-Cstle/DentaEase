@extends('layout.navigation')

@section('title','Appointment Booking')
@section('main-content')

@if(auth()->user()->position === 'Receptionist')
<form method="GET" action="{{ route('admin.booking') }}" class="flex items-end space-x-4 mb-4">
    <div class="flex flex-col">
        <label for="dentist_id" class="mb-1">Filter by Dentist:</label>
        <select name="dentist_id" id="dentist_id" class="border rounded p-2">
            <option value="">-- All Dentists --</option>
            @foreach ($dentists as $dentist)
                <option value="{{ $dentist->id }}" {{ request('dentist_id') == $dentist->id ? 'selected' : '' }}>
                    {{ $dentist->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label for="date" class="mb-1">Filter by Date:</label>
        <input type="date" name="date" id="date" value="{{ request('date') }}" class="border rounded p-2">
    </div>

    <div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </div>
</form>
@endif
@if(auth()->user()->position == 'Dentist')
   <form method="GET" action="{{ route('admin.booking') }}" class="mb-4">
    <label for="date">Filter by Date:</label>
    <input type="date" id="date" name="date" value="{{ request('date') }}">
    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Filter</button>
</form>
@endif

<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold">Appointment Booking</h2>

    <a href="{{ route('admin.booking.history') }}" 
       class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
        View History Logs
    </a>
</div>
<table class="table-auto w-full border-collapse border">
    <thead>
        <tr class="bg-gray-200">
            <th>User</th>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
   <tbody id="appointments-table-body">
    @include('admin.partials.appointments-table', ['appointments' => $appointments])
    
</tbody>
</table>
@include('admin.partials.usermodal')
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

                // ✅ Reload the appointments table body
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

                    // ✅ Reload the table body
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