<?php

use App\Http\Controllers\AuthUi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');







//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');

//signup form

Route::get('/signupform', [AuthUi::class, 'SignUpForm'])->name('signupform');

Route::get('/loginform', [AuthUi::class, 'LoginForm'])->name('loginform');