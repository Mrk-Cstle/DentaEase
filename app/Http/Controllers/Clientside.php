<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Clientside extends Controller
{
    //
    public function CDashboard(){
        return view('client.cdashboard');
    }

    public function CProfile(){
        return view('client.cprofile');
    }
 }
