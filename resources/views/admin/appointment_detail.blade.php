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
        <button @click="tab='patient'" :class="tab==='patient' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">Patient Information</button>
        <button @click="tab='info'" :class="tab==='info' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">Dental Chart</button>
        <button @click="tab='treatment'" :class="tab==='treatment' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">Treatment Record</button>
        <button @click="tab='rx'" :class="tab==='rx' ? 'text-blue-500 font-bold border-b-2 border-blue-500' : 'text-gray-500'" class="py-2 px-4">RX</button>
    </div>

    <!-- Tab Contents -->
    <div x-show="tab==='checkin'">
        <div class="w-full mx-auto bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Finalize Appointment</h2>
        
            <p><strong>Client:</strong>{{ $appointment->user->lastname ?? 'N/A' }}, {{ $appointment->user->name ?? 'N/A' }} {{ $appointment->user->middlename ?? 'N/A' }} {{ $appointment->user->suffix ?? '' }}</p>
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
        <div id="printable-treatment">
            @include('admin.dental-chart.treatment-record', ['record' => $record])
        </div>
        {{-- <button onclick="printDiv('printable-treatment')" 
            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">
            Print Treatment Record
        </button> --}}
    </div>
    
    <div x-show="tab==='info'" x-cloak>
        <div id="printable-info">
            @include('admin.dental-chart.index', ['patient'=> $patient])
        </div>
     
    </div>
    
    <div x-show="tab==='patient'" x-cloak>
        <div id="printable-patient">
            @include('client.patient_record', ['patient'=> $patient])
        </div>
       
    </div>
    
    <div x-show="tab==='rx'" x-cloak>
        <div id="printable-rx">
            @include('admin.dental-chart.rx', ['medicines'=> $medicines, 'appointment'=>$appointment])
        </div>
        {{-- <button onclick="printDiv('printable-rx')" 
            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">
            Print RX
        </button> --}}
    </div>
</div>

<div 
    x-data="{ openReceiptModal: false }"
    x-cloak
    @open-receipt.window="openReceiptModal = true"
>
    <!-- Trigger button (optional manual open) -->
    {{-- <button @click="openReceiptModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded">
        Print Receipt
    </button> --}}

    <!-- Modal -->
    <div x-show="openReceiptModal"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-[700px] p-6 relative">
            <!-- Close -->
            <button @click="openReceiptModal = false"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">✕</button>

            <!-- Receipt -->
            <div id="receipt-content" class="text-sm font-serif">
                <h2 class="text-center text-lg font-bold">
                    Santiago – Amancio Dental Clinic
                </h2>
                <p class="text-center">
                    {{ ($appointment->store->name ?? 'N/A') }}<br>
                  {{ ($appointment->store->address ?? 'N/A') }}<br>
               
                </p>

                <h3 class="text-center font-bold mt-4 underline">
                    ACKNOWLEDGEMENT RECEIPT
                </h3>

<div class="mt-4 leading-relaxed">
    <p>
        Received from 
        <u>{{ e($appointment->user->name ?? 'N/A') }} {{ ($appointment->user->lastname ?? '') }} {{ e($appointment->user->suffix ?? '') }}</u>
        of 
        <u>{{ $appointment->user->current_address }}</u>,
        the sum of 
        <u><span id="receipt-sum">____________________________</span></u>
        for 
        <u>{{ e($appointment->service_name ?? '') }}</u>
        on 
        <u>{{ now()->format('F j, Y') }}</u>.
    </p>
</div>

                <div class="mt-8 flex justify-between">
                    <span></span>
                <div class="text-center mt-8">
                <p><strong>{{ Auth::user()->name }} {{ Auth::user()->lastname }} {{ Auth::user()->suffix }}</strong></p>
                <p class="text-sm">{{ Auth::user()->position }}</p>
            </div>
                            </div>
            </div>

            <!-- Print -->
            <div class="mt-6 flex justify-end">
                <button onclick="printReceipt()"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Print
                </button>
            </div>
        </div>
    </div>
</div>




{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('input', 'input[name="total_price"]', function () {
    let value = parseFloat($(this).val()) || 0;
    // Format as PHP currency style ₱1,234.56
    let formatted = value > 0 ? `₱${value.toLocaleString('en-PH', { minimumFractionDigits: 2 })}` : '____________________________';
    $('#receipt-sum').text(formatted);
});
window.printReceipt = function () {
    // Get the receipt HTML
    const receipt = document.getElementById('receipt-content').innerHTML;

    // Backup the current page’s body
    const original = document.body.innerHTML;

    // Replace body with only the receipt + print CSS
    document.body.innerHTML = `
        <style>
            @media print {
                @page {
                    margin: 0; /* removes browser header/footer space */
                }
                body {
                    margin: 20mm; /* your custom margin */
                }
            }
        </style>
        ${receipt}
    `;

    // Trigger print
    window.print();

    // Restore original page after a short delay
    setTimeout(() => {
        document.body.innerHTML = original;
        // Redirect to another page after print
        window.location.href = '/appointments'; // change this to your target URL
    }, 500); // 500ms delay to ensure print dialog has started
};
</script>


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
                                    window.dispatchEvent(new CustomEvent('open-receipt'));
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
<script>
    function printDiv(divId) {
        const redirectUrl = "{{ route('appointments.view', ['id' => $appointment->id]) }}";
    
        // Clone the div to preserve structure
        const contentDiv = document.getElementById(divId);
        const clone = contentDiv.cloneNode(true);
    
        // Copy current values for all inputs/selects/textarea
        const originalInputs = contentDiv.querySelectorAll('input, select, textarea');
        const clonedInputs = clone.querySelectorAll('input, select, textarea');
    
        originalInputs.forEach((input, index) => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                clonedInputs[index].checked = input.checked;
            } else {
                clonedInputs[index].value = input.value;
            }
        });
    
        // Backup original page
        const originalBody = document.body.innerHTML;
    
        // Replace body with cloned content + print styles
        document.body.innerHTML = `
        
            <style>
                @media print {
                    @page { margin: 0; }
                    body { margin: 1mm; font-family: system-ui, sans-serif; }
                    .no-print { display: none !important; }
                }
                body { font-family: system-ui, sans-serif; margin: 5mm; }
            </style>
        `;
        document.body.appendChild(clone);
    
        // Trigger print
        window.print();
    
        // Restore original page
        setTimeout(() => {
            // document.body.innerHTML = originalBody;
            // if (window.Alpine) window.Alpine.initTree(document.body);
            window.location.href = redirectUrl;
        }, 200);
    }
    </script>
    

   
@endsection
