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
        $filters = $request->input('filter');
       
        $query = Service::where(function($q) use ($search){
            $q->where('name', 'like', "%{$search}%");
           
        });
        
        
    if ($filters) {
        $query->where('type', $filters);
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

    public function update(Request $request)
{
    $service = Service::findOrFail($request->id);

    $data = $request->only(['name', 'type', 'approx_time', 'approx_price', 'description']);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = uniqid('service_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('service_images', $filename, 'public');
        $data['image'] = $filename;
    }

    $service->update($data);

    return response()->json(['message' => 'Service updated successfully.']);
}


public function destroy($id)
{
    $service = Service::findOrFail($id);
    $service->delete();

    return response()->json(['status' => 'success','message' => 'Service deleted successfully.']);
}
}
