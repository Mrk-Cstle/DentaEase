@extends('layout.navigation')

@section('title', 'POS - Store ' . $storeId)

@section('main-content')
<script src="//unpkg.com/alpinejs" defer></script>

<div class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Medicines List (Left Side) -->
    <div class="lg:col-span-2 space-y-4">
        <div class="flex flex-row justify-between">
            <h1 class="text-3xl font-bold text-sky-600 mb-4">Point of Sale</h1>
            <button 
            onclick="window.location='{{ route('transactions.index', ['storeId' => session('active_branch_id')]) }}'" 
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow-sm hover:bg-gray-300 transition">
            Transaction History
        </button>
        
        
        </div>
       

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

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($medicines as $medicine)
                <div class="bg-white rounded-xl shadow border p-4 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-sky-700">{{ $medicine['name'] }}</h2>
                        <p class="text-sm text-gray-600">{{ $medicine['unit'] }}</p>
                        <p class="mt-2 text-lg font-bold text-sky-600">₱{{ number_format($medicine['price'], 2) }}</p>
                        <p class="text-sm text-gray-500">Available: 
                            <span class="font-semibold text-sky-700">{{ $medicine['available_quantity'] }}</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('pos.add', $storeId) }}" class="mt-3 flex gap-2">
                        @csrf
                        <input type="hidden" name="medicine_id" value="{{ $medicine['id'] }}">
                        <input type="number" name="quantity" min="1" 
                            max="{{ $medicine['available_quantity'] }}" 
                            class="w-20 p-2 border rounded-lg text-center focus:ring-2 focus:ring-sky-400">
                        <button class="flex-1 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">
                            Add
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cart + Checkout (Right Side) -->
    <div class="bg-white rounded-2xl shadow-lg border border-sky-200 p-6 sticky top-6 h-fit">
        <h2 class="text-xl font-bold text-sky-700 mb-4">Cart</h2>

        @php $cart = session('cart', []); @endphp
        <div class="space-y-3 mb-4">
            @forelse($cart as $i => $item)
                <div class="flex justify-between items-center bg-sky-50 p-3 rounded-lg shadow">
                    <div>
                        <p class="font-semibold">{{ $item['medicine_name'] }}</p>
                        <p class="text-sm text-gray-600">₱{{ number_format($item['price'],2) }}</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Update -->
                        <form method="POST" action="{{ route('pos.update', $storeId) }}" class="flex items-center gap-1">
                            @csrf
                            <input type="hidden" name="index" value="{{ $i }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                min="1" max="999"
                                class="w-14 p-1 border rounded-lg text-center">
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

        <!-- Total + Checkout -->
        <div class="border-t pt-4">
            <p class="text-lg font-bold text-sky-800 mb-4">
                Total: ₱{{ number_format(collect($cart)->sum('subtotal'), 2) }}
            </p>
            <form method="POST" action="{{ route('pos.checkout', $storeId) }}" class="space-y-3">
                @csrf
                <label for="patient_id" class="block text-sm font-medium">Customer (Patient)</label>
                <select name="patient_id" id="patient_id" class="border rounded p-2 w-full">
                    <option value="">Walk-in</option>
                    @foreach(\App\Models\User::where('account_type', 'patient')->get() as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
                <button class="w-full px-6 py-3 bg-sky-600 text-white rounded-2xl hover:bg-sky-700 transition">
                    Checkout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ✅ Receipt Modal -->
<div x-data="{ open: false, receipt: @js(session('receipt')) }" x-init="if(receipt){ open=true }">
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
                                <td class="border px-2 py-1" x-text="item.medicine.name"></td>
                                <td class="border px-2 py-1 text-center" x-text="item.quantity"></td>
                                <td class="border px-2 py-1 text-right"> ₱<span x-text="parseFloat(item.price).toFixed(2)"></span> </td>
                                <td class="border px-2 py-1 text-right"> ₱<span x-text="parseFloat(item.subtotal).toFixed(2)"></span> </td>
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
