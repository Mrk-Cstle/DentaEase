<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
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
 }
