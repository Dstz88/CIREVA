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
use App\Models\Booking;
use App\Enums\OrganizerStatus;
use App\Enums\SpkStatus;
use App\Enums\eventStatus;
use App\Enums\TicketStatus;
use App\Enums\BookingStatus;

class BookingModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_booking(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $organizerUser = User::factory()->create(['role' => 'organizer']);

        $profile = OrganizerProfile::create([
            'user_id' => $organizerUser->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-BKG-001',
            'version' => 'v1.0',
            'status' => SpkStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Tari', 'slug' => 'tari-bkg']);
        $location = eventLocation::create(['name' => 'Gedung Kesenian', 'address' => 'Jl. Pemuda', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Pertunjukan Tari',
            'slug' => 'pertunjukan-tari-bkg',
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
            'status' => TicketStatus::Active,
        ]);

        $response = $this->actingAs($user)->post(route('user.bookings.store'), [
            'tickets' => [
                [
                    'ticket_id' => $ticket->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertRedirect();

        $ticket->refresh();
        $this->assertEquals(2, $ticket->sold);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'total_amount' => 100000,
            'status' => BookingStatus::Pending->value,
        ]);
    }

    public function test_user_can_view_booking_history(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'BKG1234567',
            'total_amount' => 150000,
            'status' => BookingStatus::Pending,
        ]);

        $response = $this->actingAs($user)->get(route('user.bookings.index'));

        $response->assertStatus(200);
        $response->assertSee('BKG1234567');
    }

    public function test_user_can_cancel_pending_booking(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $organizerUser = User::factory()->create(['role' => 'organizer']);

        $profile = OrganizerProfile::create([
            'user_id' => $organizerUser->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'status' => OrganizerStatus::Approved,
        ]);

        $category = eventCategory::create(['name' => 'Musik', 'slug' => 'musik-bkg']);
        $location = eventLocation::create(['name' => 'Keraton', 'address' => 'Jl. Keraton', 'latitude' => -6.7063, 'longitude' => 108.5570]);

        $event = event::create([
            'organizer_profile_id' => $profile->id,
            'category_id' => $category->id,
            'location_id' => $location->id,
            'title' => 'Konser Musik',
            'slug' => 'konser-musik-bkg',
            'description' => 'Deskripsi.',
            'banner' => 'events/banners/default.jpg',
            'status' => eventStatus::Published,
        ]);

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'name' => 'Tiket Festival',
            'price' => 75000,
            'quota' => 50,
            'sold' => 2,
            'status' => TicketStatus::Active,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'BKG-CANCEL-001',
            'total_amount' => 150000,
            'status' => BookingStatus::Pending,
        ]);

        \App\Models\BookingItem::create([
            'booking_id' => $booking->id,
            'ticket_id' => $ticket->id,
            'quantity' => 2,
            'price' => 75000,
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($user)->delete(route('user.bookings.cancel', $booking->id));

        $response->assertRedirect();

        $ticket->refresh();
        $this->assertEquals(0, $ticket->sold);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => BookingStatus::Cancelled->value,
        ]);
    }
}
