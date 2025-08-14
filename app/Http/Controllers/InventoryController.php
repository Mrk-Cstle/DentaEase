<?php

namespace App\Http\Controllers;

use App\Models\medicine_batches;
use App\Models\MedicineMovement;
use App\Models\medicines;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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
    if (session('active_branch_id')=='admin') {
        $batches = $medicine->batches()
        // filter by branch
        ->with('store')
        ->where('status', 'active') // only show active
        ->orderBy('expiration_date', 'asc')
        ->get();
    } else {
        $batches = $medicine->batches()
    ->where('store_id', session('active_branch_id')) // filter by branch
    ->where('status', 'active') // only show active
    ->orderBy('expiration_date', 'asc')
    ->get();
    }
    
   

    return view('admin.medicines.show', compact('medicine', 'batches'));
}

public function storebatch(Request $request, medicines $medicine)
{
    $branchid ="{{session('active_branch_id')}}"; 
  
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'expiration_date' => 'required|date',
    ]);

   
    try {
        DB::beginTransaction();

        // Create batch
        $batch = $medicine->batches()->create([
            'store_id'       => $request->store_id,
            'quantity'       => $request->quantity,
            'expiration_date'=> $request->expiration_date,
            'status'         => 'active',
        ]);

        // Log movement
        MedicineMovement::create([
            'store_id'          => $request->store_id,
            'medicine_id'       => $medicine->id,
            'medicine_batch_id' => $batch->id,
            'type'              => 'stock_in',
            'quantity'          => $request->quantity,
            'remarks'           => 'New Batch',
        ]);

        DB::commit();

        return back()->with('success', 'Batch added successfully.');
    } 
    catch (\Exception $e) {
        DB::rollBack();

        // Optionally log the error for debugging
        \Log::error('Error adding batch: ' . $e->getMessage());

        return back()->with(['error' => 'An error occurred while adding the batch. Please try again.'. $e->getMessage()]);
    }
}

public function stockIn(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();
    
            $batch = medicine_batches::findOrFail($id);
            $batch->quantity += $request->quantity;
            $batch->save();

            MedicineMovement::create([
                'store_id'          => $batch->store_id,
                'medicine_id'       => $batch->medicine->id,
                'medicine_batch_id' => $batch->id,
                'type'              => 'stock_in',
                'quantity'          => $request->quantity,
                'remarks'           => 'Manual Add',
            ]);
    
            DB::commit();
    
            return back()->with('success', "Stock increased by {$request->quantity} for Batch #{$batch->id}");
        } catch (\Throwable $e) {
            DB::rollBack();

         
            \Log::error('Error suspend batch: ' . $e->getMessage());
    
            return back()->with(['error' => 'An error occurred while adding the batch. Please try again.'. $e->getMessage()]);
        }
       
    }

    /**
     * Stock Out - Decrease batch quantity
     */
    public function stockOut(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();
            $batch = medicine_batches::findOrFail($id);

        if ($request->quantity > $batch->quantity) {
            return back()->with('error', 'Not enough stock to remove.');
        }

        $batch->quantity -= $request->quantity;
        $batch->save();
        
        MedicineMovement::create([
            'store_id'          => $batch->store_id,
            'medicine_id'       => $batch->medicine->id,
            'medicine_batch_id' => $batch->id,
            'type'              => 'stock_out',
            'quantity'          => $request->quantity,
            'remarks'           => 'Manual Decrease',
        ]);

        DB::commit();

        return back()->with('success', "Stock decreased by {$request->quantity} for Batch #{$batch->id}");
        } catch (\Throwable $e) {
            DB::rollBack();

         
            \Log::error('Error suspend batch: ' . $e->getMessage());
    
            return back()->with(['error' => 'An error occurred while adding the batch. Please try again.'. $e->getMessage()]);
        }

       
    }

    /**
     * Suspend - Mark batch as suspended
     */
    public function suspend($id)
    {
        try {
            DB::beginTransaction();
            $batch = medicine_batches::findOrFail($id);
            $batch->status = 'suspended'; // Make sure you have a `status` column in your DB
            $batch->save();
            
            MedicineMovement::create([
                'store_id'          => $batch->store_id,
                'medicine_id'       => $batch->medicine->id,
                'medicine_batch_id' => $batch->id,
                'type'              => 'suspended',
                'quantity'          => $batch->quantity,
                'remarks'           => 'Manual Suspended',
            ]);
    
            DB::commit();
            return back()->with('success', "Batch #{$batch->id} has been suspended.");
        } catch (\Throwable $e) {
            DB::rollBack();

         
            \Log::error('Error suspend batch: ' . $e->getMessage());
    
            return back()->with(['error' => 'An error occurred while adding the batch. Please try again.'. $e->getMessage()]);
        }
     
    }

    public function expired($id)
    {
        try {
            //code...
            DB::beginTransaction();
            $batch = medicine_batches::findOrFail($id);
            $batch->status = 'expired'; 
            $batch->save();
            
            MedicineMovement::create([
                'store_id'          => $batch->store_id,
                'medicine_id'       => $batch->medicine->id,
                'medicine_batch_id' => $batch->id,
                'type'              => 'expired',
                'quantity'          => $batch->quantity,
                'remarks'           => 'Manual Expired',
            ]);
    
            DB::commit();
            return back()->with('success', "Batch #{$batch->id} has been mark expired."); 
        } catch (\Throwable $e) {
            DB::rollBack();

         
            \Log::error('Error expired batch: ' . $e->getMessage());
    
            return back()->with(['error' => 'An error occurred while adding the batch. Please try again.'. $e->getMessage()]);
        }
        
    }
}
