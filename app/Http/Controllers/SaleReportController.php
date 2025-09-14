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

     // Get branch from session
    $activeBranchId = session('active_branch_id');

    // For sales
    $from = $request->filled('from') ? Carbon::parse($request->from) : now()->startOfMonth();
    $to = $request->filled('to') ? Carbon::parse($request->to) : now()->endOfMonth();

    $salesQuery = Sale::with(['user', 'patient', 'items.medicine'])
        ->whereBetween('created_at', [$from, $to]);

    if ($activeBranchId) {
        $salesQuery->where('store_id', $activeBranchId); // 👈 filter by branch
    }

    $sales = $salesQuery->get();

    // For inventory
    $invFrom = $request->filled('inv_from') ? Carbon::parse($request->inv_from) : now()->startOfMonth();
    $invTo = $request->filled('inv_to') ? Carbon::parse($request->inv_to) : now()->endOfMonth();

    $movementsQuery = MedicineMovement::with(['medicine', 'batch'])
        ->whereBetween('created_at', [$invFrom, $invTo]);

    if ($activeBranchId) {
        $movementsQuery->where('store_id', $activeBranchId); // 👈 filter by branch
    }

    $movements = $movementsQuery->get();

    return view('admin.reports.sales', compact('sales', 'from', 'to', 'movements', 'invFrom', 'invTo'));
}

}
