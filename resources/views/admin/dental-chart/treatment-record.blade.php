@extends('layout.navigation')

@section('title', 'Dental Record')

@section('main-content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-center">Treatment Record</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 text-left">Date</th>
                    <th class="py-2 px-4 text-left">Procedure</th>
                    <th class="py-2 px-4 text-left">Work Done</th>
                    <th class="py-2 px-4 text-left">Dentist</th>
                    <th class="py-2 px-4 text-left">Branch</th>
                    <th class="py-2 px-4 text-left">Amount Charged</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($record as $r)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">
                        {{ $r->appointment_date ? \Carbon\Carbon::parse($r->appointment_date)->format('M d, Y') : '-' }}
                    </td>
                    
                    <td class="py-2 px-4">{{ $r->desc ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $r->work_done ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $r->dentist->name ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $r->branch ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $r->total_price ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">No treatment records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
