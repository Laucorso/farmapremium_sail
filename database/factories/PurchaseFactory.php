<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Card;
use App\Models\Purchase;
use App\Models\Client;
use App\Models\Pharmacy;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::factory()->create();
        $pharmacy = Pharmacy::factory()->create();
        return [
            'client_id'=>$client->id,
            'pharmacy_id'=>$pharmacy->id,
        ];
    }
}
