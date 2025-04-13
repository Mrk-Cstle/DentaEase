@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')

<div class="flex flex-row justify-end gap-3">
  
        <input type="text" placeholder="Search Name">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
        newuser();
        function newuser() {
          $.ajax({
            type: "get",
            url: "{{route('Newuserlist')}}",
            // data: "data",
            // dataType: "dataType",
            success: function (response) {
                if (response.status ==='success') {
                    let rows = ''
                    response.data.forEach(function (user) {
                        rows+=`
                        <tr>
                            <td>
                            ${user.last_name},${user.first_name}
                            </td>
                            <td>${user.birth_date}</td>
                            <td>${user.contact_number}</td>
                            <td>button</td>
                            
                            
                        </tr>
                        `
                        
                    });

                    $('#newtbody').html(rows);
                } else {
                console.error('Failed to fetch data.');
            }
            }
           }); 
        }

    })

    
</script>
@endsection