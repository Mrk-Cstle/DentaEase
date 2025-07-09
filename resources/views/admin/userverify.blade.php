@extends('layout.navigation')

@section('title','New User Verification')

@section('main-content')
<div class="mb-4">
  <a href="{{ route('Patientaccount') }}" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
    Back to Patients List
  </a>
</div>

<h1 class="text-2xl font-bold text-accent mb-4">New User List</h1>

<div class="flex flex-col sm:flex-row justify-end gap-3 mb-4">
  <input type="text" id="searchInput" placeholder="Search..." class="border p-2 rounded w-full sm:w-64">
  <button onclick="newuser(1)" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded">
    Search
  </button>
</div>

<div class="overflow-x-auto rounded shadow border">
  <table class="w-full table-auto text-sm text-center">
    <thead class="bg-secondary text-accent">
      <tr>
        <th class="py-3 px-4 border">Name</th>
        <th class="py-3 px-4 border">Birth Date</th>
        <th class="py-3 px-4 border">Contact Number</th>
        <th class="py-3 px-4 border">Action</th>
      </tr>
    </thead>
    <tbody id="newtbody" class="bg-white">
      <!-- Rows inserted via JS -->
    </tbody>
  </table>
</div>

<div id="pagination" class="mt-4 flex gap-2 justify-center"></div>

<!-- Modal -->
<div id="viewModal" class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/30 hidden z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
    <h2 class="text-xl font-semibold mb-4">User Info</h2>
    <div id="modalContent" class="text-sm text-gray-800 space-y-2">
      <!-- Filled dynamically -->
    </div>
    <div class="mt-4 text-right">
      <button onclick="closeModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Close</button>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentPage = parseInt(localStorage.getItem('currentPage')) || 1;
let currentSearch = '';

function closeModal() {
  $('#viewModal').addClass('hidden');
}

function viewUser(id) {
  window.location.href = "{{ url('/users') }}/" + id;
}

function approveuser(userid) {
  $.ajax({
    type: "POST",
    url: "{{ route('Approveuser') }}",
    data: {
      userid: userid,
      _token: "{{ csrf_token() }}",
    },
    success: function(response) {
      Swal.fire('Approved!', 'User has been approved', 'success');
      $('#viewModal').addClass('hidden');
      newuser(1);
    }
  });
}
function formatDate(dateStr) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateStr).toLocaleDateString('en-US', options);
}
function newuser(page = 1) {
  currentPage = page;
  localStorage.setItem('currentPage', currentPage);
  currentSearch = $('#searchInput').val();

  $.ajax({
    type: "GET",
    url: "{{ route('Newuserlist') }}",
    data: { page: page, search: currentSearch },
    success: function(response) {
      if (response.status === 'success') {
        let rows = '';
        response.data.forEach(user => {
          rows += `
            <tr>
              <td class="border px-4 py-2">${user.name}</td>
              <td class="border px-4 py-2">${formatDate(user.birth_date)}</td>
              <td class="border px-4 py-2">${user.contact_number}</td>
              <td class="border px-4 py-2">
                <button onclick="viewUser(${user.id})" class="text-blue-600 hover:underline">View</button>
              </td>
            </tr>`;
        });

        $('#newtbody').html(rows);

        let paginationHTML = '';
        if (response.pagination.prev_page_url) {
          paginationHTML += `<button onclick="newuser(${parseInt(currentPage) - 1})" class="px-3 py-1 bg-gray-200 rounded">Previous</button>`;
        }
        if (response.pagination.next_page_url) {
          paginationHTML += `<button onclick="newuser(${parseInt(currentPage) + 1})" class="px-3 py-1 bg-gray-200 rounded">Next</button>`;
        }

        $('#pagination').html(paginationHTML);
      }
    }
  });
}

$(document).ready(function () {
  $('#searchInput').on('input', function () {
    localStorage.setItem('currentPage', 1);
    newuser(1);
  });

  newuser(currentPage);
});
</script>
@endsection
