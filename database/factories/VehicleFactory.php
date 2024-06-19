<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition()
    {
        return [
            'marca' => $this->faker->company,
            'modelo' => $this->faker->word,
            'patente' => strtoupper($this->faker->bothify('???###')),
            'año' => $this->faker->year,
            'precio' => $this->faker->numberBetween(1000, 50000),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
