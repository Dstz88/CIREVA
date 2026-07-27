<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\event;

class AdmineventApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_event()
    {
        $role = Role::firstOrCreate(['name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);

        $event = event::factory()->create(['status' => \App\Enums\eventStatus::Submitted->value]);

        // Use web put() (not JSON) so controller returns redirect
        $response = $this->actingAs($admin)->put("/admin/events/{$event->id}/approve");

        $response->assertStatus(302);

        $event->refresh();
        $this->assertEquals(\App\Enums\eventStatus::Approved, $event->status);
    }

    public function test_admin_can_reject_event()
    {
        $role = Role::firstOrCreate(['name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);

        $event = event::factory()->create(['status' => \App\Enums\eventStatus::Submitted->value]);

        $response = $this->actingAs($admin)->put("/admin/events/{$event->id}/reject", [
            'rejection_reason' => 'Perlu penyesuaian deskripsi.',
        ]);

        $response->assertStatus(302);

        $event->refresh();
        $this->assertEquals(\App\Enums\eventStatus::RevisionRequired, $event->status);
    }
}
