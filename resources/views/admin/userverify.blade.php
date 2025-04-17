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
            <tr>
                <td>Name</td>
                <td>Birth Date</td>
                <td>Contact Number</td>
                <td>Action</td>
            </tr>
            <tr>
                <td>Name</td>
                <td>Birth Date</td>
                <td>Contact Number</td>
                <td>Action</td>
            </tr>
        </tbody>
     
    </table>
    <div id="pagination" class="mt-4 flex gap-2"></div>
    {{-- <div class="pagination">
        <button id="prevPage" disabled>Previous</button>
        <span id="currentPage">1</span>
        <button id="nextPage" disabled>Next</button>
    </div> --}}
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentPage =  parseInt(localStorage.getItem('currentPage')) || 1;
let currentSearch = '';
function viewUser(id) {
    $.ajax({
        type: "post",
        url: "{{route('Viewuser')}}",
        data: {
            id: id,
            _token: "{{csrf_token()}}"
        },
        
        success: function (response) {
            console.log(response.data.id);
        },
        error: function (xhr) {
            console.error(xhr.responseJSON);
        }
    });
}
function newuser(page = 1) {
    currentPage = page;
    localStorage.setItem('currentPage', currentPage);
    currentSearch = $('#searchInput').val();
    $.ajax({
        type: "get",
        url: "{{ route('Newuserlist') }}",
        data: { page: page, search: currentSearch },
        success: function (response) {
            if (response.status === 'success') {
                let rows = '';
                response.data.forEach(function (user) {
                    rows += `
                    <tr>
                        <td>${user.last_name}, ${user.first_name}</td>
                        <td>${user.birth_date}</td>
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
            newuser(1); // Reset to first page when searching
        });
    newuser(currentPage); // Call it on load
    window.newuser = newuser;
});
    
</script>
@endsection