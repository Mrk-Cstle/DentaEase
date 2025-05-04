<?php


use App\Http\Controllers\AuthUi;
use App\Http\Controllers\Clientside;
use App\Http\Controllers\Facerecognition;
use App\Http\Controllers\FaceRecognitionController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Client;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::get('/', function () {
    return view('auth.login');
})->name('login');


require __DIR__.'/admin.php';





//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');
Route::get('/faceui',[AuthUi::class, 'FaceUi'])->name('faceui');


//signup form

Route::post('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');
Route::post('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');
Route::post('/login-face', [AuthUi::class, 'loginFace'])->name('login-face');


//admin dashboard page


//clent side
Route::middleware(['auth', Client::class])->group(function(){
    Route::get('/cdashboard', [Clientside::class,'CDashboard'])->name('CDashboard')->middleware('auth');
    Route::get('/logout', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');
    Route::get('/cprofile', [Clientside::class,'CProfile'])->name('CProfile')->middleware('auth');
    Route::post('/cregister-face',[Facerecognition::class,'registerFace'])->name("cregister-face")->middleware('auth');
    Route::post('/cremove-face-token', [AdminController::class, 'removeFaceToken'])->middleware('auth');

});

Route::post('/get-face-landmarks', [FaceRecognitionController::class, 'getLandmarks']);
//face reecognitiion




