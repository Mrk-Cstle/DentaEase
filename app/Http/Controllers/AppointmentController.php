<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Store;

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
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|date_format:H:i',
        'desc' =>'required',
        
    ]);

    $store = Store::findOrFail($request->store_id);

    // ✅ Check if store is open that day
    $day = strtolower(Carbon::parse($request->appointment_date)->format('D'));
    if (!in_array($day, $store->open_days ?? [])) {
        return back()->withErrors(['appointment_date' => 'Store is closed on this day.']);
    }

   $booking_end = Carbon::parse($request->appointment_time)->addMinutes(30);

    // ✅ Check if time is within hours
    if (
        $request->appointment_time < $store->opening_time->format('H:i') ||
        $request->appointment_time > $store->closing_time->format('H:i')
    ) {
        return back()->withErrors(['appointment_time' => 'Time is outside of store hours.']);
    }

    // ✅ Check if time slot is already taken
    $alreadyBooked = Appointment::where('store_id', $store->id)
        ->where('appointment_date', $request->appointment_date)
        ->where('appointment_time', $request->appointment_time)
        ->exists();

    if ($alreadyBooked) {
        return back()->withErrors(['appointment_time' => 'This time slot is already booked.']);
    }

    if ($booking_end->format('H:i') > $store->closing_time->format('H:i')) {
    return back()->withErrors(['appointment_time' => 'Booking ends after store closing time.']);
    }
    // ✅ Create the appointment
    Appointment::create([
        'store_id' => $store->id,
        'user_id' => auth()->id(), // assumes you're logged in
        'appointment_date' => $request->appointment_date,
        'appointment_time' => $request->appointment_time,
        'booking_end_time' => $booking_end->format('H:i'),
        'desc'=> $request->desc,
        'status' => 'pending',
    ]);

    return back()->with('success','Appointment created successfully');
    // return redirect()->route('CBooking')->with('success', 'Appointment booked successfully!');
}
}
