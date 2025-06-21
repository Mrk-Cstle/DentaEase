<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\newuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class AuthUi extends Controller
{   
    public function GetBranchLogin (Request $request){
        $branches =Auth::user()->stores;
         return view('auth.SelectBranch', compact('branches'));
    }
     public function SelectBranchLogin (Request $request){
        $request->validate(['branch_id' => 'required|exists:stores,id']);
        session(['active_branch_id' => $request->branch_id]);
        return redirect()->intended('/dashboard');
    }
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
        $otp = rand(100000, 999999);

    // Hash the password
    $validated['password'] = bcrypt($validated['password']);
    $validated['otp_code'] = $otp;
    $validated['otp_expires_at'] = Carbon::now()->addMinutes(10); // OTP valid for 10 mins
    $validated['is_verified'] = false;

    // Create user
    $user = newuser::create($validated);

    if ($user) {
        // Send OTP to user's email
        Mail::to($user->email)->send(new \App\Mail\SendOtp($otp));

        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully. An OTP has been sent to your email for verification.',
            'user_id' => $user->id
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create account.'
        ], 500);
    }
    }

    public function LoginForm(Request $request){
        $credentials = $request->only('user','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->position == 'admin') {
                session(['active_branch_id' => 'admin']);
                $redirectUrl = route('dashboard');

            } else { 
                $redirectUrl = match ($user->account_type) {
                'admin' => route('GetBranchLogin'),
                'patient' => route('CDashboard'),
                default => route('login')

                 };
            }
            // Choose redirect URL based on account type
          
           
           
            return response()->json(['status' => 'success','redirect' => $redirectUrl]);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
    }

    // private $apiKey = 'd6oKAAzVLTtgRyeecdED4eHFi9wfmq3I';
    // private $apiSecret = 'qe_nYezzGtNwf4WN_drOcrxfeg0ryJ7S';

      private $apiKey = '-2y7KYjX1JuFECsjI_ANCAM5pugEm5R0';
    private $apiSecret = 'QHRO96q2sagJUJ-4DAgVgmBDa2-H3n8v';
    public function loginFace(Request $request)
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
        return response()->json(['message' => 'No face detected.']);
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
        Auth::login($user);
        $redirectUrl = match ($user->account_type) {
            'admin' => route('dashboard'),
            'patient' => route('CDashboard'),
            default => route('login')
        };
        return response()->json(['status'=> 'success','message' => 'Login successful!', 'verify_data' => $verifyData,'redirect' => $redirectUrl]);
    } else {
        return response()->json(['status'=> 'error','message' => 'Login failed. Face does not match.', 'verify_data' => $verifyData]);
    }
}





public function sendOtp(Request $request)
{
    $request->validate([
            'name' => 'required',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'suffix' => 'nullable|string|max:10',
            'birth_date' => 'required|date',
            'birthplace' => 'required|string',
            'current_address' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'contact_number' => 'required',
            'account_type' => 'required',
            'user' => 'required|unique:users,user',
            'verification_id' => 'required|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        
        ]);

    $otp = rand(100000, 999999);

    $uploadedFile = $request->file('verification_id');
    $filename = uniqid('verify_') . '.' . $uploadedFile->getClientOriginalExtension();
    $uploadedFile->storeAs('temp_verifications', $filename); // Store in `storage/app/temp_verifications`

    // Save both file name and user info
    Session::put('pending_user', array_merge($request->except('verification_id'), ['verification_id' => $filename]));
    Session::put('signup_otp', $otp);

    Mail::to($request->email)->send(new SendOtp($otp));

    return response()->json(['message' => 'OTP sent to your email.']);
}

public function verifyOtp(Request $request)
{
    if ($request->otp != Session::get('signup_otp')) {
        return response()->json(['message' => 'Invalid OTP.'], 400);
    }

    $data = Session::get('pending_user');
    $data['password'] = bcrypt($data['password']);


      $tempFileName = $data['verification_id'] ?? null;

    if ($tempFileName) {
        $tempPath = storage_path('app/temp_verifications/' . $tempFileName);
        $newPath = 'verifications/' . $tempFileName;

        if (file_exists($tempPath)) {
            Storage::disk('public')->put($newPath, file_get_contents($tempPath));
            unlink($tempPath); // delete from temp
            $data['verification_id'] = $newPath;
        }
    }

    $user = newuser::create($data);

    Session::forget(['pending_user', 'signup_otp']);

    return response()->json(['message' => 'Account created successfully!, Please wait for admin approval email.']);
}
}
