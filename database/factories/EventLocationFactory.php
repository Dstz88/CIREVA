<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\eventLocation;

class eventLocationFactory extends Factory
{
    protected $model = eventLocation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'capacity' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
