<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\medicine_batches;
use App\Models\MedicineMovement;

use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    //

      public function index($storeId)
    {
       $medicines = medicine_batches::with('medicine')
    ->where('store_id', $storeId)
    ->where('quantity', '>', 0)
    ->where('status', 'active') 
    ->get()
    ->groupBy('medicine_id')
    ->map(function ($batches) {
        $medicine = $batches->first()->medicine;
        return [
            'id' => $medicine->id,
            'name' => $medicine->name,
            'unit' => $medicine->unit,
            'price' => $medicine->price,
            'available_quantity' => $batches->sum('quantity'),
        ];
    });
        return view('admin.pos.index', compact('medicines', 'storeId'));
    }

    // Add to cart (simple demo — real apps use session or Livewire)
    public function addToCart(Request $request, $storeId)
    {
        $request->validate([
            'medicine_id' => 'required|integer',
            'quantity'    => 'required|integer|min:1',
        ]);

        $batch = medicine_batches::where('medicine_id', $request->medicine_id)
            ->where('store_id', $storeId)
            ->where('quantity', '>=', $request->quantity)
            ->orderBy('expiration_date', 'asc') // FIFO
            ->first();

        if (!$batch) {
            return back()->withErrors(['stock' => 'Not enough stock available!']);
        }

        // Save in session cart
        $cart = session()->get('cart', []);
        $cart[] = [
            'medicine_id' => $request->medicine_id,
            'batch_id'    => $batch->id,
            'quantity'    => $request->quantity,
            'price'       => $batch->medicine->price,
            'subtotal'    => $batch->medicine->price * $request->quantity,
        ];
        session()->put('cart', $cart);

        return redirect()->route('pos.index', $storeId)->with('success', 'Item added to cart!');
    }

    // Checkout and save sale
    public function checkout(Request $request, $storeId)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Cart is empty!']);
        }

        DB::transaction(function () use ($cart, $storeId) {
            $sale = Sale::create([
                'store_id'     => $storeId,
                'user_id'      => auth()->id(),
                'total_amount' => collect($cart)->sum('subtotal'),
                'status'       => 'completed',
            ]);

            foreach ($cart as $item) {
                // Create Sale Item
                $saleItem = SaleItem::create([
                    'sale_id'           => $sale->id,
                    'medicine_id'       => $item['medicine_id'],
                    'medicine_batch_id' => $item['batch_id'],
                    'quantity'          => $item['quantity'],
                    'price'             => $item['price'],
                    'subtotal'          => $item['subtotal'],
                ]);

                // Deduct from batch
                        // Deduct from batch
            $batch = medicine_batches::find($item['batch_id']);
            $batch->decrement('quantity', $item['quantity']);

            // ✅ If batch is now 0, suspend it
            if ($batch->quantity <= 0) {
                $batch->status = 'suspended';
                $batch->save();
            }


                // Log movement
                MedicineMovement::create([
                    'medicine_id'       => $item['medicine_id'],
                    'store_id'          => $storeId,
                    'medicine_batch_id' => $item['batch_id'],
                    'type'              => 'stock_out',
                    'quantity'          => -$item['quantity'],
                    'remarks'           => "Sale #{$sale->id}",
                ]);
            }
        });

        // Clear cart
        session()->forget('cart');

        return redirect()->route('pos.index', $storeId)->with('success', 'Sale completed successfully!');
    }

    public function updateCart(Request $request, $storeId)
{
    $request->validate([
        'index' => 'required|integer',
        'quantity' => 'required|integer|min:1',
    ]);

    $cart = session()->get('cart', []);
    if (!isset($cart[$request->index])) {
        return back()->withErrors(['cart' => 'Item not found in cart!']);
    }

    $item = $cart[$request->index];

    // Check stock availability
    $batch = medicine_batches::find($item['batch_id']);
    if (!$batch || $batch->quantity < $request->quantity) {
        return back()->withErrors(['cart' => 'Not enough stock available!']);
    }

    // Update quantity + subtotal
    $cart[$request->index]['quantity'] = $request->quantity;
    $cart[$request->index]['subtotal'] = $item['price'] * $request->quantity;

    session()->put('cart', $cart);

    return back()->with('success', 'Cart updated!');
}

// Remove item from cart
public function removeFromCart(Request $request, $storeId)
{
    $request->validate([
        'index' => 'required|integer',
    ]);

    $cart = session()->get('cart', []);
    if (isset($cart[$request->index])) {
        unset($cart[$request->index]);
        session()->put('cart', array_values($cart)); // reindex
    }

    return back()->with('success', 'Item removed from cart!');
}

}
