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
use App\Models\Ticket;
use App\Enums\OrganizerStatus;
use App\Enums\SpkStatus;
use App\Enums\eventStatus;
use App\Enums\TicketStatus;

class TicketModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_ticket_for_event(): void
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
            'agreement_number' => 'SPK-TKT-001',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Tari', 'slug' => 'tari']);
        $location = eventLocation::create(['name' => 'Gedung Kesenian', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pertunjukan Tari',
            'slug' => 'pertunjukan-tari',
            'description' => 'Deskripsi.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Approved,
        ]);

        $response = $this->actingAs($user)->post(route('organizer.tickets.store'), [
            'event_id' => $event->id,
            'name' => 'Tiket VIP',
            'description' => 'Akses baris depan',
            'price' => 150000,
            'quota' => 50,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'event_id' => $event->id,
            'name' => 'Tiket VIP',
            'quota' => 50,
            'status' => TicketStatus::Inactive->value,
        ]);
    }

    public function test_ticket_cannot_be_activated_if_event_not_published(): void
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
            'agreement_number' => 'SPK-TKT-' . uniqid() . '-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Tari ' . uniqid(), 'slug' => 'tari-' . uniqid()]);
        $location = eventLocation::create(['name' => 'Gedung Kesenian', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pertunjukan Tari Draft',
            'slug' => 'pertunjukan-tari-draft-' . uniqid(),
            'description' => 'Deskripsi.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Draft,
        ]);

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'name' => 'Tiket Biasa',
            'price' => 50000,
            'quota' => 100,
            'sold' => 0,
            'status' => TicketStatus::Inactive,
        ]);

        $response = $this->actingAs($user)->post(route('organizer.tickets.activate', $ticket->id));
        $response->assertSessionHasErrors(['error']);
    }

    public function test_ticket_can_be_activated_when_event_is_published(): void
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
            'agreement_number' => 'SPK-TKT-' . uniqid() . '-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Tari ' . uniqid(), 'slug' => 'tari-' . uniqid()]);
        $location = eventLocation::create(['name' => 'Gedung Kesenian', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pertunjukan Tari Published',
            'slug' => 'pertunjukan-tari-published-' . uniqid(),
            'description' => 'Deskripsi.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Published,
        ]);

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'name' => 'Tiket Utama',
            'price' => 50000,
            'quota' => 100,
            'sold' => 0,
            'status' => TicketStatus::Inactive,
        ]);

        $response = $this->actingAs($user)->post(route('organizer.tickets.activate', $ticket->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => TicketStatus::Active->value,
        ]);
    }
}
