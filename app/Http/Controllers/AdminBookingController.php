<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Store;
use App\Models\User;
use App\Mail\AppointmentApprovedMail;
use App\Models\StoreStaff;
use Illuminate\Support\Facades\Mail;

class AdminBookingController extends Controller
{
    //
    public function showBookings(Request $request)
{
      $user = auth()->user();

    $query = Appointment::with('user');

    $query->where('store_id', session('active_branch_id'))
        ->whereIn('status', ['pending', 'approved']);

    // 🧠 If the user is a dentist, only show their own appointments
    if ($user->position === 'Dentist') {
        $query->where('dentist_id', $user->id);
    }

    // 🧠 If receptionist, allow filtering by dentist_id
    if ($user->position === 'Receptionist' && $request->filled('dentist_id')) {
        $query->where('dentist_id', $request->input('dentist_id'));
    }

    // 📅 Optional date filter
    if ($request->filled('date')) {
        $query->whereDate('appointment_date', $request->input('date'));
    }

    $appointments = $query->get();

    // 🧑‍⚕️ For receptionist, get all dentists from current branch


  $dentists = [];
    if ($user->position === 'Receptionist') {
        $store = Store::find(session('active_branch_id'));
        $dentists = $store->staff()
                ->wherePivot('position', 'dentist')
                ->get(['users.id', 'users.name']);
    }

    return view('admin.booking', compact('appointments', 'dentists'));
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


public function fetch()
{
    $appointments = Appointment::with('user')
        ->where('store_id', session('active_branch_id'))
        ->where('dentist_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->get();

    return view('admin.partials.appointments-table', compact('appointments'));
}
public function cancelBooking($id)
{
    $appointment = Appointment::findOrFail($id);

    if ($appointment->status !== 'pending') {
        return response()->json(['message' => 'Only pending appointments can be cancelled.'], 400);
    }

    $appointment->status = 'cancelled';
    $appointment->save();

    return response()->json(['message' => 'Appointment cancelled.']);
}



public function showHistory(Request $request)
{
    $query = Appointment::with('user')
        ->where('store_id', session('active_branch_id'))
        ->whereIn('status', ['completed', 'cancelled']); // adjust as needed

    if (auth()->user()->position === 'dentist') {
        $query->where('dentist_id', auth()->id());
    }

    if ($request->filled('date')) {
        $query->whereDate('appointment_date', $request->input('date'));
    }

    $appointments = $query->get();

    return view('admin.booking_history', compact('appointments'));
}

public function modalDetails($id)
{
    $user = User::findOrFail($id);
    $completedAppointments = $user->Appointment->where('status', 'completed');

    return view('admin.partials.usermodaldetail', compact('user', 'completedAppointments'));
}
}
