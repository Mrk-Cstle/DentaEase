<?php

use App\Http\Controllers\AuthUi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');







//auth page
Route::get('/signupui',[AuthUi::class,'SignUpUi'])->name('signupui');
Route::get('/loginui',[AuthUi::class, 'LogInUi'])->name('loginui');