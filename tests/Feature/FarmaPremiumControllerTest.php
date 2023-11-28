<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\Pharmacy;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\Card;

class FarmaPremiumControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_obtain_points_function()
    {

        $transaction = Transaction::factory()->create();
        $card = Card::find($transaction->card_id);
        $response = $this->post('api/acumulate', [
            'client_id' => $card->client_id,
            'pharmacy_id' => $transaction->pharmacy_id,
            'points' => $transaction->points,
        ]);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Puntos acumulados con éxito']);

    }

    public function test_canjear_points_function()
    {

        $transaction = Transaction::factory()->create();

        $card = Card::find($transaction->card_id);
        $response = $this->post('api/canjear-points', [
            'client_id' => $card->client_id,
            'pharmacy_id' => $transaction->pharmacy_id,
            'points' => $transaction->points,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Puntos canjeados con éxito']);

        //checkeamos solicitud fallida por saldo insuficiente
        $card->update(['total_points'=>0]);
        $response = $this->post('api/canjear-points', [
            'client_id' => $card->client_id,
            'pharmacy_id' => $transaction->pharmacy_id,
            'points' => $transaction->points,
        ]);

        $response->assertJson(['message' => 'Saldo insuficiente para canjear los puntos']);
    }

    public function test_review_function()
    {

        $transactions = Transaction::factory(5)->create();
        $card = Card::find($transactions[0]->card_id);

        $response = $this->post('api/review', [
            'client_id' => $card->id,
            'pharmacy_id' => $transactions[0]->pharmacy_id,
            'start_date' => '2021-01-01', 
            'end_date' => '2023-01-31',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'given_points_dates',
            'given_points_client',
            'client_card_balance',
        ]);
    }
}
