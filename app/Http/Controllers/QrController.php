<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class QrController extends Controller
{
    //

public function generateUserQr(User $user)
{
    // 1. Generate a unique token if it doesn't exist
    if (empty($user->qr_token)) {
        $user->qr_token = Str::uuid()->toString();
    }

    // 2. Generate the filename
    $filename = 'qr_' . $user->id . '.svg';

    // 3. Ensure the directory exists
    if (!Storage::disk('public')->exists('qr_codes')) {
        Storage::disk('public')->makeDirectory('qr_codes');
    }

    // 4. Generate and store the QR code (uses GD backend by default)
    $qrImage = QrCode::format('svg')->size(200)->generate($user->qr_token);
    Storage::disk('public')->put("qr_codes/{$filename}", $qrImage);

    // 5. Save the QR filename and token to the database
    $user->qr_code = $filename;
    $user->save();

    // 6. Return a response (optional)
    return response()->json([
        'message' => 'QR code generated successfully.',
        'qr_token' => $user->qr_token,
        'qr_path' => asset("storage/qr_codes/{$filename}")
    ]);
}

    public function LoginQr(Request $request)
    {
         $request->validate([
        'token' => 'required|string',
    ]);

    $user = User::where('qr_token', $request->token)->first();

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'Invalid QR code.'], 401);
    }

    Auth::login($user);
    $request->session()->regenerate(); // regenerate session ID

    // Get authenticated user
    $user = Auth::user();

    // Default redirect based on position or account type
    if ($user->position === 'admin') {
        session(['active_branch_id' => 'admin']);
        $redirectUrl = route('dashboard');
    } else {
        $redirectUrl = match ($user->account_type) {
            'admin'   => route('GetBranchLogin'),
            'patient' => route('CBookingo'),
            default   => route('login'),
        };
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Logged in successfully.',
        'redirect' => $redirectUrl,
    ]);
    }
}
