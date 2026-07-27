<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\event;
use App\Models\OrganizerProfile;
use App\Models\eventCategory;
use App\Models\eventLocation;

class eventFactory extends Factory
{
    protected $model = event::class;

    public function definition(): array
    {
        return [
            'organizer_profile_id' => OrganizerProfile::factory(),
            // Cheat by just creating one inline or using 1 if categories don't have factories yet
            // Wait, eventCategory might not have a factory
            'category_id' => eventCategory::factory(),
            'location_id' => eventLocation::factory(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'banner' => 'banner.jpg',
            'status' => \App\Enums\eventStatus::Published,
        ];
    }
}
