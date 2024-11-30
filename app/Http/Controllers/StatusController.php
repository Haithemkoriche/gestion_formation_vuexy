<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    //
    public function index()
    {
        return response()->json(Status::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7', // Code couleur hexadécimal
        ]);

        $status = Status::create($validated);
        return response()->json($status, 201);
    }

    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:7',
        ]);

        $status->update($validated);
        return response()->json($status);
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return response()->json(['message' => 'Status deleted']);
    }
}
