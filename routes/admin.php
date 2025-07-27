<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthUi;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PatientViewController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Facerecognition;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\VisitLogController;
use Illuminate\Support\Facades\Auth;

Route::post('/remove-face-token', [AdminController::class, 'removeFaceToken'])->middleware('auth');
Route::patch('/updateProfile', [ProfileController::class, 'updateProfile'])->middleware('auth')->name('updateProfile');

Route::middleware(['web', 'auth', Admin::class])->group(function () {

Route::get('/dashboard', [AdminController::class,'Dashboard'])->name('dashboard')->middleware('auth');

Route::get('/profile', [AdminController::class,'Profile'])->name('Profile')->middleware('auth');


Route::post('/set-active-branch', function (\Illuminate\Http\Request $request) {
    session(['active_branch_id' => $request->id]);
    return response()->json(['status' => 'success']);
});


Route::get('/get-branches', function () {
   $branches = \App\Models\Store::all()->toArray(); // Convert collection to array

if (Auth::check() && Auth::user()->position === 'admin') {
    $branches[] = [
        'id' => 'admin',
        'name' => 'Admin',
        'address' => 'N/A',
        'open_days' => [],
        'opening_time' => null,
        'closing_time' => null,
    ];
}

return response()->json($branches);
});

///profile tab


//navigation link

Route::get('/userverify', [AdminController::class,'Userverify'])->name('Userverify')->middleware('auth');
Route::get('/useraccount', [AdminController::class,'Useraccount'])->name('Useraccount')->middleware('auth');
Route::get('/patientaccount', [AdminController::class,'Patientaccount'])->name('Patientaccount')->middleware('auth');
Route::get('/branch', [AdminController::class,'Branch'])->name('Branch')->middleware('auth');
Route::get('/services', [AdminController::class,'Services'])->name('Services')->middleware('auth');
Route::get('/logs', [VisitLogController::class,'logs'])->name('logs')->middleware('auth');
Route::get('/inventory', [InventoryController::class,'inventory'])->name('inventory')->middleware('auth');

//new user 

Route::get('/newuserlist', [AdminController::class,'Newuserlist'])->name('Newuserlist')->middleware('auth');
Route::post('/viewuser',[AdminController::class,'Viewuser'])->name("Viewuser")->middleware('auth');//removed
Route::post('/approveuser',[AdminController::class,'Approveuser'])->name("Approveuser")->middleware('auth');
Route::get('/users/{id}', [AdminController::class, 'show'])->name('users.show');
Route::get('/files/verification/{filename}', [FileController::class, 'servePrivateFile'])->name('file.serve');
Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');




Route::post('/register-face',[Facerecognition::class,'registerFace'])->name("register-face")->middleware('auth');

Route::post('/add-user',[AdminController::class,'Adduser'])->name("add-user")->middleware('auth');

//staff list tab
Route::get('/stafflist', [StaffController::class,'ViewStaff'])->name('Stafflist')->middleware('auth');
//Route::get('/user/{id}', [StaffController::class, 'show'])->name('userview');
Route::post('/deleteuser', [StaffController::class, 'DeleteUser'])->name('deleteuser');
Route::patch('/updateUser', [StaffController::class, 'UpdateUser'])->middleware('auth')->name('updateUser');
Route::get('/user/{id}', [StaffController::class, 'showProfile'])->name('userviewappointment');

//Paient list tab
Route::get('/patientlist', [PatientViewController::class,'ViewPatient'])->name('Patientlist')->middleware('auth');


//Branch tab\
Route::post('/addbranch', [BranchController::class,'AddBranch'])->name('AddBranch')->middleware('auth');
Route::get('/branchlist', [BranchController::class,'Branchlist'])->name('Branchlist')->middleware('auth');
Route::get('/branch-details', [BranchController::class, 'getBranchDetails']);
Route::post('/branch/{branch}/add-user', [BranchController::class, 'addUser'])->middleware('auth');
Route::get('/branch/users-by-position', [BranchController::class, 'getUsersByPosition']);
Route::post('/branch/{store}/remove-user', [BranchController::class, 'removeUser']);
Route::get('/branch/deletebranch', [BranchController::class, 'DeleteBranch'])->name('DeleteBranch');
Route::post('/branch/update-schedule/{id}', [BranchController::class, 'updateSchedule']);

//branch selection


//booking
Route::get('/appointments', [AdminBookingController::class, 'showBookings'])->name('admin.booking');
Route::put('/appointments/{id}/approve', [AdminBookingController::class, 'approveBooking'])->name('appointments.approve');
Route::get('/appointments/{id}/view', [AdminBookingController::class, 'view'])->name('appointments.view');
Route::post('/appointments/{id}/settle', [AdminBookingController::class, 'settle'])->name('appointments.settle');
Route::get('/appointments/fetch', [AdminBookingController::class, 'fetch'])->name('appointments.fetch');
Route::get('/admin/bookings/history', [AdminBookingController::class, 'showHistory'])->name('admin.booking.history');
Route::put('/appointments/{id}/cancel', [AdminBookingController::class, 'cancelBooking'])->name('appointments.cancel');
Route::get('/user/details/{id}', [AdminBookingController::class, 'modalDetails']);

//Services

Route::get('/serviceslist', [ServicesController::class,'Serviceslist'])->name('Serviceslist');
Route::post('/add-services', [ServicesController::class,'Addservices'])->name('add-services');
Route::post('/service/update', [ServicesController::class,'update'])->name('service-update');
Route::delete('services/{id}', [ServicesController::class, 'destroy'])->name('services.destroy');

//dashboard

Route::get('/dashboard/appointment-stats', [DashboardController::class, 'getAppointmentStats']);


//appointment logs 
Route::post('/scan-qr', [VisitLogController::class, 'handleQrScan'])->name('scan.qr');

//inventory
Route::get('/inventorylist', [InventoryController::class,'InventoryList'])->name('InventoryList')->middleware('auth');
Route::post('/medicines', [InventoryController::class, 'store'])->name('medicines.store');

Route::get('/medicines/{medicine}', [InventoryController::class, 'show'])->name('medicines.show');
Route::post('/medicines/{medicine}/batch', [InventoryController::class, 'addBatch'])->name('medicines.addBatch');
});

Route::get('/get-branch', [AuthUi::class,'GetBranchLogin'])->name('GetBranchLogin');
Route::post('/select-branch', [AuthUi::class,'SelectBranchLogin'])->name('SelectBranchLogin');
