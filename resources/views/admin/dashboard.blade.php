@extends('layout.navigation')

@section('title','Dashboard')
@section('main-content')

<!-- Content -->
<div class="p-6 overflow-y-auto">
    @if (auth()->user()->position != 'Receptionist')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-md border border-gray-200 p-6 shadow-md hover:shadow-lg transition duration-300">
            <div class="flex justify-between mb-6">
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold text-primary">{{ \App\Models\User::where('account_type', 'admin')->count() }}</div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">Staffs</div>
                </div>
            </div>
            <a href="/useraccount" class="text-accent font-medium text-sm hover:underline">View</a>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-6 shadow-md hover:shadow-lg transition duration-300">
            <div class="flex justify-between mb-4">
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold text-primary">{{ \App\Models\newuser::where('account_type', 'patient')->count() }}</div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">New User For Approval</div>
                </div>
            </div>
            <a href="/userverify" class="text-accent font-medium text-sm hover:underline">View</a>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-6 shadow-md hover:shadow-lg transition duration-300">
            <div class="flex justify-between mb-6">
                <div>
                    <div class="text-2xl font-semibold mb-1 text-primary">{{ \App\Models\User::where('account_type', 'patient')->count() }}</div>
                    <div class="text-sm font-medium text-gray-400">Patients</div>
                </div>
            </div>
            <a href="/patientaccount" class="text-accent font-medium text-sm hover:underline">View</a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="p-6 relative flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50 dark:bg-gray-800 w-full shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Expiring Soon Inventory</h3>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 py-3 text-xs uppercase font-semibold text-left">Item</th>
                                <th class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 py-3 text-xs uppercase font-semibold text-left">Stocks</th>
                                <th class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 py-3 text-xs uppercase font-semibold text-left min-w-140-px">Expiration</th>
                            </tr>
                        </thead>
                      <tbody>
                        @forelse ($expiringSoon as $batch)
                            <tr class="text-gray-700 dark:text-gray-100">
                                <th class="border-t-0 px-4 py-4 text-left text-xs">
                                    {{ $batch->medicine->name }}
                                </th>
                                <td class="border-t-0 px-4 py-4 text-xs">
                                    {{ $batch->quantity }}
                                </td>
                                <td class="border-t-0 px-4 py-4 text-xs">
                                    Exp: {{ \Carbon\Carbon::parse($batch->expiry_date)->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500 text-xs">
                                    No medicines expiring soon 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 shadow-md p-6 rounded-md">
            <div class="flex justify-between mb-4 items-start">
                <div class="font-medium">Appointment Today</div>
            </div>
            <div class="overflow-hidden">
                <table class="w-full min-w-[540px]">
                    <tbody>
                        @forelse ($appointmentsToday as $appointment)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-100">
                                <div class="flex items-center">
                                    <a href="#" class="text-gray-600 text-sm font-medium hover:text-primary ml-2 truncate">
                                        {{ $appointment->user->name ?? 'Unknown' }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-100">
                                <span class="text-[13px] font-medium text-gray-400">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('m-d-Y') }} {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-100">
                                <span class="text-[13px] font-medium text-gray-400">
                                    {{ $appointment->service_name }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-2 px-4 text-center text-gray-400 text-sm">No appointments for today.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white border border-gray-200 shadow-md p-6 rounded-md lg:col-span-2">
            <div class="flex items-center gap-2">
                <div class="font-medium">Appointment Count</div>
                <select id="appointmentFilter" class="ml-4 border rounded px-2 py-1 text-sm text-gray-600">
                    <option value="daily">Today</option>
                    <option value="weekly">This Week</option>
                    <option value="monthly">This Month</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="rounded-md border border-dashed border-gray-200 p-4">
                    <div class="text-xl font-semibold text-primary" id="active-count">0</div>
                    <span class="text-gray-400 text-sm">Active</span>
                </div>
                <div class="rounded-md border border-dashed border-gray-200 p-4">
                    <div class="text-xl font-semibold text-primary" id="completed-count">0</div>
                    <span class="text-gray-400 text-sm">Completed</span>
                </div>
                <div class="rounded-md border border-dashed border-gray-200 p-4">
                    <div class="text-xl font-semibold text-primary" id="canceled-count">0</div>
                    <span class="text-gray-400 text-sm">Canceled</span>
                </div>
                <div class="rounded-md border border-dashed border-gray-200 p-4">
                    <div class="text-xl font-semibold text-primary" id="noshow-count">0</div>
                    <span class="text-gray-400 text-sm">No Show</span>
                </div>
            </div>
            <div>
                <canvas id="order-chart"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- End Content -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function loadAppointmentStats(filter = 'daily') {
    $.ajax({
        url: '/dashboard/appointment-stats',
        data: { filter: filter },
        success: function(data) {
            $('#active-count').text(data.active);
            $('#completed-count').text(data.completed);
            $('#canceled-count').text(data.canceled);
            $('#noshow-count').text(data.nowshow);
        }
    });
}

$('#appointmentFilter').on('change', function () {
    const selected = $(this).val();
    loadAppointmentStats(selected);
});

// Initial load
$(document).ready(function () {
    loadAppointmentStats();
});
</script>

@endsection
