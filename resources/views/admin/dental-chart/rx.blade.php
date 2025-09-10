    <!-- Input area hidden in print -->
    <div class="flex gap-2 mb-4 no-print">
        <select id="medicine-select" class="border p-2 rounded w-full">
            <option value="">Select Medicine</option>
            @foreach($medicines as $medicine)
                <option value="{{ $medicine->id }}" data-unit="{{ $medicine->unit }}">{{ $medicine->name }} ({{ $medicine->unit }})</option>
            @endforeach
        </select>
        <input type="text" id="medicine-dose" placeholder="Schedule" class="border p-2 rounded flex-1">
        <button type="button" id="add-medicine" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
    </div>
<div id="receipt-content" class="p-6 max-w-3xl mx-auto bg-white shadow rounded">
    <h1 class="text-center text-xl font-bold mb-4">SANTIAGO-AMANCIO DENTAL CLINIC</h1>

    <p class="text-center">
        {{ ($appointment->store->name ?? 'N/A') }}<br>
        {{ ($appointment->store->address ?? 'N/A') }}<br>
    </p></br>

    <div class="flex justify-between mb-4">
        <div>
            <p><strong>Patient Name:</strong> <span id="patient_name">{{ $appointment->user->lastname ?? 'N/A' }}, {{ $appointment->user->name ?? 'N/A' }} {{ $appointment->user->middlename ?? 'N/A' }} {{ $appointment->user->suffix ?? '' }}</span></p>
            <p><strong>Age:</strong> <span id="patient_age">{{ \Carbon\Carbon::parse($appointment->user->birthdate)->age }}</span></p>
        </div>
        <div>
            <p><strong>Date:</strong> {{ now()->format('F j, Y') }}</p>
        </div>
    </div>

    <hr class="border-2 mb-4">

    <h2 class="font-bold mb-2">Rx</h2>

    <!-- Rx list -->
    <div id="rx-list" class="mb-4">
        {{-- Medicines will be appended here --}}
    </div>



    <div class="text-right">
        <p>______________________________</p>
        <p>{{$appointment->dentist->name}} {{$appointment->dentist->middlename}}. {{$appointment->dentist->lastname}} {{$appointment->dentist->suffix ?? ''}} ,DMD</p>
    </div>
</div>

<div class="text-right mt-4">
    <button onclick="window.printRx()" class="bg-green-600 text-white px-4 py-2 rounded">Print Rx</button>
</div>

<script>
const medicines = @json($medicines);

// Add medicine
document.getElementById('add-medicine').addEventListener('click', function() {
    const select = document.getElementById('medicine-select');
    const doseInput = document.getElementById('medicine-dose');
    const rxList = document.getElementById('rx-list');

    const medId = select.value;
    const dose = doseInput.value;

    if (!medId || !dose) {
        alert('Select a medicine and enter dose/schedule');
        return;
    }

    const med = medicines.find(m => m.id == medId);

    const div = document.createElement('div');
    div.classList.add('mb-2');
    div.innerHTML = `
     <button type="button" class="text-red-500 ml-2 remove-medicine no-print">-</button>
        <strong>${med.name} (${med.unit})</strong>: ${dose} x a Day
       
    `;

    rxList.appendChild(div);

    // Clear inputs
    select.value = '';
    doseInput.value = '';
});

// Remove medicine (delegate)
document.getElementById('rx-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-medicine')) {
        e.target.parentElement.remove();
    }
});

// Print function
window.printRx = function () {
    const receipt = document.getElementById('receipt-content').innerHTML;
    const original = document.body.innerHTML;

    const redirectUrl = "{{ route('appointments.view', ['id' => $appointment->id]) }}";

    document.body.innerHTML = `
        <style>
            @media print {
                @page { margin: 0; }
                body { margin: 20mm; font-family: system-ui, sans-serif; }
                .no-print { display: none !important; }
                #rx-list div { page-break-inside: avoid; }
            }
        </style>
        ${receipt}
    `;

    window.print();

    setTimeout(() => {
        document.body.innerHTML = original;
        window.location.href = redirectUrl; // now redirects correctly after printing
    }, 500);
};
</script>
