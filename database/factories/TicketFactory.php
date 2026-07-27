<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => \App\Models\event::factory(),
            'name' => implode(' ', $this->faker->words(3)),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(50000, 500000),
            'quota' => $this->faker->numberBetween(10, 100),
            'sold' => 0,
            'status' => \App\Enums\TicketStatus::Active->value,
        ];
    }
}
