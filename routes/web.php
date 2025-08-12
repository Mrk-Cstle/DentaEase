<?php


use App\Http\Controllers\AuthUi;
use App\Http\Controllers\FaceRecognitionController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;
use  App\Models\User;
use App\Http\Controllers\AdminController;
use function Laravel\Prompts\password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\QrController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/syseng', function () {
    
    $user = 'qwe';
    $password = 'qwe';
    $name= 'qwe';
    $account_type = 'admin';
    $position = 'admin';
    $auth = new User();
    $auth->user = $user;
    $auth->password = $password;
    $auth->name = $name;
    $auth->account_type = $account_type;
    $auth->position = $position;
    $auth->formstatus = 1;
    $auth->save();
    return view('auth.login');
    
});

Route::get('/generateqr', function () {
    $users = User::whereNull('qr_code')->get();

    foreach ($users as $user) {
        // Generate unique token if not set
        if (empty($user->qr_token)) {
            $user->qr_token = Str::uuid()->toString();
        }

        // Set filename
        $filename = 'qr_' . $user->id . '.svg';

        // Ensure directory exists
        if (!Storage::disk('public')->exists('qr_codes')) {
            Storage::disk('public')->makeDirectory('qr_codes');
        }

        // Generate QR and save
        $qrImage = QrCode::format('svg')->size(200)->generate($user->qr_token);
        Storage::disk('public')->put("qr_codes/{$filename}", $qrImage);

        // Save to DB
        $user->qr_code = $filename;
        $user->save();
    }

    return response()->json([
        'message' => 'QR codes generated for users without QR.',
        'count' => $users->count()
    ]);
});

require __DIR__.'/admin.php';
require __DIR__.'/client.php';





//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');
Route::get('/faceui',[AuthUi::class, 'FaceUi'])->name('faceui');
Route::post('/get-face-landmarks', [FaceRecognitionController::class, 'getLandmarks']);


Route::get('/qr',[AuthUi::class, 'Qr'])->name('Qr');
Route::post('/qr-login', [QrController::class, 'LoginQr'])->name('qr.login');


//signup form

Route::post('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');
Route::post('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');
Route::post('/login-face', [AuthUi::class, 'loginFace'])->name('login-face');


// web.php
Route::post('/signup/send-otp', [AuthUi::class, 'sendOtp'])->name('send.otp');
Route::get('/signup/verify-otp', [AuthUi::class, 'verifyOtp'])->name('verify.otp');




Route::get('/logouts', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');





