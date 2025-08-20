@extends('layout.navigation')

@section('title', 'Medicine Details')
@section('main-content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="mb-4">
    <a href="{{ route('inventory') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Back to Inventory
    </a>
</div>
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-accent mb-4">{{ $medicine->name }}</h1>
        <p class="mb-2">Unit: {{ $medicine->unit }}</p>
        <p class="mb-2">Price: ₱{{ number_format($medicine->price, 2) }}</p>
        <p class="mb-4">Description: {{ $medicine->description }}</p>

        @if (session('active_branch_id') == 'admin')
        @else
            <button onclick="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Add Batch
            </button>
        @endif


        <h2 class="text-xl font-bold mt-6 mb-2">Batches</h2>
        <table class="w-full table-auto border relative">
            <thead class="bg-secondary">
                <tr>
                    <th class="border px-4 py-2">Batch ID</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Expiration Date</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batches as $batch)
                    @php
                        $expiration = Carbon::parse($batch->expiration_date);
                        $now = Carbon::now();
                        $daysDiff = $now->diffInDays($expiration, false);

                        $isExpired = $expiration->isPast();
                        $isNearExpiry = !$isExpired && $daysDiff <= 30;
                    @endphp
                    <tr x-data="{ openMenu: false, openStockIn: false, openStockOut: false, openSuspend: false , openExpired: false}">
                        <td class="border px-4 py-2">{{ $batch->id }}</td>
                        <td class="border px-4 py-2">{{ $batch->quantity }}</td>

                        <td
                            class="border px-4 py-2
                        {{ $isExpired ? 'bg-red-200 text-red-900 font-bold' : '' }}
                        {{ $isNearExpiry ? 'bg-yellow-200 text-yellow-900 font-bold' : '' }}">
                            {{ $batch->expiration_date }}
                            @if ($isExpired)
                                <span class="ml-2 text-xs bg-red-500 text-white px-2 py-1 rounded">Expired</span>
                            @elseif($isNearExpiry)
                                <span class="ml-2 text-xs bg-yellow-500 text-white px-2 py-1 rounded">Expiring Soon</span>
                            @endif
                        </td>

                        <td class="border px-4 py-2 text-right relative">
                            <!-- 3-dot button -->
                            <button @click="openMenu = !openMenu" class="p-2 rounded hover:bg-gray-200">⋯</button>

                            <!-- Dropdown -->
                            <div x-show="openMenu" x-cloak @click.away="openMenu = false"
                                class="absolute right-0 mt-1 w-40 bg-white border rounded shadow-lg z-50">
                                <button @click="openMenu = false; openStockIn = true"
                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100">Stock In</button>
                                <button @click="openMenu = false; openStockOut = true"
                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100">Stock Out</button>
                                <button @click="openMenu = false; openSuspend = true"
                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100">Suspend</button>

                                    @if ($isExpired)
                                    <button @click="openMenu = false; openExpired = true"
                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100">Expired</button>
                                    @endif
                               
                            </div>

                            <!-- Stock In Modal -->
                            <div x-show="openStockIn" x-cloak
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div class="bg-white p-6 rounded shadow-lg w-96">
                                    <h2 class="text-lg font-bold mb-4">Stock In - Batch #{{ $batch->id }}</h2>
                                    <form method="POST" action="{{ route('stock.in', $batch->id) }}">
                                        @csrf
                                        <input type="number" name="quantity" placeholder="Enter quantity"
                                            class="border p-2 rounded w-full mb-3" required>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openStockIn = false"
                                                class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Stock Out Modal -->
                            <div x-show="openStockOut" x-cloak
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div class="bg-white p-6 rounded shadow-lg w-96">
                                    <h2 class="text-lg font-bold mb-4">Stock Out - Batch #{{ $batch->id }}</h2>
                                    <form method="POST" action="{{ route('stock.out', $batch->id) }}">
                                        @csrf
                                        <input type="number" name="quantity" placeholder="Enter quantity"
                                            class="border p-2 rounded w-full mb-3" required>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openStockOut = false"
                                                class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Suspend Modal -->
                            <div x-show="openSuspend" x-cloak
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div class="bg-white p-6 rounded shadow-lg w-96">
                                    <h2 class="text-lg font-bold mb-4">Suspend Batch #{{ $batch->id }}</h2>
                                    <p class="mb-4">Are you sure you want to suspend this batch?</p>
                                    <form method="POST" action="{{ route('batch.suspend', $batch->id) }}">
                                        @csrf
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openSuspend = false"
                                                class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded">Suspend</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div x-show="openExpired" x-cloak
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div class="bg-white p-6 rounded shadow-lg w-96">
                                    <h2 class="text-lg font-bold mb-4">Expired Batch #{{ $batch->id }}</h2>
                                    <p class="mb-4">Are you sure you want to suspend this batch?</p>
                                    <form method="POST" action="{{ route('batch.expired', $batch->id) }}">
                                        @csrf
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openExpired = false"
                                                class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded">Expired</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hide elements before Alpine loads -->
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <script src="//unpkg.com/alpinejs" defer></script>


        <!-- Modal Background -->
        <div id="addBatchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <!-- Modal Container -->
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">

                <!-- Modal Header -->
                <h2 class="text-xl font-bold mb-4">Add Batch</h2>

                <!-- Close Button -->
                <button onclick="closeModal()"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-lg">&times;</button>

                <!-- Form -->
                <form action="{{ route('medicine_batches.store', $medicine->id) }}" method="POST" class="space-y-3">
                    @csrf

                    <input type="number" name="quantity" placeholder="Quantity" required
                        class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" />

                    <input type="date" name="expiration_date" required
                        class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" />

                    <input type="number" name="store_id" required value="{{ session('active_branch_id') }}" hidden />

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>




    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 200000
            });
        </script>
    @endif

    <!-- Modal JS -->
    <script>
        function openModal() {
            document.getElementById('addBatchModal').classList.remove('hidden');
            document.getElementById('addBatchModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('addBatchModal').classList.remove('flex');
            document.getElementById('addBatchModal').classList.add('hidden');
        }
    </script>
@endsection
