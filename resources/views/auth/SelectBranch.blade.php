@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')
            <div class="bg-[#F5F5F5] bg-opacity-75 w-1/3 px-10 py-10 rounded-md flex flex-col h-150  ">
               <h3>Select Your Branch</h3>
                <form method="POST" action="/select-branch">
                    @csrf
                    <div class="form-group">
                        <label for="branch_id">Choose Branch</label>
                        <select class="form-control" name="branch_id" required>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Continue</button>
                </form>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $(document).ready(function(){
                
                    });

            
            </script>
            @endsection