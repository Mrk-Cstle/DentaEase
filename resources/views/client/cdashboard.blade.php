@extends('layout.cnav')

@section('title', 'Dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('main-content')


{{-- <form id="faceLoginForm" enctype="multipart/form-data">
    <input type="file" name="login_face_image" accept="image/*" required>
    <button type="submit">Login with Face</button>
</form> --}}
<div id="response"></div>
<script>
    document.getElementById('faceLoginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const responseBox = document.getElementById('response');
        responseBox.textContent = 'Processing...';

        try {
            const response = await fetch('/get-face-landmarks', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                responseBox.textContent = data.message;
            } else {
                responseBox.textContent = data.message || 'Login failed.';
            }
        } catch (error) {
            responseBox.textContent = 'An error occurred. Check console.';
            console.error(error);
        }
    });
</script>
@endsection