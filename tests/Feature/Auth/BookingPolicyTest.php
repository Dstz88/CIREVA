<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_booking()
    {
        $role = Role::firstOrCreate(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('view', $booking));
    }

    public function test_user_cannot_view_others_booking()
    {
        $role = Role::firstOrCreate(['name' => 'User']);
        $user1 = User::factory()->create(['role_id' => $role->id]);
        $user2 = User::factory()->create(['role_id' => $role->id]);
        $booking = Booking::factory()->create(['user_id' => $user2->id]);

        $this->assertFalse($user1->can('view', $booking));
    }
}
