<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use  App\Models\newuser;
use  App\Models\User;

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

    public function Newuserlist(){

        $users = User::all();
        return response()->json(['status'=>'success', 'data'=>$users]);

    }
}
