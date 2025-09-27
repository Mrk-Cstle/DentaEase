<?php


use App\Http\Controllers\AuthUi;
use App\Http\Controllers\FaceRecognitionController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;
use  App\Models\User;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MessageController;

use function Laravel\Prompts\password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\QrController;

// Route::get('/', function () {
//     return view('auth.login');
// })->name('login');

Route::get('/login', function () {
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
Route::get('/', [LandingPageController::class, 'index']);

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


Route::get('/dental-chart/{patient}', function (User $patient) {
    return view('admin.dental-chart.index', compact('patient'));
});


Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');

// Patient sees branches
Route::get('/branches', [MessageController::class, 'branches'])->name('branches.list');

// Admin sees patients
Route::get('/patients', [MessageController::class, 'patients'])->name('patients.list');

// Fetch messages (needs both store + patient)
Route::get('/messages/{storeId}/{userId}', [MessageController::class, 'fetch'])->name('messages.fetch');

Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

// Patient side
Route::middleware('auth')->group(function () {
    Route::get('/patient/chat', [MessageController::class, 'patientIndex'])->name('patient.chat.index');
    Route::get('/patient/branches', [MessageController::class, 'branches'])->name('patient.branches.list');
    Route::get('/patient/messages/{storeId}', [MessageController::class, 'patientMessages'])->name('patient.messages');
    Route::post('/patient/messages', [MessageController::class, 'sendMessage'])->name('patient.messages.store');
});