<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\daily_logs;

use Carbon\Carbon;

class VisitLogController extends Controller
{
    //

    public function handleQrScan(Request $request)
{
    $request->validate([
        'qr_token' => 'required|string',
    ]);

    $user = User::where('qr_token', $request->qr_token)->first();

    if (!$user) {
        return response()->json(['message' => 'QR Code not recognized.'], 404);
    }

    $alreadyLogged = daily_logs::where('user_id', $user->id)
        ->whereDate('scanned_at', Carbon::today())
        ->exists();

    if ($alreadyLogged) {
        return response()->json(['message' => 'Already logged today.'], 200);
    }

    $appointment = Appointment::where('user_id', $user->id)
        ->whereDate('appointment_date', Carbon::today())
        ->whereIn('status', ['approved', 'pending'])
        ->first();

    if (!$appointment) {
        return response()->json(['message' => 'No appointment found for today.'], 404);
    }

    $log = daily_logs::create([
        'user_id' => $user->id,
        'appointment_id' => $appointment->id,
    ]);

    return response()->json([
        'message' => 'Visit logged successfully.',
        'appointment' => [
            'name' => $user->name,
            'branch' => $appointment->store->name ?? 'N/A',
            'time' => $appointment->appointment_time,
            'status' => $appointment->status,
        ]
    ]);
}

 public function logs(Request $request){
    $date = $request->input('date', Carbon::today()->toDateString());

    $logs = daily_logs::with(['user', 'appointment.store'])
        ->whereDate('scanned_at', $date)
        ->latest()
        ->get();

    return view('admin.visit-logs', compact('logs', 'date'));
 }

}
