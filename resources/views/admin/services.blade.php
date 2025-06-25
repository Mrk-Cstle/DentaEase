@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')
<h1>Services Management</h1>
<div class="flex flex-row justify-between">
  <div class="flex flex-row ">
  
    <button id="addUserBtn">Add Services</button>
     
        
    
  </div>

 

  <div class="flex flex-row gap-5">
  <select id="Filter" class="border p-2 rounded">
  <option value="">All Services</option>
  <option value="General Dentistry">General Dentistry</option>
          <option value="Orthodontics">Orthodontics</option>
          <option value="Oral Surgery">Oral Surgery</option>

  </select>
      <input type="text" id="searchInput" placeholder="Search..." />
          <button>Search</button>
  
      
  </div>
</div>
{{-- Modal Add Service --}}
<div id="addUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
  <div class="flex flex-col"  style="background:#fff; padding:20px; margin:100px auto; width:50%; position:relative;">
    <h3>Add Services</h3>
    <form class="flex flex-col p-2 gap-2" id="addUserForm">
      <label>Name:</label>
      <input type="text" name="name" placeholder="Name" required>
      <label>Description:</label>
      <input type="text" name="description" placeholder="Description" required>
      <label>Approx. Time:</label>
      <input type="number" name="time" placeholder="Approx. Time" required>
      <label>Approx. Price:</label>
      <input type="number" name="price" placeholder="Approx. Time" required>
      <label>Position:</label>
      <select name="type" id="type" >
        <option value="General Dentistry">General Dentistry</option>
        <option value="Orthodontics">Orthodontics</option>
        <option value="Oral Surgery">Oral Surgery</option>
        
      </select>
      <div class="flex flex-row mt-5 gap-3">
       
        <div class="flex flex-row mt-5 gap-3">
        <button type="submit">Save</button>
        <button type="button" id="closeModalBtn">Cancel</button>
      </div>
     
    </form>
  </div>
</div>
</div>
<div>

    <table class="border-collapse border border-gray-400 table-auto w-full text-center">
    
        <thead class="bg-gray-200">
            <tr>
                <th>Service</th>
                <th>Type</th>
                <th>Time</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="newtbody">
       
            
          
        </tbody>
     
    </table>
    <div id="pagination" class="mt-4 flex gap-2"></div>
   
    
<!-- Service Modal -->
<div id="serviceModal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white w-full max-w-lg p-6 rounded shadow-lg relative">
    <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">&times;</button>

    <h2 class="text-xl font-bold mb-4">View & Update Service</h2>
    <form id="updateServiceForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="service_id">

        <label>Name:</label>
        <input type="text" name="name" id="service_name" class="w-full border p-2 rounded mb-2">

        <label>Type:</label><br>
        <select id="service_type" name="type"  class="w-full border p-2 rounded mb-2">
          <option value="General Dentistry">General Dentistry</option>
          <option value="Orthodontics">Orthodontics</option>
          <option value="Oral Surgery">Oral Surgery</option>
      </select><br>

        <label>Approx Time (min):</label>
        <input type="number" name="approx_time" id="service_time" class="w-full border p-2 rounded mb-2">

        <label>Approx Price:</label>
        <input type="number" name="approx_price" id="service_price" class="w-full border p-2 rounded mb-2">

        <label>Description:</label>
        <textarea name="description" id="service_description" class="w-full border p-2 rounded mb-4"></textarea>

            <!-- Image Upload -->
        <label>Service Image:</label>
        <input type="file" name="image" id="service_image_input" accept="image/*" class="mb-2">

        <!-- Preview -->
        <div id="imagePreviewWrapper" class="mb-4">
            <img id="service_image_preview" src="" alt="Service Image" class="w-32 h-32 object-cover rounded border" />
        </div>
        <div class="flex justify-end gap-2 mt-6">
          <button id="updateServiceBtn" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
              Update
          </button>
      
          <button id="deleteServiceBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
              Delete
          </button>
      </div>
    </form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // view user modal 
  function closeModal() {
    $('#viewModal').addClass('hidden');
}

// function viewUser(id) {
//     $.ajax({
//         type: "post",
//         url: "{{route('Viewuser')}}",
//         data: {
//             id: id,
//             type: 'User',
//             _token: "{{csrf_token()}}"
//         },
        
//         success: function (response) {

//             const users = response.data;
//             console.log(response.data.id);
//             $('#modalContent').html(`
//                 <p><strong>Name:</strong> ${users.name}</p>
//                 <p><strong>Birth Date:</strong> ${users.birth_date}</p>
//                 <p><strong>Contact:</strong> ${users.contact_number}</p>
//                 <p><strong>Email</strong>${users.email}</p>
                

//             `);
//             $('#viewModal').removeClass('hidden');
//         },
//         error: function (xhr) {
//             console.error(xhr.responseJSON);
//         }
//     });
// }

//end of user modal


//user table list
  let currentPage = parseInt(localStorage.getItem('Servicescurrentpage')) || 1;
  let currentSearch='';

  function serviceslist(page = 1) {
    currentPage = page;
    
    localStorage.setItem('Servicescurrentpage', page);
    currentSearch = $('#searchInput').val();
    currentFilter = $('#Filter').val();
     localStorage.setItem('ServicesPositionFilter', currentFilter);
     
    var formdata = {
      "search": currentSearch,
      "filter": currentFilter,
      "page": page

    }
   $.ajax({
      type: "get",
      url: "{{Route('Serviceslist')}}",
      data: formdata,
    
      success: function (response) {
        if (response.status === 'success') {
                let rows = '';
                response.data.forEach(function (service) {
                    rows += `
                    <tr>
                        <td>${service.name}</td>
                        <td>${service.type}</td>
                        <td>${service.approx_time}</td>
                          <td>${service.approx_price}</td>
                    <td><button class="view-service bg-blue-500 text-white px-2 py-1 rounded" 
            data-id="${service.id}" 
            data-name="${service.name}" 
            data-type="${service.type}" 
            data-time="${service.approx_time}" 
            data-price="${service.approx_price}" 
            data-description="${service.description ?? ''
            }"
             data-image="${service.image ?? ''}">
            View
        </button></td>
                    </tr>
                    `;
                });

                $('#newtbody').html(rows);

                let paginationHTML = '';

                if (response.pagination.prev_page_url) {
                    paginationHTML += `<button onclick="newuser(${parseInt(currentPage) - 1})">Previous</button>`;
                }

                if (response.pagination.next_page_url) {
                    paginationHTML += `<button onclick="newuser(${parseInt(currentPage) + 1})">Next</button>`;
                }

                $('#pagination').html(paginationHTML);
            } else {
                console.error('Failed to fetch data.');
            }
      }
    });
    
  }
  $(document).ready(function () {
    const savedPosition = localStorage.getItem('ServicesPositionFilter');
    if (savedPosition !== null) {
        $('#filter').val(savedPosition);
    }
    $('#searchInput').on('input', function () {
        localStorage.setItem('Servicescurrentpage', 1);
        serviceslist(1); // Reset to first page when searching
        });
    $('#Filter').on('change', function () {
      console.log("clicked")
         localStorage.setItem('ServicesPositionFilter', $(this).val());
        localStorage.setItem('Servicescurrentpage', 1);
        serviceslist(1); // Reset to first page when filter changes
    });
    serviceslist(currentPage); // Call it on load
    window.serviceslist = serviceslist;
});
//end of user list
</script>

<script>
  $(document).ready(function() {
    // Show modal
    $('#addUserBtn').click(function() {
      $('#addUserModal').show();
    });

    // Hide modal
    $('#closeModalBtn').click(function() {
      $('#addUserModal').hide();
    });

    // Optional: Hide modal on outside click
    $(window).click(function(e) {
      if ($(e.target).is('#addUserModal')) {
        $('#addUserModal').hide();
      }
    });

    $('#addUserForm').submit(function (e) {
    e.preventDefault();

    const formData = {
      name: $('input[name="name"]').val(),
      description: $('input[name="description"]').val(),
      type: $('#type').val(),
      price: $('input[name="price"]').val(),
      time: $('input[name="time"]').val(),
      _token: '{{ csrf_token() }}' // Laravel CSRF token
    };

    $.ajax({
      type: 'POST',
      url: '{{ route("add-services") }}', // Replace with your actual route name
      data: formData,
      success: function (response) {
        if (response.status ==='success') {
          Swal.fire('Success!', response.message, 'success');
        $('#addUserModal').fadeOut();
        $('#addUserForm')[0].reset();
        serviceslist(currentPage);
        }else{
          Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.message,
});
        }
       
      },
      error: function (xhr) {
        if (xhr.status === 409) {
            Swal.fire('Error', xhr.responseJSON.message, 'error'); // Username already exists
        } else {
            Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong.', 'error');
        }
      }
    });
  });
  });


  $(document).on('click', '.view-service', function () {
    $('#service_id').val($(this).data('id'));
    $('#service_name').val($(this).data('name'));
    $('#service_type').val($(this).data('type'));

    $('#service_time').val($(this).data('time'));
    $('#service_price').val($(this).data('price'));
    $('#service_description').val($(this).data('description'));

    const imageUrl = $(this).data('image');
    if (imageUrl) {
      $('#service_image_preview').attr('src', '{{ asset("storage/service_images") }}/' + imageUrl);

        $('#imagePreviewWrapper').show();
    } else {
        $('#imagePreviewWrapper').hide();
    }

    $('#serviceModal').removeClass('hidden');
});


// Close modal
$('#closeModal').on('click', function () {
    $('#serviceModal').addClass('hidden');
});

// Update service via AJAX
$('#updateServiceBtn').on('click', function (e) {
  e.preventDefault();
    const formElement = document.getElementById('updateServiceForm');
    const formData = new FormData(formElement);

    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: '/service/update',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            Swal.fire('Updated', res.message, 'success');
            $('#serviceModal').fadeOut();
          
            // Optionally refresh your table here
            serviceslist(currentPage);
        },
        error: function () {
            Swal.fire('Error', 'Something went wrong.', 'error');
        }
    });
});


$(document).on('click', '#deleteServiceBtn', function (e) {
  e.preventDefault();
    const serviceId = $('#service_id').val();

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this service!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/services/${serviceId}`, // Adjust if using named route
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire('Deleted!', response.message, 'success');
                    $('#serviceModal').fadeOut();
                    // Optionally: reload service list
                    serviceslist(currentPage);
                },
                error: function () {
                    Swal.fire('Error', 'Failed to delete service.', 'error');
                }
            });
        }
    });
});
</script>
@endsection