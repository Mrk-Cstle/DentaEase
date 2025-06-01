<?php

namespace App\Http\Controllers;
use App\Models\Store;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
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

    public function getBranchDetails(Request $request)
{
    $storeId = $request->input('id');

    $store = Store::with(['staff' => function ($query) {
        $query->select('users.id', 'users.name', 'store_staff.position')
              ->orderBy('store_staff.position');
    }])->find($storeId);

    if (!$store) {
        return response()->json(['status' => 'error', 'message' => 'Branch not found.'], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'id' => $store->id,
            'name' => $store->name,
            'address' => $store->address,
            'staff' => $store->staff
        ]
    ]);
}

public function addUser(Request $request, Store $branch)
{
   

    try {
        $user = User::findOrFail($request->user_id); 

        $branch->staff()->attach($user->id, [
            'position' => $request->position,
        ]);

        return response()->json(['status' => 'success', 'message' => 'User added successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}

public function getUsersByPosition(Request $request)
{
    $position = $request->position;

   $users = User::where('position', $position)
             ->get(['id', 'name']);


    return response()->json([
        'status' => 'success',
        'data' => $users
    ]);
}
public function removeUser(Request $request, $storeId)
{
    $userId = $request->input('user_id');

    $store = Store::findOrFail($storeId);
    $user = User::findOrFail($userId);

    // Assuming many-to-many: store_user pivot
    $store->staff()->detach($userId);

    return response()->json([
        'status' => 'success',
        'message' => 'User removed from store.'
    ]);
}
 public function DeleteBranch(Request $request){
            $id = $request->id;
            $user = Store::find($id);
            if ($user) {
               
                $user->delete();
    
               
                return response()->json([
                    'status' => 'success',
                    'message' => 'Branch deleted successfully',
                ] );
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Branch not found',
                ] );
            }
        }
}
