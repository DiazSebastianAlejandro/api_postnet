<?php

namespace App\Services;

use App\Models\Card;
use Exception;

class Posnet
{
    public function doPayment(string $cardNumber, float $amount, int $installments): array
    {
        $card = Card::where('number', $cardNumber)->first();

        if (!$card) {
            throw new Exception("Card not found");
        }

        if ($installments < 1 || $installments > 6) {
            throw new Exception("Installments must be between 1 and 6");
        }

        $interest = $installments === 1 ? 0 : ($installments - 1) * 0.03;
        $total = round($amount * (1 + $interest), 2);

        if ($card->available_limit < $total) {
            throw new Exception("Insufficient available limit");
        }

        $card->available_limit -= $total;
        $card->save();

        return [
            'client' => $card->user_first_name . ' ' . $card->user_last_name,
            'total_amount' => $total,
            'installment_amount' => round($total / $installments, 2),
        ];
    }
}
