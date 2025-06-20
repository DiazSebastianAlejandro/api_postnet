<?php

namespace App\Http\Controllers;

use App\Services\Posnet;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'number' => ['required', 'digits:8'],
                'amount' => ['required', 'numeric', 'min:0.01'],
                'installments' => ['required', 'integer', 'between:1,6'],
            ]);

            $posnet = new Posnet();
            $ticket = $posnet->doPayment(
                $validated['number'],
                $validated['amount'],
                $validated['installments']
            );

            return response()->json([
                'message' => 'Payment successful',
                'ticket' => $ticket
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Payment error',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
