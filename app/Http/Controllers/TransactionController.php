<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function index(Request $request, $storeId)
    {
        $query = Sale::with('patient', 'items.batch.medicine', 'items.medicine')
            ->where('store_id', $storeId);

        // Date filter
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $sales = $query->latest()->paginate(15);
        $store = Store::find($storeId);
        return view('admin.transactions.index', compact('sales', 'storeId', 'store'));
    }
}
