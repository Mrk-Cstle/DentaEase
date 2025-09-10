@foreach($appointments as $appointment)
<tr class="border-t text-center" data-id="{{ $appointment->id }}">
     <td>
    <button onclick="showUserModal({{ $appointment->user->id }})"
        class="text-blue-600 underline">
        {{ $appointment->user->lastname }}, {{ $appointment->user->name ?? 'N/A' }}  {{ $appointment->user->middlename }}.  {{ $appointment->user->suffix ?? '' }}
    </button>
</td>
<td>{{ $appointment->service_name}}</td>
    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
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
    @if ($appointment->status == 'pending')
        <button type="button"
                class="approve-btn bg-green-500 text-white px-3 py-1 rounded"
                data-id="{{ $appointment->id }}">
            Approve
        </button>

        <button type="button"
                class="cancel-btn bg-red-500 text-white px-3 py-1 rounded ml-2"
                data-id="{{ $appointment->id }}">
            Cancel
        </button>

    @elseif ($appointment->status == 'approved')
    <button type="button"
                class="approve-btn bg-green-500 text-white px-3 py-1 rounded"
                data-id="{{ $appointment->id }}">
            Change Time
        </button>
        <a href="{{ route('appointments.view', $appointment->id) }}"
           class="bg-blue-500 text-white px-3 py-1 rounded">
            View
        </a>
    @endif
</td>

</tr>


@endforeach