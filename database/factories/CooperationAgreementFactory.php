<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CooperationAgreement;
use App\Models\OrganizerProfile;
use App\Models\User;

class CooperationAgreementFactory extends Factory
{
    protected $model = CooperationAgreement::class;

    public function definition(): array
    {
        return [
            'organizer_profile_id' => OrganizerProfile::factory(),
            'agreement_number' => $this->faker->unique()->numerify('SPK-####'),
            'version' => '1.0',
            'file_path' => 'agreements/spk.pdf',
            'status' => \App\Enums\SpkStatus::Approved,
            'approved_by' => User::factory(),
            'approved_at' => now(),
        ];
    }
}
