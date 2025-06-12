@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')
<h1>Branch Management</h1>
<div class="flex flex-row justify-between">
  <div class="flex flex-row ">
  
    <button id="addUserBtn">Add Branch</button>
     
        
    
  </div>
  <div class="flex flex-row ">
    
      <input type="text" id="searchInput" placeholder="Search..." />
          <button>Search</button>
  
      
  </div>
</div>
{{-- Modal Add User --}}
<div id="addUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
  <div class="flex flex-col"  style="background:#fff; padding:20px; margin:100px auto; width:50%; position:relative;">
    <h3>Add Branch</h3>
    <form class="flex flex-col p-2 gap-2" id="addUserForm">
      <label>Branch Name:</label>
      <input type="text" name="Branch" placeholder="Branch" required>
      <label>Address:</label>
      <input type="text" name="Address" placeholder="Address" required>
     
      <div class="flex flex-row mt-5 gap-3">
       
        <div class="flex flex-row mt-5 gap-3">
        <button type="submit">Save</button>
        <button type="button" id="closeModalBtn">Cancel</button>
      </div>
     
    </form>
  </div>
</div>
</div>




 <div id="branchModal" class="fixed inset-0 hidden z-50  bg-opacity-50 items-center justify-center">
 
    <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg flex flex-col ">
         <div class="flex justify-end">
      <button id="closeBranchModal" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
    </div>
      <div class="flex flex-row">

            <div class="basis-[30%]">
              <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-bold">Branch: <span id="modalBranchName"></span></h2>
              
            </div>
            <p class="mb-4 text-gray-700">Address: <span id="modalBranchAddress"></span></p>
            <input type="hidden" id="BranchId" name="branch_id">
            <button id="deletebtn"  class="border rounded-md p-3 bg-[#FF0000] text-white" type="submit">Delete</button>
          </div>
          <div class="basis-[70%]">
            
            <div class="mb-4">
              <div class="flex justify-between items-center mb-2">
                
                <h3 class="font-semibold">Receptionists</h3>
                <button onclick="openUserModal('Receptionist')" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Add Receptionist</button>
                
              </div>
              <ul id="receptionistList" class="space-y-1"></ul>
            </div>

            <div>
              <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold">Dentists</h3>
                <button onclick="openUserModal('Dentist')" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Add Dentist</button>
              </div>
              <ul id="dentistList" class="space-y-1"></ul>
            </div>
          </div>

      </div>
     
      
     
    </div>
  </div>

  <!--  Add user in branch Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg w-full max-w-md">
    <h2 class="text-lg font-semibold mb-4" id="userModalTitle">Add User</h2>

    <form id="addUserForm">
      <input type="hidden" id="userBranchId" name="branch_id">

      <div class="mb-4">
        <label for="userName" class="block font-medium text-sm">Select User</label>
        <select id="userName" name="user_id" class="w-full border rounded p-2">
          <option value="">-- Select User --</option>
          <!-- Options will be added dynamically -->
        </select>
      </div>

    

      <input type="hidden" id="userPosition" name="position">

      <div class="flex justify-end gap-2">
        <button type="button" class="bg-gray-300 px-4 py-2 rounded" onclick="closeUserModal()">Cancel</button>
        <button type="button" onclick="addUserToBranch()" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
      </div>
    </form>
  </div>
</div>

  <!-- Add User Modal -->
  <div id="addUserModal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Add <span id="userRoleTitle"></span></h2>
        <button onclick="closeAddUserModal()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
      </div>
      <form id="addUserForm">
        <input type="hidden" name="role" id="userRole">
        <input type="hidden" name="branch_id" id="userBranchId">
        <div class="mb-4">
          <label class="block text-sm font-medium">Name</label>
          <input type="text" name="name" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium">Email</label>
          <input type="email" name="email" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
      </form>
    </div>
  </div>
 
<div>

    <table class="border-collapse border border-gray-400 table-auto w-full text-center">
    
        <thead class="bg-gray-200">
            <tr>
                <th>Branch</th>
                <th>Address</th>
             
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
$('#deletebtn').click(function (e) {
    e.preventDefault(); // prevent default if inside a form or link


 const storeId = $('#BranchId').val();
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this user!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "get",
                url: "{{ route('DeleteBranch') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: storeId
                },
                dataType: "json",
                success: function (response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                        if (result.isConfirmed) {
                           
                            window.location.href = '/branch'; 
                        }
                        });
                                        },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong.', 'error');
                }
            });
        }
    });
    });
  
  function openUserModal(position) {
  $('#userModalTitle').text(`Add ${position}`);
  $('#userPosition').val(position);

  const pos = position;
  $('#userModal').removeClass('hidden').addClass('flex');
    loadUsersByPosition(pos);
  
}

function closeUserModal() {
  $('#userModal').addClass('hidden').removeClass('flex');
}


  document.getElementById('closeBranchModal').addEventListener('click', function () {
    document.getElementById('branchModal').classList.add('hidden');
  });

function openBranchModal(branchId) {
  $('#branchModal').removeClass('hidden').addClass('flex');

  
  $.get('/branch-details', { id: branchId }, function (response) {
    if (response.status === 'success') {
      const branch = response.data;
      currentBranch = branch;

      // Populate branch details
      $('#modalBranchName').text(branch.name);
      $('#modalBranchAddress').text(branch.address);
      $('#userBranchId').val(branch.id);
      $('#BranchId').val(branch.id);

      // Clear old lists
      $('#receptionistList').empty();
      $('#dentistList').empty();

      // Add users to respective lists
      branch.staff.forEach(user => {
        let item = `
          <li class="flex justify-between items-center border p-2 rounded mb-2">
            <span>${user.name}</span>
            <button class="text-red-500 text-sm" onclick="removeUser(${user.id})">Delete</button>
          </li>
        `;

        if (user.position === 'Receptionist') {
          $('#receptionistList').append(item);
        } else if (user.position === 'Dentist') {
          $('#dentistList').append(item);
        }
      });
    } else {
      alert('Failed to load branch details.');
    }
  });
}
function addUserToBranch() {

  const storeId = $('#userBranchId').val();
  const userId = $('#userName').val();
  const position = $('#userPosition').val();

  $.post(`/branch/${storeId}/add-user`, {
    user_id: userId,
    position: position,
    _token: $('meta[name="csrf-token"]').attr('content')
  }, function (response) {
    if (response.status === 'success') {
      openBranchModal(storeId); 
      $('#userModal').addClass('hidden').removeClass('flex');
    }
  });
}
function removeUser(userId) {
  const storeId = $('#userBranchId').val(); // Or however you get the current store ID

  if (!confirm('Are you sure you want to remove this user from the branch?')) {
    return;
  }

  $.ajax({
    url: `/branch/${storeId}/remove-user`,
    method: 'POST',
    data: {
      user_id: userId,
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
      if (response.status === 'success') {
        alert('User removed successfully.');
        openBranchModal(storeId); // Reload the modal or UI
      } else {
        alert('Failed to remove user.');
      }
    },
    error: function() {
      alert('Server error while removing user.');
    }
  });
}


function loadUsersByPosition(position) {
  $.get(`/branch/users-by-position`, { position: position }, function (response) {
    if (response.status === 'success') {
      const users = response.data;
      let options = '<option value="">-- Select User --</option>';

      users.forEach(function (user) {
        options += `<option value="${user.id}">${user.name}</option>`;
      });

      $('#userName').html(options);
    } else {
      alert('Failed to load users');
    }
  });
}


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
  let currentPage = parseInt(localStorage.getItem('branchcurrentpage')) || 1;
  let currentSearch='';

  function branchlist(page = 1) {
    currentPage = page;
    localStorage.setItem('branchcurrentpage', page);
    currentSearch = $('#searchInput').val();
    var formdata = {
      "search": currentSearch,
      "page": page

    }
   $.ajax({
      type: "get",
      url: "{{Route('Branchlist')}}",
      data: formdata,
    
      success: function (response) {
        if (response.status === 'success') {
                let rows = '';
                response.data.forEach(function (branch) {
                    rows += `
                    <tr>
                        <td>${branch.name}</td>
                        <td>${branch.address}</td>
                      
                     <td><button class="text-blue-600 underline" onclick="openBranchModal(${branch.id})">View</button></td>
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
            branchlist(1); // Reset to first page when searching
        });
    branchlist(currentPage); // Call it on load
    window.branchlist = branchlist;
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
      branch: $('input[name="Branch"]').val(),
      address: $('input[name="Address"]').val(),
     
      _token: '{{ csrf_token() }}' // Laravel CSRF token
    };

    $.ajax({
      type: 'POST',
      url: '{{ route("AddBranch") }}', // Replace with your actual route name
      data: formData,
      success: function (response) {
        if (response.status ==='success') {
          Swal.fire('Success!', response.message, 'success');
        $('#addUserModal').fadeOut();
        $('#addUserForm')[0].reset();
        branchlist(currentPage);
        }else{
          Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.message,
});
        }
       
      },
      error: function (xhr) {
       Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.message,
});
      }
    });
  });
  });
</script>
@endsection