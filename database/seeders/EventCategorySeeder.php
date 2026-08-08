<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kesenian Budaya',
                'description' => 'Pertunjukan seni tradisional seperti tari, wayang, dan seni pertunjukan daerah.',
            ],
            [
                'name' => 'Ritual Adat',
                'description' => 'Upacara dan tradisi kebudayaan daerah.',
            ],
            [
                'name' => 'Festival Budaya',
                'description' => 'Pekan kebudayaan, pameran seni, dan festival rakyat.',
            ],
        ];

        foreach ($categories as $category) {
            EventCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
