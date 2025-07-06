@extends('layout.navigation')

@section('title','New User View')
@section('main-content')
<div class="mb-4">
    <a href="{{ route('Userverify') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Back to New User List
    </a>
</div>
<div class="flex gap-4">

    {{-- Left Side: Detailed User Information --}}
    <div class="w-1/2 bg-white p-6 rounded shadow space-y-3">
        <h2 class="text-2xl font-bold mb-4">New User Details</h2>

        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Middle Name:</strong> {{ $user->middlename }}</p>
        <p><strong>Last Name:</strong> {{ $user->lastname }}</p>
        <p><strong>Suffix:</strong> {{ $user->suffix ?? 'N/A' }}</p>
        <p><strong>Birth Date:</strong> {{ $user->birth_date }}</p>
        <p><strong>Birthplace:</strong> {{ $user->birthplace }}</p>
        <p><strong>Current Address:</strong> {{ $user->current_address }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Contact Number:</strong> {{ $user->contact_number }}</p>
        <p><strong>Username:</strong> {{ $user->user }}</p>
        <p><strong>Account Type:</strong> {{ ucfirst($user->account_type) }}</p>
        <p><strong>Position:</strong> {{ ucfirst($user->position) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
        <button class="border p-2 rounded-sm" onclick="approveuser({{ $user->id }})">Approve</button>
        <button class="border p-2 rounded-sm bg-red-500 text-white" onclick="deleteUser({{ $user->id }})">Delete</button>


        {{-- Display Verification Image --}}
        @if($user->verification_id)
            <div>
                <strong>Verification ID:</strong><br>
           <img src="{{ url('DentaEase/public/storage/temp_verifications/' . $user->verification_id) }}" alt="Verification ID">




            </div>
        @else
            <p><strong>Verification ID:</strong> Not Uploaded</p>
        @endif
    </div>

    {{-- Right Side: User List Table --}}
    <div class="w-1/2 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">User List</h2>
        <input type="text" id="searchUser" placeholder="Search by name..." class="w-full mb-3 p-2 border rounded">

        <table class="w-full table-auto border">
            <thead>
                <tr>
                    <th class="text-left">Name</th>
                    <th class="text-left">Birth Date</th>
                    <th class="text-left">Contact</th>
                    <th class="text-left">Address</th>
                </tr>
            </thead>
            <tbody id="userTable">
                @foreach ($users as $u)
                    <tr>
                        <td>{{ $u->last_name }}, {{ $u->name }} {{ $u->middle_name }} {{ $u->suffix }}</td>
                        <td>{{ $u->birth_date }}</td>
                        <td>{{ $u->contact_number }}</td>
                        <td>{{ $u->currrent_address }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Basic client-side search filter
    document.getElementById('searchUser').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#userTable tr');

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
        });
    });

    function approveuser(userid) {
    $.ajax({
        type: "post",
        url: "{{route('Approveuser')}}",
        data: {
           userid: userid,
           _token: "{{csrf_token()}}",
        },
        success:function(response){
          Swal.fire({ 
            title: 'Approved!',
            text: 'User has been approved',
            icon: 'success',
            confirmButtonText: 'Close'
        }).then(() => {
            window.location.href = "{{ route('Patientaccount') }}";
        });

        }

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
    }).then((result) => {
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
            .catch(err => {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            });
        }
    });
}
</script>

@endsection