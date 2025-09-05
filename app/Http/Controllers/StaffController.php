<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Appointment;
use App\Models\MedicalForm;
use App\Models\PatientRecord;

class StaffController extends Controller
{
    //
    public function ViewStaff(Request $request){

        $perPage = 5;

        $search = $request->input('search');
        $position = $request->input('position');
       
        $query = User::where('account_type', 'admin')->where(function($q) use ($search){
            $q->where('name', 'like', "%{$search}%");
            $q->orWhere('user', 'like', "%{$search}%");
        });
        
        
    if ($position) {
        $query->where('position', $position);
    }
        $staff = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $staff->items(),
            'pagination' => [
                'total' => $staff->total(),
                'per_page' => $staff->perPage(),
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'next_page_url' => $staff->nextPageUrl(),
                'prev_page_url' => $staff->previousPageUrl(),
            ]
        ]);

    }
            public function show($id)
        {
            $user = User::findOrFail($id); 

            return view('admin.viewuserdetails', compact('user'));
        }

        public function DeleteUser(Request $request){
            $id = $request->id;
            $user = User::find($id);
            if ($user) {
               
                $user->delete();
    
               
                return response()->json([
                    'status' => 'success',
                    'message' => 'User deleted successfully',
                ] );
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ] );
            }
        }
        public function UpdateUser(Request $request){
            $id = $request->id;
            $user = User::find($id);
            // \Log::info('Request data:', $request->all());
            // \Log::info('Current user data:', $user->toArray());
            $rules = [];
            $data = [];
            $isUpdated = false;
     
            if ($request->filled('email') && $request->email !== $user->email) {
                $rules['email'] = ['required', 'email'];
                $data['email'] = $request->email;
                $isUpdated = true;
            }
        
            if ($request->filled('name') && $request->name !== $user->name) {
                $rules['name'] = ['required', 'string', 'max:255'];
                $data['name'] = $request->name;
                $isUpdated = true;
            }
        
            if ($request->filled('lastname') && $request->lastname !== $user->lastname) {
                $rules['lastname'] = ['required', 'string', 'max:255'];
                $data['lastname'] = $request->lastname;
                $isUpdated = true;
            }
        
            if ($request->filled('middlename') && $request->middlename !== $user->middlename) {
                $rules['middlename'] = ['nullable', 'string', 'max:255'];
                $data['middlename'] = $request->middlename;
                $isUpdated = true;
            }
        
            if ($request->filled('suffix') && $request->suffix !== $user->suffix) {
                $rules['suffix'] = ['nullable', 'string', 'max:50'];
                $data['suffix'] = $request->suffix;
                $isUpdated = true;
            }
        
            if ($request->filled('birthdate') && $request->birthdate !== $user->birth_date) {
                $rules['birthdate'] = ['required', 'date'];
                $data['birth_date'] = $request->birthdate;
                $isUpdated = true;
            }
        
            if ($request->filled('contact') && $request->contact !== $user->contact_number) {
                $rules['contact'] = ['nullable', 'regex:/^09\d{9}$/'];
                $data['contact_number'] = $request->contact;
                $isUpdated = true;
            }
        
            if ($request->filled('user') && $request->user !== $user->user) {
                $rules['user'] = [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ];
                $data['user'] = $request->user;
                $isUpdated = true;
            }
        
            if (!empty($request->password) && !Hash::check($request->password, $user->password)) {
                $rules['password'] = ['required'];
                $data['password'] = Hash::make($request->password);
                $isUpdated = true;
            }
        
            if (!empty($rules)) {
                $request->validate($rules);
            }
        
            if ($isUpdated && !empty($data)) {
                $user->update($data);
                return response()->json(['status' => 'success', 'message' => 'Profile updated successfully.']);
            }
        
            return response()->json(['status' => 'error', 'message' => 'No changes made to your profile.']);
        }


public function showProfile($id)
{
    $user = User::findOrFail($id);

    // All completed/cancelled/no_show appointments of this user
    $completedAppointments = Appointment::with(['user', 'dentist'])
        ->where('user_id', $user->id)
        ->whereIn('status', ['completed', 'no_show', 'cancelled'])
        ->orderBy('appointment_date', 'desc')
        ->get();

    // All medical forms of this user
    $medicalForms = MedicalForm::where('user_id', $user->id)->get();

    // Latest appointment of the user (for profile context)
    $appointment = Appointment::with('user', 'store')
        ->where('user_id', $user->id)
        ->latest('appointment_date')
        ->first();

    // Initialize variables
    $record = '';
    $patient = '';
    $patientinfo = '';

    if ($user->account_type == 'patient') {
        // Treatment record (all appointments of the patient)
        $record = $user->appointment()
            ->whereIn('status', ['completed', 'no_show', 'cancelled'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // The patient is just the user object
        $patient = $user;

        // Create patient info if it doesn’t exist
        $patientinfo = PatientRecord::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );
    }

    return view('admin.viewuserdetails', compact(
        'user',
        'completedAppointments',
        'medicalForms',
        'appointment',
        'record',
        'patient',
        'patientinfo'
    ));
}

     
}
