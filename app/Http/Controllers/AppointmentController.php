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

    $dayName = strtolower(Carbon::parse($date)->format('D')); // e.g. 'mon'

    if (!in_array($dayName, $store->open_days ?? [])) {
        return response()->json(['slots' => []]); // store closed on this day
    }

    // Generate all 30-minute slots
    $opening = Carbon::parse($store->opening_time);
    $closing = Carbon::parse($store->closing_time);

    $slots = [];
    while ($opening < $closing) {
        $slots[] = $opening->format('H:i');
        $opening->addMinutes(30);
    }

    // Fetch already booked slots
    $booked = Appointment::where('store_id', $store->id)
        ->where('appointment_date', $date)
        ->pluck('appointment_time')
        ->map(fn($t) => Carbon::parse($t)->format('H:i'))
        ->toArray();

    $availableSlots = array_values(array_diff($slots, $booked));

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

    // ✅ Create the appointment
    Appointment::create([
        'store_id' => $store->id,
        'user_id' => auth()->id(), // assumes you're logged in
        'appointment_date' => $request->appointment_date,
        'appointment_time' => $request->appointment_time,
        'desc'=> $request->desc,
        'status' => 'pending',
    ]);

    return back()->with('success','Appointment created successfully');
    // return redirect()->route('CBooking')->with('success', 'Appointment booked successfully!');
}
}
