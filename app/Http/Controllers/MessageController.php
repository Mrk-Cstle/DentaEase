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
    $patientId = auth()->id();

    $branches = Store::with(['messages' => function ($q) use ($patientId) {
        $q->where(function ($query) use ($patientId) {
            $query->where('sender_id', $patientId)
                  ->orWhere('receiver_id', $patientId);
        })
        ->latest()
        ->limit(1);
    }])
    ->get()
    ->map(function ($branch) {
        return [
            'id' => $branch->id,
            'name' => $branch->name,
            'latest_message' => $branch->messages->first(),
        ];
    })
    // ✅ Sort newest to oldest based on message timestamp
    ->sortByDesc(function ($branch) {
        return optional($branch['latest_message'])->created_at ?? now()->subYears(100);
    })
    ->values();

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
    $storeId = session('active_branch_id');

    // Get all patients
    $patients = User::where('account_type', 'patient')
        ->get()
        ->map(function ($patient) use ($storeId) {
            // Get latest message between this patient and the branch
            $latestMessage = \App\Models\Message::where('store_id', $storeId)
                ->where(function ($q) use ($patient) {
                    $q->where('sender_id', $patient->id)
                      ->orWhere('receiver_id', $patient->id);
                })
                ->latest('created_at')
                ->with('store')
                ->first();

            return [
                'id' => $patient->id,
                'full_name' => trim($patient->name . ' ' . $patient->lastname),
                'latest_message' => $latestMessage?->message,
                'latest_message_time' => $latestMessage?->created_at
                    ? $latestMessage->created_at->diffForHumans()
                    : null,
                'branch_name' => $latestMessage?->store?->name,
                'sort_time' => $latestMessage?->created_at ?? now()->subYears(100)
            ];
        })
        // ✅ Sort by latest message timestamp descending (new → old)
        ->sortByDesc('sort_time')
        ->values();

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
