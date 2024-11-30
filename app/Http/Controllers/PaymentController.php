<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('serviceEntry')->get();
        return response()->json(['data' => $payments], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'service_entry_id' => 'required|exists:service_entries,id',
            'client_mail' => 'required|email',
            'amount' => 'required|numeric|min:0',
            'versed' => 'required|numeric|min:0',
            'reste' => 'required|numeric|min:0',
        ]);

        $payment = Payment::create($validatedData);

        return response()->json(['message' => 'Payment created successfully.', 'data' => $payment], 201);
    }

    public function show(Payment $payment)
    {
        return response()->json(['data' => $payment], 200);
    }

    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'service_entry_id' => 'required|exists:service_entries,id',
            'client_mail' => 'required|email',
            'amount' => 'required|numeric|min:0',
            'versed' => 'required|numeric|min:0',
            'reste' => 'required|numeric|min:0',
        ]);

        $payment->update($validatedData);

        return response()->json(['message' => 'Payment updated successfully.', 'data' => $payment], 200);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully.'], 200);
    }
}
