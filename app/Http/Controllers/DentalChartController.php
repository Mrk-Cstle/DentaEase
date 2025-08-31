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

    public function showForm(User $patient)
    {
        $userId = $patient->id;
        $patientinfo = null;
        $patientinfo = PatientRecord::firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );

        return view('client.patient_record', compact('patientinfo'));
    }

    // Store or update patient record
    public function storeOrUpdatePatientRecord(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
            // 'last_name' => 'nullable|string|max:255',
            // 'first_name' => 'nullable|string|max:255',
            // 'middle_name' => 'nullable|string|max:255',
            // 'birthdate' => 'nullable|date',
            // 'sex' => 'nullable|in:M,F',
            // 'nationality' => 'nullable|string|max:255',
            // 'religion' => 'nullable|string|max:255',
            // 'occupation' => 'nullable|string|max:255',
            // 'home_address' => 'nullable|string|max:500',
            // 'office_address' => 'nullable|string|max:500',
            // 'contact_number' => 'nullable|string|max:50',
            // 'email' => 'nullable|email|max:255',
            'referred_by' => 'nullable|string|max:255',
            'reason_for_consultation' => 'nullable|string|max:500',
            'previous_dentist' => 'nullable|string|max:255',
            'last_dental_visit' => 'nullable|string|max:255',
            'physician_name' => 'nullable|string|max:255',
            'physician_specialty' => 'nullable|string|max:255',
            'physician_contact' => 'nullable|string|max:50',
            'blood_type' => 'nullable|string|max:10',

            // Booleans (checkboxes)
            'in_good_health' => 'sometimes|accepted',
            'under_treatment' => 'sometimes|accepted',
            'had_illness_operation' => 'sometimes|accepted',
            'hospitalized' => 'sometimes|accepted',
            'taking_medication' => 'sometimes|accepted',
            'allergic' => 'sometimes|accepted',
            'bleeding_time' => 'sometimes|accepted',
            'pregnant' => 'sometimes|accepted',
            'nursing' => 'sometimes|accepted',
            'birth_control_pills' => 'sometimes|accepted',

            // Arrays
            'health_conditions' => 'nullable|array',
            'health_conditions.*' => 'string',
            'medical_conditions' => 'nullable|array',
            'medical_conditions.*' => 'string',
        ]);

        // Convert checkboxes to boolean 1/0
        $boolFields = [
            'in_good_health','under_treatment','had_illness_operation','hospitalized',
            'taking_medication','allergic','bleeding_time','pregnant','nursing','birth_control_pills'
        ];
        foreach ($boolFields as $field) {
            $data[$field] = $request->has($field) ? true : false;
        }

        // Find patient by email
        $patient = PatientRecord::where('user_id', $request->user_id)->first();

        if ($patient) {
            // Update
            $patient->update($data);
        } else {
            // Create
            PatientRecord::create($data);
        }
        return response()->json(['status' => 'success', 'message'=>'Patient record saved successfully']);
       
    
    }
}
