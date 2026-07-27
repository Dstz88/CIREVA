<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_code' => 'BKG-' . strtoupper(Str::random(8)),
            'user_id' => \App\Models\User::factory(),
            'total_amount' => 100000,
            'status' => \App\Enums\BookingStatus::Pending->value,
            'expired_at' => now()->addHours(24),
        ];
    }
}
