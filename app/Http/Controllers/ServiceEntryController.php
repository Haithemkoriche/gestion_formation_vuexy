<?php

namespace App\Http\Controllers;

use App\Models\ServiceEntry;
use Illuminate\Http\Request;

class ServiceEntryController extends Controller
{
    // Afficher tous les enregistrements d'un service spécifique
    public function index($serviceId)
    {
        $entries = ServiceEntry::where('service_id', $serviceId)->get();
        return response()->json($entries);
    }

    // Créer un nouvel enregistrement pour un service spécifique
    public function store(Request $request, $serviceId)
    {
        $request->validate([
            'entry_data' => 'required|array', // Les données doivent être un tableau
            'status' => 'required|string|max:20',
        ]);

        $entry = ServiceEntry::create([
            'service_id' => $serviceId,
            'entry_data' => $request->entry_data, // Utilise `entry_data` au lieu de `data`
            'status' => $request->status,
        ]);

        return response()->json($entry, 201);
    }

    // Afficher un enregistrement spécifique
    public function show($entryId)
    {
        $entry = ServiceEntry::findOrFail($entryId);
        return response()->json($entry);
    }

    // Mettre à jour un enregistrement spécifique
    public function update(Request $request, $entryId)
    {
        $entry = ServiceEntry::find($entryId);
        if (!$entry) {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    
        $entry->update($request->all());
        return response()->json(['message' => 'Entry updated successfully'], 200);
    }
    
    public function destroy($entryId)
    {
        $entry = ServiceEntry::find($entryId);
        if (!$entry) {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    
        $entry->delete();
        return response()->json(['message' => 'Entry deleted successfully'], 200);
    }
    
}
