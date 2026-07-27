<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\OrganizerService;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Enums\OrganizerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganizerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrganizerService $organizerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organizerService = app(OrganizerService::class);
    }

    public function test_can_update_profile()
    {
        $organizer = User::factory()->create();
        $profile = OrganizerProfile::factory()->create(['user_id' => $organizer->id]);

        $data = [
            'organization_name' => 'New Name',
            'phone' => '08123456789',
            'address' => 'New Address',
        ];

        // updateProfile returns bool, so check via refresh
        $result = $this->organizerService->updateProfile($profile->id, $data);
        $this->assertTrue($result);

        $profile->refresh();
        $this->assertEquals('New Name', $profile->organization_name);
    }

    public function test_can_verify_organizer()
    {
        $admin = User::factory()->create();
        $organizer = User::factory()->create();
        $profile = OrganizerProfile::factory()->create([
            'user_id' => $organizer->id,
            'status' => OrganizerStatus::UnderReview->value
        ]);

        // approve() requires the profile to be UnderReview
        $result = $this->organizerService->approve($profile->id, $admin->id);
        $this->assertTrue($result);

        $profile->refresh();
        $this->assertEquals(OrganizerStatus::Approved, $profile->status);
    }
}
