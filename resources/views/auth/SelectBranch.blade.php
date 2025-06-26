@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col h-150  ">
               <h3>Select Your Branch</h3>
               <form method="POST" action="/select-branch">
                @csrf
                <div class="form-group">
                    <label>Choose Branch</label><br>
                    @foreach ($branches as $branch)
                        <button 
                            type="submit" 
                            name="branch_id" 
                            value="{{ $branch->id }}" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
                            >
                            {{ $branch->name }}
                        </button>
                    @endforeach
                </div>
            </form>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $(document).ready(function(){
                
                    });

            
            </script>
            @endsection