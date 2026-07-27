<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => \App\Models\Booking::factory(),
            'transaction_number' => 'TRX-' . strtoupper(Str::random(10)),
            'amount' => 100000,
            'payment_method' => 'Bank Transfer',
            'status' => \App\Enums\TransactionStatus::Pending->value,
        ];
    }
}
