@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')

<div class="mb-6">
  <h1 class="text-2xl font-bold text-accent mb-4">Patient Management</h1>

  <div class="flex flex-col sm:flex-row justify-between gap-4 mb-4">
    <div class="flex flex-row gap-2">

        <a href="/userverify" class="bg-blue-500 text-white px-4 py-2 rounded shadow">New Users</a>
    
    </div>

    <div class="flex flex-col sm:flex-row gap-2">
      <input type="text" id="searchInput" placeholder="Search..." class="border rounded p-2 w-full sm:w-64" />
      <button onclick="stafflist(1)" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded">Search</button>
    </div>
  </div>

  <!-- Table -->
  <div class="overflow-x-auto rounded shadow border">
    <table class="w-full table-auto text-sm text-center">
      <thead class="bg-secondary text-accent">
        <tr>
          <th class="py-3 px-4 border">Name</th>
          <th class="py-3 px-4 border">Contact Number</th>
          <th class="py-3 px-4 border">Action</th>
        </tr>
      </thead>
      <tbody id="newtbody" class="bg-white">
        <!-- Data goes here -->
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div id="pagination" class="mt-4 flex gap-2 justify-center"></div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/30 hidden z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
    <h2 class="text-xl font-semibold mb-4">User Info</h2>
    <div id="modalContent" class="text-sm text-gray-800 space-y-2">
      <!-- Populated dynamically -->
    </div>
    <div class="mt-4 text-right">
      <button onclick="closeModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Close</button>
    </div>
  </div>
</div>

<!-- Optional: Add User Modal (if re-enabled) -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
  <div class="bg-white w-full max-w-xl rounded-lg p-6 shadow-lg relative">
    <h3 class="text-lg font-bold mb-4">Add New User</h3>
    <form class="flex flex-col gap-3" id="addUserForm">
      <div class="grid sm:grid-cols-2 gap-3">
        <div>
          <label class="font-semibold">Name</label>
          <input type="text" name="name" required class="w-full border p-2 rounded" />
        </div>
        <div>
          <label class="font-semibold">Username</label>
          <input type="text" name="user" required class="w-full border p-2 rounded" />
        </div>
        <div class="sm:col-span-2">
          <label class="font-semibold">Position</label>
          <select name="position" id="position" class="w-full border p-2 rounded">
            <option value="Admin">Admin</option>
            <option value="Dentist">Dentist</option>
            <option value="Receptionist">Receptionist</option>
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-4">
        <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded">Save</button>
        <button type="button" id="closeModalBtn" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function closeModal() {
    $('#viewModal').addClass('hidden');
  }

  let currentPage = parseInt(localStorage.getItem('patientviewcurrentpage')) || 1;
  let currentSearch = '';

  function stafflist(page = 1) {
    currentPage = page;
    localStorage.setItem('patientviewcurrentpage', page);
    currentSearch = $('#searchInput').val();

    $.ajax({
      type: "GET",
      url: "{{ route('Patientlist') }}",
      data: {
        search: currentSearch,
        page: page
      },
      success: function (response) {
        if (response.status === 'success') {
          let rows = '';
          response.data.forEach(function (user) {
            rows += `
              <tr>
                <td class="border py-2 px-4">${user.full_name}</td>
                <td class="border py-2 px-4">${user.contact_number}</td>
                <td class="border py-2 px-4">
                  <a href="/user/${user.id}" class="text-blue-600 hover:underline">View</a>
                </td>
              </tr>`;
          });

          $('#newtbody').html(rows);

          let paginationHTML = '';
          if (response.pagination.prev_page_url) {
            paginationHTML += `<button onclick="stafflist(${parseInt(currentPage) - 1})" class="px-3 py-1 bg-gray-200 rounded">Previous</button>`;
          }
          if (response.pagination.next_page_url) {
            paginationHTML += `<button onclick="stafflist(${parseInt(currentPage) + 1})" class="px-3 py-1 bg-gray-200 rounded">Next</button>`;
          }

          $('#pagination').html(paginationHTML);
        } else {
          console.error('Failed to fetch data.');
        }
      }
    });
  }

  $(document).ready(function () {
    $('#searchInput').on('input', function () {
      localStorage.setItem('currentPage', 1);
      stafflist(1);
    });

    $('#addUserBtn').click(() => $('#addUserModal').removeClass('hidden'));
    $('#closeModalBtn').click(() => $('#addUserModal').addClass('hidden'));

    $(window).click(function (e) {
      if ($(e.target).is('#addUserModal')) {
        $('#addUserModal').addClass('hidden');
      }
    });

    $('#addUserForm').submit(function (e) {
      e.preventDefault();

      const formData = {
        name: $('input[name="name"]').val(),
        user: $('input[name="user"]').val(),
        position: $('#position').val(),
        _token: '{{ csrf_token() }}'
      };

      $.ajax({
        type: 'POST',
        url: '{{ route("add-user") }}',
        data: formData,
        success: function (response) {
          if (response.status === 'success') {
            Swal.fire('Success!', response.message, 'success');
            $('#addUserModal').addClass('hidden');
            $('#addUserForm')[0].reset();
            stafflist(currentPage);
          } else {
            Swal.fire('Error', response.message, 'error');
          }
        },
        error: function (xhr) {
          Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong.', 'error');
        }
      });
    });

    stafflist(currentPage);
  });
</script>
@endsection
