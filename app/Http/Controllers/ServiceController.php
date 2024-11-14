<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return response()->json($services);
    }
    public function show($id)
    {
        // Find the service by ID, or return a 404 response if not found
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        // Return the service as JSON
        return response()->json($service, 200);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'price' => 'required|numeric',
            'required_documents' => 'array',
            'required_fields' => 'array',
        ]);

        $service = Service::create($validatedData);

        return response()->json($service, 201);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'price' => 'required|numeric',
            'required_documents' => 'array',
            'required_fields' => 'array',
        ]);

        $service = Service::findOrFail($id);
        $service->update($validatedData);

        return response()->json($service);
    }


    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['message' => 'Service supprimé avec succès']);
    }
}
