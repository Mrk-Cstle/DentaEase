<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use  App\Models\newuser;
use  App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailresponse;


class AdminController extends Controller
{
    //
    public function Viewuser(Request $request){
        $id = $request->input('id');
        $type = $request->input('type');
        $user = '';
        if ($type == 'User'){
        $user = User::find($id);
        }
        if ($type == 'newuser'){
            $user = newuser::find($id);
            }
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }
    public function Dashboard(Request $request){
        return view('admin.dashboard');
    }
    
    public function Logout(){
        Auth::logout();
        return redirect('/loginui');
    }
    public function Useraccount(){
        return view('admin.useraccount');
    }

    public function Userverify(){
        return view('admin.userverify');
    }
    public function Profile(){
        return view('admin.adminprofile');
    }
     public function Branch(){
        return view('admin.branch');
    }
    public function Newuserlist(Request $request)
    {
        $perPage = 5;
        $search = $request->input('search');


        $query = newuser::query();
       if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('user', 'like', "%{$search}%")
              ;
        });
    }

    $users = $query->paginate($perPage);
    
        return response()->json([
            'status' => 'success',
            'data' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
            ]
        ]);
    }

    public function Adduser(Request $request){
        $name = $request->input('name');
        $user = $request->input('user');
        $position = $request->input('position');
        $password = $user . 'Dentaease';
        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->user = $request->input('user');
            $user->position = $request->input('position');
            $user->account_type = 'admin';
            $user->password = $password;
            $user->save();
    
            return response()->json(['status' => 'success', 'message' => 'User added successfully']);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['message' => 'Username already exists.'], 409);
            }
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    public function Approveuser(Request $request){
        $id = $request->input('userid'); 
        $accounttype = "patient";
          // Find the user in the newuser table
        $newUser = newuser::findOrFail($id);

        // Create a new record in the users table
        $user = new User();
        $user->name = $newUser->name;
 
        $user->email = $newUser->email;
        $user->birth_date = $newUser->birth_date;
        $user->contact_number = $newUser->contact_number;
        $user->password = $newUser->password;
        $user->user = $newUser->user; // Optional: hash again if needed
        // Add other fields as needed
        $user->account_type = $accounttype;
        $user->save();

        // Delete the user from the newuser table
        $newUser->delete();
        Mail::to($user->email)->send(new mailresponse($user));
        return response()->json([
            'status' => 'success',
            'message' => 'User approved and moved to users table.'
        ]);
        
    }

    public function removeFaceToken(Request $request)
    {
        $user = Auth::user();
        $user->face_token = null;
        $user->save();

        return response()->json([ 'status' => 'success','message' => 'Face token removed successfully.']);
    }
}
