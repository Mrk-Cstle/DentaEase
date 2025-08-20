@extends('layout.cnav')

@section('title', 'Booking')
@section('main-content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Alpine.js for tab toggle -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <div x-data="{ tab: 'pending' }" class="flex flex-col md:flex-row md:gap-10">
        <div class="md:w-full bg-white p-4 rounded shadow">
            <!-- Tabs -->
            <div class="flex border-b mb-4">
                <button @click="tab = 'pending'"
                    :class="tab === 'pending' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-4 py-2">Pending</button>
                <button @click="tab = 'history'"
                    :class="tab === 'history' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-4 py-2">History</button>
            </div>

            <!-- Pending Tab -->
            <div x-show="tab === 'pending'">
                <h2 class="text-lg font-bold mb-3">Your Pending Appointments</h2>
                @if ($incompleteAppointments->isEmpty())
                    <p class="text-gray-500">You have no upcoming appointments.</p>
                @else
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1">Time</th>
                                <th class="border px-2 py-1">Branch</th>
                                <th class="border px-2 py-1">Dentist</th>
                                <th class="border px-2 py-1">Status</th>
                                <th class="border px-2 py-1">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incompleteAppointments as $appt)
                                <tr>
                                    <td class="border px-2 py-1">
                                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('F j, Y') }}
                                        {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($appt->booking_end_time)->format('h:i A') }}
                                    </td>
                                    <td class="border px-2 py-1">{{ $appt->store->name }}</td>
                                    <td class="border px-2 py-1">{{ $appt->dentist->name ?? 'N/A' }}</td>
                                    <td class="border px-2 py-1">{{ ucfirst($appt->status) }}</td>
                                    <td>
                                        @if ($appt->status !== 'approved' && \Carbon\Carbon::parse($appt->appointment_date)->gt(\Carbon\Carbon::now()->addDays(1)))
                                            <button type="button"
                                                class="cancel-btn bg-red-500 text-white px-3 py-1 rounded ml-2"
                                                data-id="{{ $appt->id }}">
                                                Cancel
                                            </button>
                                        @elseif($appt->status == 'approved')
                                              <button type="button"
                                                class="cancel-btn bg-red-200 text-white px-3 py-1 rounded ml-2"
                                                data-id="{{ $appt->id }}" disabled>
                                                Cancel
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- History Tab -->
            <div x-show="tab === 'history'" x-cloak>
                <h2 class="text-lg font-bold mb-3">Appointment History</h2>
                @if ($completedAppointments->isEmpty())
                    <p class="text-gray-500">You have no completed appointments.</p>
                @else
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1">Date</th>
                                <th class="border px-2 py-1">Branch</th>
                                <th class="border px-2 py-1">Dentist</th>
                                <th class="border px-2 py-1">Service</th>
                                <th class="border px-2 py-1">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($completedAppointments as $appt)
                                <tr>
                                    <td class="border px-2 py-1">
                                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('F j, Y') }}
                                        {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                                    </td>
                                    <td class="border px-2 py-1">{{ $appt->store->name }}</td>
                                    <td class="border px-2 py-1">{{ $appt->dentist->name ?? 'N/A' }}</td>
                                    <td class="border px-2 py-1">{{ $appt->service_name ?? 'N/A' }}</td>
                                    <td class="border px-2 py-1">{{ ucfirst($appt->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('click', '.cancel-btn', function() {
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
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: 'Appointment has been cancelled.'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
