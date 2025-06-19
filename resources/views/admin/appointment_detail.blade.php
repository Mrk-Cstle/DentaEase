@extends('layout.navigation')

@section('title','Appoinment Details')
@section('main-content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Finalize Appointment</h2>

    <p><strong>Client:</strong> {{ $appointment->user->name ?? 'N/A' }}</p>
   @php
    use Carbon\Carbon;

    $date = Carbon::parse($appointment->appointment_date)->format('F j, Y');
    $start = Carbon::parse($appointment->appointment_time)->format('g:i A'); // e.g. 11:50 AM
    $end = Carbon::parse($appointment->booking_end_time)->format('g:i A');   // e.g. 12:20 PM
@endphp

<p><strong>Date:</strong> {{ $date }}</p>
<p><strong>Time:</strong> {{ $start }} - {{ $end }}</p>
    <p><strong>Branch:</strong> {{ $appointment->store->name ?? 'N/A' }}</p>
    <p><strong>Description:</strong> {{ $appointment->desc }}</p>

   <form id="finalizeAppointmentForm" data-id="{{ $appointment->id }}">
    @csrf

    <div class="mt-4">
        <label class="block font-semibold">Work Done</label>
        <textarea name="work_done" rows="4" class="w-full border rounded p-2" required></textarea>
    </div>

    <div class="mt-4">
        <label class="block font-semibold">Total Price (₱)</label>
        <input type="number" name="total_price" step="0.01" min="0" class="w-full border rounded p-2" required>
    </div>

    <div class="mt-6">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Finalize</button>
    </div>
</form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
$(document).ready(function () {
    $('#finalizeAppointmentForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const id = form.data('id');
        const formData = form.serialize();

        Swal.fire({
            title: 'Finalize Appointment?',
            text: 'Are you sure you want to finalize this appointment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, finalize it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/appointments/${id}/settle`, // Your route
                    method: 'POST',
                    data: formData,
                    success: function (res) {
                        Swal.fire('Success', 'Appointment finalized!', 'success')
                            .then(() => {
                                window.location.href = "{{ route('admin.booking') }}";
                            });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });
});
</script>


@endsection
