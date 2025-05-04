<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Facerecognition;


Route::middleware(['auth', Admin::class])->group(function () {

Route::get('/dashboard', [AdminController::class,'Dashboard'])->name('dashboard')->middleware('auth');
Route::get('/logouts', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');
Route::get('/profile', [AdminController::class,'Profile'])->name('Profile')->middleware('auth');

///profile tab
Route::post('/remove-face-token', [AdminController::class, 'removeFaceToken'])->middleware('auth');
Route::patch('/updateProfile', [ProfileController::class, 'updateProfile'])->middleware('auth')->name('updateProfile');

//navigation link

Route::get('/userverify', [AdminController::class,'Userverify'])->name('Userverify')->middleware('auth');




//new user 

Route::get('/newuserlist', [AdminController::class,'Newuserlist'])->name('Newuserlist')->middleware('auth');
Route::post('/viewuser',[AdminController::class,'Viewuser'])->name("Viewuser")->middleware('auth');
Route::post('/approveuser',[AdminController::class,'Approveuser'])->name("Approveuser")->middleware('auth');

Route::post('/register-face',[Facerecognition::class,'registerFace'])->name("register-face")->middleware('auth');



});