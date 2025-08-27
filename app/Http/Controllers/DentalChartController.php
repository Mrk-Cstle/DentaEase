<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\PatientRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DentalChartController extends Controller
{
    //
    public function store(Request $request)
    {
        // Optional: if you want to log the user who agreed
        $userId = Auth::id();

        $user = Auth::user();
        $user->is_consent = true;   // or store timestamp if you want
        $user->save();

        return redirect()->route('CBookingo')->with('success', 'Thank you. Your informed consent has been recorded.');
    }

    public function storeRecord(Request $request)
    {
        $data = $request->all();

        // Handle checkboxes (if not checked, Laravel doesn’t send them, so default to 0)
        $checkboxes = [
            'in_good_health', 'under_treatment', 'had_illness_operation',
            'hospitalized', 'taking_medication', 'allergic', 'bleeding_time',
            'pregnant', 'nursing', 'birth_control_pills'
        ];

        foreach ($checkboxes as $checkbox) {
            $data[$checkbox] = $request->has($checkbox) ? 1 : 0;
        }

        // Arrays for checkboxes
        $data['health_conditions'] = $request->input('health_conditions', []);
        $data['medical_conditions'] = $request->input('medical_conditions', []);

        PatientRecord::create($data);

        return redirect()->back()->with('success', 'Patient record saved successfully!');
    }

    public function treatmentRecord(User $patient)
    {
        // Eager load appointments
        $record = $patient->appointment; // This returns a collection (even if empty)
    
        return view('admin.dental-chart.treatment-record', compact('record'));
    }
}
