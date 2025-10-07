<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Store;
use App\Models\User;
use App\Mail\AppointmentApprovedMail;
use App\Models\MedicalForm;
use App\Models\medicines;
use App\Models\PatientRecord;
use App\Models\Service;
use App\Models\StoreStaff;
use Illuminate\Support\Facades\Mail;
use App\Notifications\AppointmentNotification;
   use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    //


public function showBookings(Request $request)
{
    $user = auth()->user();

    $stores = Store::all();
    $services = Service::all();
    $clients = User::where('account_type', 'patient')->orderBy('name')->get();

    $query = Appointment::with('user')
        ->where('store_id', session('active_branch_id'))
        ->whereIn('status', ['pending', 'approved']);

    if ($user->position === 'Dentist') {
        $query->where('dentist_id', $user->id);
    }

    if ($user->position === 'Receptionist' && $request->filled('dentist_id')) {
        $query->where('dentist_id', $request->input('dentist_id'));
    }

    if ($request->filled('date')) {
        $query->whereDate('appointment_date', $request->input('date'));
    }

    // === determine column type ===
    $colType = Schema::getColumnType('appointments', 'appointment_date'); // 'date','datetime','timestamp','string', etc.

    if (in_array($colType, ['date','datetime','timestamp'])) {
        // Appointment date is a proper date/datetime type — simple ordering works
        $query->orderBy('appointment_date', 'asc')
              ->orderBy('appointment_time', 'asc');
    } else {
        // appointment_date is stored as a string. We have to convert when ordering.
        // Guess common formats: 'YYYY-MM-DD' or 'MM/DD/YYYY'. Adjust format below if different.
        // If your stored format is YYYY-MM-DD, STR_TO_DATE with '%Y-%m-%d' still works but isn't needed.
        // For time ordering assume 'HH:ii' (24-hour 'H:i'); change '%h:%i %p' for '12:00 AM' format.
        $query->orderByRaw("STR_TO_DATE(appointment_date, '%Y-%m-%d') asc")
              ->orderByRaw("STR_TO_DATE(appointment_time, '%H:%i') asc");
    }

    // optional: log SQL for debugging
    Log::debug('Appointments SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

    $appointments = $query->get();

    // dentist list for receptionist
    $dentists = [];
    if ($user->position === 'Receptionist') {
        $store = Store::find(session('active_branch_id'));
        $dentists = $store->staff()
            ->wherePivot('position', 'dentist')
            ->get(['users.id', 'users.name']);
    }

    return view('admin.booking', compact('appointments', 'dentists','services', 'stores','clients'));
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
     
     $appointment->user->notify(new AppointmentNotification([
    'title' => 'Appointment Approved',
    'message' => 'Your appointment has been approved and updated at '. $appointment->store->name,
    // 'url' => '/messages'
]));
      return response()->json(['message' => 'Appointment approved.']);
}

public function view($id)
{
    // Get the appointment with related user and store
    $appointment = Appointment::with('user', 'store')->findOrFail($id);

    // The user (patient) tied to this appointment
    $patient = $appointment->user;

    // Only completed/finalized appointments for this patient
    $record = $patient->appointment()
        ->whereIn('status', ['completed'])
        ->get();

    // Ensure patient record exists
    $patientinfo = PatientRecord::firstOrCreate(
        ['user_id' => $patient->id],
        ['user_id' => $patient->id]
    );
    $medicines = medicines::get();
    return view('admin.appointment_detail', compact(
        'appointment',
        'record',
        'patient',
        'patientinfo',
        'medicines'
    ));
}

public function settle(Request $request, $id)
{
    $appointment = Appointment::findOrFail($id);

    if ($request->status === 'no_show') {
        $appointment->status = 'no_show';
        $appointment->save();

        return response()->json(['message' => 'Marked as No Show.']);
    }

    // ✅ Validate the full request (not mapped manually)
    $validated = $request->validate([
        'work_done' => 'required|string',
        'paytype' => 'required|string',
        'total_price' => 'required|numeric',
        'payment_receipt' => 'nullable|image|max:2048',
    ]);

    // ✅ Build the data to update
    $data = [
        'work_done' => $validated['work_done'],
        'payment_type' => $validated['paytype'],
        'total_price' => $validated['total_price'],
        'status' => 'completed',
    ];

    // ✅ Handle receipt image upload
    if ($request->hasFile('payment_receipt')) {
        $file = $request->file('payment_receipt');
        $filename = uniqid('receipt_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('payment_receipts', $filename, 'public');
        $data['payment_image'] = $filename; // Make sure column is named `payment_image`
    }

    // ✅ Save to database
    $appointment->update($data);

    return response()->json(['status' => 'success', 'message' => 'Appointment finalized successfully.']);

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
    $appointment->user->notify(new AppointmentNotification([
    'title' => 'Appointment Approved',
    'message' => 'Your appointment at '. $appointment->store->name . ' has been cancelled.',
    // 'url' => '/messages'
]));

    return response()->json(['message' => 'Appointment cancelled.']);
}



public function showHistory(Request $request)
{
    $query = Appointment::with(['user', 'dentist']) // added dentist relation for eager load
        ->where('store_id', session('active_branch_id'))
        ->whereIn('status', ['completed', 'cancelled', 'no_show']);

    if (auth()->user()->position === 'dentist') {
        $query->where('dentist_id', auth()->id());
    }

 
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('appointment_date', [
            $request->input('start_date'),
            $request->input('end_date'),
        ]);
    } elseif ($request->filled('start_date')) {
        // if only start_date, filter from that day onwards
        $query->whereDate('appointment_date', '>=', $request->input('start_date'));
    } elseif ($request->filled('end_date')) {
        // if only end_date, filter up to that day
        $query->whereDate('appointment_date', '<=', $request->input('end_date'));
    }

    $appointments = $query->get();

    return view('admin.booking_history', compact('appointments'));
}


public function modalDetails($id)
{
   
       // get the user (patient)
    $user = User::findOrFail($id);

    // get all completed / finalized appointments of this user
    $completedAppointments = $user->appointment()
        ->whereIn('status', ['completed', 'no_show', 'cancelled'])
        ->get();

    // if you need the latest or specific appointment
    $appointment = $user->appointment()
        ->with('store')
        ->where('status' , 'completed')
        ->latest()
        ->first();

    // livewire dental chart
    $patient = $user;

    // Only completed/finalized appointments for this patient
    $record = $patient->appointment()
        ->whereIn('status', ['completed'])
        ->get();

    $medicines = medicines::get();


    // ensure patientinfo exists
    $patientinfo = PatientRecord::firstOrCreate(
        ['user_id' => $user->id],
        ['user_id' => $user->id]
    );

    return view('admin.partials.usermodaldetail', compact(
        'user',
        'completedAppointments',
        'appointment',
        'record',
        'patient',
        'patientinfo',
        'medicines'
    ));
}
}
