<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthUi;
use App\Http\Controllers\Clientside;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Client;
Route::get('/', function () {
    return view('auth.login');
})->name('login');







//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');


//signup form

Route::post('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');

Route::post('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');


//admin dashboard page
Route::middleware(['auth', Admin::class])->group(function () {

Route::get('/dashboard', [AdminController::class,'Dashboard'])->name('dashboard')->middleware('auth');
Route::get('/logout', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');


//navigation link

Route::get('/userverify', [AdminController::class,'Userverify'])->name('Userverify')->middleware('auth');




//new user 

Route::get('/newuserlist', [AdminController::class,'Newuserlist'])->name('Newuserlist')->middleware('auth');
Route::post('/viewuser',[AdminController::class,'Viewuser'])->name("Viewuser")->middleware('auth');
Route::post('/approveuser',[AdminController::class,'Approveuser'])->name("Approveuser")->middleware('auth');

});

//clent side
Route::middleware(['auth', Client::class])->group(function(){
    Route::get('/cdashboard', [Clientside::class,'CDashboard'])->name('CDashboard')->middleware('auth');
});
