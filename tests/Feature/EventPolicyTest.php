<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\event;
use App\Models\OrganizerProfile;

class eventPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_update_own_event()
    {
        $role = Role::firstOrCreate(['name' => 'Organizer']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $organizer = OrganizerProfile::factory()->create(['user_id' => $user->id]);

        $event = event::factory()->create(['organizer_profile_id' => $organizer->id]);

        $this->actingAs($user);

        // Use Gate facade or direct policy
        $this->assertTrue($user->can('update', $event));
    }

    public function test_organizer_cannot_update_others_event()
    {
        $role = Role::firstOrCreate(['name' => 'Organizer']);

        $user1 = User::factory()->create(['role_id' => $role->id]);
        $organizer1 = OrganizerProfile::factory()->create(['user_id' => $user1->id]);

        $user2 = User::factory()->create(['role_id' => $role->id]);
        $organizer2 = OrganizerProfile::factory()->create(['user_id' => $user2->id]);

        $event = event::factory()->create(['organizer_profile_id' => $organizer2->id]);

        $this->actingAs($user1);
        $this->assertTrue($user1->cannot('update', $event));
    }

    public function test_admin_can_update_any_event()
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $orgRole = Role::firstOrCreate(['name' => 'Organizer']);
        $user2 = User::factory()->create(['role_id' => $orgRole->id]);
        $organizer = OrganizerProfile::factory()->create(['user_id' => $user2->id]);

        $event = event::factory()->create(['organizer_profile_id' => $organizer->id]);

        $this->actingAs($admin);
        $this->assertTrue($admin->can('update', $event));
    }
}
