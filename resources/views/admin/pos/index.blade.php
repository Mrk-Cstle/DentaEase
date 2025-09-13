@extends('layout.navigation')

@section('title', 'POS - Store ' . $storeId)

@section('main-content')
<script src="//unpkg.com/alpinejs" defer></script>

<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-sky-600 mb-6">Point of Sale</h1>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- POS Table -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-sky-200 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-sky-500 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Medicine</th>
                    <th class="px-4 py-3 text-center">Unit</th>
                    <th class="px-4 py-3 text-center">Price</th>
                    <th class="px-4 py-3 text-center">Available</th>
                    <th class="px-4 py-3 text-center">Quantity</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                <tr class="border-b hover:bg-sky-50">
                    <form method="POST" action="{{ route('pos.add', $storeId) }}">
                        @csrf
                        <input type="hidden" name="medicine_id" value="{{ $medicine['id'] }}">
                        <td class="px-4 py-3 font-medium">{{ $medicine['name'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $medicine['unit'] }}</td>
                        <td class="px-4 py-3 text-center">₱{{ number_format($medicine['price'], 2) }}</td>
                        <td class="px-4 py-3 text-center text-sky-700 font-semibold">{{ $medicine['available_quantity'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <input type="number"
                                   name="quantity"
                                   min="1"
                                   max="{{ $medicine['available_quantity'] }}"
                                   class="w-20 p-2 border rounded-lg text-center focus:ring-2 focus:ring-sky-400">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button class="px-4 py-2 bg-sky-500 text-white rounded-xl hover:bg-sky-600 transition">
                                Add
                            </button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Checkout Area -->
    <div class="bg-sky-50 border border-sky-200 p-6 rounded-2xl shadow">
        <div class="space-y-2 mb-4">
    @php $cart = session('cart', []); @endphp
    @forelse($cart as $i => $item)
        <div class="flex justify-between items-center bg-white p-3 rounded-lg shadow">
            <div class="flex items-center gap-3">
                <!-- Medicine Info -->
                <span>{{ $item['medicine_name'] }} (₱{{ number_format($item['price'],2) }})</span>
            </div>

            <div class="flex items-center gap-2">
                <!-- Update Quantity -->
                <form method="POST" action="{{ route('pos.update', $storeId) }}" class="flex items-center gap-1">
                    @csrf
                    <input type="hidden" name="index" value="{{ $i }}">
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                           min="1" max="999"
                           class="w-16 p-2 border rounded-lg text-center focus:ring-2 focus:ring-sky-400">
                    <button class="px-2 py-1 bg-sky-500 text-white rounded hover:bg-sky-600">⟳</button>
                </form>

                <!-- Remove -->
                <form method="POST" action="{{ route('pos.remove', $storeId) }}">
                    @csrf
                    <input type="hidden" name="index" value="{{ $i }}">
                    <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">✕</button>
                </form>
            </div>

            <span class="font-bold">₱{{ number_format($item['subtotal'], 2) }}</span>
        </div>
    @empty
        <p class="text-gray-500">No items added yet.</p>
    @endforelse
</div>
        <div class="flex justify-between items-center mt-4">
            <span class="text-lg font-bold text-sky-800">
                Total: ₱{{ number_format(collect($cart)->sum('subtotal'), 2) }}
            </span>
            <form method="POST" action="{{ route('pos.checkout', $storeId) }}">
                @csrf
                 <label for="patient_id" class="block mb-2">Customer (Patient)</label>
                <select name="patient_id" id="patient_id" class="border rounded p-2 w-full">
                    <option value="">Walk-in</option>
                    @foreach(\App\Models\User::where('account_type', 'patient')->get() as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
                <button class="px-6 py-3 bg-sky-600 text-white rounded-2xl hover:bg-sky-700 transition">
                    Checkout
                </button>
            </form>
        </div>
    </div>
</div>

<div x-data="{ open: false, receipt: @js(session('receipt')) }" x-init="if(receipt){ open=true }">
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-lg shadow-lg w-3/4 max-w-2xl p-6 relative" >
            <!-- Close -->
            <button @click="open=false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">✖</button>
            <div id="receipt-modal">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-bold">SANTIAGO-AMANCIO DENTAL CLINIC</h1>
                <p>{{$store->name}}<br>{{$store->address}}</p>
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
                        <td class="border px-2 py-1" x-text="item.medicine.name"></td>


                            <td class="border px-2 py-1 text-center" x-text="item.quantity"></td>
                           <td class="border px-2 py-1 text-right">
                                ₱<span x-text="parseFloat(item.price).toFixed(2)"></span>
                            </td>
                            <td class="border px-2 py-1 text-right">
                                ₱<span x-text="parseFloat(item.subtotal).toFixed(2)"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

      <!-- Total -->
<div class="text-right font-bold text-lg border-t pt-2 foot">
    <span>Total: ₱<span x-text="receipt.total_amount.toFixed(2)"></span></span>
</div>

<!-- Seller -->
<div class="text-right mt-8 foot">
    <p>__________________________</p>
    <p><span x-text="receipt?.user?.name"></span>, DMD</p>
</div>

</div>
            <!-- Print -->
            <div class="mt-6 text-right">
             <button onclick="printReceipt()" 
    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    🖨 Print
</button>
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
                    @page {
                        margin: 10mm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 10px;
                        font-size: 12px;
                    }
                    h1 { font-size: 16px; margin: 0; }
                    table { border-collapse: collapse; width: 100%; }
                    td, th { border: 1px solid #000; padding: 4px; font-size: 12px; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    
                </style>
            </head>
            <body>
                <div class="receipt">
                    ${modalContent}
                </div>
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
