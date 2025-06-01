<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BranchController;
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
Route::get('/useraccount', [AdminController::class,'Useraccount'])->name('Useraccount')->middleware('auth');
Route::get('/branch', [AdminController::class,'Branch'])->name('Branch')->middleware('auth');


//new user 

Route::get('/newuserlist', [AdminController::class,'Newuserlist'])->name('Newuserlist')->middleware('auth');
Route::post('/viewuser',[AdminController::class,'Viewuser'])->name("Viewuser")->middleware('auth');
Route::post('/approveuser',[AdminController::class,'Approveuser'])->name("Approveuser")->middleware('auth');

Route::post('/register-face',[Facerecognition::class,'registerFace'])->name("register-face")->middleware('auth');

Route::post('/add-user',[AdminController::class,'Adduser'])->name("add-user")->middleware('auth');

//staff list tab
Route::get('/stafflist', [StaffController::class,'ViewStaff'])->name('Stafflist')->middleware('auth');
Route::get('/user/{id}', [StaffController::class, 'show'])->name('userview');
Route::post('/deleteuser', [StaffController::class, 'DeleteUser'])->name('deleteuser');
Route::patch('/updateUser', [StaffController::class, 'UpdateUser'])->middleware('auth')->name('updateUser');


//Branch tab\
Route::post('/addbranch', [BranchController::class,'AddBranch'])->name('AddBranch')->middleware('auth');
Route::get('/branchlist', [BranchController::class,'Branchlist'])->name('Branchlist')->middleware('auth');
Route::get('/branch-details', [BranchController::class, 'getBranchDetails']);
Route::post('/branch/{branch}/add-user', [BranchController::class, 'addUser'])->middleware('auth');
Route::get('/branch/users-by-position', [BranchController::class, 'getUsersByPosition']);
Route::post('/branch/{store}/remove-user', [BranchController::class, 'removeUser']);
Route::get('/branch/deletebranch', [BranchController::class, 'DeleteBranch'])->name('DeleteBranch');



});