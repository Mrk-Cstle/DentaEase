@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')

<div class="flex flex-row justify-end gap-3">
  
    <input type="text" id="searchInput" placeholder="Search..." />
        <button>Search</button>
  
    
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
                        <td><button onclick="viewUser(${user.id})" >View</button></td>
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
</script>
@endsection