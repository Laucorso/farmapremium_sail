<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Card;
use App\Models\Purchase;
use App\Models\Client;
use App\Models\Pharmacy;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'total_points'=>500,'client_id'=>$client->id
        ]);
        $pharmacy = Pharmacy::factory()->create();
        $purchase = Purchase::factory()->create(['client_id'=>$client->id,'pharmacy_id'=>$pharmacy->id]);
        return [
            'card_id' => $card->id,
            'purchase_id' => $purchase->id,
            'points' => $this->faker->numberBetween(0, 10),
            'pharmacy_id' => $pharmacy->id,
            'type'=>'given',
        ];
    }
}
