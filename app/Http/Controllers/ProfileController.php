<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class ProfileController extends Controller
{


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
   if ($request->has('password') && !Hash::check($request->password, $user->password)) {
    
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
}
