<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Appointment;
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
            \Log::info('Request data:', $request->all());
            \Log::info('Current user data:', $user->toArray());
            $rules = [];
            $data = [];
            $isUpdated = false;
     
            if ($request->filled('email') &&  $request->email !== $user->email) {
                 $rules['email'] = 'email';
                 $data['email'] = $request->email;
                 $isUpdated = true;
            }
            if ($request->filled('names') &&  $request->names !== $user->name) {
                $rules['name'] = 'name';
                $data['name'] = $request->names;
                $isUpdated = true;
           }
           if ($request->filled('bday') &&  $request->bday !== $user->birth_date) {
            $rules['birth_date'] = 'bday';
            $data['birth_date'] = $request->bday;
            $isUpdated = true;
       }
     
            if ($request->filled('contact') && $request->contact !== $user->contact_number) {
             $rules['contact'] = 'nullable|regex:/^09\d{9}$/';
             $data['contact_number'] = $request->contact;     
             $isUpdated = true;  
            }
            if ($request->filled('user') &&  $request->user !== $user->user) {
             $rules['user'] = [
                 'required',
                 'string',
                 'max:255',
                 Rule::unique('users')->ignore(auth()->id()),  
                 
             ];
             $data['user'] = $request->user;
             $isUpdated = true;
        }
      
        if (!empty($request->password) && !Hash::check($request->password, $user->password)) {
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

    $completedAppointments = Appointment::with(['user', 'dentist'])
        ->where('user_id', $id)
        ->where('status', 'completed')
        ->orderBy('appointment_date', 'desc')
        ->get();

    return view('admin.viewuserdetails', compact('user', 'completedAppointments'));
}
     
}
