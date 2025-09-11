@extends('layout.navigation')

@section('title', 'POS - Store ' . $storeId)

@section('main-content')
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
                <span>{{ $item['medicine_id'] }} (₱{{ number_format($item['price'],2) }})</span>
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
                <button class="px-6 py-3 bg-sky-600 text-white rounded-2xl hover:bg-sky-700 transition">
                    Checkout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
