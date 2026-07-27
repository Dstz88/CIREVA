<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\event;
use App\Models\Ticket;
use App\Enums\eventStatus;
use App\Enums\BookingStatus;

class UserBookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_bookings_page()
    {
        $role = Role::firstOrCreate(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/user/bookings');
        $response->assertStatus(200);
    }

    public function test_user_can_book_ticket()
    {
        $role = Role::firstOrCreate(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $event = event::factory()->create(['status' => eventStatus::Published->value]);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 100000,
            'quota' => 10,
            'sold' => 0,
            'status' => \App\Enums\TicketStatus::Active->value
        ]);

        // Use post() (not postJson) so controller returns redirect, not JSON
        $response = $this->actingAs($user)->post('/user/bookings', [
            '_token' => csrf_token(),
            'tickets' => [
                ['ticket_id' => $ticket->id, 'quantity' => 2]
            ]
        ]);

        // Returns redirect on success (302)
        $response->assertStatus(302);

        $booking = \App\Models\Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals($user->id, $booking->user_id);
        $this->assertEquals(BookingStatus::Pending, $booking->status);
    }
}
