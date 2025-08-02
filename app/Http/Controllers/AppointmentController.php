<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Store;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AppointmentController extends Controller
{
    //

    public function getSchedule(Store $store)
{
    return response()->json([
        'status' => 'success',
        'name' => $store->name,
        'address' => $store->address,
        'opening_time' => optional($store->opening_time)->format('H:i'),
        'closing_time' => optional($store->closing_time)->format('H:i'),
        'open_days' => $store->open_days ?? [],
    ]);
}

public function getServiceDetail(Service $service)
{
    return response()->json([
        'status' => 'success',
        'name' => $service->name,
        'desc' => $service->description,
        'type' => $service->type,
        'time' => $service->approx_time,
        'price' => $service->approx_price,

       
    ]);
}
public function getDentists($branchId)
{
    $store = Store::find($branchId);

    if (!$store) {
        return response()->json([
            'status' => 'error',
            'message' => 'Store not found'
        ], 404);
    }

    $dentists = $store->staff()
        ->wherePivot('position', 'dentist')
        ->get(['users.id', 'users.name','users.lastname', 'users.contact_number','users.profile_image']); // columns from users table

    return response()->json([
        'status' => 'success',
        'dentists' => $dentists,
    ]);
}
public function getDentistSlots($branchId, $dentistId, Request $request)
{
    $date = $request->input('date');

    if (!$date || !strtotime($date)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid date provided.'
        ], 400);
    }

    $store = Store::findOrFail($branchId);
    $dentist = User::findOrFail($dentistId);

    $dayName = strtolower(Carbon::parse($date)->format('D'));

    // Check if the store is open that day
    if (!in_array($dayName, $store->open_days ?? [])) {
        return response()->json(['slots' => []]); // Store closed
    }

    $opening = Carbon::parse($store->opening_time);
    $closing = Carbon::parse($store->closing_time);
    $slotDuration = 30; // minutes

    // Fetch bookings for this dentist on this date
    $bookings = Appointment::where('store_id', $store->id)
        ->where('dentist_id', $dentistId)
        ->where('appointment_date', $date)
        ->orderBy('appointment_time')
        ->get(['appointment_time', 'booking_end_time']);

    $availableSlots = [];
    $currentSlot = $opening->copy();

    while ($currentSlot->lt($closing)) {
        $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);

        // Check if this slot overlaps with any of the dentist's bookings
        $overlapping = $bookings->first(function ($booking) use ($currentSlot, $slotEnd) {
            $bookingStart = Carbon::parse($booking->appointment_time);
            $bookingEnd = Carbon::parse($booking->booking_end_time);
            return $currentSlot->lt($bookingEnd) && $slotEnd->gt($bookingStart);
        });

        if (!$overlapping) {
            $availableSlots[] = $currentSlot->format('H:i');
            $currentSlot = $slotEnd;
        } else {
            // Skip to end of overlapping booking
            $currentSlot = Carbon::parse($overlapping->booking_end_time)->addMinutes(20);
        }
    }

    return response()->json([
        'status' => 'success',
        'slots' => $availableSlots
    ]);
}


public function getAvailableSlots(Request $request, Store $store)
{
    $date = $request->input('date');

    if (!$date) {
        return response()->json(['error' => 'Date is required'], 422);
    }

    $dayName = strtolower(Carbon::parse($date)->format('D'));

    // Check if store is open that day
    if (!in_array($dayName, $store->open_days ?? [])) {
        return response()->json(['slots' => []]); // store closed
    }

    $opening = Carbon::parse($store->opening_time);
    $closing = Carbon::parse($store->closing_time);
    $slotDuration = 30; // minutes

    // Get all bookings on that day
    $bookings = Appointment::where('store_id', $store->id)
        ->where('appointment_date', $date)
        ->orderBy('appointment_time')
        ->get(['appointment_time', 'booking_end_time']);

    $availableSlots = [];
    $currentSlot = $opening->copy();

    while ($currentSlot->lt($closing)) {
        $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);

        // Check if this slot overlaps with any existing booking
        $overlapping = $bookings->first(function ($booking) use ($currentSlot, $slotEnd) {
            $bookingStart = Carbon::parse($booking->appointment_time);
            $bookingEnd = Carbon::parse($booking->booking_end_time);
            return $currentSlot->lt($bookingEnd) && $slotEnd->gt($bookingStart);
        });

        if (!$overlapping) {
            $availableSlots[] = $currentSlot->format('H:i');
            $currentSlot = $slotEnd; // Continue after this slot
        } else {
            // Skip to end of overlapping booking
            $currentSlot = Carbon::parse($overlapping->booking_end_time);
        }
    }

    return response()->json(['slots' => $availableSlots]);
}


public function appointment(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
        'service_id' => 'required|exists:services,id',
        'dentist_id' => 'required|exists:users,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|date_format:H:i',
        'desc' =>'required',
        
    ]);
    
    $store = Store::findOrFail($request->store_id);

    $service = Service::findOrFail($request->service_id);

    // ✅ Check if store is open that day
    $day = strtolower(Carbon::parse($request->appointment_date)->format('D'));
    if (!in_array($day, $store->open_days ?? [])) {
        return back()->withErrors(['appointment_date' => 'Store is closed on this day.']);
    }

   $booking_end = Carbon::parse($request->appointment_time)->addMinutes($service->approx_time);

    // ✅ Check if time is within hours
    if (
        $request->appointment_time < $store->opening_time->format('H:i') ||
        $request->appointment_time > $store->closing_time->format('H:i')
    ) {
        return back()->withErrors(['appointment_time' => 'Time is outside of store hours.']);
    }

    // ✅ Check if time slot is already taken
 $alreadyBooked = Appointment::where('store_id', $store->id)
    ->where('dentist_id', $request->dentist_id) // ✅ check per dentist
    ->where('appointment_date', $request->appointment_date)
    ->where('appointment_time', $request->appointment_time)
    ->exists();

    if ($alreadyBooked) {
        return back()->withErrors(['appointment_time' => 'This time slot is already booked.']);
    }

    if ($booking_end->format('H:i') > $store->closing_time->format('H:i')) {
    return back()->withErrors(['appointment_time' => 'Booking ends after store closing time.']);
    }

   
    $userHasBooking = Appointment::where('user_id', auth()->id())
    ->where('appointment_date', $request->appointment_date)
    ->exists();

if ($userHasBooking) {
    return response()->json(['status'=>'success','message' =>'You already have a booking on this day.']);
    #return back()->withErrors(['appointment_date' => 'You already have a booking on this day.']);
}
    // ✅ Create the appointment
    Appointment::create([
        'store_id' => $store->id,
        'user_id' => auth()->id(), // assumes you're logged in
        'dentist_id' => $request->dentist_id,
        'service_name' => $service->name,
        'appointment_date' => $request->appointment_date,
        'appointment_time' => $request->appointment_time,
        'booking_end_time' => $booking_end->format('H:i'),
        'desc'=> $request->desc,
        'status' => 'pending',
    ]);
    return response()->json(['status'=>'success','message' =>'Appointment created successfully']);
    #return back()->with('success','Appointment created successfully');
    // return redirect()->route('CBooking')->with('success', 'Appointment booked successfully!');
}


// public function index()
// {
//     $stores = Store::all(); // or however you're fetching branches

//     $incompleteAppointments = Appointment::with('dentist', 'store')
//         ->where('user_id', Auth::id())
//         ->where('status', '!=', 'completed') // or just `pending`, adjust based on your DB
//         ->orderBy('appointment_date', 'desc')
//         ->get();

//     return view('booking.index', compact('stores', 'incompleteAppointments'));
// }

public function showProfile()
{
    $incompleteAppointments = Appointment::with(['user', 'dentist', 'store'])
        ->where('user_id', auth()->id())
        ->where('status', '!=', 'completed') 
        ->where('status', '!=', 'cancelled')// show only non-completed ones
        ->orderBy('appointment_date', 'desc')
        ->get();

    $stores = Store::all(); // Assuming you're passing this too
    $services = Service::all();
    return view('client.cbooking', compact('incompleteAppointments', 'stores', 'services'));
}

}
