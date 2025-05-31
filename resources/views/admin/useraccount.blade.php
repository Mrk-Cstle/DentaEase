@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')
<div class="flex flex-row justify-between">
  <div class="flex flex-row ">
  
    <button id="addUserBtn">Add User</button>
     
        
    
  </div>
  <div class="flex flex-row ">
    
      <input type="text" id="searchInput" placeholder="Search..." />
          <button>Search</button>
  
      
  </div>
</div>
{{-- Modal Add User --}}
<div id="addUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
  <div class="flex flex-col"  style="background:#fff; padding:20px; margin:100px auto; width:50%; position:relative;">
    <h3>Add New User</h3>
    <form class="flex flex-col p-2 gap-2" id="addUserForm">
      <label>Name:</label>
      <input type="text" name="name" placeholder="Name" required>
      <label>User:</label>
      <input type="text" name="user" placeholder="Username" required>
      <label>Position:</label>
      <select name="position" id="position" >
        <option value="Admin">Admin</option>
        <option value="Dentist">Dentist</option>
        <option value="Receptionist">Receptionist</option>
        
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
                <th>Name</th>
                <th>Birth Date</th>
                <th>Contact Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="newtbody">
       
            
          
        </tbody>
     
    </table>
    <div id="pagination" class="mt-4 flex gap-2"></div>
   
    

    <!-- Modal -->
<div id="viewModal" class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/5 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
      <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
      
      <h2 class="text-xl font-semibold mb-4">User Info</h2>
      
      <div id="modalContent">
        <!-- User data will be injected here -->
      </div>
      
      <div class="mt-4 text-right">
        <button onclick="closeModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // view user modal 
  function closeModal() {
    $('#viewModal').addClass('hidden');
}

function viewUser(id) {
    $.ajax({
        type: "post",
        url: "{{route('Viewuser')}}",
        data: {
            id: id,
            type: 'User',
            _token: "{{csrf_token()}}"
        },
        
        success: function (response) {

            const users = response.data;
            console.log(response.data.id);
            $('#modalContent').html(`
                <p><strong>Name:</strong> ${users.name}</p>
                <p><strong>Birth Date:</strong> ${users.birth_date}</p>
                <p><strong>Contact:</strong> ${users.contact_number}</p>
                <p><strong>Email</strong>${users.email}</p>
                

            `);
            $('#viewModal').removeClass('hidden');
        },
        error: function (xhr) {
            console.error(xhr.responseJSON);
        }
    });
}

//end of user modal


//user table list
  let currentPage = parseInt(localStorage.getItem('staffcurrentpage')) || 1;
  let currentSearch='';

  function stafflist(page = 1) {
    currentPage = page;
    localStorage.setItem('staffcurrentpage', page);
    currentSearch = $('#searchInput').val();
    var formdata = {
      "search": currentSearch,
      "page": page

    }
   $.ajax({
      type: "get",
      url: "{{Route('Stafflist')}}",
      data: formdata,
    
      success: function (response) {
        if (response.status === 'success') {
                let rows = '';
                response.data.forEach(function (user) {
                    rows += `
                    <tr>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.contact_number}</td>
                     <td><a href="/user/${user.id}">View</a></td>
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
    $('#searchInput').on('input', function () {
        localStorage.setItem('currentPage', 1);
            stafflist(1); // Reset to first page when searching
        });
    stafflist(currentPage); // Call it on load
    window.stafflist = stafflist;
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
      user: $('input[name="user"]').val(),
      position: $('#position').val(),
      _token: '{{ csrf_token() }}' // Laravel CSRF token
    };

    $.ajax({
      type: 'POST',
      url: '{{ route("add-user") }}', // Replace with your actual route name
      data: formData,
      success: function (response) {
        if (response.status ==='success') {
          Swal.fire('Success!', response.message, 'success');
        $('#addUserModal').fadeOut();
        $('#addUserForm')[0].reset();
        stafflist(currentPage);
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
</script>
@endsection