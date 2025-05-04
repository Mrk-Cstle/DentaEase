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
              
            </tr>
          
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

@endsection