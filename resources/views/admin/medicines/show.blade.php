@extends('layout.navigation')

@section('title', 'Medicine Details')
@section('main-content')
@php
    use Carbon\Carbon;
@endphp
<div class="mb-6">
    
    <h1 class="text-2xl font-bold text-accent mb-4">{{ $medicine->name }}</h1>
    <p class="mb-2">Unit: {{ $medicine->unit }}</p>
    <p class="mb-2">Price: ₱{{ number_format($medicine->price, 2) }}</p>
    <p class="mb-4">Description: {{ $medicine->description }}</p>
    <button onclick="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        + Add Batch
    </button>
    <h2 class="text-xl font-bold mt-6 mb-2">Batches</h2>
    <table class="w-full table-auto border">
        <thead class="bg-secondary">
            <tr>
                <th class="border px-4 py-2">Batch ID</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Expiration Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $batch)
                @php
                    $expiration = Carbon::parse($batch->expiration_date);
                    $now = Carbon::now();
                    $daysDiff = $now->diffInDays($expiration, false);
    
                    $isExpired = $expiration->isPast();
                    $isNearExpiry = !$isExpired && $daysDiff <= 30;
                @endphp
                <tr>
                    <td class="border px-4 py-2">{{ $batch->id }}</td>
                    <td class="border px-4 py-2">{{ $batch->quantity }}</td>
                    <td class="border px-4 py-2 
                        {{ $isExpired ? 'bg-red-200 text-red-900 font-bold' : '' }}
                        {{ $isNearExpiry ? 'bg-yellow-200 text-yellow-900 font-bold' : '' }}">
                        {{ $batch->expiration_date }}
    
                        @if($isExpired)
                            <span class="ml-2 text-xs bg-red-500 text-white px-2 py-1 rounded">Expired</span>
                        @elseif($isNearExpiry)
                            <span class="ml-2 text-xs bg-yellow-500 text-white px-2 py-1 rounded">Expiring Soon</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

  <!-- Modal Background -->
<div id="addBatchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <!-- Modal Container -->
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        
        <!-- Modal Header -->
        <h2 class="text-xl font-bold mb-4">Add Batch</h2>

        <!-- Close Button -->
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-lg">&times;</button>

        <!-- Form -->
        <form action="{{ route('medicine_batches.store', $medicine->id) }}" method="POST" class="space-y-3">
            @csrf

            <input type="number" name="quantity" placeholder="Quantity" required 
                class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" />

            <input type="date" name="expiration_date" required 
                class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" />

            <input type="number" name="store_id" required 
                value="{{ session('active_branch_id') }}" hidden />

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>




</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
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
