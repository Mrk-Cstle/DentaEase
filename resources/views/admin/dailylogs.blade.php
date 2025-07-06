@extends('layout.navigation')

@section('title','Logs')
@section('main-content')
<h2 class="font-bold text-lg mb-4">Scan QR to Log Visit</h2>
<div id="reader" style="width: 320px;"></div>

<script src="https://unpkg.com/html5-qrcode"></script>
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
        alert(data.message);
        console.log(data.appointment);
    })
    .catch(err => {
        console.error('Error scanning QR:', err);
    });
}

const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 }
);
html5QrcodeScanner.render(onScanSuccess);
</script>

@endsection