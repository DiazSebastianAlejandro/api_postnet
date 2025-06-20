<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Services\Posnet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosnetTest extends TestCase
{
    use RefreshDatabase;

    public function test_do_payment_successfully()
    {
        // Setup: crear una tarjeta en la base
        $card = Card::create([
            'number' => '12345678',
            'brand' => 'VISA',
            'bank' => 'Banco Nación',
            'available_limit' => 10000.00,
            'user_dni' => '40123456',
            'user_first_name' => 'Juan',
            'user_last_name' => 'Pérez',
        ]);

        $posnet = new Posnet();

        // Pago de $1000 en 5 cuotas → 12% recargo → $1120 total
        $ticket = $posnet->doPayment('12345678', 1000, 5);

        $this->assertEquals('Juan Pérez', $ticket['client']);
        $this->assertEquals(1120.00, $ticket['total_amount']);
        $this->assertEquals(224.00, $ticket['installment_amount']);

        // Confirmar que se descontó el monto del límite
        $this->assertDatabaseHas('cards', [
            'number' => '12345678',
            'available_limit' => 8880.00
        ]);
    }
}
