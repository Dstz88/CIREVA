<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\event;
use App\Models\OrganizerProfile;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_manage_own_ticket()
    {
        $role = Role::firstOrCreate(['name' => 'Organizer']);
        $organizer = User::factory()->create(['role_id' => $role->id]);
        $profile = OrganizerProfile::factory()->create(['user_id' => $organizer->id]);

        $event = event::factory()->create(['organizer_profile_id' => $profile->id]);
        $ticket = Ticket::factory()->create(['event_id' => $event->id]);

        $this->assertTrue($organizer->can('update', $ticket));
        $this->assertTrue($organizer->can('delete', $ticket));
    }

    public function test_organizer_cannot_manage_others_ticket()
    {
        $role = Role::firstOrCreate(['name' => 'Organizer']);
        $organizer1 = User::factory()->create(['role_id' => $role->id]);
        $organizer2 = User::factory()->create(['role_id' => $role->id]);

        OrganizerProfile::factory()->create(['user_id' => $organizer1->id]);
        $profile2 = OrganizerProfile::factory()->create(['user_id' => $organizer2->id]);

        $event = event::factory()->create(['organizer_profile_id' => $profile2->id]);
        $ticket = Ticket::factory()->create(['event_id' => $event->id]);

        $this->assertFalse($organizer1->can('update', $ticket));
        $this->assertFalse($organizer1->can('delete', $ticket));
    }
}
