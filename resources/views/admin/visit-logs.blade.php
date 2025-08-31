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
        <button class="tab-button py-2 px-4 text-gray-600 border-b-2 border-transparent hover:text-blue-500" data-tab="face-tab">
           Face Recognition
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


{{-- face --}}
    <div id="face-tab" class="tab-content hidden">
        <div class="text-gray-700 text-sm">
        
            <h2 class="font-bold text-lg mb-4">Face Recognition to Log Visit</h2>
        <div id="reader" style="width: 320px;"></div>

        <form id="loginForm" method="post" class="flex flex-col gap-5">
         
    
            @csrf
    
            <div>
                <label class="text-gray-700 text-sm font-medium">Name</label>
                <select name="user" class="mt-1 w-full border border-sky-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-sky-400 bg-white">
                    @foreach($users as $user)
                        <option value="{{ $user->user }}" {{ old('user') == $user->user ? 'selected' : '' }}>
                            {{ $user->lastname }},  {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="flex justify-end">
                <button type="button" onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white font-medium rounded-md px-4 py-2 transition duration-150">
                    Face Scan
                </button>
            </div>
    
     
    
            <!-- Face Login Modal -->
            <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Face Login</h3>
                    <video id="video" width="320" height="240" autoplay class="border border-gray-300 rounded-md"></video>
                    <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
                    <div class="flex items-center justify-between mt-4">
                        <span id="countdownText" class="text-sm text-gray-600">Logging in in 3 seconds...</span>
                        <button onclick="closeModal()" type="button" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-2 rounded-md">Close</button>
                    </div>
                </div>
            </div>
    
            <!-- Loader -->
            <div id="loadingSpinner" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[9999]">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-sky-500"></div>
            </div>
        </form>
    
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    let countdownInterval = null;
    let hasCancelled = false;

    function openModal() {
        document.getElementById('videoModal').classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                document.getElementById('video').srcObject = stream;
            })
            .catch(function(err) {
                console.error("Error accessing webcam: " + err);
            });

        if (countdownInterval) clearInterval(countdownInterval);
        hasCancelled = false;

        let countdown = 5;
        const countdownText = document.getElementById('countdownText');
        countdownText.textContent = `Logging in in ${countdown} seconds...`;

        countdownInterval = setInterval(() => {
            if (hasCancelled) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                return;
            }

            countdown--;
            countdownText.textContent = `Logging in in ${countdown} seconds...`;

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                if (!hasCancelled) {
                    $('#loginForm').submit();
                }
            }
        }, 1000);
    }

    function closeModal() {
        document.getElementById('videoModal').classList.add('hidden');
        const video = document.getElementById('video');
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
        hasCancelled = true;
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        document.getElementById('countdownText').textContent = '';
        window.location.href = "/faceui";
    }

    $(document).ready(function () {
        $('#loginForm').submit(function (event) {
            event.preventDefault();

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/jpeg');

            const formData = {
                user: $('select[name="user"]').val(),
                image_base64: dataURL,
                _token: '{{ csrf_token() }}'
            };

            document.getElementById('loadingSpinner').classList.remove('hidden');

            $.ajax({
                type: 'POST',
                url: '{{ route('scan.face') }}',
                data: formData,
                success: function (response) {
                    document.getElementById('loadingSpinner').classList.add('hidden');
                    if (response.status === "success") {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                        document.getElementById('videoModal').classList.add('hidden');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    document.getElementById('loadingSpinner').classList.add('hidden');
                    document.getElementById('videoModal').classList.add('hidden');
                }
            });
        });
    });
</script>
@endsection


