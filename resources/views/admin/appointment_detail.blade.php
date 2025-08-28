@extends('layout.navigation')

@section('title', 'Appointment Details')

@section('main-content')


<style>
    [x-cloak] { display: none !important; }
</style>
<div x-data="{ tab: 'checkin' }" class="p-6">

    <h1 class="text-2xl font-bold mb-4">Appointment #{{ $appointment->id }}</h1>

    <!-- Tabs -->
    <div class="flex border-b mb-4">
        <button @click="tab='checkin'" :class="tab==='checkin' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">Check-in</button>
        <button @click="tab='info'" :class="tab==='info' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">Dental Chart</button>
        <button @click="tab='treatment'" :class="tab==='treatment' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">Treatment Record</button>
    </div>

    <!-- Tab Contents -->
    <div x-show="tab==='checkin'">
        <div class="w-full mx-auto bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Finalize Appointment</h2>
        
            <p><strong>Client:</strong>{{ $appointment->user->lastname ?? 'N/A' }}, {{ $appointment->user->name ?? 'N/A' }} {{ $appointment->user->middlename ?? 'N/A' }} {{ $appointment->user->suffix ?? 'N/A' }}</p>
            <p><strong>Dentist:</strong> {{ $appointment->dentist->name ?? 'N/A' }}</p>
        
            @php
                use Carbon\Carbon;
        
                $date = Carbon::parse($appointment->appointment_date)->format('F j, Y');
                $start = Carbon::parse($appointment->appointment_time)->format('g:i A');
                $end = Carbon::parse($appointment->booking_end_time)->format('g:i A');
            @endphp
        
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Time:</strong> {{ $start }} - {{ $end }}</p>
            <p><strong>Branch:</strong> {{ $appointment->store->name ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {{ $appointment->desc }}</p>
        
            <form id="finalizeAppointmentForm" data-id="{{ $appointment->id }}" enctype="multipart/form-data" method="POST">
                @csrf
        
                <div class="mt-4">
                    <label class="block font-semibold">Work Done</label>
                    <textarea name="work_done" rows="4" class="w-full border rounded p-2" required></textarea>
                </div>
        
                <div class="mt-4">
                    <p><strong>Service Done:</strong> {{ $appointment->service_name }}</p>
        
                    <label class="block font-semibold">Payment Type</label>
                    <select class="w-full border rounded p-2" name="paytype" id="paytype" required>
                        <option value="GCASH">GCASH</option>
                        <option value="CASH">CASH</option>
                    </select>
                </div>
        
                <div class="mt-4">
                    <label class="block font-semibold">Total Price (₱)</label>
                    <input type="number" name="total_price" step="0.01" min="0" class="w-full border rounded p-2" required>
                </div>
        
                <div class="mt-4">
                    <label class="block font-semibold">Upload Payment Receipt</label>
                    <input type="file" name="payment_receipt" accept="image/*" class="w-full border rounded p-2" id="payment_receipt_input">
                    
                    <!-- Image preview -->
                    <div class="mt-2">
                        <img id="receipt_preview" src="#" alt="Receipt Preview" class="max-w-xs rounded border hidden" />
                    </div>
                </div>
        
                <input type="hidden" name="status" id="status" value="completed">
        
                <div class="mt-6 flex flex-row gap-5">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded" data-status="completed">Complete</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded" data-status="no_show">No Show</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="tab==='treatment'" x-cloak>
        @include('admin.dental-chart.treatment-record', ['record' => $record])
    </div>

    <div x-show="tab==='info'" x-cloak>
        @include('admin.dental-chart.index', ['patient'=> $patient])
    </div>

</div>


{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
                $('#payment_receipt_input').on('change', function (event) {
            const [file] = this.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#receipt_preview')
                        .attr('src', e.target.result)
                        .removeClass('hidden'); // show preview
                };
                reader.readAsDataURL(file);
            }
        });
        $('#finalizeAppointmentForm button[type="submit"]').on('click', function (e) {
            e.preventDefault();

            const button = $(this);
            const status = button.data('status');
            $('#status').val(status); // Set hidden input

            const form = $('#finalizeAppointmentForm')[0];
            const id = $(form).data('id');
            const formData = new FormData(form);

            let confirmText = status === 'no_show'
                ? 'Are you sure you want to mark this appointment as No Show?'
                : 'Are you sure you want to finalize this appointment?';

            Swal.fire({
                title: 'Confirm Action',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/appointments/${id}/settle`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            Swal.fire('Success', res.message ?? 'Done!', 'success')
                                .then(() => {
                                    window.location.href = "{{ route('admin.booking') }}";
                                });
                        },
                        error: function () {
                            Swal.fire('Error', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
