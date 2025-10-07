@extends('layout.auth')

@section('title', 'Login')

@section('auth-content')
<div class="p-[50px] bg-sky-100">
<div class="flex justify-center">
    <div class="bg-[#F5F5F5] bg-opacity-75 w-full md:w-2/3 lg:w-1/2 px-10 py-10 rounded-md flex flex-col h-auto">
        <h3 class="text-xl font-semibold mb-6 text-center">Select Your Branch</h3>
        
        <form method="POST" action="/select-branch" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($branches as $branch)
                    <label class="cursor-pointer">
                        <input type="radio" name="branch_id" value="{{ $branch->id }}" class="hidden peer">
                        
                        <div class="w-full bg-white shadow-md rounded-xl p-6 border border-gray-200 
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg
                                    hover:shadow-xl hover:border-blue-400 transition 
                                    text-center flex flex-col items-center">
                            <div class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 7l9-4 9 4-9 4-9-4zm0 0v10a9 9 0 009 9 9 9 0 009-9V7" />
                                </svg>
                            </div>
                            <span class="text-lg font-medium text-gray-800">{{ $branch->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium 
                           hover:bg-blue-700 transition shadow-md">
                    Continue
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
