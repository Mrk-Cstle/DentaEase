<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function Dashboard(Request $request){
        return view('admin.dashboard');
    }
    
    public function Logout(){
        Auth::logout();
        return redirect('/loginui');
    }

    public function Userverify(){
        return view('admin.userverify');
    }
}
