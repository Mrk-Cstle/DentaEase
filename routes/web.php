<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthUi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');







//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');

//signup form

Route::post('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');

Route::post('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');


//dashboard page

Route::get('/dashboard', [AdminController::class,'Dashboard'])->name('dashboard')->middleware('auth');
Route::get('/logout', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');


//navigation link

Route::get('/userverify', [AdminController::class,'Userverify'])->name('Userverify')->middleware('auth');




//new user 

Route::get('/newuserlist', [AdminController::class,'Newuserlist'])->name('Newuserlist')->middleware('auth');