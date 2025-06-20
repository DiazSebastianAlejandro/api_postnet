<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Exception;

class CardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'number' => ['required', 'digits:8', 'unique:cards,number'],
                'brand' => ['required', 'in:VISA,AMEX'],
                'bank' => ['required', 'string'],
                'available_limit' => ['required', 'numeric', 'min:0'],

                'user_dni' => ['required', 'string'],
                'user_first_name' => ['required', 'string'],
                'user_last_name' => ['required', 'string'],
            ]);

            $card = Card::create($validated);

            return response()->json([
                'message' => 'Card registered successfully',
                'card_id' => $card->id,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error',
                'error' => $e->getMessage(),
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
