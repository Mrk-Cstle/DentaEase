<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthUi;
use App\Http\Controllers\Clientside;
use App\Http\Controllers\Facerecognition;
use App\Http\Controllers\FaceRecognitionController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Client;
Route::get('/', function () {
    return view('auth.login');
})->name('login');







//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');
Route::get('/faceui',[AuthUi::class, 'FaceUi'])->name('faceui');


//signup form

Route::post('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');
Route::post('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');
Route::post('/login-face', [AuthUi::class, 'loginFace'])->name('login-face');


//admin dashboard page
Route::middleware(['auth', Admin::class])->group(function () {

Route::get('/dashboard', [AdminController::class,'Dashboard'])->name('dashboard')->middleware('auth');
Route::get('/logout', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');
Route::get('/profile', [AdminController::class,'Profile'])->name('Profile')->middleware('auth');


//navigation link

Route::get('/userverify', [AdminController::class,'Userverify'])->name('Userverify')->middleware('auth');




//new user 

Route::get('/newuserlist', [AdminController::class,'Newuserlist'])->name('Newuserlist')->middleware('auth');
Route::post('/viewuser',[AdminController::class,'Viewuser'])->name("Viewuser")->middleware('auth');
Route::post('/approveuser',[AdminController::class,'Approveuser'])->name("Approveuser")->middleware('auth');

Route::post('/register-face',[Facerecognition::class,'registerFace'])->name("register-face")->middleware('auth');

});

//clent side
Route::middleware(['auth', Client::class])->group(function(){
    Route::get('/cdashboard', [Clientside::class,'CDashboard'])->name('CDashboard')->middleware('auth');
   
});

Route::post('/get-face-landmarks', [FaceRecognitionController::class, 'getLandmarks']);
//face reecognitiion




