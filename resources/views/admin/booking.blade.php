@extends('layout.navigation')

@section('title','Appointment Booking')
@section('main-content')
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
   <tbody>
    @foreach($appointments as $appointment)
        <tr class="border-t text-center" data-id="{{ $appointment->id }}">
            <td>{{ $appointment->user->name ?? 'N/A' }}</td>
            <td>{{ $appointment->appointment_date }}</td>

            <td>
                <input type="time" name="appointment_time"
                       class="appointment-time"
                       value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}"
                       required>
            </td>

            <td>
                <input type="time" name="booking_end_time"
                       class="booking-end-time"
                       value="{{ \Carbon\Carbon::parse($appointment->booking_end_time)->format('H:i') }}"
                       required>
            </td>

            <td>{{ $appointment->desc }}</td>
            <td class="status">{{ ucfirst($appointment->status) }}</td>
            <td>
                @if ($appointment->status == "pending")
                     <button type="button"
                        class="approve-btn bg-green-500 text-white px-3 py-1 rounded"
                        data-id="{{ $appointment->id }}">
                    Approve
                </button>
              
                    
                @elseif ($appointment->status == "approved")
                    <a href="{{ route('appointments.view', $appointment->id) }}"
                    class="bg-blue-500 text-white px-3 py-1 rounded">
                    View
                    </a>
                    @else
                 
                @endif
             
               
            </td>
        </tr>
    @endforeach
</tbody>
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                row.find('.status').text('Approved');
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
</script>
@endsection