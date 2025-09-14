<?php

namespace App\Http\Controllers;

use App\Models\MedicineMovement;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{
    //
    
public function index(Request $request)
{
    // For sales
    $from = $request->filled('from') ? Carbon::parse($request->from) : now()->startOfMonth();
    $to = $request->filled('to') ? Carbon::parse($request->to) : now()->endOfMonth();

    $sales = Sale::with(['user', 'patient', 'items.medicine'])
        ->whereBetween('created_at', [$from, $to])
        ->get();

    // For inventory
    $invFrom = $request->filled('inv_from') ? Carbon::parse($request->inv_from) : now()->startOfMonth();
    $invTo = $request->filled('inv_to') ? Carbon::parse($request->inv_to) : now()->endOfMonth();

    $movements = MedicineMovement::with(['medicine', 'batch'])
        ->whereBetween('created_at', [$invFrom, $invTo])
        ->get();

    return view('admin.reports.sales', compact('sales', 'from', 'to', 'movements', 'invFrom', 'invTo'));
}

}
