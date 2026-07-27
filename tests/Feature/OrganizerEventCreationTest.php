<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\OrganizerProfile;
use App\Models\eventCategory;
use App\Models\eventLocation;
use Illuminate\Support\Facades\Storage;

class OrganizereventCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event_if_verified_and_spk_approved()
    {
        Storage::fake('public');

        $role = Role::firstOrCreate(['name' => 'Organizer']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $organizer = OrganizerProfile::factory()->create([
            'user_id' => $user->id,
            'status' => \App\Enums\OrganizerStatus::Approved,
        ]);

        \App\Models\CooperationAgreement::factory()->create([
            'organizer_profile_id' => $organizer->id,
            'status' => \App\Enums\SpkStatus::Approved
        ]);

        $category = eventCategory::factory()->create();
        $location = eventLocation::factory()->create();

        // Use post() (not postJson) so controller does a web redirect, not JSON
        $response = $this->actingAs($user)->withoutMiddleware()->post('/organizer/events', [
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Festival Budaya Cirebon',
            'description' => 'event tahunan terbesar.',
            'start_time' => now()->addDays(1)->toDateTimeString(),
            'end_time' => now()->addDays(2)->toDateTimeString(),
            'banner' => 'banner.jpg'
        ]);

        // Web redirect after success
        $response->assertStatus(302);

        $this->assertDatabaseHas('events', [
            'title' => 'Festival Budaya Cirebon',
            'organizer_profile_id' => $organizer->id
        ]);
    }
}
