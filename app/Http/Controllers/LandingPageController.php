<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Doctors
        $doctors = User::where('position', 'dentist')->get();

        // Receptionists
        $receptionists = User::where('position', 'receptionist')->get();
         $services = Service::all();
        // Branches
        $branches = Store::all();

        return view('landing', compact('doctors', 'receptionists', 'branches', 'services'));
    }
}
