<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clientside;
use App\Http\Middleware\Client;
use App\Http\Controllers\Facerecognition;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicalFormController;

Route::get('/crecord', [Clientside::class,'record'])->name('crecord')->middleware('auth');

Route::post('/notifications/mark-as-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return response()->json(['status' => 'success']);
})->name('notifications.markAsRead');

Route::middleware(['auth', Client::class])->group(function(){
    Route::get('/cdashboard', [Clientside::class,'CDashboard'])->name('CDashboard')->middleware('auth');
     Route::get('/booking', [Clientside::class,'CBooking'])->name('CBooking')->middleware('auth');
     Route::get('/bookingongoing', [Clientside::class,'CBookingo'])->name('CBookingo')->middleware('auth');
      Route::get('/cforms', [Clientside::class,'CForms'])->name('CForms')->middleware('auth');
      Route::get('/consent', [Clientside::class,'CConsent'])->name('CConsent')->middleware('auth');
     
    //Route::get('/cprofile', [Clientside::class,'CProfile'])->name('CProfile')->middleware('auth');
    Route::post('/cregister-face',[Facerecognition::class,'registerFace'])->name("cregister-face")->middleware('auth');
    Route::post('/cremove-face-token', [AdminController::class, 'removeFaceToken'])->middleware('auth');
    Route::get('/cprofile', [ProfileController::class, 'showProfile'])->name('CProfile');
   

   
//medical form

Route::middleware(['auth'])->group(function () {
    Route::post('/medical-form', [MedicalFormController::class, 'store'])->name('medical-form.store');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/consentstore', [DentalChartController::class, 'store'])->name('consent.store');
});

});

Route::post('/profile/upload', [ProfileController::class, 'uploadprofileimage'])->name('profile.upload');




 //appointment

   Route::get('/store/{store}/schedule', [AppointmentController::class, 'getSchedule']);

// Returns available time slots for a date
    Route::get('/branch/{store}/available-slots', [AppointmentController::class, 'getAvailableSlots']);
    Route::post('/appointments', [AppointmentController::class, 'appointment'])->name('appointments.store');
    Route::get('/branch/{branchId}/dentists', [AppointmentController::class, 'getDentists']);
    Route::get('/branch/{branchId}/dentist/{dentistId}/slots', [AppointmentController::class, 'getDentistSlots']);
    Route::get('/booking', [AppointmentController::class, 'showProfile'])->name('appointments.incomplete');
    Route::get('/service/{service}', [AppointmentController::class, 'getServiceDetail']);

//admin appointment
    Route::post('/appointmentsadmin', [AppointmentController::class, 'appointmentadmin'])->name('appointments.storeadmin');
    



    Route::get('/dentist/{dentist}/next-approved-appointment', [AppointmentController::class, 'nextApprovedAppointment']);

//patient records

Route::post('/patientrecord', [DentalChartController::class, 'storeRecord'])->name('patient-records');

Route::get('/dental-chart/{patient}/treatmentrecord', [DentalChartController::class, 'treatmentRecord'])->name('treatmentRecord');

Route::get('/patient-record/{patient}', [DentalChartController::class, 'showForm'])->name('patient-record.form');

// Store or update record (POST)
Route::post('/patient-record', [DentalChartController::class, 'storeOrUpdatePatientRecord'])->name('patient-records');