@extends('layout.navigation')

@section('title','Services Management')
@section('main-content')
<div class="p-6">
  <h2 class="text-2xl font-bold mb-4 text-accent">Services Management</h2>

  <div class="flex justify-between items-center mb-4">
    <button id="addUserBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add Service</button>

    <div class="flex gap-3">
      <select id="Filter" class="border p-2 rounded">
        <option value="">All Services</option>
        <option value="General Dentistry">General Dentistry</option>
        <option value="Orthodontics">Orthodontics</option>
        <option value="Oral Surgery">Oral Surgery</option>
      </select>
      <input type="text" id="searchInput" placeholder="Search..." class="border p-2 rounded" />
    </div>
  </div>

  <!-- Table -->
  <table class="w-full border text-center">
    <thead class="bg-blue-100">
      <tr>
        <th class="border px-4 py-2">Service</th>
        <th class="border px-4 py-2">Type</th>
        <th class="border px-4 py-2">Time</th>
        {{-- <th class="border px-4 py-2">Price</th> --}}
        <th class="border px-4 py-2">Action</th>
      </tr>
    </thead>
    <tbody id="newtbody"></tbody>
  </table>

  <div id="pagination" class="mt-4 flex gap-2 justify-center"></div>
</div>

<!-- Add Service Modal -->
<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow p-6 w-full max-w-lg">
    <h3 class="text-xl font-bold mb-4 text-blue-700">Add Service</h3>
    <form id="addUserForm" class="space-y-3">
      <input type="text" name="name" placeholder="Name" required class="w-full border p-2 rounded">
      <input type="text" name="description" placeholder="Description" required class="w-full border p-2 rounded">
      <input type="number" name="time" placeholder="Approx. Time (mins)" required class="w-full border p-2 rounded">
      <input type="number" name="price" placeholder="Approx. Price" required class="w-full border p-2 rounded" hidden>
      <select name="type" id="type" class="w-full border p-2 rounded">
        <option value="General Dentistry">General Dentistry</option>
        <option value="Orthodontics">Orthodontics</option>
        <option value="Oral Surgery">Oral Surgery</option>
      </select>
      <div class="flex justify-end gap-2 pt-4">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
        <button type="button" id="closeModalBtn" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- View/Update Modal -->
<div id="serviceModal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white w-full max-w-lg p-6 rounded shadow-lg relative">
    <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">&times;</button>
    <h2 class="text-xl font-bold mb-4">View & Update Service</h2>
    <form id="updateServiceForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="service_id">
      <input type="text" name="name" id="service_name" placeholder="Name" class="w-full border p-2 rounded mb-2">
      <select id="service_type" name="type" class="w-full border p-2 rounded mb-2">
        <option value="General Dentistry">General Dentistry</option>
        <option value="Orthodontics">Orthodontics</option>
        <option value="Oral Surgery">Oral Surgery</option>
      </select>
      <input type="number" name="approx_time" id="service_time" placeholder="Approx. Time" class="w-full border p-2 rounded mb-2">
      <input type="number" name="approx_price" id="service_price" placeholder="Approx. Price" class="w-full border p-2 rounded mb-2">
      <textarea name="description" id="service_description" placeholder="Description" class="w-full border p-2 rounded mb-4"></textarea>
      <label>Service Image:</label>
      <input type="file" name="image" id="service_image_input" accept="image/*" class="mb-2">
      <div id="imagePreviewWrapper" class="mb-4 hidden">
        <img id="service_image_preview" src="" class="w-32 h-32 object-cover rounded border" />
      </div>
      <div class="flex justify-end gap-2">
        <button id="updateServiceBtn" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Update</button>
        <button id="deleteServiceBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  let currentPage = parseInt(localStorage.getItem('Servicescurrentpage')) || 1;
  let currentSearch = '';

  function serviceslist(page = 1) {
    currentPage = page;
    localStorage.setItem('Servicescurrentpage', page);
    currentSearch = $('#searchInput').val();
    const currentFilter = $('#Filter').val();
    localStorage.setItem('ServicesPositionFilter', currentFilter);

    $.ajax({
      type: 'get',
      url: '{{ route('Serviceslist') }}',
      data: { search: currentSearch, filter: currentFilter, page },
      success: function (res) {
        if (res.status === 'success') {
          let rows = '';
          res.data.forEach(service => {
            rows += `
              <tr>
                <td class="border px-2 py-1">${service.name}</td>
                <td class="border px-2 py-1">${service.type}</td>
                <td class="border px-2 py-1">${service.approx_time}</td>
                <td hidden class="border px-2 py-1">${service.approx_price}</td>
                <td class="border px-2 py-1">
                  <button class="view-service bg-blue-500 text-white px-2 py-1 rounded" 
                    data-id="${service.id}" 
                    data-name="${service.name}"
                    data-type="${service.type}"
                    data-time="${service.approx_time}"
                    data-price="${service.approx_price}"
                    data-description="${service.description || ''}"
                    data-image="${service.image || ''}">View</button>
                </td>
              </tr>`;
          });
          $('#newtbody').html(rows);

          let paginationHTML = '';
          if (res.pagination.prev_page_url) paginationHTML += `<button onclick="serviceslist(${page - 1})" class="bg-gray-300 px-3 py-1 rounded">Previous</button>`;
          if (res.pagination.next_page_url) paginationHTML += `<button onclick="serviceslist(${page + 1})" class="bg-gray-300 px-3 py-1 rounded">Next</button>`;
          $('#pagination').html(paginationHTML);
        }
      }
    });
  }

  $(document).ready(function () {
    const savedPosition = localStorage.getItem('ServicesPositionFilter');
    if (savedPosition !== null) $('#Filter').val(savedPosition);

    $('#searchInput').on('input', () => { localStorage.setItem('Servicescurrentpage', 1); serviceslist(1); });
    $('#Filter').on('change', () => { localStorage.setItem('ServicesPositionFilter', $('#Filter').val()); serviceslist(1); });

    serviceslist(currentPage);

    $('#addUserBtn').click(() => $('#addUserModal').removeClass('hidden'));
    $('#closeModalBtn').click(() => $('#addUserModal').addClass('hidden'));
    $(window).click(e => { if ($(e.target).is('#addUserModal')) $('#addUserModal').addClass('hidden'); });

    $('#addUserForm').submit(function (e) {
      e.preventDefault();
      const formData = {
        name: $('input[name="name"]').val(),
        description: $('input[name="description"]').val(),
        type: $('#type').val(),
        price: $('input[name="price"]').val(),
        time: $('input[name="time"]').val(),
        _token: '{{ csrf_token() }}'
      };

      $.post('{{ route("add-services") }}', formData, function (res) {
        if (res.status === 'success') {
          Swal.fire('Success!', res.message, 'success');
          $('#addUserModal').addClass('hidden');
          $('#addUserForm')[0].reset();
          serviceslist(currentPage);
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      });
    });

    $(document).on('click', '.view-service', function () {
      $('#service_id').val($(this).data('id'));
      $('#service_name').val($(this).data('name'));
      $('#service_type').val($(this).data('type'));
      $('#service_time').val($(this).data('time'));
      $('#service_price').val($(this).data('price'));
      $('#service_description').val($(this).data('description'));
      const img = $(this).data('image');
      if (img) {
        $('#service_image_preview').attr('src', '{{ asset('storage/service_images') }}/' + img);
        $('#imagePreviewWrapper').show();
      } else {
        $('#imagePreviewWrapper').hide();
      }
      $('#serviceModal').removeClass('hidden');
    });

    $('#closeModal').click(() => $('#serviceModal').addClass('hidden'));

    $('#updateServiceBtn').click(function (e) {
      e.preventDefault();
      const form = document.getElementById('updateServiceForm');
      const formData = new FormData(form);
      formData.append('_token', '{{ csrf_token() }}');

      $.ajax({
        url: '/service/update',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: res => {
          Swal.fire('Updated', res.message, 'success');
          $('#serviceModal').addClass('hidden');
          serviceslist(currentPage);
        },
        error: () => Swal.fire('Error', 'Something went wrong.', 'error')
      });
    });

    $('#deleteServiceBtn').click(function (e) {
      e.preventDefault();
      const id = $('#service_id').val();

      Swal.fire({
        title: 'Are you sure?',
        text: 'This action is irreversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
      }).then(result => {
        if (result.isConfirmed) {
          $.ajax({
            url: `/services/${id}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: res => {
              Swal.fire('Deleted!', res.message, 'success');
              $('#serviceModal').addClass('hidden');
              serviceslist(currentPage);
            },
            error: () => Swal.fire('Error', 'Failed to delete service.', 'error')
          });
        }
      });
    });
  });
</script>
@endsection