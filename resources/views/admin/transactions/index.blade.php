@extends('layout.navigation')

@section('title', 'Transaction History - Store ' . $storeId)

@section('main-content')
<script src="//unpkg.com/alpinejs" defer></script>

<div x-data="{ open: false, receipt: null }">

    <div class="flex flex-row justify-between">
        <h1 class="text-2xl font-bold mb-4">Transaction History</h1>
        <button onclick="history.back()" 
        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow-sm hover:bg-gray-300 transition">
        ⬅ Back
    </button>
    </div>
   
    <!-- Filter -->
    <form method="GET" class="mb-4 flex gap-2">
        <input type="date" name="from" value="{{ request('from') }}" class="border rounded p-2">
        <input type="date" name="to" value="{{ request('to') }}" class="border rounded p-2">
        <button class="px-4 py-2 bg-sky-600 text-white rounded">Filter</button>
    </form>

    <!-- Transactions Table -->
    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Date</th>
                <th class="border px-3 py-2">Patient</th>
                <th class="border px-3 py-2">Total</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td class="border px-3 py-2">{{ $sale->id }}</td>
                    <td class="border px-3 py-2">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                    <td class="border px-3 py-2">{{ $sale->patient->name ?? 'Walk-in' }}</td>
                    <td class="border px-3 py-2">₱{{ number_format($sale->total_amount, 2) }}</td>
                    <td class="border px-3 py-2 text-center">
                        <button 
                            @click="open = true; receipt = @js($sale)" 
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            View
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">
                        No transactions found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Receipt Modal -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-lg shadow-lg w-3/4 max-w-2xl p-6 relative">
            <!-- Close -->
            <button @click="open=false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">✖</button>

            <div id="receipt-modal">
                <!-- Header -->
                <div class="text-center mb-6">
                    <h1 class="text-xl font-bold">SANTIAGO-AMANCIO DENTAL CLINIC</h1>
                    <p>{{ $store->name }}<br>{{ $store->address }}</p>
                </div>

                <!-- Info -->
                <div class="flex justify-between mb-4 text-sm">
                    <div>
                        <p><strong>Patient:</strong> <span x-text="receipt?.patient?.name ?? 'Walk-in'"></span></p>
                    </div>
                    <div>
                        <p><strong>Receipt No:</strong> <span x-text="receipt?.id"></span></p>
                        <p><strong>Date:</strong> <span x-text="new Date(receipt?.created_at).toLocaleDateString()"></span></p>
                    </div>
                </div>

                <!-- Table -->
                <table class="w-full border border-gray-400 text-sm mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">Description</th>
                            <th class="border px-2 py-1 text-center">Qty</th>
                            <th class="border px-2 py-1 text-right">Unit Price</th>
                            <th class="border px-2 py-1 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in receipt.items" :key="item.id">
                            <tr>
                                <td class="border px-2 py-1" 
    x-text="item.medicine?.name ?? item.medicine_batch?.medicine?.name ?? 'N/A'">
</td>
                                <td class="border px-2 py-1 text-center" x-text="item.quantity"></td>
                                <td class="border px-2 py-1 text-right">₱<span x-text="parseFloat(item.price).toFixed(2)"></span></td>
                                <td class="border px-2 py-1 text-right">₱<span x-text="parseFloat(item.subtotal).toFixed(2)"></span></td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Total -->
                <div class="text-right font-bold text-lg border-t pt-2 foot">
                    <span>
                        Total: ₱
                        <span x-text="Number(receipt?.total_amount ?? 0).toFixed(2)"></span>
                    </span>
                </div>
                

                <!-- Seller -->
                <div class="text-right mt-8 foot">
                    <p>__________________________</p>
                    <p><span x-text="receipt?.user?.name"></span>, DMD</p>
                </div>
            </div>

            <!-- Print -->
            <div class="mt-6 text-right">
                <button onclick="printReceipt()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"> 🖨 Print </button>
            </div>
        </div>
    </div>
</div>

<script>
function printReceipt() {
    let modalContent = document.getElementById("receipt-modal").innerHTML;
    let printWindow = window.open("", "", "width=800,height=600");
    printWindow.document.write(`
        <html>
        <head>
            <style>
                @page { margin: 10mm; }
                body { font-family: Arial, sans-serif; margin: 0; padding: 10px; font-size: 12px; }
                h1 { font-size: 16px; margin: 0; }
                table { border-collapse: collapse; width: 100%; }
                td, th { border: 1px solid #000; padding: 4px; font-size: 12px; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class="receipt">${modalContent}</div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}
</script>
@endsection
