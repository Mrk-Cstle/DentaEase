<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\newuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class AuthUi extends Controller
{
    public function SignUpUi(Request $request){
        return view('auth.signup');
    }
    public function LoginUi(Request $request){
        return view('auth.login');
    }

    public function FaceUi(Request $request){
        return view('auth.face');
    }

    public function SignUpForm(Request $request){
      $validated = $request->validate([
            'name' => 'required',
          
            'email' => 'required|email',
            'password' => 'required',
            'contact_number' => 'required',
            'account_type' => 'required',
            'user' => 'required|unique:users,user',

        ]);

            // Hash password
        $validated['password'] = bcrypt($validated['password']);

        // Try saving user
        $user = newuser::create($validated);

    if ($user) {
        return response()->json(['status' => 'success', 'message' => 'Account created successfully.']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Failed to create account.'], 500);
    }
    }

    public function LoginForm(Request $request){
        $credentials = $request->only('user','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
    
            // Choose redirect URL based on account type
            $redirectUrl = match ($user->account_type) {
                'admin' => route('dashboard'),
                'patient' => route('CDashboard'),
                default => route('login')
            };
            return response()->json(['status' => 'success','redirect' => $redirectUrl]);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
    }

    private $apiKey = 'd6oKAAzVLTtgRyeecdED4eHFi9wfmq3I';
    private $apiSecret = 'qe_nYezzGtNwf4WN_drOcrxfeg0ryJ7S';
    public function loginFace(Request $request)
{   
    
    $request->validate([
        'user' => 'required',
        'image_base64' => 'required',
    ]);
    
    $user = User::where('user', $request->user)->first();

    if (!$user || !$user->face_token) {
        return response()->json(['message' => 'User not found or face not registered.'], 404);
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
        return response()->json(['message' => 'No face detected.'], 400);
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

    if (isset($verifyData['confidence']) && $verifyData['confidence'] > 70) {
        $redirectUrl = match ($user->account_type) {
            'admin' => route('dashboard'),
            'patient' => route('CDashboard'),
            default => route('login')
        };
        return response()->json(['status'=> 'success','message' => 'Login successful!', 'verify_data' => $verifyData,'redirect' => $redirectUrl]);
    } else {
        return response()->json(['status'=> 'error','message' => 'Login failed. Face does not match.', 'verify_data' => $verifyData], 401);
    }
}
}
