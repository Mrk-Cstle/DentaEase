<?php

namespace App\Http\Controllers;
use App\Models\Store;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    //

    public function AddBranch(Request $request){

        $branch = $request->input('branch');
        $address = $request->input('address');
        try {
            
            $store = new Store();
            $store->name = $branch;
            $store->address = $address;
            $store->save(); 

             return response()->json(['status' => 'success', 'message' => 'Branch added successfully']);
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' =>  $e->getMessage()]);
        }

    }

      public function Branchlist(Request $request)
    {
        $perPage = 5;
        $search = $request->input('search');


        $query = Store::query();
       if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('user', 'like', "%{$search}%")
              ;
        });
    }

    $branch = $query->paginate($perPage);
    
        return response()->json([
            'status' => 'success',
            'data' => $branch->items(),
            'pagination' => [
                'total' => $branch->total(),
                'per_page' => $branch->perPage(),
                'current_page' => $branch->currentPage(),
                'last_page' => $branch->lastPage(),
                'next_page_url' => $branch->nextPageUrl(),
                'prev_page_url' => $branch->previousPageUrl(),
            ]
        ]);
    }
}
