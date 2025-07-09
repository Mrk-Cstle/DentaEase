@extends('layout.navigation')

@section('title','New User View')
@section('main-content')
<div class="mb-4">
    <a href="{{ route('Userverify') }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-blue-600">
        ← Back to New User List
    </a>
</div>

<div class="flex flex-col lg:flex-row gap-6">

    {{-- Left Panel: User Info --}}
    <div class="w-full lg:w-1/2 bg-white p-6 rounded shadow space-y-3">
        <h2 class="text-2xl font-bold mb-4 text-accent">New User Details</h2>

        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Middle Name:</strong> {{ $user->middlename }}</p>
        <p><strong>Last Name:</strong> {{ $user->lastname }}</p>
        <p><strong>Suffix:</strong> {{ $user->suffix ?? 'N/A' }}</p>
        <p><strong>Birth Date:</strong> {{ \Carbon\Carbon::parse($user->birth_date)->format('F d, Y') }}</p>
        <p><strong>Birthplace:</strong> {{ $user->birthplace }}</p>
        <p><strong>Current Address:</strong> {{ $user->current_address }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Contact Number:</strong> {{ $user->contact_number }}</p>
        <p><strong>Username:</strong> {{ $user->user }}</p>
        <p><strong>Account Type:</strong> {{ ucfirst($user->account_type) }}</p>
        <p><strong>Position:</strong> {{ ucfirst($user->position) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>

        <div class="flex gap-3 mt-4">
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    onclick="approveuser({{ $user->id }})">
                Approve
            </button>
            <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                    onclick="deleteUser({{ $user->id }})">
                Delete
            </button>
        </div>

        {{-- Verification Image --}}
        <div class="mt-4">
            <strong>Verification ID:</strong><br>
            @if($user->verification_id)
                <img src="{{ asset('storage/temp_verifications/' . $user->verification_id) }}" 
                     alt="Verification ID" class="mt-2 border rounded max-w-full">
            @else
                <p class="italic text-gray-500">Not Uploaded</p>
            @endif
        </div>
    </div>

    {{-- Right Panel: Other Users --}}
    <div class="w-full lg:w-1/2 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-accent">User List</h2>

        <input type="text" id="searchUser" placeholder="Search by name..." 
               class="w-full mb-4 p-2 border rounded shadow-sm" />

        <div class="overflow-auto">
            <table class="w-full table-auto text-sm text-left border">
                <thead class="bg-secondary text-accent">
                    <tr>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Birth Date</th>
                        <th class="p-2 border">Contact</th>
                        <th class="p-2 border">Address</th>
                    </tr>
                </thead>
                <tbody id="userTable" class="bg-white">
                    @foreach ($users as $u)
                        <tr>
                            <td class="p-2 border">{{ $u->lastname }}, {{ $u->name }} {{ $u->middlename }} {{ $u->suffix }}</td>
                            <td class="p-2 border">{{ \Carbon\Carbon::parse($u->birth_date)->format('F d, Y') }}</td>
                            <td class="p-2 border">{{ $u->contact_number }}</td>
                            <td class="p-2 border">{{ $u->current_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Live name filter
    document.getElementById('searchUser').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#userTable tr');
        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
        });
    });

    function approveuser(userid) {
        $.post("{{ route('Approveuser') }}", {
            userid: userid,
            _token: "{{ csrf_token() }}"
        }, function (response) {
            Swal.fire({
                title: 'Approved!',
                text: 'User has been approved',
                icon: 'success',
                confirmButtonText: 'Close'
            }).then(() => {
                window.location.href = "{{ route('Patientaccount') }}";
            });
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be deleted permanently.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Deleted!', data.message, 'success')
                        .then(() => {
                            window.location.href = "{{ route('Patientaccount') }}";
                        });
                })
                .catch(() => {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                });
            }
        });
    }
</script>
@endsection
