<?php

namespace App\Http\Controllers;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    //
    public function Serviceslist(Request $request){

        $perPage = 5;

        $search = $request->input('search');
        $position = $request->input('filter');
       
        $query = Service::where(function($q) use ($search){
            $q->where('name', 'like', "%{$search}%");
           
        });
        
        
    if ($position) {
        $query->where('position', $position);
    }
        $staff = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $staff->items(),
            'pagination' => [
                'total' => $staff->total(),
                'per_page' => $staff->perPage(),
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'next_page_url' => $staff->nextPageUrl(),
                'prev_page_url' => $staff->previousPageUrl(),
            ]
        ]);

    }
    public function Addservices(Request $request){
     
        $mapped = [
            'name' => $request->input('name'),
            'approx_time' => $request->input('time'),
            'approx_price' => $request->input('price'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            
        ];

        $validated = validator($mapped, [
            'name' => 'required|string|max:255',
            'approx_time' => 'required|integer', // in minutes
            'approx_price' => 'required|numeric',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
        ])->validate();

    $service = Service::create($validated);

    return response()->json(['status'=> 'success', 'message' => 'Service created successfully', 'service' => $service]);
    }
}
