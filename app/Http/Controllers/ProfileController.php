<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;
use App\Models\MedicalForm;
use Illuminate\Support\Facades\Storage;
class ProfileController extends Controller
{

    public function uploadprofileimage(Request $request)
{
    $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();

    // Optionally delete old photo
    if ($user->profile_image) {
        Storage::disk('public')->delete('profile_pictures/' . $user->profile_picture);
    }

    $filename = uniqid() . '.' . $request->file('profile_image')->getClientOriginalExtension();
    $request->file('profile_image')->storeAs('profile_pictures', $filename , 'public');

    $user->profile_image = $filename;
    $user->save();

    return back()->with('success', 'Profile picture updated!');
}
    public function updateProfile(Request $request){
       
       $user =Auth::user();
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


    public function showProfile()
{
    $completedAppointments = Appointment::with(['user', 'dentist'])
        ->where('user_id', auth()->id())
        ->whereIn('status', ['completed', 'no_show'])
        ->orderBy('appointment_date', 'desc')
        ->get();
$medicalForms = MedicalForm::where('user_id', Auth::id())->get();

    return view('client.cprofile', compact('completedAppointments','medicalForms'));
}
}
