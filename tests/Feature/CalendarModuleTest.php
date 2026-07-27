<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\eventCategory;
use App\Models\eventLocation;
use App\Models\event;
use App\Models\eventSchedule;
use App\Models\CooperationAgreement;
use App\Enums\OrganizerStatus;
use App\Enums\eventStatus;
use App\Enums\ScheduleStatus;
use App\Enums\SpkStatus;

class CalendarModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_public_calendar(): void
    {
        $response = $this->get(route('calendar.index'));

        $response->assertStatus(200);
    }

    public function test_organizer_can_create_event_schedule(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-' . uniqid() . '-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Teater', 'slug' => 'teater']);
        $location = eventLocation::create(['name' => 'Gedung Kesenian Cirebon', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pertunjukan Sandiwara Cirebon',
            'slug' => 'pertunjukan-sandiwara-cirebon',
            'description' => 'Seni drama tradisional khas Cirebon.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Approved,
        ]);

        $start = now()->addDays(10)->format('Y-m-d H:i:s');
        $end = now()->addDays(10)->addHours(2)->format('Y-m-d H:i:s');

        $response = $this->actingAs($user)->post(route('organizer.calendar.store'), [
            'event_id' => $event->id,
            'start_datetime' => $start,
            'end_datetime' => $end,
            'timezone' => 'Asia/Jakarta',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('event_schedules', [
            'event_id' => $event->id,
            'status' => ScheduleStatus::Scheduled->value,
        ]);
    }

    public function test_conflict_detection_prevents_overlapping_schedules(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-' . uniqid() . '-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Teater', 'slug' => 'teater']);
        $location = eventLocation::create(['name' => 'Gedung Kesenian Cirebon', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event1 = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'event A',
            'slug' => 'event-a',
            'description' => 'Deskripsi A.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Approved,
        ]);

        $event2 = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'event B',
            'slug' => 'event-b',
            'description' => 'Deskripsi B.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Approved,
        ]);

        $start = now()->addDays(5)->format('Y-m-d H:i:s');
        $end = now()->addDays(5)->addHours(4)->format('Y-m-d H:i:s');

        eventSchedule::create([
            'event_id' => $event1->id,
            'start_datetime' => $start,
            'end_datetime' => $end,
            'timezone' => 'Asia/Jakarta',
            'status' => ScheduleStatus::Scheduled,
        ]);

        // Try creating overlapping schedule for event2 at same location
        $response = $this->actingAs($user)->post(route('organizer.calendar.store'), [
            'event_id' => $event2->id,
            'start_datetime' => now()->addDays(5)->addHours(1)->format('Y-m-d H:i:s'),
            'end_datetime' => now()->addDays(5)->addHours(3)->format('Y-m-d H:i:s'),
            'timezone' => 'Asia/Jakarta',
        ]);

        $response->assertSessionHasErrors(['error']);
    }
}
