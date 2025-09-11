<div class="p-6 bg-gray-100 min-h-screen" id="printable-area">
     @if (auth()->user()->account_type == 'admin')
    <button onclick="window.print()" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">
        Print
    </button>
    @endif

    <div class="bg-white p-6 shadow rounded">
        <p><strong>Name: </strong>{{ $patientinfo->user->lastname }}, {{ $patientinfo->user->name }} {{ $patientinfo->user->middlename }} {{ $patientinfo->user->suffix ?? '' }}</p>
        <p><strong>Address: </strong>{{ $patientinfo->user->current_address }}</p>
        <p><strong>Birthdate: </strong>{{ \Carbon\Carbon::parse($patientinfo->user->birth_date)->format('M d, Y') }}</p>
        <p><strong>Contact Number: </strong>{{ $patientinfo->user->contact_number }}</p>
        <p><strong>Email: </strong>{{ $patientinfo->user->email }}</p>

        <h1 class="text-2xl font-bold mb-6 text-center">Treatment Record</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 text-left">Date</th>
                        <th class="py-2 px-4 text-left">Procedure</th>
                        <th class="py-2 px-4 text-left">Work Done</th>
                        <th class="py-2 px-4 text-left">Dentist</th>
                        <th class="py-2 px-4 text-left">Branch</th>
                        <th class="py-2 px-4 text-left">Amount Charged</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($record as $r)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">
                                {{ $r->appointment_date ? \Carbon\Carbon::parse($r->appointment_date)->format('M d, Y') : '-' }}
                            </td>
                            <td class="py-2 px-4">{{ $r->desc ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $r->work_done ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $r->dentist->name ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $r->branch ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $r->total_price ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No treatment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {

    @page {
                    margin: 0; /* removes browser header/footer space */
                }
    body * {
        visibility: hidden; /* hide everything */
      
    }
    #printable-area, #printable-area * {
        visibility: visible; /* show only printable area */
    }
    #printable-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        padding: 0;
        margin: 0;
    }
    .print\\:hidden {
        display: none !important;
    }
    /* Optional: remove colors for better print */
    table, th, td {
        color: black !important;
        border: 1px solid black !important;
    }
    body {
        -webkit-print-color-adjust: exact; /* preserve colors if needed */
    }
}
</style>
