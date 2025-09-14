@extends('layout.navigation')

@section('title', 'Reports')
@section('main-content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #tab-content > div {
        display: none !important;
    }
    #tab-content > div.printable {
        display: block !important;
    }
    #tab-content > div.printable, #tab-content > div.printable * {
        visibility: visible;
    }
    #tab-content > div.printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<div x-data="{ tab: 'sales' }" 
     x-init="$watch('tab', value => {
         document.querySelectorAll('#tab-content > div').forEach(el => el.classList.remove('printable'));
         document.getElementById(value + '-printable').classList.add('printable');
     })">

    <!-- Tabs -->
    <div class="flex gap-2 border-b mb-4">
        <button 
            @click="tab = 'sales'" 
            :class="tab === 'sales' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" 
            class="px-4 py-2 font-semibold">
            Sales Report
        </button>
        <button 
            @click="tab = 'inventory'" 
            :class="tab === 'inventory' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" 
            class="px-4 py-2 font-semibold">
            Inventory Movement
        </button>
    </div>

    <!-- Tab Content -->
    <div id="tab-content">
        <!-- Sales Report -->
        <div x-show="tab === 'sales'" id="sales-printable" class="printable">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Sales Report</h1>
                <button onclick="window.print()" 
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 print:hidden">
                    Print
                </button>
            </div>

            <form method="GET" class="mb-4 flex gap-2 items-center">
                <label>From: <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="border rounded px-2 py-1"></label>
                <label>To: <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="border rounded px-2 py-1"></label>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">Filter</button>
            </form>

            <table class="table-auto border-collapse border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">Sale ID</th>
                        <th class="border px-2 py-1">Date</th>
                        <th class="border px-2 py-1">Cashier</th>
                        <th class="border px-2 py-1">Patient</th>
                        <th class="border px-2 py-1">Medicine</th>
                        <th class="border px-2 py-1">Qty</th>
                        <th class="border px-2 py-1">Price</th>
                        <th class="border px-2 py-1">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="border px-2 py-1">{{ $sale->id }}</td>
                            <td class="border px-2 py-1">{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td class="border px-2 py-1">{{ $sale->user->name }}</td>
                            <td class="border px-2 py-1">{{ $sale->patient?->name ?? 'Walk-in' }}</td>
                            <td class="border px-2 py-1">{{ $item->medicine->name }}</td>
                            <td class="border px-2 py-1">{{ $item->quantity }}</td>
                            <td class="border px-2 py-1">{{ number_format($item->price, 2) }}</td>
                            <td class="border px-2 py-1">{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
                
            </table>
            <div class="mt-4 text-right font-bold text-lg">
        Grand Total: {{ number_format($sales->sum('total_amount'), 2) }}
    </div>
        </div>

<!-- Inventory Movement -->
<div x-show="tab === 'inventory'" id="inventory-printable">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Inventory Movement</h1>
        <button onclick="window.print()" 
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 print:hidden">
            Print
        </button>
    </div>

    <!-- Date Range Filter -->
    <form method="GET" class="mb-4 flex gap-2 items-center ">
        <label>
            From: 
            <input type="date" name="inv_from" value="{{ request('inv_from', now()->startOfMonth()->format('Y-m-d')) }}" class="border rounded px-2 py-1">
        </label>
        <label>
            To: 
            <input type="date" name="inv_to" value="{{ request('inv_to', now()->endOfMonth()->format('Y-m-d')) }}" class="border rounded px-2 py-1">
        </label>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">
            Filter
        </button>
    </form>

    @php
        // Apply date filtering in controller instead of view, 
        // but we keep fallback here just in case
        $groupedMovements = $movements->groupBy('type');
    @endphp

    @foreach (['stock_in' => 'Stock In', 'stock_out' => 'Stock Out', 'expired' => 'Expired'] as $type => $label)
        <h2 class="text-xl font-semibold mt-6 mb-2">{{ $label }}</h2>
        <table class="table-auto border-collapse border border-gray-300 w-full mb-6">
            <thead>
                <tr>
                    <th class="border px-2 py-1">Date</th>
                    <th class="border px-2 py-1">Medicine</th>
                    <th class="border px-2 py-1">Batch</th>
                    <th class="border px-2 py-1">Quantity</th>
                    <th class="border px-2 py-1">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalQty = 0;
                @endphp
                @forelse ($groupedMovements[$type] ?? [] as $movement)
                    <tr>
                        <td class="border px-2 py-1">{{ $movement->created_at->format('Y-m-d') }}</td>
                        <td class="border px-2 py-1">{{ $movement->medicine->name }}</td>
                        <td class="border px-2 py-1">{{ $movement->batch?->medicine_id ?? 'N/A' }}</td>
                        <td class="border px-2 py-1">{{ $movement->quantity }}</td>
                        <td class="border px-2 py-1">{{ $movement->remarks }}</td>
                    </tr>
                    @php
                        $totalQty += $movement->quantity;
                    @endphp
                @empty
                    <tr>
                        <td colspan="5" class="border px-2 py-2 text-center text-gray-500">No {{ $label }} records</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right font-bold px-2 py-1">Total</td>
                    <td class="border px-2 py-1 font-bold">{{ $totalQty }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endforeach
</div>


    </div>
</div>
@endsection
