<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\newuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthUi extends Controller
{
    public function SignUpUi(Request $request){
        return view('auth.signup');
    }
    public function LoginUi(Request $request){
        return view('auth.login');
    }

    public function SignUpForm(Request $request){
      $validated = $request->validate([
            'name' => 'required',
          
            'email' => 'required|email',
            'password' => 'required',
            'contact_number' => 'required',
            'account_type' => 'required',
            'user' => 'required|unique:users,user',

        ]);

            // Hash password
        $validated['password'] = bcrypt($validated['password']);

        // Try saving user
        $user = newuser::create($validated);

    if ($user) {
        return response()->json(['status' => 'success', 'message' => 'Account created successfully.']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Failed to create account.'], 500);
    }
    }

    public function LoginForm(Request $request){
        $credentials = $request->only('user','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
    
            // Choose redirect URL based on account type
            $redirectUrl = match ($user->account_type) {
                'admin' => route('dashboard'),
                'client' => route('CDashboard'),
                default => route('login')
            };
            return response()->json(['status' => 'success','redirect' => $redirectUrl]);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
    }
}
