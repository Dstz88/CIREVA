<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\CooperationAgreement;
use App\Models\eventCategory;
use App\Models\eventLocation;
use App\Models\event;
use App\Models\eventSchedule;
use App\Enums\OrganizerStatus;
use App\Enums\SpkStatus;
use App\Enums\eventStatus;

class eventModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event_draft(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Wayang Cirebon',
            'owner_name' => 'Kusuma',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-00100-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Tari', 'slug' => 'tari']);
        $location = eventLocation::create(['name' => 'Gedung Kesenian Cirebon', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $response = $this->actingAs($user)->post(route('organizer.events.store'), [
            'title' => 'Pertunjukan Tari Topeng Cirebon',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'description' => 'Pagelaran kesenian kebudayaan khas Cirebon.',
            'start_date' => now()->addDays(5)->toDateTimeString(),
            'end_date' => now()->addDays(5)->addHours(3)->toDateTimeString(),
            'capacity' => 100,
            'is_paid' => 1,
            'price' => 50000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'organizer_profile_id' => $profile->id,
            'title' => 'Pertunjukan Tari Topeng Cirebon',
        ]);
    }

    public function test_public_user_can_view_published_events(): void
    {
        $profile = OrganizerProfile::factory()->create(['status' => OrganizerStatus::Approved]);
        $category = eventCategory::create(['name' => 'Musik', 'slug' => 'musik']);
        $location = eventLocation::create(['name' => 'Alun-alun Kejaksan', 'address' => 'Jl. Kartini', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Festival Tarling Cirebon',
            'slug' => 'festival-tarling-cirebon',
            'description' => 'Festival musik tradisional Cirebon.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Published,
        ]);

        $response = $this->get(route('events.index'));
        $response->assertStatus(200);
        $response->assertSee('Festival Tarling Cirebon');
    }

    public function test_guest_can_view_event_detail_page(): void
    {
        $profile = OrganizerProfile::factory()->create(['status' => OrganizerStatus::Approved]);
        $category = eventCategory::create(['name' => 'Seni', 'slug' => 'seni']);
        $location = eventLocation::create(['name' => 'Keraton Kasepuhan', 'address' => 'Mendung', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pameran Batik Megamendung',
            'slug' => 'pameran-batik-megamendung',
            'description' => 'Pameran karya seni warisan leluhur Cirebon.',
            'banner' => 'events/banners/batik.jpg',
            'status' => eventStatus::Published,
        ]);

        $response = $this->get(route('events.show', $event->id));
        $response->assertStatus(200);
        $response->assertSee('Pameran Batik Megamendung');
    }
}
