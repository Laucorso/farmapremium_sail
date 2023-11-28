<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Client::class;

    public function definition()
    {
        // Define cómo se deben generar los datos para el modelo Client
        return [
            'name' => $this->faker->name,
            'surname'=>$this->faker->name,
            'complete_address'=>$this->faker->text,
        ];
    }
}
