<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrganizerProfile;
use App\Models\User;

class OrganizerProfileFactory extends Factory
{
    protected $model = OrganizerProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'owner_name' => $this->faker->name,
            'organization_name' => $this->faker->company,
            'logo' => 'logo.png',
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'status' => \App\Enums\OrganizerStatus::Approved,
        ];
    }
}
