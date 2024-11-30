<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\ServiceEntry;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CaisseController extends Controller
{
    public function index()
    {
        try {
            $caisses = Caisse::with('transactions')->get();
            return response()->json([
                'success' => true,
                'message' => 'Caisse data retrieved successfully.',
                'data' => $caisses,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve caisse data.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'caisse_id' => 'required|exists:caisses,id',
            'service_entry_id' => 'required|exists:service_entries,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:entry,exit',
        ]);
    
        $caisse = Caisse::findOrFail($request->caisse_id);
        $balanceBefore = $caisse->balance;
        $amount = $request->amount;
    
        // Vérification pour les sorties
        if ($request->type === 'exit' && $caisse->balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance in the caisse.',
            ], 400);
        }
    
        // Mise à jour de la caisse
        if ($request->type === 'entry') {
            $caisse->total_entry += $amount;
            $caisse->balance += $amount;
        } else {
            $caisse->total_exit += $amount;
            $caisse->balance -= $amount;
        }
        $caisse->save();
    
        // Création de la transaction
        $transaction = Transaction::create([
            'caisse_id' => $caisse->id,
            'service_entry_id' => $request->service_entry_id,
            'service_id' => $request->service_id,
            'amount' => $amount,
            'type' => $request->type,
            'balance_before' => $balanceBefore,
            'balance_after' => $caisse->balance,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'data' => $transaction,
        ]);
    }
    
    public function getClients()
    {
        $clients = ServiceEntry::with('service:id,name')
            ->get()
            ->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'name' => $entry->entry_data['nom'] ?? 'Unknown',
                    'email' => $entry->entry_data['email'] ?? 'No email',
                    'service_name' => $entry->service->name,
                ];
            });
    
        return response()->json([
            'success' => true,
            'data' => $clients,
        ]);
    }
    
}
