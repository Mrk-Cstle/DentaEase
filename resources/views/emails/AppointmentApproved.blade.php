<!DOCTYPE html>
<html>
<head>
    <title>Appointment Approved</title>
</head>
<body>
    <h2>Hello {{ $appointment->user->name }},</h2>
    <p>Your appointment has been approved at {{ $appointment->store->name }} Branch</p>
   @php
    use Carbon\Carbon;

    $date = Carbon::parse($appointment->appointment_date)->format('F j, Y'); // e.g. June 23, 2025
    $start = Carbon::parse($appointment->appointment_time)->format('g:i A'); // e.g. 11:50 AM
    $end = Carbon::parse($appointment->booking_end_time)->format('g:i A');   // e.g. 12:20 PM
@endphp

<p><strong>Date:</strong> {{ $date }}</p>
<p><strong>Time:</strong> {{ $start }} to {{ $end }}</p>
    <p>Thank you!</p>
</body>
</html>