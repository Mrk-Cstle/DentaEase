<?php


use App\Http\Controllers\AuthUi;
use App\Http\Controllers\FaceRecognitionController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;
use  App\Models\User;

use function Laravel\Prompts\password;

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
    $auth->save();
    return view('auth.login');
    
});

require __DIR__.'/admin.php';
require __DIR__.'/client.php';





//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');
Route::get('/faceui',[AuthUi::class, 'FaceUi'])->name('faceui');
Route::post('/get-face-landmarks', [FaceRecognitionController::class, 'getLandmarks']);


//signup form

Route::post('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');
Route::post('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');
Route::post('/login-face', [AuthUi::class, 'loginFace'])->name('login-face');








