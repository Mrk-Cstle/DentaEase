<?php

namespace App\Http\Controllers;

use App\Models\User;   

use App\Models\Message;
use App\Models\Store;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Show the frontend Blade (UI)
    public function index()
    {
        return view('admin.chat'); // your Blade frontend
    }

    public function branches()
    {
        $branches = Store::all();
        return response()->json($branches);
    }

    public function fetch($storeId, $userId)
    {
        $messages = Message::where('store_id', $storeId)
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    
        return response()->json($messages);
    }
    
    

    public function store(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
        'user_id'  => 'required|exists:users,id', // this is the patient
        'message'  => 'required|string',
    ]);

    $message = Message::create([
        'store_id'   => $request->store_id,
        'sender_id'  => Auth::id(),         // admin (logged in user)
        'receiver_id'=> $request->user_id,  // patient
        'message'    => $request->message,
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => $message
    ]);
}



public function patients()
{
    // $patients = User::whereHas('messages')
    //     ->with(['messages' => function ($q) {
    //         $q->latest()->limit(1);
    //     }])
    //     ->get();

    $storeId = session('active_branch_id'); // current branch

    $patients = User::where('account_type', 'patient')
        ->with(['messages' => function ($q) use ($storeId) {
            $q->where('store_id', $storeId)->latest()->limit(1);
        }])
        ->get();


    return response()->json($patients);
}


public function patientIndex()
{
    return view('client.chat'); // separate blade for patients
}

public function patientMessages($storeId)
{
    $userId = Auth::id(); // logged-in patient
    $messages = Message::where('store_id', $storeId)
        ->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json($messages);
}

public function sendMessage(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
        'message'  => 'required|string',
    ]);

    $message = Message::create([
        'store_id'   => $request->store_id,
        'sender_id'  => Auth::id(),           // patient
        'receiver_id'=> $request->store_id,   // ❗ if stores are users, point to store-user, else leave null
        'message'    => $request->message,
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => $message
    ]);
}

}
