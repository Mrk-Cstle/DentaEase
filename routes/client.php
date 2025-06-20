<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clientside;
use App\Http\Middleware\Client;
use App\Http\Controllers\Facerecognition;
use App\Http\Controllers\AppointmentController;

Route::middleware(['auth', Client::class])->group(function(){
    Route::get('/cdashboard', [Clientside::class,'CDashboard'])->name('CDashboard')->middleware('auth');
     Route::get('/booking', [Clientside::class,'CBooking'])->name('CBooking')->middleware('auth');
    Route::get('/logout', [AdminController::class,'Logout'])->name('Logout')->middleware('auth');

    Route::get('/cprofile', [Clientside::class,'CProfile'])->name('CProfile')->middleware('auth');
    Route::post('/cregister-face',[Facerecognition::class,'registerFace'])->name("cregister-face")->middleware('auth');
    Route::post('/cremove-face-token', [AdminController::class, 'removeFaceToken'])->middleware('auth');


    //appointment

   Route::get('/store/{store}/schedule', [AppointmentController::class, 'getSchedule']);

// Returns available time slots for a date
    Route::get('/branch/{store}/available-slots', [AppointmentController::class, 'getAvailableSlots']);
    Route::post('/appointments', [AppointmentController::class, 'appointment'])->name('appointments.store');
    Route::get('/branch/{branchId}/dentists', [AppointmentController::class, 'getDentists']);
    Route::get('/branch/{branchId}/dentist/{dentistId}/slots', [AppointmentController::class, 'getDentistSlots']);
    



});