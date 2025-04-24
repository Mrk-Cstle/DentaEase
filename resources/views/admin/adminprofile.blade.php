@extends('layout.navigation')

@section('title','Profile')
@section('main-content')
<style>
    input{
        border: 1px;
        background-color:#F5F5F5;
        padding: 2px;
    }
</style>

<div class="flex flex-row h-full m-10 gap-5">
    <div class=" rounded-md flex flex-col basis-[30%] bg-white">
        <div class="basis-[30%] bg-cover bg-no-repeat bg-center bg-[url({{ asset('images/defaultp.jpg') }})]  ">
          
        </div>
        <div class="basis-[70%]  flex flex-col p-5 overflow-y-auto">
            <form class="flex flex-col gap-3" action="">
                <label for="fname">Name:</label>
                <input type="text" name="fname" id="fname" value="{{ Auth::user()->name}}">
                <label for="bday">Birth Day</label>
                <input type="date" name="bday" id="bday" value="{{Auth::user()->birth_date}}">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="{{Auth::user()->email}}">
                <label for="contact">Contact Number</label>
                <input type="number" name="contact" id="contact" value="{{Auth::user()->contact_number}}">
                <label for="user">User</label>
                <input type="text" name="user" id="user" value="{{Auth::user()->user}}">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" >
                <input type="hidden" name="oldpassword" id="oldpassword" value="{{Auth::user()->password}}">

                <button class="border rounded-md p-3" type="submit">Update</button>

            </form>
        </div>
    </div>
    <div class="flex flex-col  basis-[70%] gap-5">
        <div class=" rounded-md grow-1 bg-white flex flex-row gap-3 p-5">
            <div class="basis-[50%] border">
                QR
            </div>
            <div class="basis-[50%] border flex flex-col">
                <div class="flex flex-row justify-between m-3">
                    <p>Face Recognition</p><button class="bg-[#FF0000] p-1 rounded-sm">Remove</button>
                </div>
              

               <form>
                
                <button type="submit">Capture</button>
               </form>
            </div>
        </div>
        <div class=" rounded-md grow-1 bg-white">
            
        </div>
    </div>
</div>


@endsection