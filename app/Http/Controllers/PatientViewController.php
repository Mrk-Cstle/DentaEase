<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PatientViewController extends Controller
{
    //
   public function ViewPatient(Request $request)
{
    $perPage = 5;
    $search = $request->input('search');
    $print = $request->input('print'); // <-- check for print flag

    $query = User::where('account_type', 'patient')
        ->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('user', 'like', "%{$search}%");
        });

    // If printing, return all results (no pagination)
    if ($print) {
        $patients = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $patients,
            'pagination' => null
        ]);
    }

    // Default with pagination
    $patients = $query->paginate($perPage);

    return response()->json([
        'status' => 'success',
        'data' => $patients->items(),
        'pagination' => [
            'total' => $patients->total(),
            'per_page' => $patients->perPage(),
            'current_page' => $patients->currentPage(),
            'last_page' => $patients->lastPage(),
            'next_page_url' => $patients->nextPageUrl(),
            'prev_page_url' => $patients->previousPageUrl(),
        ]
    ]);
}

           
     
}
