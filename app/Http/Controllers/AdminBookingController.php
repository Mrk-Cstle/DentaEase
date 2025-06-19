<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Store;
use App\Mail\AppointmentApprovedMail;
use Illuminate\Support\Facades\Mail;

class AdminBookingController extends Controller
{
    //
    public function showBookings()
{   
     $query = Appointment::with('user');
        $query->where('store_id', session('active_branch_id'));
      $appointments = $query->get();
    return view('admin.booking', compact('appointments'));
}

public function approveBooking(Request $request, $id)
{
    $request->validate([
        'appointment_time' => 'required|date_format:H:i',
        'booking_end_time' => 'required|date_format:H:i|after:appointment_time',
    ]);

    $appointment = Appointment::findOrFail($id);

    $appointment->update([
        'appointment_time' => $request->appointment_time,
        'booking_end_time' => $request->booking_end_time,
        'status' => 'approved',
    ]);
     Mail::to($appointment->user->email)->send(new AppointmentApprovedMail($appointment));
      return response()->json(['message' => 'Appointment approved.']);
}

public function view($id)
{
    $appointment = Appointment::with('user', 'store')->findOrFail($id);
    return view('admin.appointment_detail', compact('appointment'));
}
public function settle(Request $request, $id)
{
    $request->validate([
        'work_done' => 'required|string',
        'total_price' => 'required|numeric|min:0',
    ]);
    
    $appointment = Appointment::findOrFail($id);
    $appointment->work_done = $request->work_done;
    $appointment->total_price = $request->total_price;
    $appointment->status = 'completed';
    $appointment->save();

   return response()->json(['message' => 'Appointment finalized successfully.']);
}



}
