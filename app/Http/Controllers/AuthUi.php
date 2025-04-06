<?php

namespace App\Http\Controllers;
use App\Models\User;
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
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'contact_number' => 'required',
            'user' => 'required',

        ]);

        User::create($request->all());
        return redirect()->route('login');
    }

    public function LoginForm(Request $request){
        $credentials = $request->only('user','password');

        if(Auth::attempt($credentials)){
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
    }
}
