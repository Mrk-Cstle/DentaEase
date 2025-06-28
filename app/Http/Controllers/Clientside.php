<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Service;
class Clientside extends Controller
{
    //
    public function CDashboard(){
        return view('client.cdashboard');
    }

    public function CProfile(){
        return view('client.cprofile');
    }
    public function CBooking(){
        $stores = Store::all(); // ✅ This provides the variable to the view
      
      
        return view('client.cbooking', compact('stores'));
    }
    public function CBookingo(){
        $incompleteAppointments = Appointment::with(['user', 'dentist', 'store'])
        ->where('user_id', auth()->id())
        ->where('status', '!=', 'completed') 
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'no_show')// show only non-completed ones
        ->orderBy('appointment_date', 'desc')
        ->get();

    $stores = Store::all(); // Assuming you're passing this too
    $services = Service::all();
    return view('client.cbookingongoing', compact('incompleteAppointments', 'stores', 'services'));
      
      
        
    }
 }
