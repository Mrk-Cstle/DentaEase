<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/MedicalFormController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth ;

class MedicalFormController extends Controller
{
   public function store(Request $request)
{
    $form = new MedicalForm();

    $form->user_id = auth()->id();
    $form->allergies = $request->input('allergies');
    $form->allergies_details = $request->input('allergies_details');
    $form->heart_condition = $request->input('heart_condition');
    $form->heart_condition_details = $request->input('heart_condition_details');
    $form->asthma = $request->input('asthma');
    $form->asthma_details = $request->input('asthma_details');
    $form->had_surgeries = $request->input('had_surgeries');
    $form->surgery_type = $request->input('surgery_type');
    $form->surgery_date = $request->input('surgery_date');
    $form->surgery_location = $request->input('surgery_location');
    $form->surgery_remarks = $request->input('surgery_remarks');
    $form->medication_name = $request->input('medication_name');
    $form->medication_dosage = $request->input('medication_dosage');
    $form->medication_reason = $request->input('medication_reason');
    $form->visit_reason = $request->input('visit_reason');
    $form->last_dental_visit = $request->input('last_dental_visit');
    $form->had_dental_issues = $request->input('had_dental_issues');
    $form->dental_issue_description = $request->input('dental_issue_description');
    $form->dental_anxiety = $request->input('dental_anxiety');
    $form->emergency_name = $request->input('emergency_name');
    $form->emergency_relationship = $request->input('emergency_relationship');
    $form->emergency_contact = $request->input('emergency_contact');

    $form->save();
    User::where('id', Auth::id())->update([
    'formstatus' => '1',
    ]);
    return redirect()->route('CBookingo'); // or wherever
}

}
