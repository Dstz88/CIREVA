<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\OrganizerDocument;
use App\Enums\OrganizerStatus;
use App\Enums\DocumentStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OrganizerModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_view_profile_page(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);

        $response = $this->actingAs($user)->get(route('organizer.profile.show'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_organizer_can_update_profile_data(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Seni Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'address' => 'Jl. Siliwangi No. 10',
            'status' => OrganizerStatus::Pending,
        ]);

        $response = $this->actingAs($user)->put(route('organizer.profile.update'), [
            'organization_name' => 'Sanggar Seni Cirebon Pemuda',
            'phone' => '08987654321',
            'address' => 'Jl. Siliwangi No. 12',
            'description' => 'Sanggar pelestari tari topeng Cirebon.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organizer_profiles', [
            'id' => $profile->id,
            'organization_name' => 'Sanggar Seni Cirebon Pemuda',
            'phone' => '08987654321',
        ]);
    }

    public function test_organizer_can_upload_supporting_document(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'organizer']);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Seni Cirebon',
            'owner_name' => 'Bambang',
            'phone' => '08123456789',
            'address' => 'Jl. Siliwangi No. 10',
            'status' => OrganizerStatus::Pending,
        ]);

        $file = UploadedFile::fake()->create('ktp.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post(route('organizer.documents.store'), [
            'document_type' => 'KTP Penanggung Jawab',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organizer_documents', [
            'organizer_profile_id' => $profile->id,
            'document_type' => 'KTP Penanggung Jawab',
            'verification_status' => 'pending',
        ]);
    }

    public function test_organizer_dashboard_displays_correct_status(): void
    {
        $user = User::factory()->create(['role' => 'organizer']);
        OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Sanggar Tari Cirebon',
            'owner_name' => 'Siti',
            'phone' => '0811111111',
            'status' => OrganizerStatus::Pending,
        ]);

        $response = $this->actingAs($user)->get(route('organizer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('AKUN BELUM DIVERIFIKASI');
    }
}
