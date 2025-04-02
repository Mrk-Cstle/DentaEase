<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthUi extends Controller
{
    public function SignUpUi(Request $request){
        return view('auth.signup');
    }
    public function LoginUi(Request $request){
        return view('auth.login');
    }
}
