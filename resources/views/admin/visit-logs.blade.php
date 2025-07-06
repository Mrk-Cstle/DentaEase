@extends('layout.navigation')

@section('title','Logs')
@section('main-content')
<div class="w-full max-w-5xl mx-auto p-6 bg-white shadow rounded-md">

    <!-- Tabs Header -->
    <div class="flex space-x-4 border-b mb-6">
        <button class="tab-button py-2 px-4 text-gray-600 border-b-2 border-transparent hover:text-blue-500" data-tab="logs-tab">
            Visit Logs
        </button>
        <button class="tab-button py-2 px-4 text-gray-600 border-b-2 border-transparent hover:text-blue-500" data-tab="scan-tab">
            Scan QR
        </button>
    </div>

    <!-- Visit Logs Tab -->
    <div id="logs-tab" class="tab-content">
        <form method="GET" action="{{ route('logs') }}" class="mb-4">
            <label for="date" class="text-sm font-medium">Filter by date:</label>
            <input type="date" id="date" name="date" value="{{ $date }}" class="border px-2 py-1 rounded">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded ml-2">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full border text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">User</th>
                        <th class="border px-4 py-2">Branch</th>
                        <th class="border px-4 py-2">Scanned At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="border px-4 py-2">{{ $log->user->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $log->appointment->store->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($log->scanned_at)->format('F j, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-4 py-2 text-center text-gray-500">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scan QR Tab -->
    <div id="scan-tab" class="tab-content hidden">
        <div class="text-gray-700 text-sm">
        
            <h2 class="font-bold text-lg mb-4">Scan QR to Log Visit</h2>
        <div id="reader" style="width: 320px;"></div>

        <script src="https://unpkg.com/html5-qrcode"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
       function onScanSuccess(qrMessage) {
            fetch("{{ route('scan.qr') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_token: qrMessage })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    title: 'Scan Successful!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('logs') }}";
                    }
                });
            })
            .catch(err => {
                console.error('Error scanning QR:', err);
                Swal.fire({
                    title: 'Error',
                    text: 'Something went wrong while scanning.',
                    icon: 'error'
                });
            });
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }
        );
        html5QrcodeScanner.render(onScanSuccess);
        </script>
        </div>
    </div>
</div>

<!-- Tab Switch Script -->
<script>
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.getAttribute('data-tab');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            document.getElementById(tab).classList.remove('hidden');

            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('text-blue-500', 'border-blue-500');
            });

            button.classList.add('text-blue-500', 'border-blue-500');
        });
    });

    // Auto-select first tab on page load
    document.querySelector('.tab-button').click();
</script>
@endsection
