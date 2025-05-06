<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    //
    public function ViewStaff(Request $request){

        $perPage = 5;

        $search = $request->input('search');

       
        $query = User::where('account_type', 'admin')->where(function($q) use ($search){
            $q->where('name', 'like', "%{$search}%");
            $q->orWhere('user', 'like', "%{$search}%");
        });

        $staff = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $staff->items(),
            'pagination' => [
                'total' => $staff->total(),
                'per_page' => $staff->perPage(),
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'next_page_url' => $staff->nextPageUrl(),
                'prev_page_url' => $staff->previousPageUrl(),
            ]
        ]);

    }
}
