<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\CooperationAgreement;
use App\Enums\SpkStatus;

class SpkModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_view_spk_page(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Ahmad',
            'phone' => '08123456789',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('organizer.spk.index'));

        $response->assertStatus(200);
        $response->assertSee('SPK-');
    }

    public function test_organizer_can_sign_spk_digitally(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Ahmad',
            'phone' => '08123456789',
            'status' => 'pending',
        ]);

        $agreement = CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-00001-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::PendingSignature,
        ]);

        $response = $this->actingAs($user)->post(route('organizer.spk.sign'));

        $response->assertRedirect();
        $this->assertDatabaseHas('cooperation_agreements', [
            'id' => $agreement->id,
            'status' => SpkStatus::Signed->value,
        ]);
    }

    public function test_admin_can_approve_spk(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Ahmad',
            'phone' => '08123456789',
            'status' => 'pending',
        ]);

        $agreement = CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-00002-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Signed,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.spk.approve', $agreement->id));

        $response->assertRedirect(route('admin.spk.index'));
        $this->assertDatabaseHas('cooperation_agreements', [
            'id' => $agreement->id,
            'status' => SpkStatus::Approved->value,
            'approved_by' => $admin->id,
        ]);
    }

    public function test_admin_can_reject_spk(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Cirebon',
            'owner_name' => 'Ahmad',
            'phone' => '08123456789',
            'status' => 'pending',
        ]);

        $agreement = CooperationAgreement::create([
            'organizer_profile_id' => $profile->id,
            'agreement_number' => 'SPK-00003-15PCT',
            'version' => 'v1.0',
            'status' => SpkStatus::Signed,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.spk.reject', $agreement->id), [
            'rejected_reason' => 'Persyaratan belum lengkap.',
        ]);

        $response->assertRedirect(route('admin.spk.index'));
        $this->assertDatabaseHas('cooperation_agreements', [
            'id' => $agreement->id,
            'status' => SpkStatus::Rejected->value,
            'rejected_reason' => 'Persyaratan belum lengkap.',
        ]);
    }
}
