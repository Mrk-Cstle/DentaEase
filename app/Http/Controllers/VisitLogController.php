<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\daily_logs;
use Illuminate\Support\Facades\Http;

use CURLFile;
use Carbon\Carbon;

class VisitLogController extends Controller
{
    //

    private $apiKey = '-2y7KYjX1JuFECsjI_ANCAM5pugEm5R0';
    private $apiSecret = 'QHRO96q2sagJUJ-4DAgVgmBDa2-H3n8v';
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
public function handleFaceScan(Request $request)
{
    $request->validate([
        'user' => 'required',
        'image_base64' => 'required',
    ]);
    
    $user = User::where('user', $request->user)->first();

    if (!$user || !$user->face_token) {
        return response()->json(['message' => 'User not found or face not registered.']);
    }

    // Decode the base64 image
    $imageData = $request->input('image_base64');
    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    $imageData = base64_decode($imageData);

    // Temporarily save the image
    $tempImagePath = storage_path('app/temp_login_face.jpg');
    file_put_contents($tempImagePath, $imageData);

    // Now send this to Face++ Detect
    $detectResponse = Http::attach(
        'image_file', file_get_contents($tempImagePath), 'temp_login_face.jpg'
    )->post('https://api-us.faceplusplus.com/facepp/v3/detect', [
        'api_key' => $this->apiKey,
        'api_secret' => $this->apiSecret,
    ]);

    $detectData = $detectResponse->json();

    if (empty($detectData['faces'][0]['face_token'])) {
        return response()->json(['status'=> 'error','message' => 'No face detected.']);
    }

    $faceToken2 = $detectData['faces'][0]['face_token'];

    // Compare face_token from DB and new one
    $verifyResponse = Http::asForm()->post('https://api-us.faceplusplus.com/facepp/v3/compare', [
        'api_key' => $this->apiKey,
        'api_secret' => $this->apiSecret,
        'face_token1' => $user->face_token,
        'face_token2' => $faceToken2,
    ]);

    $verifyData = $verifyResponse->json();

    if (isset($verifyData['confidence']) && $verifyData['confidence'] > 80) {
      
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
        return response()->json(['status'=> 'error','message' => 'No appointment found for today.'], 404);
    }

    $log = daily_logs::create([
        'user_id' => $user->id,
        'appointment_id' => $appointment->id,
    ]);

    return response()->json([
        'status'=>'success',
        'message' => 'Visit logged successfully.',
        'appointment' => [
            'name' => $user->name,
            'branch' => $appointment->store->name ?? 'N/A',
            'time' => $appointment->appointment_time,
            'status' => $appointment->status,
        ]
    ]);
    } else {
        return response()->json(['status'=> 'error','message' => 'Scan failed. Face does not match.']);
    }

}
 public function logs(Request $request){
    $date = $request->input('date', Carbon::today()->toDateString());

    $logs = daily_logs::with(['user', 'appointment.store'])
        ->whereDate('scanned_at', $date)
        ->latest()
        ->get();

        $users = Appointment::with('user')
        ->whereDate('appointment_date', $date)
        ->get()
        ->pluck('user')
        ->unique('id')
        ->values();
    

    return view('admin.visit-logs', compact('logs', 'date', 'users'));
 }

}
