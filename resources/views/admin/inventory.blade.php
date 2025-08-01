@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')
<div class="mb-6">
  <h1 class="text-2xl font-bold text-accent mb-4">Inventory Management</h1>

  <div class="flex flex-col sm:flex-row justify-between gap-4 mb-4">
    <button id="addUserBtn" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded shadow w-full sm:w-auto">
      <i class="fa-solid fa-user-plus mr-2"></i>Add Item
    </button>

    <div class="flex flex-col sm:flex-row gap-2">
      {{-- <select id="positionFilter" class="border rounded p-2 w-full sm:w-auto">
        <option value="">All Positions</option>
        <option value="Receptionist">Receptionist</option>
        <option value="Dentist">Dentist</option>
        <option value="Admin">Admin</option>
      </select> --}}
      <input type="text" id="searchInput" placeholder="Search..." class="border rounded p-2 w-full sm:w-60" />
      <button onclick="stafflist(1)" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded">
        Search
      </button>
    </div>
  </div>

  <!-- User Table -->
  <div class="overflow-x-auto rounded shadow border">
    <table class="w-full table-auto text-sm text-center">
      <thead class="bg-secondary text-accent">
        <tr>
          <th class="py-3 px-4 border">Name</th>
          <th class="py-3 px-4 border">Unit</th>
          <th class="py-3 px-4 border">Price</th>
              <th class="py-3 px-4 border">Description</th>
          <th class="py-3 px-4 border">Action</th>
        </tr>
      </thead>
      <tbody id="newtbody" class="bg-white">
        <!-- Populated via JS -->
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div id="pagination" class="mt-4 flex gap-2 justify-center"></div>
</div>

<!-- Modal: Add User -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
  <div class="bg-white w-full max-w-xl rounded-lg p-6 shadow-lg relative">
    <h3 class="text-lg font-bold mb-4">Add Item</h3>
    <form class="flex flex-col gap-3" id="addUserForm">
      <div class="grid sm:grid-cols-2 gap-3">
    
        <div>
          <label class="font-semibold">Name</label>
          <input type="text" name="name" required class="w-full border p-2 rounded" />
        </div>
        <div>
          <label class="font-semibold">Price</label>
          <input type="number" name="price" class="w-full border p-2 rounded" />
        </div>
        <div>
          <label class="font-semibold">Description</label>
          <input type="text" name="description" class="w-full border p-2 rounded" />
        </div>
       
        <div>
          <label class="font-semibold">Unit</label>
          <select name="unit" id="unit" required class="w-full border p-2 rounded">
            <option value="">-- Select Unit --</option>
            <option value="mL">mL</option>
            <option value="G">G</option>
            
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

<!-- Modal: View User -->
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
  // modal control
  function closeModal() {
    $('#viewModal').addClass('hidden');
  }

  let currentPage = parseInt(localStorage.getItem('inventorycurrentpage')) || 1;
  let currentSearch = '';

  function InventoryList(page = 1) {
    currentPage = page;
    localStorage.setItem('inventorycurrentpage', page);
    currentSearch = $('#searchInput').val();
    currentPosition = $('#positionFilter').val();
    localStorage.setItem('inventoryFilter', currentPosition);

    $.ajax({
      type: "GET",
      url: "{{ route('InventoryList') }}",
      data: {
        search: currentSearch,
        position: currentPosition,
        page: page
      },
      success: function (response) {
        if (response.status === 'success') {
          let rows = '';
          response.data.forEach(item => {
            rows += `
              <tr>
                <td class="border py-2 px-4">${item.name}</td>
                <td class="border py-2 px-4">${item.unit}</td>
                <td class="border py-2 px-4">${item.price}</td>
                <td class="border py-2 px-4">${item.description}</td>
                <td class="border py-2 px-4">
                  <a href="/user/${item.id}" class="text-blue-600 hover:underline"></a>
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
        }
      }
    });
  }

  $(document).ready(function () {
    const savedPosition = localStorage.getItem('inventoryFilter');
    if (savedPosition !== null) {
      $('#positionFilter').val(savedPosition);
    }

    $('#searchInput').on('input', function () {
      localStorage.setItem('currentPage', 1);
      InventoryList(1);
    });

    $('#positionFilter').on('change', function () {
      localStorage.setItem('inventoryFilter', $(this).val());
      localStorage.setItem('currentPage', 1);
      InventoryList(1);
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
        unit: $('select[name="unit"]').val(),
        description: $('input[name="description"]').val(),
        price: $('input[name="price"]').val(),
      
        _token: '{{ csrf_token() }}'
      };

      $.ajax({
        type: 'POST',
        url: '{{ route("medicines.store") }}',
        data: formData,
        success: function (response) {
          if (response.status === 'success') {
            Swal.fire('Success!', response.message, 'success');
            $('#addUserModal').addClass('hidden');
            $('#addUserForm')[0].reset();
            InventoryList(currentPage);
          } else {
            Swal.fire('Error', response.message, 'error');
          }
        },
        error: function (xhr) {
          Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong.', 'error');
        }
      });
    });

    InventoryList(currentPage);
  });
</script>
@endsection
