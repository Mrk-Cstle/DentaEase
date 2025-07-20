<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\MedicalForm;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
class Clientside extends Controller
{
    //
    public function CDashboard(){
        return view('client.cdashboard');
    }

    public function CProfile(){

        $medicalForm = MedicalForm::where('user_id', auth()->id())->first();
        return view('client.cprofile',compact('medicalForm'));
    }
    public function CForms(){
        return view('client.cforms');
    }
    public function CBooking(){
        $stores = Store::all(); // ✅ This provides the variable to the view
      
      
        return view('client.cbooking', compact('stores'));
    }
    public function CBookingo()
{
    $userId = auth()->id();

    // Get pending/ongoing appointments (exclude completed, cancelled, no_show)
    $incompleteAppointments = Appointment::with(['user', 'dentist', 'store'])
        ->where('user_id', $userId)
        ->whereNotIn('status', ['completed', 'cancelled', 'no_show'])
        ->orderBy('appointment_date', 'desc')
        ->get();

    // Get only completed appointments for the history tab
    $completedAppointments = Appointment::with(['user', 'dentist', 'store'])
        ->where('user_id', $userId)
        ->where('status', 'completed','no_show')
        ->orderBy('appointment_date', 'desc')
        ->get();

    $stores = Store::all();
    $services = Service::all();
     $notifications = Auth::user()->notifications()->latest()->take(10)->get();

    return view('client.cbookingongoing', compact(
        'incompleteAppointments',
        'completedAppointments',
        'stores',
        'services',
        'notifications'
    ));
}

 }
