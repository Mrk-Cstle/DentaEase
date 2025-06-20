@extends('layout.navigation')

@section('title','Appointment Booking')
@section('main-content')

<div class="mb-4">
    <a href="{{ route('admin.booking') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Back to Bookings
    </a>
</div>

<form method="GET" action="{{ route('admin.booking.history') }}" class="mb-4 flex space-x-4">
    <div>
        <label for="date">Filter by Date:</label>
        <input type="date" name="date" id="date" value="{{ request('date') }}" class="border rounded p-2">
    </div>
    <div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded">Filter</button>
    </div>
</form>

<table class="table-auto w-full border-collapse border">
    <thead class="bg-gray-200">
        <tr>
            <th>User</th>
            <th>Dentist</th>
           
            <th>Date&Time</th>
           
            <th>Description</th>
      
            <th>Procedure</th>
            <th>Price</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($appointments as $appointment)
            <tr class="border-t text-center">
                <td>{{ $appointment->user->name ?? 'N/A' }}</td>
               <td>{{ $appointment->dentist->name ?? 'N/A' }}</td>
          
                <td>{{ $appointment->appointment_time }} - {{ $appointment->booking_end_time }}</td>
             
                <td>{{ $appointment->desc }}</td>
               
                <td>{{ $appointment->work_done }}</td>
                <td>{{ $appointment->total_price }}</td>
                <td>{{ ucfirst($appointment->status) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-gray-500 py-4">No booking history found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
