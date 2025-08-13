<?php

namespace App\Http\Controllers;

use App\Models\medicines;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
       public function inventory(){
        return view('admin.inventory');
    }

     public function InventoryList(Request $request){

        $perPage = 5;

        $search = $request->input('search');
        // $position = $request->input('position');
       
        $query = medicines::where(function($q) use ($search){
            $q->where('name', 'like', "%{$search}%");
        
        });
        
        
    // if ($position) {
    //     $query->where('position', $position);
    // }
        $item = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $item->items(),
            'pagination' => [
                'total' => $item->total(),
                'per_page' => $item->perPage(),
                'current_page' => $item->currentPage(),
                'last_page' => $item->lastPage(),
                'next_page_url' => $item->nextPageUrl(),
                'prev_page_url' => $item->previousPageUrl(),
            ]
        ]);

    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'unit' => 'required|string',
        'price' => 'required|numeric',
        'description' => 'nullable|string',
    ]);

    medicines::create($request->all());

    return response()->json(['status' => 'success','message'=>'Medicine added']);
}

public function show(medicines $medicine)
{
    $batches = $medicine->batches()
        ->where('store_id', Auth::user()->store_id)
        ->orderBy('expiration_date')
        ->get();

    return view('medicines.show', compact('medicine', 'batches'));
}

public function addBatch(Request $request, medicines $medicine)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'expiration_date' => 'required|date',
    ]);

    medicines::create([
        'medicine_id' => $medicine->id,
        'store_id' => Auth::user()->store_id,
        'quantity' => $request->quantity,
        'expiration_date' => $request->expiration_date,
    ]);

    return back()->with('success', 'Batch added successfully.');
}

public function showbatch(medicines $medicine)
{
    $batches = $medicine->batches()
    ->where('store_id', session('active_branch_id')) // filter by branch
    ->where('status', 'active') // only show active
    ->orderBy('expiration_date', 'asc')
    ->get();

    return view('admin.medicines.show', compact('medicine', 'batches'));
}

public function storebatch(Request $request, medicines $medicine)
{
    $branchid ="{{session('active_branch_id')}}"; 
  
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'expiration_date' => 'required|date',
    ]);

    $medicine->batches()->create([
        'store_id' => $request->store_id, // or select store
        'quantity' => $request->quantity,
        'expiration_date' => $request->expiration_date,
        'status'=> "suspended",
        
    ]);

    return back()->with('success', 'Batch added successfully.');
}
}
